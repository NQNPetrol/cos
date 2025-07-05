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
    public $detalles = '';

    protected $rules = [
        'id_evento' => 'required|exists:eventos,id',
        'estado' => 'required|in:ABIERTO,EN REVISION,CERRADO',
        'detalles' => 'required|string|min:10|max:2000',
    ];

    public function layout()
    {
        return 'layouts.app';
    }

    public function render()
    {
        // Obtener eventos disponibles con manejo de errores
        try {
            $eventos = Evento::whereDoesntHave('seguimientos', function($query) {
                $query->where('estado', 'CERRADO');
            })->get();
        } catch (\Exception $e) {
            $eventos = collect(); // Retorna colección vacía si hay error
        }

        return view('livewire.seguimientos.nuevo-seguimiento', [
            'eventos' => $eventos,
            'title' => 'Nuevo Seguimiento',
            'header' => 'Nuevo Seguimiento de Evento'
        ]);
    }

    public function save()
    {
        $this->validate();

        // Verificar que el evento o se encuentre cerrado
        $eventoDisponible = Evento::whereDoesntHave('seguimientos', function($query) {
            $query->where('estado', 'CERRADO');
        })->where('id', $this->id_evento)->exists();

        if (!$eventoDisponible) {
            $this->addError('id_evento', 'El evento seleccionado ya no está disponible');
            return;
        }

        Seguimiento::create([
            'evento_id' => $this->id_evento,
            'estado' => $this->estado,
            'detalles' => $this->detalles,
            'user_id' => Auth::id(),
            'fecha_registro' => now()
        ]);

        session()->flash('message', 'Seguimiento creado exitosamente!');
        return redirect()->to('/seguimientos');
    }
}
