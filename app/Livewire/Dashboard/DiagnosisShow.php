<?php

namespace App\Livewire\Dashboard;

use App\Jobs\ProcessGrowthDiagnosisJob;
use App\Models\GrowthDiagnosis;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('AI Growth Diagnosis')]
class DiagnosisShow extends Component
{
    public GrowthDiagnosis $diagnosis;

    public string $activeTab = 'summary';

    public bool $isProcessing = false;

    public bool $isFailed = false;

    /** @var list<array{key: string, name: string, role: string, icon: string, color: string, status: string}> */
    public array $agents = [];

    public int $completedCount = 0;

    private const AGENT_PIPELINE = [
        ['key' => 'analytics', 'name' => 'Analisis Penjualan', 'role' => 'Menganalisis data & tren penjualan', 'icon' => 'chart-bar', 'color' => 'blue'],
        ['key' => 'customer', 'name' => 'Analisis Pelanggan', 'role' => 'Memahami perilaku pelanggan', 'icon' => 'users', 'color' => 'emerald'],
        ['key' => 'marketing', 'name' => 'Analisis Pemasaran', 'role' => 'Mengevaluasi channel pemasaran', 'icon' => 'megaphone', 'color' => 'amber'],
        ['key' => 'strategy', 'name' => 'Penyusunan Strategi', 'role' => 'Merumuskan rekomendasi prioritas', 'icon' => 'light-bulb', 'color' => 'rose'],
    ];

    public function mount(GrowthDiagnosis $growthDiagnosis): void
    {
        $this->diagnosis = $growthDiagnosis->load([
            'growthGoal.businessPassport',
            'agentAnalyses',
            'actionPlans',
        ]);

        $this->refreshStatus();
    }

    public function refreshStatus(): void
    {
        $this->diagnosis->refresh()->load(['agentAnalyses', 'actionPlans']);

        $this->isFailed = $this->diagnosis->status === 'failed';
        $this->isProcessing = $this->diagnosis->status === 'processing';

        $pipelineKeys = array_column(self::AGENT_PIPELINE, 'key');

        // Unique pipeline agent types only — retries must not inflate the count.
        $completedKeys = $this->diagnosis->agentAnalyses
            ->pluck('agent_type')
            ->unique()
            ->intersect($pipelineKeys)
            ->values();

        $this->completedCount = $completedKeys->count();

        $runningFound = false;
        $this->agents = array_map(function (array $agent) use ($completedKeys, &$runningFound): array {
            $status = 'waiting';

            if ($completedKeys->contains($agent['key'])) {
                $status = 'completed';
            } elseif ($this->isFailed) {
                $status = 'failed';
            } elseif ($this->isProcessing && ! $runningFound) {
                $status = 'running';
                $runningFound = true;
            }

            return [...$agent, 'status' => $status];
        }, self::AGENT_PIPELINE);
    }

    public function retry(): void
    {
        if ($this->diagnosis->status !== 'failed') {
            return;
        }

        $this->diagnosis->agentAnalyses()->delete();
        $this->diagnosis->actionPlans()->delete();

        $this->diagnosis->update([
            'status' => 'processing',
            'summary_diagnosis' => 'Sedang diproses...',
            'business_diagnosis' => null,
            'key_findings' => null,
            'root_causes' => null,
            'opportunities' => null,
            'kpi_metrics' => null,
        ]);

        ProcessGrowthDiagnosisJob::dispatch($this->diagnosis);

        Flux::toast(variant: 'success', text: 'Diagnosis diulang. AI Growth Team sedang menganalisis kembali bisnis Anda.');

        $this->refreshStatus();
    }

    public function render(): View
    {
        return view('livewire.dashboard.diagnosis.show');
    }
}
