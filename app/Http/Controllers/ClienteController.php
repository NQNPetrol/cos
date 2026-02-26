<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    public function create()
    {
        return view('clientes.nuevo');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre' => 'required|min:3',
            'cuit' => 'nullable',
            'domicilio' => 'nullable',
            'ciudad' => 'nullable',
            'provincia' => 'nullable',
            'categoria' => 'nullable',
            'convenio' => 'nullable',
            'logo' => 'nullable|image|mimes:png|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Eliminar logo anterior si existe (usando disco 'public')
            if ($cliente->logo && Storage::disk('public')->exists($cliente->logo)) {
                Storage::disk('public')->delete($cliente->logo);
            }

            // Guardar nuevo logo
            $logoPath = $request->file('logo')->store('clientes/logos', 'public');
            $validated['logo'] = $logoPath;
        }

        $cliente->update($validated);

        return redirect()->route('crear.cliente', $cliente->id)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function deleteLogo(Cliente $cliente)
    {
        if ($cliente->logo) {
            // Especificar el disco 'public' tanto para exists como para delete
            if (Storage::disk('public')->exists($cliente->logo)) {
                Storage::disk('public')->delete($cliente->logo);
            }

            // Actualizar el campo en la base de datos
            $cliente->update(['logo' => null]);
        }

        return back()->with('success', 'Logo eliminado correctamente.');
    }
}
