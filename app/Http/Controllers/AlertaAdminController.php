<?php

namespace App\Http\Controllers;

use App\Models\AlertaAdmin;
use App\Models\Cliente;
use App\Models\Rodado;
use App\Models\ServicioUsuario;
use App\Models\Taller;
use App\Models\User;
use Illuminate\Http\Request;

class AlertaAdminController extends Controller
{
    public function index()
    {
        $alertas = AlertaAdmin::with(['user', 'rodado', 'cliente', 'servicioUsuario', 'taller', 'destinatarioUser'])
            ->latest()
            ->get();

        $rodados = Rodado::with(['cliente', 'proveedor.talleres'])->get();
        $clientes = Cliente::orderBy('nombre')->get();
        $servicios = ServicioUsuario::where('activo', true)->orderBy('nombre')->get();
        $talleres = Taller::with('proveedor')->orderBy('nombre')->get();

        // Get users with clientsupervisor role (using Spatie)
        $usuariosClientes = User::role(['clientsupervisor', 'clientadmin'])
            ->with('clientes')
            ->orderBy('name')
            ->get();

        // Prepare rodados JSON data for JavaScript
        $rodadosJson = $rodados->map(function ($r) {
            return [
                'id' => $r->id,
                'patente' => $r->patente,
                'display_name' => $r->display_name,
                'proveedor_id' => $r->proveedor_id,
                'proveedor_nombre' => $r->proveedor?->nombre,
                'cliente_id' => $r->cliente_id,
                'talleres' => $r->proveedor ? $r->proveedor->talleres->map(function ($t) {
                    return [
                        'id' => $t->id,
                        'nombre' => $t->nombre,
                        'whatsapp' => $t->whatsapp,
                        'whatsapp_link' => $t->whatsapp_link,
                        'telefono' => $t->telefono,
                    ];
                })->values()->toArray() : [],
            ];
        })->values();

        return view('rodados.alertas-admin', compact(
            'alertas',
            'rodados',
            'clientes',
            'servicios',
            'talleres',
            'usuariosClientes',
            'rodadosJson'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:pago_servicio,cobro_cliente,km_vehiculo,vencimiento,personalizada,vencimiento_pago,recordatorio_turno,agendar_turno_km',
            'rodado_id' => 'nullable|exists:rodados,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'servicio_usuario_id' => 'nullable|exists:servicios_usuario,id',
            'taller_id' => 'nullable|exists:talleres,id',
            'destinatario_tipo' => 'nullable|in:admin,cliente,ambos',
            'destinatario_user_id' => 'nullable|exists:users,id',
            'dia_mes' => 'nullable|integer|min:1|max:31',
            'dias_anticipacion' => 'nullable|integer|min:1',
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
        $validated['destinatario_tipo'] = $validated['destinatario_tipo'] ?? 'admin';
        unset($validated['acciones']);

        // Auto-set recurrence when a service is linked (monthly payment) or cobro_cliente
        if (! empty($validated['servicio_usuario_id']) || $validated['tipo'] === 'cobro_cliente') {
            $validated['recurrente'] = true;
            $validated['frecuencia_recurrencia'] = $validated['frecuencia_recurrencia'] ?? 'mensual';
        }

        // Build trigger config based on tipo
        $triggerConfig = [];

        if (in_array($validated['tipo'], ['km_vehiculo', 'agendar_turno_km']) && ! empty($validated['km_intervalo'])) {
            $triggerConfig['km_intervalo'] = $validated['km_intervalo'];
        }
        if (! empty($validated['dia_mes'])) {
            $triggerConfig['dia_mes'] = $validated['dia_mes'];
        }
        if (! empty($validated['dias_anticipacion'])) {
            $triggerConfig['dias_anticipacion'] = $validated['dias_anticipacion'];
        }
        if ($validated['fecha_alerta'] ?? null) {
            $triggerConfig['fecha'] = $validated['fecha_alerta'];
        }
        if (! empty($validated['recurrente'])) {
            $triggerConfig['recurrente'] = true;
            $triggerConfig['frecuencia'] = $validated['frecuencia_recurrencia'] ?? 'mensual';
        }

        $validated['trigger_config'] = $triggerConfig;

        $alerta = AlertaAdmin::create($validated);

        // Notifications, emails and dashboard visibility are handled by
        // the scheduled command `alertas:procesar` when the date approaches.
        // No instant dispatch here.

        return redirect()->route('rodados.alertas-admin.index')
            ->with('success', 'Alerta creada exitosamente. Se activara al acercarse la fecha.');
    }

    public function update(Request $request, AlertaAdmin $alerta)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:pago_servicio,cobro_cliente,km_vehiculo,vencimiento,personalizada,vencimiento_pago,recordatorio_turno,agendar_turno_km',
            'rodado_id' => 'nullable|exists:rodados,id',
            'cliente_id' => 'nullable|exists:clientes,id',
            'servicio_usuario_id' => 'nullable|exists:servicios_usuario,id',
            'taller_id' => 'nullable|exists:talleres,id',
            'destinatario_tipo' => 'nullable|in:admin,cliente,ambos',
            'destinatario_user_id' => 'nullable|exists:users,id',
            'dia_mes' => 'nullable|integer|min:1|max:31',
            'dias_anticipacion' => 'nullable|integer|min:1',
            'km_intervalo' => 'nullable|integer|min:1',
            'fecha_alerta' => 'nullable|date',
            'recurrente' => 'nullable|boolean',
            'frecuencia_recurrencia' => 'nullable|in:diaria,semanal,mensual',
            'acciones' => 'required|array|min:1',
            'acciones.*' => 'in:dashboard,notificacion,correo',
        ]);

        $validated['accion_config'] = $validated['acciones'];
        $validated['destinatario_tipo'] = $validated['destinatario_tipo'] ?? 'admin';
        unset($validated['acciones']);

        // Auto-set recurrence when a service is linked or cobro_cliente
        if (! empty($validated['servicio_usuario_id']) || $validated['tipo'] === 'cobro_cliente') {
            $validated['recurrente'] = true;
            $validated['frecuencia_recurrencia'] = $validated['frecuencia_recurrencia'] ?? 'mensual';
        }

        $triggerConfig = [];
        if (in_array($validated['tipo'], ['km_vehiculo', 'agendar_turno_km']) && ! empty($validated['km_intervalo'])) {
            $triggerConfig['km_intervalo'] = $validated['km_intervalo'];
        }
        if (! empty($validated['dia_mes'])) {
            $triggerConfig['dia_mes'] = $validated['dia_mes'];
        }
        if (! empty($validated['dias_anticipacion'])) {
            $triggerConfig['dias_anticipacion'] = $validated['dias_anticipacion'];
        }
        if ($validated['fecha_alerta'] ?? null) {
            $triggerConfig['fecha'] = $validated['fecha_alerta'];
        }
        if (! empty($validated['recurrente'])) {
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
        $alerta->update(['activa' => ! $alerta->activa]);

        $message = $alerta->activa ? 'Alerta activada.' : 'Alerta desactivada.';

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('rodados.alertas-admin.index')->with('success', $message);
    }
}
