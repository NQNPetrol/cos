<?php

use App\Models\Evento;
use App\Models\Cliente;
use App\Models\Categoria;
use App\Models\Personal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\PermissionsSeeder::class);
});

test('evento factory creates evento with valid attributes and relations', function () {
    $evento = Evento::factory()->create();

    expect($evento)->toBeInstanceOf(Evento::class)
        ->and($evento->fecha_hora)->toBeInstanceOf(\Illuminate\Support\Carbon::class)
        ->and($evento->cliente_id)->not->toBeNull()
        ->and($evento->categoria_id)->not->toBeNull()
        ->and($evento->es_anulado)->toBeFalse()
        ->and($evento->cliente)->toBeInstanceOf(Cliente::class)
        ->and($evento->categoria)->toBeInstanceOf(Categoria::class);
});

test('evento has fillable fecha_hora and tipo', function () {
    $evento = Evento::factory()->create([
        'tipo' => 'Control',
        'es_anulado' => false,
    ]);

    expect($evento->tipo)->toBe('Control')
        ->and($evento->es_anulado)->toBeFalse();
});
