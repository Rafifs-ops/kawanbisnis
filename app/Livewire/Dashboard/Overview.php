<?php

namespace App\Livewire\Dashboard;

use App\Models\BusinessPassport;
use App\Models\GrowthDiagnosis;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Overview extends Component
{
    public ?BusinessPassport $passport = null;

    public ?GrowthDiagnosis $latestDiagnosis = null;

    /** @var Collection<int, GrowthDiagnosis> */
    public Collection $recentDiagnoses;

    /** @var list<array{title: string, date: string|null, status: string}> */
    public array $timelineSteps = [];

    public ?string $activeGoal = null;

    public function mount(): void
    {
        /** @var User $user */
        $user = auth()->user();
        $this->passport = $user->businessPassport;
        $this->recentDiagnoses = new Collection;

        if (! $this->passport) {
            return;
        }

        $this->activeGoal = $this->passport->growthGoals()
            ->where('status', 'active')
            ->latest('id')
            ->value('goal_type');

        $this->recentDiagnoses = GrowthDiagnosis::whereHas('growthGoal', fn ($q) => $q->where('business_passport_id', $this->passport->id))
            ->with(['growthGoal', 'actionPlans'])
            ->latest()
            ->take(3)
            ->get();

        $this->latestDiagnosis = $this->recentDiagnoses->first();

        if ($this->latestDiagnosis) {
            $this->timelineSteps = $this->buildTimeline($this->latestDiagnosis);
        }
    }

    /**
     * Horizontal roadmap data: step title, date, status only.
     *
     * @return list<array{title: string, date: string|null, status: string}>
     */
    private function buildTimeline(GrowthDiagnosis $diagnosis): array
    {
        if ($diagnosis->status !== 'completed') {
            return [];
        }

        $today = now()->format('Y-m-d');
        $steps = [];

        foreach ($diagnosis->actionPlans->sortBy('priority_rank') as $plan) {
            foreach ($plan->steps ?? [] as $step) {
                $date = $step['date'] ?? null;

                if (! $date) {
                    continue;
                }

                $steps[] = [
                    'title' => $step['title'],
                    'date' => $date,
                    'status' => $date < $today ? 'done' : ($date === $today ? 'today' : 'pending'),
                ];
            }
        }

        usort($steps, fn (array $a, array $b) => strcmp($a['date'], $b['date']));

        return $steps;
    }

    public function render(): View
    {
        return view('livewire.dashboard.overview');
    }
}
