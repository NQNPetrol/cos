<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\MisionFlytbase;
use App\Models\FlytbaseDrone;
use App\Models\FlytbaseDock;
use App\Models\FlytbaseSite;
use App\Models\Cliente;
use App\Models\UserCliente;
use App\Services\KmzParserService;

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
        Log::info('=== INICIO CREAR MISIÓN ===');
        Log::info('Usuario autenticado:', ['user_id' => auth()->id(), 'user_name' => auth()->user()->name ?? 'N/A']);
        Log::info('Datos recibidos en request:', [
            'all' => $request->all(),
            'has_kmz_file' => $request->hasFile('kmz_file'),
            'has_waypoints' => $request->has('waypoints'),
            'waypoints_value' => $request->input('waypoints'),
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type')
        ]);
        
        $user = auth()->user();
        
        try {
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
            'kmz_file' => 'nullable|file|mimetypes:application/vnd.google-earth.kmz,application/zip|max:10240', // Máximo 10MB - acepta KMZ y ZIP
            'url' => 'required|url',
            'observaciones' => 'nullable|string',
            'activo' => 'boolean'
            ]);
            
            Log::info('Validación exitosa. Datos validados:', ['validated' => $validated]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación:', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            throw $e;
        }

        // Verificar permisos del usuario para el cliente seleccionado
        if (!$user->hasRole('admin') && !$user->hasRole('operador')) {
            $userClientes = UserCliente::where('user_id', $user->id)->pluck('cliente_id');
            if (!in_array($validated['cliente_id'], $userClientes->toArray())) {
                Log::warning('Usuario sin permisos para crear misión para este cliente:', [
                    'user_id' => $user->id,
                    'cliente_id' => $validated['cliente_id']
                ]);
                return redirect()->back()->with('error', 'No tiene permisos para crear misiones para este cliente.');
            }
        }

        Log::info('Permisos verificados correctamente');

        try {
            $kmzFilePath = null;
            
            // Manejar archivo KMZ si se proporciona
            if ($request->hasFile('kmz_file')) {
                Log::info('Archivo KMZ detectado, iniciando procesamiento...');
                try {
                    $kmzFile = $request->file('kmz_file');
                    Log::info('Información del archivo KMZ:', [
                        'original_name' => $kmzFile->getClientOriginalName(),
                        'mime_type' => $kmzFile->getMimeType(),
                        'size' => $kmzFile->getSize(),
                        'extension' => $kmzFile->getClientOriginalExtension()
                    ]);
                    
                    $kmzFilePath = $kmzFile->store('misiones/kmz', 'public');
                    Log::info('Archivo KMZ guardado en:', ['path' => $kmzFilePath]);
                    
                    // Parsear KMZ y extraer waypoints
                    Log::info('Iniciando parseo del archivo KMZ...');
                    $kmzParser = new KmzParserService();
                    $waypointsFromKmz = $kmzParser->parseKmzToWaypoints($kmzFilePath);
                    
                    Log::info('Waypoints extraídos del KMZ:', [
                        'count' => count($waypointsFromKmz),
                        'waypoints' => $waypointsFromKmz
                    ]);
                    
                    if (!empty($waypointsFromKmz)) {
                        // Si hay waypoints del KMZ, usarlos (sobrescriben el JSON si existe)
                        $validated['waypoints'] = $waypointsFromKmz;
                        $validated['kmz_file_path'] = $kmzFilePath;
                        Log::info('Waypoints del KMZ asignados correctamente');
                    } else {
                        // Si no se encontraron waypoints, eliminar el archivo y mostrar error
                        Log::warning('No se encontraron waypoints en el archivo KMZ');
                        Storage::disk('public')->delete($kmzFilePath);
                        return redirect()->back()->with('error', 'No se pudieron extraer waypoints del archivo KMZ. Verifique que el archivo contenga coordenadas válidas.');
                    }
                } catch (\Exception $e) {
                    Log::error('Error al procesar archivo KMZ:', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);
                    if ($kmzFilePath) {
                        Storage::disk('public')->delete($kmzFilePath);
                    }
                    return redirect()->back()->with('error', 'Error al procesar el archivo KMZ: ' . $e->getMessage());
                }
            } else {
                Log::info('No se detectó archivo KMZ, procesando waypoints JSON...');
                // Si no hay archivo KMZ, procesar waypoints JSON
                if (!empty($validated['waypoints'])) {
                    try {
                        Log::info('Procesando waypoints JSON:', ['waypoints_raw' => $validated['waypoints']]);
                        // Validar que el JSON sea válido
                        $waypointsArray = json_decode($validated['waypoints'], true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            Log::error('Error al parsear JSON de waypoints:', [
                                'json_error' => json_last_error_msg(),
                                'waypoints_raw' => $validated['waypoints']
                            ]);
                            return redirect()->back()->with('error', 'El formato de JSON en waypoints no es válido.');
                        }
                        $validated['waypoints'] = $waypointsArray;
                        Log::info('Waypoints JSON procesados correctamente:', ['count' => count($waypointsArray)]);
                    } catch (\Exception $e) {
                        Log::error('Error al procesar waypoints JSON:', [
                            'message' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        return redirect()->back()->with('error', 'Error al procesar los waypoints: ' . $e->getMessage());
                    }
                } else {
                    $validated['waypoints'] = null;
                    Log::info('No hay waypoints (ni KMZ ni JSON)');
                }
            }

            Log::info('Datos finales antes de crear la misión:', [
                'validated' => $validated,
                'waypoints_count' => is_array($validated['waypoints']) ? count($validated['waypoints']) : 'null',
                'waypoints_type' => gettype($validated['waypoints'] ?? null),
                'kmz_file_path' => $validated['kmz_file_path'] ?? 'null',
                'activo' => $validated['activo'] ?? false,
                'activo_type' => gettype($validated['activo'] ?? null)
            ]);

            // Limpiar campos vacíos antes de crear
            foreach ($validated as $key => $value) {
                if ($value === '' || $value === null) {
                    if (!in_array($key, ['drone_id', 'dock_id', 'site_id', 'descripcion', 'observaciones', 'waypoints', 'kmz_file_path'])) {
                        unset($validated[$key]);
                    }
                }
            }

            Log::info('Datos limpiados antes de crear:', ['validated_cleaned' => $validated]);

            Log::info('Intentando crear la misión en la base de datos...');
            try {
                $mision = MisionFlytbase::create($validated);
                
                Log::info('Misión creada exitosamente:', [
                    'mision_id' => $mision->id,
                    'mision_nombre' => $mision->nombre,
                    'mision_data' => $mision->toArray()
                ]);
                
                return redirect()->route('misiones-flytbase.index')
                    ->with('success', 'Misión creada exitosamente.');
            } catch (\Illuminate\Database\QueryException $e) {
                Log::error('Error de base de datos al crear misión:', [
                    'message' => $e->getMessage(),
                    'sql' => $e->getSql() ?? 'N/A',
                    'bindings' => $e->getBindings() ?? [],
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
                
        } catch (\Exception $e) {
            Log::error('Error al crear misión Flytbase:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'validated_data' => $validated ?? []
            ]);
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
            'kmz_file' => 'nullable|file|mimetypes:application/vnd.google-earth.kmz,application/zip|max:10240', // Máximo 10MB - acepta KMZ y ZIP
            'url' => 'required|url',
            'observaciones' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        try {
            $kmzFilePath = null;
            
            // Manejar archivo KMZ si se proporciona
            if ($request->hasFile('kmz_file')) {
                try {
                    // Eliminar archivo KMZ anterior si existe
                    if ($misionesFlytbase->kmz_file_path) {
                        Storage::disk('public')->delete($misionesFlytbase->kmz_file_path);
                    }
                    
                    $kmzFile = $request->file('kmz_file');
                    $kmzFilePath = $kmzFile->store('misiones/kmz', 'public');
                    
                    // Parsear KMZ y extraer waypoints
                    $kmzParser = new KmzParserService();
                    $waypointsFromKmz = $kmzParser->parseKmzToWaypoints($kmzFilePath);
                    
                    if (!empty($waypointsFromKmz)) {
                        // Si hay waypoints del KMZ, usarlos (sobrescriben el JSON si existe)
                        $validated['waypoints'] = $waypointsFromKmz;
                        $validated['kmz_file_path'] = $kmzFilePath;
                    } else {
                        // Si no se encontraron waypoints, eliminar el archivo y mostrar error
                        Storage::disk('public')->delete($kmzFilePath);
                        return redirect()->back()->with('error', 'No se pudieron extraer waypoints del archivo KMZ. Verifique que el archivo contenga coordenadas válidas.');
                    }
                } catch (\Exception $e) {
                    Log::error('Error al procesar archivo KMZ: ' . $e->getMessage());
                    if ($kmzFilePath) {
                        Storage::disk('public')->delete($kmzFilePath);
                    }
                    return redirect()->back()->with('error', 'Error al procesar el archivo KMZ: ' . $e->getMessage());
                }
            } else {
                // Si no hay archivo KMZ, procesar waypoints JSON
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

            // Eliminar archivo KMZ si existe
            if ($misionesFlytbase->kmz_file_path) {
                Storage::disk('public')->delete($misionesFlytbase->kmz_file_path);
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
