<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\AlertLog;
use App\Models\User;

class AlertasController extends Controller
{
    public function index(Request $request)
    {
        $logs = AlertLog::with('user')
            ->tipo($request->tipo)
            ->usuario($request->usuario)
            ->exitoso($request->exitoso)
            ->fechaDesde($request->fecha_desde)
            ->fechaHasta($request->fecha_hasta)
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        // Obtener usuarios para el filtro
        $usuarios = User::whereHas('alertLogs')->get();

        return view('alertas.flytbase.index', compact('logs', 'usuarios'));
    }

    public function triggerAlarm(Request $request)
    {
        try {
            $webhookUrl = env('FLYTBASE_WEBHOOK_URL');
            $token = 'Bearer ' . $request->token; //token dinamico desde formulario

            $payload = [
                'timestamp' => round(microtime(true) * 1000), // Timestamp en milisegundos
                'severity' => 2,
                'description' => 'Desplegar mision Perimetro Rodial',
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
                'payload' => $payload,
                'user' => auth()->user()->name ?? 'Unknown'
            ]);

            $response = Http::withOptions(['verify' => false]) // ⬅️ misma configuración SSL
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

            //crear registro en tabla de logs            
            $alertLog = AlertLog::create([
                'tipo_alerta' => 'trigger_mision',
                'descripcion' => 'Desplegar mision Perimetro Rodial',
                'user_id' => auth()->id(),
                'exito' => $esExitoso,
                'codigo_respuesta' => $statusCode,
                'mensaje_error' => $esExitoso ? null : $responseBody, 
                'payload' => $payload,
                'respuesta' => json_decode($responseBody, true) ?: ['raw' => $responseBody]
            ]);

            Log::info('Respuesta HTTP recibida de Flytbase', [
                'status_code' => $statusCode,
                'response_body' => $responseBody,
                'es_exitoso' => $esExitoso // ⬅️ Para debug
            ]);

            if ($esExitoso) {
                Log::info('Alarma enviada exitosamente a Flytbase');
                return response()->json([
                    'success' => true,
                    'message' => 'Alarma enviada correctamente. Misión "Perímetro Rodial" desplegada.'
                ]);
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

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            AlertLog::create([
                'tipo_alerta' => 'trigger_mision',
                'descripcion' => 'Desplegar mision Perimetro Rodial',
                'user_id' => auth()->id(),
                'exito' => false,
                'codigo_respuesta' => 0,
                'mensaje_error' => $e->getMessage(),
                'payload' => $payload ?? [],
            ]);
           
            Log::error('Error de conexión con Flytbase', [
                'exception_message' => $e->getMessage(),
                'exception_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error de conexión: ' . $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            AlertLog::create([
                'tipo_alerta' => 'trigger_mision',
                'descripcion' => 'Desplegar mision Perimetro Rodial',
                'user_id' => auth()->id(),
                'exito' => false,
                'codigo_respuesta' => 0,
                'mensaje_error' => $e->getMessage(),
                'payload' => $payload ?? [],
            ]);
            // Error general
            Log::error('Error general en triggerAlarm', [
                'exception_message' => $e->getMessage(),
                'exception_file' => $e->getFile(),
                'exception_line' => $e->getLine(),
                'exception_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ], 500);
        }
    }
}
