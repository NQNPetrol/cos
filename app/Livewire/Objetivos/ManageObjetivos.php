<?php

namespace App\Livewire\Objetivos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Objetivo;
use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\EmpresaAsociada;

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
    public $empresa_asociada_id = null;
    public $empresasFiltradas = [];

    public $form = [
        'nombre' => '',
        'contrato_id' => '',
        'cliente_id' => '',
        'latitud' => '',
        'longitud' => '',
        'localidad' => '',
        'empresa_asociada_id' => null
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
        $this->empresasFiltradas = collect();
        $this->resetFilters();
    }

    public function cargarEmpresas($clienteId)
    {
        $this->form['cliente_id'] = $clienteId;
        $this->form['empresa_asociada_id'] = null; 

        if (empty($clienteId)) {
            $this->empresasFiltradas = collect();
            return;
        }

        $cliente = Cliente::with('empresasAsociadas')->find($clienteId);

        $this->empresasFiltradas = $cliente ? $cliente->empresasAsociadas : collect();

    }

    public function resetFilters()
    {
        $this->reset([
        'search', 
        'cliente_id', 
        'contrato_id', 
        'localidad',
        'empresa_asociada_id'
        ]);
        $this->contratos = $this->allContratos;
        $this->empresasFiltradas = collect();
        $this->resetPage();
    }

    public function selectClient($value)
    {
        $this->contratos = $this->allContratos->where('cliente_id', $value)->values();
        $this->contrato_id = ''; // reset selección previa
    }

    public function save()
    {
        $this->validate([
            'form.nombre' => 'required|string|max:255',
            'form.contrato_id' => 'required|exists:contratos,id',
            'form.cliente_id' => 'required|exists:clientes,id',
            'form.latitud' => 'required|regex:/^-?\d{1,2}\.\d+$/',
            'form.longitud' => 'required|regex:/^-?\d{1,3}\.\d+$/',
            'form.empresa_asociada_id' => 'required|exists:empresas_asociadas,id',
        ]);

        Objetivo::create($this->form);

        $this->resetForm();
        $this->resetFilters();
        $this->closeModal();

        session()->flash('success', 'Objetivo creado correctamente.');
    }

    public function edit($id)
    {
        $objetivo = Objetivo::findOrFail($id);
        $this->editingId = $objetivo->id;
        $this->form = [
            'nombre' => $objetivo->nombre,
            'contrato_id' => $objetivo->contrato_id,
            'cliente_id' => $objetivo->cliente_id,
            'latitud' => $objetivo->latitud,
            'longitud' => $objetivo->longitud,
            'localidad' => $objetivo->localidad,
            'empresa_asociada_id' => $objetivo->empresa_asociada_id
        ];
        $this->showModal = true;

        if($this->form['cliente_id']) {
            $this->cargarEmpresas($this->form['cliente_id']);
        }
    }

    public function update()
    {
        $this->validate([
            'form.nombre' => 'required|string|max:255',
            'form.contrato_id' => 'required|exists:contratos,id',
            'form.cliente_id' => 'required|exists:clientes,id',
            'form.latitud' => 'required|regex:/^-?\d{1,2}\.\d+$/',
            'form.longitud' => 'required|regex:/^-?\d{1,3}\.\d+$/',
            'form.empresa_asociada_id' => 'required|exists:empresas_asociadas,id',
        ]);

        $objetivo = Objetivo::findOrFail($this->editingId);

        $objetivo->update($this->form);

        $this->resetForm();
        $this->resetFilters();
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
        $this-> form = [
            'nombre' => '',
            'contrato_id' => '',
            'cliente_id' => '',
            'latitud' => '',
            'longitud' => '',
            'localidad' => '',
            'empresa_asociada_id' => null
        ];
        $this->empresasFiltradas = collect();
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
        $clientes = Cliente::orderby('nombre')->get();

        $objetivos = Objetivo::with(['cliente', 'contrato', 'empresaAsociada'])
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
            ->when($this->empresa_asociada_id, function ($query) {
                $query->where('empresa_asociada_id', $this->empresa_asociada_id);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.objetivos.manage-objetivos', [
            'objetivos' => $objetivos,
            'clientes'  => $clientes,
        ]);
    }
}
