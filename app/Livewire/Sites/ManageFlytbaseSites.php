<?php

namespace App\Livewire\Sites;

use App\Models\Cliente;
use App\Models\FlytbaseDock;
use App\Models\FlytbaseDrone;
use App\Models\FlytbaseOrg;
use App\Models\FlytbaseSite;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ManageFlytbaseSites extends Component
{
    use WithPagination;

    public $search = '';

    public $clienteFilter = '';

    public $dockDroneFilter = '';

    public $activoFilter = '';

    // Modal states
    public $showModal = false;

    public $editing = false;

    public $siteId = null;

    // Form fields
    public $nombre = '';

    public $descripcion = '';

    public $cliente_id = '';

    public $latitud = '';

    public $longitud = '';

    public $selectedDocks = [];

    public $selectedDrones = [];

    public $organization_id = '';

    public $selectedMembers = [];

    public $activo = true;

    // Lists for dropdowns
    public $clientes = [];

    public $organizations = [];

    public $users = [];

    public $availableDocks = [];

    public $availableDrones = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'clienteFilter' => ['except' => ''],
        'dockDroneFilter' => ['except' => ''],
        'activoFilter' => ['except' => ''],
    ];

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string|max:255',
        'cliente_id' => 'required|exists:clientes,id',
        'latitud' => 'required|numeric|between:-90,90',
        'longitud' => 'required|numeric|between:-180,180',
        'organization_id' => 'nullable|exists:flytbase_organizations,id',
        'activo' => 'boolean',
    ];

    public function mount()
    {
        $this->loadFormData();
    }

    public function loadFormData()
    {
        $this->clientes = Cliente::orderBy('nombre')->get();
        $this->organizations = FlytbaseOrg::orderBy('nombre')->get();
        $this->users = User::orderBy('name')->get();
        $this->loadAvailableDocks();
        $this->loadAvailableDrones();
    }

    public function loadAvailableDocks()
    {
        // Obtener todos los docks activos
        $allDocks = FlytbaseDock::where('active', true)->orderBy('nombre')->get();

        // Si estamos editando, excluir los docks que ya están asignados a otros sites
        if ($this->editing && $this->siteId) {
            $assignedDockIds = $this->getAssignedDockIdsExcludingCurrent();
            $this->availableDocks = $allDocks->filter(function ($dock) use ($assignedDockIds) {
                return ! in_array($dock->id, $assignedDockIds);
            });
        } else {
            // Para creación, excluir todos los docks ya asignados
            $assignedDockIds = $this->getAllAssignedDockIds();
            $this->availableDocks = $allDocks->filter(function ($dock) use ($assignedDockIds) {
                return ! in_array($dock->id, $assignedDockIds);
            });
        }
    }

    public function loadAvailableDrones()
    {
        // Obtener todos los drones activos
        $allDrones = FlytbaseDrone::where('activo', true)->orderBy('drone')->get();

        // Si estamos editando, excluir los drones que ya están asignados a otros sites
        if ($this->editing && $this->siteId) {
            $assignedDroneIds = $this->getAssignedDroneIdsExcludingCurrent();
            $this->availableDrones = $allDrones->filter(function ($drone) use ($assignedDroneIds) {
                return ! in_array($drone->id, $assignedDroneIds);
            });
        } else {
            // Para creación, excluir todos los drones ya asignados
            $assignedDroneIds = $this->getAllAssignedDroneIds();
            $this->availableDrones = $allDrones->filter(function ($drone) use ($assignedDroneIds) {
                return ! in_array($drone->id, $assignedDroneIds);
            });
        }
    }

    private function getAllAssignedDockIds(): array
    {
        $assignedDockIds = [];
        $sites = FlytbaseSite::whereNotNull('devices')->get();

        foreach ($sites as $site) {
            if (is_array($site->devices)) {
                foreach ($site->devices as $device) {
                    if (isset($device['dock_id'])) {
                        $assignedDockIds[] = $device['dock_id'];
                    }
                }
            }
        }

        return array_unique($assignedDockIds);
    }

    private function getAllAssignedDroneIds(): array
    {
        $assignedDroneIds = [];
        $sites = FlytbaseSite::whereNotNull('devices')->get();

        foreach ($sites as $site) {
            if (is_array($site->devices)) {
                foreach ($site->devices as $device) {
                    if (isset($device['drone_id'])) {
                        $assignedDroneIds[] = $device['drone_id'];
                    }
                }
            }
        }

        return array_unique($assignedDroneIds);
    }

    private function getAssignedDockIdsExcludingCurrent(): array
    {
        $assignedDockIds = [];
        $sites = FlytbaseSite::where('id', '!=', $this->siteId)
            ->whereNotNull('devices')
            ->get();

        foreach ($sites as $site) {
            if (is_array($site->devices)) {
                foreach ($site->devices as $device) {
                    if (isset($device['dock_id'])) {
                        $assignedDockIds[] = $device['dock_id'];
                    }
                }
            }
        }

        return array_unique($assignedDockIds);
    }

    private function getAssignedDroneIdsExcludingCurrent(): array
    {
        $assignedDroneIds = [];
        $sites = FlytbaseSite::where('id', '!=', $this->siteId)
            ->whereNotNull('devices')
            ->get();

        foreach ($sites as $site) {
            if (is_array($site->devices)) {
                foreach ($site->devices as $device) {
                    if (isset($device['drone_id'])) {
                        $assignedDroneIds[] = $device['drone_id'];
                    }
                }
            }
        }

        return array_unique($assignedDroneIds);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingClienteFilter()
    {
        $this->resetPage();
    }

    public function updatingDockDroneFilter()
    {
        $this->resetPage();
    }

    public function updatingActivoFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editing = false;
        $this->showModal = true;
        $this->loadAvailableDocks();
        $this->loadAvailableDrones();
    }

    public function openEditModal($siteId)
    {
        $site = FlytbaseSite::findOrFail($siteId);

        $this->siteId = $site->id;
        $this->nombre = $site->nombre;
        $this->descripcion = $site->descripcion;
        $this->cliente_id = $site->cliente_id;
        $this->latitud = $site->location[0] ?? '';
        $this->longitud = $site->location[1] ?? '';
        $this->organization_id = $site->organization_id;
        $this->activo = $site->activo;

        // Load selected docks and drones
        $this->selectedDocks = [];
        $this->selectedDrones = [];
        if (is_array($site->devices)) {
            foreach ($site->devices as $device) {
                $this->selectedDocks[] = $device['dock_id'];
                $this->selectedDrones[] = $device['drone_id'];
            }
        }

        // Load selected members
        $this->selectedMembers = is_array($site->members) ? $site->members : [];

        $this->editing = true;
        $this->showModal = true;

        // Reload available docks and drones for editing
        $this->loadAvailableDocks();
        $this->loadAvailableDrones();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'siteId', 'nombre', 'descripcion', 'cliente_id',
            'latitud', 'longitud', 'selectedDocks', 'selectedDrones',
            'organization_id', 'selectedMembers', 'activo',
        ]);
    }

    public function saveSite()
    {
        $this->validate();

        // Validate that selected docks and drones have the same count
        if (count($this->selectedDocks) !== count($this->selectedDrones)) {
            $this->addError('selectedDocks', 'La cantidad de docks seleccionados debe coincidir con la cantidad de drones seleccionados.');
            $this->addError('selectedDrones', 'La cantidad de drones seleccionados debe coincidir con la cantidad de docks seleccionados.');

            return;
        }

        // Build devices array
        $devices = [];
        for ($i = 0; $i < count($this->selectedDocks); $i++) {
            $devices[] = [
                'dock_id' => $this->selectedDocks[$i],
                'drone_id' => $this->selectedDrones[$i],
            ];
        }

        $siteData = [
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'cliente_id' => $this->cliente_id,
            'location' => [$this->latitud, $this->longitud],
            'devices' => $devices,
            'organization_id' => $this->organization_id ?: null,
            'members' => $this->selectedMembers,
            'activo' => $this->activo,
        ];

        if ($this->editing) {
            FlytbaseSite::find($this->siteId)->update($siteData);
            session()->flash('success', 'Sitio actualizado correctamente.');
        } else {
            FlytbaseSite::create($siteData);
            session()->flash('success', 'Sitio creado correctamente.');
        }

        $this->closeModal();
    }

    public function deleteSite($siteId)
    {
        $site = FlytbaseSite::findOrFail($siteId);
        $site->delete();

        session()->flash('success', 'Sitio eliminado correctamente.');
    }

    public function render()
    {
        $sites = FlytbaseSite::with(['cliente', 'organization'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%'.$this->search.'%')
                        ->orWhere('descripcion', 'like', '%'.$this->search.'%')
                        ->orWhereHas('cliente', function ($q2) {
                            $q2->where('nombre', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->clienteFilter, function ($query) {
                $query->where('cliente_id', $this->clienteFilter);
            })
            ->when($this->dockDroneFilter, function ($query) {
                $dockDronePair = explode('-', $this->dockDroneFilter);
                if (count($dockDronePair) === 2) {
                    $dockId = (int) $dockDronePair[0];
                    $droneId = (int) $dockDronePair[1];

                    $query->where(function ($q) use ($dockId, $droneId) {
                        $q->whereJsonContains('devices', ['dock_id' => $dockId, 'drone_id' => $droneId]);
                    });
                }
            })
            ->when($this->activoFilter !== '', function ($query) {
                $query->where('activo', $this->activoFilter);
            })
            ->orderBy('nombre')
            ->paginate(10);

        $dockDronePairs = $this->getDockDronePairs();

        return view('livewire.sites.manage-flytbase-sites', [
            'sites' => $sites,
            'dockDronePairs' => $dockDronePairs,
        ]);
    }

    private function getDockDronePairs()
    {
        $sites = FlytbaseSite::whereNotNull('devices')->get();
        $pairs = [];

        foreach ($sites as $site) {
            if (is_array($site->devices)) {
                foreach ($site->devices as $device) {
                    if (isset($device['dock_id']) && isset($device['drone_id'])) {
                        $dock = FlytbaseDock::find($device['dock_id']);
                        $drone = FlytbaseDrone::find($device['drone_id']);

                        if ($dock && $drone) {
                            $key = $device['dock_id'].'-'.$device['drone_id'];
                            $pairs[$key] = $dock->nombre.' - '.$drone->drone;
                        }
                    }
                }
            }
        }

        asort($pairs);

        return $pairs;
    }

    public function getCantidadDispositivos($devices)
    {
        if (is_array($devices)) {
            return count($devices) * 2;
        }

        return 0;
    }

    public function getCantidadMiembros($members)
    {
        if (is_array($members)) {
            return count($members);
        }

        return 0;
    }

    public function clearAllFilters()
    {
        $this->reset(['search', 'clienteFilter', 'dockDroneFilter', 'activoFilter']);
        $this->resetPage();
    }
}
