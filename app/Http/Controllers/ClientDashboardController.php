<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Evento;
use App\Models\EmpresaAsociada;
use App\Models\Categoria;
use App\Models\Patrulla;
use App\Models\PatrullaDocumental;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClientDashboardController extends Controller
{
    /**
     * Obtiene los IDs de los clientes asignados al usuario autenticado
     */
    private function getClienteIds()
    {
        $user = Auth::user();
        if (!$user) {
            return collect();
        }

        return $user->clientes()->pluck('clientes.id');
    }

    /**
     * Muestra el dashboard del cliente con estadísticas
     */
    public function index()
    {
        $clienteIds = $this->getClienteIds();

        if ($clienteIds->isEmpty()) {
            return view('client.dashboard', [
                'chartData' => [],
                'chartDataCategorias' => [],
                'totalEventos' => 0,
                'eventosSinEmpresa' => 0,
                'empresasAsociadas' => collect(),
                // Patrullas
                'totalPatrullas' => 0,
                'patrullasConGPS' => 0,
                'patrullasSinGPS' => 0,
                'chartDataPatrullasEstado' => [],
                'chartDataPatrullasGPS' => [],
                // Documentos de patrullas
                'totalDocumentos' => 0,
                'documentosVencidos' => 0,
                'documentosPorVencer7Dias' => 0,
                'documentosPorVencer30Dias' => 0,
                'documentosVigentes' => 0,
                'chartDataDocumentos' => [],
                'documentosAlerta' => collect()
            ]);
        }

        // Obtener empresas asociadas de los clientes del usuario
        $empresasAsociadas = EmpresaAsociada::whereHas('cliente', function($query) use ($clienteIds) {
            $query->whereIn('clientes.id', $clienteIds);
        })->get();

        // Obtener estadísticas de eventos por empresa asociada
        $eventosStats = Evento::whereIn('cliente_id', $clienteIds)
            ->where('es_anulado', false)
            ->whereNotNull('empresa_asociada_id')
            ->select('empresa_asociada_id', DB::raw('COUNT(*) as total'))
            ->groupBy('empresa_asociada_id')
            ->get()
            ->keyBy('empresa_asociada_id');

        // Crear array con datos para el gráfico
        $chartData = [];
        foreach ($empresasAsociadas as $empresa) {
            $chartData[] = [
                'nombre' => $empresa->nombre,
                'total' => $eventosStats->has($empresa->id) ? $eventosStats[$empresa->id]->total : 0
            ];
        }

        // Agregar eventos sin cliente asignado
        $eventosSinEmpresa = Evento::whereIn('cliente_id', $clienteIds)
            ->where('es_anulado', false)
            ->whereNull('empresa_asociada_id')
            ->count();

        if ($eventosSinEmpresa > 0) {
            $chartData[] = [
                'nombre' => 'Sin Cliente',
                'total' => $eventosSinEmpresa
            ];
        }

        // Ordenar por total de eventos (mayor a menor)
        usort($chartData, function($a, $b) {
            return $b['total'] - $a['total'];
        });

        // Total de eventos
        $totalEventos = Evento::whereIn('cliente_id', $clienteIds)
            ->where('es_anulado', false)
            ->count();

        // Obtener estadísticas de eventos por categoría
        $categorias = Categoria::all();

        $eventosPorCategoria = Evento::whereIn('cliente_id', $clienteIds)
            ->where('es_anulado', false)
            ->whereNotNull('categoria_id')
            ->select('categoria_id', DB::raw('COUNT(*) as total'))
            ->groupBy('categoria_id')
            ->get()
            ->keyBy('categoria_id');

        $chartDataCategorias = [];
        foreach ($categorias as $categoria) {
            $total = $eventosPorCategoria->has($categoria->id) ? $eventosPorCategoria[$categoria->id]->total : 0;
            if ($total > 0) { // Solo incluir categorías con eventos
                $chartDataCategorias[] = [
                    'nombre' => $categoria->nombre,
                    'total' => $total
                ];
            }
        }

        // Ordenar por total de eventos (mayor a menor)
        usort($chartDataCategorias, function($a, $b) {
            return $b['total'] - $a['total'];
        });

        // ========== MAPA DE CALOR - CATEGORÍAS Y TIPOS PARA FILTROS ==========

        // Obtenemos las combinaciones únicas de categoría/tipo de los eventos válidos del cliente
        $categoriasTiposRaw = Evento::whereIn('cliente_id', $clienteIds)
            ->where('es_anulado', false)
            ->whereNotNull('categoria_id')
            ->whereNotNull('tipo')
            ->select('categoria_id', 'tipo')
            ->groupBy('categoria_id', 'tipo')
            ->get();

        // Armamos un array estructurado para el front
        $categoriasMap = Categoria::whereIn('id', $categoriasTiposRaw->pluck('categoria_id')->unique())
            ->get()
            ->keyBy('id');

        $categoriasTipos = $categoriasTiposRaw
            ->groupBy('categoria_id')
            ->map(function ($group, $categoriaId) use ($categoriasMap) {
                return [
                    'id' => (int) $categoriaId,
                    'nombre' => optional($categoriasMap->get($categoriaId))->nombre ?? 'Sin nombre',
                    'tipos' => $group->pluck('tipo')->values()
                ];
            })
            ->values();

        // ========== ESTADÍSTICAS DE PATRULLAS ==========
        
        // Total de patrullas del cliente
        $totalPatrullas = Patrulla::whereIn('cliente_id', $clienteIds)->count();
        
        // Patrullas por estado
        $patrullasPorEstado = Patrulla::whereIn('cliente_id', $clienteIds)
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->get();

        $chartDataPatrullasEstado = [];
        foreach ($patrullasPorEstado as $item) {
            $chartDataPatrullasEstado[] = [
                'nombre' => ucfirst($item->estado ?? 'Sin estado'),
                'total' => $item->total
            ];
        }

        // Ordenar por total
        usort($chartDataPatrullasEstado, function($a, $b) {
            return $b['total'] - $a['total'];
        });

        // Patrullas con GPS (tienen mobile_vehicle asociado)
        $patrullasConGPS = Patrulla::whereIn('cliente_id', $clienteIds)
            ->whereHas('mobileVehicle')
            ->count();

        $patrullasSinGPS = $totalPatrullas - $patrullasConGPS;

        // Datos para gráfico de GPS
        $chartDataPatrullasGPS = [];
        if ($patrullasConGPS > 0) {
            $chartDataPatrullasGPS[] = ['nombre' => 'Con GPS', 'total' => $patrullasConGPS];
        }
        if ($patrullasSinGPS > 0) {
            $chartDataPatrullasGPS[] = ['nombre' => 'Sin GPS', 'total' => $patrullasSinGPS];
        }

        // ========== ESTADÍSTICAS DE DOCUMENTOS DE PATRULLAS ==========
        
        // Obtener IDs de patrullas del cliente
        $patrullaIds = Patrulla::whereIn('cliente_id', $clienteIds)->pluck('id');
        
        $hoy = Carbon::today();
        $en30Dias = Carbon::today()->addDays(30);
        $en7Dias = Carbon::today()->addDays(7);
        
        // Total de documentos
        $totalDocumentos = PatrullaDocumental::whereIn('patrulla_id', $patrullaIds)->count();
        
        // Documentos vencidos
        $documentosVencidos = PatrullaDocumental::whereIn('patrulla_id', $patrullaIds)
            ->whereNotNull('fecha_vto')
            ->whereDate('fecha_vto', '<', $hoy)
            ->count();
        
        // Documentos por vencer en 7 días
        $documentosPorVencer7Dias = PatrullaDocumental::whereIn('patrulla_id', $patrullaIds)
            ->whereNotNull('fecha_vto')
            ->whereDate('fecha_vto', '>=', $hoy)
            ->whereDate('fecha_vto', '<=', $en7Dias)
            ->count();
        
        // Documentos por vencer en 30 días
        $documentosPorVencer30Dias = PatrullaDocumental::whereIn('patrulla_id', $patrullaIds)
            ->whereNotNull('fecha_vto')
            ->whereDate('fecha_vto', '>=', $hoy)
            ->whereDate('fecha_vto', '<=', $en30Dias)
            ->count();
        
        // Documentos vigentes (fecha_vto > 30 días o sin fecha)
        $documentosVigentes = $totalDocumentos - $documentosVencidos - $documentosPorVencer30Dias;
        
        // Datos para gráfico de estado de documentos
        $chartDataDocumentos = [];
        if ($documentosVencidos > 0) {
            $chartDataDocumentos[] = ['nombre' => 'Vencidos', 'total' => $documentosVencidos];
        }
        if ($documentosPorVencer7Dias > 0) {
            $chartDataDocumentos[] = ['nombre' => 'Vence en 7 días', 'total' => $documentosPorVencer7Dias];
        }
        if (($documentosPorVencer30Dias - $documentosPorVencer7Dias) > 0) {
            $chartDataDocumentos[] = ['nombre' => 'Vence en 30 días', 'total' => $documentosPorVencer30Dias - $documentosPorVencer7Dias];
        }
        if ($documentosVigentes > 0) {
            $chartDataDocumentos[] = ['nombre' => 'Vigentes', 'total' => $documentosVigentes];
        }
        
        // Lista de documentos próximos a vencer o vencidos (para tabla)
        $documentosAlerta = PatrullaDocumental::with('patrulla')
            ->whereIn('patrulla_id', $patrullaIds)
            ->whereNotNull('fecha_vto')
            ->whereDate('fecha_vto', '<=', $en30Dias)
            ->orderBy('fecha_vto', 'asc')
            ->get()
            ->map(function ($doc) use ($hoy) {
                $fechaVto = Carbon::parse($doc->fecha_vto);
                $diasRestantes = $hoy->diffInDays($fechaVto, false);
                
                return [
                    'id' => $doc->id,
                    'nombre' => $doc->nombre,
                    'patrulla' => $doc->patrulla->patente ?? 'N/A',
                    'fecha_vto' => $doc->fecha_vto->format('d/m/Y'),
                    'dias_restantes' => $diasRestantes,
                    'estado' => $diasRestantes < 0 ? 'vencido' : ($diasRestantes <= 7 ? 'critico' : 'alerta')
                ];
            });

        return view('client.dashboard', [
            'chartData' => $chartData,
            'chartDataCategorias' => $chartDataCategorias,
            'totalEventos' => $totalEventos,
            'eventosSinEmpresa' => $eventosSinEmpresa,
            'empresasAsociadas' => $empresasAsociadas,
            'categoriasTiposMapa' => $categoriasTipos,
            // Patrullas
            'totalPatrullas' => $totalPatrullas,
            'patrullasConGPS' => $patrullasConGPS,
            'patrullasSinGPS' => $patrullasSinGPS,
            'chartDataPatrullasEstado' => $chartDataPatrullasEstado,
            'chartDataPatrullasGPS' => $chartDataPatrullasGPS,
            // Documentos de patrullas
            'totalDocumentos' => $totalDocumentos,
            'documentosVencidos' => $documentosVencidos,
            'documentosPorVencer7Dias' => $documentosPorVencer7Dias,
            'documentosPorVencer30Dias' => $documentosPorVencer30Dias,
            'documentosVigentes' => $documentosVigentes,
            'chartDataDocumentos' => $chartDataDocumentos,
            'documentosAlerta' => $documentosAlerta
        ]);
    }

    /**
     * API endpoint para obtener datos del gráfico con filtros de fecha
     */
    public function getEventosPorEmpresa(Request $request)
    {
        $clienteIds = $this->getClienteIds();

        if ($clienteIds->isEmpty()) {
            return response()->json([]);
        }

        $query = Evento::whereIn('cliente_id', $clienteIds)
            ->where('es_anulado', false)
            ->whereNotNull('empresa_asociada_id');

        // Filtrar por rango de fechas si se proporciona
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }

        // Obtener empresas asociadas
        $empresasAsociadas = EmpresaAsociada::whereHas('cliente', function($q) use ($clienteIds) {
            $q->whereIn('clientes.id', $clienteIds);
        })->get();

        $eventosStats = $query
            ->select('empresa_asociada_id', DB::raw('COUNT(*) as total'))
            ->groupBy('empresa_asociada_id')
            ->get()
            ->keyBy('empresa_asociada_id');

        $chartData = [];
        foreach ($empresasAsociadas as $empresa) {
            $chartData[] = [
                'nombre' => $empresa->nombre,
                'total' => $eventosStats->has($empresa->id) ? $eventosStats[$empresa->id]->total : 0
            ];
        }

        // Agregar eventos sin cliente asignado
        $querySinCliente = Evento::whereIn('cliente_id', $clienteIds)
            ->where('es_anulado', false)
            ->whereNull('empresa_asociada_id');

        if ($request->filled('fecha_desde')) {
            $querySinCliente->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $querySinCliente->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }

        $eventosSinCliente = $querySinCliente->count();

        if ($eventosSinCliente > 0) {
            $chartData[] = [
                'nombre' => 'Sin Cliente',
                'total' => $eventosSinCliente
            ];
        }

        usort($chartData, function($a, $b) {
            return $b['total'] - $a['total'];
        });

        return response()->json($chartData);
    }

    /**
     * API endpoint para obtener datos del gráfico de categorías con filtros de fecha
     */
    public function getEventosPorCategoria(Request $request)
    {
        $clienteIds = $this->getClienteIds();

        if ($clienteIds->isEmpty()) {
            return response()->json([]);
        }

        $query = Evento::whereIn('cliente_id', $clienteIds)
            ->where('es_anulado', false)
            ->whereNotNull('categoria_id');

        // Filtrar por rango de fechas si se proporciona
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }

        $categorias = Categoria::all();

        $eventosPorCategoria = $query
            ->select('categoria_id', DB::raw('COUNT(*) as total'))
            ->groupBy('categoria_id')
            ->get()
            ->keyBy('categoria_id');

        $chartData = [];
        foreach ($categorias as $categoria) {
            $total = $eventosPorCategoria->has($categoria->id) ? $eventosPorCategoria[$categoria->id]->total : 0;
            if ($total > 0) {
                $chartData[] = [
                    'nombre' => $categoria->nombre,
                    'total' => $total
                ];
            }
        }

        usort($chartData, function($a, $b) {
            return $b['total'] - $a['total'];
        });

        return response()->json($chartData);
    }

    public function getEventosMapaCalor(Request $request)
    {
        try {
            \Log::info('API Mapa Calor solicitada');
            
            $clienteIds = $this->getClienteIds();
            
            if ($clienteIds->isEmpty()) {
                return response()->json([]);
            }

            $query = Evento::whereIn('cliente_id', $clienteIds)
                ->where('es_anulado', false)
                ->whereNotNull('latitud')  
                ->whereNotNull('longitud') 
                ->where('latitud', '!=', 0)  
                ->where('longitud', '!=', 0); 

            // Filtrar por rango de fechas si se proporciona
            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
            }
            if ($request->filled('fecha_hasta')) {
                $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
            }

            // Filtro opcional por empresa asociada (cliente)
            $empresas = $request->input('empresa_asociada_id');
            if (!empty($empresas)) {
                // Puede venir como array o CSV: "1,3,5"
                $empresaIds = is_array($empresas)
                    ? $empresas
                    : array_filter(explode(',', $empresas));

                if (!empty($empresaIds)) {
                    $query->whereIn('empresa_asociada_id', $empresaIds);
                }
            }

            // Filtro opcional por categorías (puede venir como array o CSV)
            $categorias = $request->input('categorias');
            if (!empty($categorias)) {
                $categoriaIds = is_array($categorias)
                    ? $categorias
                    : array_filter(explode(',', $categorias));

                if (!empty($categoriaIds)) {
                    $query->whereIn('categoria_id', $categoriaIds);
                }
            }

            // Filtro opcional por tipos de evento (puede venir como array o CSV)
            $tipos = $request->input('tipos');
            if (!empty($tipos)) {
                $tiposArray = is_array($tipos)
                    ? $tipos
                    : array_filter(explode(',', $tipos));

                if (!empty($tiposArray)) {
                    $query->whereIn('tipo', $tiposArray);
                }
            }

            // Agrupar por ubicación para obtener el recuento
            $eventosPorUbicacion = $query
                ->select(
                    DB::raw('ROUND(latitud, 4) as lat'),  
                    DB::raw('ROUND(longitud, 4) as lng'), 
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy(DB::raw('ROUND(latitud, 4)'), DB::raw('ROUND(longitud, 4)')) // <-- Cambiado
                ->orderBy('count', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'lat' => (float) $item->lat,
                        'lng' => (float) $item->lng,
                        'count' => $item->count
                    ];
                });

            \Log::info('Resultados encontrados:', ['total' => $eventosPorUbicacion->count()]);

            return response()->json($eventosPorUbicacion);

        } catch (\Exception $e) {
            \Log::error('Error en API Mapa Calor:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'error' => true,
                'message' => 'Error interno del servidor',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Obtener eventos individuales de una ubicación específica para el popup del mapa
     */
    public function getEventosPorUbicacion(Request $request)
    {
        try {
            $clienteIds = $this->getClienteIds();
            
            if ($clienteIds->isEmpty()) {
                return response()->json([]);
            }

            $lat = $request->input('lat');
            $lng = $request->input('lng');

            if (!$lat || !$lng) {
                return response()->json(['error' => 'Coordenadas requeridas'], 400);
            }

            $query = Evento::whereIn('cliente_id', $clienteIds)
                ->where('es_anulado', false)
                ->whereRaw('ROUND(latitud, 4) = ?', [round($lat, 4)])
                ->whereRaw('ROUND(longitud, 4) = ?', [round($lng, 4)])
                ->with(['categoria', 'empresaAsociada'])
                ->orderBy('fecha_hora', 'desc');

            // Aplicar los mismos filtros que el mapa de calor
            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
            }
            if ($request->filled('fecha_hasta')) {
                $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
            }

            $empresas = $request->input('empresa_asociada_id');
            if (!empty($empresas)) {
                $empresaIds = is_array($empresas) ? $empresas : array_filter(explode(',', $empresas));
                if (!empty($empresaIds)) {
                    $query->whereIn('empresa_asociada_id', $empresaIds);
                }
            }

            $categorias = $request->input('categorias');
            if (!empty($categorias)) {
                $categoriaIds = is_array($categorias) ? $categorias : array_filter(explode(',', $categorias));
                if (!empty($categoriaIds)) {
                    $query->whereIn('categoria_id', $categoriaIds);
                }
            }

            $tipos = $request->input('tipos');
            if (!empty($tipos)) {
                $tiposArray = is_array($tipos) ? $tipos : array_filter(explode(',', $tipos));
                if (!empty($tiposArray)) {
                    $query->whereIn('tipo', $tiposArray);
                }
            }

            $eventos = $query->get()->map(function ($evento) {
                return [
                    'id' => $evento->id,
                    'fecha_hora' => $evento->fecha_hora->format('Y-m-d H:i:s'),
                    'fecha_hora_formatted' => $evento->fecha_hora->format('d/m/Y H:i'),
                    'categoria' => $evento->categoria ? $evento->categoria->nombre : 'Sin categoría',
                    'tipo' => $evento->tipo ?? 'Sin tipo',
                    'cliente' => $evento->empresaAsociada ? $evento->empresaAsociada->nombre : 'Sin cliente',
                    'descripcion' => $evento->descripcion ?? '',
                ];
            });

            return response()->json([
                'lat' => (float) $lat,
                'lng' => (float) $lng,
                'count' => $eventos->count(),
                'eventos' => $eventos
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en getEventosPorUbicacion:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'error' => true,
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }
}

