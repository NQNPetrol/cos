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
        if (Schema::hasTable('pilotos_flytbase')) { return; }

        Schema::create('pilotos_flytbase', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('token');
            $table->foreignId('user_id')->nullable()->constrained('users')->ondelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilotos_flytbase');
    }
};
