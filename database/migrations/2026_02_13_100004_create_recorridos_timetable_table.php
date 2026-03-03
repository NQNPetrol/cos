<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('recorridos_timetable')) { return; }

        Schema::create('recorridos_timetable', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recorrido_id')->nullable()->constrained('recorridos')->onDelete('set null');
            $table->timestamp('fecha_hora_inicio');
            $table->integer('velocidad');
            $table->timestamp('fechahora_fin_est')->nullable();
            $table->integer('duracion_est')->nullable();
            $table->foreignId('patrulla_id')->nullable()->constrained('patrullas')->onDelete('set null');
            $table->foreignId('supervisor_id')->nullable()->constrained('personal')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('velocidad_excedida')->default(false);
            $table->timestamps();

            $table->index('fecha_hora_inicio');
            $table->index('supervisor_id');
        });
    }

    public function down(): void
    {
        //
    }
};
