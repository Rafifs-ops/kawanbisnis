<?php

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

test('google redirect returns the provider authorize url', function () {
    Socialite::fake('google');

    $this->get(route('auth.google'))
        ->assertRedirect('https://socialite.fake/google/authorize');
});

test('new google users are registered as verified', function () {
    Socialite::fake('google', SocialiteUser::fake([
        'id' => 'google-123',
        'name' => 'Google User',
        'email' => 'google@example.com',
    ]));

    $this->get('/auth/google/callback')
        ->assertRedirect(route('dashboard'));

    $user = User::where('email', 'google@example.com')->firstOrFail();

    expect($user->google_id)->toBe('google-123');
    expect($user->email_verified_at)->not->toBeNull();
    $this->assertAuthenticatedAs($user);
});

test('existing google users are logged in', function () {
    $user = User::factory()->create(['google_id' => 'google-123']);

    Socialite::fake('google', SocialiteUser::fake([
        'id' => 'google-123',
        'email' => $user->email,
    ]));

    $this->get('/auth/google/callback')
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('existing email users have their google account linked without losing verification', function () {
    $user = User::factory()->create(['email' => 'linked@example.com', 'google_id' => null]);

    Socialite::fake('google', SocialiteUser::fake([
        'id' => 'google-456',
        'email' => 'linked@example.com',
    ]));

    $this->get('/auth/google/callback')
        ->assertRedirect(route('dashboard'));

    $user->refresh();

    expect($user->google_id)->toBe('google-456');
    expect($user->email_verified_at)->not->toBeNull();
    $this->assertAuthenticatedAs($user);
});
