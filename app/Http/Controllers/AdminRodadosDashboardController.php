<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rodado;
use App\Models\TurnoRodado;
use App\Models\PagoServiciosRodado;
use App\Models\Cobranza;
use App\Models\AlertaAdmin;
use App\Models\Cliente;
use App\Models\Proveedor;
use Carbon\Carbon;

class AdminRodadosDashboardController extends Controller
{
    public function index(Request $request)
    {
        // KPIs
        $totalVehiculos = Rodado::count();
        $turnosPendientes = TurnoRodado::where('estado', TurnoRodado::ESTADO_PENDIENTE)->count();
        $pagosVencidos = PagoServiciosRodado::where('estado', PagoServiciosRodado::ESTADO_VENCIDO)->count();
        $pagosPendientes = PagoServiciosRodado::where('estado', PagoServiciosRodado::ESTADO_PENDIENTE)->count();
        $cobrosPendientes = Cobranza::where('estado', Cobranza::ESTADO_PENDIENTE)->count();

        $totalPagadoMes = PagoServiciosRodado::where('estado', PagoServiciosRodado::ESTADO_PAGADO)
            ->whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->sum('monto');

        $totalCobradoMes = Cobranza::where('estado', Cobranza::ESTADO_COBRADO)
            ->whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->sum('monto_total');

        // Alertas activas
        $alertas = AlertaAdmin::activas()->with(['rodado', 'cliente'])->latest()->take(10)->get();

        // Proximos turnos (7 dias)
        $proximosTurnos = TurnoRodado::with(['rodado', 'taller'])
            ->where('estado', TurnoRodado::ESTADO_PENDIENTE)
            ->where('fecha_hora', '>=', now())
            ->where('fecha_hora', '<=', now()->addDays(7))
            ->orderBy('fecha_hora')
            ->take(5)
            ->get();

        // Pagos proximos a vencer (7 dias)
        $pagosProximosVencer = PagoServiciosRodado::with(['rodado', 'proveedor'])
            ->where('estado', PagoServiciosRodado::ESTADO_PENDIENTE)
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '<=', now()->addDays(7))
            ->orderBy('fecha_vencimiento')
            ->take(5)
            ->get();

        // Filtros disponibles
        $clientes = Cliente::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();

        return view('rodados.dashboard', compact(
            'totalVehiculos',
            'turnosPendientes',
            'pagosVencidos',
            'pagosPendientes',
            'cobrosPendientes',
            'totalPagadoMes',
            'totalCobradoMes',
            'alertas',
            'proximosTurnos',
            'pagosProximosVencer',
            'clientes',
            'proveedores'
        ));
    }

    public function getPagosMensuales(Request $request)
    {
        $year = $request->get('year', now()->year);
        $data = [];

        for ($m = 1; $m <= 12; $m++) {
            $pagados = PagoServiciosRodado::where('estado', PagoServiciosRodado::ESTADO_PAGADO)
                ->whereMonth('fecha_pago', $m)
                ->whereYear('fecha_pago', $year)
                ->sum('monto');

            $pendientes = PagoServiciosRodado::where('estado', PagoServiciosRodado::ESTADO_PENDIENTE)
                ->whereMonth('fecha_pago', $m)
                ->whereYear('fecha_pago', $year)
                ->sum('monto');

            $data[] = [
                'mes' => Carbon::create($year, $m, 1)->format('M'),
                'pagados' => (float) $pagados,
                'pendientes' => (float) $pendientes,
            ];
        }

        return response()->json($data);
    }

    public function getTurnosPorEstado()
    {
        $data = [
            ['estado' => 'Pendientes', 'total' => TurnoRodado::where('estado', 'pendiente')->count()],
            ['estado' => 'Atendidos', 'total' => TurnoRodado::where('estado', 'atendido')->count()],
            ['estado' => 'Completados', 'total' => TurnoRodado::where('estado', 'completado')->count()],
            ['estado' => 'Cancelados', 'total' => TurnoRodado::where('estado', 'cancelado')->count()],
        ];

        return response()->json($data);
    }

    public function getCobrosVsPagos(Request $request)
    {
        $year = $request->get('year', now()->year);
        $data = [];

        for ($m = 1; $m <= 12; $m++) {
            $cobrado = Cobranza::where('estado', Cobranza::ESTADO_COBRADO)
                ->whereMonth('fecha_pago', $m)
                ->whereYear('fecha_pago', $year)
                ->sum('monto_total');

            $pagado = PagoServiciosRodado::where('estado', PagoServiciosRodado::ESTADO_PAGADO)
                ->whereMonth('fecha_pago', $m)
                ->whereYear('fecha_pago', $year)
                ->sum('monto');

            $data[] = [
                'mes' => Carbon::create($year, $m, 1)->format('M'),
                'cobrado' => (float) $cobrado,
                'pagado' => (float) $pagado,
            ];
        }

        return response()->json($data);
    }
}
