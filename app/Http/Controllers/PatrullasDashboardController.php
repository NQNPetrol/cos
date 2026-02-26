<?php

namespace App\Http\Controllers;

use App\Models\EmpresaAsociada;
use App\Models\Patrulla;
use App\Models\PatrullaDocumental;
use App\Models\Recorrido;
use App\Models\RecorridoTimetable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatrullasDashboardController extends Controller
{
    private function getClienteIds()
    {
        $user = Auth::user();
        if (! $user) {
            return collect();
        }

        return $user->clientes()->pluck('clientes.id');
    }

    /**
     * Dashboard principal de patrullas (secciones movidas + nuevos gráficos)
     */
    public function index()
    {
        $clienteIds = $this->getClienteIds();

        if ($clienteIds->isEmpty()) {
            return view('client.patrullas-dashboard', [
                'totalPatrullas' => 0,
                'patrullasConGPS' => 0,
                'patrullasSinGPS' => 0,
                'chartDataPatrullasEstado' => [],
                'chartDataPatrullasGPS' => [],
                'totalDocumentos' => 0,
                'documentosVencidos' => 0,
                'documentosPorVencer7Dias' => 0,
                'documentosPorVencer30Dias' => 0,
                'documentosVigentes' => 0,
                'chartDataDocumentos' => [],
                'documentosAlerta' => collect(),
                'empresasAsociadas' => collect(),
                'patrullas' => collect(),
            ]);
        }

        // ========== ESTADÍSTICAS DE PATRULLAS ==========
        $totalPatrullas = Patrulla::whereIn('cliente_id', $clienteIds)->count();

        $patrullasPorEstado = Patrulla::whereIn('cliente_id', $clienteIds)
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->get();

        $chartDataPatrullasEstado = [];
        foreach ($patrullasPorEstado as $item) {
            $chartDataPatrullasEstado[] = [
                'nombre' => ucfirst($item->estado ?? 'Sin estado'),
                'total' => $item->total,
            ];
        }
        usort($chartDataPatrullasEstado, fn ($a, $b) => $b['total'] - $a['total']);

        $patrullasConGPS = Patrulla::whereIn('cliente_id', $clienteIds)
            ->whereHas('mobileVehicle')
            ->count();
        $patrullasSinGPS = $totalPatrullas - $patrullasConGPS;

        $chartDataPatrullasGPS = [];
        if ($patrullasConGPS > 0) {
            $chartDataPatrullasGPS[] = ['nombre' => 'Con GPS', 'total' => $patrullasConGPS];
        }
        if ($patrullasSinGPS > 0) {
            $chartDataPatrullasGPS[] = ['nombre' => 'Sin GPS', 'total' => $patrullasSinGPS];
        }

        // ========== ESTADÍSTICAS DE DOCUMENTOS ==========
        $patrullaIds = Patrulla::whereIn('cliente_id', $clienteIds)->pluck('id');
        $hoy = Carbon::today();
        $en30Dias = Carbon::today()->addDays(30);
        $en7Dias = Carbon::today()->addDays(7);

        $totalDocumentos = PatrullaDocumental::whereIn('patrulla_id', $patrullaIds)->count();

        $documentosVencidos = PatrullaDocumental::whereIn('patrulla_id', $patrullaIds)
            ->whereNotNull('fecha_vto')
            ->whereDate('fecha_vto', '<', $hoy)
            ->count();

        $documentosPorVencer7Dias = PatrullaDocumental::whereIn('patrulla_id', $patrullaIds)
            ->whereNotNull('fecha_vto')
            ->whereDate('fecha_vto', '>=', $hoy)
            ->whereDate('fecha_vto', '<=', $en7Dias)
            ->count();

        $documentosPorVencer30Dias = PatrullaDocumental::whereIn('patrulla_id', $patrullaIds)
            ->whereNotNull('fecha_vto')
            ->whereDate('fecha_vto', '>=', $hoy)
            ->whereDate('fecha_vto', '<=', $en30Dias)
            ->count();

        $documentosVigentes = $totalDocumentos - $documentosVencidos - $documentosPorVencer30Dias;

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
                    'estado' => $diasRestantes < 0 ? 'vencido' : ($diasRestantes <= 7 ? 'critico' : 'alerta'),
                ];
            });

        // ========== DATOS PARA FILTROS ==========
        $empresasAsociadas = EmpresaAsociada::whereHas('cliente', function ($q) use ($clienteIds) {
            $q->whereIn('clientes.id', $clienteIds);
        })
            ->orderBy('nombre')
            ->get();

        $patrullas = Patrulla::whereIn('cliente_id', $clienteIds)
            ->select('id', 'patente')
            ->orderBy('patente')
            ->get();

        return view('client.patrullas-dashboard', compact(
            'totalPatrullas', 'patrullasConGPS', 'patrullasSinGPS',
            'chartDataPatrullasEstado', 'chartDataPatrullasGPS',
            'totalDocumentos', 'documentosVencidos', 'documentosPorVencer7Dias',
            'documentosPorVencer30Dias', 'documentosVigentes',
            'chartDataDocumentos', 'documentosAlerta',
            'empresasAsociadas', 'patrullas'
        ));
    }

    /**
     * API: Recorridos con waypoints para una empresa asociada, con frecuencia de uso.
     */
    public function getRecorridosMapData(Request $request)
    {
        $clienteIds = $this->getClienteIds();
        $empresaId = $request->input('empresa_asociada_id');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');
        $patrullaId = $request->input('patrulla_id');

        if (! $empresaId) {
            return response()->json(['recorridos' => [], 'legend' => []]);
        }

        // Get all recorridos for this empresa
        $allRecorridos = Recorrido::where('empresa_asociada_id', $empresaId)
            ->whereIn('cliente_id', $clienteIds)
            ->get();

        // Filter to only those with valid waypoints (points array)
        $recorridos = $allRecorridos->filter(function ($rec) {
            $wp = $rec->waypoints;
            if (! is_array($wp)) {
                return false;
            }
            // Handle nested structure: {points: [...], metadata: {...}}
            if (isset($wp['points'])) {
                return is_array($wp['points']) && count($wp['points']) > 0;
            }

            // Handle flat array of coordinates
            return count($wp) > 0;
        });

        // Build date range for frequency query
        $query = RecorridoTimetable::query();
        if ($fechaDesde) {
            $query->where('fecha_hora_inicio', '>=', $fechaDesde);
        } else {
            $query->where('fecha_hora_inicio', '>=', Carbon::now()->startOfMonth());
        }
        if ($fechaHasta) {
            $query->where('fecha_hora_inicio', '<=', $fechaHasta.' 23:59:59');
        } else {
            $query->where('fecha_hora_inicio', '<=', Carbon::now()->endOfMonth());
        }

        // Filter by patrulla if provided
        if ($patrullaId) {
            $query->where('patrulla_id', $patrullaId);
        }

        // Count frequency per recorrido
        $frecuencias = (clone $query)
            ->whereIn('recorrido_id', $recorridos->pluck('id'))
            ->select('recorrido_id', DB::raw('COUNT(*) as freq'))
            ->groupBy('recorrido_id')
            ->pluck('freq', 'recorrido_id')
            ->toArray();

        // Count frequency per recorrido + patrulla (breakdown)
        $frecPorPatrulla = (clone $query)
            ->whereIn('recorrido_id', $recorridos->pluck('id'))
            ->join('patrullas', 'patrullas.id', '=', 'recorridos_timetable.patrulla_id')
            ->select('recorrido_id', 'patrullas.patente', DB::raw('COUNT(*) as freq'))
            ->groupBy('recorrido_id', 'patrullas.patente')
            ->get()
            ->groupBy('recorrido_id')
            ->map(fn ($items) => $items->pluck('freq', 'patente')->toArray())
            ->toArray();

        $maxFreq = count($frecuencias) > 0 ? max($frecuencias) : 0;

        $result = $recorridos->map(function ($rec) use ($frecuencias, $frecPorPatrulla, $maxFreq) {
            $freq = $frecuencias[$rec->id] ?? 0;
            $waypoints = is_string($rec->waypoints) ? json_decode($rec->waypoints, true) : $rec->waypoints;

            // Normalize waypoints: extract 'points' array if nested structure
            if (is_array($waypoints) && isset($waypoints['points'])) {
                $waypoints = $waypoints['points'];
            }

            return [
                'id' => $rec->id,
                'nombre' => $rec->nombre,
                'descripcion' => $rec->descripcion,
                'objetivos' => $rec->objetivos,
                'longitud_km' => $rec->longitud_mts ? round($rec->longitud_mts / 1000, 2) : null,
                'velocidadmax' => $rec->velocidadmax_permitida,
                'duracion_promedio' => $rec->duracion_promedio,
                'frecuencia' => $freq,
                'frecuencia_patrulla' => $frecPorPatrulla[$rec->id] ?? [],
                'waypoints' => array_values($waypoints ?? []),
                'intensidad' => $maxFreq > 0 ? $freq / $maxFreq : 0,
            ];
        })->values();

        // Legend thresholds
        $legend = [];
        if ($maxFreq > 0) {
            $step = max(1, ceil($maxFreq / 5));
            for ($i = 0; $i < 5; $i++) {
                $from = $i * $step;
                $to = min(($i + 1) * $step - 1, $maxFreq);
                if ($from > $maxFreq) {
                    break;
                }
                $legend[] = ['from' => $from, 'to' => $to];
            }
        }

        return response()->json([
            'recorridos' => $result,
            'maxFreq' => $maxFreq,
            'legend' => $legend,
        ]);
    }

    /**
     * API: Tendency data - day of week / hour of day.
     */
    public function getRecorridosTendencia(Request $request)
    {
        $clienteIds = $this->getClienteIds();
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');

        $query = RecorridoTimetable::query()
            ->whereHas('recorrido', fn ($q) => $q->whereIn('cliente_id', $clienteIds));

        if ($fechaDesde) {
            $query->where('fecha_hora_inicio', '>=', $fechaDesde);
        } else {
            $query->where('fecha_hora_inicio', '>=', Carbon::now()->startOfMonth());
        }
        if ($fechaHasta) {
            $query->where('fecha_hora_inicio', '<=', $fechaHasta.' 23:59:59');
        } else {
            $query->where('fecha_hora_inicio', '<=', Carbon::now()->endOfMonth());
        }

        $registros = $query->select('fecha_hora_inicio')->get();

        // Matrix: 7 days x 24 hours
        $matrix = array_fill(0, 7, array_fill(0, 24, 0));
        foreach ($registros as $reg) {
            if ($reg->fecha_hora_inicio) {
                $dow = $reg->fecha_hora_inicio->dayOfWeekIso - 1; // 0=Lunes, 6=Domingo
                $hour = $reg->fecha_hora_inicio->hour;
                $matrix[$dow][$hour]++;
            }
        }

        return response()->json([
            'matrix' => $matrix,
            'days' => ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
        ]);
    }

    /**
     * API: Top 5 most and least performed recorridos across ALL empresas this month.
     */
    public function getTopRecorridos(Request $request)
    {
        $clienteIds = $this->getClienteIds();
        $fechaDesde = $request->input('fecha_desde', Carbon::now()->startOfMonth()->toDateString());
        $fechaHasta = $request->input('fecha_hasta', Carbon::now()->endOfMonth()->toDateString());

        // Get frequency counts for the period
        $frecuencias = RecorridoTimetable::whereHas('recorrido', fn ($q) => $q->whereIn('cliente_id', $clienteIds))
            ->where('fecha_hora_inicio', '>=', $fechaDesde)
            ->where('fecha_hora_inicio', '<=', $fechaHasta.' 23:59:59')
            ->select('recorrido_id', DB::raw('COUNT(*) as freq'))
            ->groupBy('recorrido_id')
            ->get()
            ->keyBy('recorrido_id');

        // Get ALL recorridos for these clients (including those with 0 frequency)
        $allRecorridos = Recorrido::whereIn('cliente_id', $clienteIds)
            ->with('empresaAsociada')
            ->get();

        $data = $allRecorridos->map(function ($rec) use ($frecuencias) {
            return [
                'nombre' => $rec->nombre,
                'empresa' => $rec->empresaAsociada ? $rec->empresaAsociada->nombre : 'N/A',
                'freq' => $frecuencias->has($rec->id) ? $frecuencias->get($rec->id)->freq : 0,
            ];
        })->sortByDesc('freq')->values();

        // Split into most performed (green) and least performed (red)
        $withFreq = $data->filter(fn ($item) => $item['freq'] > 0)->sortByDesc('freq')->values();
        $withoutFreq = $data->filter(fn ($item) => $item['freq'] === 0)->sortBy('nombre')->values();

        if ($withFreq->count() > 5) {
            // Plenty of data: top 5 vs bottom 5 of those with frequency
            $top5 = $withFreq->take(5)->values();
            $bottom5 = $withFreq->sortBy('freq')->take(5)->values();
            // Remove overlap
            $topKeys = $top5->pluck('nombre')->toArray();
            $bottom5 = $bottom5->filter(fn ($item) => ! in_array($item['nombre'], $topKeys))->values();
            // Add any with zero freq
            $remaining = 5 - $bottom5->count();
            if ($remaining > 0 && $withoutFreq->isNotEmpty()) {
                $bottom5 = $bottom5->merge($withoutFreq->take($remaining))->values();
            }
        } else {
            // Few recorridos: all with freq as top, all with zero freq as bottom
            $top5 = $withFreq->take(5)->values();
            $bottom5 = $withoutFreq->take(5)->values();
        }

        return response()->json([
            'top5' => $top5,
            'bottom5' => $bottom5,
        ]);
    }

    /**
     * API: Supervisor and patrulla performance indicators.
     */
    public function getIndicadores(Request $request)
    {
        $clienteIds = $this->getClienteIds();
        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

        $registrosMes = RecorridoTimetable::whereHas('recorrido', fn ($q) => $q->whereIn('cliente_id', $clienteIds))
            ->where('fecha_hora_inicio', '>=', $inicioMes)
            ->where('fecha_hora_inicio', '<=', $finMes)
            ->with(['supervisor', 'patrulla', 'recorrido.empresaAsociada'])
            ->get();

        $indicadores = [];

        // Supervisor con más recorridos
        $porSupervisor = $registrosMes->groupBy('supervisor_id');
        if ($porSupervisor->isNotEmpty()) {
            $maxSup = $porSupervisor->sortByDesc(fn ($items) => $items->count())->first();
            if ($maxSup && $maxSup->first()->supervisor) {
                $sup = $maxSup->first()->supervisor;
                $empresa = $maxSup->first()->recorrido->empresaAsociada->nombre ?? '';
                $indicadores[] = [
                    'tipo' => 'success',
                    'icon' => 'trophy',
                    'texto' => "El supervisor <strong>{$sup->nombre} {$sup->apellido}</strong> realizó la mayor cantidad de recorridos este mes".($empresa ? " en <strong>{$empresa}</strong>" : '')." con <strong>{$maxSup->count()}</strong> recorridos.",
                ];
            }

            // Supervisor con menos recorridos
            $minSup = $porSupervisor->sortBy(fn ($items) => $items->count())->first();
            if ($minSup && $minSup->first()->supervisor && $porSupervisor->count() > 1) {
                $sup = $minSup->first()->supervisor;
                $indicadores[] = [
                    'tipo' => 'warning',
                    'icon' => 'alert',
                    'texto' => "El supervisor <strong>{$sup->nombre} {$sup->apellido}</strong> realizó solo <strong>{$minSup->count()}</strong> recorrido(s) este mes.",
                ];
            }
        }

        // Patrulla con más recorridos
        $porPatrulla = $registrosMes->groupBy('patrulla_id');
        if ($porPatrulla->isNotEmpty()) {
            $minPat = $porPatrulla->sortBy(fn ($items) => $items->count())->first();
            if ($minPat && $minPat->first()->patrulla) {
                $pat = $minPat->first()->patrulla;
                $indicadores[] = [
                    'tipo' => 'info',
                    'icon' => 'car',
                    'texto' => "La patrulla <strong>{$pat->patente}</strong> fue la que realizó menos recorridos este mes, con solo <strong>{$minPat->count()}</strong>.",
                ];
            }
        }

        // Velocidades excedidas
        $excedidas = $registrosMes->where('velocidad_excedida', true);
        if ($excedidas->isNotEmpty()) {
            $porSupVel = $excedidas->groupBy('supervisor_id');
            $maxVelSup = $porSupVel->sortByDesc(fn ($items) => $items->count())->first();
            if ($maxVelSup && $maxVelSup->first()->supervisor) {
                $sup = $maxVelSup->first()->supervisor;
                $indicadores[] = [
                    'tipo' => 'danger',
                    'icon' => 'speed',
                    'texto' => "El supervisor <strong>{$sup->nombre} {$sup->apellido}</strong> excedió la velocidad máxima en <strong>{$maxVelSup->count()}</strong> recorrido(s) este mes.",
                ];
            }

            $porPatVel = $excedidas->groupBy('patrulla_id');
            $maxVelPat = $porPatVel->sortByDesc(fn ($items) => $items->count())->first();
            if ($maxVelPat && $maxVelPat->first()->patrulla) {
                $pat = $maxVelPat->first()->patrulla;
                $indicadores[] = [
                    'tipo' => 'danger',
                    'icon' => 'speed',
                    'texto' => "La patrulla <strong>{$pat->patente}</strong> registró <strong>{$maxVelPat->count()}</strong> exceso(s) de velocidad este mes.",
                ];
            }

            // Total excedidas
            $indicadores[] = [
                'tipo' => 'danger',
                'icon' => 'speed',
                'texto' => "Se registraron <strong>{$excedidas->count()}</strong> exceso(s) de velocidad en total este mes.",
            ];
        }

        return response()->json(['indicadores' => $indicadores]);
    }
}
