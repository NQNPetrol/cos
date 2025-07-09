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
        Schema::table('contratos', function (Blueprint $table) {
            // Quitar columnas
            $table->dropColumn(['fecha_fin', 'detalles']);

            // Modificar columnas a nullable
            $table->string('localidad')->nullable()->change();
            $table->string('provincia')->nullable()->change();
            $table->string('observaciones')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
            // Restaurar columnas quitadas
            $table->date('fecha_fin')->nullable();
            $table->string('detalles')->nullable();

            // Volver a not nullable
            $table->string('localidad')->nullable(false)->change();
            $table->string('provincia')->nullable(false)->change();
            $table->string('observaciones')->nullable(false)->change();
        });
    }
};
