<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TurnoRodado;
use App\Models\Rodado;
use App\Models\Taller;
use Illuminate\Support\Facades\Storage;

class TurnoRodadoController extends Controller
{
    public function show(TurnoRodado $turno)
    {
        // Cargar relaciones necesarias
        $turno->load(['rodado', 'taller']);
        
        // Obtener partes afectadas directamente del atributo raw para evitar el accessor
        $partesAfectadasRaw = $turno->getAttributes()['partes_afectadas'] ?? null;
        
        // Logging para depurar
        \Log::info('Turno ID: ' . $turno->id);
        \Log::info('Partes afectadas raw: ' . ($partesAfectadasRaw ?? 'null'));
        
        $partesAfectadas = [];
        
        if ($partesAfectadasRaw) {
            if (is_string($partesAfectadasRaw)) {
                $decoded = json_decode($partesAfectadasRaw, true);
                \Log::info('Partes afectadas decoded: ', ['decoded' => $decoded]);
                
                if (is_array($decoded)) {
                    // Si es un array asociativo con claves numéricas como strings, convertirlo a array indexado
                    if (array_keys($decoded) !== range(0, count($decoded) - 1)) {
                        // Es un objeto asociativo, convertir a array indexado
                        $partesAfectadas = array_values($decoded);
                    } else {
                        // Ya es un array indexado
                        $partesAfectadas = $decoded;
                    }
                } elseif (is_object($decoded)) {
                    // Si es un objeto, convertirlo a array
                    $partesAfectadas = array_values((array) $decoded);
                }
            } elseif (is_array($partesAfectadasRaw)) {
                $partesAfectadas = $partesAfectadasRaw;
            }
        }
        
        \Log::info('Partes afectadas final: ', ['partes' => $partesAfectadas]);
        
        // Preparar datos para el formulario
        $data = [
            'id' => $turno->id,
            'rodado_id' => $turno->rodado_id,
            'taller_id' => $turno->taller_id,
            'tipo' => $turno->tipo,
            'fecha_hora' => $turno->fecha_hora->format('Y-m-d\TH:i'),
            'encargado_dejar' => $turno->encargado_dejar ?? '',
            'encargado_retirar' => $turno->encargado_retirar ?? '',
            'descripcion' => $turno->descripcion ?? '',
            'partes_afectadas' => $partesAfectadas,
            'estado' => $turno->estado,
        ];
        
        \Log::info('Datos enviados al frontend: ', ['data' => $data]);
        \Log::info('Descripción del turno: ', ['descripcion' => $turno->descripcion, 'descripcion_raw' => $turno->getAttributes()['descripcion'] ?? 'null']);
        
        return response()->json($data);
    }

    public function store(Request $request)
    {
        // Logging para depurar
        \Log::info('Store turno - Request data:', $request->all());
        \Log::info('Store turno - Descripcion recibida:', ['descripcion' => $request->input('descripcion')]);
        \Log::info('Store turno - Tipo recibido:', ['tipo' => $request->input('tipo')]);

        // Validación base
        $validated = $request->validate([
            'rodado_id' => 'required|exists:rodados,id',
            'taller_id' => 'required|exists:talleres,id',
            'tipo' => 'required|in:turno_service,turno_mecanico,turno_taller',
            'fecha_hora' => 'required|date',
            'encargado_dejar' => 'nullable|string|max:255',
            'encargado_retirar' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'partes_afectadas' => 'nullable|array',
            'partes_afectadas.*.item' => 'required_with:partes_afectadas|string|max:255',
            'partes_afectadas.*.cantidad' => 'required_with:partes_afectadas|integer|min:1',
            'partes_afectadas.*.descripcion' => 'required_with:partes_afectadas|string',
        ]);

        // Si es turno_taller, cambiar a turno_mecanico automáticamente
        if ($validated['tipo'] === 'turno_taller') {
            $validated['tipo'] = TurnoRodado::TIPO_TURNO_MECANICO;
        }

        // Validación condicional: descripcion es requerida para turno_mecanico
        if ($validated['tipo'] === TurnoRodado::TIPO_TURNO_MECANICO) {
            $request->validate([
                'descripcion' => 'required|string|min:1',
            ], [
                'descripcion.required' => 'El motivo del turno es obligatorio para turnos mecánicos.',
                'descripcion.min' => 'El motivo del turno no puede estar vacío.',
            ]);
            // Asegurar que descripcion esté en validated
            $validated['descripcion'] = $request->input('descripcion', '');
        } else {
            // Para turno_service, descripcion es opcional
            $validated['descripcion'] = $request->input('descripcion', null);
        }

        // Logging después de validación
        \Log::info('Store turno - Descripcion validada:', ['descripcion' => $validated['descripcion'] ?? 'null']);

        // Estado predeterminado
        if ($validated['tipo'] === TurnoRodado::TIPO_TURNO_SERVICE || 
            $validated['tipo'] === TurnoRodado::TIPO_TURNO_MECANICO) {
            $validated['estado'] = TurnoRodado::ESTADO_PENDIENTE;
        }

        // Procesar partes afectadas (solo para turno_mecanico)
        if ($validated['tipo'] === TurnoRodado::TIPO_TURNO_MECANICO && isset($validated['partes_afectadas'])) {
            $validated['partes_afectadas'] = json_encode($validated['partes_afectadas']);
        } else {
            $validated['partes_afectadas'] = null;
        }

        // No guardar factura_path, comprobante_pago_path, fecha_factura, dias_vencimiento, cubre_servicio para turno_taller/turno_mecanico
        // Estos se adjuntarán después
        $validated['cubre_servicio'] = false;
        $validated['tipo_reparacion'] = null;

        // Logging antes de crear
        \Log::info('Store turno - Datos finales para crear:', $validated);

        $turno = TurnoRodado::create($validated);

        \Log::info('Store turno - Turno creado:', ['id' => $turno->id, 'descripcion' => $turno->descripcion]);

        return redirect()->route('rodados.index')
            ->with('success', 'Turno creado exitosamente.');
    }

    public function update(Request $request, TurnoRodado $turno)
    {
        // Logging para depurar
        \Log::info('Update turno - Turno ID:', ['id' => $turno->id]);
        \Log::info('Update turno - Request data:', $request->all());
        \Log::info('Update turno - Descripcion recibida:', ['descripcion' => $request->input('descripcion')]);
        \Log::info('Update turno - Tipo recibido:', ['tipo' => $request->input('tipo')]);

        // Validación base
        $validated = $request->validate([
            'rodado_id' => 'required|exists:rodados,id',
            'taller_id' => 'required|exists:talleres,id',
            'tipo' => 'required|in:turno_service,turno_mecanico,turno_taller',
            'fecha_hora' => 'required|date',
            'encargado_dejar' => 'nullable|string|max:255',
            'encargado_retirar' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'partes_afectadas' => 'nullable|array',
            'partes_afectadas.*.item' => 'required_with:partes_afectadas|string|max:255',
            'partes_afectadas.*.cantidad' => 'required_with:partes_afectadas|integer|min:1',
            'partes_afectadas.*.descripcion' => 'required_with:partes_afectadas|string',
            'estado' => 'nullable|in:pendiente,completado,atendido,cancelado',
        ]);

        // Si es turno_taller, cambiar a turno_mecanico automáticamente
        if ($validated['tipo'] === 'turno_taller') {
            $validated['tipo'] = TurnoRodado::TIPO_TURNO_MECANICO;
        }

        // Validación condicional: descripcion es requerida para turno_mecanico
        if ($validated['tipo'] === TurnoRodado::TIPO_TURNO_MECANICO) {
            $request->validate([
                'descripcion' => 'required|string|min:1',
            ], [
                'descripcion.required' => 'El motivo del turno es obligatorio para turnos mecánicos.',
                'descripcion.min' => 'El motivo del turno no puede estar vacío.',
            ]);
            // Asegurar que descripcion esté en validated
            $validated['descripcion'] = $request->input('descripcion', '');
        } else {
            // Para turno_service, descripcion es opcional
            $validated['descripcion'] = $request->input('descripcion', null);
        }

        // Logging después de validación
        \Log::info('Update turno - Descripcion validada:', ['descripcion' => $validated['descripcion'] ?? 'null']);

        // Procesar partes afectadas (solo para turno_mecanico)
        if ($validated['tipo'] === TurnoRodado::TIPO_TURNO_MECANICO) {
            if (isset($validated['partes_afectadas']) && is_array($validated['partes_afectadas']) && count($validated['partes_afectadas']) > 0) {
                // Filtrar items vacíos antes de guardar
                $partesFiltradas = array_filter($validated['partes_afectadas'], function($parte) {
                    return !empty($parte['item']) || !empty($parte['cantidad']) || !empty($parte['descripcion']);
                });
                $validated['partes_afectadas'] = !empty($partesFiltradas) ? json_encode(array_values($partesFiltradas)) : null;
            } else {
                $validated['partes_afectadas'] = null;
            }
        } else {
            $validated['partes_afectadas'] = null;
        }

        // Limpiar campos no permitidos
        $validated['tipo_reparacion'] = null;

        // Logging antes de actualizar
        \Log::info('Update turno - Datos finales para actualizar:', $validated);

        $turno->update($validated);

        \Log::info('Update turno - Turno actualizado:', ['id' => $turno->id, 'descripcion' => $turno->descripcion]);

        return redirect()->route('rodados.index')
            ->with('success', 'Turno actualizado exitosamente.');
    }

    public function destroy(TurnoRodado $turno)
    {
        // Eliminar archivos asociados
        if ($turno->factura_path) {
            Storage::disk('public')->delete($turno->factura_path);
        }
        if ($turno->comprobante_pago_path) {
            Storage::disk('public')->delete($turno->comprobante_pago_path);
        }

        $turno->delete();

        return redirect()->route('rodados.index')
            ->with('success', 'Turno eliminado exitosamente.');
    }

    public function adjuntarFactura(Request $request, TurnoRodado $turno)
    {
        $validated = $request->validate([
            'factura' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'comprobante_pago' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Manejar factura
        if ($request->hasFile('factura')) {
            // Eliminar factura anterior si existe
            if ($turno->factura_path) {
                Storage::disk('public')->delete($turno->factura_path);
            }
            $factura = $request->file('factura');
            $validated['factura_path'] = $factura->store('rodados/' . $turno->rodado_id . '/facturas', 'public');
        }

        // Manejar comprobante de pago
        if ($request->hasFile('comprobante_pago')) {
            // Eliminar comprobante anterior si existe
            if ($turno->comprobante_pago_path) {
                Storage::disk('public')->delete($turno->comprobante_pago_path);
            }
            $comprobante = $request->file('comprobante_pago');
            $validated['comprobante_pago_path'] = $comprobante->store('rodados/' . $turno->rodado_id . '/comprobantes', 'public');
        }

        $turno->update($validated);

        return redirect()->route('rodados.index')
            ->with('success', 'Factura adjuntada exitosamente.');
    }

    public function aprobarCobertura(TurnoRodado $turno)
    {
        if ($turno->tipo !== TurnoRodado::TIPO_TURNO_MECANICO) {
            if ($turno->request()->expectsJson()) {
                return response()->json(['error' => 'Solo se puede aprobar cobertura para turnos mecánicos.'], 400);
            }
            return redirect()->route('rodados.index')
                ->with('error', 'Solo se puede aprobar cobertura para turnos mecánicos.');
        }

        $turno->update(['cubre_servicio' => true]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Cobertura aprobada exitosamente.']);
        }

        return redirect()->route('rodados.index')
            ->with('success', 'Cobertura aprobada exitosamente.');
    }

    public function rechazarCobertura(TurnoRodado $turno)
    {
        if ($turno->tipo !== TurnoRodado::TIPO_TURNO_MECANICO) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Solo se puede rechazar cobertura para turnos mecánicos.'], 400);
            }
            return redirect()->route('rodados.index')
                ->with('error', 'Solo se puede rechazar cobertura para turnos mecánicos.');
        }

        $turno->update(['cubre_servicio' => false]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Cobertura rechazada exitosamente.']);
        }

        return redirect()->route('rodados.index')
            ->with('success', 'Cobertura rechazada exitosamente.');
    }

    public function cancelarTurno(TurnoRodado $turno)
    {
        $turno->update(['estado' => TurnoRodado::ESTADO_CANCELADO]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Turno cancelado exitosamente.']);
        }

        return redirect()->route('rodados.index')
            ->with('success', 'Turno cancelado exitosamente.');
    }

    public function reprogramarTurno(Request $request, TurnoRodado $turno)
    {
        $validated = $request->validate([
            'fecha_hora' => 'required|date',
        ]);

        $turno->update([
            'fecha_hora' => $validated['fecha_hora'],
            'estado' => TurnoRodado::ESTADO_PENDIENTE,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Turno reprogramado exitosamente.']);
        }

        return redirect()->route('rodados.index')
            ->with('success', 'Turno reprogramado exitosamente.');
    }
}
