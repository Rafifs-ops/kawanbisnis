<?php

namespace App\Models;

use Database\Factories\AgentKnowledgeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Ai\Embeddings;

/**
 * @property int $id
 * @property string $agent_type
 * @property string $title
 * @property string $content
 * @property list<float>|null $embedding
 */
#[Fillable([
    'agent_type',
    'title',
    'content',
    'embedding',
])]
class AgentKnowledge extends Model
{
    /** @use HasFactory<AgentKnowledgeFactory> */
    use HasFactory;

    protected $table = 'agent_knowledges';

    protected $casts = [
        'embedding' => 'array',
    ];

    /**
     * Generate and store embedding for this knowledge entry.
     */
    public function generateEmbedding(): void
    {
        $response = Embeddings::for([$this->content])->generate();

        $this->update(['embedding' => $response->embeddings[0]]);
    }

    /**
     * Find similar knowledge entries using in-memory cosine similarity.
     * Works with any database driver (SQLite, MySQL, Postgres).
     *
     * @return Collection<int, array<string, mixed>>
     */
    public static function findSimilar(string $agentType, string $query, int $limit = 5): Collection
    {
        $response = Embeddings::for([$query])->generate();
        $queryEmbedding = $response->embeddings[0];

        return static::where('agent_type', $agentType)
            ->get()
            ->map(fn (AgentKnowledge $knowledge) => collect($knowledge->toArray())
                ->merge(['similarity' => self::cosineSimilarity($queryEmbedding, $knowledge->embedding ?? [])])
                ->all()
            )
            ->where('similarity', '>', 0.3)
            ->sortByDesc('similarity')
            ->take($limit)
            ->values();
    }

    /**
     * Calculate cosine similarity between two vectors.
     *
     * @param  array<int, float>  $a
     * @param  array<int, float>  $b
     */
    public static function cosineSimilarity(array $a, array $b): float
    {
        if ($a === [] || $b === [] || count($a) !== count($b)) {
            return 0.0;
        }

        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        foreach ($a as $i => $value) {
            $dotProduct += $value * ($b[$i] ?? 0);
            $normA += $value * $value;
            $normB += ($b[$i] ?? 0) * ($b[$i] ?? 0);
        }

        $magnitude = sqrt($normA) * sqrt($normB);

        return $magnitude > 0 ? $dotProduct / $magnitude : 0.0;
    }
}
