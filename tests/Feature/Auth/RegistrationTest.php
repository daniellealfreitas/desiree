<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Schema;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $voltTest = Volt::test('auth.register')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('username', 'testuser')
        ->set('password', 'password')
        ->set('password_confirmation', 'password');

    // Adicionar role apenas se a coluna existir
    if (Schema::hasColumn('users', 'role')) {
        $voltTest->set('role', 'visitante');
    }

    $response = $voltTest->call('register');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});