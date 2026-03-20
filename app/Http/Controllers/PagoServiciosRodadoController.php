<?php

namespace App\Http\Controllers;

use App\Models\PagoServiciosRodado;
use App\Models\Rodado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PagoServiciosRodadoController extends Controller
{
    public function store(Request $request)
    {
        // Normalize empty strings to null for nullable foreign keys
        $input = $request->all();
        foreach (['rodado_id', 'servicio_usuario_id', 'proveedor_id'] as $field) {
            if (array_key_exists($field, $input) && $input[$field] === '') {
                $input[$field] = null;
            }
        }
        $request->merge($input);

        $rules = [
            'rodado_id' => 'nullable|exists:rodados,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'servicio_usuario_id' => 'nullable|exists:servicios_usuario,id',
            'tipo' => ['required', 'in:'.implode(',', [
                PagoServiciosRodado::TIPO_PAGO_PATENTE,
                PagoServiciosRodado::TIPO_PAGO_ALQUILER,
                PagoServiciosRodado::TIPO_PAGO_PROVEEDOR,
                PagoServiciosRodado::TIPO_PAGO_A_PROVEEDOR,
                PagoServiciosRodado::TIPO_PAGO_SEGURO,
                PagoServiciosRodado::TIPO_PAGO_SERVICIO_STARLINK,
                PagoServiciosRodado::TIPO_PAGO_VTV,
                PagoServiciosRodado::TIPO_PAGOS_ADICIONALES,
                PagoServiciosRodado::TIPO_PAGO_SERVICE,
                PagoServiciosRodado::TIPO_PAGO_TALLER,
            ])],
            'fecha_pago' => 'nullable|date',
            'fecha_vencimiento' => 'nullable|date',
            'moneda' => 'required|in:ARS,USD',
            'monto' => 'required|numeric|min:0',
            'monto_service' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string|max:500',
            'estado' => 'nullable|in:pendiente,pagado',
            'comprobante_pago' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'factura' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ];

        $validated = $request->validate($rules);

        // Ensure at least one of rodado_id or servicio_usuario_id is provided
        $hasServicio = ! empty($validated['servicio_usuario_id'] ?? null);
        $hasRodado = ! empty($validated['rodado_id'] ?? null);

        if (! $hasServicio && ! $hasRodado) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['rodado_id' => 'Seleccione un vehículo o un servicio mensual.']);
        }

        // Validar que proveedor_id sea requerido para pago_alquiler
        if ($validated['tipo'] === PagoServiciosRodado::TIPO_PAGO_ALQUILER && empty($validated['proveedor_id'] ?? null)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['proveedor_id' => 'El proveedor es requerido para pagos de alquiler.']);
        }

        // Limpiar monto_service si no es pago_alquiler
        if ($validated['tipo'] !== PagoServiciosRodado::TIPO_PAGO_ALQUILER) {
            $validated['monto_service'] = null;
        }

        // Ensure nullable fields are explicitly null if not set
        $validated['rodado_id'] = $validated['rodado_id'] ?? null;
        $validated['servicio_usuario_id'] = $validated['servicio_usuario_id'] ?? null;
        $validated['estado'] = $validated['estado'] ?? PagoServiciosRodado::ESTADO_PENDIENTE;

        // Si "Ya pagado": marcar estado y usar fecha_pago
        if ($validated['estado'] === 'pagado') {
            $validated['fecha_pago'] = $validated['fecha_pago'] ?? now()->toDateString();
        } else {
            $validated['fecha_pago'] = null;
        }

        // Adjuntar comprobante si se envió
        if ($request->hasFile('comprobante_pago')) {
            $file = $request->file('comprobante_pago');
            $rodadoId = $validated['rodado_id'] ?? 0;
            $validated['comprobante_pago_path'] = $file->store('rodados/'.$rodadoId.'/comprobantes', 'public');
        }

        // Adjuntar factura si se envió
        if ($request->hasFile('factura')) {
            $file = $request->file('factura');
            $rodadoId = $validated['rodado_id'] ?? 0;
            $validated['factura_path'] = $file->store('rodados/'.$rodadoId.'/facturas', 'public');
        }

        // Pago por vehículo: si es pago_proveedor y el rodado tiene proveedor, asignar
        if ($hasRodado && $validated['tipo'] === PagoServiciosRodado::TIPO_PAGO_PROVEEDOR) {
            $rodado = Rodado::find($validated['rodado_id']);
            if ($rodado && $rodado->proveedor_id) {
                $validated['proveedor_id'] = $rodado->proveedor_id;
            }
        }

        // Remove non-model fields before creating
        unset($validated['comprobante_pago'], $validated['factura']);

        PagoServiciosRodado::create($validated);

        return redirect()->route('rodados.pagos-servicios.index')
            ->with('success', 'Pago registrado exitosamente.');
    }

    public function update(Request $request, PagoServiciosRodado $pago)
    {
        $validated = $request->validate([
            'rodado_id' => 'required|exists:rodados,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'tipo' => ['required', 'in:'.implode(',', [
                PagoServiciosRodado::TIPO_PAGO_PATENTE,
                PagoServiciosRodado::TIPO_PAGO_ALQUILER,
                PagoServiciosRodado::TIPO_PAGO_PROVEEDOR,
                PagoServiciosRodado::TIPO_PAGO_A_PROVEEDOR,
                PagoServiciosRodado::TIPO_PAGO_SEGURO,
                PagoServiciosRodado::TIPO_PAGO_SERVICIO_STARLINK,
                PagoServiciosRodado::TIPO_PAGO_VTV,
                PagoServiciosRodado::TIPO_PAGOS_ADICIONALES,
                PagoServiciosRodado::TIPO_PAGO_SERVICE,
                PagoServiciosRodado::TIPO_PAGO_TALLER,
            ])],
            'fecha_pago' => 'nullable|date',
            'moneda' => 'required|in:ARS,USD',
            'monto' => 'required|numeric|min:0',
            'monto_service' => 'nullable|numeric|min:0',
        ]);

        // Validar que proveedor_id sea requerido para pago_alquiler
        if ($validated['tipo'] === PagoServiciosRodado::TIPO_PAGO_ALQUILER && empty($validated['proveedor_id'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['proveedor_id' => 'El proveedor es requerido para pagos de alquiler.']);
        }

        // Limpiar monto_service si no es pago_alquiler
        if ($validated['tipo'] !== PagoServiciosRodado::TIPO_PAGO_ALQUILER) {
            $validated['monto_service'] = null;
        }

        $pago->update($validated);

        return redirect()->route('rodados.pagos-servicios.index')
            ->with('success', 'Pago actualizado exitosamente.');
    }

    public function destroy(PagoServiciosRodado $pago)
    {
        // Eliminar archivos asociados
        if ($pago->factura_path) {
            Storage::disk('public')->delete($pago->factura_path);
        }
        if ($pago->comprobante_pago_path) {
            Storage::disk('public')->delete($pago->comprobante_pago_path);
        }

        $pago->delete();

        return redirect()->route('rodados.pagos-servicios.index')
            ->with('success', 'Pago eliminado exitosamente.');
    }

    public function adjuntarFactura(Request $request, PagoServiciosRodado $pago)
    {
        $validated = $request->validate([
            'factura' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'comprobante_pago' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $updateData = [];

        // Manejar factura
        if ($request->hasFile('factura')) {
            if ($pago->factura_path) {
                Storage::disk('public')->delete($pago->factura_path);
            }
            $factura = $request->file('factura');
            $rodadoIdForPath = $pago->rodado_id ?? 0;
            $updateData['factura_path'] = $factura->store('rodados/'.$rodadoIdForPath.'/facturas', 'public');
        }

        // Manejar comprobante de pago
        if ($request->hasFile('comprobante_pago')) {
            if ($pago->comprobante_pago_path) {
                Storage::disk('public')->delete($pago->comprobante_pago_path);
            }
            $comprobante = $request->file('comprobante_pago');
            $rodadoIdForPath = $pago->rodado_id ?? 0;
            $updateData['comprobante_pago_path'] = $comprobante->store('rodados/'.$rodadoIdForPath.'/comprobantes', 'public');

            // Marcar como pagado y establecer fecha de pago
            $updateData['estado'] = PagoServiciosRodado::ESTADO_PAGADO;
            $updateData['fecha_pago'] = now()->toDateString();

            // Si este pago está vinculado a un turno, actualizar también el comprobante en el turno
            if ($pago->turno_rodado_id) {
                $turno = \App\Models\TurnoRodado::find($pago->turno_rodado_id);
                if ($turno) {
                    $turno->update([
                        'comprobante_pago_path' => $updateData['comprobante_pago_path'],
                    ]);
                }
            }
        }

        if (! empty($updateData)) {
            $pago->update($updateData);
        }

        return redirect()->route('rodados.pagos-servicios.index')
            ->with('success', $request->hasFile('comprobante_pago') ? 'Comprobante adjuntado. Pago marcado como realizado.' : 'Documentación actualizada exitosamente.');
    }

    public function adjuntarComprobanteBatch(Request $request)
    {
        $request->validate([
            'comprobante_pago' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'pago_ids' => 'required|array|min:1',
            'pago_ids.*' => 'exists:pago_servicios_rodados,id',
        ]);

        $pagos = PagoServiciosRodado::whereIn('id', $request->pago_ids)->get();

        $noPendientes = $pagos->where('estado', '!=', PagoServiciosRodado::ESTADO_PENDIENTE);
        if ($noPendientes->isNotEmpty()) {
            return redirect()->route('rodados.pagos-servicios.index')
                ->with('error', 'Algunos pagos seleccionados no están en estado pendiente.');
        }

        $conComprobante = $pagos->whereNotNull('comprobante_pago_path');
        if ($conComprobante->isNotEmpty()) {
            return redirect()->route('rodados.pagos-servicios.index')
                ->with('error', 'Algunos pagos seleccionados ya tienen comprobante adjunto.');
        }

        $proveedores = $pagos->pluck('proveedor_id')->unique()->filter();
        if ($proveedores->count() > 1) {
            return redirect()->route('rodados.pagos-servicios.index')
                ->with('error', 'Todos los pagos seleccionados deben ser del mismo proveedor.');
        }

        $comprobantePath = $request->file('comprobante_pago')
            ->store('rodados/comprobantes-batch', 'public');

        foreach ($pagos as $pago) {
            $pago->update([
                'comprobante_pago_path' => $comprobantePath,
                'estado' => PagoServiciosRodado::ESTADO_PAGADO,
                'fecha_pago' => now()->toDateString(),
            ]);

            if ($pago->turno_rodado_id) {
                $turno = \App\Models\TurnoRodado::find($pago->turno_rodado_id);
                if ($turno) {
                    $turno->update(['comprobante_pago_path' => $comprobantePath]);
                }
            }
        }

        $count = $pagos->count();

        return redirect()->route('rodados.pagos-servicios.index')
            ->with('success', "Comprobante adjuntado a {$count} pagos. Marcados como pagados.");
    }
}
