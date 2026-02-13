<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recorridos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->foreignId('empresa_asociada_id')->nullable()->constrained('empresas_asociadas')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('objetivos')->nullable();
            $table->json('waypoints')->nullable();
            $table->decimal('longitud_mts', 10, 2)->nullable();
            $table->integer('velocidadmax_permitida')->nullable();
            $table->integer('duracion_promedio')->nullable();
            $table->timestamps();

            $table->index('empresa_asociada_id');
            $table->index('cliente_id');
        });
    }

    public function down(): void
    {
        //
    }
};
