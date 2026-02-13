<?php

namespace App\Livewire\EmpresasAsociadas;

use App\Models\EmpresaAsociada;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoEmpresasAsociadas extends Component
{
    use WithPagination;

    public $search = '';

    public $showModal = false;

    public $editingId = null;

    public $nombre = '';

    public function render()
    {
        $query = EmpresaAsociada::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%'.$this->search.'%');
            });
        }

        $empresas = $query->orderBy('nombre')->paginate(10);

        return view('livewire.clientes.listado-empresas-asociadas', [
            'empresas' => $empresas,
        ]);
    }

    public function clearFilters()
    {
        $this->search = '';
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
            'nombre' => 'required|string|max:255',
        ]);

        if ($this->editingId) {
            $empresa = EmpresaAsociada::find($this->editingId);
            $empresa->update([
                'nombre' => $this->nombre,
            ]);
            $message = 'Empresa actualizada correctamente';
        } else {
            EmpresaAsociada::create([
                'nombre' => $this->nombre,
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
        $empresa->delete();
        session()->flash('message', 'Empresa asociada eliminada correctamente');
        $this->resetPage();
    }

    public function desasociarDeCliente($empresaId)
    {
        $empresa = EmpresaAsociada::findOrFail($empresaId);

        session()->flash('message', "Empresa '{$empresa->nombre}' eliminada correctamente.");
        $this->resetPage();
    }
}
