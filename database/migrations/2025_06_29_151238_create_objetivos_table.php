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
        if (Schema::hasTable('objetivos')) {
            return;
        }

        Schema::create('objetivos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
            $table->foreignId('contrato_id')->constrained()->onDelete('cascade');
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->string('localidad')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('objetivos');
    }
};
