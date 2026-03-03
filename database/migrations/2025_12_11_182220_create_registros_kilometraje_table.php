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
        if (Schema::hasTable('registros_kilometraje')) { return; }

        Schema::create('registros_kilometraje', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rodado_id')->constrained('rodados')->onDelete('cascade');
            $table->integer('kilometraje');
            $table->date('fecha_registro');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('registros_kilometraje');
    }
};
