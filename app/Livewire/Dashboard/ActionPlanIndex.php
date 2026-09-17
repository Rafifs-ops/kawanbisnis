<?php

namespace App\Livewire\Dashboard;

use App\Models\ActionPlan;
use App\Models\BusinessPassport;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Action Plan')]
class ActionPlanIndex extends Component
{
    /** @var Collection<int, ActionPlan> */
    public Collection $actionPlans;

    /** @var array<int, array{id: int, number: int, created_at: string, plans: Collection<int, ActionPlan>}> */
    public array $groupedPlans = [];

    public function mount(): void
    {
        /** @var User $user */
        $user = auth()->user();
        /** @var BusinessPassport|null $passport */
        $passport = $user->businessPassport;

        if ($passport) {
            $this->actionPlans = ActionPlan::whereHas('growthDiagnosis.growthGoal', fn ($q) => $q->where('business_passport_id', $passport->id))
                ->with('growthDiagnosis')
                ->orderBy('growth_diagnosis_id')
                ->orderBy('priority_rank')
                ->get();

            $this->groupedPlans = $this->groupByDiagnosis($this->actionPlans);
        } else {
            $this->actionPlans = new Collection;
            $this->groupedPlans = [];
        }
    }

    /**
     * @param  Collection<int, ActionPlan>  $plans
     * @return array<int, array{id: int, number: int, created_at: string, plans: Collection<int, ActionPlan>}>
     */
    private function groupByDiagnosis(Collection $plans): array
    {
        $grouped = $plans->groupBy('growth_diagnosis_id');
        $result = [];
        $number = $grouped->count();

        foreach ($grouped->reverse() as $diagnosisId => $diagnosisPlans) {
            $result[] = [
                'id' => $diagnosisId,
                'number' => $number,
                'created_at' => $diagnosisPlans->first()->growthDiagnosis?->created_at?->format('d M Y H:i') ?? '-',
                'plans' => $diagnosisPlans->sortBy('priority_rank'),
            ];
            $number--;
        }

        return $result;
    }

    public function render(): View
    {
        return view('livewire.dashboard.action-plan.index');
    }
}
