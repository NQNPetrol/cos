<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('dispositivo_patrulla', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patrulla_id')->constrained('patrullas')->onDelete('cascade');
            $table->foreignId('dispositivo_id')->constrained('dispositivos')->onDelete('cascade');
            $table->date('fecha_asignacion');
            $table->timestamps();

            $table->unique(['patrulla_id', 'dispositivo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositivo_patrulla');
    }
};
