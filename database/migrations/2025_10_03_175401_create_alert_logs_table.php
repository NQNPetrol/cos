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
        if (Schema::hasTable('alert_logs')) { return; }

        Schema::create('alert_logs', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_alerta'); // trigger_mision, alerta_tecnica, alerta_hardware
            $table->string('descripcion');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('exito')->default(false);
            $table->integer('codigo_respuesta')->nullable();
            $table->text('mensaje_error')->nullable();
            $table->json('payload')->nullable();
            $table->json('respuesta')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('tipo_alerta');
            $table->index('exito');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_logs');
    }
};
