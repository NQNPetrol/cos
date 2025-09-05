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

        if ($selectedRole) {
            $this->currentRoleId = $selectedRole;
            $this->loadPermissions($selectedRole);
        }
    }

    public function loadPermissions($roleId)
    {
        $this->currentRoleId = $roleId;

        if ($roleId) {
            $role = Role::where('id', $roleId)
                        ->where('guard_name', 'web')
                        ->firstOrFail();
            
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

        try{
            $role = Role::where('id', $this->currentRoleId)
                        ->where('guard_name', 'web')
                        ->firstOrFail();

            $permissionsIds = [];
            foreach ($this->selectedPermissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)
                                      ->where('guard_name', 'web')
                                      ->first();
                if ($permission) {
                    $permissionIds[] = $permission->id;
                }
            }

        $role->syncPermissions($permissionIds);

        $this->successMessage = "Permisos actualizados para el rol: {$role->name}";

        // Opcional: recargar datos
        $this->roles = Role::all();
        $this->permissions = Permission::all();

        } catch (\Exception $e) {
            $this->addError('permissions', 'Error al actualizar permisos: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.assign-permissions-to-roles');
    }
}
