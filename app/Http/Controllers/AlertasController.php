<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AlertasController extends Controller
{
    public function index()
    {
        return view('alertas.flytbase.index');
    }

    public function triggerAlarm(Request $request)
    {
        try {
            $webhookUrl = env('FLYTBASE_WEBHOOK_URL');
            $token = 'Bearer ' . env('FLYTBASE_API_TOKEN');

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

            


            Log::info('Respuesta HTTP recibida de Flytbase', [
                'status_code' => $response->status(),
                'response_body' => $response->body(),
                'response_headers' => $response->headers()
            ]);

            if ($response->successful()) {
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
           
            Log::error('Error de conexión con Flytbase', [
                'exception_message' => $e->getMessage(),
                'exception_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error de conexión: ' . $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
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
