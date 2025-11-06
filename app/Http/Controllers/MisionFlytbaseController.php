<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\MisionFlytbase;
use App\Models\FlytbaseDrone;
use App\Models\FlytbaseDock;
use App\Models\FlytbaseSite;
use App\Models\Cliente;
use App\Models\UserCliente;

class MisionFlytbaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Obtener misiones según permisos
        $query = MisionFlytbase::with('cliente');
        
        if ($user->hasRole('admin') || $user->hasRole('operador')) {
            // Admin y operadores ven todas las misiones
            $misiones = $query->latest()->paginate(15);
        } elseif ($user->hasRole('cliente')) {
            // Clientes ven solo sus misiones
            $userClientes = UserCliente::where('user_id', $user->id)->pluck('cliente_id');
            $misiones = $query->whereIn('cliente_id', $userClientes)->latest()->paginate(15);
        } else {
            $misiones = collect();
        }

        $clientes = Cliente::orderBy('nombre')->get();
        $drones = FlytbaseDrone::activos()->get();
        $docks = FlytbaseDock::activos()->get();
        $sites = FlytbaseSite::activos()->get();

        return view('misiones-flytbase.index', compact('misiones', 'clientes', 'drones', 'docks', 'sites'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cliente_id' => 'required|exists:clientes,id',
            'drone_id' => 'nullable|exists:drones_flytbase,id',
            'dock_id' => 'nullable|exists:flytbase_docks,id',
            'site_id' => 'nullable|exists:flytbase_sites,id',
            'route_altitude' => 'required|numeric|min:0|max:500',
            'route_speed' => 'required|numeric|min:0|max:50',
            'route_waypoint_type' => 'required|in:linear_route,transits_waypoint,curved_route_drone_stops,curved_route_drone_continues',
            'waypoints' => 'nullable|json',
            'url' => 'required|url',
            'observaciones' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        // Verificar permisos del usuario para el cliente seleccionado
        if (!$user->hasRole('admin') && !$user->hasRole('operador')) {
            $userClientes = UserCliente::where('user_id', $user->id)->pluck('cliente_id');
            if (!in_array($validated['cliente_id'], $userClientes->toArray())) {
                return redirect()->back()->with('error', 'No tiene permisos para crear misiones para este cliente.');
            }
        }

        try {
            if (!empty($validated['waypoints'])) {
                try {
                    // Validar que el JSON sea válido
                    $waypointsArray = json_decode($validated['waypoints'], true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        return redirect()->back()->with('error', 'El formato de JSON en waypoints no es válido.');
                    }
                    $validated['waypoints'] = $waypointsArray;
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Error al procesar los waypoints: ' . $e->getMessage());
                }
            } else {
                $validated['waypoints'] = null;
            }

            MisionFlytbase::create($validated);
            
            return redirect()->route('misiones-flytbase.index')
                ->with('success', 'Misión creada exitosamente.');
                
        } catch (\Exception $e) {
            Log::error('Error al crear misión Flytbase: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al crear la misión: ' . $e->getMessage());
        }
    }

  


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MisionFlytbase $misionesFlytbase)
    {
        // Verificar permisos
        if (!$this->usuarioPuedeAccederMision(auth()->user(), $misionesFlytbase)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permisos para editar esta misión.'
                ], 403);
            }
            abort(403, 'No tiene permisos para editar esta misión.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cliente_id' => 'required|exists:clientes,id',
            'drone_id' => 'nullable|exists:drones_flytbase,id',
            'dock_id' => 'nullable|exists:flytbase_docks,id',
            'site_id' => 'nullable|exists:flytbase_sites,id',
            'route_altitude' => 'required|numeric|min:0|max:500',
            'route_speed' => 'required|numeric|min:0|max:50',
            'route_waypoint_type' => 'required|in:linear_route,transits_waypoint,curved_route_drone_stops,curved_route_drone_continues',
            'waypoints' => 'nullable|json',
            'url' => 'required|url',
            'observaciones' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        try {
            if (!empty($validated['waypoints'])) {
                try {
                    // Validar que el JSON sea válido
                    $waypointsArray = json_decode($validated['waypoints'], true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        return redirect()->back()->with('error', 'El formato de JSON en waypoints no es válido.');
                    }
                    $validated['waypoints'] = $waypointsArray;
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', 'Error al procesar los waypoints: ' . $e->getMessage());
                }
            } else {
                $validated['waypoints'] = null;
            }

            $misionesFlytbase->update($validated);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Misión actualizada exitosamente.'
                ]);
            }
            
            return redirect()->route('misiones-flytbase.index')
                ->with('success', 'Misión actualizada exitosamente.');
                
        } catch (\Exception $e) {
            Log::error('Error al actualizar misión Flytbase: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la misión: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error al actualizar la misión: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MisionFlytbase $misionesFlytbase)
    {
        if (!$this->usuarioPuedeAccederMision(auth()->user(), $misionesFlytbase)) {
            abort(403, 'No tiene permisos para eliminar esta misión.');
        }

        try {
            // Verificar si hay alertas asociadas
            if ($misionesFlytbase->alertLogs()->exists()) {
                return redirect()->back()->with('error', 'No se puede eliminar la misión porque tiene alertas asociadas.');
            }

            $misionesFlytbase->delete();
            
            return redirect()->route('misiones-flytbase.index')
                ->with('success', 'Misión eliminada exitosamente.');
                
        } catch (\Exception $e) {
            Log::error('Error al eliminar misión Flytbase: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar la misión: ' . $e->getMessage());
        }
    }

    public function toggleStatus(MisionFlytbase $misionesFlytbase)
    {
        // Verificar permisos
        if (!$this->usuarioPuedeAccederMision(auth()->user(), $misionesFlytbase)) {
            abort(403, 'No tiene permisos para modificar esta misión.');
        }

        try {
            $misionesFlytbase->update([
                'activo' => !$misionesFlytbase->activo
            ]);

            $status = $misionesFlytbase->activo ? 'activada' : 'desactivada';
            
            return redirect()->back()->with('success', "Misión {$status} exitosamente.");
                
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de misión: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cambiar el estado de la misión.');
        }
    }

    private function usuarioPuedeAccederMision($user, $mision)
    {
        if ($user->hasRole('admin') || $user->hasRole('operador')) {
            return true;
        }

        if ($user->hasRole('cliente')) {
            $userClientes = UserCliente::where('user_id', $user->id)->pluck('cliente_id');
            return in_array($mision->cliente_id, $userClientes->toArray());
        }

        return false;
    }
}
