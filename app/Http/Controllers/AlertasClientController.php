<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\AlertLog;
use App\Models\User;
use App\Models\MisionFlytbase;
use App\Models\UserCliente;
use App\Models\PilotoFlytbaseCliente;
use App\Models\PilotoFlytbase;

class AlertasClientController extends Controller
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
        $usuarios = User::whereHas('alertLogs', function($query) use ($user) {
            $query->whereHas('mision', function($q) use ($user) {
                $q->porClienteUsuario($user);
            });
        })->get();

        $misiones = $this->getMisionesDisponibles($user);

        return view('alertas.client.index', compact('logs', 'usuarios', 'misiones'));
    }

    public function triggerAlarm(Request $request)
    {
        try {
            $user = auth()->user();
            $tipoAlerta = 'trigger_mision';
            $misionId = $request->mision_id;

            // Validar que si es trigger_mision, tenga una misión seleccionada
            if (!$misionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe seleccionar una misión para enviar el trigger.'
                ], 400);
            }

            // Obtener la misión si es trigger_mision

            $mision = MisionFlytbase::activas()
                ->porClienteUsuario($user) // Solo misiones a las que tiene acceso
                ->with('drone')
                ->find($misionId);

                
            if (!$mision) {
                return response()->json([
                    'success' => false,
                    'message' => 'La misión seleccionada no existe o no está activa.'
                ], 400);
            }

            $tokenPiloto = $this->obtenerTokenPiloto($user);
            if (!$tokenPiloto) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo obtener el token de autenticación del piloto asignado.'
                ], 500);
            }

            $webhookUrl = $mision->url;
            $descripcion = "Desplegar misión: {$mision->nombre}";

            $token = 'Bearer ' . $tokenPiloto;

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

            
            
            $alertLogData['mision_id'] = $misionId;
            

            $alertLog = AlertLog::create($alertLogData);

            Log::info('Respuesta HTTP recibida de Flytbase', [
                'status_code' => $statusCode,
                'response_body' => $responseBody,
                'es_exitoso' => $esExitoso
            ]);

            if ($esExitoso) {
                Log::info('Alarma enviada exitosamente a Flytbase');
                $responseData = [
                    'success' => true,
                    'message' => "Misión '{$mision->nombre}' desplegada exitosamente."
                ];
                if ($mision->hasLiveview()) {
                    Log::info('Misión tiene liveview', [
                        'mision_id' => $misionId,
                        'mision_nombre' => $mision->nombre,
                        'has_liveview' => $mision->hasLiveview()
                    ]);
                    $responseData['mision_id'] = $misionId;
                    $responseData['mision_nombre'] = $mision->nombre;
                    $responseData['has_liveview'] = true;
                    $responseData['drone_name'] = $mision->drone->drone ?? 'Drone no disponible';
                    $responseData['liveview_route'] = $mision->getLiveviewRoute();
                }

                
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
                'tipo_alerta' => 'trigger_mision',
                'descripcion' => $descripcion ?? 'Error al desplegar mision',
                'user_id' => auth()->id(),
                'exito' => false,
                'codigo_respuesta' => 0,
                'mensaje_error' => $e->getMessage(),
                'payload' => $payload ?? [],
                'mision_id' => $misionId ?? null
            ];

            if (isset($misionId)) {
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

    private function obtenerTokenPiloto($user)
    {
        try {
            // Obtener el cliente del usuario
            $userCliente = UserCliente::where('user_id', $user->id)->first();
            
            if (!$userCliente) {
                Log::error('Usuario no tiene cliente asignado', ['user_id' => $user->id]);
                return null;
            }

            // Obtener el piloto asignado al cliente
            $pilotoCliente = PilotoFlytbaseCliente::where('cliente_id', $userCliente->cliente_id)->first();
            
            if (!$pilotoCliente) {
                Log::error('Cliente no tiene piloto asignado', ['cliente_id' => $userCliente->cliente_id]);
                return null;
            }

            // Obtener el token del piloto
            $piloto = PilotoFlytbase::find($pilotoCliente->piloto_flytbase_id);
            
            if (!$piloto || empty($piloto->token)) {
                Log::error('Piloto no encontrado o sin token', [
                    'piloto_id' => $pilotoCliente->piloto_flytbase_id,
                    'piloto_encontrado' => !is_null($piloto),
                    'tiene_token' => $piloto && !empty($piloto->token)
                ]);
                return null;
            }

            Log::info('Token de piloto obtenido exitosamente', [
                'user_id' => $user->id,
                'cliente_id' => $userCliente->cliente_id,
                'piloto_id' => $piloto->id
            ]);

            return $piloto->token;

        } catch (\Exception $e) {
            Log::error('Error al obtener token del piloto', [
                'user_id' => $user->id,
                'exception' => $e->getMessage()
            ]);
            return null;
        }
    }

    private function getMisionesDisponibles($user)
    {
        return MisionFlytbase::activas()
            ->porClienteUsuario($user) // Nuevo scope que filtra por cliente del usuario
            ->with('cliente', 'drone')
            ->get();
    }

}
