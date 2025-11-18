<?php

namespace App\Livewire\Patrullas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Patrulla;
use App\Models\PatrullaRegistroFlota;
use Illuminate\Support\Facades\Auth;

class ListadoCliente extends Component
{
    use WithPagination;

    public $search = '';
    public $estadoFilter = '';
    public $editingEstadoId = null;
    public $nuevoEstado = '';
    public $editingObjetivoId = null;
    public $nuevoObjetivo = '';
    public $editingObservacionId = null;
    public $nuevaObservacion = '';

    /**
     * Obtener los IDs de clientes asociados al usuario autenticado
     */
    private function getClienteIds()
    {
        $user = Auth::user();
        
        if (!$user) {
            return collect();
        }

        return $user->clientes()->pluck('clientes.id');
    }

    public function render()
    {
        $clienteIds = $this->getClienteIds();

        $patrullas = Patrulla::with(['cliente', 'ultimoRegistroFlota']) // Cargar relación con cliente
            ->whereIn('cliente_id', $clienteIds) // Filtrar por clientes del usuario
            ->when($this->search, function($query){
                $query->where('patente', 'like', '%'.$this->search.'%')
                      ->orWhere('marca', 'like', '%'.$this->search.'%')
                      ->orWhere('modelo', 'like', '%'.$this->search.'%')
                      ->orWhere('color', 'like', '%'.$this->search.'%');
            })
            ->when($this->estadoFilter, function($query){
                $query->where('estado', $this->estadoFilter);
            })
            ->orderBy('patente')
            ->paginate(10);

        return view('livewire.patrullas.listado-cliente', [
            'patrullas' => $patrullas
        ]);
    }

    public function iniciarEdicionEstado($patrullaId, $estadoActual)
    {
        $this->editingEstadoId = $patrullaId;
        $this->nuevoEstado = $estadoActual;
    }

    public function guardarEstado($patrullaId)
    {
        // Validar que el estado sea uno de los permitidos
        $estadosPermitidos = ['operativa', 'disponible', 'en mantenimiento'];
        
        if (!in_array($this->nuevoEstado, $estadosPermitidos)) {
            session()->flash('error', 'Estado no válido');
            return;
        }

        // Buscar la patrulla y actualizar el estado
        $patrulla = Patrulla::find($patrullaId);
        
        if ($patrulla) {
            $patrulla->update([
                'estado' => $this->nuevoEstado
            ]);
            
            session()->flash('success', 'Estado actualizado correctamente');
        } else {
            session()->flash('error', 'No se encontró la patrulla');
        }

        // Limpiar el estado de edición
        $this->cancelarEdicion();
    }

    public function cancelarEdicion()
    {
        $this->editingEstadoId = null;
        $this->nuevoEstado = '';
    }

    public function iniciarEdicionObjetivo($patrullaId, $objetivoActual)
    {
        $this->editingObjetivoId = $patrullaId;
        $this->nuevoObjetivo = $objetivoActual ?? '';
    }

    public function guardarObjetivo($patrullaId)
    {
        $patrulla = Patrulla::find($patrullaId);
        
        if ($patrulla) {
            // Obtener el último registro para mantener la observación existente
            $ultimoRegistro = $patrulla->ultimoRegistroFlota;
            $observacionExistente = $ultimoRegistro->observacion ?? null;

            // Crear nuevo registro
            PatrullaRegistroFlota::create([
                'fecha_registro' => now(),
                'patrulla_id' => $patrullaId,
                'objetivo_servicio' => $this->nuevoObjetivo,
                'observacion' => $observacionExistente,
                'user_id' => Auth::id()
            ]);

            session()->flash('success', 'Objetivo/Servicio actualizado correctamente');
        } else {
            session()->flash('error', 'No se encontró la patrulla');
        }

        $this->cancelarEdicionObjetivo();
    }

    public function cancelarEdicionObjetivo()
    {
        $this->editingObjetivoId = null;
        $this->nuevoObjetivo = '';
    }

    public function iniciarEdicionObservacion($patrullaId, $observacionActual)
    {
        $this->editingObservacionId = $patrullaId;
        $this->nuevaObservacion = $observacionActual ?? '';
    }

    public function guardarObservacion($patrullaId)
    {
        $patrulla = Patrulla::find($patrullaId);
        
        if ($patrulla) {
            // Obtener el último registro para mantener el objetivo existente
            $ultimoRegistro = $patrulla->ultimoRegistroFlota;
            $objetivoExistente = $ultimoRegistro->objetivo_servicio ?? null;

            // Crear nuevo registro
            PatrullaRegistroFlota::create([
                'fecha_registro' => now(),
                'patrulla_id' => $patrullaId,
                'objetivo_servicio' => $objetivoExistente,
                'observacion' => $this->nuevaObservacion,
                'user_id' => Auth::id()
            ]);

            session()->flash('success', 'Observación actualizada correctamente');
        } else {
            session()->flash('error', 'No se encontró la patrulla');
        }

        $this->cancelarEdicionObservacion();
    }

    public function cancelarEdicionObservacion()
    {
        $this->editingObservacionId = null;
        $this->nuevaObservacion = '';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEstadoFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'estadoFilter']);
        $this->resetPage();
    }
}