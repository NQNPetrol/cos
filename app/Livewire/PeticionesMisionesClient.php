<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PeticionMisionFlytbase;
use App\Models\FlytbaseSite;
use App\Models\FlytbaseDrone;
use App\Models\FlytbaseDock;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;

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

    // Filtros
    public $filtroEstado = '';

    protected $queryString = [
        'filtroEstado' => ['except' => '']
    ];

    public function mount()
    {
        $this->waypoints = [
            [
                'latitud' => '',
                'longitud' => '',
                'altitud' => '',
                'acciones' => []
            ]
        ];
    }

    public function render()
    {
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
        $sites = FlytbaseSite::where('activo', true)->get();
        $drones = FlytbaseDrone::where('activo', true)->get();
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
        $cliente = $user->cliente;

        // Obtener dock basado en el drone seleccionado
        $drone = FlytbaseDrone::find($this->drone_id);
        $dock = $drone ? FlytbaseDock::where('activo', true)->first() : null;

        $peticion = PeticionMisionFlytbase::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'cliente_id' => $cliente->id,
            'drone_id' => $this->drone_id,
            'dock_id' => $dock?->id,
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

    public function agregarWaypoint()
    {
        $this->waypoints[] = [
            'latitud' => '',
            'longitud' => '',
            'altitud' => $this->route_altitude,
            'acciones' => []
        ];
    }

    public function eliminarWaypoint($index)
    {
        if (count($this->waypoints) > 1) {
            unset($this->waypoints[$index]);
            $this->waypoints = array_values($this->waypoints);
        }
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