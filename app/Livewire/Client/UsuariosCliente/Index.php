<?php

namespace App\Livewire\Client\UsuariosCliente;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cliente')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $mostrarModalAsignar = false;

    public $usuarioSeleccionado = null;

    public $rolSeleccionado = '';

    /**
     * Obtener el cliente principal del usuario autenticado
     */
    private function getClientePrincipal()
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        return $user->clientes()->first();
    }

    /**
     * Obtener IDs de clientes del usuario
     */
    private function getClienteIds()
    {
        $user = Auth::user();

        if (! $user) {
            return collect();
        }

        return $user->clientes()->pluck('clientes.id');
    }

    /**
     * Verificar si el usuario puede administrar usuarios
     */
    public function mount()
    {
        if (! Auth::user() || ! Auth::user()->hasRole('clientadmin')) {
            abort(403, 'No autorizado');
        }
    }

    public function render()
    {
        $clienteIds = $this->getClienteIds();

        $usuarios = User::whereHas('clientes', function ($query) use ($clienteIds) {
            $query->whereIn('clientes.id', $clienteIds);
        })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->with('roles')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.client.usuarios-cliente.index', [
            'usuarios' => $usuarios,
            'clientePrincipal' => $this->getClientePrincipal(),
        ]);
    }

    /**
     * Abrir modal para asignar rol
     */
    public function abrirModalAsignar($usuarioId)
    {
        $clienteIds = $this->getClienteIds();

        $usuario = User::whereHas('clientes', function ($query) use ($clienteIds) {
            $query->whereIn('clientes.id', $clienteIds);
        })
            ->find($usuarioId);

        if (! $usuario) {
            session()->flash('error', 'Usuario no encontrado');

            return;
        }

        // No permitir modificar el propio usuario
        if ($usuario->id === Auth::id()) {
            session()->flash('error', 'No puedes modificar tu propio rol');

            return;
        }

        $this->usuarioSeleccionado = $usuario;

        // Obtener el rol actual del usuario (clientsupervisor o cliente)
        if ($usuario->hasRole('clientsupervisor')) {
            $this->rolSeleccionado = 'clientsupervisor';
        } else {
            $this->rolSeleccionado = 'cliente';
        }

        $this->mostrarModalAsignar = true;
    }

    /**
     * Cerrar modal de asignar rol
     */
    public function cerrarModalAsignar()
    {
        $this->mostrarModalAsignar = false;
        $this->usuarioSeleccionado = null;
        $this->rolSeleccionado = '';
    }

    /**
     * Asignar rol al usuario
     */
    public function asignarRol()
    {
        if (! $this->usuarioSeleccionado) {
            session()->flash('error', 'Usuario no encontrado');

            return;
        }

        // Verificar que el usuario pertenece al cliente
        $clienteIds = $this->getClienteIds();
        $tieneAcceso = $this->usuarioSeleccionado->clientes()
            ->whereIn('clientes.id', $clienteIds)
            ->exists();

        if (! $tieneAcceso) {
            session()->flash('error', 'No tienes acceso a este usuario');

            return;
        }

        // Solo permitir asignar clientsupervisor o cliente (básico)
        $rolesPermitidos = ['clientsupervisor', 'cliente'];

        if (! in_array($this->rolSeleccionado, $rolesPermitidos)) {
            session()->flash('error', 'Rol no válido');

            return;
        }

        // No permitir modificar usuarios con rol clientadmin o admin
        if ($this->usuarioSeleccionado->hasRole(['clientadmin', 'admin', 'operador', 'supervisor'])) {
            session()->flash('error', 'No puedes modificar el rol de este usuario');

            return;
        }

        // Remover roles de cliente anteriores y asignar el nuevo
        $this->usuarioSeleccionado->removeRole('cliente');
        $this->usuarioSeleccionado->removeRole('clientsupervisor');
        $this->usuarioSeleccionado->assignRole($this->rolSeleccionado);

        session()->flash('success', 'Rol asignado correctamente');
        $this->cerrarModalAsignar();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
