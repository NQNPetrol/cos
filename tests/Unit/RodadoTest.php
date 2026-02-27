<?php

use App\Models\Cliente;
use App\Models\Rodado;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('rodado factory creates rodado with valid marca and tipo_vehiculo', function () {
    $rodado = Rodado::factory()->create();

    expect($rodado)->toBeInstanceOf(Rodado::class)
        ->and($rodado->marca)->toBeIn(Rodado::getMarcas())
        ->and($rodado->tipo_vehiculo)->toBeIn(Rodado::getTiposVehiculo())
        ->and($rodado->cliente_id)->not->toBeNull()
        ->and($rodado->cliente)->toBeInstanceOf(Cliente::class)
        ->and($rodado->es_propio)->toBeTrue();
});

test('rodado display_name includes cliente when present', function () {
    $cliente = Cliente::factory()->create(['nombre' => 'Acme SA']);
    $rodado = Rodado::factory()->create([
        'cliente_id' => $cliente->id,
        'patente' => 'AB 123 CD',
    ]);

    expect($rodado->display_name)->toContain('Acme SA')
        ->and($rodado->display_name)->toContain('AB 123 CD');
});
