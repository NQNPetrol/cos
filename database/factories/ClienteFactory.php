<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->company(),
            'cuit' => null,
            'domicilio' => fake()->optional()->streetAddress(),
            'ciudad' => fake()->optional()->city(),
            'provincia' => fake()->optional()->state(),
            'categoria' => null,
            'convenio' => null,
            'logo' => null,
        ];
    }
}
