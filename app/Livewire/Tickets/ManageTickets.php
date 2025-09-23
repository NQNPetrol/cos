<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\Cliente;
use App\Models\User;
use App\Models\UserCliente;

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

        //Filtrado de vista seun rol
        $user = auth()->user();

        if ($user->hasRole('admin') || $user->hasRole('operador')) {
            // Admin y operador ven todos los tickets
            if ($this->clientTypeFilter === 'interno') {
                $query->whereNull('cliente_id');
            } elseif ($this->clientTypeFilter === 'cliente') {
                $query->whereNotNull('cliente_id');
            }
        } elseif ($user->hasRole('cliente')) {
            // Usuarios con rol cliente ven tickets de sus clientes y tickets asignados a ellos
            $userClientes = UserCliente::where('user_id', $user->id)->pluck('cliente_id');
            
            $query->where(function($q) use ($user, $userClientes) {
                // Tickets de los clientes a los que pertenece
                $q->whereIn('cliente_id', $userClientes)
                  // O tickets asignados específicamente a este usuario
                  ->orWhere('asignado_a', $user->id)
                  // O tickets creados por el usuario
                  ->orWhere('user_id', $user->id);
            });
        } else {
            // Otros roles solo ven sus propios tickets
            $query->where('user_id', $user->id);
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
        $usuariosParaTabla = User::orderBy('name')->get();

        $categorias = $this->getCategorias();
        $estados = $this->getEstados();

        $usuariosParaAsignar = $this->getUsuariosParaAsignar();

        return view('livewire.tickets.manage-tickets', [
            'tickets' => $tickets,
            'clientes' => $clientes,
            'usuarios' => $usuariosParaAsignar,
            'usuariosTabla' => $usuariosParaTabla,
            'categorias' => $categorias,
            'estados' => $estados,
        ]);
    }

    private function getUsuariosParaAsignar()
    {
        $user = auth()->user();
        $cosCliente = Cliente::where('nombre', 'COS')->first();
        
        if ($user->hasRole('admin') || $user->hasRole('operador')) {
            // Admin y operador pueden asignar a cualquier usuario del COS
            if ($cosCliente) {
                return User::whereHas('userClientes', function($q) use ($cosCliente) {
                    $q->where('cliente_id', $cosCliente->id);
                })
                ->orderBy('name')
                ->get();
            }
        } elseif ($user->hasRole('cliente')) {
            // Usuarios cliente solo pueden asignar si pertenecen al COS
            if ($cosCliente && UserCliente::where('user_id', $user->id)->where('cliente_id', $cosCliente->id)->exists()) {
                return User::whereHas('userClientes', function($q) use ($cosCliente) {
                    $q->where('cliente_id', $cosCliente->id);
                })
                ->orderBy('name')
                ->get();
            }
        }
        
        return collect(); // Retorna colección vacía si no cumple condiciones
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

        $user = auth()->user();
        $cosCliente = Cliente::where('nombre', 'COS')->first();

        // Restricciones solo aplican para tickets internos (COS)
        if ($this->emitido_por === 'COS') {
            // Verificar si el usuario tiene permisos para crear tickets internos
            if (!$cosCliente || !UserCliente::where('user_id', $user->id)->where('cliente_id', $cosCliente->id)->exists()) {
                session()->flash('error', 'No tienes permisos para crear tickets internos.');
                return;
            }
        } else {
            // Para tickets de CLIENTE, verificar que el usuario pertenezca al cliente seleccionado
            if ($this->cliente_id) {
                $userHasCliente = UserCliente::where('user_id', $user->id)
                    ->where('cliente_id', $this->cliente_id)
                    ->exists();
                
                if (!$userHasCliente) {
                    session()->flash('error', 'No tienes permisos para crear tickets para este cliente.');
                    return;
                }
            }
        }

        $cliente_id = ($this->emitido_por === 'COS') ? null : $this->cliente_id;
        $asignado_a = ($this->emitido_por === 'CLIENTE') ? null : $this->asignado_a;

        Ticket::create([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'categoria' => $this->categoria,
            'estado' => 'abierto',
            'prioridad' => $this->prioridad,
            'user_id' => $user->id,
            'cliente_id' => $cliente_id,
            'asignado_a' => $asignado_a,
            'fecha_cierre' => null,
        ]);

        $this->closeModal();
        session()->flash('message', 'Ticket creado exitosamente.');
    }

    public function edit($id)
    {
        $user = auth()->user();
        $ticket = Ticket::findOrFail($id);

        // Verificar permisos de edición
        if (!$this->canEditTicket($user, $ticket)) {
            session()->flash('error', 'No tienes permisos para editar este ticket.');
            return;
        }

        // No editar tickets ya cerrados
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
        $user = auth()->user();
        $ticket = Ticket::findOrFail($this->ticketId);


        if (!$this->canEditTicket($user, $ticket)) {
            session()->flash('error', 'No tienes permisos para editar este ticket.');
            return;
        }


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
        ];

        // Solo admin y operador pueden cambiar el estado
        if ($user->hasRole('admin') || $user->hasRole('operador')) {
            $updateData['estado'] = $this->estado;

            if ($this->estado === 'cerrado' && $ticket->estado !== 'cerrado') {
                $updateData['fecha_cierre'] = now();
            } elseif ($this->estado !== 'cerrado') {
                $updateData['fecha_cierre'] = null;
            }
        }

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
        $user = auth()->user();

        if (!$user->hasRole('admin')) {
            session()->flash('error', 'No tienes permisos para eliminar tickets.');
            return;
        }

        Ticket::findOrFail($id)->delete();
        session()->flash('message', 'Ticket eliminado exitosamente.');
    }

    public function updateStatus($id, $estado)
    {

        $user = auth()->user();
        $ticket = Ticket::findOrFail($id);
        
        // Solo admin y operador pueden cambiar estados
        if (!$user->hasRole('admin') && !$user->hasRole('operador')) {
            session()->flash('error', 'No tienes permisos para cambiar el estado de tickets.');
            return;
        }
        
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

    private function canEditTicket($user, $ticket)
    {
        if ($user->hasRole('admin') || $user->hasRole('operador')) {
            return true;
        }

        if ($user->hasRole('cliente')) {
            // Usuarios cliente pueden editar tickets de sus clientes o asignados a ellos
            $userClientes = UserCliente::where('user_id', $user->id)->pluck('cliente_id');
            
            return in_array($ticket->cliente_id, $userClientes->toArray()) || 
                   $ticket->asignado_a === $user->id ||
                   $ticket->user_id === $user->id;
        }

        // Otros roles solo pueden editar sus propios tickets
        return $ticket->user_id === $user->id;
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
