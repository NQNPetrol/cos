<?php

namespace App\Livewire\Contratos;

use App\Models\Cliente;
use App\Models\Contrato;
use Carbon\Carbon;
use Livewire\Component;

class Edit extends Component
{
    public $contrato;

    public $clientes;

    public $cliente_id;

    public $empresa_asociada_id;

    public $empresasFiltradas = [];

    public $nombre_proyecto;

    public $localidad;

    public $provincia;

    public $observaciones;

    public $fecha_inicio;

    public function mount(Contrato $contrato)
    {
        $this->contrato = $contrato;
        $this->clientes = Cliente::all();

        // Cargar datos del contrato
        $this->cliente_id = $contrato->cliente_id;
        $this->empresa_asociada_id = $contrato->empresa_asociada_id;
        $this->nombre_proyecto = $contrato->nombre_proyecto;
        $this->localidad = $contrato->localidad;
        $this->provincia = $contrato->provincia;
        $this->observaciones = $contrato->observaciones;
        $this->fecha_inicio = $this->contrato->fecha_inicio
            ? Carbon::parse($this->contrato->fecha_inicio)->format('Y-m-d')
            : null;

        // Cargar empresas asociadas iniciales
        $this->cargarEmpresas($this->cliente_id);
        $this->dispatch('empresas-cargadas');
    }

    public function cargarEmpresas($clienteId)
    {
        $this->cliente_id = $clienteId;

        if (empty($clienteId)) {
            $this->empresasFiltradas = collect();

            return;
        }

        $cliente = Cliente::with('empresasAsociadas')->find($clienteId);
        $this->empresasFiltradas = $cliente ? $cliente->empresasAsociadas : collect();
    }

    public function update()
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

        $this->contrato->update($validated);

        return redirect()->route('contratos.index')
            ->with('success', 'Contrato actualizado correctamente.');
    }

    public function render()
    {
        return view('livewire.contratos.edit');
    }
}
