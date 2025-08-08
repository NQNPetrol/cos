<?php

namespace App\Livewire\Contratos;

use App\Models\Contrato;
use App\Models\Cliente;
use App\Models\EmpresaAsociada;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $searchCliente = null;
    public $searchNombre = '';
    public $sortField = 'nombre_proyecto';
    public $sortDirection = 'asc';

    // Nuevas propiedades para el formulario
    public $cliente_id;
    public $empresa_asociada_id;
    public $empresasFiltradas = [];

    public $casts = [
        'searchCliente' => 'integer',
    ];

    public function cargarEmpresas()
    {
        $this->empresasFiltradas = EmpresaAsociada::where('cliente_id', $cliente_id)->get();
        $this->empresa_asociada_id = null;
    }

    public function mount()
    {
        if ($this->cliente_id) {
            $this->cargarEmpresas($this->cliente_id);
        }
    }

    public function filtrarCliente($value)
    {
        $this->searchCliente = $value === '' ? null : (int) $value;

        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->searchNombre = '';
        $this->searchCliente = '';
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

        $contratos = Contrato::with(['cliente', 'empresasFiltradas'])
            ->when($this->searchCliente, fn($q) =>
                $q->where('cliente_id', $this->searchCliente)
            )
            ->when($this->searchNombre, fn($q) =>
                $q->where('nombre_proyecto', 'like', '%' . $this->searchNombre . '%')
                  ->orWhereHas('empresasFiltradas', function($q) {
                          $q->where('nombre', 'like', '%'.$this->searchNombre.'%');
                        }))
                        ->orderBy($this->sortField, $this->sortDirection)
                        ->paginate(10);

        return view('livewire.contratos.index', [
            'contratos' => $contratos,
            'clientes' => $clientes,
        ]);
    }

    public function delete($id)
    {
        Contrato::find($id)->delete();
        session()->flash('message', 'Contrato eliminado correctamente');
    }
    
}

