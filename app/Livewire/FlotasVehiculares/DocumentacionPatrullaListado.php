<?php

namespace App\Livewire\FlotasVehiculares;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Patrulla;
use App\Models\PatrullaDocumental;
use App\Models\Documento;
use Illuminate\Support\Facades\Auth;

class DocumentacionPatrullaListado extends Component
{
    use WithPagination;

    public $patrullaId;
    public $patrulla;

    public $mostrarFormulario = false;
    public $nuevoNombre = '';
    public $nuevaFechaInicio = '';
    public $nuevaFechaVto = '';
    public $nuevosDetalles = '';

    public function mount($patrullaId)
    {
        $this->patrullaId = $patrullaId;
        $this->patrulla = Patrulla::find($patrullaId);
    }

    public function render()
    {
        $documentacion = PatrullaDocumental::where('patrulla_id', $this->patrullaId)
            ->orderBy('fecha_vto', 'asc')
            ->paginate(4);

        // Pull active document types from DB
        $opcionesDocumentacion = Documento::activos()->orderBy('nombre')->pluck('nombre')->toArray();

        return view('livewire.flotas-vehiculares.documentacion-patrulla-listado', [
            'documentacion' => $documentacion,
            'patrulla' => $this->patrulla,
            'opcionesDocumentacion' => $opcionesDocumentacion,
        ]);
    }

    public function mostrarFormularioAgregar()
    {
        $this->mostrarFormulario = true;
        $this->resetearFormulario();
    }

    public function cancelarAgregar()
    {
        $this->mostrarFormulario = false;
        $this->resetearFormulario();
    }

    public function guardarDocumentacion()
    {
        $opcionesActivas = Documento::activos()->pluck('nombre')->toArray();

        $this->validate([
            'nuevoNombre' => 'required|string|in:' . implode(',', $opcionesActivas),
            'nuevaFechaInicio' => 'required|date',
            'nuevaFechaVto' => 'nullable|date',
            'nuevosDetalles' => 'nullable|string|max:500',
        ]);

        try {
            PatrullaDocumental::create([
                'patrulla_id' => $this->patrullaId,
                'nombre' => $this->nuevoNombre,
                'fecha_inicio' => $this->nuevaFechaInicio,
                'fecha_vto' => $this->nuevaFechaVto ?: null,
                'detalles' => $this->nuevosDetalles,
            ]);

            session()->flash('success', 'Documentación agregada correctamente');
            $this->mostrarFormulario = false;
            $this->resetearFormulario();
            $this->resetPage();

        } catch (\Exception $e) {
            session()->flash('error', 'Error al agregar la documentación: ' . $e->getMessage());
        }
    }

    private function resetearFormulario()
    {
        $this->nuevoNombre = '';
        $this->nuevaFechaInicio = '';
        $this->nuevaFechaVto = '';
        $this->nuevosDetalles = '';
    }
}
