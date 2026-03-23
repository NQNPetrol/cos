<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Ejecutar en producción tras migrate (p. ej. deploy.yml: db:seed --class=ProductionSeeder).
 *
 * Solo seeders idempotentes (firstOrCreate / syncPermissions): seguro correrlo en cada deploy.
 * Quedan fuera datos de demo con IDs fijos: PatrullasSeeder, EventosSeeder.
 * RolesSeeder no se incluye: PermissionsSeeder ya crea roles y asigna permisos.
 */
class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
            SistemasSeeder::class,
            CategoriaSeeder::class,
        ]);
    }
}
