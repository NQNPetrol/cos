<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PeticionMisionFlytbase;
use App\Models\MisionFlytbase;
use App\Models\FlytbaseDrone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PeticionesMisionesClientes extends Component
{
    use WithPagination;

    public $showViewModal = false;
    public $selectedPeticion;

    // Filtros
    public $filtroEstado = '';

    public function mount()
    {
        Log::info('Componente PeticionesMisionesClientes montado');
    }

    public function getWaypointsCount($peticion)
    {
        if (empty($peticion->waypoints)) {
            return 'sin waypoints';
        }
        
        $waypoints = is_array($peticion->waypoints) ? $peticion->waypoints : json_decode($peticion->waypoints, true);
        
        if (empty($waypoints) || !is_array($waypoints)) {
            return 'sin waypoints';
        }
        
        return count($waypoints);
    }

    public function getAccionesUnicas($peticion)
    {
        if (empty($peticion->waypoints)) {
            return 'sin acciones';
        }
        
        $waypoints = is_array($peticion->waypoints) ? $peticion->waypoints : json_decode($peticion->waypoints, true);
        
        if (empty($waypoints) || !is_array($waypoints)) {
            return 'sin acciones';
        }
        
        $accionesUnicas = [];
        
        foreach ($waypoints as $waypoint) {
            if (isset($waypoint['acciones']) && is_array($waypoint['acciones'])) {
                foreach ($waypoint['acciones'] as $accion) {
                    if (!in_array($accion, $accionesUnicas)) {
                        $accionesUnicas[] = $accion;
                    }
                }
            }
        }
        
        if (empty($accionesUnicas)) {
            return 'sin acciones';
        }
        
        $accionesLegibles = array_map(function($accion) {
            return $this->getActionLabel($accion);
        }, $accionesUnicas);
        
        return implode(', ', $accionesLegibles);
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
        // Construir query base - MOSTRAR TODAS LAS PETICIONES
        $query = PeticionMisionFlytbase::with(['cliente', 'site', 'drone', 'dock', 'usuario', 'revisor'])
            ->orderBy('created_at', 'desc');

        // Aplicar filtros
        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }

        $peticiones = $query->paginate(10);

        return view('livewire.peticiones-misiones-clientes', compact('peticiones'));
    }

    public function verPeticion($id)
    {
        $this->selectedPeticion = PeticionMisionFlytbase::with(['cliente', 'site', 'drone', 'dock', 'usuario', 'revisor'])
            ->findOrFail($id);
        $this->showViewModal = true;
    }

    public function aprobarMision()
    {
        if (!$this->selectedPeticion || $this->selectedPeticion->estado !== 'pendiente') {
            session()->flash('error', 'La petición no está disponible o ya fue procesada.');
            return;
        }


        try {
            $drone = FlytbaseDrone::with('dock')->find($this->selectedPeticion->drone_id);
            if (!$drone) {
                Log::error('No hay drone asociado');
                session()->flash('error', 'No se pudo encontrar el drone asociado a esta petición.');
                return;
            }
            if (!$drone->dock_id) {
                Log::error('No hay dock asociado al dorne seleccionado');
                session()->flash('error', 'El drone seleccionado no tiene un dock asignado.');
                return;
            }
            // Crear la misión en la tabla misiones_flytbase
            $mision = MisionFlytbase::create([
                'nombre' => $this->selectedPeticion->nombre,
                'descripcion' => $this->selectedPeticion->descripcion,
                'cliente_id' => $this->selectedPeticion->cliente_id,
                'drone_id' => $this->selectedPeticion->drone_id,
                'dock_id' => $drone->dock_id,

                'site_id' => $this->selectedPeticion->site_id,
                'route_altitude' => $this->selectedPeticion->route_altitude,
                'route_speed' => $this->selectedPeticion->route_speed,
                'route_waypoint_type' => $this->selectedPeticion->route_waypoint_type,
                'waypoints' => $this->selectedPeticion->waypoints,
                'kmz_file_path' => $this->selectedPeticion->kmz_file_path,
                'observaciones' => $this->selectedPeticion->observaciones,
                'user_id' => $this->selectedPeticion->user_id,
                'url' => '', // Se completará manualmente por el admin
                'activo' => true
            ]);

            // Actualizar la petición original
            $this->selectedPeticion->update([
                'estado' => 'aprobada',
                'revisado_por' => Auth::id(),
                'mision_aprobada_id' => $mision->id
            ]);

            session()->flash('success', 'Misión aprobada correctamente. Se ha creado la misión #' . $mision->id);
            Log::info('Mision aceptada.');
            $this->showViewModal = false;

        } catch (\Exception $e) {
            Log::error('Error al aprobar misión: ' . $e->getMessage());
            session()->flash('error', 'Error al aprobar la misión: ' . $e->getMessage());
        }
    }

    public function rechazarMision()
    {
        if (!$this->selectedPeticion || $this->selectedPeticion->estado !== 'pendiente') {
            session()->flash('error', 'La petición no está disponible o ya fue procesada.');
            Log::info('Mision rechazada.');
            return;
        }

        // Actualizar la petición - SOLO CAMPOS QUE EXISTEN
        $this->selectedPeticion->update([
            'estado' => 'rechazada',
            'revisado_por' => Auth::id()
        ]);

        session()->flash('success', 'Misión rechazada correctamente.');
        $this->showViewModal = false;
    }

    public function eliminarPeticion($id)
    {
        $peticion = PeticionMisionFlytbase::findOrFail($id);
        $peticion->delete();

        session()->flash('success', 'Petición eliminada correctamente.');
    }

    public function closeModal()
    {
        $this->showViewModal = false;
        $this->selectedPeticion = null;
    }
}