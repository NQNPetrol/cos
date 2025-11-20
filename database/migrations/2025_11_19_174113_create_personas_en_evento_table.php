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
        Schema::create('personas_en_evento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos');
            $table->string('nombre')->nullable();
            $table->string('tipo_doc')->nullable();
            $table->integer('nro_doc')->nullable();
            $table->string('nro_telefono')->nullable();
            $table->text('relacion_evento')->nullable();
            $table->string('descripcion_fisica')->nullable();
            $table->text('comportamiento_observado')->nullable();
            $table->string('tipo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas_en_evento');
    }
};
