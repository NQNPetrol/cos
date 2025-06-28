<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionsToRoles extends Component
{

     public $roles;
    public $permissions;
    public $selectedPermissions = [];

    public $currentRoleId;

    public $successMessage = null;

    public function mount()
    {
        $this->roles = Role::all();
        $this->permissions = Permission::all();
    }

    public function loadPermissions($roleId)
    {
        $this->currentRoleId = $roleId;

        $role = Role::findOrFail($roleId);
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();

        $this->successMessage = null;
    }

    public function updatePermissions()
    {
        $role = Role::findOrFail($this->currentRoleId);
        $role->syncPermissions($this->selectedPermissions);

        $this->successMessage = "Permisos actualizados para el rol: {$role->name}";

        // Opcional: recargar datos
        $this->roles = Role::all();
    }

    public function render()
    {
        return view('livewire.admin.assign-permissions-to-roles');
    }
}
