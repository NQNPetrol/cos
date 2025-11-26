<?php

namespace App\Livewire\Patrullas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Patrulla;
use Illuminate\Validation\Rule;
use App\Models\Cliente;

class Listado extends Component
{
    use WithPagination;

    public $search = '';
    public $estadoFilter = '';
    public $showModal = false;
    public $editingId = null;

    //formulario
    public $patente;
    public $marca;
    public $modelo;
    public $año;
    public $color;
    public $estado = 'operativa';
    public $observaciones;
    public $cliente_id;

    public $clientes = [];

    public function mount()
    {
        // Cargar lista de clientes para el dropdown
        $this->clientes = Cliente::orderBy('nombre')->get();
    }

    protected function rules()
    {
        return [
            'patente' => [
                'required',
                'string',
                'max:10',
                Rule::unique('patrullas', 'patente')->ignore($this->editingId)
            ],
            'cliente_id' => 'required|exists:clientes,id',
            
        ];
    }

    public function render()
    {
        $patrullas = Patrulla::with(['cliente'])
            ->when($this->search, function($query){
                $query->where('patente', 'like', '%'.$this->search.'%')
                      ->orwhere('marca', 'like', '%'.$this->search.'%')
                      ->orwhere('modelo', 'like', '%'.$this->search.'%')
                      ->orWhereHas('cliente', function($q) {
                          $q->where('nombre', 'like', '%'.$this->search.'%');
                      });
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
        $this->marca = '';
        $this->patente = '';
        $this->modelo = '';
        $this->color = '';
        $this->año = '';
        $this->estado = 'operativa';
        $this->observaciones = '';
        $this->cliente_id = '';

    }

    public function save()
    {
        $this->validate();

        if ($this->editingId){
            $patrulla = Patrulla::find($this->editingId);
            $patrulla->update([
                'patente' => $this->patente,
                'marca' => $this->marca,
                'modelo' => $this->modelo,
                'color' => $this->color,
                'año' => $this->año,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
                'cliente_id' => $this->cliente_id,
            ]);

            session()->flash('sucess', 'Patrulla actualizada exitosamente');
        } else {
            Patrulla::create([
                'patente' => $this->patente,
                'marca' => $this->marca,
                'modelo' => $this->modelo,
                'color' => $this->color,
                'año' => $this->año,
                'estado' => $this->estado,
                'observaciones' => $this->observaciones,
                'cliente_id' => $this->cliente_id,
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
        $this->marca = $patrulla->marca;
        $this->modelo = $patrulla->modelo;
        $this->color = $patrulla->color;
        $this->año = $patrulla->año;
        $this->estado = $patrulla->estado;
        $this->observaciones = $patrulla->observaciones;
        $this->cliente_id = $patrulla->cliente_id;
        
        $this->showModal = true;
    }
    
    public function delete($id)
    {
        Patrulla::find($id)->delete();
        session()->flash('success', 'Patrulla eliminada correctamente');
    }

}
