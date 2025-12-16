<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\Evento;
use App\Models\Seguimiento;
use App\Models\EncodingDevice;
use App\Models\Rodado;
use App\Models\FlightLog;
use App\Models\Camera;
use App\Models\Dispositivo;
use App\Models\MobileVehicle;
use App\Models\FlytbaseDock;
use App\Models\Cliente;
use App\Models\UserCliente;
use App\Http\Controllers\MobileVehicleController;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OperacionesDashboardController extends Controller
{
    private $hikCentralService;

    public function __construct()
    {
        // Inicializar servicio HikCentral si es necesario
        // $this->hikCentralService = app(HikCentralService::class);
    }

    /**
     * Vista principal del dashboard
     */
    public function index(Request $request)
    {
        // Detectar si es layout cliente
        $isClient = $this->isClientLayout();
        
        // Verificar permisos
        if (!$isClient) {
            $this->authorize('ver.operaciones');
        }

        // Obtener eventos iniciales para el listado
        $clienteId = $this->getClienteFilter($request);
        $eventosIniciales = $this->getEventosIniciales($clienteId);
        
        // Obtener KPIs iniciales
        $kpisIniciales = Cache::remember("operaciones_kpis_" . ($clienteId ?? 'all'), 60, function() use ($clienteId) {
            return $this->calculateKPIs($clienteId);
        });

        return view('operaciones.dashboard', compact('isClient', 'eventosIniciales', 'kpisIniciales'));
    }

    /**
     * API para obtener KPIs
     */
    public function getKPIs(Request $request)
    {
        try {
            $clienteId = $this->getClienteFilter($request);
            $cacheKey = "operaciones_kpis_" . ($clienteId ?? 'all');
            
            $kpis = Cache::remember($cacheKey, 60, function() use ($clienteId) {
                return $this->calculateKPIs($clienteId);
            });

            return response()->json([
                'success' => true,
                'data' => $kpis,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Error obteniendo KPIs: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar KPIs',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * API para obtener datos del mapa
     */
    public function getMapData(Request $request)
    {
        try {
            $validated = $request->validate([
                'cliente_id' => 'nullable|exists:clientes,id',
                'estado_evento' => 'nullable|array',
                'estado_evento.*' => 'in:ABIERTO,EN REVISION,CERRADO'
            ]);

            $clienteId = $this->getClienteFilter($request);
            if ($clienteId) {
                $validated['cliente_id'] = $clienteId;
            }

            $eventos = $this->getEventosParaMapa($validated);
            $vehiculos = $this->getVehiculosParaMapa($validated);
            $docks = $this->getDocksParaMapa();
            $camaras = $this->getCamarasParaMapa($validated);

            return response()->json([
                'success' => true,
                'eventos' => $eventos,
                'vehiculos' => $vehiculos,
                'docks' => $docks,
                'camaras' => $camaras,
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Error obteniendo datos del mapa: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar datos del mapa',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * API para obtener listado de eventos
     */
    public function getEventos(Request $request)
    {
        try {
            $validated = $request->validate([
                'cliente_id' => 'nullable|exists:clientes,id',
                'estado_evento' => 'nullable|array',
                'estado_evento.*' => 'in:ABIERTO,EN REVISION,CERRADO',
                'page' => 'nullable|integer|min:1'
            ]);

            $clienteId = $this->getClienteFilter($request);
            if ($clienteId) {
                $validated['cliente_id'] = $clienteId;
            }

            $page = $validated['page'] ?? 1;
            $perPage = 20;
            
            $eventos = $this->getEventosIniciales(
                $validated['cliente_id'] ?? null,
                $validated['estado_evento'] ?? [],
                $page,
                $perPage
            );

            return response()->json([
                'success' => true,
                'data' => $eventos->items(),
                'pagination' => [
                    'current_page' => $eventos->currentPage(),
                    'last_page' => $eventos->lastPage(),
                    'per_page' => $eventos->perPage(),
                    'total' => $eventos->total()
                ],
                'timestamp' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Error obteniendo eventos: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar eventos',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500);
        }
    }

    /**
     * Obtener eventos iniciales para mostrar en la vista
     */
    private function getEventosIniciales($clienteId = null, $estadosFiltro = [], $page = 1, $perPage = 20)
    {
        $query = Evento::query()
            ->select([
                'eventos.id',
                'eventos.cliente_id',
                'eventos.categoria_id',
                'eventos.fecha_hora',
                'eventos.descripcion',
                'eventos.latitud',
                'eventos.longitud'
            ])
            ->with([
                'cliente:id,nombre',
                'categoria:id,nombre'
            ])
            ->orderBy('eventos.fecha_hora', 'desc');
        
        // Aplicar filtro de cliente
        if ($clienteId) {
            $query->where('eventos.cliente_id', $clienteId);
        }
        
        // Aplicar filtro de estado
        if (!empty($estadosFiltro)) {
            $query->where(function($q) use ($estadosFiltro) {
                // Eventos con seguimientos que tienen el estado (último seguimiento)
                $q->whereExists(function($subquery) use ($estadosFiltro) {
                    $subquery->selectRaw('1')
                        ->from('seguimientos as s1')
                        ->whereColumn('s1.evento_id', 'eventos.id')
                        ->whereIn('s1.estado', $estadosFiltro)
                        ->whereRaw('s1.id = (
                            SELECT MAX(s2.id) 
                            FROM seguimientos s2 
                            WHERE s2.evento_id = s1.evento_id
                            AND s2.fecha = (
                                SELECT MAX(s3.fecha) 
                                FROM seguimientos s3 
                                WHERE s3.evento_id = s1.evento_id
                            )
                        )');
                });
                
                // Eventos sin seguimientos (se consideran ABIERTO por defecto)
                if (in_array('ABIERTO', $estadosFiltro)) {
                    $q->orWhereDoesntHave('seguimientos');
                }
            });
        }
        
        $eventos = $query->paginate($perPage, ['*'], 'page', $page);
        
        // Obtener el último seguimiento para cada evento de forma eficiente
        $eventoIds = $eventos->pluck('id')->toArray();
        
        if (!empty($eventoIds)) {
            // Obtener el último seguimiento por evento usando subconsulta
            $ultimosSeguimientos = Seguimiento::whereIn('evento_id', $eventoIds)
                ->whereRaw('id = (
                    SELECT MAX(s2.id) 
                    FROM seguimientos s2 
                    WHERE s2.evento_id = seguimientos.evento_id
                    AND s2.fecha = (
                        SELECT MAX(s3.fecha) 
                        FROM seguimientos s3 
                        WHERE s3.evento_id = seguimientos.evento_id
                    )
                )')
                ->get()
                ->keyBy('evento_id');
        } else {
            $ultimosSeguimientos = collect();
        }
        
        // Transformar eventos con su estado
        $eventos->getCollection()->transform(function($evento) use ($ultimosSeguimientos) {
            $ultimoSeguimiento = $ultimosSeguimientos->get($evento->id);
            $estado = $ultimoSeguimiento ? $ultimoSeguimiento->estado : 'ABIERTO';
            
            return [
                'id' => $evento->id,
                'cliente' => $evento->cliente->nombre ?? 'N/A',
                'estado' => $estado,
                'fecha_hora' => $evento->fecha_hora->format('Y-m-d H:i:s'),
                'fecha_hora_formatted' => $evento->fecha_hora->format('d/m/Y H:i'),
                'categoria' => $evento->categoria->nombre ?? 'N/A',
                'descripcion' => Str::limit($evento->descripcion ?? '', 100),
                'latitud' => $evento->latitud,
                'longitud' => $evento->longitud,
            ];
        });
        
        return $eventos;
    }

    /**
     * Calcular todos los KPIs
     */
    private function calculateKPIs($clienteId = null)
    {
        // 1. Cantidad de Cámaras (Encoding Devices activos)
        $camarasQuery = EncodingDevice::where('status', 1);
        if ($clienteId) {
            // Filtrar por cliente si es necesario (a través de cámaras -> dispositivos)
            $camarasQuery->whereHas('cameras.dispositivo', function($q) use ($clienteId) {
                $q->where('cliente_id', $clienteId);
            });
        }
        $camarasTotal = $camarasQuery->count();

        // 2. Vehículos vs Equipados
        $vehiculosQuery = Rodado::query();
        if ($clienteId) {
            $vehiculosQuery->where('cliente_id', $clienteId);
        }
        $vehiculosTotal = $vehiculosQuery->count();
        
        // Vehículos equipados: rodados que tienen dispositivos instalados
        // Esto se calcula a través de cambios de equipo o dispositivos con estado "Instalado"
        $vehiculosEquipadosQuery = Rodado::whereHas('cambiosEquipos.dispositivo', function($q) {
            $q->where('estado_inventario', 'Instalado');
        });
        if ($clienteId) {
            $vehiculosEquipadosQuery->where('cliente_id', $clienteId);
        }
        $vehiculosEquipados = $vehiculosEquipadosQuery->count();

        // 3. Trending de Eventos (30 días)
        $hoy = now();
        $hace30Dias = now()->subDays(30);
        $hace60Dias = now()->subDays(60);

        $eventosQueryUltimos30 = Evento::whereBetween('fecha_hora', [$hace30Dias, $hoy]);
        $eventosQueryAnteriores30 = Evento::whereBetween('fecha_hora', [$hace60Dias, $hace30Dias]);

        if ($clienteId) {
            $eventosQueryUltimos30->where('cliente_id', $clienteId);
            $eventosQueryAnteriores30->where('cliente_id', $clienteId);
        }

        $eventosUltimos30 = $eventosQueryUltimos30->count();
        $eventosAnteriores30 = $eventosQueryAnteriores30->count();

        $porcentajeCambio = $eventosAnteriores30 > 0 
            ? round((($eventosUltimos30 - $eventosAnteriores30) / $eventosAnteriores30) * 100, 2)
            : ($eventosUltimos30 > 0 ? 100 : 0);

        $tendencia = $porcentajeCambio >= 0 ? 'aumento' : 'disminucion';

        // 4. Eventos Abiertos
        $eventosAbiertosQuery = Evento::where(function($q) {
            $q->whereExists(function($subquery) {
                $subquery->selectRaw('1')
                    ->from('seguimientos as s1')
                    ->whereColumn('s1.evento_id', 'eventos.id')
                    ->where('s1.estado', 'ABIERTO')
                    ->whereRaw('s1.id = (
                        SELECT MAX(s2.id) 
                        FROM seguimientos s2 
                        WHERE s2.evento_id = s1.evento_id
                        AND s2.fecha = (
                            SELECT MAX(s3.fecha) 
                            FROM seguimientos s3 
                            WHERE s3.evento_id = s1.evento_id
                        )
                    )');
            })->orWhereDoesntHave('seguimientos');
        });

        if ($clienteId) {
            $eventosAbiertosQuery->where('cliente_id', $clienteId);
        }

        $eventosAbiertos = $eventosAbiertosQuery->count();

        // 5. Tiempo Promedio de Cierre
        $eventosCerradosQuery = Evento::whereHas('seguimientos', function($q) {
            $q->where('estado', 'CERRADO');
        });

        if ($clienteId) {
            $eventosCerradosQuery->where('cliente_id', $clienteId);
        }

        $eventosCerrados = $eventosCerradosQuery->get();

        $tiemposCierre = $eventosCerrados->map(function($evento) {
            $creacion = $evento->fecha_hora;
            $cierre = $evento->seguimientos()
                ->where('estado', 'CERRADO')
                ->latest('fecha')
                ->first();
            
            if ($cierre && $cierre->fecha) {
                return $creacion->diffInHours($cierre->fecha);
            }
            return null;
        })->filter();

        $promedioHoras = $tiemposCierre->isNotEmpty() ? $tiemposCierre->avg() : 0;
        $dias = floor($promedioHoras / 24);
        $horas = round($promedioHoras % 24);

        // 6. Vuelos Total vs Incompletos
        $vuelosQuery = FlightLog::query();
        $vuelosIncompletosQuery = FlightLog::whereIn('estado', ['en_proceso', 'interrumpido']);

        if ($clienteId) {
            $vuelosQuery->whereHas('mision', function($q) use ($clienteId) {
                $q->where('cliente_id', $clienteId);
            });
            $vuelosIncompletosQuery->whereHas('mision', function($q) use ($clienteId) {
                $q->where('cliente_id', $clienteId);
            });
        }

        $vuelosTotal = $vuelosQuery->count();
        $vuelosIncompletos = $vuelosIncompletosQuery->count();

        // 7. Triggers de Misiones
        $triggersQuery = FlightLog::whereNotNull('trigger_senttime')
            ->with(['mision.cliente', 'mision.drone']);

        if ($clienteId) {
            $triggersQuery->whereHas('mision', function($q) use ($clienteId) {
                $q->where('cliente_id', $clienteId);
            });
        }

        $triggers = $triggersQuery->get()
            ->groupBy(function($log) {
                return $log->drone_name ?? 'Desconocido';
            })
            ->map(function($logs, $drone) {
                return [
                    'drone_name' => $drone,
                    'count' => $logs->count(),
                    'cliente' => $logs->first()->mision->cliente->nombre ?? 'N/A'
                ];
            })
            ->values();

        return [
            'camaras_total' => $camarasTotal,
            'vehiculos_total' => $vehiculosTotal,
            'vehiculos_equipados' => $vehiculosEquipados,
            'eventos_trending' => [
                'porcentaje_cambio' => abs($porcentajeCambio),
                'tendencia' => $tendencia,
                'periodo' => '30_dias',
                'eventos_actuales' => $eventosUltimos30,
                'eventos_anteriores' => $eventosAnteriores30
            ],
            'eventos_abiertos' => $eventosAbiertos,
            'tiempo_promedio_cierre' => [
                'dias' => $dias,
                'horas' => $horas,
                'total_horas' => round($promedioHoras, 2)
            ],
            'vuelos_total' => $vuelosTotal,
            'vuelos_incompletos' => $vuelosIncompletos,
            'triggers_misiones' => [
                'total' => $triggers->sum('count'),
                'por_dron' => $triggers->toArray()
            ]
        ];
    }

    /**
     * Obtener eventos para el mapa
     */
    private function getEventosParaMapa(array $filters)
    {
        try {
            $query = Evento::query()
                ->select([
                    'eventos.id',
                    'eventos.cliente_id',
                    'eventos.categoria_id',
                    'eventos.fecha_hora',
                    'eventos.descripcion',
                    'eventos.latitud',
                    'eventos.longitud'
                ])
                ->with([
                    'cliente:id,nombre',
                    'categoria:id,nombre'
                ])
                ->whereNotNull('eventos.latitud')
                ->whereNotNull('eventos.longitud');

            if (isset($filters['cliente_id'])) {
                $query->where('eventos.cliente_id', $filters['cliente_id']);
            }

            if (isset($filters['estado_evento']) && !empty($filters['estado_evento'])) {
                $query->where(function($q) use ($filters) {
                    // Eventos con seguimientos que tienen el estado
                    $q->whereHas('seguimientos', function($sq) use ($filters) {
                        $sq->whereIn('estado', $filters['estado_evento'])
                           ->whereRaw('seguimientos.id = (
                               SELECT MAX(s2.id) 
                               FROM seguimientos s2 
                               WHERE s2.evento_id = seguimientos.evento_id
                           )');
                    });
                    
                    // Eventos sin seguimientos (se consideran ABIERTO por defecto)
                    if (in_array('ABIERTO', $filters['estado_evento'])) {
                        $q->orWhereDoesntHave('seguimientos');
                    }
                });
            }

            $eventos = $query->get();
            
            // Obtener últimos seguimientos de forma eficiente
            $eventoIds = $eventos->pluck('id')->toArray();
            $ultimosSeguimientos = Seguimiento::whereIn('evento_id', $eventoIds)
                ->selectRaw('seguimientos.*, 
                    ROW_NUMBER() OVER (PARTITION BY seguimientos.evento_id ORDER BY seguimientos.fecha DESC, seguimientos.id DESC) as rn')
                ->get()
                ->filter(function($item) {
                    return $item->rn == 1;
                })
                ->keyBy('evento_id');

            return $eventos->map(function($evento) use ($ultimosSeguimientos) {
                $ultimoSeguimiento = $ultimosSeguimientos->get($evento->id);
                $estado = $ultimoSeguimiento ? $ultimoSeguimiento->estado : 'ABIERTO';

                return [
                    'id' => $evento->id,
                    'latitud' => (float) $evento->latitud,
                    'longitud' => (float) $evento->longitud,
                    'estado' => $estado,
                    'cliente' => $evento->cliente->nombre ?? 'N/A',
                    'fecha_hora' => $evento->fecha_hora->format('Y-m-d H:i:s'),
                    'fecha_hora_formatted' => $evento->fecha_hora->format('d/m/Y H:i'),
                    'categoria' => $evento->categoria->nombre ?? 'N/A',
                    'descripcion' => $evento->descripcion ?? ''
                ];
            });

        } catch (\Exception $e) {
            Log::error('Error obteniendo eventos para mapa: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Obtener vehículos para el mapa
     */
    private function getVehiculosParaMapa(array $filters)
    {
        try {
            $vehicleQuery = MobileVehicle::with('patrulla.cliente');
            
            if (isset($filters['cliente_id'])) {
                $vehicleQuery->whereHas('patrulla', function($q) use ($filters) {
                    $q->where('cliente_id', $filters['cliente_id']);
                });
            }
            
            $vehicles = $vehicleQuery->get();
            
            if ($vehicles->isEmpty()) {
                return [];
            }

            $vehicleIndexCodes = $vehicles->pluck('mobile_vehicle_index_code')->toArray();

            // Obtener ubicaciones desde HikCentral
            $hikCentralService = app(\App\Services\HikCentralService::class);
            $result = $hikCentralService->getLatestGpsLocations($vehicleIndexCodes);
            $locations = $result['locations'] ?? [];

            $mapData = [];

            foreach ($vehicles as $vehicle) {
                $vehicleCode = $vehicle->mobile_vehicle_index_code;
                $location = $locations[$vehicleCode] ?? null;

                if ($location && isset($location['latitude']) && isset($location['longitude'])) {
                    $mapData[] = [
                        'vehicle_code' => $vehicleCode,
                        'vehicle_name' => $vehicle->mobile_vehicle_name,
                        'plate_no' => $location['plateNo'] ?? $vehicle->plate_no ?? 'N/A',
                        'latitude' => (float) $location['latitude'],
                        'longitude' => (float) $location['longitude'],
                        'speed' => $location['speed'] ?? 0,
                        'direction' => $location['direction'] ?? 0,
                        'occur_time' => $location['occurTime'] ?? 'N/A',
                        'status' => $vehicle->status ?? 0,
                        'cliente' => $vehicle->patrulla->cliente->nombre ?? 'N/A'
                    ];
                }
            }

            return $mapData;

        } catch (\Exception $e) {
            Log::error('Error obteniendo vehículos para mapa: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener docks para el mapa
     */
    private function getDocksParaMapa()
    {
        try {
            return FlytbaseDock::with('site')
                ->where('active', true)
                ->whereNotNull('latitud')
                ->whereNotNull('longitud')
                ->get()
                ->map(function($dock) {
                    return [
                        'id' => $dock->id,
                        'nombre' => $dock->nombre,
                        'latitud' => (float) $dock->latitud,
                        'longitud' => (float) $dock->longitud,
                        'altitude' => $dock->altitude,
                        'active' => $dock->active,
                        'site' => $dock->site->nombre ?? 'N/A'
                    ];
                });

        } catch (\Exception $e) {
            Log::error('Error obteniendo docks para mapa: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Obtener cámaras para el mapa
     */
    private function getCamarasParaMapa(array $filters)
    {
        try {
            // Verificar si la columna dispositivo_id existe
            $hasDispositivoId = \Schema::hasColumn('cameras', 'dispositivo_id');
            
            if (!$hasDispositivoId) {
                // Si no existe la columna, retornar vacío (la migración no se ejecutó)
                Log::warning('La columna dispositivo_id no existe en la tabla cameras. Ejecutar migración.');
                return collect([]);
            }
            
            $query = Camera::with(['dispositivo.cliente'])
                ->whereNotNull('dispositivo_id')
                ->whereHas('dispositivo', function($q) {
                    $q->whereNotNull('ubicacion');
                });

            if (isset($filters['cliente_id'])) {
                $query->whereHas('dispositivo', function($q) use ($filters) {
                    $q->where('cliente_id', $filters['cliente_id']);
                });
            }

            return $query->get()
                ->filter(function($camara) {
                    return $camara->dispositivo && $camara->dispositivo->tieneCoordenadas();
                })
                ->map(function($camara) {
                    $coords = $camara->dispositivo->coordenadas;

                    return [
                        'id' => $camara->id,
                        'camera_name' => $camara->camera_name,
                        'latitud' => $coords['lat'],
                        'longitud' => $coords['lng'],
                        'dispositivo_id' => $camara->dispositivo_id,
                        'dispositivo_nombre' => $camara->dispositivo->nombre ?? 'N/A',
                        'camera_index_code' => $camara->camera_index_code,
                        'cliente' => $camara->dispositivo->cliente->nombre ?? 'N/A',
                        'status' => $camara->status,
                        'direccion_ip' => $camara->dispositivo->direccion_ip ?? null
                    ];
                })
                ->values();

        } catch (\Exception $e) {
            Log::error('Error obteniendo cámaras para mapa: ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Obtener filtro de cliente según el tipo de usuario
     */
    private function getClienteFilter(Request $request = null)
    {
        $user = Auth::user();

        if ($this->isClientLayout()) {
            // Usuario cliente: obtener su cliente_id
            $userCliente = UserCliente::where('user_id', $user->id)->first();

            if (!$userCliente) {
                return null;
            }

            return $userCliente->cliente_id;
        }

        // Admin/Operador: usar filtro de request si existe
        if ($request) {
            return $request->get('cliente_id');
        }

        return null;
    }

    /**
     * Verificar si es layout cliente
     */
    private function isClientLayout()
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Verificar si el usuario tiene cliente asignado
        return UserCliente::where('user_id', $user->id)->exists();
    }
}

