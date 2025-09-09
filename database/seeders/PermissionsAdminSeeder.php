<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos para clientes
        Permission::firstOrCreate(['name' => 'crear.cliente']);
        Permission::firstOrCreate(['name' => 'editar.cliente']);
        
        // Crear permisos para usuarios
        Permission::firstOrCreate(['name' => 'administrar.usuarios']);
        Permission::firstOrCreate(['name' => 'administrar.roles']);
        
        // Crear permisos para contratos
        Permission::firstOrCreate(['name' => 'ver.contratos']);
        Permission::firstOrCreate(['name' => 'crear.contratos']);
        Permission::firstOrCreate(['name' => 'editar.contratos']);
        Permission::firstOrCreate(['name' => 'eliminar.contratos']);
        
        // Crear permisos para empresas
        Permission::firstOrCreate(['name' => 'crear.empresas']);
        Permission::firstOrCreate(['name' => 'ver.empresas']);
        
        // Crear permisos para objetivos
        Permission::firstOrCreate(['name' => 'ver.objetivos']);
        
        // Crear permisos para seguimientos
        Permission::firstOrCreate(['name' => 'ver.seguimientos']);
        Permission::firstOrCreate(['name' => 'crear.seguimientos']);
        
        // Crear permisos para eventos
        Permission::firstOrCreate(['name' => 'crear.eventos']);
        Permission::firstOrCreate(['name' => 'ver.eventos']);
        Permission::firstOrCreate(['name' => 'editar.eventos']);
        Permission::firstOrCreate(['name' => 'eliminar.eventos']);
        
        // Crear permisos para reportes
        Permission::firstOrCreate(['name' => 'ver.reportes']);
        Permission::firstOrCreate(['name' => 'generar.reportes']);
        
        // Crear permisos para media
        Permission::firstOrCreate(['name' => 'eliminar.media']);
        
        // Crear permisos para personal
        Permission::firstOrCreate(['name' => 'ver.personal']);
        Permission::firstOrCreate(['name' => 'crear.personal']);
        Permission::firstOrCreate(['name' => 'editar.personal']);
        
        // Crear permisos para inventario
        Permission::firstOrCreate(['name' => 'ver.inventario']);
        Permission::firstOrCreate(['name' => 'crear.inventario']);
        Permission::firstOrCreate(['name' => 'editar.inventario']);
        
        // Crear permisos para patrullas
        Permission::firstOrCreate(['name' => 'ver.patrullas']);
        Permission::firstOrCreate(['name' => 'crear.patrullas']);
        
        // Crear permisos para dispositivos
        Permission::firstOrCreate(['name' => 'asignar.dispositivos']);
        
        // Crear permisos para tickets
        Permission::firstOrCreate(['name' => 'ver.tickets']);
        
        // Crear permisos para sistema
        Permission::firstOrCreate(['name' => 'crear.permiso']);
        
        // Crear o obtener el rol de admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Asignar todos los permisos al rol admin
        $permissions = Permission::all();
        $adminRole->syncPermissions($permissions);
        
        $this->command->info('Todos los permisos han sido creados y asignados al rol admin.');
    }
}