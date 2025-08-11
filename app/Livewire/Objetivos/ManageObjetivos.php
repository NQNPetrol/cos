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
    public $localidades = [];

    public $showModal = false;
    public $editingId = null;
    public $clientes;
    public $allContratos;
    public $contratos;

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'contrato_id' => 'required|exists:contratos,id',
        'cliente_id' => 'required|exists:clientes,id',
        'latitud' => 'required|regex:/^-?\d{1,2}\.\d+$/',
        'longitud' => 'required|regex:/^-?\d{1,3}\.\d+$/',
    ];

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'q'],
        'cliente_id' => ['except' => ''],
        'contrato_id' => ['except' => ''],
        'localidad' => ['except' => '']
    ];

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
    }


    public function mount()
    {
        $this->clientes = Cliente::all();
        $this->allContratos = Contrato::all();
        $this->contratos = $this->allContratos; // Mostrar todos los contratos inicialmente
        $this->localidades = Objetivo::select('localidad')
            ->distinct()
            ->whereNotNull('localidad')
            ->orderBy('localidad')
            ->pluck('localidad')
            ->toArray();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'cliente_id', 'contrato_id', 'localidad']);
        $this->contratos = $this->allContratos;
        $this->resetPage();
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
        $this->closeModal();

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
        $this->showModal = true;
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
        $this->closeModal();

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
        $this->resetErrorbag();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'cliente_id', 'contrato_id', 'localidad'])){
            $this->resetPage();
        }

        if ($property === 'cliente_id'){
            $this->contratos = $this->allContratos->where('cliente_id', $this->cliente_id)->values();
            $this->contrato_id = '';
        }
    }

    public function render()
    {
        $objetivos = Objetivo::with('cliente', 'contrato')
            ->when($this->search, function($query) {
                $query->where(function($q){
                    $q->where('nombre', 'like', '%'.$this->search.'%')
                       ->orWhere('localidad', 'like', '%'.$this->search.'%')
                       ->orWhere('cliente_id', 'like', '%'.$this->search.'%');
                    });
            })
            ->when($this->cliente_id, function($query) {
                $query->where('cliente_id', $this->cliente_id);
            })
            ->when($this->contrato_id, function($query) {
                $query->where('contrato_id', $this->contrato_id);
            })
            ->when($this->localidad, function($query) {
                $query->where('localidad', $this->localidad);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.objetivos.manage-objetivos', compact('objetivos'));
    }
}
