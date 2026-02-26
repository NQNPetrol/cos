<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Rodado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rodado>
 */
class RodadoFactory extends Factory
{
    protected $model = Rodado::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $marcas = Rodado::getMarcas();
        $tipos = Rodado::getTiposVehiculo();

        return [
            'marca' => fake()->randomElement($marcas),
            'tipo_vehiculo' => fake()->randomElement($tipos),
            'modelo' => fake()->word(),
            'año' => fake()->numberBetween(2015, date('Y')),
            'proveedor_id' => null,
            'cliente_id' => Cliente::factory(),
            'es_propio' => true,
            'patente' => null,
            'imagen_frente_path' => null,
            'imagen_costado_izq_path' => null,
            'imagen_costado_der_path' => null,
            'imagen_dorso_path' => null,
        ];
    }
}
