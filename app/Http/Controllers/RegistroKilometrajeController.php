<?php

namespace App\Http\Controllers;

use App\Models\RegistroKilometraje;
use Illuminate\Http\Request;

class RegistroKilometrajeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rodado_id' => 'required|exists:rodados,id',
            'kilometraje' => 'required|integer|min:0',
            'fecha_registro' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        // Validar que el kilometraje sea mayor al anterior
        $ultimoRegistro = RegistroKilometraje::where('rodado_id', $validated['rodado_id'])
            ->latest('fecha_registro')
            ->first();

        if ($ultimoRegistro && $validated['kilometraje'] < $ultimoRegistro->kilometraje) {
            return redirect()->back()
                ->with('error', 'El kilometraje debe ser mayor al último registro ('.$ultimoRegistro->kilometraje.' km).');
        }

        RegistroKilometraje::create($validated);

        return redirect()->route('rodados.index')
            ->with('success', 'Kilometraje registrado exitosamente.');
    }

    public function destroy(RegistroKilometraje $registro)
    {
        $registro->delete();

        return redirect()->route('rodados.index')
            ->with('success', 'Registro de kilometraje eliminado exitosamente.');
    }
}
