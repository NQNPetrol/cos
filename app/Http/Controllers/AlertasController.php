<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\AlertLog;
use App\Models\User;
use App\Models\MisionFlytbase;
use App\Models\FlightLog;
use App\Models\PilotoFlytbaseCliente;
use App\Models\UserCliente;

class AlertasController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $logs = AlertLog::with('user')
            ->tipo($request->tipo)
            ->usuario($request->usuario)
            ->exitoso($request->exitoso)
            ->fechaDesde($request->fecha_desde)
            ->fechaHasta($request->fecha_hasta)
            ->porMisionesUsuario($user)
            ->latest()
            ->paginate(5)
            ->appends($request->query());

        // Obtener usuarios para el filtro
        $usuarios = User::whereHas('alertLogs')->get();

        $misiones = $this->getMisionesDisponibles($user);

        return view('alertas.flytbase.index', compact('logs', 'usuarios', 'misiones'));
    }

    public function triggerAlarm(Request $request)
    {
        try {
            $user = auth()->user();
            $tipoAlerta = $request->tipo_alerta;
            $misionId = $request->mision_id;

            // Validar que si es trigger_mision, tenga una misión seleccionada
            if ($tipoAlerta === 'trigger_mision' && !$misionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe seleccionar una misión para enviar el trigger.'
                ], 400);
            }

            // Obtener la misión si es trigger_mision
            if ($tipoAlerta === 'trigger_mision') {
                $mision = MisionFlytbase::with('drone')->activas()->find($misionId);
                
                if (!$mision) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La misión seleccionada no existe o no está activa.'
                    ], 400);
                }

                // Verificar permisos del usuario para esta misión
                if (!$this->usuarioPuedeAccederMision($user, $mision)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tiene permisos para acceder a esta misión.'
                    ], 403);
                }

                $webhookUrl = $mision->url;
                $descripcion = "Desplegar misión: {$mision->nombre}";
            } else {
                $webhookUrl = env('FLYTBASE_WEBHOOK_URL');
                $descripcion = 'Alerta técnica/hardware';
            }

            $token = 'Bearer ' . env('FLYTBASE_WEBHOOK_TOKEN');

            $payload = [
                'timestamp' => round(microtime(true) * 1000),
                'severity' => 2,
                'description' => 'Iniciando mision drone',
                'latitude' => 37.7749,
                'longitude' => -122.4194,
                'altitude_msl' => 100,
                'metadata' => [
                    'sensor_id' => 'TempSensor12A',
                    'temperature' => 50.2,
                    'battery_level' => 80
                ]
            ];

            Log::info('Iniciando envío de alarma a Flytbase', [
                'webhook_url' => $webhookUrl,
                'tipo_alerta' => $tipoAlerta,
                'mision_id' => $misionId,
                'payload' => $payload,
                'user' => $user->name
            ]);

            $response = Http::withOptions(['verify' => false])
                ->timeout(30)
                ->withHeaders([
                    'Authorization' => $token,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($webhookUrl, $payload);

            $statusCode = $response->status();
            $responseBody = $response->body();
            $esExitoso = $statusCode >= 200 && $statusCode < 300;

            // Crear registro en tabla de logs
            $alertLogData = [
                'tipo_alerta' => $tipoAlerta,
                'descripcion' => $descripcion,
                'user_id' => $user->id,
                'exito' => $esExitoso,
                'codigo_respuesta' => $statusCode,
                'mensaje_error' => $esExitoso ? null : $responseBody,
                'payload' => $payload,
                'respuesta' => json_decode($responseBody, true) ?: ['raw' => $responseBody]
            ];

            // Solo agregar mision_id si es trigger_mision
            if ($tipoAlerta === 'trigger_mision') {
                $alertLogData['mision_id'] = $misionId;
            }

            $alertLog = AlertLog::create($alertLogData);

            if ($tipoAlerta === 'trigger_mision' && $esExitoso) {
                $this->crearFlightLog($alertLog, $mision, $user);
            }

            Log::info('Respuesta HTTP recibida de Flytbase', [
                'status_code' => $statusCode,
                'response_body' => $responseBody,
                'es_exitoso' => $esExitoso
            ]);

            if ($esExitoso) {
                Log::info('Alarma enviada exitosamente a Flytbase');
                $responseData = [
                    'success' => true,
                    'message' => $tipoAlerta === 'trigger_mision' 
                        ? "Alarma enviada correctamente. Misión '{$mision->nombre}' desplegada."
                        : 'Alarma enviada correctamente.'
                ];
                if ($tipoAlerta === 'trigger_mision' && $mision->hasLiveview()) {
                    Log::info('Misión tiene liveview', [
                        'mision_id' => $misionId,
                        'mision_nombre' => $mision->nombre,
                        'has_liveview' => $mision->hasLiveview()
                    ]);
                    $responseData['mision_id'] = $misionId;
                    $responseData['mision_nombre'] = $mision->nombre;
                    $responseData['has_liveview'] = true;
                    $responseData['drone_name'] = $mision->drone->drone;
                    $responseData['liveview_route'] = $mision->getLiveviewRoute();
                }

                if ($tipoAlerta === 'trigger_mision') {
                    Log::info('DEBUG - Estado de liveview', [
                        'mision_id' => $misionId,
                        'mision_nombre' => $mision->nombre,
                        'tiene_drone' => isset($mision->drone),
                        'drone_id' => $mision->drone->id ?? 'No tiene drone',
                        'metodo_hasLiveview_existe' => method_exists($mision, 'hasLiveview'),
                        'hasLiveview_result' => method_exists($mision, 'hasLiveview') ? $mision->hasLiveview() : 'Método no existe',
                        'drone_data' => $mision->drone ?? 'No hay datos de drone'
                    ]);
                    
                    // Si el método existe y retorna true, o si forzamos para testing
                    $shouldShowLiveview = (method_exists($mision, 'hasLiveview') && $mision->hasLiveview()) || true;
                    
                    if ($shouldShowLiveview) {
                        Log::info('Misión tiene liveview - agregando a respuesta');
                        $responseData['mision_id'] = $misionId;
                        $responseData['mision_nombre'] = $mision->nombre;
                        $responseData['has_liveview'] = true;
                        $responseData['drone_name'] = $mision->drone->drone ?? 'Drone no disponible';
                        $responseData['liveview_route'] = method_exists($mision, 'getLiveviewRoute') 
                            ? $mision->getLiveviewRoute() 
                            : route('alertas.liveview');
                    }
                }
                
                return response()->json($responseData);
                
            } else {
                Log::warning('Flytbase respondió con código de error', [
                    'status_code' => $response->status(),
                    'response_body' => $response->body()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Error al enviar la alarma. Código: ' . $response->status()
                ], 500);
            }

        } catch (\Exception $e) {
            $alertLogData = [
                'tipo_alerta' => $tipoAlerta ?? 'trigger_mision',
                'descripcion' => $descripcion ?? 'Error desconocido',
                'user_id' => auth()->id(),
                'exito' => false,
                'codigo_respuesta' => 0,
                'mensaje_error' => $e->getMessage(),
                'payload' => $payload ?? [],
            ];

            if (isset($misionId) && $tipoAlerta === 'trigger_mision') {
                $alertLogData['mision_id'] = $misionId;
            }

            AlertLog::create($alertLogData);

            Log::error('Error en triggerAlarm', [
                'exception_message' => $e->getMessage(),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ], 500);
        }
    }

    private function crearFlightLog($alertLog, $mision, $user)
    {
        try {
            
            $flightLog = FlightLog::create([
                'piloto_flytbase_id' => null,
                'mision_flytbase_id' => $mision->id,
                'alert_log_id' => $alertLog->id,
                'drone_name' => $mision->drone->drone ?? null,
                'trigger_senttime' => now(),
                'flight_starttime' => null,
                'estado' => FlightLog::ESTADO_EN_PROCESO,
            ]);

            Log::info('Flight log creado exitosamente', [
                'flight_log_id' => $flightLog->id,
                'mision_id' => $mision->id,
                'drone_name' => $mision->drone->drone ?? 'N/A',
                 'trigger_senttime' => $flightLog->trigger_senttime
            ]);

        } catch (\Exception $e) {
            Log::error('Error creando flight log', [
                'exception' => $e->getMessage(),
                'mision_id' => $mision->id,
                'user_id' => $user->id
            ]);
        }
    }

    private function obtenerPilotoDelCliente($user)
    {
        try {
            // Obtener cliente del usuario
            $userCliente = UserCliente::where('user_id', $user->id)->first();
            
            if (!$userCliente) {
                Log::error('Usuario no tiene cliente asignado', ['user_id' => $user->id]);
                return null;
            }

            // Obtener piloto asignado al cliente
            $pilotoCliente = PilotoFlytbaseCliente::where('cliente_id', $userCliente->cliente_id)->first();
            
            if (!$pilotoCliente) {
                Log::error('Cliente no tiene piloto asignado', ['cliente_id' => $userCliente->cliente_id]);
                return null;
            }

            return $pilotoCliente->piloto_flytbase_id;

        } catch (\Exception $e) {
            Log::error('Error obteniendo piloto del cliente', [
                'user_id' => $user->id,
                'exception' => $e->getMessage()
            ]);
            return null;
        }
    }

    private function getMisionesDisponibles($user)
    {
        $query = MisionFlytbase::activas()->with('cliente');

        if ($user->hasRole('admin') || $user->hasRole('operador')) {
            return $query->get();
        }

        if ($user->hasRole('cliente')) {
            $userClientes = UserCliente::where('user_id', $user->id)->pluck('cliente_id');
            return $query->porClientes($userClientes)->get();
        }

        return collect();
    }

    private function usuarioPuedeAccederMision($user, $mision)
    {
        if ($user->hasRole('admin') || $user->hasRole('operador')) {
            return true;
        }

        if ($user->hasRole('cliente')) {
            $userClientes = UserCliente::where('user_id', $user->id)->pluck('cliente_id');
            return in_array($mision->cliente_id, $userClientes->toArray());
        }

        return false;
    }
}
