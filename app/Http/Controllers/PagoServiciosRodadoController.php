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
            'tipo' => 'required|in:pago_patente,pago_alquiler,pago_proveedor',
            'mes' => 'required|integer|min:1|max:12',
            'año' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'monto' => 'required|numeric|min:0',
            'monto_service' => 'nullable|numeric|min:0',
            'factura' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'comprobante_pago' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'fecha_pago' => 'required|date',
        ]);

        // Validar que proveedor_id sea requerido para pago_alquiler
        if ($validated['tipo'] === 'pago_alquiler' && empty($validated['proveedor_id'])) {
            return redirect()->back()
                ->with('error', 'El proveedor es requerido para pagos de alquiler.');
        }

        // Limpiar monto_service si no es pago_alquiler
        if ($validated['tipo'] !== 'pago_alquiler') {
            $validated['monto_service'] = null;
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

        PagoServiciosRodado::create($validated);

        return redirect()->route('rodados.index')
            ->with('success', 'Pago registrado exitosamente.');
    }

    public function update(Request $request, PagoServiciosRodado $pago)
    {
        $validated = $request->validate([
            'rodado_id' => 'required|exists:rodados,id',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'tipo' => 'required|in:pago_patente,pago_alquiler,pago_proveedor',
            'mes' => 'required|integer|min:1|max:12',
            'año' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'monto' => 'required|numeric|min:0',
            'monto_service' => 'nullable|numeric|min:0',
            'factura' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'comprobante_pago' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'fecha_pago' => 'required|date',
        ]);

        // Validar que proveedor_id sea requerido para pago_alquiler
        if ($validated['tipo'] === 'pago_alquiler' && empty($validated['proveedor_id'])) {
            return redirect()->back()
                ->with('error', 'El proveedor es requerido para pagos de alquiler.');
        }

        // Limpiar monto_service si no es pago_alquiler
        if ($validated['tipo'] !== 'pago_alquiler') {
            $validated['monto_service'] = null;
        }

        // Manejar archivos nuevos
        if ($request->hasFile('factura')) {
            if ($pago->factura_path) {
                Storage::disk('public')->delete($pago->factura_path);
            }
            $factura = $request->file('factura');
            $validated['factura_path'] = $factura->store('rodados/' . $validated['rodado_id'] . '/facturas', 'public');
        }

        if ($request->hasFile('comprobante_pago')) {
            if ($pago->comprobante_pago_path) {
                Storage::disk('public')->delete($pago->comprobante_pago_path);
            }
            $comprobante = $request->file('comprobante_pago');
            $validated['comprobante_pago_path'] = $comprobante->store('rodados/' . $validated['rodado_id'] . '/comprobantes', 'public');
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
}
