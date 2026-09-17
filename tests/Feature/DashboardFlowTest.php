<?php

use App\Livewire\Dashboard\CheckInCreate;
use App\Livewire\Dashboard\PassportIndex;
use App\Livewire\Dashboard\SnapshotCreate;
use App\Models\ActionPlan;
use App\Models\BusinessPassport;
use App\Models\GrowthDiagnosis;
use App\Models\GrowthGoal;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

test('authenticated users can visit the business passport page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('passport.index'));
    $response->assertOk();
});

test('users can save business passport via livewire', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(PassportIndex::class)
        ->set('business_name', 'Toko Berkah')
        ->set('business_type', 'Retail')
        ->set('target_customer', 'Ibu Rumah Tangga')
        ->set('business_description', 'Toko kebutuhan sehari-hari')
        ->call('save')
        ->assertHasNoErrors();

    expect($user->fresh()->businessPassport)
        ->not->toBeNull()
        ->business_name->toBe('Toko Berkah')
        ->business_type->toBe('Retail');
});

test('authenticated users can visit snapshot create page', function () {
    $user = User::factory()->create();
    BusinessPassport::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $response = $this->get(route('snapshot.create'));
    $response->assertOk();
});

test('users can submit snapshot and growth goal', function () {
    Queue::fake();

    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    Livewire::test(SnapshotCreate::class)
        ->set('period_start', '2026-01-01')
        ->set('period_end', '2026-03-01')
        ->set('revenue', 15000000)
        ->set('total_orders', 150)
        ->set('average_order_value', 100000)
        ->set('goal_type', 'Increase Sales')
        ->set('target_value', 25000000)
        ->call('submit')
        ->assertHasNoErrors();

    expect($passport->fresh()->snapshots)->toHaveCount(1);
    expect($passport->fresh()->growthGoals)->toHaveCount(1);

    $goal = $passport->growthGoals()->first();
    expect($goal->growthDiagnoses)->toHaveCount(1);
});

test('authenticated users can view diagnosis show page', function () {
    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);
    $diagnosis = GrowthDiagnosis::factory()->create(['growth_goal_id' => $goal->id]);
    $this->actingAs($user);

    $response = $this->get(route('diagnosis.show', $diagnosis));
    $response->assertOk();
});

test('authenticated users can visit action plan page', function () {
    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);
    $diagnosis = GrowthDiagnosis::factory()->create(['growth_goal_id' => $goal->id]);
    ActionPlan::factory()->create([
        'growth_diagnosis_id' => $diagnosis->id,
    ]);
    $this->actingAs($user);

    $response = $this->get(route('action-plan.index'));
    $response->assertOk();
});

test('authenticated users can submit check-in feedback', function () {
    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);
    $diagnosis = GrowthDiagnosis::factory()->create(['growth_goal_id' => $goal->id]);
    $plan = ActionPlan::factory()->create(['growth_diagnosis_id' => $diagnosis->id]);
    $this->actingAs($user);

    $response = $this->get(route('check-in.create', $plan));
    $response->assertOk();

    Livewire::test(CheckInCreate::class, ['actionPlan' => $plan])
        ->set('checkin_date', '2026-03-10')
        ->set('actual_result', 'Penjualan meningkat 10% dalam 7 hari.')
        ->set('kpi_achieved', true)
        ->set('learning_notes', 'Fokus pada Instagram story sangat efektif.')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect($plan->fresh()->checkInFeedbacks)->toHaveCount(1);
});
