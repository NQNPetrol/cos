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
        Schema::create('turnos_rodados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rodado_id')->constrained('rodados')->onDelete('cascade');
            $table->foreignId('taller_id')->constrained('talleres')->onDelete('cascade');
            $table->enum('tipo', ['turno_service', 'turno_mecanico', 'cambio_equipo', 'turno_taller']);
            $table->dateTime('fecha_hora');
            $table->string('encargado_dejar')->nullable();
            $table->string('encargado_retirar')->nullable();
            $table->string('tipo_reparacion')->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('cubre_servicio')->default(false)->comment('Solo para turno_mecanico');
            $table->string('tipo_equipo')->nullable()->comment('Solo para cambio_equipo');
            $table->string('tipo_cubierta')->nullable()->comment('Solo para cambio_equipo tipo cubiertas');
            $table->decimal('pago_mano_obra', 10, 2)->nullable()->comment('Solo para cambio_equipo');
            $table->string('factura_path')->nullable();
            $table->string('comprobante_pago_path')->nullable();
            $table->date('fecha_factura')->nullable();
            $table->integer('dias_vencimiento')->nullable();
            $table->date('fecha_vencimiento_pago')->nullable();
            $table->enum('estado', ['pendiente', 'completado'])->default('pendiente');
            $table->timestamps();
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
