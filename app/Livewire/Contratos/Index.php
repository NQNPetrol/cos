<?php

namespace App\Livewire\Contratos;

use App\Models\Contrato;
use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $searchCliente = '';
    public $searchNombre = '';
    public $sortField = 'nombre_proyecto';
    public $sortDirection = 'asc';

    public function updatingSearchCliente()
    {
        $this->resetPage();
    }

    public function updatingSearchNombre()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $clientes = Cliente::all();

        $contratos = Contrato::with('cliente')
            ->when($this->searchCliente, fn($q) =>
                $q->where('id_cliente', $this->searchCliente)
            )
            ->when($this->searchNombre, fn($q) =>
                $q->where('nombre_proyecto', 'like', '%' . $this->searchNombre . '%')
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.contratos.index', [
            'contratos' => $contratos,
            'clientes' => $clientes
        ]);
    }
}
