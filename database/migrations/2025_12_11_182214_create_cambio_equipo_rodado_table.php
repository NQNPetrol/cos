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
        if (Schema::hasTable('cambio_equipo_rodado')) { return; }

        Schema::create('cambio_equipo_rodado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rodado_id')->constrained('rodados')->onDelete('cascade');
            $table->foreignId('taller_id')->constrained('talleres')->onDelete('cascade');
            $table->enum('tipo', ['cubiertas', 'antena_starlink', 'camara_mobil', 'dvr'])->default('cubiertas');
            $table->dateTime('fecha_hora_estimada');
            $table->string('tipo_cubierta')->nullable()->comment('Solo para tipo cubiertas');
            $table->decimal('pago_mano_obra', 10, 2);
            $table->string('factura_path')->nullable();
            $table->string('comprobante_pago_path')->nullable();
            $table->integer('kilometraje_en_cambio');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('cambio_equipo_rodado');
    }
};
