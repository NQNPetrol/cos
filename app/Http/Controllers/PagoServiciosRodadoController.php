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
            ])],
            'fecha_pago' => 'required|date',
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

        PagoServiciosRodado::create($validated);

        return redirect()->route('rodados.index')
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
            ])],
            'fecha_pago' => 'required|date',
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

        return redirect()->route('rodados.index')
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

        return redirect()->route('rodados.index')
            ->with('success', 'Pago eliminado exitosamente.');
    }

    public function adjuntarFactura(Request $request, PagoServiciosRodado $pago)
    {
        $validated = $request->validate([
            'factura' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'comprobante_pago' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Manejar factura
        if ($request->hasFile('factura')) {
            // Eliminar factura anterior si existe
            if ($pago->factura_path) {
                Storage::disk('public')->delete($pago->factura_path);
            }
            $factura = $request->file('factura');
            $validated['factura_path'] = $factura->store('rodados/' . $pago->rodado_id . '/facturas', 'public');
        }

        // Manejar comprobante de pago
        if ($request->hasFile('comprobante_pago')) {
            // Eliminar comprobante anterior si existe
            if ($pago->comprobante_pago_path) {
                Storage::disk('public')->delete($pago->comprobante_pago_path);
            }
            $comprobante = $request->file('comprobante_pago');
            $validated['comprobante_pago_path'] = $comprobante->store('rodados/' . $pago->rodado_id . '/comprobantes', 'public');
        }

        $pago->update($validated);

        return redirect()->route('rodados.index')
            ->with('success', 'Factura adjuntada exitosamente.');
    }
}
