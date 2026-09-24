<?php

use App\Livewire\Dashboard\DiagnosisHistory;
use App\Livewire\Dashboard\PassportIndex;
use App\Models\ActionPlan;
use App\Models\BusinessPassport;
use App\Models\GrowthDiagnosis;
use App\Models\GrowthGoal;
use App\Models\User;
use Livewire\Livewire;

test('authenticated users can visit diagnosis history page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('diagnosis.history'));
    $response->assertOk();
});

test('diagnosis history lists user diagnoses newest first', function () {
    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);

    $older = GrowthDiagnosis::factory()->create([
        'growth_goal_id' => $goal->id,
        'created_at' => now()->subDays(7),
    ]);
    $newer = GrowthDiagnosis::factory()->create([
        'growth_goal_id' => $goal->id,
        'created_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test(DiagnosisHistory::class)
        ->assertViewHas('diagnoses', function ($diagnoses) use ($newer, $older) {
            return $diagnoses->count() === 2
                && $diagnoses->first()->id === $newer->id
                && $diagnoses->last()->id === $older->id;
        });
});

test('dashboard does not show diagnosis selesai widget', function () {
    $user = User::factory()->create();
    BusinessPassport::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertDontSee('Diagnosis Selesai');
});

test('dashboard shows tujuan bisnis next to the title when active goal exists', function () {
    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    GrowthGoal::factory()->create([
        'business_passport_id' => $passport->id,
        'goal_type' => 'Increase Sales',
        'status' => 'active',
    ]);
    $this->actingAs($user);

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Tujuan Bisnis')
        ->assertSee('Increase Sales');
});

test('first passport save shows next step to fill sales data', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(PassportIndex::class)
        ->set('business_name', 'Toko Baru')
        ->set('business_type', 'Retail')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('justSavedFirstTime', true)
        ->assertSee('Lanjut Isi Data Penjualan');
});

test('subsequent passport save does not show first-time next step', function () {
    $user = User::factory()->create();
    BusinessPassport::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    Livewire::test(PassportIndex::class)
        ->set('business_name', 'Toko Update')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('justSavedFirstTime', false);
});

test('passport page uses profil bisnis title and simpan button', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get(route('passport.index'))
        ->assertOk()
        ->assertSee('Profil Bisnis')
        ->assertSee('>Simpan</', false)
        ->assertDontSee('Simpan Passport Bisnis');
});

test('sidebar no longer links to data penjualan and uses profil bisnis label', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $html = $this->get(route('dashboard'))->assertOk()->getContent();

    expect($html)->toContain('Riwayat Diagnosis')
        ->toContain('Profil Bisnis')
        ->not->toContain('Data Penjualan Bisnis')
        ->not->toContain('Identitas Bisnis');
});

test('diagnosis show page uses friendly agent labels', function () {
    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);
    $diagnosis = GrowthDiagnosis::factory()->create([
        'growth_goal_id' => $goal->id,
        'status' => 'processing',
        'summary_diagnosis' => 'Sedang diproses...',
    ]);
    $this->actingAs($user);

    $this->get(route('diagnosis.show', $diagnosis))
        ->assertOk()
        ->assertSee('Analisis Penjualan')
        ->assertSee('Analisis Pelanggan')
        ->assertSee('Analisis Pemasaran')
        ->assertSee('Penyusunan Strategi');
});

test('completed diagnosis result shows ordered sections without daily step detail', function () {
    $user = User::factory()->create();
    $passport = BusinessPassport::factory()->create(['user_id' => $user->id]);
    $goal = GrowthGoal::factory()->create(['business_passport_id' => $passport->id]);
    $diagnosis = GrowthDiagnosis::factory()->create([
        'growth_goal_id' => $goal->id,
        'status' => 'completed',
        'summary_diagnosis' => 'Masalah utama X.',
        'business_diagnosis' => 'Masalah utama X karena Y.',
        'key_findings' => ['Fakta A'],
        'root_causes' => ['Hipotesis B'],
    ]);
    ActionPlan::factory()->create([
        'growth_diagnosis_id' => $diagnosis->id,
        'title' => 'Rekomendasi Utama',
        'priority_rank' => 1,
        'steps' => [
            ['day_offset' => 0, 'title' => 'Langkah harian', 'description' => 'Detail panjang', 'date' => now()->format('Y-m-d')],
        ],
    ]);
    $this->actingAs($user);

    $this->get(route('diagnosis.show', $diagnosis))
        ->assertOk()
        ->assertSee('Ringkasan')
        ->assertSee('Dasar Analisis')
        ->assertSee('Temuan')
        ->assertSee('Dugaan Penyebab')
        ->assertSee('Tiga Rekomendasi Utama')
        ->assertSee('Lihat Action Plan')
        ->assertDontSee('Langkah harian')
        ->assertDontSee('Langkah-langkah Detail');
});
