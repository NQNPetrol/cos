<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cobranza;
use App\Models\Cliente;
use App\Models\ServicioUsuario;
use App\Models\PagoServiciosRodado;
use Illuminate\Support\Facades\Storage;

class CobranzaController extends Controller
{
    public function index(Request $request)
    {
        $query = Cobranza::with(['cliente', 'servicioUsuario']);

        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('mes') && $request->filled('anio')) {
            $query->whereMonth('fecha_emision', $request->mes)->whereYear('fecha_emision', $request->anio);
        }

        $cobranzas = $query->latest('fecha_emision')->get();

        $cobradas = $cobranzas->where('estado', Cobranza::ESTADO_COBRADO);
        $pendientes = $cobranzas->where('estado', '!=', Cobranza::ESTADO_COBRADO);

        $clientes = Cliente::orderBy('nombre')->get();
        $servicios = ServicioUsuario::activos()->orderBy('nombre')->get();

        // Comparativa cobrado vs pagado por mes por cliente
        $comparativa = [];
        if ($request->filled('cliente_id') || $request->filled('comparativa')) {
            $clienteIds = $request->filled('cliente_id') ? [$request->cliente_id] : Cliente::pluck('id')->toArray();
            $mes = $request->get('mes', now()->month);
            $anio = $request->get('anio', now()->year);

            foreach ($clienteIds as $clienteId) {
                $cliente = Cliente::find($clienteId);
                if (!$cliente) continue;

                $totalCobrado = Cobranza::where('cliente_id', $clienteId)
                    ->where('estado', Cobranza::ESTADO_COBRADO)
                    ->whereMonth('fecha_pago', $mes)
                    ->whereYear('fecha_pago', $anio)
                    ->sum('monto_total');

                $totalPagado = PagoServiciosRodado::whereHas('rodado', function ($q) use ($clienteId) {
                    $q->where('cliente_id', $clienteId);
                })->where('estado', PagoServiciosRodado::ESTADO_PAGADO)
                    ->whereMonth('fecha_pago', $mes)
                    ->whereYear('fecha_pago', $anio)
                    ->sum('monto');

                $comparativa[] = [
                    'cliente' => $cliente->nombre,
                    'cobrado' => (float) $totalCobrado,
                    'pagado' => (float) $totalPagado,
                    'diferencia' => (float) $totalCobrado - (float) $totalPagado,
                ];
            }
        }

        return view('rodados.cobranzas', compact(
            'cobranzas',
            'cobradas',
            'pendientes',
            'clientes',
            'servicios',
            'comparativa'
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

        // Calcular monto total
        $validated['monto_total'] = $validated['valor_unitario'] * $validated['cantidad'];
        $validated['estado'] = Cobranza::ESTADO_PENDIENTE;

        Cobranza::create($validated);

        return redirect()->route('rodados.cobranzas.index')
            ->with('success', 'Cobranza registrada exitosamente.');
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
                ->store('cobranzas/' . $cobranza->cliente_id . '/facturas', 'public');
        }

        if ($request->hasFile('comprobante')) {
            if ($cobranza->comprobante_path) {
                Storage::disk('public')->delete($cobranza->comprobante_path);
            }
            $cobranza->comprobante_path = $request->file('comprobante')
                ->store('cobranzas/' . $cobranza->cliente_id . '/comprobantes', 'public');
        }

        if ($request->filled('fecha_pago')) {
            $cobranza->fecha_pago = $request->fecha_pago;
            $cobranza->estado = Cobranza::ESTADO_COBRADO;
        }

        $cobranza->save();

        return redirect()->route('rodados.cobranzas.index')
            ->with('success', 'Documentación adjuntada exitosamente.');
    }
}
