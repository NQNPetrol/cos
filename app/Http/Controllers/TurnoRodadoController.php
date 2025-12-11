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
        $validated = $request->validate([
            'rodado_id' => 'required|exists:rodados,id',
            'taller_id' => 'required|exists:talleres,id',
            'tipo' => 'required|in:turno_service,turno_mecanico,cambio_equipo,turno_taller',
            'fecha_hora' => 'required|date',
            'encargado_dejar' => 'nullable|string|max:255',
            'encargado_retirar' => 'nullable|string|max:255',
            'tipo_reparacion' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'cubre_servicio' => 'nullable|boolean',
            'tipo_equipo' => 'nullable|string|max:255',
            'tipo_cubierta' => 'nullable|string|max:255',
            'pago_mano_obra' => 'nullable|numeric|min:0',
            'factura' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'comprobante_pago' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'fecha_factura' => 'nullable|date',
            'dias_vencimiento' => 'nullable|integer|min:0',
        ]);

        // Calcular fecha de vencimiento si hay factura y días de vencimiento
        if (!empty($validated['fecha_factura']) && !empty($validated['dias_vencimiento'])) {
            $validated['fecha_vencimiento_pago'] = \Carbon\Carbon::parse($validated['fecha_factura'])
                ->addDays($validated['dias_vencimiento']);
        }

        // Manejar archivos
        if ($request->hasFile('factura')) {
            $factura = $request->file('factura');
            $validated['factura_path'] = $factura->store('rodados/' . $validated['rodado_id'] . '/facturas', 'public');
        }

        if ($request->hasFile('comprobante_pago')) {
            $comprobante = $request->file('comprobante_pago');
            $validated['comprobante_pago_path'] = $comprobante->store('rodados/' . $validated['rodado_id'] . '/comprobantes', 'public');
        }

        // Limpiar campos según el tipo
        if ($validated['tipo'] !== 'turno_mecanico') {
            $validated['cubre_servicio'] = false;
            $validated['tipo_reparacion'] = null;
        }

        if ($validated['tipo'] !== 'cambio_equipo') {
            $validated['tipo_equipo'] = null;
            $validated['tipo_cubierta'] = null;
            $validated['pago_mano_obra'] = null;
        }

        $turno = TurnoRodado::create($validated);

        return redirect()->route('rodados.index')
            ->with('success', 'Turno creado exitosamente.');
    }

    public function update(Request $request, TurnoRodado $turno)
    {
        $validated = $request->validate([
            'rodado_id' => 'required|exists:rodados,id',
            'taller_id' => 'required|exists:talleres,id',
            'tipo' => 'required|in:turno_service,turno_mecanico,cambio_equipo,turno_taller',
            'fecha_hora' => 'required|date',
            'encargado_dejar' => 'nullable|string|max:255',
            'encargado_retirar' => 'nullable|string|max:255',
            'tipo_reparacion' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'cubre_servicio' => 'nullable|boolean',
            'tipo_equipo' => 'nullable|string|max:255',
            'tipo_cubierta' => 'nullable|string|max:255',
            'pago_mano_obra' => 'nullable|numeric|min:0',
            'factura' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'comprobante_pago' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'fecha_factura' => 'nullable|date',
            'dias_vencimiento' => 'nullable|integer|min:0',
            'estado' => 'nullable|in:pendiente,completado',
        ]);

        // Calcular fecha de vencimiento
        if (!empty($validated['fecha_factura']) && !empty($validated['dias_vencimiento'])) {
            $validated['fecha_vencimiento_pago'] = \Carbon\Carbon::parse($validated['fecha_factura'])
                ->addDays($validated['dias_vencimiento']);
        }

        // Manejar archivos nuevos
        if ($request->hasFile('factura')) {
            // Eliminar factura anterior si existe
            if ($turno->factura_path) {
                Storage::disk('public')->delete($turno->factura_path);
            }
            $factura = $request->file('factura');
            $validated['factura_path'] = $factura->store('rodados/' . $validated['rodado_id'] . '/facturas', 'public');
        }

        if ($request->hasFile('comprobante_pago')) {
            // Eliminar comprobante anterior si existe
            if ($turno->comprobante_pago_path) {
                Storage::disk('public')->delete($turno->comprobante_pago_path);
            }
            $comprobante = $request->file('comprobante_pago');
            $validated['comprobante_pago_path'] = $comprobante->store('rodados/' . $validated['rodado_id'] . '/comprobantes', 'public');
        }

        // Limpiar campos según el tipo
        if ($validated['tipo'] !== 'turno_mecanico') {
            $validated['cubre_servicio'] = false;
            $validated['tipo_reparacion'] = null;
        }

        if ($validated['tipo'] !== 'cambio_equipo') {
            $validated['tipo_equipo'] = null;
            $validated['tipo_cubierta'] = null;
            $validated['pago_mano_obra'] = null;
        }

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
}
