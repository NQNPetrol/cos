<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AlertaAdmin;
use App\Models\Notification;
use App\Models\Rodado;
use App\Models\Cliente;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\AlertaNotificationMail;

class AlertaAdminController extends Controller
{
    public function index()
    {
        $alertas = AlertaAdmin::with(['user', 'rodado', 'cliente'])
            ->latest()
            ->get();

        $rodados = Rodado::with(['cliente', 'proveedor'])->get();
        $clientes = Cliente::orderBy('nombre')->get();

        return view('rodados.alertas-admin', compact('alertas', 'rodados', 'clientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:vencimiento_pago,recordatorio_turno,agendar_turno_km,personalizada',
            'rodado_id' => 'nullable|exists:rodados,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'km_intervalo' => 'nullable|integer|min:1',
            'fecha_alerta' => 'nullable|date',
            'recurrente' => 'nullable|boolean',
            'frecuencia_recurrencia' => 'nullable|in:diaria,semanal,mensual',
            'acciones' => 'required|array|min:1',
            'acciones.*' => 'in:dashboard,notificacion,correo',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['accion_config'] = $validated['acciones'];
        $validated['activa'] = true;
        unset($validated['acciones']);

        // Build trigger config based on tipo
        $triggerConfig = [];
        if ($validated['tipo'] === AlertaAdmin::TIPO_AGENDAR_TURNO_KM && $validated['km_intervalo']) {
            $triggerConfig['km_intervalo'] = $validated['km_intervalo'];
        }
        if ($validated['fecha_alerta']) {
            $triggerConfig['fecha'] = $validated['fecha_alerta'];
        }
        if (!empty($validated['recurrente'])) {
            $triggerConfig['recurrente'] = true;
            $triggerConfig['frecuencia'] = $validated['frecuencia_recurrencia'] ?? 'mensual';
        }
        $validated['trigger_config'] = $triggerConfig;

        $alerta = AlertaAdmin::create($validated);

        // If accion includes 'notificacion', create a notification
        if ($alerta->tieneAccion(AlertaAdmin::ACCION_NOTIFICACION)) {
            $this->crearNotificacionDesdeAlerta($alerta);
        }

        // If accion includes 'correo', send email to the alert creator
        if ($alerta->tieneAccion(AlertaAdmin::ACCION_CORREO)) {
            $user = auth()->user();
            try {
                Mail::to($user->email)->send(new AlertaNotificationMail($alerta, $user->name));
            } catch (\Exception $e) {
                \Log::error('Error enviando email de alerta: ' . $e->getMessage());
            }
        }

        return redirect()->route('rodados.alertas-admin.index')
            ->with('success', 'Alerta creada exitosamente.');
    }

    public function update(Request $request, AlertaAdmin $alerta)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:vencimiento_pago,recordatorio_turno,agendar_turno_km,personalizada',
            'rodado_id' => 'nullable|exists:rodados,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'km_intervalo' => 'nullable|integer|min:1',
            'fecha_alerta' => 'nullable|date',
            'recurrente' => 'nullable|boolean',
            'frecuencia_recurrencia' => 'nullable|in:diaria,semanal,mensual',
            'acciones' => 'required|array|min:1',
            'acciones.*' => 'in:dashboard,notificacion,correo',
        ]);

        $validated['accion_config'] = $validated['acciones'];
        unset($validated['acciones']);

        $triggerConfig = [];
        if ($validated['tipo'] === AlertaAdmin::TIPO_AGENDAR_TURNO_KM && $validated['km_intervalo']) {
            $triggerConfig['km_intervalo'] = $validated['km_intervalo'];
        }
        if ($validated['fecha_alerta']) {
            $triggerConfig['fecha'] = $validated['fecha_alerta'];
        }
        if (!empty($validated['recurrente'])) {
            $triggerConfig['recurrente'] = true;
            $triggerConfig['frecuencia'] = $validated['frecuencia_recurrencia'] ?? 'mensual';
        }
        $validated['trigger_config'] = $triggerConfig;

        $alerta->update($validated);

        return redirect()->route('rodados.alertas-admin.index')
            ->with('success', 'Alerta actualizada exitosamente.');
    }

    public function destroy(AlertaAdmin $alerta)
    {
        $alerta->delete();

        return redirect()->route('rodados.alertas-admin.index')
            ->with('success', 'Alerta eliminada exitosamente.');
    }

    public function toggle(AlertaAdmin $alerta)
    {
        $alerta->update(['activa' => !$alerta->activa]);

        $message = $alerta->activa ? 'Alerta activada.' : 'Alerta desactivada.';

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('rodados.alertas-admin.index')->with('success', $message);
    }

    /**
     * Create a notification from an alert
     */
    public function crearNotificacionDesdeAlerta(AlertaAdmin $alerta)
    {
        $notification = Notification::create([
            'title' => 'Alerta: ' . $alerta->titulo,
            'message' => $alerta->descripcion ?? $alerta->titulo,
            'type' => 'global',
            'priority' => 'NORMAL',
            'is_active' => true,
            'user_id' => $alerta->user_id,
        ]);

        return $notification;
    }
}
