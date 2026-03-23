<?php

namespace App\Http\Controllers;

use App\Mail\CoberturaRechazadaMail;
use App\Models\Notification;
use App\Models\PagoServiciosRodado;
use App\Models\Rodado;
use App\Models\TurnoRodado;
use App\Models\UserCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TurnoRodadoController extends Controller
{
    public function show(TurnoRodado $turno)
    {
        // Cargar relaciones necesarias
        $turno->load(['rodado', 'taller']);

        // Obtener partes afectadas directamente del atributo raw para evitar el accessor
        $partesAfectadasRaw = $turno->getAttributes()['partes_afectadas'] ?? null;

        $partesAfectadas = [];

        if ($partesAfectadasRaw) {
            if (is_string($partesAfectadasRaw)) {
                $decoded = json_decode($partesAfectadasRaw, true);

                if (is_array($decoded)) {
                    if (array_keys($decoded) !== range(0, count($decoded) - 1)) {
                        $partesAfectadas = array_values($decoded);
                    } else {
                        $partesAfectadas = $decoded;
                    }
                } elseif (is_object($decoded)) {
                    $partesAfectadas = array_values((array) $decoded);
                }
            } elseif (is_array($partesAfectadasRaw)) {
                $partesAfectadas = $partesAfectadasRaw;
            }
        }

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
            'cobertura_estado' => $turno->cobertura_estado ?? 'pendiente',
            'cubre_servicio' => $turno->cubre_servicio ?? false,
            'informe_path' => $turno->informe_path,
            'factura_path' => $turno->factura_path,
            'comprobante_pago_path' => $turno->comprobante_pago_path,
            'rodado' => $turno->rodado ? [
                'id' => $turno->rodado->id,
                'patente' => $turno->rodado->patente,
                'marca' => $turno->rodado->marca,
                'modelo' => $turno->rodado->modelo,
                'tipo_vehiculo' => $turno->rodado->tipo_vehiculo,
            ] : null,
            'taller' => $turno->taller ? [
                'id' => $turno->taller->id,
                'nombre' => $turno->taller->nombre,
                'whatsapp' => $turno->taller->whatsapp,
                'email' => $turno->taller->email ?? null,
            ] : null,
        ];

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
            'estado' => 'nullable|in:programado,asistido,cancelado,perdido',
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
                $partesFiltradas = array_filter($validated['partes_afectadas'], function ($parte) {
                    return ! empty($parte['item']) || ! empty($parte['cantidad']) || ! empty($parte['descripcion']);
                });
                $validated['partes_afectadas'] = ! empty($partesFiltradas) ? json_encode(array_values($partesFiltradas)) : null;
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
            'fecha_vencimiento_pago' => 'nullable|date',
            'monto_factura' => 'nullable|numeric|min:0',
        ]);

        // Extraer monto_factura antes de update (no es columna de turnos)
        $montoFactura = $request->input('monto_factura');
        unset($validated['monto_factura']);

        // Manejar factura
        if ($request->hasFile('factura')) {
            if ($turno->factura_path) {
                Storage::disk('public')->delete($turno->factura_path);
            }
            $factura = $request->file('factura');
            $validated['factura_path'] = $factura->store('rodados/'.$turno->rodado_id.'/facturas', 'public');

            // Establecer fecha de factura
            $validated['fecha_factura'] = now()->toDateString();

            // Si se envió fecha de vencimiento desde el formulario (turno mecánico con cobertura aprobada)
            if ($request->filled('fecha_vencimiento_pago')) {
                $fechaVencimiento = \Carbon\Carbon::parse($request->fecha_vencimiento_pago);
                $validated['fecha_vencimiento_pago'] = $fechaVencimiento->toDateString();
                $validated['dias_vencimiento'] = now()->diffInDays($fechaVencimiento);
            } else {
                // Auto-set fecha vencimiento a 30 dias si rodado tiene proveedor
                $rodado = $turno->rodado;
                if ($rodado && $rodado->proveedor_id) {
                    $validated['fecha_vencimiento_pago'] = now()->addDays(30)->toDateString();
                    $validated['dias_vencimiento'] = 30;
                }
            }
        }

        $turno->update($validated);

        // Auto-crear pago pendiente cuando se adjunta factura a un turno (si no existe ya)
        if ($request->hasFile('factura')) {
            $existingPago = PagoServiciosRodado::where('turno_rodado_id', $turno->id)->first();

            // Usar el monto ingresado en el formulario, o fallback a pago_mano_obra, o 0
            $montoPago = $montoFactura !== null ? (float) $montoFactura : ($turno->pago_mano_obra ?? 0);

            if (! $existingPago) {
                $tipoPago = $turno->tipo === TurnoRodado::TIPO_TURNO_SERVICE
                    ? PagoServiciosRodado::TIPO_PAGO_SERVICE
                    : PagoServiciosRodado::TIPO_PAGO_TALLER;

                PagoServiciosRodado::create([
                    'rodado_id' => $turno->rodado_id,
                    'turno_rodado_id' => $turno->id,
                    'tipo' => $tipoPago,
                    'monto' => $montoPago,
                    'factura_path' => $turno->factura_path,
                    'moneda' => 'ARS',
                    'estado' => PagoServiciosRodado::ESTADO_PENDIENTE,
                    'fecha_vencimiento' => $turno->fecha_vencimiento_pago,
                    'observaciones' => $turno->tipo === TurnoRodado::TIPO_TURNO_SERVICE
                        ? 'Service - '.($turno->rodado->patente ?? 'N/A')
                        : 'Taller Mecánico - '.($turno->rodado->patente ?? 'N/A'),
                ]);
            } else {
                // Actualizar factura en pago existente si cambió
                $existingPago->update([
                    'factura_path' => $turno->factura_path,
                    'monto' => $montoPago,
                    'fecha_vencimiento' => $turno->fecha_vencimiento_pago ?? $existingPago->fecha_vencimiento,
                ]);
            }
        }

        return redirect()->route('rodados.index')
            ->with('success', 'Factura adjuntada exitosamente.');
    }

    /**
     * Unified documentation upload for turnos with proveedor (informe + factura + comprobante)
     */
    public function adjuntarDocumentacion(Request $request, TurnoRodado $turno)
    {
        $request->validate([
            'informe' => 'nullable|file|mimes:pdf|max:10240',
            'factura' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'comprobante_pago' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'monto_factura' => 'nullable|numeric|min:0',
        ]);

        // Extraer monto_factura (no es columna de turnos)
        $montoFactura = $request->input('monto_factura');

        $updateData = [];

        // Handle informe
        if ($request->hasFile('informe')) {
            if ($turno->informe_path) {
                Storage::disk('public')->delete($turno->informe_path);
            }
            $updateData['informe_path'] = $request->file('informe')
                ->store('rodados/'.$turno->rodado_id.'/informes', 'public');
        }

        // Handle factura
        if ($request->hasFile('factura')) {
            if ($turno->factura_path) {
                Storage::disk('public')->delete($turno->factura_path);
            }
            $updateData['factura_path'] = $request->file('factura')
                ->store('rodados/'.$turno->rodado_id.'/facturas', 'public');

            // Auto-set fecha vencimiento if proveedor
            $rodado = $turno->rodado;
            if ($rodado && $rodado->proveedor_id) {
                $updateData['fecha_factura'] = now()->toDateString();
                $updateData['fecha_vencimiento_pago'] = now()->addDays(30)->toDateString();
                $updateData['dias_vencimiento'] = 30;
            }
        }

        // Handle comprobante de pago
        if ($request->hasFile('comprobante_pago')) {
            if ($turno->comprobante_pago_path) {
                Storage::disk('public')->delete($turno->comprobante_pago_path);
            }
            $updateData['comprobante_pago_path'] = $request->file('comprobante_pago')
                ->store('rodados/'.$turno->rodado_id.'/comprobantes', 'public');
        }

        if (! empty($updateData)) {
            $turno->update($updateData);
        }

        // Auto-crear pago pendiente cuando se adjunta factura a un turno (si no existe ya).
        // No aplica a turno_service con rodado de proveedor (alquilado): esos se pagan
        // en conjunto (services + alquiler) y el usuario crea el pago manualmente.
        $rodado = $turno->rodado;
        $esServiceDeProveedor = $turno->tipo === TurnoRodado::TIPO_TURNO_SERVICE
            && $rodado && $rodado->proveedor_id;

        if ($request->hasFile('factura') && ! $esServiceDeProveedor) {
            $turno->refresh();
            $existingPago = PagoServiciosRodado::where('turno_rodado_id', $turno->id)->first();

            $montoPago = $montoFactura !== null ? (float) $montoFactura : ($turno->pago_mano_obra ?? 0);

            if (! $existingPago) {
                $tipoPago = $turno->tipo === TurnoRodado::TIPO_TURNO_SERVICE
                    ? PagoServiciosRodado::TIPO_PAGO_SERVICE
                    : PagoServiciosRodado::TIPO_PAGO_TALLER;

                PagoServiciosRodado::create([
                    'rodado_id' => $turno->rodado_id,
                    'turno_rodado_id' => $turno->id,
                    'tipo' => $tipoPago,
                    'monto' => $montoPago,
                    'factura_path' => $turno->factura_path,
                    'moneda' => 'ARS',
                    'estado' => PagoServiciosRodado::ESTADO_PENDIENTE,
                    'fecha_vencimiento' => $turno->fecha_vencimiento_pago,
                    'observaciones' => $turno->tipo === TurnoRodado::TIPO_TURNO_SERVICE
                        ? 'Service - '.($turno->rodado->patente ?? 'N/A')
                        : 'Taller Mecánico - '.($turno->rodado->patente ?? 'N/A'),
                ]);
            } else {
                $existingPago->update([
                    'factura_path' => $turno->factura_path,
                    'monto' => $montoPago,
                    'fecha_vencimiento' => $turno->fecha_vencimiento_pago ?? $existingPago->fecha_vencimiento,
                ]);
            }
        }

        if (empty($updateData)) {
            return redirect()->route('rodados.index')
                ->with('error', 'No se recibió ningún archivo. Verificá que hayas seleccionado al menos un documento y que no exceda el límite de subida del servidor.');
        }

        return redirect()->route('rodados.index')
            ->with('success', 'Documentación adjuntada exitosamente.');
    }

    public function aprobarCobertura(TurnoRodado $turno)
    {
        if ($turno->tipo !== TurnoRodado::TIPO_TURNO_MECANICO) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Solo se puede aprobar cobertura para turnos mecánicos.'], 400);
            }

            return redirect()->route('rodados.index')
                ->with('error', 'Solo se puede aprobar cobertura para turnos mecánicos.');
        }

        $turno->update([
            'cubre_servicio' => true,
            'cobertura_estado' => TurnoRodado::COBERTURA_APROBADA,
        ]);

        $taller = $turno->taller;
        $responseData = [
            'success' => true,
            'message' => 'Cobertura aprobada exitosamente. La empresa cubrirá los gastos del reparo.',
            'taller_whatsapp' => $taller->whatsapp_link ?? null,
            'taller_email' => $taller->mailto_link ?? null,
            'taller_nombre' => $taller->nombre ?? 'N/A',
        ];

        if (request()->expectsJson()) {
            return response()->json($responseData);
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

        $turno->update([
            'cubre_servicio' => false,
            'cobertura_estado' => TurnoRodado::COBERTURA_RECHAZADA,
        ]);

        // Send notification and email to client user
        $rodado = $turno->rodado;
        if ($rodado && $rodado->cliente_id) {
            $userClientes = UserCliente::where('cliente_id', $rodado->cliente_id)->get();
            foreach ($userClientes as $userCliente) {
                Notification::create([
                    'title' => 'Cobertura Rechazada',
                    'message' => 'La cobertura para el vehículo '.($rodado->patente ?? 'Sin patente').' ha sido rechazada. Turno del '.$turno->fecha_hora->format('d/m/Y H:i'),
                    'type' => 'user',
                    'user_id' => $userCliente->user_id,
                    'priority' => 'ALTA',
                    'is_active' => true,
                ]);

                // Send email notification
                try {
                    $user = \App\Models\User::find($userCliente->user_id);
                    if ($user && $user->email) {
                        Mail::to($user->email)->send(new CoberturaRechazadaMail($turno, $user->name));
                    }
                } catch (\Exception $e) {
                    \Log::error('Error enviando email de cobertura rechazada: '.$e->getMessage());
                }
            }
        }

        $taller = $turno->taller;
        $responseData = [
            'success' => true,
            'message' => 'Cobertura rechazada. Se ha notificado al cliente.',
            'taller_whatsapp' => $taller->whatsapp_link ?? null,
            'taller_email' => $taller->mailto_link ?? null,
            'taller_nombre' => $taller->nombre ?? 'N/A',
        ];

        if (request()->expectsJson()) {
            return response()->json($responseData);
        }

        return redirect()->route('rodados.index')
            ->with('success', 'Cobertura rechazada. Se ha notificado al cliente.');
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

    public function confirmarEstado(Request $request, TurnoRodado $turno)
    {
        $validated = $request->validate([
            'estado' => 'required|in:asistido,perdido',
        ]);

        $turno->update(['estado' => $validated['estado']]);

        $label = $validated['estado'] === 'asistido' ? 'Asistencia confirmada' : 'Turno marcado como perdido';

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => "$label exitosamente."]);
        }

        return redirect()->route('rodados.index')
            ->with('success', "$label exitosamente.");
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
