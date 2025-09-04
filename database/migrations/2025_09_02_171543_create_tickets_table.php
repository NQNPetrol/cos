<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->enum('categoria', ['Fallas Técnicas', 'Solicitud de compra', 'Solicitud de instalación', 'Solicitud de mantenimiento', 'Solicitud de equipamiento de vehiculos', 'Reclamos', 'Solicitud de acceso/creacion de usuarios']);
            $table->enum('estado', ['abierto', 'en_proceso', 'cerrado', 'resuelto'])->default('abierto');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'urgente'])->default('media');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes');
            $table->foreignId('asignado_a')->nullable()->constrained('users');
            $table->timestamp('fecha_cierre')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
