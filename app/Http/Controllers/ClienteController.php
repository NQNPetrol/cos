<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

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
            'nombre'    => 'required|min:3',
            'cuit'      => 'required',
            'domicilio' => 'required',
            'ciudad'    => 'nullable',
            'provincia' => 'nullable',
            'categoria' => 'nullable',
            'convenio'  => 'nullable',
        ]);

        $cliente->update($validated);

        return redirect()->route('crear.cliente', $cliente->id)
            ->with('success', 'Cliente actualizado correctamente.');
    }

}
