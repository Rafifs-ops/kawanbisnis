<?php

namespace App\Livewire\Dashboard;

use App\Models\ActionPlan;
use App\Models\AgentKnowledge;
use App\Models\CheckInFeedback;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Component;
use Throwable;

#[Title('Tandai Selesai')]
class CheckInCreate extends Component
{
    public ActionPlan $actionPlan;

    public ?CheckInFeedback $feedback = null;

    public string $checkin_date = '';

    public string $actual_result = '';

    public bool $kpi_achieved = false;

    public string $learning_notes = '';

    public function mount(ActionPlan $actionPlan): void
    {
        $this->actionPlan = $actionPlan->load('growthDiagnosis.growthGoal.businessPassport');
        $this->feedback = $actionPlan->checkInFeedbacks()->latest('id')->first();
        $this->checkin_date = now()->format('Y-m-d');
    }

    public function submit(): void
    {
        $validated = $this->validate([
            'checkin_date' => 'required|date',
            'actual_result' => 'required|string|max:1000',
            'learning_notes' => 'nullable|string|max:1000',
        ]);

        $feedback = CheckInFeedback::create([
            'action_plan_id' => $this->actionPlan->id,
            'checkin_date' => $validated['checkin_date'],
            'actual_result' => ['result' => $validated['actual_result']],
            'kpi_achieved' => [
                'metric' => $this->actionPlan->target_kpi['metric'] ?? '',
                'achieved' => $this->kpi_achieved,
            ],
            'learning_notes' => $validated['learning_notes'] ?? null,
        ]);

        $this->saveAsKnowledge($feedback);

        Flux::toast(variant: 'success', text: 'Berhasil disimpan! Hasil eksekusi telah ditambahkan sebagai knowledge untuk analisis AI berikutnya.');

        $this->redirect(route('check-in.create', $this->actionPlan));
    }

    private function saveAsKnowledge(CheckInFeedback $feedback): void
    {
        try {
            $plan = $this->actionPlan;
            $passport = $plan->growthDiagnosis->growthGoal->businessPassport;

            $kpiStatus = ($feedback->kpi_achieved['achieved'] ?? false) ? 'Tercapai' : 'Belum Tercapai';
            $kpiMetric = $feedback->kpi_achieved['metric'] ?? '-';

            $content = sprintf(
                'Action Plan: %s. Deskripsi: %s. Timeline: %d hari. KPI Target: %s. Hasil Aktual: %s. Status KPI: %s (%s). Catatan Belajar: %s. Bisnis: %s (%s).',
                $plan->title,
                $plan->description,
                $plan->timeline_days,
                $kpiMetric,
                $feedback->actual_result['result'] ?? '-',
                $kpiStatus,
                $kpiMetric,
                $feedback->learning_notes ?? '-',
                $passport->business_name ?? '-',
                $passport->business_type ?? '-'
            );

            $title = sprintf('Hasil Eksekusi: %s', $plan->title);

            $knowledge = AgentKnowledge::create([
                'agent_type' => 'strategy',
                'title' => $title,
                'content' => $content,
            ]);

            $knowledge->generateEmbedding();
        } catch (Throwable $e) {
            Log::warning('Gagal menyimpan check-in sebagai knowledge', [
                'action_plan_id' => $plan->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function render(): View
    {
        return view('livewire.dashboard.check-in.create');
    }
}
