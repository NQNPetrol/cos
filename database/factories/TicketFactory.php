<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categorias = [
            'Fallas Técnicas', 'Solicitud de compra', 'Solicitud de instalación',
            'Solicitud de mantenimiento', 'Solicitud de equipamiento de vehiculos',
            'Reclamos', 'Solicitud de acceso/creacion de usuarios',
        ];

        return [
            'titulo' => fake()->sentence(3),
            'descripcion' => fake()->paragraph(),
            'categoria' => fake()->randomElement($categorias),
            'estado' => fake()->randomElement(['abierto', 'en_proceso', 'cerrado', 'resuelto']),
            'prioridad' => fake()->randomElement(['baja', 'media', 'alta', 'urgente']),
            'user_id' => User::factory(),
            'asignado_a' => null,
            'cliente_id' => Cliente::factory(),
            'fecha_cierre' => null,
        ];
    }
}
