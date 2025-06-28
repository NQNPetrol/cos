<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class ManagePermissions extends Component
{
    public $name;
    public $permissions;

    public function mount()
    {
        $this->loadPermissions();
    }

    public function loadPermissions()
    {
        $this->permissions = Permission::all();
    }

    public function createPermission()
    {
        $this->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        Permission::create(['name' => $this->name]);

        $this->name = '';
        $this->loadPermissions();

        session()->flash('success', 'Permiso creado correctamente.');
    }

    public function deletePermission($id)
    {
        $perm = Permission::findOrFail($id);
        $perm->delete();
        $this->loadPermissions();

        session()->flash('success', 'Permiso eliminado.');
    }

    public function render()
    {
        return view('livewire.admin.manage-permissions');
    }
}
