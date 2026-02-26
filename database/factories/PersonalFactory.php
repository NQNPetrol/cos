<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Personal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Personal>
 */
class PersonalFactory extends Factory
{
    protected $model = Personal::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'cliente_id' => Cliente::factory(),
            'cargo' => fake()->optional()->jobTitle(),
            'puesto' => null,
            'convenio' => null,
            'fecha_ing' => fake()->optional()->date(),
            'tipo_doc' => null,
            'nro_doc' => null,
            'telefono' => null,
            'legajo' => null,
            'user_id' => null,
        ];
    }
}
