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

    public $mostrarModal = false;
    public $patrullaSeleccionada = null;

    // Propiedades para crear/editar patrulla
    public $mostrarModalCrear = false;
    public $mostrarModalEditar = false;
    public $patrullaEditar = null;
    
    // Campos del formulario
    public $patente = '';
    public $marca = '';
    public $modelo = '';
    public $color = '';
    public $año = '';
    public $estado = 'disponible';
    public $observaciones = '';

    protected $rules = [
        'patente' => 'required|string|max:20',
        'marca' => 'required|string|max:100',
        'modelo' => 'required|string|max:100',
        'color' => 'nullable|string|max:50',
        'año' => 'nullable|integer|min:1900|max:2100',
        'estado' => 'required|in:operativa,disponible,en mantenimiento',
        'observaciones' => 'nullable|string|max:500',
    ];

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

    public function abrirModal($patrullaId)
    {
        $this->patrullaSeleccionada = Patrulla::with(['cliente'])->find($patrullaId);
        $this->mostrarModal = true;
    }

    public function cerrarModal()
    {
        $this->mostrarModal = false;
        $this->patrullaSeleccionada = null;
    }

    public function clearFilters()
    {
        $this->reset(['search', 'estadoFilter']);
        $this->resetPage();
    }

    /**
     * Verificar si el usuario puede crear/editar patrullas
     */
    public function puedeGestionarPatrullas()
    {
        $user = Auth::user();
        return $user && $user->hasAnyRole(['clientadmin', 'clientsupervisor']);
    }

    /**
     * Abrir modal para crear patrulla
     */
    public function abrirModalCrear()
    {
        if (!$this->puedeGestionarPatrullas()) {
            session()->flash('error', 'No tienes permisos para crear patrullas');
            return;
        }

        $this->resetFormularioPatrulla();
        $this->mostrarModalCrear = true;
    }

    /**
     * Cerrar modal de crear patrulla
     */
    public function cerrarModalCrear()
    {
        $this->mostrarModalCrear = false;
        $this->resetFormularioPatrulla();
    }

    /**
     * Crear nueva patrulla
     */
    public function crearPatrulla()
    {
        if (!$this->puedeGestionarPatrullas()) {
            session()->flash('error', 'No tienes permisos para crear patrullas');
            return;
        }

        $this->validate();

        $clienteIds = $this->getClienteIds();
        
        if ($clienteIds->isEmpty()) {
            session()->flash('error', 'No tienes un cliente asignado');
            return;
        }

        $patrulla = Patrulla::create([
            'patente' => strtoupper($this->patente),
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'color' => $this->color,
            'año' => $this->año ?: null,
            'estado' => $this->estado,
            'cliente_id' => $clienteIds->first(),
        ]);

        // Crear registro inicial de flota si hay observaciones
        if ($this->observaciones) {
            PatrullaRegistroFlota::create([
                'fecha_registro' => now(),
                'patrulla_id' => $patrulla->id,
                'observacion' => $this->observaciones,
                'user_id' => Auth::id()
            ]);
        }

        session()->flash('success', 'Patrulla creada correctamente');
        $this->cerrarModalCrear();
    }

    /**
     * Abrir modal para editar patrulla
     */
    public function abrirModalEditar($patrullaId)
    {
        if (!$this->puedeGestionarPatrullas()) {
            session()->flash('error', 'No tienes permisos para editar patrullas');
            return;
        }

        $patrulla = Patrulla::with('ultimoRegistroFlota')->find($patrullaId);
        
        if (!$patrulla) {
            session()->flash('error', 'Patrulla no encontrada');
            return;
        }

        // Verificar que la patrulla pertenece al cliente del usuario
        $clienteIds = $this->getClienteIds();
        if (!$clienteIds->contains($patrulla->cliente_id)) {
            session()->flash('error', 'No tienes acceso a esta patrulla');
            return;
        }

        $this->patrullaEditar = $patrulla;
        $this->patente = $patrulla->patente;
        $this->marca = $patrulla->marca;
        $this->modelo = $patrulla->modelo;
        $this->color = $patrulla->color;
        $this->año = $patrulla->año;
        $this->estado = $patrulla->estado;
        $this->observaciones = $patrulla->ultimoRegistroFlota->observacion ?? '';
        
        $this->mostrarModalEditar = true;
    }

    /**
     * Cerrar modal de editar patrulla
     */
    public function cerrarModalEditar()
    {
        $this->mostrarModalEditar = false;
        $this->patrullaEditar = null;
        $this->resetFormularioPatrulla();
    }

    /**
     * Actualizar patrulla
     */
    public function actualizarPatrulla()
    {
        if (!$this->puedeGestionarPatrullas()) {
            session()->flash('error', 'No tienes permisos para editar patrullas');
            return;
        }

        if (!$this->patrullaEditar) {
            session()->flash('error', 'Patrulla no encontrada');
            return;
        }

        $this->validate();

        $this->patrullaEditar->update([
            'patente' => strtoupper($this->patente),
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'color' => $this->color,
            'año' => $this->año ?: null,
            'estado' => $this->estado,
        ]);

        // Actualizar o crear registro de flota con observaciones
        $ultimoRegistro = $this->patrullaEditar->ultimoRegistroFlota;
        $objetivoExistente = $ultimoRegistro->objetivo_servicio ?? null;

        PatrullaRegistroFlota::create([
            'fecha_registro' => now(),
            'patrulla_id' => $this->patrullaEditar->id,
            'objetivo_servicio' => $objetivoExistente,
            'observacion' => $this->observaciones,
            'user_id' => Auth::id()
        ]);

        session()->flash('success', 'Patrulla actualizada correctamente');
        $this->cerrarModalEditar();
    }

    /**
     * Resetear formulario de patrulla
     */
    private function resetFormularioPatrulla()
    {
        $this->patente = '';
        $this->marca = '';
        $this->modelo = '';
        $this->color = '';
        $this->año = '';
        $this->estado = 'disponible';
        $this->observaciones = '';
        $this->patrullaEditar = null;
        $this->resetErrorBag();
    }
}