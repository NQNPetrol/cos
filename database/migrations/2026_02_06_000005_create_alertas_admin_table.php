<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alertas_admin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->string('tipo');
            $table->json('trigger_config')->nullable();
            $table->json('accion_config')->nullable();
            $table->foreignId('rodado_id')->nullable()->constrained('rodados')->onDelete('set null');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->integer('km_intervalo')->nullable();
            $table->date('fecha_alerta')->nullable();
            $table->boolean('recurrente')->default(false);
            $table->string('frecuencia_recurrencia')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamp('ultima_ejecucion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Schema::dropIfExists('alertas_admin');
    }
};
