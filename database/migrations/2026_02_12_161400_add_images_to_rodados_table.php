<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rodados', function (Blueprint $table) {
            $table->string('imagen_frente_path')->nullable()->after('patente');
            $table->string('imagen_costado_izq_path')->nullable()->after('imagen_frente_path');
            $table->string('imagen_costado_der_path')->nullable()->after('imagen_costado_izq_path');
            $table->string('imagen_dorso_path')->nullable()->after('imagen_costado_der_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rodados', function (Blueprint $table) {
            //
        });
    }
};
