<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\FlightLog;
use App\Models\PilotoFlytbase;
use Carbon\Carbon;

class FlytbaseFlightLogsController extends Controller
{
    /**
     * Procesar datos del email de Flytbase y actualizar flight log
     */
    public function storeFromEmail(Request $request)
    {
        try {
            Log::info('Incoming Flytbase flight log data from email', ['request_data' => $request->all()]);

            // Validar datos mínimos requeridos
            $validator = Validator::make($request->all(), [
                'event_timestamp' => 'required|date',
                'drone_name' => 'required|string',
                'flight_details' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $droneNameToSearch = $request->drone_name_custom ?? $request->drone_name;

            Log::info('Drone name for search', [
                'drone_name_flytbase' => $request->drone_name,
                'drone_name_custom' => $request->drone_name_custom,
                'drone_name_used_for_search' => $droneNameToSearch
            ]);

            // Buscar flight log más cercano al timestamp del evento
            $flightLog = $this->findClosestFlightLog($request->event_timestamp, $droneNameToSearch);
            
            if (!$flightLog) {
                Log::warning('No flight log found close to event timestamp', [
                    'event_timestamp' => $request->event_timestamp,
                    'drone_name' => $request->drone_name,
                    'drone_name_custom' => $request->drone_name_custom,
                    'drone_name_used_for_search' => $droneNameToSearch,
                    'search_range' => '30 minutes before event'
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'No matching flight log found for update'
                ], 404);
            }

            $pilotoId = $this->extractPilotoFromFlightDetails($request->flight_details);
            
            if (!$pilotoId) {
                Log::warning('Piloto not found in flight details', [
                    'flight_details' => $request->flight_details
                ]);
                // Podemos continuar sin piloto o decidir fallar
                // Por ahora continuamos sin actualizar el piloto
            }
            

            // Convertir timestamp del email
            $flightEndTime = Carbon::parse($request->event_timestamp);

            // Calcular flight_time
            $flightTime = null;
            if ($flightLog->flight_starttime) {
                $flightTime = $flightLog->flight_starttime->diffInSeconds($flightEndTime);
                Log::info('Flight time calculated', [
                    'start' => $flightLog->flight_starttime,
                    'end' => $flightEndTime,
                    'seconds' => $flightTime
                ]);
            }

            // Preparar datos para actualizar
            $updateData = [
                'event_id' => $request->event_id,
                'message' => $request->message,
                'severity' => $request->severity,
                'dock_name' => $request->dock_name, //actualizar cuando se cree tabla docks
                'event_coordinates' => $request->event_coordinates,
                'site' => $request->site,
                'organization' => $request->organization,
                'automation' => $request->automation,
                'drone_battery' => $request->drone_battery,
                'flight_details' => $request->flight_details,
                'event_timestamp' => $flightEndTime,
                'flight_endtime' => $flightEndTime,
                'flight_time' => $flightTime,
                'total_distance' => null, //temporalmente
                'estado' => FlightLog::ESTADO_COMPLETADO,
            ];

            if ($pilotoId) {
                $updateData['piloto_flytbase_id'] = $pilotoId;
                Log::info('Piloto will be updated in flight log', ['piloto_id' => $pilotoId]);
            }

            // Actualizar el flight log
            $flightLog->update($updateData);

            Log::info('Flytbase flight log updated successfully from email', [
                'flight_log_id' => $flightLog->id,
                'drone_name' => $request->drone_name,
                'drone_name_custom' => $request->drone_name_custom,
                'flight_time_seconds' => $flightTime,
                'flight_starttime' => $flightLog->flight_starttime,
                'flight_endtime' => $flightEndTime,
                'piloto_updated' => !is_null($pilotoId),
                'piloto_id' => $pilotoId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Flight log updated successfully',
                'data' => [
                    'id' => $flightLog->id,
                    'flight_time_seconds' => $flightTime,
                    'flight_time_readable' => $flightLog->duracion_legible,
                    'status' => 'completed',
                    'piloto_assigned' => !is_null($pilotoId),
                    'drone_match' => [
                        'requested' => $request->drone_name,
                        'custom' => $request->drone_name_custom,
                        'found_in_db' => $flightLog->drone_name
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error processing Flytbase flight log from email', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error: ' . $e->getMessage()
            ], 500);
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
            'event_time' => $eventTime
        ]);

        // Si tenemos drone_name, hacer búsqueda flexible
        if ($droneName) {
            // Normalizar el nombre del drone para búsqueda flexible
            $cleanedDroneName = preg_replace('/<[^>]*>/', '', $droneName);
            $cleanedDroneName = trim($cleanedDroneName);
            
            $query->where(function($q) use ($cleanedDroneName) {
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
        $closestLog = $flightLogs->sortBy(function($log) use ($eventTime) {
            return abs($log->flight_starttime->diffInSeconds($eventTime));
        })->first();

        Log::info('Closest flight log found', [
            'flight_log_id' => $closestLog->id,
            'flight_starttime' => $closestLog->flight_starttime,
            'drone_name_in_db' => $closestLog->drone_name,
            'drone_name_requested' => $droneName,
            'event_timestamp' => $eventTime,
            'time_difference_seconds' => $closestLog->flight_starttime->diffInSeconds($eventTime),
            'match_type' => $closestLog->drone_name === $droneName ? 'exact' : 'time_based'
        ]);

        return $closestLog;
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
                    'extracted_name' => $pilotoName
                ]);

                // Buscar piloto por nombre (búsqueda flexible)
                $piloto = PilotoFlytbase::where('nombre', 'like', '%' . $pilotoName . '%')
                    ->first();

                if ($piloto) {
                    Log::info('Piloto found in database', [
                        'searched_name' => $pilotoName,
                        'piloto_id' => $piloto->id,
                        'piloto_nombre' => $piloto->nombre
                    ]);
                    return $piloto->id;
                } else {
                    Log::warning('Piloto not found in database', [
                        'searched_name' => $pilotoName,
                        'available_pilots' => PilotoFlytbase::pluck('nombre')
                    ]);
                }
            } else {
                Log::warning('No dash found in flight details for piloto extraction', [
                    'flight_details' => $flightDetails
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error extracting piloto from flight details', [
                'flight_details' => $flightDetails,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }
    
}
