<?php

namespace App\Livewire\Seguimientos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Seguimiento;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;

class VerSeguimientosCliente extends Component
{
    use WithPagination;

    // Propiedades para filtros
    public $search = '';
    public $estadoFilter = '';
    public $eventoFilter = '';
    public $tipoFilter = '';
    public $eventosDisponibles = [];
    
    // Propiedades para ordenar
    public $sortField = 'fecha';
    public $sortDirection = 'desc';

    /**
     * Obtener los IDs de clientes asociados al usuario autenticado
     */
    private function getClienteIds()
    {
        $user = Auth::user();
        
        if (!$user) {
            return collect();
        }

        return $user->clientes()->pluck('clientes.id');
    }

    public function mount()
    {
        $clienteIds = $this->getClienteIds();
        
        // Solo eventos de los clientes asignados al usuario
        $this->eventosDisponibles = Evento::whereIn('cliente_id', $clienteIds)
            ->with(['cliente', 'categoria'])
            ->get();
    }

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

    public function updatingTipoFilter()
    {
        $this->resetPage();
    }

    public function updatingEstadoFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'estadoFilter', 'tipoFilter']);
        $this->resetPage();
    }

    public function render()
    {
        $clienteIds = $this->getClienteIds();
        
        $query = Seguimiento::with(['evento', 'user'])
            ->whereHas('evento', function($q) use ($clienteIds) {
                $q->whereIn('cliente_id', $clienteIds);
            })
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    if (preg_match('/#(\d+)/', $this->search, $matches)) {
                        $q->whereHas('evento', function($subQuery) use ($matches) {
                            $subQuery->where('id', $matches[1]);
                        });
                    }
                    // Buscar en detalles o tipo
                    $q->orWhere('observaciones', 'like', '%'.$this->search.'%')
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

        return view('livewire.seguimientos.ver-seguimientos-cliente', [
            'seguimientos' => $query->paginate(10),
            'header' => 'Listado de Seguimientos'
        ]);
    }
}