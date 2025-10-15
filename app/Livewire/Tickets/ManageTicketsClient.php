<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\Cliente;
use App\Models\User;
use App\Models\UserCliente;
use App\Models\Notification;
use App\Mail\TicketCreatedNotification;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Str;

class ManageTicketsClient extends Component
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

        return view('livewire.tickets.manage-tickets-client', [
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

        //disparar evento js para actualizar campos
        $this->dispatch('modal-opened');
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

        logger('Store method called', [
            'emitido_por' => $this->emitido_por,
            'cliente_id' => $this->cliente_id,
            'user_id' => auth()->id()
        ]);

        $user = auth()->user();
        $cosCliente = Cliente::where('nombre', 'COS')->first();

        logger('User info', [
            'user_id' => $user->id,
            'user_roles' => $user->getRoleNames()->toArray(),
            'emitido_por' => $this->emitido_por
        ]);

         // Si es ticket de CLIENTE, verificar permisos
        if ($this->emitido_por === 'CLIENTE' && $this->cliente_id) {
            $userHasCliente = UserCliente::where('user_id', $user->id)
                ->where('cliente_id', $this->cliente_id)
                ->exists();
            
            if (!$userHasCliente) {
                session()->flash('error', 'No tienes permisos para crear tickets para este cliente.');
                return;
            }
        }

        // Restricciones solo aplican para tickets internos (COS)
        if ($this->emitido_por === 'COS') {
            $cosCliente = Cliente::where('nombre', 'COS')->first();
            $userHasCOS = $cosCliente && UserCliente::where('user_id', $user->id)
                ->where('cliente_id', $cosCliente->id)
                ->exists();
            
            if (!$userHasCOS && !$user->hasRole('admin') && !$user->hasRole('operador')) {
                session()->flash('error', 'No tienes permisos para crear tickets internos.');
                return;
            }
        }

        $cliente_id = ($this->emitido_por === 'COS') ? null : $this->cliente_id;
        $asignado_a = ($this->emitido_por === 'CLIENTE') ? null : $this->asignado_a;

        $ticket = Ticket::create([
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

        //Crear notificacion si el ticket esta asignado a un usuario especifico
        if ($this->emitido_por === 'COS' && $asignado_a) {
            // Notificación para usuario asignado (ticket interno)
            $this->createAssignmentNotification($ticket, $asignado_a);
        } elseif ($this->emitido_por === 'CLIENTE') {
            // Notificación para todos los usuarios del COS (ticket de cliente)
            $this->createClientTicketNotification($ticket, $user);
            $this->sendTicketCreatedEmail($ticket, $user);
        }

        if ($this->emitido_por === 'CLIENTE') {
            logger('Creando ticket de CLIENTE - debería enviar email');
            
        } else {
            logger('Creando ticket de COS - NO debería enviar email');
        }

        $this->closeModal();
        
        if ($this->emitido_por === 'CLIENTE') {
            logger('Mostrando mensaje con email');
            session()->flash('message', 'Ticket creado exitosamente. Se ha enviado un email de confirmación a tu correo.');
        } else {
            logger('Mostrando mensaje sin email');
            session()->flash('message', 'Ticket creado exitosamente.');
        }
    }

    private function sendTicketCreatedEmail(Ticket $ticket, User $user)
    {
        try {
            logger('Intentando enviar email', [
                'to' => $user->email,
                'ticket_id' => $ticket->id,
                'user_name' => $user->name
            ]);
            Mail::to($user->email)
                ->send(new TicketCreatedNotification($ticket, $user->name));
            
            logger('Email de confirmación enviado a: ' . $user->email);
            
        } catch (\Exception $e) {
            logger('Error al enviar email de confirmación: ' . $e->getMessage());
            // No mostrar error al usuario para no interrumpir el flujo
        }
    }

    private function createAssignmentNotification(Ticket $ticket, $assignedUserId)
    {
        try {
            $assignedUser = User::find($assignedUserId);
            $creatorUser = auth()->user();

            if (!$assignedUser) {
                logger('Usuario asignado no encontrado: ' . $assignedUserId);
                return;
            }

            // Determinar la prioridad de la notificación basada en la prioridad del ticket
            $notificationPriority = $this->mapPrioridades($ticket->prioridad);

            // Crear la notificación
            $notification = Notification::create([
                'title' => 'Nuevo Ticket Asignado',
                'message' => "Se te ha asignado un nuevo ticket: '{$ticket->titulo}'. Prioridad: " . ucfirst($ticket->prioridad),
                'type' => 'user', // Notificación específica para el usuario
                'user_id' => $assignedUser->id, // Usuario al que se le asignó el ticket
                'client_id' => null, // No es específica de cliente
                'priority' => $notificationPriority,
                'is_active' => true,
            ]);

            logger('Notificación creada exitosamente para el usuario: ' . $assignedUser->name);

        } catch (\Exception $e) {
            logger('Error al crear notificación de asignación: ' . $e->getMessage());
        }
    }

    private function createClientTicketNotification(Ticket $ticket, User $creatorUser)
    {
        try {
            // Obtener el cliente asociado al ticket
            $cliente = $ticket->cliente;
            
            if (!$cliente) {
                logger('Cliente no encontrado para el ticket: ' . $ticket->id);
                return;
            }

            // Obtener el cliente COS
            $cosCliente = Cliente::where('nombre', 'COS')->first();
            
            if (!$cosCliente) {
                logger('Cliente COS no encontrado en la base de datos');
                return;
            }

            // Determinar la prioridad de la notificación
            $notificationPriority = $this->mapPrioridades($ticket->prioridad);

            // Crear el mensaje con la información solicitada
            $message = "► TICKET: {$ticket->titulo}\n";
            $message .= "► USUARIO: {$creatorUser->name}\n";
            $message .= "► EMAIL: {$creatorUser->email}\n";
            $message .= "► CLIENTE: {$cliente->nombre}\n";
            $message .= "► PRIORIDAD: " . ucfirst($ticket->prioridad) . "\n";
            $message .= "► CATEGORÍA: {$ticket->categoria}\n";
            $message .= "Contactar al usuario para seguimiento.";

            logger('Creando notificación GLOBAL para usuarios del COS');

            // CORRECCIÓN: Crear una sola notificación GLOBAL para el cliente COS
            $notification = Notification::create([
                'title' => 'Nuevo Ticket de Cliente',
                'message' => $message,
                'type' => 'client',           // Notificación de tipo CLIENTE
                'user_id' => null,            // No es para usuario específico
                'client_id' => $cosCliente->id, // Para el cliente COS (se busca dinámicamente)
                'priority' => $notificationPriority,
                'is_active' => true,
            ]);

            logger('Notificación global de ticket de cliente creada para el COS (ID: ' . $cosCliente->id . ')');

        } catch (\Exception $e) {
            logger('Error al crear notificación de ticket de cliente: ' . $e->getMessage());
        }
    }

    private function mapPrioridades($ticketPriority)
    {
        $priorityMap = [
            'baja' => 'BAJA',
            'media' => 'NORMAL', 
            'alta' => 'ALTA',
            'urgente' => 'ALTA'
        ];

        return $priorityMap[$ticketPriority] ?? 'NORMAL';
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

        $previousAssignedTo = $ticket->asignado_a;

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

        $ticket->update($updateData);

        // Crear notificación si se cambió la asignación
        if ($previousAssignedTo != $this->asignado_a) {
            // Si se removió la asignación
            if ($previousAssignedTo && !$this->asignado_a) {
                $this->notifAsignada($ticket, $previousAssignedTo);
            }
            // Si se asignó a un nuevo usuario
            elseif ($this->asignado_a) {
                $this->notifAsignada($ticket, $this->asignado_a);
            }
        }

        $this->closeModal();
        session()->flash('message', 'Ticket actualizado exitosamente.');
    }

    //funcion por si se cambia el usuario asignado al ticket
    private function notifAsignada(Ticket $ticket, $previousAssignedUserId)
    {
        try {
            $previousUser = User::find($previousAssignedUserId);
        
        if (!$previousUser) return;

        $notification = Notification::create([
            'title' => 'Ticket Desasignado',
            'message' => "Se te ha removido la asignación del ticket: '{$ticket->titulo}'",
            'type' => 'user',
            'user_id' => $previousUser->id,
            'client_id' => null,
            'priority' => 'NORMAL',
            'is_active' => true,
        ]);

        logger('Notificación de desasignación creada para: ' . $previousUser->name);
        } catch (\Exception $e) {
            logger('Error al crear notificación de desasignación: ' . $e->getMessage());
        }
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

        $viejoEstado = $ticket->estado;

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


        // Notificaciones multiples usuarios
        if ($viejoEstado !== $estado){
            $this->cambioEstadoNotif($ticket, $viejoEstado, $estado, $user);
        }

        session()->flash('message', 'Estado del ticket actualizado.');
    }

    private function cambioEstadoNotif(Ticket $ticket, $viejoEstado, $nuevoEstado, User $userWhoChanged)
    {
        try {
            $estados = $this->getEstados();
            $viejoEstadoLabel = $estados[$viejoEstado] ?? $viejoEstado;
            $nuevoEstadoLabel = $estados[$nuevoEstado] ?? $nuevoEstado;

            $message = "El ticket '{$ticket->titulo}' cambió de estado: {$viejoEstadoLabel} → {$nuevoEstadoLabel}";
            $message .= "\nModificado por: {$userWhoChanged->name}";

            // 1. Notificar al USUARIO ASIGNADO (si existe)
            if ($ticket->asignado_a) {
                $this->estadoCambiado(
                    $ticket, 
                    $viejoEstado, 
                    $nuevoEstado, 
                    $ticket->asignado_a,
                    'Estado del Ticket Actualizado',
                    $message
                );
            }

            // 2. Notificar al CREADOR DEL TICKET (si es diferente al usuario asignado y al que cambió el estado)
            if ($ticket->user_id && 
                $ticket->user_id !== $ticket->asignado_a && 
                $ticket->user_id !== $userWhoChanged->id) {
                
                $this->estadoCambiado(
                    $ticket, 
                    $viejoEstado, 
                    $nuevoEstado, 
                    $ticket->user_id,
                    'Estado de tu Ticket Actualizado',
                    $message . "\n\nTu ticket ha sido actualizado por nuestro equipo."
                );
            }

            // 3. Si es un ticket de CLIENTE, notificar a todos los usuarios del COS
            if ($ticket->cliente_id) {
                $this->estadoClienteCambiado($ticket, $viejoEstado, $nuevoEstado, $userWhoChanged);
            }

            logger('Notificaciones de cambio de estado enviadas para el ticket: ' . $ticket->id);

        } catch (\Exception $e) {
            logger('Error al enviar notificaciones de cambio de estado: ' . $e->getMessage());
        }
    }

    private function estadoCambiado(Ticket $ticket, $viejoEstado, $nuevoEstado, $userId, $title, $message)
    {
        try {
            $user = User::find($userId);
            if (!$user) return;

            $notification = Notification::create([
                'title' => $title,
                'message' => $message,
                'type' => 'user',
                'user_id' => $userId,
                'client_id' => null,
                'priority' => 'NORMAL',
                'is_active' => true,
            ]);

            logger("Notificación de cambio de estado enviada a: {$user->name} (ID: {$user->id})");

        } catch (\Exception $e) {
            logger("Error al crear notificación para usuario {$userId}: " . $e->getMessage());
        }
    }

    private function estadoClienteCambiado(Ticket $ticket, $viejoEstado, $nuevoEstado, User $userWhoChanged)
    {
        try {
            $cliente = $ticket->cliente;
            $cosCliente = Cliente::where('nombre', 'COS')->first();
            
            if (!$cliente || !$cosCliente) {
                logger('Cliente no encontrado para la notificación de cambio de estado');
                return;
            }

            $estados = $this->getEstados();
            $viejoEstadoLabel = $estados[$viejoEstado] ?? $viejoEstado;
            $nuevoEstadoLabel = $estados[$nuevoEstado] ?? $nuevoEstado;

            $message = "📊 **CAMBIO DE ESTADO - TICKET DE CLIENTE**\n\n";
            $message .= "► TICKET: {$ticket->titulo}\n";
            $message .= "► CLIENTE: {$cliente->nombre}\n";
            $message .= "► ESTADO ANTERIOR: {$viejoEstadoLabel}\n";
            $message .= "► NUEVO ESTADO: {$nuevoEstadoLabel}\n";
            $message .= "► MODIFICADO POR: {$userWhoChanged->name}\n";
            $message .= "► FECHA: " . now()->format('d/m/Y H:i') . "\n\n";
            $message .= "El ticket ha sido actualizado por el equipo del COS.";

            // Crear notificación global para el COS
            $notification = Notification::create([
                'title' => '🔄 Estado de Ticket de Cliente Actualizado',
                'message' => $message,
                'type' => 'client',
                'user_id' => null,
                'client_id' => $cosCliente->id,
                'priority' => 'NORMAL',
                'is_active' => true,
            ]);

            logger('Notificación global de cambio de estado creada para el COS');

        } catch (\Exception $e) {
            logger('Error al crear notificación de cambio de estado para cliente: ' . $e->getMessage());
        }
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
