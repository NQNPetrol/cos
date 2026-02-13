<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rodado;
use App\Models\TurnoRodado;
use App\Models\PagoServiciosRodado;
use App\Models\Cobranza;
use App\Models\CambioEquipoRodado;
use App\Models\RegistroKilometraje;
use App\Models\AlertaAdmin;
use App\Models\Cliente;
use App\Models\Proveedor;
use Carbon\Carbon;

class AdminRodadosDashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('rodados.dashboard');
    }

    /**
     * KPIs mensuales: services totales, turnos mecánicos, cambios de equipo, total rodados
     */
    public function getKpis(Request $request)
    {
        $mes = (int) $request->get('mes', now()->month);
        $anio = (int) $request->get('anio', now()->year);

        $totalServices = TurnoRodado::where('tipo', TurnoRodado::TIPO_TURNO_SERVICE)
            ->whereMonth('fecha_hora', $mes)
            ->whereYear('fecha_hora', $anio)
            ->count();

        $totalTurnosMecanicos = TurnoRodado::where('tipo', TurnoRodado::TIPO_TURNO_MECANICO)
            ->whereMonth('fecha_hora', $mes)
            ->whereYear('fecha_hora', $anio)
            ->count();

        $totalCambiosEquipo = CambioEquipoRodado::whereMonth('fecha_hora_estimada', $mes)
            ->whereYear('fecha_hora_estimada', $anio)
            ->count();

        $totalRodados = Rodado::count();

        return response()->json([
            'totalServices' => $totalServices,
            'totalTurnosMecanicos' => $totalTurnosMecanicos,
            'totalCambiosEquipo' => $totalCambiosEquipo,
            'totalRodados' => $totalRodados,
        ]);
    }

    /**
     * Ingresos (cobranzas cobradas) vs Egresos (pagos pagados) por mes del año
     */
    public function getIngresosEgresos(Request $request)
    {
        $anio = (int) $request->get('anio', now()->year);
        $meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $ingresos = [];
        $egresos = [];

        for ($m = 1; $m <= 12; $m++) {
            $ingresos[] = (float) Cobranza::where('estado', Cobranza::ESTADO_COBRADO)
                ->whereMonth('fecha_pago', $m)
                ->whereYear('fecha_pago', $anio)
                ->sum('monto_total');

            $egresos[] = (float) PagoServiciosRodado::where('estado', PagoServiciosRodado::ESTADO_PAGADO)
                ->whereMonth('fecha_pago', $m)
                ->whereYear('fecha_pago', $anio)
                ->sum('monto');
        }

        return response()->json([
            'meses' => $meses,
            'ingresos' => $ingresos,
            'egresos' => $egresos,
        ]);
    }

    /**
     * Evolución de flota (recuento rodados) vs Ingresos en el año
     */
    public function getFlotaIngresos(Request $request)
    {
        $anio = (int) $request->get('anio', now()->year);
        $meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $rodados = [];
        $ingresos = [];

        for ($m = 1; $m <= 12; $m++) {
            $finMes = Carbon::create($anio, $m)->endOfMonth();
            $rodados[] = Rodado::where('created_at', '<=', $finMes)->count();

            $ingresos[] = (float) Cobranza::where('estado', Cobranza::ESTADO_COBRADO)
                ->whereMonth('fecha_pago', $m)
                ->whereYear('fecha_pago', $anio)
                ->sum('monto_total');
        }

        return response()->json([
            'meses' => $meses,
            'rodados' => $rodados,
            'ingresos' => $ingresos,
        ]);
    }

    /**
     * Turnos mensuales por vehículo (ordenado desc)
     */
    public function getTurnosPorVehiculo(Request $request)
    {
        $mes = (int) $request->get('mes', now()->month);
        $anio = (int) $request->get('anio', now()->year);

        $turnos = TurnoRodado::selectRaw('rodado_id, COUNT(*) as total')
            ->whereMonth('fecha_hora', $mes)
            ->whereYear('fecha_hora', $anio)
            ->groupBy('rodado_id')
            ->orderByDesc('total')
            ->with('rodado:id,patente')
            ->get()
            ->map(fn($t) => [
                'rodado_id' => $t->rodado_id,
                'patente' => $t->rodado->patente ?? 'N/A',
                'total' => $t->total,
            ]);

        return response()->json($turnos);
    }

    /**
     * Top 5 vehículos por kilometraje (último registro)
     */
    public function getTopKm(Request $request)
    {
        $topKm = RegistroKilometraje::selectRaw('rodado_id, MAX(kilometraje) as kilometraje')
            ->groupBy('rodado_id')
            ->orderByDesc('kilometraje')
            ->take(5)
            ->get()
            ->map(function ($reg) {
                $rodado = Rodado::find($reg->rodado_id);
                return [
                    'rodado_id' => $reg->rodado_id,
                    'patente' => $rodado->patente ?? 'N/A',
                    'kilometraje' => (int) $reg->kilometraje,
                ];
            });

        return response()->json($topKm);
    }

    /**
     * Próximos turnos y pagos (7 días)
     */
    public function getUpcoming(Request $request)
    {
        $turnos = TurnoRodado::with(['rodado', 'taller'])
            ->where('estado', TurnoRodado::ESTADO_PENDIENTE)
            ->where('fecha_hora', '>=', now())
            ->where('fecha_hora', '<=', now()->addDays(7))
            ->orderBy('fecha_hora')
            ->take(5)
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'patente' => $t->rodado->patente ?? 'Sin patente',
                'taller' => $t->taller->nombre ?? 'N/A',
                'tipo' => ucfirst($t->tipo),
                'fecha' => $t->fecha_hora->format('d/m H:i'),
            ]);

        $pagos = PagoServiciosRodado::with(['rodado'])
            ->where('estado', PagoServiciosRodado::ESTADO_PENDIENTE)
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '<=', now()->addDays(7))
            ->orderBy('fecha_vencimiento')
            ->take(5)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'patente' => $p->rodado?->patente ?? 'Sin patente',
                'monto' => number_format($p->monto, 2, ',', '.'),
                'moneda' => $p->moneda ?? 'ARS',
                'fecha' => $p->fecha_vencimiento->format('d/m/Y'),
            ]);

        return response()->json([
            'turnos' => $turnos,
            'pagos' => $pagos,
        ]);
    }

    // Legacy endpoints (kept for compatibility)
    public function getPagosMensuales(Request $request)
    {
        $year = $request->get('year', now()->year);
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $pagados = PagoServiciosRodado::where('estado', PagoServiciosRodado::ESTADO_PAGADO)
                ->whereMonth('fecha_pago', $m)->whereYear('fecha_pago', $year)->sum('monto');
            $pendientes = PagoServiciosRodado::where('estado', PagoServiciosRodado::ESTADO_PENDIENTE)
                ->whereMonth('fecha_pago', $m)->whereYear('fecha_pago', $year)->sum('monto');
            $data[] = ['mes' => Carbon::create($year, $m, 1)->format('M'), 'pagados' => (float) $pagados, 'pendientes' => (float) $pendientes];
        }
        return response()->json($data);
    }

    public function getTurnosPorEstado()
    {
        return response()->json([
            ['estado' => 'Pendientes', 'total' => TurnoRodado::where('estado', 'pendiente')->count()],
            ['estado' => 'Atendidos', 'total' => TurnoRodado::where('estado', 'atendido')->count()],
            ['estado' => 'Completados', 'total' => TurnoRodado::where('estado', 'completado')->count()],
            ['estado' => 'Cancelados', 'total' => TurnoRodado::where('estado', 'cancelado')->count()],
        ]);
    }

    public function getCobrosVsPagos(Request $request)
    {
        $year = $request->get('year', now()->year);
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $cobrado = Cobranza::where('estado', Cobranza::ESTADO_COBRADO)
                ->whereMonth('fecha_pago', $m)->whereYear('fecha_pago', $year)->sum('monto_total');
            $pagado = PagoServiciosRodado::where('estado', PagoServiciosRodado::ESTADO_PAGADO)
                ->whereMonth('fecha_pago', $m)->whereYear('fecha_pago', $year)->sum('monto');
            $data[] = ['mes' => Carbon::create($year, $m, 1)->format('M'), 'cobrado' => (float) $cobrado, 'pagado' => (float) $pagado];
        }
        return response()->json($data);
    }
}
