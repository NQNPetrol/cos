<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recorrido;
use App\Models\SupervisorEmpresaAsociada;
use App\Models\Personal;

class RecorridosController extends Controller
{
    /**
     * Display the recorridos management view.
     */
    public function index()
    {
        $user = auth()->user();
        $cliente = $user->clientes()->first();

        if (!$cliente) {
            abort(403, 'No tiene un cliente asociado.');
        }

        $personal = $user->personal;

        // Get empresas assigned to this supervisor
        $empresaIds = [];
        if ($personal) {
            $empresaIds = SupervisorEmpresaAsociada::where('supervisor_id', $personal->id)
                ->pluck('empresa_asociada_id')
                ->toArray();
        }

        // If user is clientadmin, show all recorridos of the client
        if ($user->hasRole('clientadmin')) {
            $recorridos = Recorrido::where('cliente_id', $cliente->id)
                ->with(['empresaAsociada', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();

            $empresasDisponibles = $cliente->empresasAsociadas;
        } else {
            // Supervisor sees only recorridos for their assigned empresas
            $recorridos = Recorrido::where('cliente_id', $cliente->id)
                ->whereIn('empresa_asociada_id', $empresaIds)
                ->with(['empresaAsociada', 'user'])
                ->orderBy('created_at', 'desc')
                ->get();

            $empresasDisponibles = $personal
                ? $personal->empresasAsociadas
                : collect();
        }

        $tienePersonal = $personal !== null;

        return view('recorridos.index', compact(
            'recorridos',
            'empresasDisponibles',
            'tienePersonal',
            'cliente'
        ));
    }

    /**
     * Store a new recorrido.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'empresa_asociada_id' => 'required|exists:empresas_asociadas,id',
            'objetivos' => 'nullable|string|max:500',
            'velocidadmax_permitida' => 'nullable|integer|min:1|max:300',
            'duracion_promedio' => 'nullable|integer|min:1',
            'kml_file' => 'nullable|file|max:5120',
        ]);

        $user = auth()->user();
        $cliente = $user->clientes()->first();
        $personal = $user->personal;

        if (!$cliente) {
            return response()->json(['error' => 'No tiene un cliente asociado.'], 403);
        }

        // Verify supervisor has this empresa assigned (unless clientadmin)
        if (!$user->hasRole('clientadmin')) {
            if (!$personal) {
                return response()->json(['error' => 'Debe tener un registro de personal asignado.'], 422);
            }

            $tieneEmpresa = SupervisorEmpresaAsociada::where('supervisor_id', $personal->id)
                ->where('empresa_asociada_id', $request->empresa_asociada_id)
                ->exists();

            if (!$tieneEmpresa) {
                return response()->json(['error' => 'No tiene permisos para crear recorridos en este cliente.'], 403);
            }
        }

        $data = [
            'cliente_id' => $cliente->id,
            'empresa_asociada_id' => $request->empresa_asociada_id,
            'user_id' => $user->id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'objetivos' => $request->objetivos,
            'velocidadmax_permitida' => $request->velocidadmax_permitida,
            'duracion_promedio' => $request->duracion_promedio,
        ];

        // Handle KML file
        if ($request->hasFile('kml_file')) {
            $parsed = Recorrido::parseKmlFile($request->file('kml_file'));
            $data['waypoints'] = $parsed['waypoints'];
            $data['longitud_mts'] = $parsed['longitud_mts'];

            // Store metadata inside waypoints JSON
            if (!empty($parsed['metadata'])) {
                $data['waypoints'] = [
                    'points' => $parsed['waypoints'],
                    'metadata' => $parsed['metadata'],
                ];
            }
        }

        $recorrido = Recorrido::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Recorrido creado correctamente.',
            'recorrido' => $recorrido->load('empresaAsociada'),
        ]);
    }

    /**
     * Show recorrido details (JSON for modal).
     */
    public function show(Recorrido $recorrido)
    {
        $user = auth()->user();
        $cliente = $user->clientes()->first();

        if (!$cliente || $recorrido->cliente_id !== $cliente->id) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }

        return response()->json([
            'recorrido' => $recorrido->load(['empresaAsociada', 'user']),
        ]);
    }

    /**
     * Update a recorrido.
     */
    public function update(Request $request, Recorrido $recorrido)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'objetivos' => 'nullable|string|max:500',
            'velocidadmax_permitida' => 'nullable|integer|min:1|max:300',
            'duracion_promedio' => 'nullable|integer|min:1',
            'kml_file' => 'nullable|file|max:5120',
        ]);

        $user = auth()->user();
        $cliente = $user->clientes()->first();

        if (!$cliente || $recorrido->cliente_id !== $cliente->id) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }

        $data = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'objetivos' => $request->objetivos,
            'velocidadmax_permitida' => $request->velocidadmax_permitida,
            'duracion_promedio' => $request->duracion_promedio,
        ];

        // Handle KML file if new one uploaded
        if ($request->hasFile('kml_file')) {
            $parsed = Recorrido::parseKmlFile($request->file('kml_file'));
            $data['waypoints'] = [
                'points' => $parsed['waypoints'],
                'metadata' => $parsed['metadata'],
            ];
            $data['longitud_mts'] = $parsed['longitud_mts'];
        }

        $recorrido->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Recorrido actualizado correctamente.',
            'recorrido' => $recorrido->fresh()->load('empresaAsociada'),
        ]);
    }

    /**
     * Delete a recorrido.
     */
    public function destroy(Recorrido $recorrido)
    {
        $user = auth()->user();
        $cliente = $user->clientes()->first();

        if (!$cliente || $recorrido->cliente_id !== $cliente->id) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }

        $recorrido->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recorrido eliminado correctamente.',
        ]);
    }

    /**
     * Import a KML file and return parsed waypoints (preview).
     */
    public function importKml(Request $request)
    {
        $request->validate([
            'kml_file' => 'required|file|max:5120',
        ]);

        $parsed = Recorrido::parseKmlFile($request->file('kml_file'));

        return response()->json([
            'success' => true,
            'data' => $parsed,
        ]);
    }
}
