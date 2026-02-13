<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChecklistPatrulla;
use App\Models\Patrulla;

class ChecklistPatrullaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $clienteIds = $user->clientes()->pluck('cliente_id')->toArray();

        $patrullas = Patrulla::whereIn('cliente_id', $clienteIds)
            ->orderBy('patente')
            ->get();

        $checklists = ChecklistPatrulla::with(['patrulla', 'user'])
            ->whereIn('patrulla_id', $patrullas->pluck('id'))
            ->latest('fecha')
            ->take(50)
            ->get();

        return view('client.checklist.index', compact('patrullas', 'checklists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patrulla_id' => 'required|exists:patrullas,id',
            'fecha' => 'required|date',
            'ruedas_auxilio' => 'required|integer|min:0|max:10',
            'antena_starlink' => 'nullable|boolean',
            'camaras_dvr' => 'nullable|boolean',
            'parabrisas' => 'nullable|boolean',
            'luces' => 'nullable|boolean',
            'balizas' => 'nullable|boolean',
            'antivuelco' => 'nullable|boolean',
            'observaciones' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['antena_starlink'] = $request->has('antena_starlink');
        $validated['camaras_dvr'] = $request->has('camaras_dvr');
        $validated['parabrisas'] = $request->has('parabrisas');
        $validated['luces'] = $request->has('luces');
        $validated['balizas'] = $request->has('balizas');
        $validated['antivuelco'] = $request->has('antivuelco');

        ChecklistPatrulla::create($validated);

        return redirect()->route('client.checklist.index')
            ->with('success', 'Checklist guardado exitosamente.');
    }
}
