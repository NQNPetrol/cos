<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\EmpresaAsociada;
use Livewire\Component;
use Livewire\WithPagination;

class ClienteEmpresasAsociadas extends Component
{
    use WithPagination;

    public $cliente;

    public $clienteId;

    public $search = '';

    public $showModal = false;

    public $empresaSeleccionada = '';

    public $empresasSeleccionadas = [];

    public function mount($clienteId)
    {
        $this->clienteId = $clienteId;
        $this->cliente = Cliente::findOrFail($clienteId);
    }

    public function render()
    {
        $empresasAsociadas = $this->cliente->empresasAsociadas()
            ->withPivot(['created_at', 'updated_at'])
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%'.$this->search.'%');
            })
            ->paginate(10);

        $empresasDisponibles = EmpresaAsociada::whereDoesntHave('cliente', function ($query) {
            $query->where('cliente_id', $this->cliente->id);
        })->get();

        return view('livewire.cliente-empresas-asociadas', [
            'empresasAsociadas' => $empresasAsociadas,
            'empresasDisponibles' => $empresasDisponibles,
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
        $this->reset('empresasSeleccionadas');
    }

    public function asociarEmpresa()
    {
        $this->validate([
            'empresasSeleccionadas' => 'required|array',
            'empresasSeleccionadas.*' => 'exists:empresas_asociadas,id',
        ]);

        $now = now()->format('Y-m-d H:i:s');
        $attachData = [];

        foreach ($this->empresasSeleccionadas as $empresaId) {
            $attachData[$empresaId] = [
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->cliente->empresasAsociadas()->syncWithoutDetaching($attachData);

        session()->flash('message', 'Empresas asociadas correctamente');
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
