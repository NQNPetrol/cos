<?php

namespace App\Livewire\DispositivoPatrulla;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Patrulla;
use App\Models\Dispositivo;
use App\Models\DispositivoPatrulla;

class AsignarDispositivos extends Component
{

    use WithPagination;

    public $patrulla;
    public $showModal = false;
    public $selectedDispositivos = [];
    public $search = '';
    public $fechaAsignacion;

    protected $rules = [
        'selectedDispositivos' => 'required|array|min:1',
        'selectedDispositivos.*' => 'exists:dispositivos,id',
        'fechaAsignacion' => 'required|date'
    ];

    public function mount(Patrulla $patrulla)
    {
        $this->patrulla = $patrulla;
        $this->fechaAsignacion = now()->format('Y-m-d');
    }

    public function render()
    {

        $asignaciones = DispositivoPatrulla::with(['dispositivo.cliente'])
            ->where('patrulla_id', $this->patrulla->id)
            ->paginate(10);

        $dispositivosDisponibles = Dispositivo::whereDoesntHave('patrullas', function ($query) {
            $query->where('patrulla_id', $this->patrulla->id);
        })
        ->when($this->search, function($query) {
            $query->where('tipo', 'like', '%'.$this->search.'%')
                  ->orWhere('modelo', 'like', '%'.$this->search.'%');
        })
        ->where('estado_inventario', '!=', 'Dado de Baja')
        ->get();
        return view('livewire.patrullas.asignar-dispositivos', [
            'asignaciones' => $asignaciones,
            'dispositivosDisponibles' => $dispositivosDisponibles
        ]);
    }

    public function openModal()
    {
        $this->reset(['selectedDispositivos', 'search']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->reset(['selectedDispositivos', 'search']);
        $this->showModal = false;
        $this->resetErrorBag();
        $this->dispatch('close-modal');
    }

    public function asignarDispositivos()
    {
        $this->validate();

        $syncData = [];
        foreach ($this->selectedDispositivos as $dispositivoId) {
            $syncData[$dispositivoId] = [
                'fecha_asignacion' => $this->fechaAsignacion
            ];
        }

        $this->patrulla->dispositivos()->syncWithoutDetaching($syncData);

        $this->showModal = false;
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Dispositivos asignados correctamente'
        ]);
    }

    public function eliminarAsignacion($dispositivoId)
    {
        $this->patrulla->dispositivos()->detach($dispositivoId);
        
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Dispositivo desvinculado correctamente'
        ]);
    }
}
