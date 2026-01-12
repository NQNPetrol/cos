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
        Schema::table('cambio_equipo_rodado', function (Blueprint $table) {
            $table->foreignId('dispositivo_id')->nullable()->after('tipo')->constrained('dispositivos')->onDelete('set null');
            $table->text('detalle_equipo_nuevo')->nullable()->after('dispositivo_id');
            $table->text('detalle_equipo_viejo')->nullable()->after('detalle_equipo_nuevo');
            $table->text('motivo')->nullable()->after('detalle_equipo_viejo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cambio_equipo_rodado', function (Blueprint $table) {
            //
        });
    }
};
