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
     * Vista principal del dashboard (layout admin / operadores)
     */
    public function index(Request $request)
    {
        // En el layout principal el dashboard es global (sin filtro automático por cliente)
        $isClient = false;

        // Permitir aplicar un filtro manual opcional por cliente desde el layout principal
        $clienteId = $this->getClienteFilter($request);
        $eventosIniciales = $this->getEventosIniciales($clienteId);
        
        // Obtener KPIs iniciales (cacheados por contexto de cliente)
        $kpisIniciales = Cache::remember(
            'operaciones_kpis_' . ($clienteId ?? 'all'),
            60,
            function () use ($clienteId) {
                return $this->calculateKPIs($clienteId);
            }
        );

        return view('operaciones.dashboard', compact('isClient', 'eventosIniciales', 'kpisIniciales'));
    }

    /**
     * Vista del dashboard operacional para clientes (layout clientes)
     */
    public function indexClient(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $isClient = true;

        // Para el layout de clientes siempre se filtra por el cliente asociado al usuario
        $clienteId = $this->getClienteFilter($request);

        if (!$clienteId) {
            // Si el usuario no tiene cliente asociado no debe ver este dashboard
            abort(403, 'Usuario cliente sin cliente asociado');
        }

        $eventosIniciales = $this->getEventosIniciales($clienteId);

        // KPIs cacheados por cliente
        $kpisIniciales = Cache::remember(
            'operaciones_kpis_' . $clienteId,
            60,
            function () use ($clienteId) {
                return $this->calculateKPIs($clienteId);
            }
        );

        return view('operaciones.client-dashboard', compact('isClient', 'eventosIniciales', 'kpisIniciales'));
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

            Log::info('OperacionesDashboard@getMapData llamado', [
                'cliente_id' => $validated['cliente_id'] ?? null,
                'estado_evento' => $validated['estado_evento'] ?? null,
                'user_id' => optional($request->user())->id,
                'is_client_layout' => $this->isClientLayout(),
            ]);

            $eventos = $this->getEventosParaMapa($validated);
            $vehiculos = $this->getVehiculosParaMapa($validated);
            $docks = $this->getDocksParaMapa();
            $camaras = $this->getCamarasParaMapa($validated);

            Log::info('OperacionesDashboard@getMapData resultados', [
                'eventos_count' => is_countable($eventos) ? count($eventos) : null,
                'vehiculos_count' => is_countable($vehiculos) ? count($vehiculos) : null,
                'docks_count' => is_countable($docks) ? count($docks) : null,
                'camaras_count' => is_countable($camaras) ? count($camaras) : null,
            ]);

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
                'eventos.longitud',
                'eventos.tipo',
                'eventos.user_id',
            ])
            ->with([
                'cliente:id,nombre',
                'categoria:id,nombre',
                'creador:id,name',
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

            // Formato de fecha legible en español: "Viernes, 12 de Enero 2025 11:56hs"
            $fechaFormateada = $evento->fecha_hora
                ? \Illuminate\Support\Str::ucfirst(
                    $evento->fecha_hora
                        ->locale('es')
                        ->translatedFormat('l, d \\d\\e F Y H:i\\h\\s')
                )
                : null;
            
            return [
                'id' => $evento->id,
                'cliente' => $evento->cliente->nombre ?? 'N/A',
                'estado' => $estado,
                'fecha_hora' => $evento->fecha_hora->format('Y-m-d H:i:s'),
                'fecha_hora_formatted' => $fechaFormateada ?? $evento->fecha_hora->format('d/m/Y H:i'),
                'categoria' => $evento->tipo ?? ($evento->categoria->nombre ?? 'N/A'),
                'descripcion' => Str::limit($evento->descripcion ?? '', 100),
                'latitud' => $evento->latitud,
                'longitud' => $evento->longitud,
                'registrado_por' => $evento->creador->name
                    ?? ($evento->user_id ? 'Usuario ID ' . $evento->user_id : 'N/A'),
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
        // Total de vehículos: todos los registros en rodados (con filtrado opcional por cliente)
        $rodadosQuery = Rodado::query();
        if ($clienteId) {
            $rodadosQuery->where('cliente_id', $clienteId);
        }
        $rodados = $rodadosQuery->select('patente')->get();
        $vehiculosTotal = $rodados->count();

        // Vehículos equipados:
        // rodados cuya patente coincide con plate_no de mobile_vehicles (vehículos conectados)
        $normalizePlaca = function ($value) {
            if ($value === null) {
                return null;
            }

            // Eliminar espacios y guiones, pasar a mayúsculas
            $normalized = strtoupper(preg_replace('/[\s\-]+/', '', (string) $value));

            return $normalized !== '' ? $normalized : null;
        };

        // Patentes de rodados normalizadas
        $patentesRodados = $rodados->pluck('patente')
            ->map($normalizePlaca)
            ->filter()
            ->unique();

        // Placas en mobile_vehicles normalizadas
        $mobileQuery = MobileVehicle::query();
        if ($clienteId) {
            // Filtrar mobile_vehicles por cliente a través de la relación con patrulla
            $mobileQuery->whereHas('patrulla', function ($q) use ($clienteId) {
                $q->where('cliente_id', $clienteId);
            });
        }

        $platesMobile = $mobileQuery->pluck('plate_no')
            ->map($normalizePlaca)
            ->filter()
            ->unique();

        // Intersección de patentes entre rodados y mobile_vehicles
        $vehiculosEquipados = $patentesRodados->intersect($platesMobile)->count();

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
                    'eventos.longitud',
                    'eventos.tipo',
                    'eventos.user_id',
                ])
                ->with([
                    'cliente:id,nombre',
                    'categoria:id,nombre',
                    'creador:id,name',
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

                // Formato de fecha legible en español: "Viernes, 12 de Enero 2025 11:56hs"
                $fechaFormateada = $evento->fecha_hora
                    ? \Illuminate\Support\Str::ucfirst(
                        $evento->fecha_hora
                            ->locale('es')
                            ->translatedFormat('l, d \\d\\e F Y H:i\\h\\s')
                    )
                    : null;

                return [
                    'id' => $evento->id,
                    'latitud' => (float) $evento->latitud,
                    'longitud' => (float) $evento->longitud,
                    'estado' => $estado,
                    'cliente' => $evento->cliente->nombre ?? 'N/A',
                    'fecha_hora' => $evento->fecha_hora->format('Y-m-d H:i:s'),
                    'fecha_hora_formatted' => $fechaFormateada ?? $evento->fecha_hora->format('d/m/Y H:i'),
                    'categoria' => $evento->tipo ?? ($evento->categoria->nombre ?? 'N/A'),
                    'descripcion' => $evento->descripcion ?? '',
                    'registrado_por' => $evento->creador->name
                        ?? ($evento->user_id ? 'Usuario ID ' . $evento->user_id : 'N/A'),
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
            return FlytbaseDock::with([
                    'site',
                    'drones' => function ($q) {
                        $q->activos();
                    },
                ])
                ->where('active', true)
                ->whereNotNull('latitud')
                ->whereNotNull('longitud')
                ->get()
                ->map(function($dock) {
                    $drone = $dock->drones->first();

                    return [
                        'id' => $dock->id,
                        'nombre' => $dock->nombre,
                        'latitud' => (float) $dock->latitud,
                        'longitud' => (float) $dock->longitud,
                        'altitude' => $dock->altitude,
                        'active' => $dock->active,
                        'site' => $dock->site->nombre ?? 'N/A',
                        'drone' => $drone?->drone,
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
                ->whereHas('dispositivo');

            if (isset($filters['cliente_id'])) {
                $query->whereHas('dispositivo', function($q) use ($filters) {
                    $q->where('cliente_id', $filters['cliente_id']);
                });
            }

            $camaras = $query->get();

            Log::info('OperacionesDashboard: cámaras encontradas para mapa', [
                'total' => $camaras->count(),
            ]);

            $resultado = $camaras
                ->filter(function($camara) {
                    $tiene = $camara->dispositivo && $camara->dispositivo->tieneCoordenadas();
                    Log::info('OperacionesDashboard: cámara evaluada para coordenadas', [
                        'camera_id' => $camara->id,
                        'camera_name' => $camara->camera_name,
                        'dispositivo_id' => $camara->dispositivo_id,
                        'tiene_coordenadas' => $tiene,
                        'latitud' => $camara->dispositivo->latitud ?? null,
                        'longitud' => $camara->dispositivo->longitud ?? null,
                        'ubicacion' => $camara->dispositivo->ubicacion ?? null,
                        'dispositivo_nombre' => $camara->dispositivo->nombre ?? null,
                    ]);
                    return $tiene;
                })
                ->map(function($camara) {
                    $coords = $camara->dispositivo->coordenadas;

                    $dispositivoNombre = $camara->dispositivo->nombre
                        ?? $camara->dispositivo->modelo
                        ?? $camara->dispositivo->direccion_ip
                        ?? 'Sin nombre';

                    return [
                        'id' => $camara->id,
                        'camera_name' => $camara->camera_name,
                        'latitud' => $coords['lat'],
                        'longitud' => $coords['lng'],
                        'dispositivo_id' => $camara->dispositivo_id,
                        'dispositivo_nombre' => $dispositivoNombre,
                        'camera_index_code' => $camara->camera_index_code,
                        'cliente' => $camara->dispositivo->cliente->nombre ?? 'N/A',
                        'status' => $camara->status,
                        'direccion_ip' => $camara->dispositivo->direccion_ip ?? null,
                        'observaciones' => $camara->dispositivo->observaciones ?? null,
                        'fecha_instalacion' => optional($camara->dispositivo->fecha_instalacion)?->format('Y-m-d'),
                        'fecha_instalacion_formatted' => optional($camara->dispositivo->fecha_instalacion)?->format('d/m/Y'),
                    ];
                })
                ->values();

            Log::info('OperacionesDashboard: cámaras con coordenadas para mapa', [
                'total_con_coordenadas' => $resultado->count(),
            ]);

            return $resultado;

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

        /**
         * Regla general:
         * - En layout CLIENTE: siempre se filtra por el cliente asociado al usuario.
         * - En layout PRINCIPAL: SOLO se filtra si el request trae explícitamente cliente_id
         *   (por ejemplo, desde el selector visual de clientes).
         */

        if ($this->isClientLayout($request)) {
            // Usuario cliente en layout cliente: obtener siempre su cliente_id
            $userCliente = $user
                ? UserCliente::where('user_id', $user->id)->first()
                : null;

            if (!$userCliente) {
                return null;
            }

            return $userCliente->cliente_id;
        }

        // Layout principal (admin/operador u otros casos):
        // usar SOLO el filtro explícito enviado en el request (selector visual).
        if ($request) {
            return $request->get('cliente_id');
        }

        return null;
    }
    
    /**
     * Verificar si el request actual corresponde al layout cliente.
     *
     * Importante:
     * - NO usamos solo el hecho de que el usuario tenga un UserCliente,
     *   para evitar filtrar automáticamente en el layout principal.
     * - Intentamos inferir el contexto por ruta o referer.
     */
    private function isClientLayout(Request $request = null)
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Si el usuario no tiene relación UserCliente, nunca es layout cliente
        $tieneRelacionCliente = UserCliente::where('user_id', $user->id)->exists();
        if (!$tieneRelacionCliente) {
            return false;
        }

        // Si tenemos información del request, intentamos detectar el contexto "client"
        if ($request) {
            // 1) Por nombre de ruta (ej: client.operaciones.dashboard)
            $route = $request->route();
            $routeName = $route ? $route->getName() : null;
            if ($routeName && str_starts_with($routeName, 'client.')) {
                return true;
            }

            // 2) Por path directo (ej: client/operaciones/dashboard)
            $path = $request->path();
            if (str_starts_with($path, 'client/')) {
                return true;
            }

            // 3) Por Referer (para las rutas API llamadas desde el layout cliente)
            $referer = $request->headers->get('referer');
            if ($referer) {
                $refererPath = parse_url($referer, PHP_URL_PATH) ?? '';
                if (str_contains($refererPath, '/client/')) {
                    return true;
                }
            }
        }

        // En cualquier otro caso, asumimos que NO estamos en el layout cliente
        return false;
    }
}

