<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlytbaseDrone;
use App\Models\MisionFlytbase;
use Illuminate\Support\Facades\Log;

class FlytbaseDroneController extends Controller
{

    public function index(Request $request)
    {
        $drones = FlytbaseDrone::withCount(['misiones' => function($query) {
            $query->where('activo', true);
        }])
        ->latest()
        ->get();

        return view('drones-flytbase.index', compact('drones'));
    }

    /**
     * Mostrar el liveview de un drone específico
     */
    public function liveview(Request $request, $droneName = null)
    {
        Log::debug('=== INICIANDO LIVEWVIEW ===');
        \Log::debug('Usuario autenticado:', [
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'email_verified' => auth()->user()->hasVerifiedEmail()
        ]);
        Log::debug('Parámetros recibidos:', [
            'mision_id' => $request->get('mision_id'),
            'droneName' => $droneName,
            'all_params' => $request->all(),
            'url_completa' => $request->fullUrl()
        ]);

        // Si se proporciona mision_id, obtener el drone desde la misión
        $misionId = $request->get('mision_id');
        $mision = null;
        $drone = null;

        if ($misionId) {
            Log::debug('Buscando misión por ID:', ['mision_id' => $misionId]);
            $mision = MisionFlytbase::with('drone')->find($misionId);
            
            Log::debug('Resultado de búsqueda de misión:', [
                'mision_encontrada' => !!$mision,
                'mision_nombre' => $mision ? $mision->nombre : 'No encontrada',
                'tiene_drone' => $mision && $mision->drone ? 'Sí' : 'No'
            ]);

            if ($mision && $mision->drone) {
                $drone = $mision->drone;
                Log::debug('Drone encontrado desde misión:', [
                    'drone_id' => $drone->id,
                    'drone_nombre' => $drone->drone,
                    'share_url' => $drone->share_url ? 'Configurado' : 'No configurado'
                ]);
            }
        }

        // Si no se encontró drone por misión, buscar por nombre
        if (!$drone && $droneName) {
            Log::debug('Buscando drone por nombre:', ['droneName' => $droneName]);
            $drone = FlytbaseDrone::where('drone', $droneName)->first();
            Log::debug('Resultado de búsqueda por nombre:', [
                'drone_encontrado' => !!$drone,
                'drone_nombre' => $drone ? $drone->drone : 'No encontrado'
            ]);
        }

        // Si no se encuentra el drone, redirigir con error
        if (!$drone) {
            Log::warning('DRONE NO ENCONTRADO - Redirigiendo a alertas.index');
            return redirect()->route('alertas.index')
                ->with('error', 'Drone no encontrado o no configurado.');
        }

        Log::debug('Drone seleccionado:', [
            'id' => $drone->id,
            'nombre' => $drone->drone,
            'share_url' => $drone->share_url,
            'activo' => $drone->activo
        ]);

        // Verificar si existe la vista
        $viewPath = $drone->liveview_view_path;
        Log::debug('Verificando vista:', [
            'view_path' => $viewPath,
            'view_exists' => view()->exists($viewPath) ? 'Sí' : 'No'
        ]);

        if (!$drone->hasLiveviewView()) {
            Log::warning('VISTA NO EXISTE - Redirigiendo a alertas.index', [
                'view_path' => $viewPath
            ]);
            return redirect()->route('alertas.index')
                ->with('error', "Vista de liveview no encontrada para el drone: {$drone->drone}");
        }

        // Preparar datos para la vista
        $viewData = [
            'drone' => $drone,
            'mision' => $mision,
            'flytbaseGuestUrl' => $drone->share_url,
        ];

        Log::debug('Datos enviados a la vista:', [
            'drone_presente' => isset($viewData['drone']),
            'mision_presente' => isset($viewData['mision']),
            'url_presente' => isset($viewData['flytbaseGuestUrl'])
        ]);

        Log::debug('=== RENDERIZANDO VISTA ===', ['view' => $viewPath]);
        \Log::debug('=== RENDERIZANDO VISTA - ANTES DEL RETURN ===');

        // Renderizar la vista específica del drone
        return view($viewPath, $viewData);
    }

    /**
     * Obtener información del drone para una misión (API)
     */
    public function getDroneInfo(Request $request)
    {
        $misionId = $request->get('mision_id');
        
        if (!$misionId) {
            return response()->json([
                'success' => false,
                'message' => 'ID de misión no proporcionado'
            ], 400);
        }

        $mision = MisionFlytbase::with('drone')->find($misionId);

        if (!$mision) {
            return response()->json([
                'success' => false,
                'message' => 'Misión no encontrada'
            ], 404);
        }

        if (!$mision->drone) {
            return response()->json([
                'success' => false,
                'message' => 'La misión no tiene un drone asignado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'drone' => [
                'id' => $mision->drone->id,
                'name' => $mision->drone->drone,
                'has_liveview' => $mision->drone->hasLiveviewView(),
                'liveview_route' => $mision->drone->liveview_route,
            ],
            'mision' => [
                'id' => $mision->id,
                'nombre' => $mision->nombre,
            ]
        ]);
    }
}