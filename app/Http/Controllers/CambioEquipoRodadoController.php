<?php

namespace App\Http\Controllers;

use App\Models\CambioEquipoRodado;
use App\Models\Dispositivo;
use Illuminate\Http\Request;
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
            'tipo_cubierta' => 'nullable|string|max:255|required_if:tipo,cubiertas',
            'pago_mano_obra' => 'nullable|numeric|min:0',
            'kilometraje_en_cambio' => 'nullable|integer|min:0',
            'motivo' => 'nullable|string',
            'dispositivo_id' => 'nullable|exists:dispositivos,id',
            'detalle_equipo_nuevo' => 'nullable|string',
            'detalle_equipo_viejo' => 'nullable|string',
            'factura_mano_obra' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Validar que si tipo requiere dispositivo, se proporcione dispositivo_id o detalle_equipo_nuevo
        $tiposQueRequierenDispositivo = [
            CambioEquipoRodado::TIPO_ANTENA_STARLINK,
            CambioEquipoRodado::TIPO_CAMARA_MOBIL,
            CambioEquipoRodado::TIPO_DVR,
        ];

        if (in_array($validated['tipo'], $tiposQueRequierenDispositivo)) {
            if (empty($validated['dispositivo_id']) && empty($validated['detalle_equipo_nuevo'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['dispositivo_id' => 'Debe seleccionar un dispositivo del inventario o proporcionar detalles del equipo nuevo.']);
            }
        }

        // Limpiar tipo_cubierta si no es tipo cubiertas
        if ($validated['tipo'] !== CambioEquipoRodado::TIPO_CUBIERTAS) {
            $validated['tipo_cubierta'] = null;
        }

        // Limpiar campos de dispositivo si no aplican
        if (! in_array($validated['tipo'], $tiposQueRequierenDispositivo)) {
            $validated['dispositivo_id'] = null;
            $validated['detalle_equipo_nuevo'] = null;
        }

        // Procesar dispositivo_id_viejo manual si viene del formulario
        if ($request->has('dispositivo_id_viejo') && $request->dispositivo_id_viejo === 'manual') {
            // Si es manual, el detalle está en detalle_equipo_viejo_manual
            if ($request->has('detalle_equipo_viejo_manual')) {
                $validated['detalle_equipo_viejo'] = $request->detalle_equipo_viejo_manual;
            }
        }

        // Procesar dispositivo_id cuando es "manual"
        if ($request->has('dispositivo_id') && $request->dispositivo_id === 'manual') {
            // El detalle del nuevo equipo ya está en detalle_equipo_nuevo
            $validated['dispositivo_id'] = null;
        }

        // Handle factura mano obra upload
        if ($request->hasFile('factura_mano_obra')) {
            $factura = $request->file('factura_mano_obra');
            $validated['factura_path'] = $factura->store('rodados/'.$validated['rodado_id'].'/facturas', 'public');
        }
        unset($validated['factura_mano_obra']);

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
            'tipo_cubierta' => 'nullable|string|max:255|required_if:tipo,cubiertas',
            'pago_mano_obra' => 'nullable|numeric|min:0',
            'kilometraje_en_cambio' => 'nullable|integer|min:0',
            'motivo' => 'nullable|string',
            'dispositivo_id' => 'nullable|exists:dispositivos,id',
            'detalle_equipo_nuevo' => 'nullable|string',
            'detalle_equipo_viejo' => 'nullable|string',
            'factura_mano_obra' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Validar que si tipo requiere dispositivo, se proporcione dispositivo_id o detalle_equipo_nuevo
        $tiposQueRequierenDispositivo = [
            CambioEquipoRodado::TIPO_ANTENA_STARLINK,
            CambioEquipoRodado::TIPO_CAMARA_MOBIL,
            CambioEquipoRodado::TIPO_DVR,
        ];

        if (in_array($validated['tipo'], $tiposQueRequierenDispositivo)) {
            if (empty($validated['dispositivo_id']) && empty($validated['detalle_equipo_nuevo'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['dispositivo_id' => 'Debe seleccionar un dispositivo del inventario o proporcionar detalles del equipo nuevo.']);
            }
        }

        // Limpiar tipo_cubierta si no es tipo cubiertas
        if ($validated['tipo'] !== CambioEquipoRodado::TIPO_CUBIERTAS) {
            $validated['tipo_cubierta'] = null;
        }

        // Limpiar campos de dispositivo si no aplican
        if (! in_array($validated['tipo'], $tiposQueRequierenDispositivo)) {
            $validated['dispositivo_id'] = null;
            $validated['detalle_equipo_nuevo'] = null;
        }

        // Procesar dispositivo_id_viejo manual si viene del formulario
        if ($request->has('dispositivo_id_viejo') && $request->dispositivo_id_viejo === 'manual') {
            // Si es manual, el detalle está en detalle_equipo_viejo_manual
            if ($request->has('detalle_equipo_viejo_manual')) {
                $validated['detalle_equipo_viejo'] = $request->detalle_equipo_viejo_manual;
            }
        }

        // Procesar dispositivo_id cuando es "manual"
        if ($request->has('dispositivo_id') && $request->dispositivo_id === 'manual') {
            // El detalle del nuevo equipo ya está en detalle_equipo_nuevo
            $validated['dispositivo_id'] = null;
        }

        // Handle factura mano obra upload
        if ($request->hasFile('factura_mano_obra')) {
            if ($cambio->factura_path) {
                Storage::disk('public')->delete($cambio->factura_path);
            }
            $factura = $request->file('factura_mano_obra');
            $validated['factura_path'] = $factura->store('rodados/'.$validated['rodado_id'].'/facturas', 'public');
        }
        unset($validated['factura_mano_obra']);

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

    public function adjuntarFactura(Request $request, CambioEquipoRodado $cambio)
    {
        $validated = $request->validate([
            'factura' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'comprobante_pago' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Manejar factura
        if ($request->hasFile('factura')) {
            // Eliminar factura anterior si existe
            if ($cambio->factura_path) {
                Storage::disk('public')->delete($cambio->factura_path);
            }
            $factura = $request->file('factura');
            $validated['factura_path'] = $factura->store('rodados/'.$cambio->rodado_id.'/facturas', 'public');
        }

        // Manejar comprobante de pago
        if ($request->hasFile('comprobante_pago')) {
            // Eliminar comprobante anterior si existe
            if ($cambio->comprobante_pago_path) {
                Storage::disk('public')->delete($cambio->comprobante_pago_path);
            }
            $comprobante = $request->file('comprobante_pago');
            $validated['comprobante_pago_path'] = $comprobante->store('rodados/'.$cambio->rodado_id.'/comprobantes', 'public');
        }

        $cambio->update($validated);

        return redirect()->route('rodados.index')
            ->with('success', 'Factura adjuntada exitosamente.');
    }
}
