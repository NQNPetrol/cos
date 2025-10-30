<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PeticionMisionFlytbase;
use App\Models\FlytbaseSite;
use App\Models\FlytbaseDrone;
use App\Models\FlytbaseDock;
use App\Models\Cliente;
use App\Models\UserCliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PeticionesMisionesClient extends Component
{
    use WithPagination;

    public $showCreateForm = false;
    public $showViewModal = false;
    public $selectedPeticion;

    // Datos del formulario
    public $nombre;
    public $descripcion;
    public $site_id;
    public $drone_id;
    public $route_altitude = 35.00;
    public $route_speed = 5.33;
    public $route_waypoint_type = 'linear_route';
    public $observaciones;
    public $waypoints = [];
            
    public $currentWaypointIndex = 0;
    public $showActionModal = false;

    // Filtros
    public $filtroEstado = '';

  

    public function mount()
    {
        Log::info('Componente PeticionesMisionesClient montado');
        $this->waypoints = [];
    }

    public function agregarWaypoint()
    {
        Log::info('Agregando waypoint');
        $this->waypoints[] = [
            'latitud' => '',
            'longitud' => '',
            'altitud' => $this->route_altitude,
            'acciones' => []
        ];
        
        // Si es el primer waypoint, establecer como current
        if (count($this->waypoints) === 1) {
            $this->currentWaypointIndex = 0;
        }
        Log::info('Waypoints después de agregar:', ['count' => count($this->waypoints), 'currentIndex' => $this->currentWaypointIndex]);
    }

    public function eliminarWaypoint($index)
    {
        Log::info('Eliminando waypoint', ['index' => $index, 'totalWaypoints' => count($this->waypoints)]);
        if (count($this->waypoints) > 0) {
            unset($this->waypoints[$index]);
            $this->waypoints = array_values($this->waypoints);
            
            // Ajustar el índice actual si es necesario
            if ($this->currentWaypointIndex >= count($this->waypoints)) {
                $this->currentWaypointIndex = max(0, count($this->waypoints) - 1);
            }
            
            // Si no hay waypoints, resetear el índice
            if (count($this->waypoints) === 0) {
                $this->currentWaypointIndex = 0;
            }
            Log::info('Waypoints después de eliminar:', ['count' => count($this->waypoints), 'currentIndex' => $this->currentWaypointIndex]);
        }
    }

    public function nextWaypoint()
    {
        Log::info('Navegando al siguiente waypoint', ['currentIndex' => $this->currentWaypointIndex, 'totalWaypoints' => count($this->waypoints)]);
        if ($this->currentWaypointIndex < count($this->waypoints) - 1) {
            $this->currentWaypointIndex++;
            Log::info('Nuevo índice de waypoint:', ['currentIndex' => $this->currentWaypointIndex]);
        } else {
            Log::info('No se puede navegar al siguiente - ya está en el último waypoint');
        }
    }

    public function previousWaypoint()
    {
        Log::info('Navegando al waypoint anterior', ['currentIndex' => $this->currentWaypointIndex]);
        if ($this->currentWaypointIndex > 0) {
            $this->currentWaypointIndex--;
            Log::info('Nuevo índice de waypoint:', ['currentIndex' => $this->currentWaypointIndex]);
        } else {
            Log::info('No se puede navegar al anterior - ya está en el primer waypoint');
        }
    }

    public function abrirModalAcciones()
    {
        Log::info('Abriendo modal de acciones', [
            'currentWaypointIndex' => $this->currentWaypointIndex,
            'totalWaypoints' => count($this->waypoints),
            'showActionModal' => $this->showActionModal
        ]);
        
        $this->showActionModal = true;
        
        Log::info('Modal de acciones abierto', ['showActionModal' => $this->showActionModal]);
        $this->dispatch('modal-opened');
    }

    public function cerrarModalAcciones()
    {
        Log::info('Cerrando modal de acciones');
        $this->showActionModal = false;
        $this->dispatch('modal-closed');
    }

    public function agregarAccion($accion)
    {
        Log::info('Agregando acción', [
            'accion' => $accion,
            'currentIndex' => $this->currentWaypointIndex,
            'waypointsCount' => count($this->waypoints)
        ]);

        if (!isset($this->waypoints[$this->currentWaypointIndex])) {
            Log::error('Waypoint actual no existe', ['currentIndex' => $this->currentWaypointIndex]);
            return;
        }

        $currentIndex = $this->currentWaypointIndex;
        
        if (!in_array($accion, $this->waypoints[$currentIndex]['acciones'])) {
            $this->waypoints[$currentIndex]['acciones'][] = $accion;
            Log::info('Acción agregada exitosamente', [
                'accion' => $accion,
                'accionesActuales' => $this->waypoints[$currentIndex]['acciones']
            ]);
            session()->flash('action-added', 'Acción agregada correctamente');
        } else {
            Log::info('La acción ya existe en el waypoint', ['accion' => $accion]);
            session()->flash('info', 'La acción ya está agregada en este waypoint');
        }
        
        $this->showActionModal = false;
        Log::info('Modal cerrado después de agregar acción');
    }

    public function eliminarAccion($waypointIndex, $accion)
    {
        Log::info('Eliminando acción', [
            'waypointIndex' => $waypointIndex,
            'accion' => $accion,
            'accionesAntes' => $this->waypoints[$waypointIndex]['acciones'] ?? []
        ]);

        if (isset($this->waypoints[$waypointIndex]['acciones'])) {
            $acciones = $this->waypoints[$waypointIndex]['acciones'];
            $this->waypoints[$waypointIndex]['acciones'] = array_values(array_diff($acciones, [$accion]));
            Log::info('Acción eliminada exitosamente', [
                'accionesDespues' => $this->waypoints[$waypointIndex]['acciones']
            ]);
        } else {
            Log::warning('No se encontraron acciones para eliminar en el waypoint', ['waypointIndex' => $waypointIndex]);
        }
    }

    public function getActionLabel($action)
    {
        $acciones = [
            'take_thermal_image' => 'Capturar Imagen Térmica',
            'take_wide_image' => 'Capturar Imagen Angular',
            'take_panorama_image' => 'Capturar Imagen Panoramica',
            'start_recording' => 'Iniciar Grabación',
            'stop_recording' => 'Detener Grabación',
            'zoom_in' => 'Activar Zoom',
            'set_gimbal_90' => 'Rotar Camara a 90°',
            'set_gimbal_45' => 'Rotar Camara 45°',
        ];
        
        return $acciones[$action] ?? $action;
    }

    public function render()
    {
        Log::info('Renderizando componente', [
            'showActionModal' => $this->showActionModal,
            'currentWaypointIndex' => $this->currentWaypointIndex,
            'waypointsCount' => count($this->waypoints)
        ]);

        $user = Auth::user();
        
        // Obtener peticiones del usuario cliente
        $query = PeticionMisionFlytbase::where('user_id', $user->id)
            ->with(['cliente', 'site', 'drone', 'dock'])
            ->orderBy('created_at', 'desc');

        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }

        $peticiones = $query->paginate(10);

        // Datos para el formulario
        $sites = FlytbaseSite::activos()->get();
        $drones = FlytbaseDrone::activos()->get();
        $cliente = $user->cliente;

        return view('livewire.peticiones-misiones-client', compact('peticiones', 'sites', 'drones', 'cliente'));
    }

    public function crearPeticion()
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'site_id' => 'required|exists:flytbase_sites,id',
            'drone_id' => 'required|exists:drones_flytbase,id',
            'route_altitude' => 'required|numeric|min:10|max:120',
            'route_speed' => 'required|numeric|min:1|max:15',
            'route_waypoint_type' => 'required|in:linear_route,transits_waypoint,curved_route_drone_stops,curved_route_drone_continues',
            'waypoints' => 'required|array|min:1',
            'waypoints.*.latitud' => 'required|numeric|between:-90,90',
            'waypoints.*.longitud' => 'required|numeric|between:-180,180',
            'waypoints.*.altitud' => 'required|numeric|min:0|max:120',
        ]);

        $user = Auth::user();
        $userCliente = UserCliente::where('user_id', $user->id)->first();

        // Obtener dock basado en el drone seleccionado
        $drone = FlytbaseDrone::activos()->find($this->drone_id);
        $site = FlytbaseSite::activos()->find($this->site_id);

        if (!$drone) {
            session()->flash('error', 'El drone seleccionado no está disponible o no está activo.');
            return;
        }

        if (!$site) {
            session()->flash('error', 'El site seleccionado no está disponible o no está activo.');
            return;
        }

        $dock = FlytbaseDock::activos()->where('flytbase_site_id', $this->site_id)->first();

        if (!$dock) {
            session()->flash('error', 'No hay un dock activo disponible para el site seleccionado.');
            return;
        }

        $peticion = PeticionMisionFlytbase::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'cliente_id' => $userCliente->cliente_id,
            'drone_id' => $this->drone_id,
            'site_id' => $this->site_id,
            'route_altitude' => $this->route_altitude,
            'route_speed' => $this->route_speed,
            'route_waypoint_type' => $this->route_waypoint_type,
            'waypoints' => $this->waypoints,
            'observaciones' => $this->observaciones,
            'user_id' => $user->id,
            'estado' => 'pendiente'
        ]);

        // TODO: Enviar notificación a operadores

        $this->resetForm();
        $this->showCreateForm = false;

        session()->flash('success', 'Petición de misión creada correctamente. Será revisada por un operador.');
    }

    public function verPeticion($id)
    {
        $this->selectedPeticion = PeticionMisionFlytbase::with(['cliente', 'site', 'drone', 'dock', 'usuario', 'revisor'])
            ->findOrFail($id);
        $this->showViewModal = true;
    }

    public function anularPeticion($id)
    {
        $peticion = PeticionMisionFlytbase::where('user_id', Auth::id())
            ->where('estado', 'pendiente')
            ->findOrFail($id);

        $peticion->update(['estado' => 'rechazada']);

        session()->flash('success', 'Petición anulada correctamente.');
    }


    public function toggleAccion($waypointIndex, $accion)
    {
        $acciones = $this->waypoints[$waypointIndex]['acciones'] ?? [];
        
        if (in_array($accion, $acciones)) {
            $acciones = array_diff($acciones, [$accion]);
        } else {
            $acciones[] = $accion;
        }

        $this->waypoints[$waypointIndex]['acciones'] = array_values($acciones);
    }

    private function resetForm()
    {
        $this->reset([
            'nombre',
            'descripcion',
            'site_id',
            'drone_id',
            'route_altitude',
            'route_speed',
            'route_waypoint_type',
            'observaciones'
        ]);

        $this->waypoints = [
            [
                'latitud' => '',
                'longitud' => '',
                'altitud' => $this->route_altitude,
                'acciones' => []
            ]
        ];
    }
}