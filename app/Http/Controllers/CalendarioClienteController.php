<?php

namespace App\Http\Controllers;

use App\Models\Patrulla;
use App\Models\Rodado;
use App\Models\TurnoRodado;
use Illuminate\Http\Request;

class CalendarioClienteController extends Controller
{
    public function index()
    {
        return view('client.calendario.index');
    }

    public function getEventos(Request $request)
    {
        $user = auth()->user();
        $clienteIds = $user->clientes()->pluck('cliente_id')->toArray();

        // Get patrulla patentes for this client
        $patentes = Patrulla::whereIn('cliente_id', $clienteIds)->pluck('patente')->toArray();

        // Find rodados that match these patentes
        $rodadoIds = Rodado::whereIn('patente', $patentes)->pluck('id')->toArray();

        if (empty($rodadoIds)) {
            return response()->json([]);
        }

        $eventos = collect();

        // Get turnos for matching rodados
        $turnos = TurnoRodado::with(['rodado', 'taller'])
            ->whereIn('rodado_id', $rodadoIds)
            ->get();

        foreach ($turnos as $turno) {
            $color = '#3b82f6';
            if ($turno->tipo === TurnoRodado::TIPO_TURNO_SERVICE) {
                $color = '#3b82f6';
            } elseif ($turno->tipo === TurnoRodado::TIPO_TURNO_MECANICO) {
                $color = '#f97316';
            }

            $eventos->push([
                'id' => 'turno_'.$turno->id,
                'title' => 'Turno: '.($turno->rodado->patente ?? 'Sin patente'),
                'start' => $turno->fecha_hora->toIso8601String(),
                'color' => $color,
                'tipo' => 'turno',
                'tipo_servicio' => $turno->tipo,
                'estado' => $turno->estado,
                'readOnly' => true,
                'extendedProps' => [
                    'turno_id' => $turno->id,
                    'tipo_servicio' => $turno->tipo,
                    'vehiculo' => $turno->rodado->patente ?? 'Sin patente',
                    'taller' => $turno->taller->nombre ?? 'N/A',
                    'readOnly' => true,
                ],
            ]);
        }

        return response()->json($eventos->values());
    }
}
