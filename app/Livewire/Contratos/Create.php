<?php

namespace App\Livewire\Contratos;

use Livewire\Component;
use App\Models\EmpresaAsociada;
use App\Models\Cliente;
use App\Models\Contrato;

class Create extends Component
{

    public $clientes;
    public $cliente_id;
    public $empresa_asociada_id;
    public $empresasFiltradas = [];


    public $nombre_proyecto;
    public $localidad;
    public $provincia;
    public $observaciones;
    public $fecha_inicio;

    public function mount($contrato = null)
    {
        $this->clientes = Cliente::all();
        $this->empresasFiltradas = collect();
    }

    public function cargarEmpresas($clienteId)
    {
        $this->cliente_id = $clienteId;
        $this->empresa_asociada_id = null; 

        if (empty($clienteId)) {
            $this->empresasFiltradas = collect();
            return;
        }

        $cliente = Cliente::with('empresasAsociadas')->find($clienteId);

        $this->empresasFiltradas = $cliente ? $cliente->empresasAsociadas : collect();

    }

    public function save()
    {
        $validated = $this->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'empresa_asociada_id' => 'required|exists:empresas_asociadas,id',
            'nombre_proyecto' => 'required|string|max:255',
            'localidad' => 'nullable|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'fecha_inicio' => 'nullable|date',
        ]);
        Contrato::create($validated);
        
        return redirect()->route('contratos.index')
            ->with('success', 'Contrato creado correctamente.');
    }

    public function render()
    {
        return view('livewire.contratos.create');
    }

}
