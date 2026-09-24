<?php

namespace App\Livewire\Dashboard;

use App\Jobs\ProcessGrowthDiagnosisJob;
use App\Models\BusinessPassport;
use App\Models\BusinessSnapshot;
use App\Models\GrowthDiagnosis;
use App\Models\GrowthGoal;
use App\Models\User;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Data Snapshot & Goal')]
class SnapshotCreate extends Component
{
    public string $period_start = '';

    public string $period_end = '';

    public ?float $revenue = 0;

    public ?int $total_orders = 0;

    public int $new_customers = 0;

    public int $returning_customers = 0;

    public string $goal_type = 'Increase Sales';

    public float $target_value = 0;

    public bool $isProcessing = false;

    #[Computed]
    public function averageOrderValue(): float
    {
        return $this->calculateAverageOrderValue();
    }

    private function calculateAverageOrderValue(): float
    {
        // Livewire unsets typed props when the client sends '' (cleared number input).
        $totalOrders = $this->total_orders ?? 0;
        $revenue = $this->revenue ?? 0;

        if ($totalOrders <= 0) {
            return 0;
        }

        return round($revenue / $totalOrders);
    }

    public function mount(): void
    {
        $this->period_start = now()->subMonths(3)->startOfMonth()->format('Y-m-d');
        $this->period_end = now()->subMonth()->endOfMonth()->format('Y-m-d');
    }

    public function updatedGoalType(): void
    {
        $this->target_value = 0;
        $this->resetValidation('target_value');
    }

    public function isTargetInputHidden(): bool
    {
        return $this->goal_type === 'Retention';
    }

    public function targetUnit(): string
    {
        return $this->goal_type === 'Margin' ? 'percent' : 'nominal';
    }

    public function submit(): void
    {
        $validated = $this->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'revenue' => 'required|numeric|min:0',
            'total_orders' => 'required|integer|min:0',
            'goal_type' => 'required|in:Increase Sales,Retention,AOV,Margin',
            'target_value' => $this->isTargetInputHidden()
                ? 'nullable|numeric|min:0'
                : 'required|numeric|min:0',
        ]);

        /** @var User $user */
        $user = auth()->user();
        /** @var BusinessPassport|null $passport */
        $passport = $user->businessPassport;

        if (! $passport) {
            Flux::toast(variant: 'error', text: 'Silakan lengkapi Profil Bisnis terlebih dahulu.');

            return;
        }

        // Create snapshot
        /** @var BusinessSnapshot $snapshot */
        $snapshot = BusinessSnapshot::create([
            'business_passport_id' => $passport->id,
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'revenue' => $validated['revenue'],
            'total_orders' => $validated['total_orders'],
            'average_order_value' => $this->calculateAverageOrderValue(),
            'new_vs_returning_customers' => [
                'new' => $this->new_customers,
                'returning' => $this->returning_customers,
            ],
        ]);

        // Create growth goal
        /** @var GrowthGoal $goal */
        $goal = GrowthGoal::create([
            'business_passport_id' => $passport->id,
            'goal_type' => $validated['goal_type'],
            'target_metrics' => $this->isTargetInputHidden()
                ? null
                : [
                    'target' => $validated['target_value'],
                    'unit' => $this->targetUnit(),
                ],
            'status' => 'active',
        ]);

        // Create diagnosis
        /** @var GrowthDiagnosis $diagnosis */
        $diagnosis = GrowthDiagnosis::create([
            'growth_goal_id' => $goal->id,
            'status' => 'processing',
            'summary_diagnosis' => 'Sedang diproses...',
        ]);

        // Dispatch AI processing job
        ProcessGrowthDiagnosisJob::dispatch($diagnosis);

        Flux::toast(variant: 'success', text: 'Data berhasil dikirim! AI Growth Team sedang menganalisis bisnis Anda.');

        $this->redirect(route('diagnosis.show', $diagnosis));
    }

    public function render(): View
    {
        return view('livewire.dashboard.snapshot.create');
    }
}
