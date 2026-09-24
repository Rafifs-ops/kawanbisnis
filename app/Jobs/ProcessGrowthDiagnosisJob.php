<?php

namespace App\Jobs;

use App\AI\Agents\AnalyticsAgent;
use App\AI\Agents\CustomerAgent;
use App\AI\Agents\MarketingAgent;
use App\AI\Agents\StrategyAgent;
use App\Models\ActionPlan;
use App\Models\AgentAnalysis;
use App\Models\BusinessPassport;
use App\Models\GrowthDiagnosis;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Contracts\Agent;
use Throwable;

class ProcessGrowthDiagnosisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 300;

    public function __construct(
        public GrowthDiagnosis $diagnosis,
    ) {}

    public function handle(): void
    {
        // Clear partial results from prior attempts so retries stay idempotent.
        $this->diagnosis->agentAnalyses()->delete();
        $this->diagnosis->actionPlans()->delete();
        $this->diagnosis->update(['status' => 'processing']);

        /** @var BusinessPassport $passport */
        $passport = $this->diagnosis->growthGoal->businessPassport;
        $snapshot = $passport->snapshots()->latest()->first();

        if (! $snapshot) {
            $this->diagnosis->update([
                'status' => 'failed',
                'summary_diagnosis' => 'Data snapshot tidak ditemukan untuk diagnosis ini.',
            ]);

            return;
        }

        // 1. Execute Analytics Agent
        $analyticsAgent = new AnalyticsAgent($snapshot);
        $analyticsRes = $this->promptAgent($analyticsAgent, $analyticsAgent->buildPrompt(), 'analytics');

        AgentAnalysis::create([
            'growth_diagnosis_id' => $this->diagnosis->id,
            'agent_type' => 'analytics',
            'question_answered' => 'Apa yang berubah dari data bisnis ini?',
            'findings' => [
                'summary' => $analyticsRes['summary'],
                'anomalies' => $analyticsRes['anomalies'],
                'key_findings' => $analyticsRes['key_findings'],
            ],
            'confidence_score' => $analyticsRes['confidence_score'],
        ]);

        // 2. Execute Customer Agent
        $customerAgent = new CustomerAgent($snapshot);
        $customerRes = $this->promptAgent($customerAgent, $customerAgent->buildPrompt(), 'customer');

        AgentAnalysis::create([
            'growth_diagnosis_id' => $this->diagnosis->id,
            'agent_type' => 'customer',
            'question_answered' => 'Bagaimana perilaku pelanggan ini dan apa yang bisa diperbaiki?',
            'findings' => [
                'summary' => $customerRes['summary'],
                'segments' => $customerRes['segments'],
                'insights' => $customerRes['insights'],
            ],
            'hypotheses' => $customerRes['hypotheses'],
            'confidence_score' => $customerRes['confidence_score'],
        ]);

        // 3. Execute Marketing Agent
        $marketingAgent = new MarketingAgent($snapshot, $passport);
        $marketingRes = $this->promptAgent($marketingAgent, $marketingAgent->buildPrompt(), 'marketing');

        AgentAnalysis::create([
            'growth_diagnosis_id' => $this->diagnosis->id,
            'agent_type' => 'marketing',
            'question_answered' => 'Channel mana yang paling efektif dan mana yang perlu diperbaiki?',
            'findings' => [
                'summary' => $marketingRes['summary'],
                'channel_performance' => $marketingRes['channel_performance'],
                'opportunities' => $marketingRes['opportunities'],
            ],
            'hypotheses' => $marketingRes['hypotheses'],
            'confidence_score' => $marketingRes['confidence_score'],
        ]);

        // 4. Execute Strategy Agent with combined findings
        $multiAgentFindings = [
            'analytics' => $analyticsRes['key_findings'],
            'customer' => $customerRes['insights'],
            'marketing' => $marketingRes['opportunities'],
        ];

        $strategyAgent = new StrategyAgent($multiAgentFindings, $passport->constraints ?? []);
        $strategyRes = $this->promptAgent($strategyAgent, $strategyAgent->buildPrompt(), 'strategy');

        AgentAnalysis::create([
            'growth_diagnosis_id' => $this->diagnosis->id,
            'agent_type' => 'strategy',
            'question_answered' => 'Apa rekomendasi prioritas berdasarkan semua temuan?',
            'findings' => [
                'summary' => $strategyRes['business_diagnosis'] ?? '',
                'root_causes' => $strategyRes['root_causes'] ?? [],
                'opportunities' => $strategyRes['growth_opportunity'] ?? [],
                'kpi_metrics' => $strategyRes['kpi_metrics'] ?? [],
            ],
            'confidence_score' => $strategyRes['confidence_score'] ?? 0.0,
        ]);

        // 5. Save Top 3 Action Plans from recommendations
        $today = Carbon::today();
        $recommendations = $strategyRes['recommendations'] ?? [];
        foreach ($recommendations as $index => $recommendation) {
            $steps = $this->buildStepsWithDates($recommendation['steps'] ?? [], $today);

            ActionPlan::create([
                'growth_diagnosis_id' => $this->diagnosis->id,
                'title' => $recommendation['title'],
                'description' => $recommendation['description'],
                'priority_rank' => $index + 1,
                'priority_score' => $recommendation['priority_score'],
                'timeline_days' => $this->resolveTimeline($strategyRes, $index),
                'target_kpi' => $this->resolveTargetKpi($strategyRes, $index),
                'steps' => $steps,
                'approval_status' => 'pending',
            ]);
        }

        // 6. Update diagnosis with all 6 sections
        $this->diagnosis->update([
            'status' => 'completed',
            'summary_diagnosis' => $strategyRes['business_diagnosis'] ?? '',
            'business_diagnosis' => $strategyRes['business_diagnosis'] ?? null,
            'root_causes' => $strategyRes['root_causes'] ?? null,
            'opportunities' => $strategyRes['growth_opportunity'] ?? null,
            'kpi_metrics' => $strategyRes['kpi_metrics'] ?? null,
        ]);
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('Growth diagnosis job failed permanently', [
            'diagnosis_id' => $this->diagnosis->id,
            'error' => $exception?->getMessage(),
        ]);

        $this->diagnosis->update([
            'status' => 'failed',
            'summary_diagnosis' => 'Proses diagnosis gagal. Silakan coba lagi.',
        ]);
    }

    /**
     * @param  array<string, mixed>  $strategyRes
     */
    private function resolveTimeline(array $strategyRes, int $index): int
    {
        $actionPlan = $strategyRes['action_plan'] ?? null;

        if ($actionPlan === null) {
            return 30;
        }

        $shortTerm = $actionPlan['short_term'] ?? [];
        $longTerm = $actionPlan['long_term'] ?? [];

        if ($index < count($shortTerm)) {
            return 7;
        }

        return 30;
    }

    /**
     * @param  array<string, mixed>  $strategyRes
     * @return array{metric: string}|null
     */
    private function resolveTargetKpi(array $strategyRes, int $index): ?array
    {
        $kpiMetrics = $strategyRes['kpi_metrics'] ?? [];

        if (isset($kpiMetrics[$index])) {
            return ['metric' => $kpiMetrics[$index]['metric']];
        }

        return null;
    }

    /**
     * Build steps with calculated dates from day_offset.
     *
     * @param  list<array{day_offset: int, title: string, description: string}>  $rawSteps
     * @return list<array{day_offset: int, title: string, description: string, date: string, day_name: string}>
     */
    private function buildStepsWithDates(array $rawSteps, Carbon $today): array
    {
        return array_map(function (array $step) use ($today): array {
            $date = $today->copy()->addDays($step['day_offset']);

            return [
                'day_offset' => $step['day_offset'],
                'title' => $step['title'],
                'description' => $step['description'],
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->translatedFormat('dddd'),
            ];
        }, $rawSteps);
    }

    /**
     * @return array<string, mixed>
     */
    private function promptAgent(Agent $agent, string $prompt, string $agentType): array
    {
        try {
            $response = $agent->prompt($prompt);

            $decoded = json_decode($response->text, true, 512, JSON_THROW_ON_ERROR);

            if (! is_array($decoded)) {
                throw new \InvalidArgumentException("Agent {$agentType} returned non-array response");
            }

            return $decoded;
        } catch (Throwable $e) {
            Log::error("Agent {$agentType} failed", [
                'diagnosis_id' => $this->diagnosis->id,
                'error' => $e->getMessage(),
            ]);

            // Mark failed on the final attempt so the UI can notify the user.
            // Earlier queue retries reset status to processing at the start of handle().
            if ($this->attempts() >= $this->tries) {
                $this->diagnosis->update([
                    'status' => 'failed',
                    'summary_diagnosis' => 'Proses diagnosis gagal. Silakan coba lagi.',
                ]);
            }

            throw $e;
        }
    }
}
