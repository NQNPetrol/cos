<?php

namespace App\Livewire\FlotasVehiculares;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Patrulla;
use App\Models\PatrullaSistema;
use App\Models\Sistema;
use Illuminate\Support\Facades\Auth;

class SistemaPatrullaListado extends Component
{
    use WithPagination;

    public $patrullaId;
    public $patrulla;
    public $sistemasDisponibles = [];

    public $mostrarFormulario = false;
    public $nuevoSistemaId = '';
    public $nuevaFechaRegistro = '';
    public $nuevoNroInterno = '';
    public $nuevaFechaVto = '';

    public function mount($patrullaId)
    {
        $this->patrullaId = $patrullaId;
        $this->patrulla = Patrulla::find($patrullaId);
        $this->sistemasDisponibles = Sistema::get();
    }

    public function render()
    {
        $sistemas = PatrullaSistema::with(['sistema'])
            ->where('patrulla_id', $this->patrullaId)
            ->orderBy('created_at', 'desc')
            ->paginate(4);

        return view('livewire.flotas-vehiculares.sistema-patrulla-listado', [
            'sistemas' => $sistemas,
            'patrulla' => $this->patrulla
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

    public function guardarSistema()
    {
        $this->validate([
            'nuevoSistemaId' => 'required|exists:sistemas,id',
            'nuevaFechaRegistro' => 'required|date',
            'nuevaFechaVto' => 'required|date',
            'nuevoNroInterno' => 'nullable|integer',
        ]);

        try {
            PatrullaSistema::create([
                'patrulla_id' => $this->patrullaId,
                'sistema_id' => $this->nuevoSistemaId,
                'fecha_registro' => $this->nuevaFechaRegistro,
                'fecha_vto' => $this->nuevaFechaVto,
                'nro_interno' => $this->nuevoNroInterno,
                'registra_user' => Auth::id(),
            ]);

            session()->flash('success', 'Sistema agregado correctamente');
            $this->mostrarFormulario = false;
            $this->resetearFormulario();
            $this->resetPage(); // Resetear paginación para mostrar el nuevo registro

        } catch (\Exception $e) {
            session()->flash('error', 'Error al agregar el sistema: ' . $e->getMessage());
        }
    }

    private function resetearFormulario()
    {
        $this->nuevoSistemaId = '';
        $this->nuevaFechaRegistro = '';
        $this->nuevoNroInterno = '';
        $this->nuevaFechaVto = '';
    }
}
