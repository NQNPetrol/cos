<?php

use App\Models\User;
use Database\Seeders\PermissionsSeeder;

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users are routed from dashboard to the appropriate home', function () {
    $this->seed(PermissionsSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('admin');

    // DashboardController no devuelve vista en /dashboard: redirige según rol.
    $this->actingAs($user)->get('/dashboard')->assertRedirect(route('main.dashboard'));
});
