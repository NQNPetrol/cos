<?php

namespace App\Livewire\EmpresasAsociadas;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\EmpresaAsociada;

class ListadoEmpresasAsociadas extends Component
{
    use withPagination;

    public $cliente;
    public $search = '';

    public function mount($clienteId)
    {
        $this->cliente = Cliente::findOrFail($clienteId);
    }

    public function render()
    {
        $empresas = EmpresaAsociada::where('cliente_id', $this->cliente->id)
            ->when($this->search, function($query) {
                $query->where('nombre', 'like', '%'.$this->search.'%');
            })
            ->paginate(10);

        return view('livewire.clientes.listado-empresas-asociadas', [
            'empresas' => $empresas,
            'cliente' => $this->cliente
        ]);
    }

    public function eliminarEmpresa($id)
    {
        EmpresaAsociada::find($id)->delete();
        session()->flash('message', 'Empresa asociada eliminada correctamente');
    }
    
}
