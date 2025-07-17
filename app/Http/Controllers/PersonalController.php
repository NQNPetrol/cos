<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personal;
use App\Models\Categoria;

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
        return view('personal.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'documento' => 'nullable|string|max:255|unique:personal,documento',
            'fecha_inicio' => 'nullable|date',
            'puesto' => 'nullable|string|max:255',
            'cargo' => 'required|string'
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
        return view('personal.edit', compact('personal'));
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
            'puesto' => 'nullable|string|max:255',
            'cargo' => 'required|string'
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
