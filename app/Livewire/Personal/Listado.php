<?php

namespace App\Livewire\Personal;

use App\Models\Cliente;
use App\Models\Personal;
use Livewire\Component;
use Livewire\WithPagination;

class Listado extends Component
{
    use WithPagination;

    public $search = '';

    public $cliente_id = '';

    public $convenio = '';

    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'cliente_id' => ['except' => ''],
        'convenio' => ['except' => ''],
    ];

    public function aplicarFiltros()
    {
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->search = '';
        $this->cliente_id = '';
        $this->convenio = '';
        $this->resetPage();
    }

    public function render()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $convenios = Personal::select('convenio')
            ->whereNotNull('convenio')
            ->where('convenio', '!=', '')
            ->distinct()
            ->orderBy('convenio')
            ->pluck('convenio');

        $personal = Personal::query()
            ->when($this->search, fn ($q) => $q->where(function ($sub) {
                $sub->where('nombre', 'like', '%'.$this->search.'%')
                    ->orWhere('apellido', 'like', '%'.$this->search.'%');
            })
            )
            ->when($this->cliente_id, fn ($q) => $q->where('cliente_id', $this->cliente_id)
            )
            ->when($this->convenio, fn ($q) => $q->where('convenio', $this->convenio)
            )
            ->with('cliente')
            ->orderBy('nombre')
            ->paginate($this->perPage);

        return view('livewire.personal.listado', [
            'personal' => $personal,
            'clientes' => $clientes,
            'convenios' => $convenios,
        ]);
    }

    public function edit($id)
    {
        return redirect()->route('personal.edit', $id);
    }
}
