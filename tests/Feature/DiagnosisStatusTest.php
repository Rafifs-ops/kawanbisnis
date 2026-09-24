<?php

use App\AI\Agents\AnalyticsAgent;
use App\AI\Agents\CustomerAgent;
use App\AI\Agents\MarketingAgent;
use App\AI\Agents\StrategyAgent;
use App\Jobs\ProcessGrowthDiagnosisJob;
use App\Livewire\Dashboard\DiagnosisShow;
use App\Models\AgentAnalysis;
use App\Models\BusinessPassport;
use App\Models\BusinessSnapshot;
use App\Models\GrowthDiagnosis;
use App\Models\GrowthGoal;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

function makeDiagnosis(array $attributes = []): GrowthDiagnosis
{
    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);

    return GrowthDiagnosis::factory()->create(array_merge([
        'growth_goal_id' => $goal->id,
    ], $attributes));
}

function fakeAgentsForSuccess(): void
{
    AnalyticsAgent::fake([['summary' => 'OK', 'anomalies' => [], 'key_findings' => ['A'], 'confidence_score' => 0.8]]);
    CustomerAgent::fake([['summary' => 'OK', 'segments' => [], 'insights' => ['B'], 'hypotheses' => [], 'confidence_score' => 0.8]]);
    MarketingAgent::fake([['summary' => 'OK', 'channel_performance' => [], 'opportunities' => [], 'hypotheses' => [], 'confidence_score' => 0.8]]);
    StrategyAgent::fake([[
        'business_diagnosis' => 'Selesai',
        'root_causes' => ['Masalah'],
        'growth_opportunity' => ['Peluang'],
        'recommendations' => [],
        'action_plan' => ['short_term' => [], 'long_term' => []],
        'kpi_metrics' => [],
        'confidence_score' => 0.8,
    ]]);
}

it('marks diagnosis completed after successful job run', function () {
    fakeAgentsForSuccess();

    $diagnosis = makeDiagnosis([
        'status' => 'processing',
        'summary_diagnosis' => 'Sedang diproses...',
    ]);
    BusinessSnapshot::factory()->create([
        'business_passport_id' => $diagnosis->growthGoal->business_passport_id,
    ]);

    ProcessGrowthDiagnosisJob::dispatchSync($diagnosis);

    expect($diagnosis->fresh()->status)->toBe('completed');
});

it('marks diagnosis failed when job fails permanently', function () {
    $diagnosis = makeDiagnosis([
        'status' => 'processing',
        'summary_diagnosis' => 'Sedang diproses...',
    ]);

    (new ProcessGrowthDiagnosisJob($diagnosis))->failed(new RuntimeException('AI down'));

    $fresh = $diagnosis->fresh();
    expect($fresh->status)->toBe('failed')
        ->and($fresh->summary_diagnosis)->toContain('gagal');
});

it('does not inflate completed count when agent analysis rows are duplicated', function () {
    $diagnosis = makeDiagnosis([
        'status' => 'processing',
        'summary_diagnosis' => 'Sedang diproses...',
    ]);

    foreach (['analytics', 'analytics', 'analytics', 'customer'] as $type) {
        AgentAnalysis::factory()->create([
            'growth_diagnosis_id' => $diagnosis->id,
            'agent_type' => $type,
        ]);
    }

    $component = Livewire::test(DiagnosisShow::class, ['growthDiagnosis' => $diagnosis])
        ->call('refreshStatus')
        ->assertSet('completedCount', 2)
        ->assertSet('isProcessing', true);

    $agents = $component->get('agents');
    $statuses = collect($agents)->mapWithKeys(fn ($a) => [$a['key'] => $a['status']]);

    expect($statuses['analytics'])->toBe('completed')
        ->and($statuses['customer'])->toBe('completed')
        ->and($statuses['marketing'])->toBe('running')
        ->and($statuses['strategy'])->toBe('waiting');
});

it('shows only completed agents in roadmap when all four are done while finishing', function () {
    $diagnosis = makeDiagnosis([
        'status' => 'processing',
        'summary_diagnosis' => 'Sedang diproses...',
    ]);

    foreach (['analytics', 'customer', 'marketing', 'strategy'] as $type) {
        AgentAnalysis::factory()->create([
            'growth_diagnosis_id' => $diagnosis->id,
            'agent_type' => $type,
        ]);
    }

    $component = Livewire::test(DiagnosisShow::class, ['growthDiagnosis' => $diagnosis])
        ->call('refreshStatus')
        ->assertSet('completedCount', 4)
        ->assertSet('isProcessing', true);

    $agents = $component->get('agents');
    expect(collect($agents)->every(fn ($a) => $a['status'] === 'completed'))->toBeTrue();
});

it('allows retrying a failed diagnosis', function () {
    Queue::fake();
    fakeAgentsForSuccess();

    $diagnosis = makeDiagnosis([
        'status' => 'failed',
        'summary_diagnosis' => 'Proses diagnosis gagal. Silakan coba lagi.',
    ]);
    BusinessSnapshot::factory()->create([
        'business_passport_id' => $diagnosis->growthGoal->business_passport_id,
    ]);

    Livewire::test(DiagnosisShow::class, ['growthDiagnosis' => $diagnosis])
        ->assertSet('isFailed', true)
        ->call('retry')
        ->assertSet('isFailed', false)
        ->assertSet('isProcessing', true)
        ->assertSet('completedCount', 0);

    Queue::assertPushed(ProcessGrowthDiagnosisJob::class);

    $fresh = $diagnosis->fresh();
    expect($fresh->status)->toBe('processing')
        ->and($fresh->summary_diagnosis)->toBe('Sedang diproses...');
});

it('does not retry when diagnosis is not failed', function () {
    Queue::fake();

    $diagnosis = makeDiagnosis(['status' => 'completed']);

    Livewire::test(DiagnosisShow::class, ['growthDiagnosis' => $diagnosis])
        ->assertSet('isFailed', false)
        ->call('retry')
        ->assertSet('isFailed', false)
        ->assertSet('isProcessing', false);

    Queue::assertNothingPushed();
    expect($diagnosis->fresh()->status)->toBe('completed');
});
