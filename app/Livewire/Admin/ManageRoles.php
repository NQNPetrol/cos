<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class ManageRoles extends Component
{
    public $name;
    public $roles;
    public $editingRoleId = null;
    public $editName = '';

    public function mount()
    {
        $this->loadRoles();
    
    }

    public function loadRoles()
    {
        $this->roles = Role::all();
    }

    public function createRole()
    {
        $this->validate([
            'name' => 'required|unique:roles,name'
        ]);

        // Asegurar que siempre se use guard_name = 'web'
        Role::create([
            'name' => $this->name,
            'guard_name' => 'web'
        ]);
        
        $this->name = '';
        $this->loadRoles();
        session()->flash('success', 'Rol creado correctamente.');
    }

    public function startEdit($roleId)
    {
        $role = Role::findOrFail($roleId);
        $this->editingRoleId = $roleId;
        $this->editName = $role->name;
    }

    public function cancelEdit()
    {
        $this->editingRoleId = null;
        $this->editName = '';
    }

    public function updateRole($roleId)
    {
        $this->validate([
            'editName' => [
                'required',
                Rule::unique('roles', 'name')->ignore($roleId)
            ]
        ]);

        $role = Role::findOrFail($roleId);
        $role->update([
            'name' => $this->editName,
            'guard_name' => 'web'
        ]);
        
        $this->cancelEdit();
        $this->loadRoles();

        session()->flash('success', 'Rol actualizado correctamente.');
    }

    public function deleteRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        
        // Prevenir eliminación de roles del sistema si es necesario
        if (in_array($role->name, ['admin', 'super-admin'])) {
            session()->flash('error', 'No se puede eliminar este rol del sistema.');
            return;
        }

        $role->delete();
        $this->loadRoles();

        session()->flash('success', 'Rol eliminado correctamente.');
    }

    public function redirectToPermissions($roleId)
    {
        $role = Role::findOrFail($roleId);
        return redirect()->route('asignar.permisos', ['role' => $roleId]);
    }

    public function render()
    {
        return view('livewire.admin.manage-roles');
    }
}
