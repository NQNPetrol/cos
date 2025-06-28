<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cliente;

class Clientes extends Component
{
     use WithPagination;

    public $nombre;
    public $cuit;
    public $domicilio;
    public $ciudad;
    public $provincia;
    public $categoria;
    public $convenio;

    public $successMessage = null;

    protected $rules = [
        'nombre'    => 'required|min:3',
        'cuit'      => 'required',
        'domicilio' => 'required',
        'ciudad'    => 'nullable',
        'provincia' => 'nullable',
        'categoria' => 'nullable',
        'convenio'  => 'nullable',
    ];

    public function save()
    {
        $this->validate();

        Cliente::create([
            'nombre'    => $this->nombre,
            'cuit'      => $this->cuit,
            'domicilio' => $this->domicilio,
            'ciudad'    => $this->ciudad,
            'provincia' => $this->provincia,
            'categoria' => $this->categoria,
            'convenio'  => $this->convenio,
        ]);

        $this->reset(['nombre', 'cuit', 'domicilio', 'ciudad', 'provincia', 'categoria', 'convenio']);

        $this->successMessage = "Cliente creado exitosamente.";
    }

    public function updating($property)
    {
        $this->successMessage = null;
    }

    public function render()
    {
        return view('livewire.clientes', [
            'clientes' => Cliente::orderBy('created_at', 'desc')->paginate(5),
        ]);
    }
}
