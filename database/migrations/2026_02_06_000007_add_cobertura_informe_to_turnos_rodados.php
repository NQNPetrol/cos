<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('turnos_rodados', 'cobertura_estado')) {
            Schema::table('turnos_rodados', function (Blueprint $table) {
                $table->string('cobertura_estado')->default('pendiente');
                $table->string('informe_path')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Schema::table('turnos_rodados', function (Blueprint $table) {
        //     $table->dropColumn(['cobertura_estado', 'informe_path']);
        // });
    }
};
