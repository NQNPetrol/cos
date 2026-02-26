<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cobranza;
use App\Models\PagoServiciosRodado;
use App\Models\ServicioUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CobranzaController extends Controller
{
    public function index(Request $request)
    {
        $mes = (int) $request->get('mes', now()->month);
        $anio = (int) $request->get('anio', now()->year);
        $clienteFilter = $request->get('cliente_id');
        $estadoFilter = $request->get('estado');

        // Base query filtered by period
        $query = Cobranza::with(['cliente', 'servicioUsuario'])
            ->whereMonth('fecha_emision', $mes)
            ->whereYear('fecha_emision', $anio);

        if ($clienteFilter) {
            $query->where('cliente_id', $clienteFilter);
        }
        if ($estadoFilter) {
            $query->where('estado', $estadoFilter);
        }

        $cobranzas = $query->latest('fecha_emision')->get();
        $pendientes = $cobranzas->where('estado', '!=', Cobranza::ESTADO_COBRADO);
        $cobradas = $cobranzas->where('estado', Cobranza::ESTADO_COBRADO);

        $clientes = Cliente::orderBy('nombre')->get();
        $servicios = ServicioUsuario::where('activo', true)->orderBy('nombre')->get();

        // ==========================================
        // RESUMEN DEL PERIODO (siempre se calcula)
        // ==========================================

        // Total ingresos (cobrado) del periodo
        $qIngresos = Cobranza::where('estado', Cobranza::ESTADO_COBRADO)
            ->whereMonth('fecha_pago', $mes)
            ->whereYear('fecha_pago', $anio);
        if ($clienteFilter) {
            $qIngresos->where('cliente_id', $clienteFilter);
        }
        $totalIngresos = (float) $qIngresos->sum('monto_total');

        // Total ingresos pendientes del periodo
        $qPendientesTotal = Cobranza::where('estado', '!=', Cobranza::ESTADO_COBRADO)
            ->whereMonth('fecha_emision', $mes)
            ->whereYear('fecha_emision', $anio);
        if ($clienteFilter) {
            $qPendientesTotal->where('cliente_id', $clienteFilter);
        }
        $totalPendiente = (float) $qPendientesTotal->sum('monto_total');

        // Total egresos (pagos realizados) del periodo
        $qEgresos = PagoServiciosRodado::where('estado', PagoServiciosRodado::ESTADO_PAGADO)
            ->whereMonth('fecha_pago', $mes)
            ->whereYear('fecha_pago', $anio);
        if ($clienteFilter) {
            $qEgresos->whereHas('rodado', function ($q) use ($clienteFilter) {
                $q->where('cliente_id', $clienteFilter);
            });
        }
        $totalEgresos = (float) $qEgresos->sum('monto');

        $diferencia = $totalIngresos - $totalEgresos;

        // ==========================================
        // COMPARATIVA POR CLIENTE del periodo
        // ==========================================
        $comparativa = [];
        $clienteIds = $clienteFilter ? [$clienteFilter] : Cliente::pluck('id')->toArray();

        foreach ($clienteIds as $cId) {
            $cliente = Cliente::find($cId);
            if (! $cliente) {
                continue;
            }

            $cobrado = (float) Cobranza::where('cliente_id', $cId)
                ->where('estado', Cobranza::ESTADO_COBRADO)
                ->whereMonth('fecha_pago', $mes)
                ->whereYear('fecha_pago', $anio)
                ->sum('monto_total');

            $pagado = (float) PagoServiciosRodado::whereHas('rodado', function ($q) use ($cId) {
                $q->where('cliente_id', $cId);
            })->where('estado', PagoServiciosRodado::ESTADO_PAGADO)
                ->whereMonth('fecha_pago', $mes)
                ->whereYear('fecha_pago', $anio)
                ->sum('monto');

            // Only include clients that have any activity
            if ($cobrado > 0 || $pagado > 0) {
                $comparativa[] = [
                    'cliente' => $cliente->nombre,
                    'cobrado' => $cobrado,
                    'pagado' => $pagado,
                    'diferencia' => $cobrado - $pagado,
                ];
            }
        }

        return view('rodados.cobranzas', compact(
            'cobranzas',
            'cobradas',
            'pendientes',
            'clientes',
            'servicios',
            'comparativa',
            'totalIngresos',
            'totalPendiente',
            'totalEgresos',
            'diferencia',
            'mes',
            'anio'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'servicio_usuario_id' => 'nullable|exists:servicios_usuario,id',
            'concepto' => 'required|string|max:255',
            'valor_unitario' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:1',
            'moneda' => 'required|in:ARS,USD',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        $validated['monto_total'] = $validated['valor_unitario'] * $validated['cantidad'];
        $validated['estado'] = Cobranza::ESTADO_PENDIENTE;

        Cobranza::create($validated);

        return redirect()->route('rodados.cobranzas.index', [
            'mes' => $request->get('mes', now()->month),
            'anio' => $request->get('anio', now()->year),
        ])->with('success', 'Cobranza registrada exitosamente.');
    }

    public function update(Request $request, Cobranza $cobranza)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'servicio_usuario_id' => 'nullable|exists:servicios_usuario,id',
            'concepto' => 'required|string|max:255',
            'valor_unitario' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:1',
            'moneda' => 'required|in:ARS,USD',
            'estado' => 'nullable|in:pendiente,cobrado,vencido',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'nullable|date',
            'fecha_pago' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        $validated['monto_total'] = $validated['valor_unitario'] * $validated['cantidad'];
        $cobranza->update($validated);

        return redirect()->route('rodados.cobranzas.index')
            ->with('success', 'Cobranza actualizada exitosamente.');
    }

    public function destroy(Cobranza $cobranza)
    {
        if ($cobranza->factura_path) {
            Storage::disk('public')->delete($cobranza->factura_path);
        }
        if ($cobranza->comprobante_path) {
            Storage::disk('public')->delete($cobranza->comprobante_path);
        }

        $cobranza->delete();

        return redirect()->route('rodados.cobranzas.index')
            ->with('success', 'Cobranza eliminada exitosamente.');
    }

    public function adjuntar(Request $request, Cobranza $cobranza)
    {
        $request->validate([
            'factura' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'comprobante' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'fecha_pago' => 'nullable|date',
        ]);

        if ($request->hasFile('factura')) {
            if ($cobranza->factura_path) {
                Storage::disk('public')->delete($cobranza->factura_path);
            }
            $cobranza->factura_path = $request->file('factura')
                ->store('cobranzas/'.$cobranza->cliente_id.'/facturas', 'public');
        }

        if ($request->hasFile('comprobante')) {
            if ($cobranza->comprobante_path) {
                Storage::disk('public')->delete($cobranza->comprobante_path);
            }
            $cobranza->comprobante_path = $request->file('comprobante')
                ->store('cobranzas/'.$cobranza->cliente_id.'/comprobantes', 'public');
        }

        if ($request->filled('fecha_pago')) {
            $cobranza->fecha_pago = $request->fecha_pago;
            $cobranza->estado = Cobranza::ESTADO_COBRADO;
        }

        $cobranza->save();

        return redirect()->route('rodados.cobranzas.index')
            ->with('success', 'Documentacion adjuntada exitosamente.');
    }
}
