<?php

namespace App\Livewire\Client\EmpresasAsociadas;

use App\Models\EmpresaAsociada;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cliente')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $mostrarModalCrear = false;

    public $mostrarModalEditar = false;

    public $empresaEditar = null;

    // Campos del formulario
    public $nombre = '';

    protected $rules = [
        'nombre' => 'required|string|max:255',
    ];

    protected $messages = [
        'nombre.required' => 'El nombre de la empresa es obligatorio.',
        'nombre.max' => 'El nombre no puede superar los 255 caracteres.',
    ];

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

    public function render()
    {
        $clienteIds = $this->getClienteIds();

        $empresas = EmpresaAsociada::whereHas('cliente', function ($query) use ($clienteIds) {
            $query->whereIn('clientes.id', $clienteIds);
        })
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%'.$this->search.'%');
            })
            ->orderBy('nombre')
            ->paginate(10);

        return view('livewire.client.empresas-asociadas.index', [
            'empresas' => $empresas,
            'clientePrincipal' => $this->getClientePrincipal(),
        ]);
    }

    /**
     * Abrir modal para crear empresa
     */
    public function abrirModalCrear()
    {
        $this->resetFormulario();
        $this->mostrarModalCrear = true;
    }

    /**
     * Cerrar modal de crear
     */
    public function cerrarModalCrear()
    {
        $this->mostrarModalCrear = false;
        $this->resetFormulario();
    }

    /**
     * Crear nueva empresa asociada
     */
    public function crearEmpresa()
    {
        $this->validate();

        $cliente = $this->getClientePrincipal();

        if (! $cliente) {
            session()->flash('error', 'No tienes un cliente asignado');

            return;
        }

        // Crear la empresa asociada
        $empresa = EmpresaAsociada::create([
            'nombre' => $this->nombre,
        ]);

        // Asociar con el cliente a través de la tabla pivot
        $cliente->empresasAsociadas()->attach($empresa->id);

        session()->flash('success', 'Empresa asociada creada correctamente');
        $this->cerrarModalCrear();
    }

    /**
     * Abrir modal para editar
     */
    public function abrirModalEditar($empresaId)
    {
        $clienteIds = $this->getClienteIds();

        $empresa = EmpresaAsociada::whereHas('cliente', function ($query) use ($clienteIds) {
            $query->whereIn('clientes.id', $clienteIds);
        })
            ->find($empresaId);

        if (! $empresa) {
            session()->flash('error', 'Empresa no encontrada');

            return;
        }

        $this->empresaEditar = $empresa;
        $this->nombre = $empresa->nombre;
        $this->mostrarModalEditar = true;
    }

    /**
     * Cerrar modal de editar
     */
    public function cerrarModalEditar()
    {
        $this->mostrarModalEditar = false;
        $this->empresaEditar = null;
        $this->resetFormulario();
    }

    /**
     * Actualizar empresa
     */
    public function actualizarEmpresa()
    {
        if (! $this->empresaEditar) {
            session()->flash('error', 'Empresa no encontrada');

            return;
        }

        $this->validate();

        // Verificar que la empresa pertenece al cliente del usuario
        $clienteIds = $this->getClienteIds();
        $tieneAcceso = $this->empresaEditar->cliente()
            ->whereIn('clientes.id', $clienteIds)
            ->exists();

        if (! $tieneAcceso) {
            session()->flash('error', 'No tienes acceso a esta empresa');

            return;
        }

        $this->empresaEditar->update([
            'nombre' => $this->nombre,
        ]);

        session()->flash('success', 'Empresa asociada actualizada correctamente');
        $this->cerrarModalEditar();
    }

    /**
     * Resetear formulario
     */
    private function resetFormulario()
    {
        $this->nombre = '';
        $this->empresaEditar = null;
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
