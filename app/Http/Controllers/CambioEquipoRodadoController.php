<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CambioEquipoRodado;
use Illuminate\Support\Facades\Storage;

class CambioEquipoRodadoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rodado_id' => 'required|exists:rodados,id',
            'taller_id' => 'required|exists:talleres,id',
            'tipo' => 'required|in:cubiertas,antena_starlink,camara_mobil,dvr',
            'fecha_hora_estimada' => 'required|date',
            'tipo_cubierta' => 'nullable|string|max:255',
            'pago_mano_obra' => 'required|numeric|min:0',
            'factura' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'comprobante_pago' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'kilometraje_en_cambio' => 'required|integer|min:0',
        ]);

        // Manejar archivos
        if ($request->hasFile('factura')) {
            $factura = $request->file('factura');
            $validated['factura_path'] = $factura->store('rodados/' . $validated['rodado_id'] . '/facturas', 'public');
        }

        if ($request->hasFile('comprobante_pago')) {
            $comprobante = $request->file('comprobante_pago');
            $validated['comprobante_pago_path'] = $comprobante->store('rodados/' . $validated['rodado_id'] . '/comprobantes', 'public');
        }

        // Limpiar tipo_cubierta si no es tipo cubiertas
        if ($validated['tipo'] !== 'cubiertas') {
            $validated['tipo_cubierta'] = null;
        }

        $cambio = CambioEquipoRodado::create($validated);

        return redirect()->route('rodados.index')
            ->with('success', 'Cambio de equipo registrado exitosamente.');
    }

    public function update(Request $request, CambioEquipoRodado $cambio)
    {
        $validated = $request->validate([
            'rodado_id' => 'required|exists:rodados,id',
            'taller_id' => 'required|exists:talleres,id',
            'tipo' => 'required|in:cubiertas,antena_starlink,camara_mobil,dvr',
            'fecha_hora_estimada' => 'required|date',
            'tipo_cubierta' => 'nullable|string|max:255',
            'pago_mano_obra' => 'required|numeric|min:0',
            'factura' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'comprobante_pago' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'kilometraje_en_cambio' => 'required|integer|min:0',
        ]);

        // Manejar archivos nuevos
        if ($request->hasFile('factura')) {
            if ($cambio->factura_path) {
                Storage::disk('public')->delete($cambio->factura_path);
            }
            $factura = $request->file('factura');
            $validated['factura_path'] = $factura->store('rodados/' . $validated['rodado_id'] . '/facturas', 'public');
        }

        if ($request->hasFile('comprobante_pago')) {
            if ($cambio->comprobante_pago_path) {
                Storage::disk('public')->delete($cambio->comprobante_pago_path);
            }
            $comprobante = $request->file('comprobante_pago');
            $validated['comprobante_pago_path'] = $comprobante->store('rodados/' . $validated['rodado_id'] . '/comprobantes', 'public');
        }

        // Limpiar tipo_cubierta si no es tipo cubiertas
        if ($validated['tipo'] !== 'cubiertas') {
            $validated['tipo_cubierta'] = null;
        }

        $cambio->update($validated);

        return redirect()->route('rodados.index')
            ->with('success', 'Cambio de equipo actualizado exitosamente.');
    }

    public function destroy(CambioEquipoRodado $cambio)
    {
        // Eliminar archivos asociados
        if ($cambio->factura_path) {
            Storage::disk('public')->delete($cambio->factura_path);
        }
        if ($cambio->comprobante_pago_path) {
            Storage::disk('public')->delete($cambio->comprobante_pago_path);
        }

        $cambio->delete();

        return redirect()->route('rodados.index')
            ->with('success', 'Cambio de equipo eliminado exitosamente.');
    }
}
