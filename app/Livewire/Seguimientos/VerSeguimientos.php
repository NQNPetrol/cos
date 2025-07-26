<?php

namespace App\Livewire\Seguimientos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Seguimiento;
use App\Models\Evento;

class VerSeguimientos extends Component
{

    use WithPagination;

    // Propiedades para filtros
    public $search = '';
    public $estadoFilter = '';
    public $eventoFilter = '';
    public $tipoFilter = '';
    
    // Propiedades para ordenar
    public $sortField = 'fecha';
    public $sortDirection = 'desc';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }

    public function clearFilters()
    {
        $this->reset(['search', 'estadoFilter', 'tipoFilter']);
        $this->resetPage();
    }
    public function render()
    {
        $query = Seguimiento::with(['evento', 'user'])
            ->when($this->search, function($query) {
                $query->where(function($q) {

                    if (preg_match('/#(\d+)/', $this->search, $matches)) {
                        $q->whereHas('evento', function($subQuery) use ($matches) {
                            $subQuery->where('id', $matches[1]);
                        });
                    }
                    // Buscar en detalles o tipo
                    $q->orWhere('detalles', 'like', '%'.$this->search.'%')
                      ->orWhere('estado', 'like', '%'.$this->search.'%')
                      ->orWhere('id', 'like', '%'.$this->search.'%')
                      ->orWhereHas('evento', function($subQuery) {
                          $subQuery->where('tipo', 'like', '%'.$this->search.'%');
                      });
                });
            })
            ->when($this->estadoFilter, function($query) {
                $query->where('estado', $this->estadoFilter);
            })
            ->when($this->tipoFilter, function($query) {
                $query->whereHas('evento', function($subQuery) {
                    $subQuery->where('tipo', 'like', '%'.$this->tipoFilter.'%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.seguimientos.ver-seguimientos', [
            'seguimientos' => $query->paginate(10),
            'header' => 'Listado de Seguimientos'
        ]);
    }
}
