<?php

namespace App\Http\Controllers;

use App\Models\ServicioUsuario;
use Illuminate\Http\Request;

class ServicioUsuarioController extends Controller
{
    public function index()
    {
        $servicios = ServicioUsuario::orderBy('nombre')->get();

        return response()->json($servicios);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_calculo' => 'required|in:fijo,variable',
            'valor_unitario' => 'nullable|numeric|min:0',
            'moneda' => 'required|in:ARS,USD',
        ]);

        $validated['activo'] = true;

        $servicio = ServicioUsuario::create($validated);

        if ($request->expectsJson()) {
            return response()->json($servicio, 201);
        }

        return redirect()->back()->with('success', 'Servicio creado exitosamente.');
    }

    public function show(ServicioUsuario $servicios_usuario)
    {
        return response()->json($servicios_usuario);
    }

    public function update(Request $request, ServicioUsuario $servicios_usuario)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_calculo' => 'required|in:fijo,variable',
            'valor_unitario' => 'nullable|numeric|min:0',
            'moneda' => 'required|in:ARS,USD',
            'activo' => 'nullable|boolean',
        ]);

        $servicios_usuario->update($validated);

        if ($request->expectsJson()) {
            return response()->json($servicios_usuario);
        }

        return redirect()->back()->with('success', 'Servicio actualizado exitosamente.');
    }

    public function destroy(ServicioUsuario $servicios_usuario)
    {
        $servicios_usuario->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Servicio eliminado exitosamente.']);
        }

        return redirect()->back()->with('success', 'Servicio eliminado exitosamente.');
    }
}
