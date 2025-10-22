<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PilotoFlytbase;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class AsignarPilotosClientes extends Component
{

    public $pilotos;
    public $clientes;
    public $asignaciones = [];
    public $pilotoSeleccionado = null;
    public $clienteSeleccionado = null;

    protected $rules = [
        'pilotoSeleccionado' => 'required|exists:pilotos_flytbase,id',
        'clienteSeleccionado' => 'required|exists:clientes,id',
    ];

    public function mount()
    {
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $this->pilotos = PilotoFlytbase::with(['user', 'clientes'])->get();
        $this->clientes = Cliente::all();
        $this->cargarAsignaciones();
    }

    public function cargarAsignaciones()
    {
        $this->asignaciones = DB::table('piloto_flytbase_cliente')
            ->select('piloto_flytbase_id', 'cliente_id')
            ->get()
            ->groupBy('piloto_flytbase_id')
            ->map(function ($item) {
                return $item->pluck('cliente_id')->toArray();
            })
            ->toArray();
    }

    public function asignarPiloto()
    {
        $this->validate();

        // Verificar si ya existe la asignación
        $existeAsignacion = DB::table('piloto_flytbase_cliente')
            ->where('piloto_flytbase_id', $this->pilotoSeleccionado)
            ->where('cliente_id', $this->clienteSeleccionado)
            ->exists();

        if (!$existeAsignacion) {
            DB::table('piloto_flytbase_cliente')->insert([
                'piloto_flytbase_id' => $this->pilotoSeleccionado,
                'cliente_id' => $this->clienteSeleccionado,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->cargarAsignaciones();
            $this->reset(['pilotoSeleccionado', 'clienteSeleccionado']);
            session()->flash('success', 'Piloto asignado correctamente al cliente.');
        } else {
            session()->flash('warning', 'Este cliente ya esta asignado al piloto.');
        }
    }

    public function eliminarAsignacion($pilotoId, $clienteId)
    {
        DB::table('piloto_flytbase_cliente')
            ->where('piloto_flytbase_id', $pilotoId)
            ->where('cliente_id', $clienteId)
            ->delete();

        $this->cargarAsignaciones();
        session()->flash('success', 'Asignacion eliminada correctamente.');
    }

    public function render()
    {
        return view('livewire.asignar-pilotos-clientes');
    }
}
