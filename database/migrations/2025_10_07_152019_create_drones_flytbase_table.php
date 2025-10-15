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
        Schema::create('drones_flytbase', function (Blueprint $table) {
            $table->id();
            $table->string('drone'); // Nombre del drone (ej: matrice4td-1)
            $table->string('share_url')->nullable(); // URL de Guest Sharing
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['drone', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drones_flytbase');
    }
};
