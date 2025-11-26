<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\FlightLog;
use Carbon\Carbon;

class DroneStatusController extends Controller
{
    public function getDroneStatus(Request $request)
    {
        try {
            
            $validator = \Validator::make($request->all(), [
                'drone_name' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $droneName = $request->drone_name;
            
            Log::info('Consultando estado del drone', [
                'drone_name' => $droneName,
                'client_ip' => $request->ip()
            ]);

            // Obtener el estado del drone
            $droneStatus = $this->determineDroneStatus($droneName);

            Log::info('Estado del drone obtenido', [
                'drone_name' => $droneName,
                'status' => $droneStatus['status']
            ]);

            return response()->json([
                'success' => true,
                'data' => $droneStatus
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error obteniendo estado del drone', [
                'exception' => $e->getMessage(),
                'drone_name' => $request->drone_name ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function determineDroneStatus($droneName)
    {
        // Buscar el flight log más reciente para este drone
        $latestFlight = FlightLog::where('drone_name', $droneName)
            ->orWhere('drone_name', 'like', "%{$droneName}%")
            ->latest('flight_starttime')
            ->first();

        // Si no hay flight logs registrados
        if (!$latestFlight) {
            return [
                'status' => 'unknown',
                'drone_name' => $droneName,
                'message' => 'No flight history found for this drone',
                'battery_level' => null,
            ];
        }

        // Determinar estado basado en el flight log más reciente
        if ($this->isDroneFlying($latestFlight)) {
            return $this->buildFlyingStatus($latestFlight);
        } elseif ($this->isDroneIdle($latestFlight)) {
            return $this->buildIdleStatus($latestFlight);
        } else {
            return $this->buildUnknownStatus($latestFlight);
        }
    }

    private function isDroneFlying(FlightLog $flightLog): bool
    {
        return $flightLog->estado === FlightLog::ESTADO_EN_PROCESO &&
               $flightLog->flight_starttime &&
               !$flightLog->flight_endtime;
    }

    private function isDroneIdle(FlightLog $flightLog): bool
    {
        return $flightLog->estado === FlightLog::ESTADO_COMPLETADO &&
               $flightLog->flight_starttime &&
               $flightLog->flight_endtime;
    }

    private function buildFlyingStatus(FlightLog $flightLog): array
    {
        $flightDuration = null;
        if ($flightLog->flight_starttime) {
            $flightDuration = $flightLog->flight_starttime->diffInMinutes(Carbon::now());
        }

        return [
            'status' => 'flying',
            'drone_name' => $flightLog->drone_name,
            'message' => 'Drone is currently on flight',
            'flight_start_time' => $flightLog->flight_starttime?->toISOString(),
            'current_flight_duration_minutes' => $flightDuration,
            'battery_level' => $flightLog->drone_battery
        ];
    }

    private function buildIdleStatus(FlightLog $flightLog): array
    {
        $timeSinceLastFlight = null;
        if ($flightLog->flight_endtime) {
            $timeSinceLastFlight = $flightLog->flight_endtime->diffInMinutes(Carbon::now());
        }

        return [
            'status' => 'idle',
            'drone_name' => $flightLog->drone_name,
            'message' => 'Drone is idle at base',
            'battery_level' => $flightLog->drone_battery,
        ];
    }

    private function buildUnknownStatus(FlightLog $flightLog): array
    {
        return [
            'status' => 'unknown',
            'drone_name' => $flightLog->drone_name,
            'message' => 'Drone status could not be determined',
            'last_known_status' => $flightLog->estado,
        ];
    }

    private function estimateLandingTime(FlightLog $flightLog): ?string
    {
        if (!$flightLog->flight_starttime) {
            return null;
        }

        // Asumir un vuelo máximo de 30 minutos por seguridad
        $estimatedLanding = $flightLog->flight_starttime->copy()->addMinutes(30);
        
        return $estimatedLanding->toISOString();
    }

}
