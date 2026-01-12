<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pago_servicios_rodados', function (Blueprint $table) {
            // Drop mes and año columns
            $table->dropColumn(['mes', 'año']);
            
            // Add moneda enum column
            $table->enum('moneda', ['ARS', 'USD'])->default('ARS')->after('monto');
        });

        // Update tipo enum - Laravel doesn't support modifying enums directly, so we use DB::statement
        DB::statement("ALTER TABLE pago_servicios_rodados MODIFY COLUMN tipo ENUM('pago_patente', 'pago_alquiler', 'pago_proveedor', 'pago_a_proveedor', 'pago_seguro', 'pago_servicio_starlink', 'pago_vtv', 'pagos_adicionales') DEFAULT 'pago_patente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
