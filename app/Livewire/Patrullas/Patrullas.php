<?php

namespace App\Livewire\Patrullas;

use App\Models\Patrulla;
use Livewire\Component;
use Livewire\WithPagination;

class Patrullas extends Component
{
    use WithPagination;

    public $search = '';

    public $estadoFilter = '';

    public function render()
    {
        $patrullas = Patrulla::query()
            ->when($this->search, function ($query) {
                $query->where('patente', 'like', '%'.$this->search.'%')
                    ->orwhere('modelo', 'like', '%'.$this->search.'%');
            })
            ->when($this->estadoFilter, function ($query) {
                $query->where('estado', $this->estadoFilter);
            })
            ->orderBy('patente')
            ->paginate(10);

        return view('livewire.patrullas.listado', [
            'patrullas' => $patrullas,
        ]);
    }
}
