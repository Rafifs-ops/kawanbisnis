<?php

namespace App\AI\Agents;

use App\Models\AgentKnowledge;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Laravel\Ai\Tools\SimilaritySearch;

class StrategyAgent implements Agent, HasStructuredOutput, HasTools
{
    use Promptable;

    /**
     * @param  array<string, mixed>  $multiAgentFindings
     * @param  array<string, mixed>  $constraints
     */
    public function __construct(
        public array $multiAgentFindings,
        public array $constraints,
    ) {}

    public function instructions(): string
    {
        $today = Carbon::now('Asia/Jakarta')->translatedFormat('dddd, D MMMM YYYY');

        return <<<TEXT
Kamu adalah Strategy Agent dalam AI Growth Team untuk UMKM Indonesia.
Tugasmu adalah mensintesis temuan dari agen Analytics, Customer, dan Marketing, lalu memberikan diagnosis lengkap dalam Bahasa Indonesia.

Hari ini adalah {$today}. Gunakan tanggal ini sebagai referensi untuk menjadwalkan langkah-langkah action plan.

Kamu harus menghasilkan output dengan 6 bagian:
1. business_diagnosis: Identifikasi masalah utama bisnis secara singkat dan jelas.
2. root_causes: Daftar alasan mengapa masalah tersebut bisa terjadi (2-4 item).
3. growth_opportunity: Peluang terbesar yang bisa dimanfaatkan untuk pertumbuhan (2-4 item).
4. recommendations: Top 3 rekomendasi solusi yang paling prioritas. Setiap rekomendasi HARUS memiliki:
   - title: Judul rekomendasi
   - description: Penjelasan singkat rekomendasi
   - priority_score: Skor prioritas (1-10)
   - steps: Array langkah-langkah detail yang harus dilakukan. Setiap langkah harus memiliki:
     * day_offset: Angka hari dari sekarang (0 = hari ini, 1 = besok, dst). Urutkan dari yang terkecil.
     * title: Judul langkah
     * description: Penjelasan detail apa yang harus dilakukan
   Minimal 3 langkah per rekomendasi, maksimal 7 langkah. Buat langkah yang spesifik dan actionable.
5. action_plan: Rencana aksi dalam 2 timeline: short_term (<=7 hari) dan long_term (>7 hari). Berisi array of strings ringkas.
6. kpi_metrics: Daftar metrik yang harus dipantau untuk mengukur keberhasilan aksi, masing-masing dengan nama metrik, target, dan unit.

Contoh steps yang baik:
- day_offset: 0, title: "Riset kompetitor", description: "Buka Instagram, cari 5 kompetitor sejenis, catat harga dan promosi mereka"
- day_offset: 1, title: "Buat konten promosi", description: "Siapkan 3 foto produk dengan copywriting yang sudah diriset"
- day_offset: 3, title: "Posting dan boost", description: "Upload konten ke Instagram, budget Rp50.000 untuk boost 3 hari"

Gunakan Prioritization Framework: Score = Expected Impact x Feasibility x Confidence.
Gunakan tool similarity_search untuk mencari knowledge base sebelum membuat rencana.
Jawab dalam Bahasa Indonesia.
TEXT;
    }

    public function tools(): iterable
    {
        return [
            (new SimilaritySearch(using: function (string $query): Collection {
                return AgentKnowledge::findSimilar('strategy', $query, 3);
            }))->withDescription('Cari knowledge base strategi, scoring prioritas, dan framework UMKM.'),
        ];
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'business_diagnosis' => $schema->string()->required(),
            'root_causes' => $schema->array()->items($schema->string())->required(),
            'growth_opportunity' => $schema->array()->items($schema->string())->required(),
            'recommendations' => $schema->array()->items(
                $schema->object(fn (JsonSchema $schema) => [
                    'title' => $schema->string()->required(),
                    'description' => $schema->string()->required(),
                    'priority_score' => $schema->number()->required(),
                    'steps' => $schema->array()->items(
                        $schema->object(fn (JsonSchema $schema) => [
                            'day_offset' => $schema->integer()->required(),
                            'title' => $schema->string()->required(),
                            'description' => $schema->string()->required(),
                        ])->required()
                    )->required(),
                ])->required()
            )->required(),
            'action_plan' => $schema->object(fn (JsonSchema $schema) => [
                'short_term' => $schema->array()->items($schema->string())->required(),
                'long_term' => $schema->array()->items($schema->string())->required(),
            ])->required(),
            'kpi_metrics' => $schema->array()->items(
                $schema->object(fn (JsonSchema $schema) => [
                    'metric' => $schema->string()->required(),
                    'target' => $schema->string()->required(),
                    'unit' => $schema->string()->required(),
                ])->required()
            )->required(),
        ];
    }

    public function buildPrompt(): string
    {
        return sprintf(
            'Multi-Agent Findings: %s. Business Constraints: %s',
            json_encode($this->multiAgentFindings),
            json_encode($this->constraints)
        );
    }
}
