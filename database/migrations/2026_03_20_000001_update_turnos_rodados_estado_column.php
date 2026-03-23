<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Change enum to string for flexibility with new states
        Schema::table('turnos_rodados', function (Blueprint $table) {
            $table->string('estado', 30)->default('programado')->change();
        });

        // Migrate existing data
        DB::table('turnos_rodados')
            ->where('estado', 'pendiente')
            ->update(['estado' => 'programado']);

        DB::table('turnos_rodados')
            ->where('estado', 'completado')
            ->update(['estado' => 'asistido']);

        DB::table('turnos_rodados')
            ->where('estado', 'atendido')
            ->update(['estado' => 'asistido']);
    }

    public function down(): void
    {
        DB::table('turnos_rodados')
            ->where('estado', 'programado')
            ->update(['estado' => 'pendiente']);

        DB::table('turnos_rodados')
            ->where('estado', 'asistido')
            ->update(['estado' => 'completado']);

        DB::table('turnos_rodados')
            ->where('estado', 'perdido')
            ->update(['estado' => 'pendiente']);

        Schema::table('turnos_rodados', function (Blueprint $table) {
            $table->enum('estado', ['pendiente', 'completado'])->default('pendiente')->change();
        });
    }
};
