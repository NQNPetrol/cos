<?php

namespace App\Http\Controllers;

use App\Models\FlytbaseDock;
use App\Models\FlytbaseDrone;
use App\Models\MisionFlytbase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FlytbaseDroneController extends Controller
{
    public function index(Request $request)
    {
        $drones = FlytbaseDrone::withCount(['misiones' => function ($query) {
            $query->where('activo', true);
        }])
            ->latest()
            ->get();

        $docks = FlytbaseDock::activos()->get();

        return view('drones-flytbase.index', compact('drones', 'docks'));
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
            'email_verified' => auth()->user()->hasVerifiedEmail(),
        ]);
        Log::debug('Parámetros recibidos:', [
            'mision_id' => $request->get('mision_id'),
            'droneName' => $droneName,
            'all_params' => $request->all(),
            'url_completa' => $request->fullUrl(),
        ]);

        // Si se proporciona mision_id, obtener el drone desde la misión
        $misionId = $request->get('mision_id');
        $mision = null;
        $drone = null;

        if ($misionId) {
            Log::debug('Buscando misión por ID:', ['mision_id' => $misionId]);
            $mision = MisionFlytbase::with('drone')->find($misionId);

            Log::debug('Resultado de búsqueda de misión:', [
                'mision_encontrada' => (bool) $mision,
                'mision_nombre' => $mision ? $mision->nombre : 'No encontrada',
                'tiene_drone' => $mision && $mision->drone ? 'Sí' : 'No',
            ]);

            if ($mision && $mision->drone) {
                $drone = $mision->drone;
                Log::debug('Drone encontrado desde misión:', [
                    'drone_id' => $drone->id,
                    'drone_nombre' => $drone->drone,
                    'share_url' => $drone->share_url ? 'Configurado' : 'No configurado',
                ]);
            }
        }

        // Si no se encontró drone por misión, buscar por nombre
        if (! $drone && $droneName) {
            Log::debug('Buscando drone por nombre:', ['droneName' => $droneName]);
            $drone = FlytbaseDrone::where('drone', $droneName)->first();
            Log::debug('Resultado de búsqueda por nombre:', [
                'drone_encontrado' => (bool) $drone,
                'drone_nombre' => $drone ? $drone->drone : 'No encontrado',
            ]);
        }

        // Si no se encuentra el drone, redirigir con error
        if (! $drone) {
            Log::warning('DRONE NO ENCONTRADO - Redirigiendo a alertas.index');

            return redirect()->route('alertas.index')
                ->with('error', 'Drone no encontrado o no configurado.');
        }

        Log::debug('Drone seleccionado:', [
            'id' => $drone->id,
            'nombre' => $drone->drone,
            'share_url' => $drone->share_url,
            'activo' => $drone->activo,
        ]);

        // Verificar si existe la vista
        $viewPath = $drone->liveview_view_path;
        Log::debug('Verificando vista:', [
            'view_path' => $viewPath,
            'view_exists' => view()->exists($viewPath) ? 'Sí' : 'No',
        ]);

        if (! $drone->hasLiveviewView()) {
            Log::warning('VISTA NO EXISTE - Redirigiendo a alertas.index', [
                'view_path' => $viewPath,
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
            'url_presente' => isset($viewData['flytbaseGuestUrl']),
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

        if (! $misionId) {
            return response()->json([
                'success' => false,
                'message' => 'ID de misión no proporcionado',
            ], 400);
        }

        $mision = MisionFlytbase::with('drone')->find($misionId);

        if (! $mision) {
            return response()->json([
                'success' => false,
                'message' => 'Misión no encontrada',
            ], 404);
        }

        if (! $mision->drone) {
            return response()->json([
                'success' => false,
                'message' => 'La misión no tiene un drone asignado',
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
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'drone' => 'required|string|max:255|unique:drones_flytbase,drone',
            'dock_id' => 'nullable|exists:flytbase_docks,id',
            'share_url' => 'required|url|max:500',
            'activo' => 'boolean',
        ]);

        try {
            FlytbaseDrone::create([
                'drone' => $request->drone,
                'dock_id' => $request->dock_id,
                'share_url' => $request->share_url,
                'activo' => $request->activo ?? true,
            ]);

            return redirect()->route('drones-flytbase.index')
                ->with('success', 'Drone creado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear el drone: '.$e->getMessage());
        }
    }

    /**
     * Actualizar un drone existente
     */
    public function update(Request $request, FlytbaseDrone $drones_flytbase)
    {
        $request->validate([
            'drone' => 'required|string|max:255|unique:drones_flytbase,drone,'.$drones_flytbase->id,
            'dock_id' => 'nullable|exists:flytbase_docks,id',
            'share_url' => 'required|url|max:500',
            'activo' => 'boolean',
        ]);

        try {
            $drones_flytbase->update([
                'drone' => $request->drone,
                'dock_id' => $request->dock_id,
                'share_url' => $request->share_url,
                'activo' => $request->activo ?? false,
            ]);

            return redirect()->route('drones-flytbase.index')
                ->with('success', 'Drone actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el drone: '.$e->getMessage());
        }
    }

    public function liveviewClient(Request $request, $droneName = null)
    {
        Log::debug('=== INICIANDO LIVEWVIEW CLIENT ===');
        $user = auth()->user();

        Log::debug('Usuario cliente autenticado:', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'roles' => $user->getRoleNames()->toArray(),
        ]);

        Log::debug('Parámetros recibidos:', [
            'mision_id' => $request->get('mision_id'),
            'droneName' => $droneName,
            'all_params' => $request->all(),
        ]);

        // Si se proporciona mision_id, obtener el drone desde la misión con filtro de cliente
        $misionId = $request->get('mision_id');
        $mision = null;
        $drone = null;

        if ($misionId) {
            Log::debug('Buscando misión por ID con filtro de cliente:', ['mision_id' => $misionId]);

            // Usar el scope porClienteUsuario para filtrar por cliente del usuario
            $mision = MisionFlytbase::activas()
                ->porClienteUsuario($user)
                ->with('drone')
                ->find($misionId);

            Log::debug('Resultado de búsqueda de misión para cliente:', [
                'mision_encontrada' => (bool) $mision,
                'mision_nombre' => $mision ? $mision->nombre : 'No encontrada o sin acceso',
                'tiene_drone' => $mision && $mision->drone ? 'Sí' : 'No',
            ]);

            if ($mision && $mision->drone) {
                $drone = $mision->drone;
                Log::debug('Drone encontrado desde misión:', [
                    'drone_id' => $drone->id,
                    'drone_nombre' => $drone->drone,
                    'share_url' => $drone->share_url ? 'Configurado' : 'No configurado',
                ]);
            }
        }

        if (! $drone && $droneName) {
            Log::debug('Buscando drone por nombre:', ['droneName' => $droneName]);
            $drone = FlytbaseDrone::where('drone', $droneName)->first();
            Log::debug('Resultado de búsqueda por nombre:', [
                'drone_encontrado' => (bool) $drone,
                'drone_nombre' => $drone ? $drone->drone : 'No encontrado',
            ]);
        }

        // Si no se encuentra el drone, redirigir con error
        if (! $drone) {
            Log::warning('DRONE NO ENCONTRADO O SIN ACCESO - Redirigiendo a client.alertas.index');

            return redirect()->route('client.alertas.index')
                ->with('error', 'Drone no encontrado o no tiene permisos para acceder.');
        }

        Log::debug('Drone seleccionado para cliente:', [
            'id' => $drone->id,
            'nombre' => $drone->drone,
            'share_url' => $drone->share_url,
            'activo' => $drone->activo,
        ]);

        // Verificar si existe la vista específica para clientes
        $viewPath = 'livestreaming.client.'.$drone->drone.'.liveview';
        Log::debug('Verificando vista para cliente:', [
            'view_path' => $viewPath,
            'view_exists' => view()->exists($viewPath) ? 'Sí' : 'No',
        ]);

        if (! view()->exists($viewPath)) {
            // Intentar con el nombre en minúsculas
            $viewPath = 'livestreaming.client.'.strtolower($drone->drone).'.liveview';
            Log::debug('Verificando vista en minúsculas:', [
                'view_path' => $viewPath,
                'view_exists' => view()->exists($viewPath) ? 'Sí' : 'No',
            ]);
        }

        if (! view()->exists($viewPath)) {
            // Intentar reemplazar espacios y caracteres especiales
            $cleanDroneName = str_replace([' ', '-'], '', $drone->drone);
            $viewPath = 'livestreaming.client.'.strtolower($cleanDroneName).'.liveview';
            Log::debug('Verificando vista limpia:', [
                'view_path' => $viewPath,
                'view_exists' => view()->exists($viewPath) ? 'Sí' : 'No',
            ]);
        }

        // Preparar datos para la vista
        $viewData = [
            'drone' => $drone,
            'mision' => $mision,
            'flytbaseGuestUrl' => $drone->share_url,
        ];

        Log::debug('Datos enviados a la vista cliente:', [
            'drone_presente' => isset($viewData['drone']),
            'mision_presente' => isset($viewData['mision']),
            'url_presente' => isset($viewData['flytbaseGuestUrl']),
        ]);

        Log::debug('=== RENDERIZANDO VISTA CLIENTE ===', ['view' => $viewPath]);

        // Renderizar la vista específica para clientes
        return view($viewPath, $viewData);
    }

    public function destroy(FlytbaseDrone $drones_flytbase)
    {
        try {
            // Verificar si el drone tiene misiones asociadas
            if ($drones_flytbase->misiones()->exists()) {
                return redirect()->back()->with('error', 'No se puede eliminar el drone porque tiene misiones asociadas.');
            }

            $drones_flytbase->delete();

            return redirect()->route('drones-flytbase.index')
                ->with('success', 'Drone eliminado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el drone: '.$e->getMessage());
        }
    }
}
