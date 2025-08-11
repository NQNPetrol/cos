<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cliente;
use App\Models\EmpresaAsociada;
use App\Models\ClienteEmpresaAsociada;

class ClienteEmpresasAsociadas extends Component
{

    use WithPagination;

    public $cliente;
    public $search = '';
    public $showModal = false;
    public $empresaSeleccionada = '';

    public function mount($clienteId)
    {
        $this->cliente = Cliente::findOrFail($clienteId);
    }

    public function render()
    {
        $empresasAsociadas = $this->cliente->empresasAsociadas()
            ->when($this->search, function($query) {
                $query->where('nombre', 'like', '%'.$this->search.'%');
            })
            ->paginate(10);

        $empresasDisponibles = EmpresaAsociada::whereDoesntHave('clientes', function($query) {
            $query->where('cliente_id', $this->cliente->id);
        })->get();
        return view('livewire.cliente-empresas-asociadas', [
            'empresasAsociadas' => $empresasAsociadas,
            'empresasDisponibles' => $empresasDisponibles
        ]);

        
    }

    public function openModal()
    {
        $this->empresaSeleccionada = '';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->empresaSeleccionada = '';
    }

    public function asociarEmpresa()
    {
        $this->validate([
            'empresaSeleccionada' => 'required|exists:empresas_asociadas,id'
        ]);

        // Verificar que la empresa no esté ya asociada
        if (!$this->cliente->empresasAsociadas()->where('empresa_asociada_id', $this->empresaSeleccionada)->exists()) {
            $this->cliente->empresasAsociadas()->attach($this->empresaSeleccionada);
            session()->flash('message', 'Empresa asociada correctamente');
        } else {
            session()->flash('error', 'La empresa ya está asociada a este cliente');
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function desasociarEmpresa($empresaId)
    {
        $this->cliente->empresasAsociadas()->detach($empresaId);
        session()->flash('message', 'Empresa desasociada correctamente');
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->resetPage();
    }
}
