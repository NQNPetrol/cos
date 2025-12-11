<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Taller;

class TallerController extends Controller
{
    public function index()
    {
        return Taller::orderBy('nombre')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        return Taller::create($validated);
    }

    public function show(Taller $taller)
    {
        return $taller;
    }

    public function update(Request $request, Taller $taller)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        $taller->update($validated);
        return $taller;
    }

    public function destroy(Taller $taller)
    {
        $taller->delete();
        return response()->json(['message' => 'Taller eliminado exitosamente']);
    }
}
