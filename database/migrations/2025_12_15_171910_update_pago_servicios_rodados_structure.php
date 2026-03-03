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
        if (Schema::hasColumn('pago_servicios_rodados', 'mes')) {
            Schema::table('pago_servicios_rodados', function (Blueprint $table) {
                $table->dropColumn(['mes', 'año']);
            });
        }

        if (! Schema::hasColumn('pago_servicios_rodados', 'moneda')) {
            Schema::table('pago_servicios_rodados', function (Blueprint $table) {
                $table->enum('moneda', ['ARS', 'USD'])->default('ARS')->after('monto');
            });
        }

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
