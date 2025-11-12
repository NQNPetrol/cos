<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ========== PERMISOS PARA CLIENTES (VISTA CLIENTE) ==========
        
        // Eventos cliente
        Permission::firstOrCreate(['name' => 'ver.eventos-cliente']);
        Permission::firstOrCreate(['name' => 'crear.eventos-cliente']);
        Permission::firstOrCreate(['name' => 'editar.eventos-cliente']);
        Permission::firstOrCreate(['name' => 'eliminar.eventos-cliente']);
        
        // Reportes cliente
        Permission::firstOrCreate(['name' => 'ver.reportes-cliente']);
        Permission::firstOrCreate(['name' => 'generar.reportes-cliente']);
        
        // Seguimientos cliente
        Permission::firstOrCreate(['name' => 'ver.seguimientos-cliente']);
        
        // Patrullas cliente
        Permission::firstOrCreate(['name' => 'ver.patrullas-cliente']);
        
        // Location cliente
        Permission::firstOrCreate(['name' => 'ver.location-cliente']);
        
        // Tickets cliente
        Permission::firstOrCreate(['name' => 'ver.tickets-cliente']);
        
        // Alertas cliente
        Permission::firstOrCreate(['name' => 'trigger.alertas-cliente']);
        Permission::firstOrCreate(['name' => 'ver.alertas-cliente']);
        
        // Liveview cliente
        Permission::firstOrCreate(['name' => 'ver.liveview-cliente']);
        
        // Flight logs cliente
        Permission::firstOrCreate(['name' => 'ver.flightlogs-cliente']);
        
        // Galería cliente
        // Permission::firstOrCreate(['name' => 'ver.galeria-cliente']);
        
        // Misiones cliente
        Permission::firstOrCreate(['name' => 'crear.peticion-misiones']);

        // ========== PERMISOS PARA ADMINISTRACIÓN (VISTA ADMIN) ==========
        
        // Clientes
        Permission::firstOrCreate(['name' => 'crear.cliente']);
        Permission::firstOrCreate(['name' => 'editar.cliente']);
        
        // Permisos
        Permission::firstOrCreate(['name' => 'crear.permiso']);
        
        // Usuarios
        Permission::firstOrCreate(['name' => 'administrar.usuarios']);
        Permission::firstOrCreate(['name' => 'administrar.roles']);
        Permission::firstOrCreate(['name' => 'resetar.contraseña']);
        
        // Asignación clientes
        Permission::firstOrCreate(['name' => 'asignar.clientes']);
        
        // Contratos
        Permission::firstOrCreate(['name' => 'ver.contratos']);
        Permission::firstOrCreate(['name' => 'crear.contratos']);
        Permission::firstOrCreate(['name' => 'editar.contratos']);
        Permission::firstOrCreate(['name' => 'eliminar.contratos']);
        
        // Empresas
        Permission::firstOrCreate(['name' => 'crear.empresas']);
        Permission::firstOrCreate(['name' => 'ver.empresas']);
        
        // Objetivos
        Permission::firstOrCreate(['name' => 'ver.objetivos']);
        
        // Seguimientos
        Permission::firstOrCreate(['name' => 'ver.seguimientos']);
        Permission::firstOrCreate(['name' => 'crear.seguimientos']);
        
        // Eventos
        Permission::firstOrCreate(['name' => 'ver.eventos']);
        Permission::firstOrCreate(['name' => 'crear.eventos']);
        Permission::firstOrCreate(['name' => 'editar.eventos']);
        Permission::firstOrCreate(['name' => 'eliminar.eventos']);
        
        // Reportes
        Permission::firstOrCreate(['name' => 'ver.reportes']);
        Permission::firstOrCreate(['name' => 'generar.reportes']);
        
        // Media
        Permission::firstOrCreate(['name' => 'eliminar.media']);
        
        // Personal
        Permission::firstOrCreate(['name' => 'ver.personal']);
        Permission::firstOrCreate(['name' => 'crear.personal']);
        Permission::firstOrCreate(['name' => 'editar.personal']);
        
        // Inventario
        Permission::firstOrCreate(['name' => 'ver.inventario']);
        Permission::firstOrCreate(['name' => 'crear.inventario']);
        Permission::firstOrCreate(['name' => 'editar.inventario']);
        
        // Patrullas
        Permission::firstOrCreate(['name' => 'ver.patrullas']);
        Permission::firstOrCreate(['name' => 'crear.patrullas']);
        
        // Location
        Permission::firstOrCreate(['name' => 'ver.location']);
        
        // Dispositivos
        Permission::firstOrCreate(['name' => 'asignar.dispositivos']);
        
        // Tickets
        Permission::firstOrCreate(['name' => 'ver.tickets']);
        
        // Notificaciones
        Permission::firstOrCreate(['name' => 'administrar.notificaciones']);
        Permission::firstOrCreate(['name' => 'crear.notificaciones']);
        Permission::firstOrCreate(['name' => 'crear.notif']);
        
        // Cámaras
        Permission::firstOrCreate(['name' => 'ver.camaras']);
        
        // Alertas
        Permission::firstOrCreate(['name' => 'ver.alertas']);
        Permission::firstOrCreate(['name' => 'trigger.alertas']);
        
        // Misiones Flytbase
        Permission::firstOrCreate(['name' => 'ver.misiones']);
        Permission::firstOrCreate(['name' => 'crear.misiones']);
        
        // Liveview
        Permission::firstOrCreate(['name' => 'ver.liveview']);
        
        // Drones
        Permission::firstOrCreate(['name' => 'ver.droneInfo']);
        Permission::firstOrCreate(['name' => 'ver.drones']);
        Permission::firstOrCreate(['name' => 'crear.drones']);
        Permission::firstOrCreate(['name' => 'eliminar.drones']);
        
        // Docks
        Permission::firstOrCreate(['name' => 'ver.docks']);
        Permission::firstOrCreate(['name' => 'crear.docks']);
        Permission::firstOrCreate(['name' => 'eliminar.docks']);
        
        // Galería
        Permission::firstOrCreate(['name' => 'ver.galeria']);
        Permission::firstOrCreate(['name' => 'importar.galeria']);
        
        // Pilotos
        Permission::firstOrCreate(['name' => 'ver.pilotos']);
        
        // Peticiones
        Permission::firstOrCreate(['name' => 'ver.peticiones']);
        
        // Sites
        Permission::firstOrCreate(['name' => 'ver.sites']);
        
        // Objetivos AIPEM
        Permission::firstOrCreate(['name' => 'ver.objetivos-aipem']);
        
        // ANPR
        Permission::firstOrCreate(['name' => 'ver.registros-anpr']);
        Permission::firstOrCreate(['name' => 'importar.registros-anpr']);
        
        // Crear permisos para sistema
        Permission::firstOrCreate(['name' => 'crear.permiso']);
        
        // Crear o obtener roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $operadorRole = Role::firstOrCreate(['name' => 'operador']);
        $clienteRole = Role::firstOrCreate(['name' => 'cliente']);

        // Asignar todos los permisos al rol admin
        $adminRole->syncPermissions(Permission::all());
        
        $operadorPermissions = [
            // Clientes
            'crear.cliente', 'editar.cliente',
            // Usuarios
            'administrar.usuarios',
            // Asignación clientes
            'asignar.clientes',
            // Contratos
            'ver.contratos', 'crear.contratos', 'editar.contratos',
            // Empresas
            'crear.empresas', 'ver.empresas',
            // Objetivos
            'ver.objetivos',
            // Seguimientos
            'ver.seguimientos', 'crear.seguimientos',
            // Eventos
            'ver.eventos', 'crear.eventos', 'editar.eventos',
            // Reportes
            'ver.reportes', 'generar.reportes',
            // Personal
            'ver.personal', 'crear.personal', 'editar.personal',
            // Inventario
            'ver.inventario', 'crear.inventario', 'editar.inventario',
            // Patrullas
            'ver.patrullas', 'crear.patrullas',
            // Location
            'ver.location',
            // Dispositivos
            'asignar.dispositivos',
            // Tickets
            'ver.tickets',
            // Notificaciones
            'administrar.notificaciones', 'crear.notificaciones', 'crear.notif',
            // Cámaras
            'ver.camaras',
            // Alertas
            'ver.alertas', 'trigger.alertas',
            // Misiones Flytbase
            'ver.misiones', 'crear.misiones',
            // Liveview
            'ver.liveview',
            // Drones
            'ver.droneInfo', 'ver.drones', 'crear.drones',
            // Docks
            'ver.docks', 'crear.docks',
            // Galería
            'ver.galeria', 'importar.galeria',
            // Pilotos
            'ver.pilotos',
            // Peticiones
            'ver.peticiones',
            // Sites
            'ver.sites',
            // Objetivos AIPEM
            'ver.objetivos-aipem',
            // ANPR
            'ver.registros-anpr', 'importar.registros-anpr',
        ];

        $operadorRole->syncPermissions($operadorPermissions);

        $clientePermissions = [
            'ver.eventos-cliente', 'crear.eventos-cliente', 'editar.eventos-cliente', 'eliminar.eventos-cliente',
            'ver.reportes-cliente', 'generar.reportes-cliente',
            'ver.seguimientos-cliente',
            'ver.patrullas-cliente',
            'ver.location-cliente',
            'ver.tickets-cliente',
            'trigger.alertas-cliente',
            'ver.liveview-cliente',
            'ver.flightlogs-cliente',
            'ver.galeria-cliente',
            'crear.peticion-misiones',
        ];

        $clienteRole->syncPermissions($clientePermissions);
        
        $this->command->info('Todos los permisos han sido creados y asignados a sus roles.');
        $this->command->info('Total de permisos creados: ' . Permission::count());
        $this->command->info('- Admin: ' . $adminRole->permissions->count() . ' permisos');
        $this->command->info('- Operador: ' . count($operadorPermissions) . ' permisos');
        $this->command->info('- Cliente: ' . count($clientePermissions) . ' permisos');
    }
}