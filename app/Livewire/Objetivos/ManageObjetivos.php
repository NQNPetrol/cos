<?php

namespace App\Livewire\Objetivos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Objetivo;
use App\Models\Cliente;
use App\Models\Contrato;

class ManageObjetivos extends Component
{
    use WithPagination;

    public $search = '';
    public $nombre = '';
    public $contrato_id = '';
    public $cliente_id = '';
    public $latitud = '';
    public $longitud = '';
    public $localidad = '';

    public $editingId = null;
    public $clientes;
    public $allContratos;
    public $contratos;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'contrato_id' => 'required|exists:contratos,id',
        'cliente_id' => 'required|exists:clientes,id',
        'latitud' => 'required|string|max:255',
        'latitud' => 'required|regex:/^-?\d{1,2}\.\d+$/',
        'longitud' => 'required|regex:/^-?\d{1,3}\.\d+$/',
    ];

    public function mount()
    {
        $this->clientes = Cliente::all();
        $this->allContratos = Contrato::all();
        $this->contratos = collect();
    }

    public function selectClient($value)
    {
        $this->contratos = $this->allContratos->where('cliente_id', $value)->values();
        $this->contrato_id = ''; // reset selección previa
    }

    public function save()
    {
        $this->validate();

        Objetivo::create([
            'nombre' => $this->nombre,
            'contrato_id' => $this->contrato_id,
            'cliente_id' => $this->cliente_id,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'localidad' => $this->localidad,
        ]);

        $this->resetForm();

        session()->flash('success', 'Objetivo creado correctamente.');
    }

    public function edit($id)
    {
        $objetivo = Objetivo::findOrFail($id);
        $this->editingId = $objetivo->id;
        $this->nombre = $objetivo->nombre;
        $this->contrato_id = $objetivo->contrato_id;
        $this->cliente_id = $objetivo->cliente_id;
        $this->latitud = $objetivo->latitud;
        $this->longitud = $objetivo->longitud;
        $this->localidad = $objetivo->localidad;
    }

    public function update()
    {
        $this->validate();

        $objetivo = Objetivo::findOrFail($this->editingId);

        $objetivo->update([
            'nombre' => $this->nombre,
            'contrato_id' => $this->contrato_id,
            'cliente_id' => $this->cliente_id,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'localidad' => $this->localidad,
        ]);

        $this->resetForm();

        session()->flash('success', 'Objetivo actualizado correctamente.');
    }

    public function delete($id)
    {
        Objetivo::findOrFail($id)->delete();
        session()->flash('success', 'Objetivo eliminado.');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->nombre = '';
        $this->contrato_id = '';
        $this->cliente_id = '';
        $this->latitud = '';
        $this->longitud = '';
        $this->localidad = '';
    }

    public function render()
    {
        $objetivos = Objetivo::with('cliente', 'contrato')
            ->where('nombre', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.objetivos.manage-objetivos', compact('objetivos'));
    }
}
