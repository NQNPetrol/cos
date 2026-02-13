<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Muestra el dashboard del administrador con estadísticas por cliente
     */
    public function index()
    {
        // Obtener todos los clientes
        $clientes = Cliente::orderBy('nombre')->get();

        // Obtener estadísticas de eventos por cliente
        $eventosPorCliente = Evento::where('es_anulado', false)
            ->whereNotNull('cliente_id')
            ->select('cliente_id', DB::raw('COUNT(*) as total'))
            ->groupBy('cliente_id')
            ->get()
            ->keyBy('cliente_id');

        // Crear array con datos para el gráfico de clientes
        $chartDataClientes = [];
        foreach ($clientes as $cliente) {
            $total = $eventosPorCliente->has($cliente->id) ? $eventosPorCliente[$cliente->id]->total : 0;
            if ($total > 0) {
                $chartDataClientes[] = [
                    'nombre' => $cliente->nombre,
                    'total' => $total,
                ];
            }
        }

        // Ordenar por total de eventos (mayor a menor)
        usort($chartDataClientes, function ($a, $b) {
            return $b['total'] - $a['total'];
        });

        // Obtener estadísticas de eventos por categoría
        $categorias = Categoria::all();

        $eventosPorCategoria = Evento::where('es_anulado', false)
            ->whereNotNull('categoria_id')
            ->select('categoria_id', DB::raw('COUNT(*) as total'))
            ->groupBy('categoria_id')
            ->get()
            ->keyBy('categoria_id');

        $chartDataCategorias = [];
        foreach ($categorias as $categoria) {
            $total = $eventosPorCategoria->has($categoria->id) ? $eventosPorCategoria[$categoria->id]->total : 0;
            if ($total > 0) {
                $chartDataCategorias[] = [
                    'nombre' => $categoria->nombre,
                    'total' => $total,
                ];
            }
        }

        usort($chartDataCategorias, function ($a, $b) {
            return $b['total'] - $a['total'];
        });

        // Totales
        $totalEventos = Evento::where('es_anulado', false)->count();
        $totalClientes = $clientes->count();
        $eventosSinCliente = Evento::where('es_anulado', false)
            ->whereNull('cliente_id')
            ->count();

        return view('dashboard', [
            'chartDataClientes' => $chartDataClientes,
            'chartDataCategorias' => $chartDataCategorias,
            'totalEventos' => $totalEventos,
            'totalClientes' => $totalClientes,
            'eventosSinCliente' => $eventosSinCliente,
        ]);
    }

    /**
     * API endpoint para obtener eventos por cliente con filtros
     */
    public function getEventosPorCliente(Request $request)
    {
        $query = Evento::where('es_anulado', false)
            ->whereNotNull('cliente_id');

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }

        $clientes = Cliente::orderBy('nombre')->get();

        $eventosPorCliente = $query
            ->select('cliente_id', DB::raw('COUNT(*) as total'))
            ->groupBy('cliente_id')
            ->get()
            ->keyBy('cliente_id');

        $chartData = [];
        foreach ($clientes as $cliente) {
            $total = $eventosPorCliente->has($cliente->id) ? $eventosPorCliente[$cliente->id]->total : 0;
            if ($total > 0) {
                $chartData[] = [
                    'nombre' => $cliente->nombre,
                    'total' => $total,
                ];
            }
        }

        // Agregar eventos sin cliente
        $querySinCliente = Evento::where('es_anulado', false)
            ->whereNull('cliente_id');

        if ($request->filled('fecha_desde')) {
            $querySinCliente->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $querySinCliente->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }

        $sinCliente = $querySinCliente->count();
        if ($sinCliente > 0) {
            $chartData[] = [
                'nombre' => 'Sin Cliente',
                'total' => $sinCliente,
            ];
        }

        usort($chartData, function ($a, $b) {
            return $b['total'] - $a['total'];
        });

        return response()->json($chartData);
    }

    /**
     * API endpoint para obtener eventos por categoría con filtros
     */
    public function getEventosPorCategoria(Request $request)
    {
        $query = Evento::where('es_anulado', false)
            ->whereNotNull('categoria_id');

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
                    'total' => $total,
                ];
            }
        }

        usort($chartData, function ($a, $b) {
            return $b['total'] - $a['total'];
        });

        return response()->json($chartData);
    }
}
