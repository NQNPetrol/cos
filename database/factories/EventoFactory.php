<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Evento;
use App\Models\Personal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evento>
 */
class EventoFactory extends Factory
{
    protected $model = Evento::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cliente = Cliente::factory()->create();
        $categoria = Categoria::factory()->create();
        $personal = Personal::factory()->create(['cliente_id' => $cliente->id]);
        $user = User::factory()->create();

        return [
            'fecha_hora' => fake()->dateTimeBetween('-1 month', 'now'),
            'cliente_id' => $cliente->id,
            'supervisor_id' => $personal->id,
            'categoria_id' => $categoria->id,
            'tipo' => fake()->randomElement(['Incidente', 'Control', 'Otro']),
            'latitud' => fake()->latitude(),
            'longitud' => fake()->longitude(),
            // descripcion dropped from eventos table in migration update_eventos_table_for_new_structure
            'observaciones' => null,
            'url_reporte' => null,
            'user_id' => $user->id,
            'empresa_asociada_id' => null,
            'elementos_sustraidos' => null,
            'cantidad' => null,
            'es_anulado' => false,
            'anulado_por' => null,
            'fecha_anulado' => null,
            'notas_adicionales' => null,
        ];
    }
}
