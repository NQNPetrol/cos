<?php

namespace App\Livewire\Patrullas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Patrulla;
use Illuminate\Support\Facades\Auth;

class ListadoCliente extends Component
{
    use WithPagination;

    public $search = '';
    public $estadoFilter = '';

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

    public function render()
    {
        $clienteIds = $this->getClienteIds();

        $patrullas = Patrulla::with(['cliente']) // Cargar relación con cliente
            ->whereIn('cliente_id', $clienteIds) // Filtrar por clientes del usuario
            ->when($this->search, function($query){
                $query->where('patente', 'like', '%'.$this->search.'%')
                      ->orWhere('marca', 'like', '%'.$this->search.'%')
                      ->orWhere('modelo', 'like', '%'.$this->search.'%')
                      ->orWhere('color', 'like', '%'.$this->search.'%');
            })
            ->when($this->estadoFilter, function($query){
                $query->where('estado', $this->estadoFilter);
            })
            ->orderBy('patente')
            ->paginate(10);

        return view('livewire.patrullas.listado-cliente', [
            'patrullas' => $patrullas
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEstadoFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'estadoFilter']);
        $this->resetPage();
    }
}