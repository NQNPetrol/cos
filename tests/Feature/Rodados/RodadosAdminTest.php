<?php

use App\Models\Cliente;
use App\Models\Rodado;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionsSeeder::class);
});

test('authenticated user can access rodados index', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)->get('/rodados');

    $response->assertOk();
});

test('authenticated user can create rodado with valid data', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $cliente = Cliente::factory()->create();

    $response = $this->actingAs($user)->post('/rodados', [
        'marca' => Rodado::MARCA_TOYOTA,
        'tipo_vehiculo' => Rodado::TIPO_CAMIONETA,
        'modelo' => 'Hilux',
        'año' => 2023,
        'cliente_id' => $cliente->id,
        'es_propio' => true,
    ]);

    $response->assertRedirect(route('rodados.index'));
    $this->assertDatabaseHas('rodados', [
        'modelo' => 'Hilux',
        'cliente_id' => $cliente->id,
    ]);
});
