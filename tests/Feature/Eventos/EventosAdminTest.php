<?php

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Evento;
use App\Models\Personal;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionsSeeder::class);
});

test('user with ver.eventos can access admin eventos index', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)->get('/eventos');

    $response->assertOk();
});

test('user without ver.eventos cannot access admin eventos index', function () {
    $user = User::factory()->create();
    $user->assignRole('cliente');

    $response = $this->actingAs($user)->get('/eventos');

    $response->assertForbidden();
});

test('user with crear.eventos can access create form', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $response = $this->actingAs($user)->get('/eventos/nuevo');

    $response->assertOk();
});

test('user with crear.eventos can store evento with valid data', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $cliente = Cliente::factory()->create();
    $categoria = Categoria::factory()->create();
    $personal = Personal::factory()->create(['cliente_id' => $cliente->id]);

    $response = $this->actingAs($user)->post('/eventos', [
        'fecha_hora' => now()->format('Y-m-d H:i:s'),
        'cliente_id' => $cliente->id,
        'supervisor_id' => $personal->id,
        'categoria_id' => $categoria->id,
        'tipo' => 'Control',
        'coordenadas' => '-34.6037, -58.3816',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('eventos', [
        'cliente_id' => $cliente->id,
        'tipo' => 'Control',
    ]);
});

test('user with editar.eventos can access edit and update evento', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');
    $evento = Evento::factory()->create(['tipo' => 'Incidente']);

    $responseEdit = $this->actingAs($user)->get("/eventos/{$evento->id}/edit");
    $responseEdit->assertOk();

    $responseUpdate = $this->actingAs($user)->put("/eventos/{$evento->id}/update", [
        'fecha_hora' => $evento->fecha_hora->format('Y-m-d H:i:s'),
        'cliente_id' => $evento->cliente_id,
        'supervisor_id' => $evento->supervisor_id,
        'categoria_id' => $evento->categoria_id,
        'tipo' => 'Control',
        'coordenadas' => (string) $evento->longitud.','.(string) $evento->latitud,
    ]);

    $responseUpdate->assertRedirect();
    $evento->refresh();
    expect($evento->tipo)->toBe('Control');
});
