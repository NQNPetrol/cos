<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HikCentralService;
use App\Models\MobileVehicle;
use App\Models\Patrulla;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MobileVehicleClientController extends Controller
{
    protected HikCentralService $hikCentral;

    public function __construct(HikCentralService $hikCentral)
    {
        $this->hikCentral = $hikCentral;
    }

    private function getClienteIds()
    {
        $user = Auth::user();
        
        if (!$user) {
            return collect();
        }

        return $user->clientes()->pluck('clientes.id');
    }

    private function getPatentesUsuario()
    {
        $clienteIds = $this->getClienteIds();
        
        if ($clienteIds->isEmpty()) {
            return [];
        }

        return Patrulla::whereIn('cliente_id', $clienteIds)
            ->pluck('patente')
            ->toArray();
    }

    private function redirectIfNoClientes()
    {
        $clienteIds = $this->getClienteIds();
        
        if ($clienteIds->isEmpty()) {
            return redirect()->back()->with('error', 'No tienes clientes asignados. Contacta al administrador.');
        }
        
        return null;
    }


    public function locationClient()
    {
        $redirect = $this->redirectIfNoClientes();
        if ($redirect) {
            return $redirect;
        }

        // Obtener array de patentes del usuario
        $patentesUsuario = $this->getPatentesUsuario();

        Log::info('Filtrado por cliente - Paso 1: Patentes del usuario', [
            'user_id' => Auth::id(),
            'patentes_count' => count($patentesUsuario),
            'patentes' => $patentesUsuario
        ]);

        // Si no hay patentes en el array, mostrar mensaje
        if (empty($patentesUsuario)) {
            Log::info('Usuario no tiene patrullas asignadas', ['user_id' => Auth::id()]);
            return view('patrullas.client.no-vehicles', [
                'message' => 'Tu empresa no tiene patrullas asignadas para visualizar.'
            ]);
        }

        // Filtrar mobile_vehicles por las patentes del array
        $mobileVehicles = MobileVehicle::with('patrulla')
            ->whereIn('plate_no', $patentesUsuario)
            ->orderBy('mobile_vehicle_name')
            ->paginate(20);

        Log::info('Filtrado por cliente -  Vehículos móviles encontrados', [
            'user_id' => Auth::id(),
            'mobile_vehicles_count' => $mobileVehicles->count(),
            'mobile_vehicles' => $mobileVehicles->pluck('plate_no', 'mobile_vehicle_index_code')->toArray()
        ]);

        // Si el array no está vacío pero no hay coincidencias en mobile_vehicles
        if ($mobileVehicles->isEmpty()) {
            Log::info('Usuario tiene patrullas pero no están en HikCentral', [
                'user_id' => Auth::id(),
                'patentes_buscadas' => $patentesUsuario
            ]);
            return view('patrullas.client.no-vehicles', [
                'message' => 'Tus patrullas asignadas no están conectadas al sistema HikCentral.'
            ]);
        }

        // Obtener códigos de índice de los vehículos filtrados
        $vehicleIndexCodes = $mobileVehicles->pluck('mobile_vehicle_index_code')->toArray();
        
        // Hacer el request a la API igual que el controlador principal pero filtrado
        $result = $this->hikCentral->getLatestGpsLocations($vehicleIndexCodes);
        $locations = $result['locations'];
        $latestTimestamp = $result['latest_timestamp'];

        Log::info('Filtrado por cliente - Datos de ubicación obtenidos', [
            'user_id' => Auth::id(),
            'vehicles_count' => $mobileVehicles->count(),
            'locations_count' => count($locations),
            'vehicles_con_ubicacion' => array_keys($locations),
            'latest_timestamp' => $latestTimestamp
        ]);

        // Filtrar las locations para mostrar solo las que tienen coordenadas
        $locationsConCoordenadas = array_filter($locations, function($location) {
            return !empty($location['latitude']) && !empty($location['longitude']);
        });

        Log::info('Ubicaciones con coordenadas válidas', [
            'total_locations' => count($locations),
            'locations_con_coordenadas' => count($locationsConCoordenadas),
            'vehicles_con_coordenadas' => array_keys($locationsConCoordenadas)
        ]);

        // Formatear la última actualización para la vista
        $lastUpdate = $latestTimestamp ? 
            date('d/m/Y H:i:s', $latestTimestamp) : 
            'No hay datos disponibles';
        
        return view('patrullas.client.location-client', compact(
            'mobileVehicles', 
            'locations', 
            'lastUpdate',
            'locationsConCoordenadas',
            'patentesUsuario',
        ));
    }

    public function apiLocationsClient(Request $request)
    {
        try {
            
            $patentes = $request->get('patentes');

            if (!$patentes) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parámetro patentes requerido',
                    'timestamp' => now()->toISOString()
                ], 400);
            }

            $patentesUsuario = json_decode($patentes, true);
            
            Log::debug('API Client - Patentes del usuario', [
                'patentes_count' => count($patentesUsuario),
                'patentes' => $patentesUsuario
            ]);

            if (empty($patentesUsuario)) {
                return response()->json([
                    'success' => true,
                    'locations' => [],
                    'timestamp' => now()->toISOString(),
                    'message' => 'No hay patrullas asignadas a tus clientes'
                ]);
            }

            // Filtrar mobile_vehicles por las patentes del array
            $mobileVehicles = MobileVehicle::whereIn('plate_no', $patentesUsuario)->get();
            
            if ($mobileVehicles->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'locations' => [],
                    'timestamp' => now()->toISOString(),
                    'message' => 'Tus patrullas asignadas no están conectadas al sistema HikCentral'
                ]);
            }

            // Obtener códigos de los vehículos filtrados
            $vehicleIndexCodes = $mobileVehicles->pluck('mobile_vehicle_index_code')->toArray();

            Log::debug('API Client - Vehículos filtrados', [
                'vehicle_codes_count' => count($vehicleIndexCodes),
                'vehicle_codes' => $vehicleIndexCodes
            ]);

            // Hacer request a la API con los vehículos filtrados
            $result = $this->hikCentral->getLatestGpsLocations($vehicleIndexCodes);
        

            return response()->json([
                'success' => true,
                'locations' => $result['locations'],
                'latest_timestamp' => $result['latest_timestamp'],
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en apiLocationsClient: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo ubicaciones: ' . $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }


     public function apiMapDataClient(Request $request)
    {
        try {
            // Obtener array de patentes del usuario
            $patentes = $request->get('patentes');

            if (!$patentes) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parámetro patentes requerido',
                    'timestamp' => now()->toISOString()
                ], 400);
            }

            $patentesUsuario = json_decode($patentes, true);
            
            if (empty($patentesUsuario)) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'timestamp' => now()->toISOString(),
                    'message' => 'No hay patrullas asignadas a tus clientes'
                ]);
            }

            // Filtrar mobile_vehicles por las patentes del array
            $mobileVehicles = MobileVehicle::whereIn('plate_no', $patentesUsuario)->get();
            
            if ($mobileVehicles->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'timestamp' => now()->toISOString(),
                    'message' => 'Tus patrullas asignadas no están conectadas al sistema HikCentral'
                ]);
            }

            //  Obtener códigos de los vehículos filtrados
            $vehicleIndexCodes = $mobileVehicles->pluck('mobile_vehicle_index_code')->toArray();
            
            $result = $this->hikCentral->getLatestGpsLocations($vehicleIndexCodes);
            $locations = $result['locations'];
            
            // Obtener información adicional de los vehículos filtrados
            $vehicles = $mobileVehicles->keyBy('mobile_vehicle_index_code');
            
            $mapData = [];
            
            foreach ($locations as $vehicleCode => $location) {
                $vehicle = $vehicles->get($vehicleCode);
                
                // Solo incluir vehículos que tienen coordenadas válidas
                if ($vehicle && $location && isset($location['latitude']) && isset($location['longitude'])) {
                    $mapData[] = [
                        'vehicle_code' => $vehicleCode,
                        'vehicle_name' => $vehicle->mobile_vehicle_name,
                        'plate_no' => $location['plateNo'] ?? $vehicle->plate_no,
                        'latitude' => $location['latitude'],
                        'longitude' => $location['longitude'],
                        'occur_time' => $location['occurTime'] ?? 'N/A',
                        'direction' => $location['direction'] ?? 0,
                        'speed' => $location['speed'] ?? 0,
                        'status' => $vehicle->status
                    ];
                }
            }

            Log::debug('API Map Data Client - Datos para mapa', [
                'total_vehicles' => count($vehicleIndexCodes),
                'vehicles_con_ubicacion' => count($mapData),
                'map_data_vehicles' => array_column($mapData, 'vehicle_code')
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $mapData,
                'timestamp' => now()->toISOString(),
                'filtered_vehicles_count' => count($vehicleIndexCodes),
                'vehicles_with_location_count' => count($mapData)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en apiMapDataClient: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo datos del mapa: ' . $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    public function apiMobileVehiclesClient()
    {
        try {
            $patentesUsuario = $this->getPatentesUsuario();
            
            if (empty($patentesUsuario)) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'timestamp' => now()->toISOString(),
                    'message' => 'No hay patrullas asignadas a tus clientes'
                ]);
            }

            $mobileVehicles = MobileVehicle::with('patrulla')
                ->whereIn('plate_no', $patentesUsuario)
                ->select([
                    'id',
                    'mobile_vehicle_index_code',
                    'mobile_vehicle_name',
                    'status',
                    'plate_no',
                    'patrulla_id',
                    'region_index_code'
                ])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $mobileVehicles,
                'timestamp' => now()->toISOString(),
                'count' => $mobileVehicles->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en apiMobileVehiclesClient: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo vehículos: ' . $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }
    
    public function statsClient()
    {
        try {
            $patentesUsuario = $this->getPatentesUsuario();
            
            if (empty($patentesUsuario)) {
                return response()->json([
                    'success' => true,
                    'stats' => [
                        'total' => 0,
                        'active' => 0,
                        'inactive' => 0,
                        'with_location' => 0,
                        'message' => 'No hay patrullas asignadas'
                    ],
                    'timestamp' => now()->toISOString()
                ]);
            }

            $mobileVehicles = MobileVehicle::whereIn('plate_no', $patentesUsuario)->get();
            $vehicleIndexCodes = $mobileVehicles->pluck('mobile_vehicle_index_code')->toArray();
            
            $locations = $this->hikCentral->getLatestGpsLocations($vehicleIndexCodes);
            $locationsConCoordenadas = array_filter($locations, function($location) {
                return !empty($location['latitude']) && !empty($location['longitude']);
            });

            $stats = [
                'total' => $mobileVehicles->count(),
                'active' => $mobileVehicles->where('status', 1)->count(),
                'inactive' => $mobileVehicles->where('status', 2)->count(),
                'with_location' => count($locationsConCoordenadas),
                'without_location' => $mobileVehicles->count() - count($locationsConCoordenadas)
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en statsClient: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo estadísticas: ' . $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }
}
