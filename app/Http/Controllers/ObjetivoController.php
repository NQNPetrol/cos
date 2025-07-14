<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objetivo;
use App\Models\Cliente;
use App\Models\Contrato;

class ObjetivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $objetivos = Objetivo::with('cliente', 'contrato')->paginate(10);
        return view('objetivos.index', compact('objetivos'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $contratos = Contrato::all();

        return view('objetivos.create', compact('clientes', 'contratos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'contrato_id' => 'required|exists:contratos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'latitud' => 'required|regex:/^-?\d{1,2}\.\d+$/',
            'longitud' => 'required|regex:/^-?\d{1,3}\.\d+$/',
            'localidad' => 'required|string|max:255',
        ]);

        Objetivo::create($validated);

        return redirect()->route('objetivos.index')->with('success', 'Objetivo creado correctamente.');
    }

    public function edit(Objetivo $objetivo)
    {
        $clientes = Cliente::all();
        $contratos = Contrato::all();

        return view('objetivos.edit', compact('objetivo', 'clientes', 'contratos'));
    }

    public function update(Request $request, Objetivo $objetivo)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'contrato_id' => 'required|exists:contratos,id',
            'cliente_id' => 'required|exists:clientes,id',
            'latitud' => 'required|string|max:255',
            'longitud' => 'required|string|max:255',
            'localidad' => 'required|string|max:255',
        ]);

        $objetivo->update($validated);

        return redirect()->route('objetivos.index')->with('success', 'Objetivo actualizado correctamente.');
    }

    public function destroy(Objetivo $objetivo)
    {
        $objetivo->delete();
        return redirect()->route('objetivos.index')->with('success', 'Objetivo eliminado.');
    }
}


