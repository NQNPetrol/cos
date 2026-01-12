<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TurnoRodado;
use App\Models\CambioEquipoRodado;
use App\Models\PagoServiciosRodado;
use Carbon\Carbon;

class CalendarioRodadosController extends Controller
{
    public function index()
    {
        return view('rodados.calendario');
    }
    
    // Este método ya no se usa, pero se mantiene por compatibilidad con el componente Livewire
    // El componente Livewire ahora obtiene los datos directamente

    public function getEventos(Request $request)
    {
        $eventos = collect();

        // Obtener turnos
        $turnos = TurnoRodado::with(['rodado', 'taller'])->get();
        foreach ($turnos as $turno) {
            $color = '#3b82f6'; // Azul por defecto
            
            if ($turno->tipo === TurnoRodado::TIPO_TURNO_SERVICE) {
                $color = '#3b82f6'; // Azul
            } elseif ($turno->tipo === TurnoRodado::TIPO_TURNO_MECANICO) {
                $color = '#f97316'; // Naranja
            }

            $eventos->push([
                'id' => 'turno_' . $turno->id,
                'title' => 'Turno: ' . ($turno->rodado->patente ?? 'Sin patente'),
                'start' => $turno->fecha_hora->toIso8601String(),
                'color' => $color,
                'tipo' => 'turno',
                'tipo_servicio' => $turno->tipo,
                'turno_id' => $turno->id,
                'estado' => $turno->estado,
                'extendedProps' => [
                    'turno_id' => $turno->id,
                    'tipo_servicio' => $turno->tipo,
                ],
            ]);
        }

        // Obtener cambios de equipos
        $cambiosEquipos = CambioEquipoRodado::with(['rodado', 'taller'])->get();
        foreach ($cambiosEquipos as $cambio) {
            $eventos->push([
                'id' => 'cambio_' . $cambio->id,
                'title' => 'Cambio Equipo: ' . ($cambio->rodado->patente ?? 'Sin patente'),
                'start' => $cambio->fecha_hora_estimada->toIso8601String(),
                'color' => '#10b981', // Verde
                'tipo' => 'cambio_equipo',
                'cambio_id' => $cambio->id,
                'extendedProps' => [
                    'cambio_id' => $cambio->id,
                ],
            ]);
        }

        // Obtener pagos (mostrar vencimientos)
        $pagos = PagoServiciosRodado::with(['rodado', 'proveedor'])->get();
        foreach ($pagos as $pago) {
            // Para pagos, podríamos mostrar la fecha de pago o calcular una fecha de vencimiento
            // Por ahora mostramos la fecha de pago
            $eventos->push([
                'id' => 'pago_' . $pago->id,
                'title' => 'Pago: ' . ($pago->rodado->patente ?? 'Sin patente'),
                'start' => Carbon::parse($pago->fecha_pago)->toIso8601String(),
                'color' => '#ef4444', // Rojo
                'tipo' => 'pago',
                'pago_id' => $pago->id,
                'estado' => $this->calcularEstadoPago($pago),
                'extendedProps' => [
                    'pago_id' => $pago->id,
                ],
            ]);
        }

        return response()->json($eventos->values());
    }

    public function getDetalleEvento(Request $request, $tipo, $id)
    {
        if ($tipo === 'turno') {
            $turno = TurnoRodado::with(['rodado.cliente', 'rodado.proveedor', 'taller'])->find($id);
            
            if (!$turno) {
                return response()->json(['error' => 'Turno no encontrado'], 404);
            }

            return response()->json([
                'tipo' => 'turno',
                'data' => [
                    'id' => $turno->id,
                    'tipo' => $turno->tipo,
                    'fecha_hora' => $turno->fecha_hora->format('Y-m-d H:i'),
                    'rodado' => $turno->rodado->display_name,
                    'taller' => $turno->taller->nombre ?? 'N/A',
                    'encargado_dejar' => $turno->encargado_dejar,
                    'encargado_retirar' => $turno->encargado_retirar,
                    'descripcion' => $turno->descripcion,
                    'estado' => $turno->estado,
                    'cubre_servicio' => $turno->cubre_servicio,
                    'partes_afectadas' => $turno->partes_afectadas,
                ],
            ]);
        } elseif ($tipo === 'cambio_equipo') {
            $cambio = CambioEquipoRodado::with(['rodado.cliente', 'rodado.proveedor', 'taller', 'dispositivo'])->find($id);
            
            if (!$cambio) {
                return response()->json(['error' => 'Cambio de equipo no encontrado'], 404);
            }

            return response()->json([
                'tipo' => 'cambio_equipo',
                'data' => [
                    'id' => $cambio->id,
                    'tipo' => $cambio->tipo,
                    'fecha_hora_estimada' => $cambio->fecha_hora_estimada->format('Y-m-d H:i'),
                    'rodado' => $cambio->rodado->display_name,
                    'taller' => $cambio->taller->nombre ?? 'N/A',
                    'tipo_cubierta' => $cambio->tipo_cubierta,
                    'pago_mano_obra' => $cambio->pago_mano_obra,
                    'motivo' => $cambio->motivo,
                    'dispositivo' => $cambio->dispositivo ? $cambio->dispositivo->nombre : null,
                    'detalle_equipo_nuevo' => $cambio->detalle_equipo_nuevo,
                    'detalle_equipo_viejo' => $cambio->detalle_equipo_viejo,
                ],
            ]);
        } elseif ($tipo === 'pago') {
            $pago = PagoServiciosRodado::with(['rodado.cliente', 'rodado.proveedor', 'proveedor'])->find($id);
            
            if (!$pago) {
                return response()->json(['error' => 'Pago no encontrado'], 404);
            }

            return response()->json([
                'tipo' => 'pago',
                'data' => [
                    'id' => $pago->id,
                    'tipo' => $pago->tipo,
                    'fecha_pago' => Carbon::parse($pago->fecha_pago)->format('Y-m-d'),
                    'rodado' => $pago->rodado->display_name,
                    'proveedor' => $pago->proveedor->nombre ?? 'N/A',
                    'moneda' => $pago->moneda ?? 'ARS',
                    'monto' => $pago->monto,
                    'estado' => $this->calcularEstadoPago($pago),
                ],
            ]);
        }

        return response()->json(['error' => 'Tipo de evento no válido'], 400);
    }

    private function calcularEstadoPago(PagoServiciosRodado $pago)
    {
        // Lógica simple: si tiene factura_path, consideramos que está pagado
        // En el futuro se podría agregar una fecha de vencimiento real
        if ($pago->factura_path) {
            return 'pagado';
        }
        return 'pendiente';
    }
}
