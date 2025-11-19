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
        Schema::create('patrulla_sistemas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patrulla_id')->constrained('patrullas');
            $table->foreignId('sistema_id')->constrained('sistemas');
            $table->date('fecha_registro')->nullable();
            $table->integer('nro_interno')->nullable();
            $table->foreignId('registra_user')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrulla_sistemas');
    }
};
