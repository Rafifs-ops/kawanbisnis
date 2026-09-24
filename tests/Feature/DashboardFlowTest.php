<?php

use App\Livewire\Dashboard\CheckInCreate;
use App\Livewire\Dashboard\PassportIndex;
use App\Livewire\Dashboard\SnapshotCreate;
use App\Models\ActionPlan;
use App\Models\BusinessPassport;
use App\Models\CheckInFeedback;
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
        ->set('goal_type', 'Increase Sales')
        ->set('target_value', 25000000)
        ->call('submit')
        ->assertHasNoErrors();

    expect($passport->fresh()->snapshots)->toHaveCount(1);
    expect($passport->fresh()->growthGoals)->toHaveCount(1);

    $goal = $passport->growthGoals()->first();
    expect($goal->growthDiagnoses)->toHaveCount(1);
});

test('users can submit retention goal without target value', function () {
    Queue::fake();

    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    Livewire::test(SnapshotCreate::class)
        ->set('period_start', '2026-01-01')
        ->set('period_end', '2026-03-01')
        ->set('revenue', 15000000)
        ->set('total_orders', 150)
        ->set('goal_type', 'Retention')
        ->call('submit')
        ->assertHasNoErrors();

    $goal = $passport->fresh()->growthGoals()->first();
    expect($goal->goal_type)->toBe('Retention')
        ->and($goal->target_metrics)->toBeNull();
});

test('users can submit margin goal with percent unit', function () {
    Queue::fake();

    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    Livewire::test(SnapshotCreate::class)
        ->set('period_start', '2026-01-01')
        ->set('period_end', '2026-03-01')
        ->set('revenue', 15000000)
        ->set('total_orders', 150)
        ->set('goal_type', 'Margin')
        ->set('target_value', 25)
        ->call('submit')
        ->assertHasNoErrors();

    $goal = $passport->fresh()->growthGoals()->first();
    expect($goal->goal_type)->toBe('Margin')
        ->and($goal->target_metrics['unit'])->toBe('percent')
        ->and((float) $goal->target_metrics['target'])->toEqual(25.0);
});

test('average order value updates when revenue and total orders change', function () {
    $user = User::factory()->create();
    BusinessPassport::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    Livewire::test(SnapshotCreate::class)
        ->set('revenue', 1500000)
        ->set('total_orders', 10)
        ->assertSee('150000');
});

test('clearing total orders does not throw property not found', function () {
    $user = User::factory()->create();
    BusinessPassport::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    Livewire::test(SnapshotCreate::class)
        ->set('revenue', 1500000)
        ->set('total_orders', '')
        ->assertHasNoErrors()
        ->assertSee('Rata-rata Nilai Pesanan')
        ->assertSet('averageOrderValue', 0);
});

test('retention goal hides target input and shows helper text', function () {
    $user = User::factory()->create();
    BusinessPassport::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    Livewire::test(SnapshotCreate::class)
        ->set('goal_type', 'Retention')
        ->assertDontSee('Target Omzet (Rp)')
        ->assertDontSee('Target Margin (%)')
        ->assertSee('tidak membutuhkan target angka');
});

test('margin goal shows percent target label and increase sales shows rp label', function () {
    $user = User::factory()->create();
    BusinessPassport::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    Livewire::test(SnapshotCreate::class)
        ->set('goal_type', 'Margin')
        ->assertSee('Target Margin (%)')
        ->assertDontSee('Target Omzet (Rp)')
        ->set('goal_type', 'Increase Sales')
        ->assertSee('Target Omzet (Rp)')
        ->assertDontSee('Target Margin (%)')
        ->set('goal_type', 'AOV')
        ->assertSee('Target AOV (Rp)');
});

test('goal radio group uses live binding so target input updates on selection', function () {
    $user = User::factory()->create();
    BusinessPassport::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $html = Livewire::test(SnapshotCreate::class)->html();

    expect($html)->toContain('wire:model.live="goal_type"')
        ->not->toContain('wire:model="goal_type"');
});

test('changing goal type resets target value', function () {
    $user = User::factory()->create();
    BusinessPassport::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    Livewire::test(SnapshotCreate::class)
        ->set('goal_type', 'Margin')
        ->set('target_value', 25)
        ->set('goal_type', 'Increase Sales')
        ->assertSet('target_value', 0);
});

test('sidebar uses brand aqua background color', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $html = $this->get(route('dashboard'))->assertOk()->getContent();

    expect($html)->toContain('bg-[#9DE6FA]');
});

test('action plan page shows lihat action plan dropdown toggle', function () {
    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);
    $diagnosis = GrowthDiagnosis::factory()->create(['growth_goal_id' => $goal->id]);
    ActionPlan::factory()->create([
        'growth_diagnosis_id' => $diagnosis->id,
        'steps' => [
            ['day_offset' => 0, 'title' => 'Langkah satu', 'description' => 'Detail langkah', 'date' => now()->format('Y-m-d')],
        ],
    ]);
    $this->actingAs($user);

    $this->get(route('action-plan.index'))
        ->assertOk()
        ->assertSee('Lihat Action Plan')
        ->assertSee('x-collapse');
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
        ->assertRedirect(route('check-in.create', $plan));

    expect($plan->fresh()->checkInFeedbacks)->toHaveCount(1);
});

test('check-in page shows feedback read-only after completed', function () {
    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);
    $diagnosis = GrowthDiagnosis::factory()->create(['growth_goal_id' => $goal->id]);
    $plan = ActionPlan::factory()->create([
        'growth_diagnosis_id' => $diagnosis->id,
        'target_kpi' => ['metric' => 'ROAS'],
    ]);
    CheckInFeedback::factory()->create([
        'action_plan_id' => $plan->id,
        'checkin_date' => '2026-03-10',
        'actual_result' => ['result' => 'Penjualan naik 15%.'],
        'kpi_achieved' => ['metric' => 'ROAS', 'achieved' => true],
        'learning_notes' => 'Fokus Instagram lebih efektif.',
    ]);
    $this->actingAs($user);

    $this->get(route('check-in.create', $plan))
        ->assertOk()
        ->assertSee('Sudah Ditandai Selesai')
        ->assertSee('Penjualan naik 15%.')
        ->assertSee('Fokus Instagram lebih efektif.')
        ->assertDontSee('Simpan & Jadikan Knowledge')
        ->assertDontSee('wire:model="actual_result"');
});
