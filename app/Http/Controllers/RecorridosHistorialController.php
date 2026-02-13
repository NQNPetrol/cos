<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recorrido;
use App\Models\RecorridoTimetable;
use App\Models\SupervisorPatrulla;
use App\Models\SupervisorEmpresaAsociada;
use Carbon\Carbon;

class RecorridosHistorialController extends Controller
{
    /**
     * Display the historial of recorridos realizados.
     */
    public function index()
    {
        $user = auth()->user();
        $cliente = $user->clientes()->first();

        if (!$cliente) {
            abort(403, 'No tiene un cliente asociado.');
        }

        $personal = $user->personal;
        $tienePersonal = $personal !== null;
        $tienePatrulla = false;

        if ($personal) {
            $tienePatrulla = SupervisorPatrulla::where('supervisor_id', $personal->id)->exists();
        }

        // Get recorridos available for this supervisor
        if ($user->hasRole('clientadmin')) {
            $registros = RecorridoTimetable::whereHas('recorrido', fn($q) => $q->where('cliente_id', $cliente->id))
                ->with(['recorrido.empresaAsociada', 'patrulla', 'supervisor', 'user'])
                ->orderBy('fecha_hora_inicio', 'desc')
                ->get();

            $recorridos = Recorrido::where('cliente_id', $cliente->id)
                ->with('empresaAsociada')
                ->get();
        } else {
            if ($personal) {
                $empresaIds = SupervisorEmpresaAsociada::where('supervisor_id', $personal->id)
                    ->pluck('empresa_asociada_id');

                $registros = RecorridoTimetable::where('supervisor_id', $personal->id)
                    ->with(['recorrido.empresaAsociada', 'patrulla', 'supervisor', 'user'])
                    ->orderBy('fecha_hora_inicio', 'desc')
                    ->get();

                $recorridos = Recorrido::where('cliente_id', $cliente->id)
                    ->whereIn('empresa_asociada_id', $empresaIds)
                    ->with('empresaAsociada')
                    ->get();
            } else {
                $registros = collect();
                $recorridos = collect();
            }
        }

        // Stats
        $totalRecorridos = $registros->count();
        $promedioDuracion = $registros->avg('duracion_est') ?? 0;
        $velocidadExcedida = $registros->where('velocidad_excedida', true)->count();

        return view('recorridos.historial', compact(
            'registros',
            'recorridos',
            'totalRecorridos',
            'promedioDuracion',
            'velocidadExcedida',
            'tienePersonal',
            'tienePatrulla',
            'cliente'
        ));
    }

    /**
     * Store a new recorrido realizado record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recorrido_id' => 'required|exists:recorridos,id',
            'fecha_hora_inicio' => 'required|date',
            'velocidad' => 'required|integer|min:1|max:300',
        ]);

        $user = auth()->user();
        $personal = $user->personal;

        if (!$personal) {
            return response()->json(['error' => 'Debe tener un registro de personal asignado para registrar recorridos.'], 422);
        }

        $patrullaAsignacion = SupervisorPatrulla::where('supervisor_id', $personal->id)->first();
        if (!$patrullaAsignacion) {
            return response()->json(['error' => 'Debe tener una patrulla asignada para registrar recorridos.'], 422);
        }

        $recorrido = Recorrido::findOrFail($request->recorrido_id);

        // Calculate estimated duration
        $duracionEst = null;
        $fechaFinEst = null;
        $velocidadExcedida = false;

        if ($recorrido->longitud_mts && $request->velocidad > 0) {
            $distanciaKm = $recorrido->longitud_mts / 1000;
            $duracionHoras = $distanciaKm / $request->velocidad;
            $duracionEst = (int) round($duracionHoras * 60);

            $fechaInicio = Carbon::parse($request->fecha_hora_inicio);
            $fechaFinEst = $fechaInicio->copy()->addMinutes($duracionEst);
        }

        // Check speed limit
        if ($recorrido->velocidadmax_permitida && $request->velocidad > $recorrido->velocidadmax_permitida) {
            $velocidadExcedida = true;
        }

        $registro = RecorridoTimetable::create([
            'recorrido_id' => $recorrido->id,
            'fecha_hora_inicio' => $request->fecha_hora_inicio,
            'velocidad' => $request->velocidad,
            'fechahora_fin_est' => $fechaFinEst,
            'duracion_est' => $duracionEst,
            'patrulla_id' => $patrullaAsignacion->patrulla_id,
            'supervisor_id' => $personal->id,
            'user_id' => $user->id,
            'velocidad_excedida' => $velocidadExcedida,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Recorrido registrado correctamente.',
            'registro' => $registro->load(['recorrido', 'patrulla']),
        ]);
    }

    /**
     * Update a recorrido realizado record.
     */
    public function update(Request $request, RecorridoTimetable $registro)
    {
        $request->validate([
            'fecha_hora_inicio' => 'required|date',
            'velocidad' => 'required|integer|min:1|max:300',
        ]);

        $user = auth()->user();
        $cliente = $user->clientes()->first();

        if (!$cliente || $registro->recorrido->cliente_id !== $cliente->id) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }

        $recorrido = $registro->recorrido;

        // Recalculate
        $duracionEst = null;
        $fechaFinEst = null;
        $velocidadExcedida = false;

        if ($recorrido->longitud_mts && $request->velocidad > 0) {
            $distanciaKm = $recorrido->longitud_mts / 1000;
            $duracionHoras = $distanciaKm / $request->velocidad;
            $duracionEst = (int) round($duracionHoras * 60);

            $fechaInicio = Carbon::parse($request->fecha_hora_inicio);
            $fechaFinEst = $fechaInicio->copy()->addMinutes($duracionEst);
        }

        if ($recorrido->velocidadmax_permitida && $request->velocidad > $recorrido->velocidadmax_permitida) {
            $velocidadExcedida = true;
        }

        $registro->update([
            'fecha_hora_inicio' => $request->fecha_hora_inicio,
            'velocidad' => $request->velocidad,
            'fechahora_fin_est' => $fechaFinEst,
            'duracion_est' => $duracionEst,
            'velocidad_excedida' => $velocidadExcedida,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registro actualizado correctamente.',
            'registro' => $registro->fresh()->load(['recorrido', 'patrulla']),
        ]);
    }

    /**
     * Delete a recorrido realizado record (only clientadmin).
     */
    public function destroy(RecorridoTimetable $registro)
    {
        $user = auth()->user();
        $cliente = $user->clientes()->first();

        if (!$cliente || $registro->recorrido->cliente_id !== $cliente->id) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }

        if (!$user->hasRole('clientadmin')) {
            return response()->json(['error' => 'Solo administradores del cliente pueden eliminar registros de recorridos.'], 403);
        }

        $registro->delete();

        return response()->json([
            'success' => true,
            'message' => 'Registro eliminado correctamente.',
        ]);
    }
}
