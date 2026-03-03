<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('pago_servicios_rodados')) { return; }

        Schema::create('pago_servicios_rodados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rodado_id')->constrained('rodados')->onDelete('cascade');
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->onDelete('set null');
            $table->enum('tipo', ['pago_patente', 'pago_alquiler', 'pago_proveedor'])->default('pago_patente');
            $table->integer('mes');
            $table->integer('año');
            $table->decimal('monto', 10, 2);
            $table->decimal('monto_service', 10, 2)->nullable()->comment('Solo para pago_alquiler');
            $table->string('factura_path')->nullable();
            $table->string('comprobante_pago_path')->nullable();
            $table->date('fecha_pago');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('pago_servicios_rodados');
    }
};
