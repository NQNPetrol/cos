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
        if (Schema::hasTable('patrulla_registros_flota')) { return; }

        Schema::create('patrulla_registros_flota', function (Blueprint $table) {
            $table->id();
            $table->datetime('fecha_registro')->nullable();
            $table->foreignId('patrulla_id')->constrained('patrullas');
            $table->string('objetivo_servicio')->nullable();
            $table->text('observacion')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrulla_registros_flota');
    }
};
