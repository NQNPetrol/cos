<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PagoServiciosRodado;
use App\Models\Rodado;
use Illuminate\Support\Facades\Storage;

class PagoServiciosRodadoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rodado_id' => 'required|exists:rodados,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'tipo' => ['required', 'in:' . implode(',', [
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

        // Establecer estado pendiente por defecto
        $validated['estado'] = PagoServiciosRodado::ESTADO_PENDIENTE;

        PagoServiciosRodado::create($validated);

        return redirect()->route('rodados.pagos-servicios.index')
            ->with('success', 'Pago registrado exitosamente.');
    }

    public function update(Request $request, PagoServiciosRodado $pago)
    {
        $validated = $request->validate([
            'rodado_id' => 'required|exists:rodados,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'tipo' => ['required', 'in:' . implode(',', [
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
            $updateData['factura_path'] = $factura->store('rodados/' . $pago->rodado_id . '/facturas', 'public');
        }

        // Manejar comprobante de pago
        if ($request->hasFile('comprobante_pago')) {
            if ($pago->comprobante_pago_path) {
                Storage::disk('public')->delete($pago->comprobante_pago_path);
            }
            $comprobante = $request->file('comprobante_pago');
            $updateData['comprobante_pago_path'] = $comprobante->store('rodados/' . $pago->rodado_id . '/comprobantes', 'public');

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

        if (!empty($updateData)) {
            $pago->update($updateData);
        }

        return redirect()->route('rodados.pagos-servicios.index')
            ->with('success', $request->hasFile('comprobante_pago') ? 'Comprobante adjuntado. Pago marcado como realizado.' : 'Documentación actualizada exitosamente.');
    }
}
