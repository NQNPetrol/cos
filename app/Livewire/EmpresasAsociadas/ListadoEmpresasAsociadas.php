<?php

namespace App\Livewire\EmpresasAsociadas;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\EmpresaAsociada;
use Livewire\WithPagination; 

class ListadoEmpresasAsociadas extends Component
{
    use WithPagination;

    public $search = '';
    public $clienteFilter = '';
    public $showModal = false;
    public $editingId = null;


    public $nombre = '';
    public $cliente_id = '';
    public $clientesDisponibles = [];
    public $clienteEspecifico = null;

    public function mount($clienteId = null)
    {
        $this->clientesDisponibles = Cliente::all();

        if ($clienteId) {
            $this->clienteEspecifico = Cliente::find($clienteId);
            $this->clienteFilter = $clienteId;
        }
    }
    

    public function render()
    {
        $query = EmpresaAsociada::query();

        if ($this->clienteFilter) {
            $query->whereHas('clientes', function($q){
                $q->where('clientes.id', $this->clienteFilter);
            });
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nombre', 'like', '%'.$this->search.'%')
                  ->orWhereHas('cliente', function($q) {
                          $q->where('nombre', 'like', '%'.$this->search.'%');
                  });
            });
        }
        
        $empresas = $query->with('clientes')->orderBy('nombre')->paginate(10);

        return view('livewire.clientes.listado-empresas-asociadas', [
            'empresas' => $empresas,
            'clientes' => $this->clientesDisponibles,
            'clienteEspecifico' => $this->clienteEspecifico
        ]);
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->clienteFilter = '';
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->reset(['nombre', 'editingId']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        
        $this->showModal = false;
        $this->editingId = null;
    }

    public function editarEmpresa($id)
    {
        $empresa = EmpresaAsociada::findOrFail($id);
        $this->editingId = $id;
        $this->nombre = $empresa->nombre;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'nombre' => 'required|string|max:255'
        ]);

        if ($this->editingId) {
            $empresa = EmpresaAsociada::find($this->editingId);
            $empresa->update([
                'nombre' => $this->nombre
            ]);
            $message = 'Empresa actualizada correctamente';
        } else {
            EmpresaAsociada::create([
                'nombre' => $this->nombre
            ]);
            $message = 'Empresa creada correctamente';
        }

        $this->closeModal();
        session()->flash('message', $message);
        $this->resetPage();

    }

    public function eliminarEmpresa($id)
    {
        $empresa = EmpresaAsociada::findOrFail($id);

        if ($empresa->clientes()->count() > 0) {
            session()->flash('error', 'No se puede eliminar');
            return;
        }
        $empresa->delete();
        session()->flash('message', 'Empresa asociada eliminada correctamente');
        $this->resetPage();
    }

    public function desasociarDeCliente($empresaId, $clienteId)
    {
        $empresa = EmpresaAsociada::findOrFail($empresaId);
        $cliente = Cliente::findOrFail($clienteId);
        
        $empresa->clientes()->detach($clienteId);
        
        session()->flash('message', "Empresa '{$empresa->nombre}' desasociada del cliente '{$cliente->nombre}' correctamente");
        $this->resetPage();
    }
    
}
