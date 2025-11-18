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
    public $editingEstadoId = null;
    public $nuevoEstado = '';

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

        $patrullas = Patrulla::with(['cliente', 'ultimoRegistroFlota']) // Cargar relación con cliente
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

    public function iniciarEdicionEstado($patrullaId, $estadoActual)
    {
        $this->editingEstadoId = $patrullaId;
        $this->nuevoEstado = $estadoActual;
    }

    public function guardarEstado($patrullaId)
    {
        // Validar que el estado sea uno de los permitidos
        $estadosPermitidos = ['operativa', 'disponible', 'en mantenimiento'];
        
        if (!in_array($this->nuevoEstado, $estadosPermitidos)) {
            session()->flash('error', 'Estado no válido');
            return;
        }

        // Buscar la patrulla y actualizar el estado
        $patrulla = Patrulla::find($patrullaId);
        
        if ($patrulla) {
            $patrulla->update([
                'estado' => $this->nuevoEstado
            ]);
            
            session()->flash('success', 'Estado actualizado correctamente');
        } else {
            session()->flash('error', 'No se encontró la patrulla');
        }

        // Limpiar el estado de edición
        $this->cancelarEdicion();
    }

    public function cancelarEdicion()
    {
        $this->editingEstadoId = null;
        $this->nuevoEstado = '';
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