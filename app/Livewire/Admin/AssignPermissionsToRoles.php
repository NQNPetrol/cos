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

    public function mount($selectedRole = null)
    {
        $this->roles = Role::all();
        $this->permissions = Permission::all();
        $this->permissions = Permission::where('guard_name', 'web')->get();

        if ($selectedRole) {
            $this->currentRoleId = $selectedRole;
            $this->loadPermissions($selectedRole);
        }
    }

    public function loadPermissions($roleId)
    {
        $this->currentRoleId = $roleId;

        if ($roleId) {
            $role = Role::findOrFail($roleId);
            
            $this->selectedPermissions = $role->permissions()
                ->where('guard_name', 'web')
                ->pluck('name')
                ->toArray();
        } else {
            $this->selectedPermissions = [];
        }

        $this->successMessage = null;
    }
    

    public function updatePermissions()
    {
        $this->validate([
            'currentRoleId' => 'required|exists:roles,id'
        ]);

        $role = Role::findOrFail($this->currentRoleId);

        $validPermissions = Permission::where('guard_name', 'web')
            ->whereIn('name', $this->selectedPermissions)
            ->pluck('name')
            ->toArray();

        $role->syncPermissions($validPermissions);

        $this->successMessage = "Permisos actualizados para el rol: {$role->name}";

        // Opcional: recargar datos
        $this->roles = Role::all();
    }

    public function createNewRole()
    {
        $this->validate([
            'newRoleName' => 'required|unique:roles,name'
        ]);

        $role = Role::create([
            'name' => $this->newRoleName,
            'guard_name' => 'web' // Especificar el guard_name
        ]);
        
        $this->newRoleName = '';
        $this->showCreateRoleModal = false;
        
        // Recargar roles y seleccionar automáticamente el nuevo rol
        $this->roles = Role::all();
        $this->currentRoleId = $role->id;
        $this->selectedPermissions = [];
        
        $this->successMessage = "Rol creado correctamente. Ahora puedes asignarle permisos.";
    }
    public function loadRoles()
    {
        $this->roles = Role::all();
    }

    public function render()
    {
        return view('livewire.admin.assign-permissions-to-roles');
    }
}
