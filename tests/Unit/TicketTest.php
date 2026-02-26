<?php

use App\Models\Ticket;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('ticket factory creates ticket with valid estado and categoria', function () {
    $ticket = Ticket::factory()->create();

    expect($ticket)->toBeInstanceOf(Ticket::class)
        ->and($ticket->titulo)->not->toBeEmpty()
        ->and($ticket->estado)->toBeIn(['abierto', 'en_proceso', 'cerrado', 'resuelto'])
        ->and($ticket->user_id)->not->toBeNull()
        ->and($ticket->user)->toBeInstanceOf(User::class);
});

test('ticket has initial estado when created', function () {
    $ticket = Ticket::factory()->create(['estado' => 'abierto']);

    expect($ticket->estado)->toBe('abierto')
        ->and($ticket->fecha_cierre)->toBeNull();
});
