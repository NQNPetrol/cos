<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HikCentralService;
use App\Models\MobileVehicle;
use App\Models\Patrulla;
use Illuminate\Support\Facades\Log;

class MobileVehicleController extends Controller
{
    protected HikCentralService $hikCentral;

    public function __construct(HikCentralService $hikCentral)
    {
        $this->hikCentral = $hikCentral;
    }


    /**
     * Muestra la lista de vehículos móviles
     */
    public function index()
    {
        $mobileVehicles = MobileVehicle::with('patrulla')
            ->orderBy('mobile_vehicle_name')
            ->paginate(20);

        return view('patrullas.location', compact('mobileVehicles'));
    }

    public function show_map(GpsInfo $gpsInfo)
    {
        // metodo que hace peticion get a la api y trate la info de localizacion del vehiculo
    }

    /**
     * Importa los vehículos móviles desde HikCentral
     */
    public function import()
    {
        try {
            Log::info('Iniciando importación de vehículos móviles');

            $results = $this->hikCentral->importMobileVehicles();

            $message = sprintf(
                'Importación completada. Procesados: %d, Nuevos: %d, Actualizados: %d, Vinculados: %d',
                $results['total_processed'],
                $results['total_imported'],
                $results['total_updated'],
                $results['total_linked']
            );

            if (!empty($results['errors'])) {
                $message .= sprintf('. Errores: %d', count($results['errors']));
                Log::warning('Errores durante la importación', $results['errors']);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'results' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Error en importación de vehículos móviles: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error durante la importación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vincula manualmente un vehículo móvil con una patrulla
     */
    public function linkToPatrulla(Request $request, MobileVehicle $mobileVehicle)
    {
        $request->validate([
            'patrulla_id' => 'nullable|exists:patrullas,id'
        ]);

        try {
            $mobileVehicle->update([
                'patrulla_id' => $request->patrulla_id
            ]);

            $message = $request->patrulla_id 
                ? 'Vehículo vinculado exitosamente con la patrulla'
                : 'Vinculación con patrulla removida exitosamente';

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al vincular: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Revincula automáticamente todos los vehículos con sus patrullas por patente
     */
    public function relinkAll()
    {
        try {
            $linked = 0;
            $unlinked = 0;

            $mobileVehicles = MobileVehicle::all();

            foreach ($mobileVehicles as $mobileVehicle) {
                $patrulla = Patrulla::where('patente', $mobileVehicle->plate_no)->first();
                
                if ($patrulla && $mobileVehicle->patrulla_id !== $patrulla->id) {
                    $mobileVehicle->update(['patrulla_id' => $patrulla->id]);
                    $linked++;
                } elseif (!$patrulla && $mobileVehicle->patrulla_id) {
                    $mobileVehicle->update(['patrulla_id' => null]);
                    $unlinked++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Revinculación completada. Vinculados: {$linked}, Desvinculados: {$unlinked}",
                'results' => [
                    'linked' => $linked,
                    'unlinked' => $unlinked
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error en revinculación: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error durante la revinculación: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Muestra los detalles de un vehículo móvil
     */
    public function show(MobileVehicle $mobileVehicle)
    {
        $mobileVehicle->load('patrulla');
        
        return view('mobile-vehicles.show', compact('mobileVehicle'));
    }

    /**
     * API: Obtiene la lista de vehículos móviles en formato JSON
     */
    public function api()
    {
        $mobileVehicles = MobileVehicle::with('patrulla')
            ->select([
                'id',
                'mobile_vehicle_index_code',
                'mobile_vehicle_name',
                'status',
                'plate_no',
                'patrulla_id'
            ])
            ->get();

        return response()->json($mobileVehicles);
    }

    /**
     * API: Obtiene un vehículo móvil específico
     */
    public function apiShow($id)
    {
        $mobileVehicle = MobileVehicle::with('patrulla')->findOrFail($id);
        
        return response()->json($mobileVehicle);
    }

    /**
     * Obtiene estadísticas de los vehículos móviles
     */
    public function stats()
    {
        $stats = [
            'total' => MobileVehicle::count(),
            'active' => MobileVehicle::where('status', 1)->count(),
            'inactive' => MobileVehicle::where('status', 2)->count(),
            'linked' => MobileVehicle::whereNotNull('patrulla_id')->count(),
            'unlinked' => MobileVehicle::whereNull('patrulla_id')->count(),
        ];

        return response()->json($stats);
    }

    public function location()
    {
        // Obtener vehículos móviles
        $mobileVehicles = MobileVehicle::with('patrulla')
            ->orderBy('mobile_vehicle_name')
            ->paginate(20);

        // Obtener códigos de índice de todos los vehículos
        $vehicleIndexCodes = MobileVehicle::pluck('mobile_vehicle_index_code')->toArray();
        
        // Obtener ubicaciones actuales
        $result = $this->hikCentral->getLatestGpsLocations($vehicleIndexCodes);
        $locations = $result['locations'];
        $latestTimestamp = $result['latest_timestamp'];

        Log::debug('Datos para vista location', [
            'vehicles_count' => $mobileVehicles->count(),
            'locations_count' => count($locations),
            'latest_timestamp' => $latestTimestamp,
            'locations_keys' => array_keys($locations)
        ]);

        $dataDate = $latestTimestamp ? 
            date('Y-m-d', $latestTimestamp) : 
            now()->format('Y-m-d');
        
        // Formatear la última actualización para la vista
        $lastUpdate = $latestTimestamp ? 
            date('d/m/Y H:i:s', $latestTimestamp) : 
            'No hay datos disponibles';
        
        return view('patrullas.location', compact('mobileVehicles', 'locations', 'lastUpdate', 'dataDate'));
    }

    /**
     * API: Obtiene las ubicaciones actuales de los vehículos
     */
    public function apiLocations()
    {
        try {
            $vehicleIndexCodes = MobileVehicle::pluck('mobile_vehicle_index_code')->toArray();

            Log::debug('Vehículos en BD para locations', [
                'count' => count($vehicleIndexCodes),
                'codes' => $vehicleIndexCodes
            ]);

            if (empty($vehicleIndexCodes)) {
                Log::warning('No hay vehículos móviles en la base de datos');
                return response()->json([
                    'success' => true,
                    'locations' => [],
                    'timestamp' => now()->toISOString(),
                    'message' => 'No hay vehículos móviles registrados'
                ]);
            }

            $result = $this->hikCentral->getLatestGpsLocations($vehicleIndexCodes);
            $locations = $result['locations'];
            
            Log::debug('Ubicaciones obtenidas para API', [
                'count' => count($locations),
                'vehicles_with_data' => array_keys($locations),
                'sample_data' => !empty($locations) ? reset($locations) : 'No data'
            ]);

            // CORRECCIÓN: Devolver la estructura plana correcta
            return response()->json([
                'success' => true,
                'locations' => $locations, // ← Directamente las locations
                'latest_timestamp' => $result['latest_timestamp'],
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en apiLocations: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo ubicaciones: ' . $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * API: Obtiene datos para el mapa de Google Maps
     */
    public function apiMapData()
    {
        try {
            $vehicleIndexCodes = MobileVehicle::pluck('mobile_vehicle_index_code')->toArray();
            $locations = $this->hikCentral->getLatestGpsLocations($vehicleIndexCodes);
            
            // Obtener información adicional de los vehículos
            $vehicles = MobileVehicle::whereIn('mobile_vehicle_index_code', array_keys($locations))
                ->get()
                ->keyBy('mobile_vehicle_index_code');
            
            $mapData = [];
            
            foreach ($locations as $vehicleCode => $location) {
                $vehicle = $vehicles->get($vehicleCode);
                
                $mapData[] = [
                    'vehicle_code' => $vehicleCode,
                    'vehicle_name' => $vehicle ? $vehicle->mobile_vehicle_name : 'Desconocido',
                    'plate_no' => $location['plateNo'],
                    'latitude' => $location['latitude'],
                    'longitude' => $location['longitude'],
                    'occur_time' => $location['occurTime'],
                    'direction' => $location['direction'],
                    'speed' => $location['speed'],
                    'status' => $vehicle ? $vehicle->status : 0
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $mapData,
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en apiMapData: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo datos del mapa: ' . $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }
}
