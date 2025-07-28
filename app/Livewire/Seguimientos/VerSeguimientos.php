<?php

namespace App\Livewire\Seguimientos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Seguimiento;
use App\Models\Evento;

class VerSeguimientos extends Component
{

    use WithPagination;

    // Propiedades para filtros
    public $search = '';
    public $estadoFilter = '';
    public $eventoFilter = '';
    public $tipoFilter = '';
    
    // Propiedades para ordenar
    public $sortField = 'fecha';
    public $sortDirection = 'desc';

    // Propiedades para el modal
    public $showModal = false;
    public $editingId = null;
    
    // Propiedades del formulario
    public $evento_id;
    public $fecha;
    public $estado = 'ABIERTO';
    public $observaciones;

    protected $rules = [
        'evento_id' => 'required|exists:eventos,id',
        'fecha' => 'required|date',
        'estado' => 'required|in:ABIERTO,EN REVISION,CERRADO',
        'observaciones' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->eventos = Evento::all();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $seguimiento = Seguimiento::findOrFail($id);
        $this->editingId = $id;
        $this->evento_id = $seguimiento->evento_id;
        $this->fecha  = $seguimiento->fecha->format('Y-m-d');
        $this->estado  = $seguimiento->estado;
        $this->observaciones = $seguimiento->observaciones;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'evento_id' => $this->evento_id,
            'fecha' => $this->fecha,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
            'user_id' => auth()->id(),
        ];

        if ($this->editingId) {
            Seguimiento::find($this->editingId)->update($data);
            $message = 'Seguimiento actualizado correctamente';
        } else {
            Seguimiento::create($data);
            $message = 'Seguimiento creado correctamente';
        }

        $this->closeModal();
        session()->flash('success', $message);
    }


    public function delete($id)
    {
        Seguimiento::find($id)->delete();
        session()->flash('success', 'Seguimiento eliminado correctamente');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->editingId = null;
        $this->evento_id = '';
        $this->fecha = '';
        $this->estado = 'ABIERTO';
        $this->observaciones = '';
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortField = $field;
    }

    public function updatingTipoFilter()
    {
        $this->resetPage();
    }

    public function updatingEstadoFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'estadoFilter', 'tipoFilter']);
        $this->resetPage();
    }
    public function render()
    {
        $query = Seguimiento::with(['evento', 'user'])
            ->when($this->search, function($query) {
                $query->where(function($q) {

                    if (preg_match('/#(\d+)/', $this->search, $matches)) {
                        $q->whereHas('evento', function($subQuery) use ($matches) {
                            $subQuery->where('id', $matches[1]);
                        });
                    }
                    // Buscar en detalles o tipo
                    $q->orWhere('observaciones', 'like', '%'.$this->search.'%')
                      ->orWhere('estado', 'like', '%'.$this->search.'%')
                      ->orWhere('id', 'like', '%'.$this->search.'%')
                      ->orWhereHas('evento', function($subQuery) {
                          $subQuery->where('tipo', 'like', '%'.$this->search.'%');
                      });
                });
            })
            ->when($this->estadoFilter, function($query) {
                $query->where('estado', $this->estadoFilter);
            })
            ->when($this->tipoFilter, function($query) {
                $query->whereHas('evento', function($subQuery) {
                    $subQuery->where('tipo', 'like', '%'.$this->tipoFilter.'%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.seguimientos.ver-seguimientos', [
            'seguimientos' => $query->paginate(10),
            'header' => 'Listado de Seguimientos'
        ]);
    }

}
