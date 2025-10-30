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
        Schema::create('piloto_flytbase_cliente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('piloto_flytbase_id')->constrained('pilotos_flytbase');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->timestamps();

            $table->unique(['piloto_flytbase_id', 'cliente_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piloto_flytbase_cliente');
    }
};
