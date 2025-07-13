<?php

namespace App\Livewire\Contratos;

use App\Models\Contrato;
use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $searchCliente = null;
    public $searchNombre = '';
    public $sortField = 'nombre_proyecto';
    public $sortDirection = 'asc';

    public $casts = [
        'searchCliente' => 'integer',
    ];

    public function filtrarCliente($value)
    {
        $this->searchCliente = $value === '' ? null : (int) $value;

        $this->resetPage();
    }

    public function loadContratos($cliente)
    {
        $cliente = Cliente::find($this->cliente_id);
        
        // Asegúrate de que $client no sea nulo antes de intentar acceder a wells
        if ($cliente) {
            $this->contratos = $cliente->contratos;
        } else {
            $this->contratos = collect(); // Si no hay cliente seleccionado, retorna una colección vacía
        }
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
        $query = Contrato::with('cliente');

        $contratos = Contrato::with('cliente')
            ->when($this->searchCliente, fn($q) =>
                $q->where('cliente_id', $this->searchCliente)
            )
            ->when($this->searchNombre, fn($q) =>
                $q->where('nombre_proyecto', 'like', '%' . $this->searchNombre . '%')
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.contratos.index', [
            'contratos' => $contratos,
            'clientes' => $clientes,
        ]);
    }
}
