<?php

namespace App\Livewire\Dashboard;

use App\Models\BusinessPassport;
use App\Models\GrowthDiagnosis;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Overview extends Component
{
    public ?BusinessPassport $passport = null;

    public ?GrowthDiagnosis $latestDiagnosis = null;

    public function mount(): void
    {
        /** @var User $user */
        $user = auth()->user();
        $this->passport = $user->businessPassport;

        if ($this->passport) {
            $this->latestDiagnosis = GrowthDiagnosis::whereHas('growthGoal', fn ($q) => $q->where('business_passport_id', $this->passport->id))
                ->with(['actionPlans', 'growthGoal'])
                ->latest()
                ->first();
        }
    }

    public function render(): View
    {
        return view('livewire.dashboard.overview');
    }
}
