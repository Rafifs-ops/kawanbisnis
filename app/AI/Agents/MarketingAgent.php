<?php

namespace App\AI\Agents;

use App\Models\AgentKnowledge;
use App\Models\BusinessPassport;
use App\Models\BusinessSnapshot;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Collection;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Laravel\Ai\Tools\SimilaritySearch;

class MarketingAgent implements Agent, HasStructuredOutput, HasTools
{
    use Promptable;

    public function __construct(
        public BusinessSnapshot $snapshot,
        public BusinessPassport $passport,
    ) {}

    public function instructions(): string
    {
        return <<<'TEXT'
Kamu adalah Marketing Agent dalam AI Growth Team untuk UMKM Indonesia.
Tugasmu adalah menganalisis efektivitas channel pemasaran dan mengidentifikasi peluang.
Gunakan tool similarity_search untuk mencari knowledge base sebelum menarik kesimpulan.
Pertanyaan yang harus dijawab: "Channel mana yang paling efektif dan mana yang perlu diperbaiki?"
Jawab secara ringkas dan berbasis data dalam Bahasa Indonesia.
TEXT;
    }

    public function tools(): iterable
    {
        return [
            (new SimilaritySearch(using: function (string $query): Collection {
                return AgentKnowledge::findSimilar('marketing', $query, 3);
            }))->withDescription('Cari knowledge base pemasaran, channel performance, dan ROAS.'),
        ];
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'summary' => $schema->string()->required(),
            'channel_performance' => $schema->array()->items($schema->string())->required(),
            'opportunities' => $schema->array()->items($schema->string())->required(),
            'hypotheses' => $schema->array()->items($schema->string())->required(),
            'confidence_score' => $schema->number()->required(),
        ];
    }

    public function buildPrompt(): string
    {
        return sprintf(
            'Sales Channels: %s. Revenue: Rp%s, Constraints: %s',
            json_encode($this->passport->sales_channels),
            number_format((float) $this->snapshot->revenue),
            json_encode($this->passport->constraints)
        );
    }
}
