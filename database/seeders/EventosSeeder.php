<?php

namespace Database\Seeders;

use App\Models\Evento;
use Illuminate\Database\Seeder;

class EventosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventos = [
            [
                'fecha_hora' => '2025-07-15 14:30:00',
                'cliente_id' => 17,
                'supervisor_id' => 1204,
                'longitud' => -37.110931652761984,
                'latitud' => -69.7049398044536,
                'descripcion' => 'Robo de herramientas en obra en construcción',
                'observaciones' => 'Se sustrajeron taladros, amoladoras y equipo eléctrico',
                'tipo' => 'Robo o intento de robo',
                'categoria_id' => 1,
                'user_id' => 1,
                'es_anulado' => false,
                'notas_adicionales' => 'Cliente solicita seguimiento especial del caso',
            ],
            [
                'fecha_hora' => '2025-05-16 09:15:00',
                'cliente_id' => 17,
                'supervisor_id' => 1204,
                'longitud' => -37.1272276715314,
                'latitud' => -69.65767953779616,
                'descripcion' => 'Vandalismo en sucursal comercial',
                'observaciones' => 'Daños en vidrieras y mobiliario exterior',
                'tipo' => 'Robo o intento de robo',
                'categoria_id' => 1,
                'user_id' => 1,
                'es_anulado' => false,
                'notas_adicionales' => 'Requiere reparaciones urgentes',
            ],
            [
                'fecha_hora' => '2025-05-17 22:45:00',
                'cliente_id' => 17,
                'supervisor_id' => 1204,
                'longitud' => -37.49431852591524,
                'latitud' => -69.10142222937222,
                'descripcion' => 'Incidente de seguridad en depósito',
                'observaciones' => 'Intento de ingreso no autorizado detectado por cámaras',
                'tipo' => 'Robo o intento de robo',
                'categoria_id' => 1,
                'user_id' => 1,
                'es_anulado' => false,
                'elementos_sustraidos' => json_encode(['ninguno']),
                'cantidad' => json_encode([0]),
                'notas_adicionales' => 'Sistema de alarma funcionó correctamente',
            ],
            [
                'fecha_hora' => '2025-05-18 11:00:00',
                'cliente_id' => 17,
                'supervisor_id' => 1204,
                'longitud' => -37.50021489469793,
                'latitud' => -69.06723531588601,
                'descripcion' => 'Incendio menor en área administrativa',
                'observaciones' => 'Corto circuito en tomacorriente, daños controlados',
                'tipo' => 'Robo o intento de robo',
                'categoria_id' => 1,
                'user_id' => 1,
                'es_anulado' => false,
                'notas_adicionales' => 'Bomberos asistieron, no hay heridos',
            ],
            [
                'fecha_hora' => '2025-05-19 16:20:00',
                'cliente_id' => 17,
                'supervisor_id' => 1204,
                'longitud' => -37.45319947753395,
                'latitud' => -68.99440232628498,
                'descripcion' => 'Pérdida de equipos tecnológicos',
                'observaciones' => 'Sustracción de laptops y proyectores de sala de reuniones',
                'tipo' => 'Robo o intento de robo',
                'categoria_id' => 1,
                'user_id' => 1,
                'es_anulado' => false,
                'notas_adicionales' => 'Clave para investigación: acceso con tarjeta identificada',
            ],
            [
                'fecha_hora' => '2025-10-21 13:20:00',
                'cliente_id' => 17,
                'supervisor_id' => 1204,
                'longitud' => -37.4601104070165,
                'latitud' => -68.9795384508562,
                'descripcion' => 'Pérdida de equipos tecnológicos',
                'observaciones' => 'Sustracción de laptops y proyectores de sala de reuniones',
                'tipo' => 'Robo o intento de robo',
                'categoria_id' => 1,
                'user_id' => 1,
                'es_anulado' => false,
                'notas_adicionales' => 'Clave para investigación: acceso con tarjeta identificada',
            ],
            [
                'fecha_hora' => '2025-12-01 13:20:00',
                'cliente_id' => 17,
                'supervisor_id' => 1204,
                'longitud' => -37.4601104070165,
                'latitud' => -68.9795384508562,
                'descripcion' => 'Pérdida de equipos tecnológicos',
                'observaciones' => 'Sustracción de laptops y proyectores de sala de reuniones',
                'tipo' => 'Robo o intento de robo',
                'categoria_id' => 1,
                'user_id' => 1,
                'es_anulado' => false,
                'notas_adicionales' => 'Clave para investigación: acceso con tarjeta identificada',
            ],
            [
                'fecha_hora' => '2025-11-21 13:20:00',
                'cliente_id' => 17,
                'supervisor_id' => 1204,
                'longitud' => -37.135030707496036,
                'latitud' => -69.70008450482965,
                'descripcion' => 'Pérdida de equipos tecnológicos',
                'observaciones' => 'Sustracción de laptops y proyectores de sala de reuniones',
                'tipo' => 'Robo o intento de robo',
                'categoria_id' => 1,
                'user_id' => 1,
                'es_anulado' => false,
                'notas_adicionales' => 'Clave para investigación: acceso con tarjeta identificada',
            ],
            [
                'fecha_hora' => '2025-06-21 13:20:00',
                'cliente_id' => 17,
                'supervisor_id' => 1204,
                'longitud' => -37.125450074435825,
                'latitud' => -69.7077316017374,
                'descripcion' => 'Pérdida de equipos tecnológicos',
                'observaciones' => 'Sustracción de laptops y proyectores de sala de reuniones',
                'tipo' => 'Robo o intento de robo',
                'categoria_id' => 1,
                'user_id' => 1,
                'es_anulado' => false,
                'notas_adicionales' => 'Clave para investigación: acceso con tarjeta identificada',
            ],
        ];

        foreach ($eventos as $evento) {
            Evento::create($evento);
        }

        $this->command->info('5 eventos creados para el cliente TSB (ID: 17)');
    }
}
