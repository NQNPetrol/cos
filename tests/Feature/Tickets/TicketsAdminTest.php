<?php

use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionsSeeder::class);
});

test('user with ver.tickets can access admin tickets view', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)->get('/tickets/nuevo');

    $response->assertOk();
});

test('user without ver.tickets cannot access admin tickets view', function () {
    $user = User::factory()->create();
    $user->assignRole('cliente');

    $response = $this->actingAs($user)->get('/tickets/nuevo');

    $response->assertForbidden();
});
