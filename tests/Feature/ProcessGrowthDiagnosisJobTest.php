<?php

use App\AI\Agents\AnalyticsAgent;
use App\AI\Agents\CustomerAgent;
use App\AI\Agents\MarketingAgent;
use App\AI\Agents\StrategyAgent;
use App\Jobs\ProcessGrowthDiagnosisJob;
use App\Models\ActionPlan;
use App\Models\AgentAnalysis;
use App\Models\BusinessPassport;
use App\Models\BusinessSnapshot;
use App\Models\GrowthDiagnosis;
use App\Models\GrowthGoal;
use App\Models\User;

it('creates agent analysis records for each agent', function () {
    AnalyticsAgent::fake([
        ['summary' => 'Revenue stabil', 'anomalies' => ['AOV turun'], 'key_findings' => ['Order naik'], 'confidence_score' => 0.85],
    ]);
    CustomerAgent::fake([
        ['summary' => 'Churn rate normal', 'segments' => ['New 60%', 'Returning 40%'], 'insights' => ['Retention bagus'], 'hypotheses' => ['Promo efektif'], 'confidence_score' => 0.80],
    ]);
    MarketingAgent::fake([
        ['summary' => 'Instagram bagus', 'channel_performance' => ['Instagram ROAS 3x'], 'opportunities' => ['Tambah budget IG'], 'hypotheses' => ['Shopee bisa lebih'], 'confidence_score' => 0.75],
    ]);
    StrategyAgent::fake([
        [
            'business_diagnosis' => 'Revenue stabil tapi AOV turun',
            'root_causes' => ['Harga kompetitif', 'Promo berlebihan'],
            'growth_opportunity' => ['Ekspasi channel baru'],
            'recommendations' => [
                [
                    'title' => 'Optimasi Instagram',
                    'description' => 'Tingkatkan ROAS',
                    'priority_score' => 8.5,
                    'steps' => [
                        ['day_offset' => 0, 'title' => 'Audit konten', 'description' => 'Review performa 10 posting terakhir'],
                        ['day_offset' => 1, 'title' => 'Buat konten baru', 'description' => 'Siapkan 3 konten berdasarkan hasil audit'],
                    ],
                ],
            ],
            'action_plan' => ['short_term' => ['Optimasi IG'], 'long_term' => ['Ekspasi Shopee']],
            'kpi_metrics' => [['metric' => 'ROAS', 'target' => '3x', 'unit' => 'multiplier']],
        ],
    ]);

    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    BusinessSnapshot::factory()->create(['business_passport_id' => $passport->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);
    $diagnosis = GrowthDiagnosis::factory()->create(['growth_goal_id' => $goal->id]);

    ProcessGrowthDiagnosisJob::dispatchSync($diagnosis);

    expect(AgentAnalysis::where('growth_diagnosis_id', $diagnosis->id)->count())->toBe(4);

    $analytics = AgentAnalysis::where('growth_diagnosis_id', $diagnosis->id)
        ->where('agent_type', 'analytics')->first();
    expect($analytics->findings['summary'])->toBe('Revenue stabil');

    $customer = AgentAnalysis::where('growth_diagnosis_id', $diagnosis->id)
        ->where('agent_type', 'customer')->first();
    expect($customer->findings['summary'])->toBe('Churn rate normal');

    $marketing = AgentAnalysis::where('growth_diagnosis_id', $diagnosis->id)
        ->where('agent_type', 'marketing')->first();
    expect($marketing->findings['summary'])->toBe('Instagram bagus');

    $strategy = AgentAnalysis::where('growth_diagnosis_id', $diagnosis->id)
        ->where('agent_type', 'strategy')->first();
    expect($strategy->findings['summary'])->toBe('Revenue stabil tapi AOV turun');
});

it('creates action plans from recommendations', function () {
    AnalyticsAgent::fake([['summary' => 'OK', 'anomalies' => [], 'key_findings' => ['A'], 'confidence_score' => 0.8]]);
    CustomerAgent::fake([['summary' => 'OK', 'segments' => [], 'insights' => ['B'], 'hypotheses' => [], 'confidence_score' => 0.8]]);
    MarketingAgent::fake([['summary' => 'OK', 'channel_performance' => [], 'opportunities' => ['C'], 'hypotheses' => [], 'confidence_score' => 0.8]]);
    StrategyAgent::fake([
        [
            'business_diagnosis' => 'Masalah utama',
            'root_causes' => ['Penyebab 1'],
            'growth_opportunity' => ['Peluang 1'],
            'recommendations' => [
                [
                    'title' => 'Action 1',
                    'description' => 'Desc 1',
                    'priority_score' => 9.0,
                    'steps' => [
                        ['day_offset' => 0, 'title' => 'Langkah 1', 'description' => 'Detail langkah 1'],
                        ['day_offset' => 2, 'title' => 'Langkah 2', 'description' => 'Detail langkah 2'],
                    ],
                ],
                [
                    'title' => 'Action 2',
                    'description' => 'Desc 2',
                    'priority_score' => 7.5,
                    'steps' => [
                        ['day_offset' => 7, 'title' => 'Langkah A', 'description' => 'Detail langkah A'],
                        ['day_offset' => 14, 'title' => 'Langkah B', 'description' => 'Detail langkah B'],
                    ],
                ],
            ],
            'action_plan' => ['short_term' => ['Action 1'], 'long_term' => ['Action 2']],
            'kpi_metrics' => [['metric' => 'Revenue', 'target' => '10M', 'unit' => 'IDR']],
        ],
    ]);

    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    BusinessSnapshot::factory()->create(['business_passport_id' => $passport->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);
    $diagnosis = GrowthDiagnosis::factory()->create(['growth_goal_id' => $goal->id]);

    ProcessGrowthDiagnosisJob::dispatchSync($diagnosis);

    $plans = ActionPlan::where('growth_diagnosis_id', $diagnosis->id)->get();
    expect($plans)->toHaveCount(2);

    $firstPlan = $plans->first();
    expect($firstPlan->title)->toBe('Action 1')
        ->and($firstPlan->priority_rank)->toBe(1)
        ->and($firstPlan->timeline_days)->toBe(7);
});

it('updates diagnosis with all 6 sections', function () {
    AnalyticsAgent::fake([['summary' => 'OK', 'anomalies' => [], 'key_findings' => [], 'confidence_score' => 0.8]]);
    CustomerAgent::fake([['summary' => 'OK', 'segments' => [], 'insights' => [], 'hypotheses' => [], 'confidence_score' => 0.8]]);
    MarketingAgent::fake([['summary' => 'OK', 'channel_performance' => [], 'opportunities' => [], 'hypotheses' => [], 'confidence_score' => 0.8]]);
    StrategyAgent::fake([
        [
            'business_diagnosis' => 'Revenue stabil tapi pelanggan menurun',
            'root_causes' => ['Harga terlalu tinggi', 'Layanan pelanggan buruk'],
            'growth_opportunity' => ['Ekspasi ke Shopee', 'Program loyalitas'],
            'recommendations' => [
                [
                    'title' => 'Turunkan harga',
                    'description' => 'Sesuaikan harga pasar',
                    'priority_score' => 8.0,
                    'steps' => [
                        ['day_offset' => 0, 'title' => 'Riset harga', 'description' => 'Bandingkan harga dengan 5 kompetitor'],
                        ['day_offset' => 1, 'title' => 'Update harga', 'description' => 'Terapkan harga baru di semua channel'],
                    ],
                ],
            ],
            'action_plan' => ['short_term' => ['Turunkan harga'], 'long_term' => ['Program loyalitas']],
            'kpi_metrics' => [['metric' => 'Churn Rate', 'target' => '<5%', 'unit' => 'percent']],
        ],
    ]);

    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    BusinessSnapshot::factory()->create(['business_passport_id' => $passport->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);
    $diagnosis = GrowthDiagnosis::factory()->create([
        'growth_goal_id' => $goal->id,
        'summary_diagnosis' => 'Sedang diproses...',
    ]);

    ProcessGrowthDiagnosisJob::dispatchSync($diagnosis);

    $fresh = $diagnosis->fresh();
    expect($fresh->summary_diagnosis)->toBe('Revenue stabil tapi pelanggan menurun')
        ->and($fresh->business_diagnosis)->toBe('Revenue stabil tapi pelanggan menurun')
        ->and($fresh->root_causes)->toBe(['Harga terlalu tinggi', 'Layanan pelanggan buruk'])
        ->and($fresh->opportunities)->toBe(['Ekspasi ke Shopee', 'Program loyalitas'])
        ->and($fresh->kpi_metrics)->toBe([['metric' => 'Churn Rate', 'target' => '<5%', 'unit' => 'percent']]);
});

it('saves steps with calculated dates on action plans', function () {
    AnalyticsAgent::fake([['summary' => 'OK', 'anomalies' => [], 'key_findings' => [], 'confidence_score' => 0.8]]);
    CustomerAgent::fake([['summary' => 'OK', 'segments' => [], 'insights' => [], 'hypotheses' => [], 'confidence_score' => 0.8]]);
    MarketingAgent::fake([['summary' => 'OK', 'channel_performance' => [], 'opportunities' => [], 'hypotheses' => [], 'confidence_score' => 0.8]]);
    StrategyAgent::fake([
        [
            'business_diagnosis' => 'Masalah utama',
            'root_causes' => ['Penyebab 1'],
            'growth_opportunity' => ['Peluang 1'],
            'recommendations' => [
                [
                    'title' => 'Action with Steps',
                    'description' => 'Deskripsi aksi',
                    'priority_score' => 8.0,
                    'steps' => [
                        ['day_offset' => 0, 'title' => 'Hari ini', 'description' => 'Langkah pertama'],
                        ['day_offset' => 3, 'title' => 'Nanti', 'description' => 'Langkah kedua'],
                    ],
                ],
            ],
            'action_plan' => ['short_term' => ['Action with Steps'], 'long_term' => []],
            'kpi_metrics' => [['metric' => 'Revenue', 'target' => '5M', 'unit' => 'IDR']],
        ],
    ]);

    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    BusinessSnapshot::factory()->create(['business_passport_id' => $passport->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);
    $diagnosis = GrowthDiagnosis::factory()->create(['growth_goal_id' => $goal->id]);

    ProcessGrowthDiagnosisJob::dispatchSync($diagnosis);

    $plan = ActionPlan::where('growth_diagnosis_id', $diagnosis->id)->first();
    expect($plan->steps)->toHaveCount(2)
        ->and($plan->steps[0]['day_offset'])->toBe(0)
        ->and($plan->steps[0]['date'])->toBe(now()->format('Y-m-d'))
        ->and($plan->steps[0]['title'])->toBe('Hari ini')
        ->and($plan->steps[1]['day_offset'])->toBe(3)
        ->and($plan->steps[1]['date'])->toBe(now()->addDays(3)->format('Y-m-d'))
        ->and($plan->steps[1]['day_name'])->not->toBeEmpty();
});

it('returns early when no snapshot exists', function () {
    AnalyticsAgent::fake()->preventStrayPrompts();
    CustomerAgent::fake()->preventStrayPrompts();
    MarketingAgent::fake()->preventStrayPrompts();
    StrategyAgent::fake()->preventStrayPrompts();

    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);
    $diagnosis = GrowthDiagnosis::factory()->create(['growth_goal_id' => $goal->id]);

    ProcessGrowthDiagnosisJob::dispatchSync($diagnosis);

    expect(AgentAnalysis::where('growth_diagnosis_id', $diagnosis->id)->count())->toBe(0);
});

it('logs error and throws when agent fails', function () {
    AnalyticsAgent::fake()->preventStrayPrompts();
    CustomerAgent::fake()->preventStrayPrompts();
    MarketingAgent::fake()->preventStrayPrompts();
    StrategyAgent::fake()->preventStrayPrompts();

    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    BusinessSnapshot::factory()->create(['business_passport_id' => $passport->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);
    $diagnosis = GrowthDiagnosis::factory()->create(['growth_goal_id' => $goal->id]);

    $this->expectException(RuntimeException::class);

    $job = new ProcessGrowthDiagnosisJob($diagnosis);
    $job->handle();
});
