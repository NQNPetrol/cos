<?php

namespace App\Livewire\Objetivos;

use App\Models\ObjetivoAipem;
use Livewire\Component;
use Livewire\WithPagination;

class ManageObjetivosAipem extends Component
{
    use WithPagination;

    public $search = '';

    public $pcia = '';

    public $nombre = '';

    public $localidad = '';

    public $showModal = false;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'q'],
        'pcia' => ['except' => ''],
        'localidad' => ['except' => ''],
    ];

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'pcia',
            'localidad',
        ]);

        $this->resetPage();
    }

    public function getProvinciasProperty()
    {
        return ObjetivoAipem::select('pcia')
            ->distinct()
            ->whereNotNull('pcia')
            ->where('pcia', '!=', '')
            ->orderBy('pcia')
            ->pluck('pcia');
    }

    public function getLocalidadesProperty()
    {
        return ObjetivoAipem::select('localidad')
            ->distinct()
            ->whereNotNull('localidad')
            ->where('localidad', '!=', '')
            ->when($this->pcia, function ($query) {
                $query->where('pcia', $this->pcia);
            })
            ->orderBy('localidad')
            ->pluck('localidad');
    }

    public function render()
    {
        $objetivos = ObjetivoAipem::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%'.$this->search.'%')
                        ->orWhere('localidad', 'like', '%'.$this->search.'%')
                        ->orWhere('codobj', 'like', '%'.$this->search.'%')
                        ->orWhere('codcli', 'like', '%'.$this->search.'%')
                        ->orWhere('codsuc', 'like', '%'.$this->search.'%')
                        ->orWhere('codsup', 'like', '%'.$this->search.'%')
                        ->orWhere('codpostal', 'like', '%'.$this->search.'%')
                        ->orWhere('pcia', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->pcia, function ($query) {
                $query->where('pcia', $this->pcia);
            })
            ->when($this->localidad, function ($query) {
                $query->where('localidad', $this->localidad);
            })
            ->orderBy('nombre')
            ->paginate(10);

        return view('livewire.objetivos.manage-objetivos-aipem', [
            'objetivos' => $objetivos,
            'provincias' => $this->provincias,
            'localidades' => $this->localidades,
        ]);
    }
}
