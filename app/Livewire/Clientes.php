<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cliente;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class Clientes extends Component
{
     use WithPagination, WithFileUploads;

    public $nombre;
    public $cuit;
    public $domicilio;
    public $ciudad;
    public $provincia;
    public $categoria;
    public $convenio;
    public $logo;

    public $successMessage = null;

    protected $rules = [
        'nombre'    => 'required|min:3',
        'cuit'      => 'nullable',
        'domicilio' => 'nullable',
        'ciudad'    => 'nullable',
        'provincia' => 'nullable',
        'categoria' => 'nullable',
        'convenio'  => 'nullable',
        'logo'      => 'nullable|image|mimes:png|max:2048',
    ];

    public function save()
    {
        $this->validate();

        $logoPath = null;
        if ($this->logo) {
            $logoPath = $this->logo->store('clientes/logos', 'public');
        }

        Cliente::create([
            'nombre'    => $this->nombre,
            'cuit'      => $this->cuit,
            'domicilio' => $this->domicilio,
            'ciudad'    => $this->ciudad,
            'provincia' => $this->provincia,
            'categoria' => $this->categoria,
            'convenio'  => $this->convenio,
            'logo'      => $logoPath,
        ]);

        $this->reset(['nombre', 'cuit', 'domicilio', 'ciudad', 'provincia', 'categoria', 'convenio', 'logo']);

        $this->successMessage = "Cliente creado exitosamente.";
    }

    public function updating($property)
    {
        $this->successMessage = null;
    }

    public function verEmpresasAsociadas($clienteId)
    {
        return redirect()->route('livewire.cliente-empresas-asociadas', $clienteId);
    }

    public function render()
    {
        return view('livewire.clientes', [
            'clientes' => Cliente::withCount('empresasAsociadas')->paginate(5),
        ]);
    }

    public function delete($clienteId)
    {
        $cliente = Cliente::findOrFail($clienteId);

        if ($cliente->logo && Storage::disk('public')->exists($cliente->logo)) {
            Storage::disk('public')->delete($cliente->logo);
        }
        
        // Verifica si el cliente tiene empresas asociadas antes de eliminar
        if ($cliente->empresasAsociadas()->count() > 0) {
            $this->successMessage = "No se puede eliminar el cliente porque tiene empresas asociadas.";
            return;
        }
        
        $cliente->delete();
        
        $this->successMessage = "Cliente eliminado exitosamente.";
    }
}
