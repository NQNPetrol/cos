<?php

namespace App\Livewire\Contratos;

use App\Models\Cliente;
use App\Models\Contrato;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $searchCliente = null;

    public $searchNombre = '';

    public $sortField = 'nombre_proyecto';

    public $sortDirection = 'asc';

    public $empresa_asociada_id = null;

    protected $queryString = [
        'searchNombre' => ['except' => ''],
        'searchCliente' => ['except' => null],
        'empresa_asociada_id' => ['except' => null],
        'sortField' => ['except' => 'nombre_proyecto'],
        'sortDirection' => ['except' => 'asc'],
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

    public function delete($id)
    {
        Contrato::find($id)->delete();
        session()->flash('message', 'Contrato eliminado correctamente');
    }

    public function render()
    {
        $clientes = Cliente::orderBy('nombre')->get();

        $contratos = Contrato::with(['cliente', 'empresaAsociada'])
            ->when($this->searchCliente, function ($query) {
                $query->where('cliente_id', $this->searchCliente);
            })
            ->when($this->empresa_asociada_id, function ($query) {
                $query->where('empresa_asociada_id', $this->empresa_asociada_id);
            })
            ->when($this->searchNombre, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre_proyecto', 'like', '%'.$this->searchNombre.'%')
                        ->orWhereHas('empresaAsociada', function ($q) {
                            $q->where('nombre', 'like', '%'.$this->searchNombre.'%');
                        })
                        ->orWhereHas('cliente', function ($q) {
                            $q->where('nombre', 'like', '%'.$this->searchNombre.'%');
                        });
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.contratos.index', [
            'contratos' => $contratos,
            'clientes' => $clientes,
        ]);
    }
}
