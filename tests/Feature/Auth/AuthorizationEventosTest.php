<?php

use App\Models\Cliente;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionsSeeder::class);
});

test('user without ver.eventos permission cannot access admin eventos index', function () {
    $user = User::factory()->create();
    $user->assignRole('cliente'); // cliente no tiene ver.eventos (admin)

    $response = $this->actingAs($user)->get('/eventos');

    $response->assertForbidden();
});

test('user with ver.eventos permission can access admin eventos index', function () {
    $user = User::factory()->create();
    $user->assignRole('admin'); // admin tiene todos los permisos incl. ver.eventos

    $response = $this->actingAs($user)->get('/eventos');

    $response->assertOk();
});

test('user without ver.eventos-cliente cannot access client eventos index', function () {
    $user = User::factory()->create();
    $user->assignRole('operador'); // operador tiene ver.eventos (admin) pero no ver.eventos-cliente

    $response = $this->actingAs($user)->get('/client/eventos');

    $response->assertForbidden();
});

test('user with ver.eventos-cliente permission can access client eventos index', function () {
    $user = User::factory()->create();
    $user->assignRole('cliente'); // cliente tiene ver.eventos-cliente
    $cliente = Cliente::create([
        'nombre' => 'Cliente Test',
        'cuit' => null,
        'domicilio' => null,
        'ciudad' => null,
        'provincia' => null,
        'categoria' => null,
        'convenio' => null,
    ]);
    $user->clientes()->attach($cliente->id);

    $response = $this->actingAs($user)->get('/client/eventos');

    $response->assertOk();
});
