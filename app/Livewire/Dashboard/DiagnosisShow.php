<?php

namespace App\Livewire\Dashboard;

use App\Models\GrowthDiagnosis;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('AI Growth Diagnosis')]
class DiagnosisShow extends Component
{
    public GrowthDiagnosis $diagnosis;

    public string $activeTab = 'summary';

    public bool $isProcessing = false;

    /** @var list<array{key: string, name: string, role: string, icon: string, color: string, status: string}> */
    public array $agents = [];

    public int $completedCount = 0;

    private const AGENT_PIPELINE = [
        ['key' => 'analytics', 'name' => 'Analytics Agent', 'role' => 'Menganalisis data & tren', 'icon' => 'chart-bar', 'color' => 'blue'],
        ['key' => 'customer', 'name' => 'Customer Agent', 'role' => 'Memahami perilaku pelanggan', 'icon' => 'users', 'color' => 'emerald'],
        ['key' => 'marketing', 'name' => 'Marketing Agent', 'role' => 'Mengevaluasi channel marketing', 'icon' => 'megaphone', 'color' => 'amber'],
        ['key' => 'strategy', 'name' => 'Strategy Agent', 'role' => 'Merumuskan rekomendasi prioritas', 'icon' => 'light-bulb', 'color' => 'rose'],
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

        $this->isProcessing = $this->diagnosis->summary_diagnosis === 'Sedang diproses...';

        $completedTypes = $this->diagnosis->agentAnalyses->pluck('agent_type')->values()->all();
        $this->completedCount = count($completedTypes);

        $runningFound = false;
        $this->agents = array_map(function (array $agent) use ($completedTypes, &$runningFound): array {
            $status = 'waiting';
            if (in_array($agent['key'], $completedTypes)) {
                $status = 'completed';
            } elseif ($this->isProcessing && ! $runningFound) {
                $status = 'running';
                $runningFound = true;
            }

            return [...$agent, 'status' => $status];
        }, self::AGENT_PIPELINE);
    }

    public function render(): View
    {
        return view('livewire.dashboard.diagnosis.show');
    }
}
