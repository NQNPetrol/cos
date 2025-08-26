<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\EmpresaAsociada;


class ClienteEmpresasAsociadasController extends Controller
{
    public function index($clienteId)
    {
        // Verificar que el cliente existe
        $cliente = Cliente::with(['empresasAsociadas' => function($query) {
            $query->withTimestamps();
        }])->findOrFail($clienteId);

        $empresasNoAsociadas = EmpresaAsociada::whereDoesntHave('cliente', function($query) use ($clienteId) {
            $query->where('cliente_id', $clienteId);
        })->get();
        
        return view('clienteEmpresaAsociada.index', [
            'cliente' => $cliente,
            'empresasAsociadas' => $cliente->empresasAsociadas,
            'empresasNoAsociadas' => $empresasNoAsociadas
        ]);
    }

    public function store(Request $request, $clienteId)
    {
        $request->validate([
            'empresas' => 'required|array',
            'empresas.*' => 'exists:empresas_asociadas,id'
        ]);

        $cliente = Cliente::findOrFail($clienteId);
        $now = now()->format('Y-m-d H:i:s');
        $attachData = [];
        foreach ($request->empresas as $empresaId) {
            $attachData[$empresaId] = ['created_at' => $now, 'updated_at' => $now];
        }

        $cliente->empresasAsociadas()->syncWithoutDetaching($attachData);

        return redirect()
            ->route('clientes.empresas-asociadas', $clienteId)
            ->with('success', 'Empresas asociadas correctamente');
    }

    public function destroy($clienteId, $empresaId)
    {
        $cliente = Cliente::findOrFail($clienteId);
        $cliente->empresasAsociadas()->detach($empresaId);

        return redirect()
            ->route('clientes.empresas-asociadas', $clienteId)
            ->with('success', 'Empresa desasociada correctamente');
    }
}
