<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Livewire\Dashboard\ActionPlanIndex;
use App\Livewire\Dashboard\CheckInCreate;
use App\Livewire\Dashboard\DiagnosisShow;
use App\Livewire\Dashboard\Overview;
use App\Livewire\Dashboard\PassportIndex;
use App\Livewire\Dashboard\SnapshotCreate;
use App\Livewire\Landing\Index as LandingIndex;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::livewire('/', LandingIndex::class)->name('home');

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Dashboard (authenticated)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('dashboard', Overview::class)->name('dashboard');

    // Business Passport
    Route::livewire('dashboard/passport', PassportIndex::class)->name('passport.index');

    // Snapshot & Goal
    Route::livewire('dashboard/snapshot/create', SnapshotCreate::class)->name('snapshot.create');

    // Diagnosis
    Route::livewire('dashboard/diagnosis/{growthDiagnosis}', DiagnosisShow::class)->name('diagnosis.show');

    // Action Plan
    Route::livewire('dashboard/action-plan', ActionPlanIndex::class)->name('action-plan.index');

    // Check-in / Feedback Loop
    Route::livewire('dashboard/check-in/{actionPlan}/create', CheckInCreate::class)->name('check-in.create');
});

require __DIR__.'/settings.php';
