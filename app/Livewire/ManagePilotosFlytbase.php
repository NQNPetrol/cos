<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PilotoFlytbase;
use App\Models\User;

class ManagePilotosFlytbase extends Component
{
    use WithPagination;

    public $pilotos;
    public $users;
    public $showModal = false;
    public $editing = false;
    public $pilotoId = null;
    public $nombre = '';
    public $token = '';
    public $user_id = '';

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'token' => 'nullable|string',
        'user_id' => 'nullable|exists:users,id',
    ];

    public function mount()
    {
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $this->pilotos = PilotoFlytbase::with('user')->get();
        $this->users = User::all();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editing = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->pilotoId = null;
        $this->nombre = '';
        $this->token = '';
        $this->user_id = '';
        $this->resetErrorBag();
    }

    public function crearPiloto()
    {
        $this->validate();

        try {
            PilotoFlytbase::create([
                'nombre' => $this->nombre,
                'token' => $this->token,
                'user_id' => $this->user_id ?: null,
            ]);

            $this->cargarDatos();
            $this->closeModal();
            session()->flash('success', 'Piloto creado exitosamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el piloto: ' . $e->getMessage());
        }
    }

    public function editarPiloto($id)
    {
        $piloto = PilotoFlytbase::findOrFail($id);
        
        $this->pilotoId = $piloto->id;
        $this->nombre = $piloto->nombre;
        $this->token = $piloto->token;
        $this->user_id = $piloto->user_id;
        
        $this->showModal = true;
        $this->editing = true;
    }

    public function actualizarPiloto()
    {
        $this->validate();

        try {
            $piloto = PilotoFlytbase::findOrFail($this->pilotoId);
            $piloto->update([
                'nombre' => $this->nombre,
                'token' => $this->token,
                'user_id' => $this->user_id ?: null,
            ]);

            $this->cargarDatos();
            $this->closeModal();
            session()->flash('success', 'Piloto actualizado exitosamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el piloto: ' . $e->getMessage());
        }
    }

    public function eliminarPiloto($id)
    {
        try {
            $piloto = PilotoFlytbase::findOrFail($id);
            
            // Verificar si tiene asignaciones
            if ($piloto->clientes()->count() > 0) {
                session()->flash('error', 'No se puede eliminar el piloto porque tiene clientes asignados.');
                return;
            }

            $piloto->delete();
            $this->cargarDatos();
            session()->flash('success', 'Piloto eliminado exitosamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el piloto: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.manage-pilotos-flytbase', [
            'pilotosPaginados' => PilotoFlytbase::with('user')->paginate(10)
        ]);
    }
}
