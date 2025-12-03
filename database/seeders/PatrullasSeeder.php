<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Patrulla;
use App\Models\PatrullaDocumental;
use App\Models\PatrullaSistema;
use App\Models\Sistema;
use Carbon\Carbon;

class PatrullasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sistemas = Sistema::all();

        $patrullas = [
            [
                'patente' => 'AC189DF',
                'marca' => 'Toyota',
                'modelo' => 'Hilux',
                'color' => 'Blanco',
                'estado' => 'operativa',
                'año' => 2022,
                'cliente_id' => 17,
                'observaciones' => 'Vehículo principal de patrulla, estado óptimo'
            ],
            [
                'patente' => 'EF456GH',
                'marca' => 'Ford',
                'modelo' => 'Ranger',
                'color' => 'Negro',
                'estado' => 'operativa',
                'año' => 2023,
                'cliente_id' => 17,
                'observaciones' => 'Nuevo ingreso a flota, equipamiento completo'
            ],
            [
                'patente' => 'IJ789KL',
                'marca' => 'Chevrolet',
                'modelo' => 'S10',
                'color' => 'Gris',
                'estado' => 'en_mantenimiento',
                'año' => 2021,
                'cliente_id' => 17,
                'observaciones' => 'En taller por cambio de frenos'
            ],
            [
                'patente' => 'MN012OP',
                'marca' => 'Nissan',
                'modelo' => 'Frontier',
                'color' => 'Azul',
                'estado' => 'operativa',
                'año' => 2022,
                'cliente_id' => 17,
                'observaciones' => 'Vehículo de apoyo, baja kilometraje'
            ],
            [
                'patente' => 'QR345ST',
                'marca' => 'Toyota',
                'modelo' => 'Corolla',
                'color' => 'Plateado',
                'estado' => 'operativa',
                'año' => 2023,
                'cliente_id' => 17,
                'observaciones' => 'Vehículo de supervisión, uso administrativo'
            ]
        ];

        foreach ($patrullas as $patrullaData) {
            $patrulla = Patrulla::create($patrullaData);
            
            // Crear documentación para cada patrulla
            $this->crearDocumentacion($patrulla);
            
            // Registrar patrulla en sistemas
            $this->registrarEnSistemas($patrulla, $sistemas);
        }

        $this->command->info('5 patrullas creadas para el cliente TSB (ID: 17) con documentación y registros en sistemas');
    }

    private function crearDocumentacion(Patrulla $patrulla): void
    {
        $documentos = [
            [
                'nombre' => 'VTV',
                'fecha_inicio' => Carbon::now()->subMonths(6),
                'fecha_vto' => Carbon::now()->addMonths(6),
                'detalles' => 'Certificado de inspección técnica emitido por organismo oficial'
            ],
            [
                'nombre' => 'Póliza de Seguro',
                'fecha_inicio' => Carbon::now()->subMonths(3),
                'fecha_vto' => Carbon::now()->addMonths(9),
                'detalles' => 'Cobertura completa contra todo riesgo, número de póliza: TSB-' . $patrulla->patente
            ],
            [
                'nombre' => 'RTO Nacional',
                'fecha_inicio' => Carbon::now()->subMonths(1),
                'fecha_vto' => Carbon::now()->addMonths(5),
                'detalles' => 'Último servicio realizado en taller autorizado TSB'
            ],
            [
                'nombre' => 'RTO Provincial',
                'fecha_inicio' => Carbon::now()->subMonths(2),
                'fecha_vto' => Carbon::now()->addMonths(10),
                'detalles' => 'Control de emisiones y sistema de combustible'
            ]
        ];

        foreach ($documentos as $documento) {
            PatrullaDocumental::create(array_merge($documento, ['patrulla_id' => $patrulla->id]));
        }
    }

    private function registrarEnSistemas(Patrulla $patrulla, $sistemas): void
    {
        $registros = [
            [
                'sistema_id' => $sistemas[0]->id,
                'fecha_registro' => Carbon::now()->subYear(),
                'fecha_vto' => Carbon::now()->addMonths(8),
                'nro_interno' => '88',
                'registra_user' => 1
            ],
            [
                'sistema_id' => $sistemas[1]->id,
                'fecha_registro' => Carbon::now()->subMonths(3),
                'fecha_vto' => Carbon::now()->subMonths(1), 
                'nro_interno' => '89',
                'registra_user' => 1
            ],
            [
                'sistema_id' => $sistemas[2]->id,
                'fecha_registro' => Carbon::now()->subMonths(1),
                'fecha_vto' => Carbon::now()->addMonths(11),
                'nro_interno' => '75',
                'registra_user' => 1
            ]
        ];

        foreach ($registros as $registro) {
            PatrullaSistema::create(array_merge($registro, ['patrulla_id' => $patrulla->id]));
        }
    }
}
