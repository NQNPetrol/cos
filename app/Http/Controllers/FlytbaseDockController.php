<?php

namespace App\Http\Controllers;

use App\Models\FlytbaseDock;
use App\Models\FlytbaseSite;
use Illuminate\Http\Request;

class FlytbaseDockController extends Controller
{
    public function index(Request $request)
    {
        $docks = FlytbaseDock::with('site')
            ->latest()
            ->get();

        $sites = FlytbaseSite::where('activo', true)
            ->latest()
            ->get();

        return view('docks-flytbase.index', compact('docks', 'sites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|min:10',
            'flytbase_site_id' => 'required|exists:flytbase_sites,id',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'altitude' => 'nullable|numeric|min:0',
            'active' => 'boolean',
        ]);

        try {
            FlytbaseDock::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'flytbase_site_id' => $request->flytbase_site_id,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'altitude' => $request->altitude,
                'active' => $request->active ?? true,

            ]);

            return redirect()->route('docks-flytbase.index')
                ->with('success', 'Dock creado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el dock: '.$e->getMessage());
        }
    }

    /**
     * Actualizar un drone existente
     */
    public function update(Request $request, FlytbaseDock $flytbase_dock)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:flytbase_docks,nombre,'.$flytbase_dock->id,
            'descripcion' => 'nullable|string',
            'flytbase_site_id' => 'required|exists:flytbase_sites,id',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'altitude' => 'nullable|numeric|min:0',
            'active' => 'boolean',

        ]);

        try {
            $flytbase_dock->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'flytbase_site_id' => $request->flytbase_site_id,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'altitude' => $request->altitude,
                'active' => $request->active ?? true,

            ]);

            return redirect()->route('docks-flytbase.index')
                ->with('success', 'Dock actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el dock: '.$e->getMessage());
        }
    }

    public function destroy(FlytbaseDock $flytbase_dock)
    {

        try {
            if ($flytbase_dock->misiones()->exists()) {
                return redirect()->back()->with('error', 'No se puede eliminar el dock porque tiene misiones asociadas.');
            }

            $flytbase_dock->delete();

            return redirect()->route('docks-flytbase.index')
                ->with('success', 'Dock eliminado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el dock: '.$e->getMessage());
        }
    }
}
