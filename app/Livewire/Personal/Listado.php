<?php

namespace App\Livewire\Personal;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Personal;


class Listado extends Component
{
    
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    // Livewire 3: resetear página si se actualiza el campo de búsqueda
    public function updated($property, $value)
    {
        if ($property === 'search') {
            $this->resetPage();
        }
    }

    public function render()
    {
        $personal = Personal::query()
            ->when($this->search, fn($q) =>
                $q->where(function ($sub) {
                    $sub->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('apellido', 'like', '%' . $this->search . '%');
                })
            )
            ->orderBy('apellido')
            ->paginate($this->perPage);

        return view('livewire.personal.listado', [
            'personal' => $personal,
        ]);
    }
    public function edit($id)
    {
        return redirect()->route('personal.edit', $id);
    }
}
