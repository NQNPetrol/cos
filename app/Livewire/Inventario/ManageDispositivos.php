<?php

namespace App\Livewire\Inventario;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Dispositivo;
use App\Models\Cliente;

class ManageDispositivos extends Component
{
    use WithPagination;

    //propiedades para el modal
    public $showModal = false;
    public $editingId = null;

    // Propiedades para filtros
    public $search = '';
    public $statusFilter = '';
    public $clienteFilter = '';
    public $estadoInventarioFilter = '';
    public $deviceTypeFilter = '';
    public $mantenimientoFilter = '';
    public $actualizacionFilter = '';

    // Propiedades para el formulario
    public $tipo = '';
    public $puerto = '8000';
 
    public $version_software = '';
    public $direccion_ip = '';
    public $fecha_instalacion = '';
    public $estado_hikconnect = 'Conectado';
    public $cliente_id = '';
    public $ubicacion = '';
    public $observaciones = '';
    public $necesita_mantenimiento = false;
    public $necesita_actualizacion = false;
    public $ultimo_mantenimiento = '';
    public $proximo_mantenimiento = '';
    public $estado_inventario = 'En stock';
    public $clientes;

    protected $rules = [
        'tipo' => 'required|in:cámara_ip,nvr_dvr,control_acceso,intercomunicador,switch_poe,sensor_alarma,dispositivo_reconocimiento,gps,otros',
        'direccion_ip' => 'nullable|ip',
        'puerto' => 'nullable|string|max:10',
        'version_software' => 'nullable|string|max:255',
        'cliente_id' => 'nullable|exists:clientes,id',
        'ubicacion' => 'nullable|string|max:255',
        'observaciones' => 'nullable|string',
        'fecha_instalacion' => 'nullable|date',
        'ultimo_mantenimiento' => 'nullable|date',
        'proximo_mantenimiento' => 'nullable|date|after_or_equal:today',
        'estado_hikconnect' => 'nullable|in:Conectado, Por Conectar',
        'estado_inventario' => 'required|in:En stock,Instalado,En mantenimiento,Dado de baja'
    ];

    public function mount()
    {
        $this->clientes = Cliente::all();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingClienteFilter()
    {
        $this->resetPage();
    }

    public function updatingEstadoInventarioFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->clienteFilter = '';
        $this->estadoInventarioFilter = '';
        $this->deviceTypeFilter = '';
        $this->mantenimientoFilter = '';
        $this->actualizacionFilter = '';
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'tipo' => $this->tipo,
            'direccion_ip' => $this->direccion_ip,
            'puerto' => $this->puerto,
            'version_software' => $this->version_software,
            'estado_hikconnect' => $this->estado_hikconnect,
            'cliente_id' => $this->cliente_id ?: null,
            'ubicacion' => $this->ubicacion,
            'observaciones' => $this->observaciones,
            'necesita_mantenimiento' => $this->necesita_mantenimiento,
            'necesita_actualizacion' => $this->necesita_actualizacion,
            'fecha_instalacion' => $this->fecha_instalacion ?: null,
            'ultimo_mantenimiento' => $this->ultimo_mantenimiento ?: null,
            'proximo_mantenimiento' => $this->proximo_mantenimiento ?: null,
            'estado_inventario' => $this->estado_inventario,
        ];

        if ($this->editingId) {
            Dispositivo::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Dispositivo actualizado correctamente.');
            $message = 'Dispositivo actualizado correctamente.';
        } else {
            Dispositivo::create($data);
            session()->flash('success', 'Dispositivo creado correctamente.');
            $message = 'Dispositivo creado correctamente.';
        }

        $this->closeModal();
        session()->flash('success', $message);
    }

    public function edit($id)
    {
        $dispositivo = Dispositivo::findOrFail($id);
        
        $this->editingId = $dispositivo->id;
        $this->tipo = $dispositivo->tipo;
        $this->puerto = $dispositivo->puerto;
        $this->version_software = $dispositivo->version_software;
        $this->estado_hikconnect = $dispositivo->estado_hikconnect;
        $this->cliente_id = $dispositivo->cliente_id;
        $this->ubicacion = $dispositivo->ubicacion;
        $this->observaciones = $dispositivo->observaciones;
        $this->necesita_mantenimiento = $dispositivo->necesita_mantenimiento;
        $this->necesita_actualizacion = $dispositivo->necesita_actualizacion;
        $this->fecha_instalacion = $dispositivo->fecha_instalacion ? $dispositivo->fecha_instalacion->format('Y-m-d') : '';
        $this->ultimo_mantenimiento = $dispositivo->ultimo_mantenimiento ? $dispositivo->ultimo_mantenimiento->format('Y-m-d') : '';
        $this->proximo_mantenimiento = $dispositivo->proximo_mantenimiento ? $dispositivo->proximo_mantenimiento->format('Y-m-d') : '';
        $this->estado_inventario = $dispositivo->estado_inventario;

        $this->showModal = true;
    }

    public function delete($id)
    {
        Dispositivo::findOrFail($id)->delete();
        session()->flash('success', 'Dispositivo eliminado correctamente.');
    }

    public function resetForm()
    {
        $this->reset([
            'tipo', 'cliente_id', 'ubicacion', 'fecha_instalacion', 
            'observaciones', 'direccion_ip', 'puerto', 'version_software',
            'estado_hikconnect', 'necesita_actualizacion', 'necesita_mantenimiento',
            'ultimo_mantenimiento', 'proximo_mantenimiento', 'estado_inventario'
        ]);
    }

    public function render()
    {
        $query = Dispositivo::query()->with('cliente');

        // Aplicar filtros
        if ($this->search) {
            $query->where(function($q) {
                $q->where('tipo', 'like', '%' . $this->search . '%')
                  ->orWhere('tipo', 'like', '%' . $this->search . '%')
                  ->orWhere('ubicacion', 'like', '%' . $this->search . '%')
                   ->orWhere('modelo', 'like', '%' . $this->search . '%')
                   ->orWhereHas('cliente', function($subQuery) {
                        $subQuery->where('nombre', 'like', '%' . $this->search . '%');
                  });
            });
        }


        if ($this->clienteFilter) {
            $query->where('cliente_id', $this->clienteFilter);
        }

        if ($this->estadoInventarioFilter) {
            $query->where('estado_inventario', $this->estadoInventarioFilter);
        }

        if ($this->deviceTypeFilter) {
            $query->where('tipo', 'like', '%' . $this->deviceTypeFilter . '%');
        }

        if ($this->mantenimientoFilter === 'si') {
            $query->where('necesita_mantenimiento', true);
        } elseif ($this->mantenimientoFilter === 'no') {
            $query->where('necesita_mantenimiento', false);
        }

        if ($this->actualizacionFilter === 'si') {
            $query->where('necesita_actualizacion', true);
        } elseif ($this->actualizacionFilter === 'no') {
            $query->where('necesita_actualizacion', false);
        }

        $dispositivos = $query->orderBy('id', 'desc')->paginate(15);

        return view('livewire.inventario.manage-dispositivos', [
            'dispositivos' => $dispositivos,
            'clientes' => Cliente::all()
        ]);
    }
}