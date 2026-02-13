<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            // 'Seguridad física',
            // 'Incidentes con el personal',
            // 'Seguridad vial y patrullaje',
            // 'Tecnológicos/comunicación',
            // 'Entorno y contexto',
            // 'Administrativos/reportables al cliente',
            'Salud/Emergencias',
            'Otros',
        ];

        foreach ($categorias as $nombre) {
            Categoria::firstOrCreate(
                ['nombre' => $nombre],
                ['descripcion' => $nombre]
            );
        }
    }
}
