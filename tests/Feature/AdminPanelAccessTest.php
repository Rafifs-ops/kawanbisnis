<?php

use App\Models\User;
use Filament\Auth\Pages\Login;
use Livewire\Livewire;

test('guests are redirected to the admin login', function () {
    $response = $this->get('/admin');

    $response->assertRedirect(route('filament.admin.auth.login'));
});

test('non-admin users cannot access the admin panel', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin');

    $response->assertForbidden();
});

test('admins can access the admin panel', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get('/admin');

    $response->assertOk();
});

test('non-admin users cannot log in to the admin panel', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->fill([
            'data.email' => $user->email,
            'data.password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasErrors('data.email');

    $this->assertGuest();
});

test('admins can log in to the admin panel', function () {
    $admin = User::factory()->admin()->create();

    Livewire::test(Login::class)
        ->fill([
            'data.email' => $admin->email,
            'data.password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasNoErrors();

    $this->assertAuthenticated();
});
