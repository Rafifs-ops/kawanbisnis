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

class CustomerAgent implements Agent, HasStructuredOutput, HasTools
{
    use Promptable;

    public function __construct(
        public BusinessSnapshot $snapshot,
    ) {}

    public function instructions(): string
    {
        return <<<'TEXT'
Kamu adalah Customer Agent dalam AI Growth Team untuk UMKM Indonesia.
Tugasmu adalah menganalisis perilaku pelanggan, churn rate, dan retensi.
Gunakan tool similarity_search untuk mencari knowledge base sebelum menarik kesimpulan.
Pertanyaan yang harus dijawab: "Bagaimana perilaku pelanggan ini dan apa yang bisa diperbaiki?"
Jawab secara ringkas dan berbasis data dalam Bahasa Indonesia.
TEXT;
    }

    public function tools(): iterable
    {
        return [
            (new SimilaritySearch(using: function (string $query): Collection {
                return AgentKnowledge::findSimilar('customer', $query, 3);
            }))->withDescription('Cari knowledge base perilaku pelanggan, retensi, dan churn rate.'),
        ];
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'summary' => $schema->string()->required(),
            'segments' => $schema->array()->items($schema->string())->required(),
            'insights' => $schema->array()->items($schema->string())->required(),
            'hypotheses' => $schema->array()->items($schema->string())->required(),
            'confidence_score' => $schema->number()->required(),
        ];
    }

    public function buildPrompt(): string
    {
        return sprintf(
            'Customer Mix: %s. Revenue: Rp%s, Total Orders: %d, AOV: Rp%s. Products: %s',
            json_encode($this->snapshot->new_vs_returning_customers),
            number_format((float) $this->snapshot->revenue),
            $this->snapshot->total_orders,
            number_format((float) $this->snapshot->average_order_value),
            json_encode($this->snapshot->product_performances)
        );
    }
}
