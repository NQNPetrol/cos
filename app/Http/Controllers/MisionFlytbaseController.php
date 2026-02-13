<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\FlytbaseDock;
use App\Models\FlytbaseDrone;
use App\Models\FlytbaseSite;
use App\Models\MisionFlytbase;
use App\Models\UserCliente;
use App\Services\KmzParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
     * Procesar archivo KMZ y retornar waypoints
     */
    public function processKmz(Request $request)
    {
        try {
            $request->validate([
                'kmz_file' => 'required|file|mimetypes:application/vnd.google-earth.kmz,application/zip|max:10240',
            ]);

            $kmzFile = $request->file('kmz_file');

            // Guardar archivo temporalmente para parsear
            $tempKmzFilePath = $kmzFile->store('misiones/kmz/temp', 'public');

            // Parsear KMZ y extraer waypoints
            $kmzParser = new KmzParserService;
            $waypoints = $kmzParser->parseKmzToWaypoints($tempKmzFilePath);

            if (empty($waypoints)) {
                // Eliminar archivo temporal si no hay waypoints
                Storage::disk('public')->delete($tempKmzFilePath);

                return response()->json([
                    'success' => false,
                    'message' => 'No se pudieron extraer waypoints del archivo KMZ. Verifique que el archivo contenga coordenadas válidas.',
                ], 400);
            }

            // Guardar archivo permanentemente (no en temp)
            $kmzFilePath = $kmzFile->store('misiones/kmz', 'public');

            // Eliminar archivo temporal
            Storage::disk('public')->delete($tempKmzFilePath);

            return response()->json([
                'success' => true,
                'waypoints' => $waypoints,
                'kmz_file_path' => $kmzFilePath,
                'count' => count($waypoints),
            ]);

        } catch (\Exception $e) {
            Log::error('Error al procesar KMZ: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el archivo KMZ: '.$e->getMessage(),
            ], 500);
        }
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
            'content_type' => $request->header('Content-Type'),
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
                'activo' => 'boolean',
                'est_total_distance' => 'nullable|numeric|min:0',
                'est_total_duration' => 'nullable|integer|min:0',
                'waypoints_count' => 'nullable|integer|min:0',
            ]);

            Log::info('Validación exitosa. Datos validados:', ['validated' => $validated]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación:', [
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }

        // Verificar permisos del usuario para el cliente seleccionado
        if (! $user->hasRole('admin') && ! $user->hasRole('operador')) {
            $userClientes = UserCliente::where('user_id', $user->id)->pluck('cliente_id');
            if (! in_array($validated['cliente_id'], $userClientes->toArray())) {
                Log::warning('Usuario sin permisos para crear misión para este cliente:', [
                    'user_id' => $user->id,
                    'cliente_id' => $validated['cliente_id'],
                ]);

                return redirect()->back()->with('error', 'No tiene permisos para crear misiones para este cliente.');
            }
        }

        Log::info('Permisos verificados correctamente');

        try {
            $kmzFilePath = null;

            // Manejar archivo KMZ si se proporciona (para guardar el archivo)
            if ($request->hasFile('kmz_file')) {
                Log::info('Archivo KMZ detectado, guardando...');
                try {
                    $kmzFile = $request->file('kmz_file');
                    $kmzFilePath = $kmzFile->store('misiones/kmz', 'public');
                    Log::info('Archivo KMZ guardado en:', ['path' => $kmzFilePath]);
                    $validated['kmz_file_path'] = $kmzFilePath;
                } catch (\Exception $e) {
                    Log::error('Error al guardar archivo KMZ:', [
                        'message' => $e->getMessage(),
                    ]);

                    return redirect()->back()->with('error', 'Error al guardar el archivo KMZ: '.$e->getMessage());
                }
            }

            // Procesar waypoints desde el campo hidden (JSON string)
            if (! empty($validated['waypoints'])) {
                try {
                    Log::info('Procesando waypoints desde formulario:', ['waypoints_raw' => $validated['waypoints']]);
                    $waypointsArray = json_decode($validated['waypoints'], true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        Log::error('Error al parsear JSON de waypoints:', [
                            'json_error' => json_last_error_msg(),
                            'waypoints_raw' => $validated['waypoints'],
                        ]);

                        return redirect()->back()->with('error', 'El formato de waypoints no es válido.');
                    }
                    $validated['waypoints'] = $waypointsArray;

                    // Calcular waypoints_count automáticamente si no viene en el request o si hay waypoints
                    if (! isset($validated['waypoints_count']) || $validated['waypoints_count'] === null || $validated['waypoints_count'] === '') {
                        $validated['waypoints_count'] = count($waypointsArray);
                    }

                    Log::info('Waypoints procesados correctamente:', [
                        'count' => count($waypointsArray),
                        'waypoints_count' => $validated['waypoints_count'],
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error al procesar waypoints:', [
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);

                    return redirect()->back()->with('error', 'Error al procesar los waypoints: '.$e->getMessage());
                }
            } else {
                $validated['waypoints'] = null;
                // Si no hay waypoints, establecer waypoints_count en null
                if (! isset($validated['waypoints_count']) || $validated['waypoints_count'] === null || $validated['waypoints_count'] === '') {
                    $validated['waypoints_count'] = null;
                }
                Log::info('No hay waypoints en el formulario');
            }

            Log::info('Datos finales antes de crear la misión:', [
                'validated' => $validated,
                'waypoints_count' => is_array($validated['waypoints']) ? count($validated['waypoints']) : 'null',
                'waypoints_type' => gettype($validated['waypoints'] ?? null),
                'kmz_file_path' => $validated['kmz_file_path'] ?? 'null',
                'activo' => $validated['activo'] ?? false,
                'activo_type' => gettype($validated['activo'] ?? null),
            ]);

            // Limpiar campos vacíos antes de crear
            foreach ($validated as $key => $value) {
                if ($value === '' || $value === null) {
                    if (! in_array($key, ['drone_id', 'dock_id', 'site_id', 'descripcion', 'observaciones', 'waypoints', 'kmz_file_path'])) {
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
                    'mision_data' => $mision->toArray(),
                ]);

                return redirect()->route('misiones-flytbase.index')
                    ->with('success', 'Misión creada exitosamente.');
            } catch (\Illuminate\Database\QueryException $e) {
                Log::error('Error de base de datos al crear misión:', [
                    'message' => $e->getMessage(),
                    'sql' => $e->getSql() ?? 'N/A',
                    'bindings' => $e->getBindings() ?? [],
                    'trace' => $e->getTraceAsString(),
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error al crear misión Flytbase:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'validated_data' => $validated ?? [],
            ]);

            return redirect()->back()->with('error', 'Error al crear la misión: '.$e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MisionFlytbase $misionesFlytbase)
    {
        // Verificar permisos
        if (! $this->usuarioPuedeAccederMision(auth()->user(), $misionesFlytbase)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene permisos para editar esta misión.',
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
            'activo' => 'boolean',
            'est_total_distance' => 'nullable|numeric|min:0',
            'est_total_duration' => 'nullable|integer|min:0',
            'waypoints_count' => 'nullable|integer|min:0',
        ]);

        try {
            $kmzFilePath = null;
            $oldKmzFilePath = null; // Guardar ruta del archivo anterior

            // Manejar archivo KMZ si se proporciona (guardar primero el nuevo)
            if ($request->hasFile('kmz_file')) {
                try {
                    // Guardar la ruta del archivo anterior antes de guardar el nuevo
                    if ($misionesFlytbase->kmz_file_path) {
                        $oldKmzFilePath = $misionesFlytbase->kmz_file_path;
                    }

                    // Guardar el nuevo archivo KMZ primero
                    $kmzFile = $request->file('kmz_file');
                    $kmzFilePath = $kmzFile->store('misiones/kmz', 'public');
                    $validated['kmz_file_path'] = $kmzFilePath;

                    Log::info('Nuevo archivo KMZ guardado:', ['path' => $kmzFilePath, 'old_path' => $oldKmzFilePath]);
                } catch (\Exception $e) {
                    Log::error('Error al guardar archivo KMZ: '.$e->getMessage());

                    return redirect()->back()->with('error', 'Error al guardar el archivo KMZ: '.$e->getMessage());
                }
            }

            // Procesar waypoints desde el campo hidden (JSON string)
            if (! empty($validated['waypoints'])) {
                try {
                    $waypointsArray = json_decode($validated['waypoints'], true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        // Si hay error y se guardó un nuevo archivo, limpiarlo
                        if ($kmzFilePath) {
                            Storage::disk('public')->delete($kmzFilePath);
                            Log::info('Archivo KMZ nuevo eliminado por error de validación de waypoints');
                        }

                        return redirect()->back()->with('error', 'El formato de waypoints no es válido.');
                    }
                    $validated['waypoints'] = $waypointsArray;

                    // Calcular waypoints_count automáticamente si no viene en el request o si hay waypoints
                    if (! isset($validated['waypoints_count']) || $validated['waypoints_count'] === null || $validated['waypoints_count'] === '') {
                        $validated['waypoints_count'] = count($waypointsArray);
                    }
                } catch (\Exception $e) {
                    // Si hay error y se guardó un nuevo archivo, limpiarlo
                    if ($kmzFilePath) {
                        Storage::disk('public')->delete($kmzFilePath);
                        Log::info('Archivo KMZ nuevo eliminado por error al procesar waypoints');
                    }

                    return redirect()->back()->with('error', 'Error al procesar los waypoints: '.$e->getMessage());
                }
            } else {
                $validated['waypoints'] = null;
                // Si no hay waypoints, establecer waypoints_count en null
                if (! isset($validated['waypoints_count']) || $validated['waypoints_count'] === null || $validated['waypoints_count'] === '') {
                    $validated['waypoints_count'] = null;
                }
            }

            // Actualizar la misión con el nuevo archivo y waypoints
            $misionesFlytbase->update($validated);

            // Solo después de actualizar exitosamente, eliminar el archivo anterior
            if ($oldKmzFilePath && $kmzFilePath) {
                try {
                    Storage::disk('public')->delete($oldKmzFilePath);
                    Log::info('Archivo KMZ anterior eliminado exitosamente:', ['old_path' => $oldKmzFilePath]);
                } catch (\Exception $e) {
                    // No fallar la actualización si no se puede eliminar el archivo anterior
                    Log::warning('No se pudo eliminar el archivo KMZ anterior, pero la actualización fue exitosa:', [
                        'old_path' => $oldKmzFilePath,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Misión actualizada exitosamente.',
                ]);
            }

            return redirect()->route('misiones-flytbase.index')
                ->with('success', 'Misión actualizada exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al actualizar misión Flytbase: '.$e->getMessage());

            // Si se guardó un nuevo archivo pero falló la actualización, limpiarlo
            if (isset($kmzFilePath) && $kmzFilePath) {
                try {
                    Storage::disk('public')->delete($kmzFilePath);
                    Log::info('Archivo KMZ nuevo eliminado por error en la actualización');
                } catch (\Exception $deleteException) {
                    Log::error('Error al eliminar archivo KMZ nuevo después de fallo:', [
                        'path' => $kmzFilePath,
                        'error' => $deleteException->getMessage(),
                    ]);
                }
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la misión: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al actualizar la misión: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MisionFlytbase $misionesFlytbase)
    {
        if (! $this->usuarioPuedeAccederMision(auth()->user(), $misionesFlytbase)) {
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
            Log::error('Error al eliminar misión Flytbase: '.$e->getMessage());

            return redirect()->back()->with('error', 'Error al eliminar la misión: '.$e->getMessage());
        }
    }

    public function toggleStatus(MisionFlytbase $misionesFlytbase)
    {
        // Verificar permisos
        if (! $this->usuarioPuedeAccederMision(auth()->user(), $misionesFlytbase)) {
            abort(403, 'No tiene permisos para modificar esta misión.');
        }

        try {
            $misionesFlytbase->update([
                'activo' => ! $misionesFlytbase->activo,
            ]);

            $status = $misionesFlytbase->activo ? 'activada' : 'desactivada';

            return redirect()->back()->with('success', "Misión {$status} exitosamente.");

        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de misión: '.$e->getMessage());

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
