<?php

namespace App\Livewire\Dashboard;

use App\Models\BusinessPassport;
use App\Models\GrowthDiagnosis;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Riwayat Diagnosis')]
class DiagnosisHistory extends Component
{
    /** @var Collection<int, GrowthDiagnosis> */
    public Collection $diagnoses;

    public function mount(): void
    {
        /** @var User $user */
        $user = auth()->user();
        /** @var BusinessPassport|null $passport */
        $passport = $user->businessPassport;

        $this->diagnoses = $passport
            ? GrowthDiagnosis::whereHas('growthGoal', fn ($q) => $q->where('business_passport_id', $passport->id))
                ->with(['growthGoal', 'actionPlans'])
                ->latest()
                ->get()
            : new Collection;
    }

    public function render(): View
    {
        return view('livewire.dashboard.diagnosis.history');
    }
}
