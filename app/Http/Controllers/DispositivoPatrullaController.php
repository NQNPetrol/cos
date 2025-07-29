<?php

namespace App\Http\Controllers;

use App\Models\Patrulla;
use App\Models\Dispositivo;
use App\Models\DispositivoPatrulla;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DispositivoPatrullaController extends Controller
{
    public function index(Patrulla $patrulla)
    {
        $asignaciones = DispositivoPatrulla::with(['dispositivo.cliente', 'patrulla'])
        ->where('patrulla_id', $patrulla->id)
        ->orderBy('fecha_asignacion', 'desc')
        ->paginate(10);

    return view('patrullas.dispositivos', [
        'patrulla' => $patrulla,
        'asignaciones' => $asignaciones
        ]);
    }

    public function store(Request $request, Patrulla $patrulla)
    {
        $request->validate([
            'dispositivos' => 'required|array',
            'dispositivos.*' => 'exists:dispositivos,id'
        ]);

        //Obtener dispositivos ya asignados
        $dispositivosActuales = $patrulla->dispositivos()->pluck('dispositivos.id')->toArray();
        $nuevosDispositivos = array_diff($request->dispositivos, $dispositivosActuales);
        
        //fecha actual
        $now = Carbon::now()->toDateString();
        $syncData= [];
        foreach ($nuevosDispositivos as $dispositivoId) {
            $syncData[$dispositivoId] = ['fecha_asignacion' => $now];
        }

        $patrulla->dispositivos()->syncWithoutDetaching($syncData);
        
        return back()->with('success', 'Dispositivos asignados correctamente');
    }

    public function destroy(Patrulla $patrulla, Dispositivo $dispositivo)
    {
        $patrulla->dispositivos()->detach($dispositivo->id);
        
        return back()->with('success', 'Dispositivo desvinculado correctamente');
    }

    public function update(Request $request, Patrulla $patrulla, Dispositivo $dispositivo)
{
    $validated = $request->validate([
        'fecha_asignacion' => 'required|date'
    ]);
    
    $patrulla->dispositivos()->updateExistingPivot($dispositivo->id, [
        'fecha_asignacion' => $validated['fecha_asignacion']
    ]);
    
    return back()->with('success', 'Asignación actualizada correctamente');
}
}
