<?php

namespace App\Livewire\Tickets;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;

class ManageTickets extends Component
{
    use WithPagination;

    public $showModal = false;
    public $titulo;
    public $descripcion;
    public $prioridad = 'media';
    public $editMode = false;
    public $ticketId;
    public $statusFilter = '';

    protected $rules = [
        'titulo' => 'required|min:5',
        'descripcion' => 'required|min:10',
        'prioridad' => 'required|in:baja,media,alta,urgente',
    ];

    public function render()
    {
        $query = Ticket::with(['user', 'assignedTo']);

        if ($this->statusFilter) {
            $query->where('estado', $this->statusFilter);
        }

        $tickets = $query->latest()->paginate(10);

        return view('livewire.tickets.manage-tickets', [
            'tickets' => $tickets
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

        Ticket::create([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'categoria' => $this->categoria,
            'estado' => $this->estado,
            'prioridad' => $this->prioridad,
            'user_id' => auth()->id(),
            'cliente_id' => $this->cliente_id,
            'asignado_a' => $this->asignado_a,
            'fecha_cierre' => $this->fecha_cierre,
        ]);

        $this->closeModal();
        session()->flash('message', 'Ticket creado exitosamente.');
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);

        $this->ticketId = $id;
        $this->titulo = $ticket->titulo;
        $this->descripcion = $ticket->descripcion;
        $this->prioridad = $ticket->prioridad;

        $this->showModal = true;
        $this->editMode = true;
    }

    public function update()
    {
        $this->validate();

        $ticket = Ticket::findOrFail($this->ticketId);
        $ticket->update([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'categoria' => $this->categoria,
            'estado' => $this->estado,
            'prioridad' => $this->prioridad,
            'user_id' => auth()->id(),
            'cliente_id' => $this->cliente_id,
            'asignado_a' => $this->asignado_a,
            'fecha_cierre' => $this->fecha_cierre,
        ]);

        $this->closeModal();
        session()->flash('message', 'Ticket actualizado exitosamente.');
    }

    public function delete($id)
    {
        Ticket::findOrFail($id)->delete();
        session()->flash('message', 'Ticket eliminado exitosamente.');
    }

    public function updateStatus($id, $estado)
    {
        $ticket = Ticket::findOrFail($id);
        
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

    private function resetForm()
    {
        $this->titulo = '';
        $this->descripcion = '';
        $this->prioridad = 'media';
        $this->editMode = false;
        $this->ticketId = null;
    }
}
