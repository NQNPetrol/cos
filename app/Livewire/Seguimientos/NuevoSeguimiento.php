<?php

namespace App\Livewire\Seguimientos;

use Livewire\Component;
use App\Models\Evento;
use App\Models\Seguimiento;
use Illuminate\Support\Facades\Auth;

class NuevoSeguimiento extends Component
{
    public $id_evento = '';
    public $estado = 'ABIERTO';
    public $observaciones = '';

    protected $rules = [
        'id_evento' => 'required|exists:eventos,id',
        'estado' => 'required|in:ABIERTO,EN REVISION,CERRADO',
        'observaciones' => 'nullable|string|min:5|max:2000',
    ];

    protected $messages = [
        'id_evento.required' => 'Debe seleccionar un evento',
    ];


    public function render()
    {
        $eventos = Evento::whereDoesntHave('seguimientos', function($query) {
            $query->where('estado', 'CERRADO');
        })->get();

        return view('livewire.seguimientos.nuevo-seguimiento', [
            'eventos' => $eventos,
            'header' => 'Nuevo Seguimiento de Evento'
        ]);
    }

    public function save()
    {
        $this->validate();
        try{
            Seguimiento::create([
            'evento_id' => $this->id_evento,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
            'user_id' => Auth::id(),
            'fecha' => now(),
            'titulo' => 'Seguimiento para Evento #'.$this->id_evento
        ]);

            session()->flash('sucess', 'Seguimiento creado exitosamente!');
            return redirect()->route('seguimientos.index');
        } catch (\Exception $e) {
            $this->addError('save_error', 'Error al guardar el seguimiento:'.$e->getMessage());
        }
    }
}
