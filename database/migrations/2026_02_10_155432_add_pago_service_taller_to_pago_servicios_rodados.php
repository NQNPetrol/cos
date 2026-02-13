<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar pago_service y pago_taller al ENUM de tipo
        DB::statement("ALTER TABLE pago_servicios_rodados MODIFY COLUMN tipo ENUM('pago_patente', 'pago_alquiler', 'pago_proveedor', 'pago_seguro', 'pago_servicio_starlink', 'pago_vtv', 'pagos_adicionales', 'pago_service', 'pago_taller') DEFAULT 'pago_patente'");

        // Hacer fecha_pago nullable para pagos pendientes
        Schema::table('pago_servicios_rodados', function (Blueprint $table) {
            $table->date('fecha_pago')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //

    }
};
