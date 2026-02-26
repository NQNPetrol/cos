<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Personal;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    public function index()
    {
        $personal = Personal::with('categoria')->paginate(10);

        return view('personal.index', compact('personal'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $clientes = Cliente::orderBy('nombre')->get();

        return view('personal.create', compact('clientes', 'categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'documento' => 'nullable|string|max:255|unique:personal,documento',
            'fecha_inicio' => 'nullable|date',
            'cliente_id' => 'required|exists:clientes,id',
            'puesto' => 'nullable|string|max:255',
            'cargo' => 'required|string',
        ]);

        Personal::create($validated);

        return redirect()->route('personal.index')
            ->with('success', 'Personal creado correctamente.');
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $personal = Personal::findOrFail($id);

        $clientes = Cliente::orderBy('nombre')->get();

        return view('personal.edit', compact('personal', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $personal = Personal::findOrFail($id);
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'documento' => 'nullable|string|max:255|unique:personal,documento',
            'fecha_inicio' => 'nullable|date',
            'cliente_id' => 'required|exists:clientes,id',
            'puesto' => 'nullable|string|max:255',
            'cargo' => 'required|string',
        ]);
        $personal->update($validated);

        return redirect()->route('personal.index')
            ->with('success', 'Personal actualizado correctamente');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
