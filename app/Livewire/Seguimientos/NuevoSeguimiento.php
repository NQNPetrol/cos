<?php

namespace App\Livewire\Seguimientos;

use Livewire\Component;

class NuevoSeguimiento extends Component
{
    public $titulo;
    public $descripcion;
    public $fecha;

    protected $rules = [
        'titulo' => 'required|min:3',
        'descripcion' => 'required',
        'fecha' => 'required|date',
    ];

    public function save()
    {
        $this->validate();

        NuevoSeguimiento::create([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'fecha' => $this->fecha
        ]);

        session()->flash('message', 'Seguimiento creado!');
        return redirect()->route('seguimientos.index');
    }
    
    public function render()
    {
        return view('livewire.seguimientos.nuevo-seguimiento');
    }
}
