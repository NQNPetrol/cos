<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\Cliente;
use App\Models\User;

class ManageTickets extends Component
{
    use WithPagination;

    public $showModal = false;
    public $titulo;
    public $descripcion;
    public $categoria;
    public $prioridad = 'media';
    public $emitido_por = 'COS';
    public $cliente_id;
    public $asignado_a;
    public $estado;
    public $editMode = false;
    public $ticketId;
    public $statusFilter = '';
    public $categoryFilter = '';
    public $clientTypeFilter = '';
    public $showClienteField = false;

    protected $rules = [
        'titulo' => 'required|min:5',
        'descripcion' => 'required|min:10',
        'prioridad' => 'required|in:baja,media,alta,urgente',
        'categoria' => 'required|in:Fallas Técnicas,Solicitud de compra,Solicitud de instalación,Solicitud de mantenimiento,Solicitud de equipamiento de vehiculos,Reclamos,Solicitud de acceso/creacion de usuarios',
        'cliente_id' => 'nullable|exists:clientes,id',
        'asignado_a' => 'nullable|exists:users,id',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function render()
    {
        $query = Ticket::with(['user', 'assignedTo', 'cliente']);

        if (!auth()->user()->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        } else {
            // Admin puede filtrar por tipo de cliente
            if ($this->clientTypeFilter === 'interno') {
                $query->whereNull('cliente_id');
            } elseif ($this->clientTypeFilter === 'cliente') {
                $query->whereNotNull('cliente_id');
            }
        }

        //estado
        if ($this->statusFilter) {
            $query->where('estado', $this->statusFilter);
        }

        //categoria
        if ($this->categoryFilter) {
            $query->where('categoria', $this->categoryFilter);
        }

        $tickets = $query->latest()->paginate(10);

        $clientes = Cliente::orderBy('nombre')->get();
        $usuarios = User::orderBy('name')->get();
        $categorias = $this->getCategorias();
        $estados = $this->getEstados();

        return view('livewire.tickets.manage-tickets', [
            'tickets' => $tickets,
            'clientes' => $clientes,
            'usuarios' => $usuarios,
            'categorias' => $categorias,
            'estados' => $estados,
        ]);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function store()
    {
        $this->validate();

        $cliente_id = ($this->emitido_por === 'COS') ? null : $this->cliente_id;
        $asignado_a = ($this->emitido_por === 'CLIENTE') ? null : $this->asignado_a;

        Ticket::create([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'categoria' => $this->categoria,
            'estado' => 'abierto', //estado al crear abierto siempre
            'prioridad' => $this->prioridad,
            'user_id' => auth()->id(),
            'cliente_id' => $this->cliente_id,
            'asignado_a' => $this->asignado_a,
            'fecha_cierre' => null, //hasta no cerrar no hay fecha
        ]);

        $this->closeModal();
        session()->flash('message', 'Ticket creado exitosamente.');
    }

    public function edit($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            session()->flash('error', 'No tienes permisos para editar tickets.');
            return;
        }

        $ticket = Ticket::findOrFail($id);

        //no editar tickets ya cerrados
        if ($ticket->estado === 'cerrado') {
            session()->flash('error', 'No se pueden editar tickets cerrados.');
            return;
        }

        $this->ticketId = $id;
        $this->titulo = $ticket->titulo;
        $this->descripcion = $ticket->descripcion;
        $this->categoria = $ticket->categoria;
        $this->prioridad = $ticket->prioridad;
        $this->estado = $ticket->estado;
        $this->cliente_id = $ticket->cliente_id;
        $this->asignado_a = $ticket->asignado_a;

        $this->emitido_por = $ticket->cliente_id ? 'CLIENTE' : 'COS';
        $this->showClienteField = $ticket->cliente_id ? true : false;



        $this->showModal = true;
        $this->editMode = true;
    }

    public function update()
    {
        if (!auth()->user()->hasRole('admin')) {
            session()->flash('error', 'No tienes permisos para editar tickets.');
            return;
        }

        $ticket = Ticket::findOrFail($this->ticketId);

        if ($ticket->estado === 'cerrado') {
            session()->flash('error', 'No se pueden editar tickets cerrados.');
            return;
        }

        $this->validate();

        $updateData = [
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'categoria' => $this->categoria,
            'prioridad' => $this->prioridad,
            'estado' => $this->estado,
        ];

        //si estado se cambia  a cerrado se completa fecha de cierre
        if ($this->estado === 'cerrado' && $ticket->estado !== 'cerrado') {
            $updateData['fecha_cierre'] = now();
        } elseif ($this->estado !== 'cerrado') {
            $updateData['fecha_cierre'] = null;
        }

        $ticket->update($updateData);

        $this->closeModal();
        session()->flash('message', 'Ticket actualizado exitosamente.');
    }

    public function delete($id)
    {

        if (!auth()->user()->hasRole('admin')) {
            session()->flash('error', 'No tienes permisos para eliminar tickets.');
            return;
        }

        Ticket::findOrFail($id)->delete();
        session()->flash('message', 'Ticket eliminado exitosamente.');
    }

    public function updateStatus($id, $estado)
    {

        if (!auth()->user()->hasRole('admin')) {
            session()->flash('error', 'No tienes permisos para cambiar el estado de tickets.');
            return;
        }

        $ticket = Ticket::findOrFail($id);
        
        if ($ticket->estado === 'cerrado') {
            session()->flash('error', 'No se pueden editar tickets cerrados.');
            return;
        }

        if ($estado === 'cerrado') {
            $ticket->update([
                'estado' => $estado,
                'fecha_cierre' => now()
            ]);
        } else {
            $ticket->update([
                'estado' => $estado,
                'fecha_cierre' => null
            ]);
        }

        session()->flash('message', 'Estado del ticket actualizado.');
    }


    public function updatedEmitidoPor($value)
    {
        $this->showClienteField = ($value == 'CLIENTE');
        // Resetear campos dependientes cuando cambia emitido_por
        if ($value === 'COS') {
            $this->cliente_id = null;
        } else {
            $this->asignado_a = null;
        }
    }

    public function closeModalOnClickAway()
    {
        $this->closeModal();
    }

    private function resetForm()
    {
        $this->titulo = '';
        $this->descripcion = '';
        $this->categoria = '';
        $this->prioridad = 'media';
        $this->emitido_por = 'COS';
        $this->showClienteField = false;
        $this->cliente_id = null;
        $this->asignado_a = null;
        $this->estado = '';
        $this->editMode = false;
        $this->ticketId = null;
    }

    public function clearFilters()
    {
        $this->statusFilter = '';
        $this->categoryFilter = '';
        $this->clientTypeFilter = '';
    }

    private function getCategorias()
    {
        return [
        'Fallas Técnicas' => 'Fallas Técnicas',
        'Solicitud de compra' => 'Solicitud de compra',
        'Solicitud de instalación' => 'Solicitud de instalación',
        'Solicitud de mantenimiento' => 'Solicitud de mantenimiento',
        'Solicitud de equipamiento de vehiculos' => 'Solicitud de equipamiento de vehículos',
        'Reclamos' => 'Reclamos',
        'Solicitud de acceso/creacion de usuarios' => 'Solicitud de acceso/creación de usuarios'
        ];

    }

    private function getEstados()
    {
        return [
            'abierto' => 'Abierto',
            'en_proceso' => 'En Proceso',
            'resuelto' => 'Resuelto',
            'cerrado' => 'Cerrado'
        ];
    }
}
