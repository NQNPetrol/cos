<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sistema;

class SistemasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sistemas = [
            [
                'nombre' => 'Exactian',
                'link' => 'https://exactian.com/',
                'correo_electronico' => null,
                'telefono' => null,
            ],
            [
                'nombre' => 'GRO',
                'link' => null,
                'correo_electronico' => null,
                'telefono' => null,
            ],
            [
                'nombre' => 'Certronic SIMA',
                'link' => null,
                'correo_electronico' => null,
                'telefono' => null,
            ],
            [
                'nombre' => 'Certronic Auditium',
                'link' => 'https://auditium.com.ar/',
                'correo_electronico' => null,
                'telefono' => null,
            ],
            [
                'nombre' => 'Techint',
                'link' => 'https://auditium.com.ar/',
                'correo_electronico' => null,
                'telefono' => null,
            ],
        ];

        foreach ($sistemas as $sistema) {
            Sistema::create($sistema);
        }

        $this->command->info('Se han creado ' . count($sistemas) . ' sistemas correctamente.');
    }
}
