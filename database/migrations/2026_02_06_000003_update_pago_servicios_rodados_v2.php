<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pago_servicios_rodados', function (Blueprint $table) {
            $table->foreignId('servicio_usuario_id')->nullable()->constrained('servicios_usuario')->onDelete('set null');
            $table->foreignId('turno_rodado_id')->nullable()->constrained('turnos_rodados')->onDelete('set null');
            $table->string('estado')->default('pendiente');
            $table->date('fecha_vencimiento')->nullable();
            $table->text('observaciones')->nullable();
        });
    }

    public function down(): void
    {
        // Schema::table('pago_servicios_rodados', function (Blueprint $table) {
        //     $table->dropForeign(['servicio_usuario_id']);
        //     $table->dropForeign(['turno_rodado_id']);
        //     $table->dropColumn(['servicio_usuario_id', 'turno_rodado_id', 'estado', 'fecha_vencimiento', 'observaciones']);
        // });
    }
};
