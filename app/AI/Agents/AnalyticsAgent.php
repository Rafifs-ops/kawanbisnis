<?php

namespace App\AI\Agents;

use App\Models\AgentKnowledge;
use App\Models\BusinessSnapshot;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Collection;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Laravel\Ai\Tools\SimilaritySearch;

class AnalyticsAgent implements Agent, HasStructuredOutput, HasTools
{
    use Promptable;

    public function __construct(
        public BusinessSnapshot $snapshot,
    ) {}

    public function instructions(): string
    {
        return <<<'TEXT'
Kamu adalah Analytics Agent dalam AI Growth Team untuk UMKM Indonesia.
Tugasmu adalah menganalisis angka, tren, dan anomali dari data bisnis.
Gunakan tool similarity_search untuk mencari knowledge base sebelum menarik kesimpulan.
Pertanyaan yang harus dijawab: "Apa yang berubah dari data bisnis ini?"
Jawab secara ringkas, analitis, dan faktual dalam Bahasa Indonesia.
TEXT;
    }

    public function tools(): iterable
    {
        return [
            (new SimilaritySearch(using: function (string $query): Collection {
                return AgentKnowledge::findSimilar('analytics', $query, 3);
            }))->withDescription('Cari knowledge base analytics untuk benchmark, penyebab anomali, dan aturan metrik bisnis.'),
        ];
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'summary' => $schema->string()->required(),
            'anomalies' => $schema->array()->items($schema->string())->required(),
            'key_findings' => $schema->array()->items($schema->string())->required(),
            'confidence_score' => $schema->number()->required(),
        ];
    }

    public function buildPrompt(): string
    {
        return sprintf(
            'Revenue: Rp%s, Total Orders: %d, AOV: Rp%s. Customer Mix: %s. Product Performances: %s',
            number_format((float) $this->snapshot->revenue),
            $this->snapshot->total_orders,
            number_format((float) $this->snapshot->average_order_value),
            json_encode($this->snapshot->new_vs_returning_customers),
            json_encode($this->snapshot->product_performances)
        );
    }
}
