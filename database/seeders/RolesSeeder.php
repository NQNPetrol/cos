<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $roles = ['admin', 'operador', 'supervisor'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Crear permisos ejemplo
        $permisos = [
            'crear.cliente',
            'ver.cliente',
            'editar.cliente',
            'eliminar.cliente',
            'administrar.usuarios',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Asignar todos los permisos al rol admin
        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->syncPermissions(Permission::all());
    }
}
