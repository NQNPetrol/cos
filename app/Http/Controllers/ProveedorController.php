<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    public function index()
    {
        return Proveedor::orderBy('nombre')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $proveedor = Proveedor::create($validated);

        if ($request->expectsJson()) {
            return $proveedor;
        }

        return redirect()->route('rodados.proveedores-talleres.index')->with('success', 'Proveedor creado exitosamente.');
    }

    public function show(Proveedor $proveedor)
    {
        return $proveedor;
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $proveedor->update($validated);

        if ($request->expectsJson()) {
            return $proveedor;
        }

        return redirect()->route('rodados.proveedores-talleres.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Proveedor eliminado exitosamente']);
        }

        return redirect()->route('rodados.proveedores-talleres.index')->with('success', 'Proveedor eliminado exitosamente.');
    }
}
