<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Evento;
use App\Models\EmpresaAsociada;
use App\Models\Categoria;
use App\Models\Patrulla;
use Illuminate\Support\Facades\DB;

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
                'chartDataPatrullasGPS' => []
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

        return view('client.dashboard', [
            'chartData' => $chartData,
            'chartDataCategorias' => $chartDataCategorias,
            'totalEventos' => $totalEventos,
            'eventosSinEmpresa' => $eventosSinEmpresa,
            'empresasAsociadas' => $empresasAsociadas,
            // Patrullas
            'totalPatrullas' => $totalPatrullas,
            'patrullasConGPS' => $patrullasConGPS,
            'patrullasSinGPS' => $patrullasSinGPS,
            'chartDataPatrullasEstado' => $chartDataPatrullasEstado,
            'chartDataPatrullasGPS' => $chartDataPatrullasGPS
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
}

