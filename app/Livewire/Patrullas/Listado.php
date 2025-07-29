<?php

namespace App\Livewire\Patrullas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Patrulla;
use Illuminate\Validation\Rule;

class Listado extends Component
{
    use WithPagination;

    public $search = '';
    public $estadoFilter = '';
    public $showModal = false;
    public $editingId = null;

    //formulario
    public $patente;
    public $modelo;
    public $color;
    public $estado = 'operativa';
    public $observaciones;

    protected function rules()
    {
    return [
        'patente' => [
            'required',
            'string',
            'max:10',
            Rule::unique('patrullas', 'patente')->ignore($this->editingId)
        ],
        // ... otras reglas
    ];
    }

    public function render()
    {
        $patrullas = Patrulla::query()
            ->when($this->search, function($query){
                $query->where('patente', 'like', '%'.$this->search.'%')
                      ->orwhere('modelo', 'like', '%'.$this->search.'%');
            })
            ->when($this->estadoFilter, function($query){
                $query->where('estado', $this->estadoFilter);
            })
            ->orderBy('patente')
            ->paginate(10);
        return view('livewire.patrullas.listado', [
            'patrullas' => $patrullas
        ]);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->patente = '';
        $this->modelo = '';
        $this->color = '';
        $this->estado = 'operativa';
        $this->observaciones = '';

    }

    public function save()
    {
        $this->validate();

        if ($this->editingId){
            $patrulla = Patrulla::find($this->editingId);
            $patrulla->update([
                'patente' => $this->patente,
                'modelo' => $this->modelo,
                'color' => $this->color,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
            ]);

            session()->flash('sucess', 'Patrulla actualizada exitosamente');
        } else {
            Patrulla::create([
                'patente' => $this->patente,
                'modelo' => $this->modelo,
                'color' => $this->color,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
            ]);

            session()->flash('success', 'Patrulla creada exitosamente');
        }
        $this->closeModal();
    }

    public function edit($id)
    {
        $patrulla = Patrulla::findOrFail($id);
        
        $this->editingId = $id;
        $this->patente = $patrulla->patente;
        $this->modelo = $patrulla->modelo;
        $this->color = $patrulla->color;
        $this->estado = $patrulla->estado;
        $this->observaciones = $patrulla->observaciones;
        
        $this->showModal = true;
    }
    
    public function delete($id)
    {
        Patrulla::find($id)->delete();
        session()->flash('success', 'Patrulla eliminada correctamente');
    }

}
