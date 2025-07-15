<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            'Seguridad física',
            'Incidentes con el personal',
            'Seguridad vial y patrullaje',
            'Tecnológicos/comunicación',
            'Entorno y contexto',
            'Administrativos/reportables al cliente',
        ];

        foreach ($categorias as $nombre) {
            Categoria::create([
                'nombre' => $nombre,
                'descripcion' => $nombre,
            ]);
        }
    }
}
