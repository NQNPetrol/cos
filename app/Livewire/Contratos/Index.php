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

    public $casts = [
        'searchCliente' => 'integer',
    ];

    public function clearFilters()
    {
        $this->searchNombre = '';
        $this->searchCliente = '';
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

        $contratos = Contrato::with(['cliente', 'empresaAsociada'])
            ->when($this->searchCliente, fn($q) =>
                $q->where('cliente_id', $this->searchCliente)
            )
            ->when($this->searchNombre, fn($q) =>
                $q->where('nombre_proyecto', 'like', '%' . $this->searchNombre . '%')
                  ->orWhereHas('empresaAsociada', function($q) {
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

