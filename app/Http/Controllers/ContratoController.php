<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Contrato;

class ContratoController extends Controller
{
    public function index()
    {
        return view('contratos.index');
        // Nota: Ideal para cargar un componente Livewire
    }

    public function create()
    {
        $clientes = Cliente::all();
        return view('contratos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id'      => 'required|exists:clientes,id',
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
        return view('contratos.edit', compact('contrato', 'clientes'));
    }

    public function update(Request $request, Contrato $contrato)
    {
        $validated = $request->validate([
            'cliente_id'      => 'required|exists:clientes,id',
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
