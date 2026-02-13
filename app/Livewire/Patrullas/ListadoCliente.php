<?php

namespace App\Livewire\Patrullas;

use App\Models\Documento;
use App\Models\Patrulla;
use App\Models\PatrullaRegistroFlota;
use App\Models\Sistema;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

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

    public $mostrarFiltros = false;

    public $mostrarModal = false;

    public $patrullaSeleccionada = null;

    // Propiedades para crear/editar patrulla
    public $mostrarModalCrear = false;

    public $mostrarModalEditar = false;

    public $patrullaEditar = null;

    // Campos del formulario patrulla
    public $patente = '';

    public $marca = '';

    public $modelo = '';

    public $color = '';

    public $año = '';

    public $estado = 'disponible';

    public $observaciones = '';

    // ── Sistemas CRUD ──
    public $mostrarPanelSistemas = false;

    public $sistemaEditId = null;

    public $sistemaNombre = '';

    public $sistemaLink = '';

    public $sistemaCorreo = '';

    public $sistemaTelefono = '';

    // ── Documentos CRUD ──
    public $mostrarPanelDocumentos = false;

    public $documentoEditId = null;

    public $documentoNombre = '';

    public $documentoDescripcion = '';

    public $documentoActivo = true;

    protected $rules = [
        'patente' => 'required|string|max:20',
        'marca' => 'required|string|max:100',
        'modelo' => 'required|string|max:100',
        'color' => 'nullable|string|max:50',
        'año' => 'nullable|integer|min:1900|max:2100',
        'estado' => 'required|in:operativa,disponible,en mantenimiento',
        'observaciones' => 'nullable|string|max:500',
    ];

    private function getClienteIds()
    {
        $user = Auth::user();
        if (! $user) {
            return collect();
        }

        return $user->clientes()->pluck('clientes.id');
    }

    public function render()
    {
        $clienteIds = $this->getClienteIds();

        $patrullas = Patrulla::with(['cliente', 'ultimoRegistroFlota'])
            ->whereIn('cliente_id', $clienteIds)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('patente', 'like', '%'.$this->search.'%')
                        ->orWhere('marca', 'like', '%'.$this->search.'%')
                        ->orWhere('modelo', 'like', '%'.$this->search.'%')
                        ->orWhere('color', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->estadoFilter, function ($query) {
                $query->where('estado', $this->estadoFilter);
            })
            ->orderBy('patente')
            ->paginate(10);

        // Stats
        $totalPatrullas = Patrulla::whereIn('cliente_id', $clienteIds)->count();
        $operativas = Patrulla::whereIn('cliente_id', $clienteIds)->where('estado', 'operativa')->count();
        $disponibles = Patrulla::whereIn('cliente_id', $clienteIds)->where('estado', 'disponible')->count();
        $enMantenimiento = Patrulla::whereIn('cliente_id', $clienteIds)->where('estado', 'en mantenimiento')->count();

        return view('livewire.patrullas.listado-cliente', [
            'patrullas' => $patrullas,
            'totalPatrullas' => $totalPatrullas,
            'operativas' => $operativas,
            'disponibles' => $disponibles,
            'enMantenimiento' => $enMantenimiento,
            'sistemas' => Sistema::orderBy('nombre')->get(),
            'documentos' => Documento::orderBy('nombre')->get(),
        ]);
    }

    // ══════════════════════════════════════════════
    //  INLINE EDITING (estado, objetivo, observacion)
    // ══════════════════════════════════════════════

    public function iniciarEdicionEstado($patrullaId, $estadoActual)
    {
        $this->editingEstadoId = $patrullaId;
        $this->nuevoEstado = $estadoActual;
    }

    public function guardarEstado($patrullaId)
    {
        $estadosPermitidos = ['operativa', 'disponible', 'en mantenimiento'];
        if (! in_array($this->nuevoEstado, $estadosPermitidos)) {
            session()->flash('error', 'Estado no válido');

            return;
        }
        $patrulla = Patrulla::find($patrullaId);
        if ($patrulla) {
            $patrulla->update(['estado' => $this->nuevoEstado]);
            session()->flash('success', 'Estado actualizado correctamente');
        }
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
            $ultimoRegistro = $patrulla->ultimoRegistroFlota;
            PatrullaRegistroFlota::create([
                'fecha_registro' => now(),
                'patrulla_id' => $patrullaId,
                'objetivo_servicio' => $this->nuevoObjetivo,
                'observacion' => $ultimoRegistro->observacion ?? null,
                'user_id' => Auth::id(),
            ]);
            session()->flash('success', 'Objetivo/Servicio actualizado');
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
            $ultimoRegistro = $patrulla->ultimoRegistroFlota;
            PatrullaRegistroFlota::create([
                'fecha_registro' => now(),
                'patrulla_id' => $patrullaId,
                'objetivo_servicio' => $ultimoRegistro->objetivo_servicio ?? null,
                'observacion' => $this->nuevaObservacion,
                'user_id' => Auth::id(),
            ]);
            session()->flash('success', 'Observación actualizada');
        }
        $this->cancelarEdicionObservacion();
    }

    public function cancelarEdicionObservacion()
    {
        $this->editingObservacionId = null;
        $this->nuevaObservacion = '';
    }

    public function toggleFiltros()
    {
        $this->mostrarFiltros = ! $this->mostrarFiltros;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEstadoFilter()
    {
        $this->resetPage();
    }

    // ══════════════════════════════════════════════
    //  MODAL: View patrulla details
    // ══════════════════════════════════════════════

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

    // ══════════════════════════════════════════════
    //  CRUD: Patrullas
    // ══════════════════════════════════════════════

    public function puedeGestionarPatrullas()
    {
        $user = Auth::user();

        return $user && $user->hasAnyRole(['clientadmin', 'clientsupervisor']);
    }

    public function abrirModalCrear()
    {
        if (! $this->puedeGestionarPatrullas()) {
            session()->flash('error', 'No tienes permisos para crear patrullas');

            return;
        }
        $this->resetFormularioPatrulla();
        $this->mostrarModalCrear = true;
    }

    public function cerrarModalCrear()
    {
        $this->mostrarModalCrear = false;
        $this->resetFormularioPatrulla();
    }

    public function crearPatrulla()
    {
        if (! $this->puedeGestionarPatrullas()) {
            return;
        }
        $this->validate();
        $clienteIds = $this->getClienteIds();
        if ($clienteIds->isEmpty()) {
            session()->flash('error', 'No tienes un cliente asignado');

            return;
        }
        Patrulla::create([
            'patente' => strtoupper($this->patente),
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'color' => $this->color,
            'año' => $this->año ?: null,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones ?: null,
            'cliente_id' => $clienteIds->first(),
        ]);
        session()->flash('success', 'Patrulla creada correctamente');
        $this->cerrarModalCrear();
    }

    public function abrirModalEditar($patrullaId)
    {
        if (! $this->puedeGestionarPatrullas()) {
            return;
        }
        $patrulla = Patrulla::find($patrullaId);
        if (! $patrulla) {
            return;
        }
        $clienteIds = $this->getClienteIds();
        if (! $clienteIds->contains($patrulla->cliente_id)) {
            return;
        }

        $this->patrullaEditar = $patrulla;
        $this->patente = $patrulla->patente;
        $this->marca = $patrulla->marca;
        $this->modelo = $patrulla->modelo;
        $this->color = $patrulla->color;
        $this->año = $patrulla->año;
        $this->estado = $patrulla->estado;
        $this->observaciones = $patrulla->observaciones ?? '';
        $this->mostrarModalEditar = true;
    }

    public function cerrarModalEditar()
    {
        $this->mostrarModalEditar = false;
        $this->patrullaEditar = null;
        $this->resetFormularioPatrulla();
    }

    public function actualizarPatrulla()
    {
        if (! $this->puedeGestionarPatrullas() || ! $this->patrullaEditar) {
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
            'observaciones' => $this->observaciones ?: null,
        ]);
        session()->flash('success', 'Patrulla actualizada correctamente');
        $this->cerrarModalEditar();
    }

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

    // ══════════════════════════════════════════════
    //  CRUD: Sistemas
    // ══════════════════════════════════════════════

    public function togglePanelSistemas()
    {
        $this->mostrarPanelSistemas = ! $this->mostrarPanelSistemas;
        $this->resetFormularioSistema();
    }

    public function editarSistema($id)
    {
        $sistema = Sistema::find($id);
        if (! $sistema) {
            return;
        }
        $this->sistemaEditId = $id;
        $this->sistemaNombre = $sistema->nombre;
        $this->sistemaLink = $sistema->link ?? '';
        $this->sistemaCorreo = $sistema->correo_electronico ?? '';
        $this->sistemaTelefono = $sistema->telefono ?? '';
    }

    public function guardarSistema()
    {
        $this->validate([
            'sistemaNombre' => 'required|string|max:255',
            'sistemaLink' => 'nullable|string|max:255',
            'sistemaCorreo' => 'nullable|email|max:255',
            'sistemaTelefono' => 'nullable|string|max:50',
        ]);

        if ($this->sistemaEditId) {
            $sistema = Sistema::find($this->sistemaEditId);
            if ($sistema) {
                $sistema->update([
                    'nombre' => $this->sistemaNombre,
                    'link' => $this->sistemaLink ?: null,
                    'correo_electronico' => $this->sistemaCorreo ?: null,
                    'telefono' => $this->sistemaTelefono ?: null,
                ]);
                session()->flash('success', 'Sistema actualizado');
            }
        } else {
            Sistema::create([
                'nombre' => $this->sistemaNombre,
                'link' => $this->sistemaLink ?: null,
                'correo_electronico' => $this->sistemaCorreo ?: null,
                'telefono' => $this->sistemaTelefono ?: null,
            ]);
            session()->flash('success', 'Sistema creado');
        }
        $this->resetFormularioSistema();
    }

    public function eliminarSistema($id)
    {
        $sistema = Sistema::find($id);
        if ($sistema) {
            $sistema->delete();
            session()->flash('success', 'Sistema eliminado');
        }
        $this->resetFormularioSistema();
    }

    public function cancelarEdicionSistema()
    {
        $this->resetFormularioSistema();
    }

    private function resetFormularioSistema()
    {
        $this->sistemaEditId = null;
        $this->sistemaNombre = '';
        $this->sistemaLink = '';
        $this->sistemaCorreo = '';
        $this->sistemaTelefono = '';
    }

    // ══════════════════════════════════════════════
    //  CRUD: Documentos
    // ══════════════════════════════════════════════

    public function togglePanelDocumentos()
    {
        $this->mostrarPanelDocumentos = ! $this->mostrarPanelDocumentos;
        $this->resetFormularioDocumento();
    }

    public function editarDocumento($id)
    {
        $doc = Documento::find($id);
        if (! $doc) {
            return;
        }
        $this->documentoEditId = $id;
        $this->documentoNombre = $doc->nombre;
        $this->documentoDescripcion = $doc->descripcion ?? '';
        $this->documentoActivo = $doc->activo;
    }

    public function guardarDocumento()
    {
        $this->validate([
            'documentoNombre' => 'required|string|max:255',
            'documentoDescripcion' => 'nullable|string|max:500',
        ]);

        if ($this->documentoEditId) {
            $doc = Documento::find($this->documentoEditId);
            if ($doc) {
                $doc->update([
                    'nombre' => $this->documentoNombre,
                    'descripcion' => $this->documentoDescripcion ?: null,
                    'activo' => $this->documentoActivo,
                ]);
                session()->flash('success', 'Documento actualizado');
            }
        } else {
            Documento::create([
                'nombre' => $this->documentoNombre,
                'descripcion' => $this->documentoDescripcion ?: null,
                'activo' => $this->documentoActivo,
            ]);
            session()->flash('success', 'Documento creado');
        }
        $this->resetFormularioDocumento();
    }

    public function eliminarDocumento($id)
    {
        $doc = Documento::find($id);
        if ($doc) {
            $doc->delete();
            session()->flash('success', 'Documento eliminado');
        }
        $this->resetFormularioDocumento();
    }

    public function cancelarEdicionDocumento()
    {
        $this->resetFormularioDocumento();
    }

    private function resetFormularioDocumento()
    {
        $this->documentoEditId = null;
        $this->documentoNombre = '';
        $this->documentoDescripcion = '';
        $this->documentoActivo = true;
    }
}
