<?php

namespace App\Http\Controllers;

use App\Models\FlightLog;
use App\Models\MisionFlytbase;
use App\Models\PilotoFlytbase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FlytbaseFlightLogsController extends Controller
{
    /**
     * Procesar datos del email de Flytbase y actualizar flight log
     */
    public function storeFromEmail(Request $request)
    {
        try {
            Log::info('Incoming Flytbase flight log data from email', [
                'event_type' => $request->event_type,
                'drone_name' => $request->drone_name,
                'drone_name_custom' => $request->drone_name_custom,
                'request_data' => $request->all(),
            ]);

            // Validar datos mínimos requeridos
            $validator = Validator::make($request->all(), [
                'event_timestamp' => 'required|date',
                'drone_name' => 'required|string',
                'event_type' => 'required|in:takeoff,landed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $droneNameToSearch = $request->drone_name_custom ?? $request->drone_name;

            if ($request->event_type === 'takeoff') {
                return $this->handleTakeoffEvent($request, $droneNameToSearch);
            }

            if ($request->event_type === 'landed') {
                return $this->handleLandedEvent($request, $droneNameToSearch);
            }

        } catch (\Exception $e) {
            Log::error('Error processing Flytbase flight log from email', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error: '.$e->getMessage(),
            ], 500);
        }
    }

    private function handleTakeoffEvent(Request $request, $droneName)
    {
        try {
            Log::info('Processing takeoff event', [
                'drone_name' => $droneName,
                'event_timestamp' => $request->event_timestamp,
            ]);

            // Buscar si existe un flight log por trigger (vuelo automático)
            $flightLog = $this->findFlightLogByTriggerTime($request->event_timestamp, $droneName);

            if ($flightLog) {
                // Vuelo por trigger - Actualizar con flight_starttime real
                Log::info('Found existing flight log for trigger-based flight', [
                    'flight_log_id' => $flightLog->id,
                    'trigger_senttime' => $flightLog->trigger_senttime,
                ]);

                return $this->updateExistingFlightLogWithTakeoff($flightLog, $request, $droneName);
            } else {
                // Vuelo manual - Crear nuevo flight log
                Log::info('No existing flight log found, creating new one for manual flight');

                return $this->createNewFlightLogForManualFlight($request, $droneName);
            }

        } catch (\Exception $e) {
            Log::error('Error handling takeoff event', [
                'exception' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);
            throw $e;
        }
    }

    private function updateExistingFlightLogWithTakeoff($flightLog, $request, $droneName)
    {
        $pilotoId = $this->extractPilotoId($request->piloto_nombre, $request->flight_details);

        // Buscar misión basada en el drone name si no tiene misión asignada
        $misionId = $flightLog->mision_flytbase_id;
        if (! $misionId) {
            $misionId = $this->findMisionByDroneName($droneName);
        }

        $eventTimestamp = Carbon::parse($request->event_timestamp);

        // Preparar datos para actualizar
        $updateData = [
            'event_id' => $request->event_id,
            'message' => $request->message,
            'severity' => $request->severity,
            'dock_name' => $request->dock_name,
            'event_coordinates' => $request->event_coordinates,
            'site' => $request->site,
            'organization' => $request->organization,
            'automation' => $request->automation,
            'drone_battery' => $request->drone_battery,
            'flight_details' => $request->flight_details,
            'event_timestamp' => $eventTimestamp,
            'flight_starttime' => $eventTimestamp, // Tiempo real de despegue
            'estado' => FlightLog::ESTADO_EN_PROCESO,
        ];

        if ($pilotoId) {
            $updateData['piloto_flytbase_id'] = $pilotoId;
        }

        if ($misionId && ! $flightLog->mision_flytbase_id) {
            $updateData['mision_flytbase_id'] = $misionId;
        }

        // Actualizar el flight log
        $flightLog->update($updateData);

        Log::info('Flight log existente actualizado con takeoff time', [
            'flight_log_id' => $flightLog->id,
            'tipo' => 'por_trigger',
            'trigger_senttime' => $flightLog->trigger_senttime,
            'flight_starttime' => $flightLog->flight_starttime,
            'time_difference_minutes' => $flightLog->trigger_senttime->diffInMinutes($flightLog->flight_starttime),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Flight log existente actualizado con takeoff time',
            'data' => [
                'id' => $flightLog->id,
                'tipo' => 'por_trigger',
                'trigger_senttime' => $flightLog->trigger_senttime,
                'flight_starttime' => $flightLog->flight_starttime,
                'time_difference_minutes' => $flightLog->trigger_senttime->diffInMinutes($flightLog->flight_starttime),
            ],
        ], 200);
    }

    private function createNewFlightLogForManualFlight($request, $droneName)
    {
        $pilotoId = $this->extractPilotoId($request->piloto_nombre, $request->flight_details);
        $misionId = $this->findMisionByDroneName($droneName);

        $eventTimestamp = Carbon::parse($request->event_timestamp);

        $flightLogData = [
            'event_id' => $request->event_id,
            'message' => $request->message,
            'severity' => $request->severity,
            'drone_name' => $droneName,
            'dock_name' => $request->dock_name,
            'event_coordinates' => $request->event_coordinates,
            'site' => $request->site,
            'organization' => $request->organization,
            'automation' => $request->automation,
            'drone_battery' => $request->drone_battery,
            'flight_details' => $request->flight_details,
            'event_timestamp' => $eventTimestamp,
            'flight_starttime' => $eventTimestamp, // Tiempo real de despegue
            'trigger_senttime' => null, // No hubo trigger - vuelo manual
            'estado' => FlightLog::ESTADO_EN_PROCESO,
            'piloto_flytbase_id' => $pilotoId,
            'mision_flytbase_id' => $misionId,
        ];

        $flightLog = FlightLog::create($flightLogData);

        Log::info('Nuevo flight log creado para vuelo manual', [
            'flight_log_id' => $flightLog->id,
            'tipo' => 'manual',
            'flight_starttime' => $flightLog->flight_starttime,
            'drone_name' => $droneName,
            'piloto_id' => $pilotoId,
            'mision_id' => $misionId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nuevo flight log creado para vuelo manual',
            'data' => [
                'id' => $flightLog->id,
                'tipo' => 'manual',
                'flight_starttime' => $flightLog->flight_starttime,
                'drone_name' => $droneName,
            ],
        ], 201);
    }

    private function findMisionByDroneName($droneName)
    {
        try {
            // Buscar misión activa que use este drone
            $mision = MisionFlytbase::activas()
                ->whereHas('drone', function ($q) use ($droneName) {
                    $q->where('drone', 'like', "%{$droneName}%");
                })
                ->first();

            if ($mision) {
                Log::info('Misión encontrada por drone name', [
                    'drone_name' => $droneName,
                    'mision_id' => $mision->id,
                    'mision_nombre' => $mision->nombre,
                ]);
            }

            return $mision ? $mision->id : null;

        } catch (\Exception $e) {
            Log::error('Error finding mision by drone name', [
                'drone_name' => $droneName,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function handleLandedEvent(Request $request, $droneName)
    {
        try {
            Log::info('Processing landed event', [
                'drone_name' => $droneName,
                'event_timestamp' => $request->event_timestamp,
            ]);

            // Buscar flight log activo por drone name
            $flightLog = $this->findActiveFlightLog($request->event_timestamp, $droneName);

            if (! $flightLog) {
                Log::warning('No active flight log found for landed event', [
                    'event_timestamp' => $request->event_timestamp,
                    'drone_name' => $droneName,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'No matching active flight log found for landed event',
                ], 404);
            }

            $pilotoId = $this->extractPilotoId($request->piloto_nombre, $request->flight_details);

            // Convertir timestamp del email
            $flightEndTime = Carbon::parse($request->event_timestamp);

            // Calcular flight_time usando el flight_starttime real
            $flightTime = null;
            if ($flightLog->flight_starttime) {
                $flightTime = $flightLog->flight_starttime->diffInSeconds($flightEndTime);
                Log::info('Flight time calculated', [
                    'start' => $flightLog->flight_starttime,
                    'end' => $flightEndTime,
                    'seconds' => $flightTime,
                ]);
            }

            // Preparar datos para actualizar
            $updateData = [
                'event_id' => $request->event_id,
                'message' => $request->message,
                'severity' => $request->severity,
                'dock_name' => $request->dock_name,
                'event_coordinates' => $request->event_coordinates,
                'site' => $request->site,
                'organization' => $request->organization,
                'automation' => $request->automation,
                'drone_battery' => $request->drone_battery,
                'flight_details' => $request->flight_details,
                'event_timestamp' => $flightEndTime,
                'flight_endtime' => $flightEndTime,
                'flight_time' => $flightTime,
                'total_distance' => $request->total_distance ?? null,
                'estado' => FlightLog::ESTADO_COMPLETADO,
            ];

            if ($pilotoId && ! $flightLog->piloto_flytbase_id) {
                $updateData['piloto_flytbase_id'] = $pilotoId;
            }

            // Actualizar el flight log
            $flightLog->update($updateData);

            Log::info('Flight log completado', [
                'flight_log_id' => $flightLog->id,
                'tipo' => $flightLog->trigger_senttime ? 'por_trigger' : 'manual',
                'flight_time_seconds' => $flightTime,
                'flight_starttime' => $flightLog->flight_starttime,
                'flight_endtime' => $flightEndTime,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Flight log completed successfully',
                'data' => [
                    'id' => $flightLog->id,
                    'tipo' => $flightLog->trigger_senttime ? 'por_trigger' : 'manual',
                    'flight_time_seconds' => $flightTime,
                    'flight_time_readable' => $flightLog->duracion_legible,
                    'status' => 'completed',
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error handling landed event', [
                'exception' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);
            throw $e;
        }
    }

    /**
     * Encontrar flight log más cercano al timestamp del evento
     */
    private function findClosestFlightLog($eventTimestamp, $droneName = null)
    {
        $eventTime = Carbon::parse($eventTimestamp);
        $startSearch = $eventTime->copy()->subMinutes(30); // tiempo maximo que puede durar un vuelo

        $query = FlightLog::where('flight_starttime', '>=', $startSearch)
            ->where('flight_starttime', '<=', $eventTime)
            ->where('estado', FlightLog::ESTADO_EN_PROCESO)
            ->whereNull('flight_endtime');

        $allLogs = FlightLog::where('estado', FlightLog::ESTADO_EN_PROCESO)
            ->whereNull('flight_endtime')
            ->get(['id', 'drone_name', 'flight_starttime']);

        Log::info('Available flight logs for matching', [
            'available_logs' => $allLogs->toArray(),
            'requested_drone' => $droneName,
            'event_time' => $eventTime,
        ]);

        // Si tenemos drone_name, hacer búsqueda flexible
        if ($droneName) {
            // Normalizar el nombre del drone para búsqueda flexible
            $cleanedDroneName = preg_replace('/<[^>]*>/', '', $droneName);
            $cleanedDroneName = trim($cleanedDroneName);

            $query->where(function ($q) use ($cleanedDroneName) {
                // Búsqueda exacta
                $q->where('drone_name', $cleanedDroneName)
                // O buscar drones que contengan partes del nombre
                    ->orWhereRaw('LOWER(drone_name) = LOWER(?)', [$cleanedDroneName]);
            });

        }

        // Ordenar por proximidad al evento
        $flightLogs = $query->get();

        if ($flightLogs->isEmpty()) {
            Log::warning('No flight logs found with flexible search', [
                'event_timestamp' => $eventTime,
                'drone_name' => $droneName,
                'search_range' => '30 minutes before event',
            ]);

            $queryWithoutDrone = FlightLog::where('flight_starttime', '>=', $startSearch)
                ->where('flight_starttime', '<=', $eventTime)
                ->where('estado', FlightLog::ESTADO_EN_PROCESO)
                ->whereNull('flight_endtime');

            $flightLogs = $queryWithoutDrone->get();

            if ($flightLogs->isEmpty()) {
                return null;
            }
        }

        // Encontrar el más cercano al timestamp del evento
        $closestLog = $flightLogs->sortBy(function ($log) use ($eventTime) {
            return abs($log->flight_starttime->diffInSeconds($eventTime));
        })->first();

        Log::info('Closest flight log found', [
            'flight_log_id' => $closestLog->id,
            'flight_starttime' => $closestLog->flight_starttime,
            'drone_name_in_db' => $closestLog->drone_name,
            'drone_name_requested' => $droneName,
            'event_timestamp' => $eventTime,
            'time_difference_seconds' => $closestLog->flight_starttime->diffInSeconds($eventTime),
            'match_type' => $closestLog->drone_name === $droneName ? 'exact' : 'time_based',
        ]);

        return $closestLog;
    }

    private function findFlightLogByTriggerTime($eventTimestamp, $droneName = null)
    {
        $eventTime = Carbon::parse($eventTimestamp);

        $query = FlightLog::cercanosATriggerTime($eventTimestamp, 30); // 30 minutos tolerancia

        if ($droneName) {
            $query->where('drone_name', $droneName);
        }

        return $query->first();
    }

    /**
     * Buscar flight log activo (para landed events)
     */
    private function findActiveFlightLog($eventTimestamp, $droneName = null)
    {
        $eventTime = Carbon::parse($eventTimestamp);
        $startSearch = $eventTime->copy()->subMinutes(120); // Buscar en las últimas 2 horas

        $query = FlightLog::activos()
            ->where('flight_starttime', '>=', $startSearch)
            ->where('flight_starttime', '<=', $eventTime);

        if ($droneName) {
            $query->where('drone_name', $droneName);
        }

        $flightLogs = $query->get();

        if ($flightLogs->isEmpty()) {
            return null;
        }

        // Encontrar el más cercano al tiempo del evento
        return $flightLogs->sortBy(function ($log) use ($eventTime) {
            return abs($log->flight_starttime->diffInSeconds($eventTime));
        })->first();
    }

    /**
     * Extraer ID del piloto (usa el nombre del piloto si viene en el request)
     */
    private function extractPilotoId($pilotoNombre = null, $flightDetails = null)
    {
        // Prioridad 1: Usar el nombre del piloto que viene en el request
        if ($pilotoNombre) {
            $piloto = PilotoFlytbase::where('nombre', 'like', '%'.$pilotoNombre.'%')->first();
            if ($piloto) {
                Log::info('👤 Piloto encontrado por nombre directo', [
                    'nombre_buscado' => $pilotoNombre,
                    'piloto_id' => $piloto->id,
                ]);

                return $piloto->id;
            }
        }

        // Prioridad 2: Extraer del flight_details (método existente)
        if ($flightDetails) {
            return $this->extractPilotoFromFlightDetails($flightDetails);
        }

        return null;
    }

    private function normalizeDroneName($droneName)
    {
        // Convertir a minúsculas y remover números al final
        $normalized = strtolower($droneName);
        $normalized = preg_replace('/\d+$/', '', $normalized); // Remover números al final
        $normalized = preg_replace('/[^a-z]/', '', $normalized); // Remover caracteres no alfabéticos

        return $normalized;
    }

    private function extractPilotoFromFlightDetails($flightDetails)
    {
        try {
            // Ejemplo: "Alarm Response Mission (Ronda Rodial) - Lucia Pardini"
            if (str_contains($flightDetails, '-')) {
                $parts = explode('-', $flightDetails);
                $pilotoName = trim(end($parts)); // "Lucia Pardini"

                Log::info('Extracting piloto from flight details', [
                    'flight_details' => $flightDetails,
                    'extracted_name' => $pilotoName,
                ]);

                // Buscar piloto por nombre (búsqueda flexible)
                $piloto = PilotoFlytbase::where('nombre', 'like', '%'.$pilotoName.'%')
                    ->first();

                if ($piloto) {
                    Log::info('Piloto found in database', [
                        'searched_name' => $pilotoName,
                        'piloto_id' => $piloto->id,
                        'piloto_nombre' => $piloto->nombre,
                    ]);

                    return $piloto->id;
                } else {
                    Log::warning('Piloto not found in database', [
                        'searched_name' => $pilotoName,
                        'available_pilots' => PilotoFlytbase::pluck('nombre'),
                    ]);
                }
            } else {
                Log::warning('No dash found in flight details for piloto extraction', [
                    'flight_details' => $flightDetails,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error extracting piloto from flight details', [
                'flight_details' => $flightDetails,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }
}
