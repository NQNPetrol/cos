<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TurnoRodado;
use App\Models\Rodado;
use App\Models\Taller;
use Illuminate\Support\Facades\Storage;

class TurnoRodadoController extends Controller
{
    public function store(Request $request)
    {
        // Validación base
        $validated = $request->validate([
            'rodado_id' => 'required|exists:rodados,id',
            'taller_id' => 'required|exists:talleres,id',
            'tipo' => 'required|in:turno_service,turno_mecanico,turno_taller',
            'fecha_hora' => 'required|date',
            'encargado_dejar' => 'nullable|string|max:255',
            'encargado_retirar' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'motivo_turno' => 'nullable|string',
            'partes_afectadas' => 'nullable|array',
            'partes_afectadas.*.item' => 'required_with:partes_afectadas|string|max:255',
            'partes_afectadas.*.cantidad' => 'required_with:partes_afectadas|integer|min:1',
            'partes_afectadas.*.descripcion' => 'required_with:partes_afectadas|string',
        ]);

        // Si es turno_taller, cambiar a turno_mecanico automáticamente
        if ($validated['tipo'] === 'turno_taller') {
            $validated['tipo'] = TurnoRodado::TIPO_TURNO_MECANICO;
        }

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

        $turno = TurnoRodado::create($validated);

        return redirect()->route('rodados.index')
            ->with('success', 'Turno creado exitosamente.');
    }

    public function update(Request $request, TurnoRodado $turno)
    {
        // Validación base
        $validated = $request->validate([
            'rodado_id' => 'required|exists:rodados,id',
            'taller_id' => 'required|exists:talleres,id',
            'tipo' => 'required|in:turno_service,turno_mecanico,turno_taller',
            'fecha_hora' => 'required|date',
            'encargado_dejar' => 'nullable|string|max:255',
            'encargado_retirar' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'motivo_turno' => 'nullable|string',
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

        // Procesar partes afectadas (solo para turno_mecanico)
        if ($validated['tipo'] === TurnoRodado::TIPO_TURNO_MECANICO && isset($validated['partes_afectadas'])) {
            $validated['partes_afectadas'] = json_encode($validated['partes_afectadas']);
        } else {
            $validated['partes_afectadas'] = null;
        }

        // Limpiar campos no permitidos
            $validated['tipo_reparacion'] = null;

        $turno->update($validated);

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
