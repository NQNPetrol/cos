<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\EmpresaAsociada;

class ContratoController extends Controller
{
    public function index()
    {
        return view('contratos.index');
    }

    public function create()
    {
        $clientes = Cliente::all();
        $empresas_asociadas = collect();
        return view('contratos.create', compact(['clientes', 'empresas_asociadas']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id'      => 'required|exists:clientes,id',
            'empresa_asociada_id'=> 'required|exists:empresas_asociadas,id',
            'nombre_proyecto' => 'required|string|max:255',
            'localidad'       => 'nullable|string|max:255',
            'provincia'       => 'nullable|string|max:255',
            'observaciones'   => 'nullable|string',
            'fecha_inicio'    => 'nullable|date',
        ]);

        Contrato::create($validated);

        return redirect()->route('contratos.index')
            ->with('success', 'Contrato creado correctamente.');
    }

    public function edit(Contrato $contrato)
    {
        $clientes = Cliente::all();
        $empresa_asociada = $contrato->cliente->empresasAsociadas ?? collect();
        return view('contratos.edit', compact(['contrato', 'clientes', 'empresa_asociada', 'cliente_id']));
    }

    public function update(Request $request, Contrato $contrato)
    {
        $validated = $request->validate([
            'cliente_id'      => 'required|exists:clientes,id',
            'empresa_asociada_id'=> 'required|exists:empresas_asociadas,id',
            'nombre_proyecto' => 'required|string|max:255',
            'localidad'       => 'nullable|string|max:255',
            'provincia'       => 'nullable|string|max:255',
            'observaciones'   => 'nullable|string',
            'fecha_inicio'    => 'nullable|date',
        ]);

        $contrato->update($validated);

        return redirect()->route('contratos.index')
            ->with('success', 'Contrato actualizado correctamente.');
    }

    public function destroy(Contrato $contrato)
    {
        $contrato->delete();

        return back()->with('success', 'Contrato eliminado correctamente.');
    }

}
