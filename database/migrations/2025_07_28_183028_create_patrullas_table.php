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
        if (Schema::hasTable('patrullas')) { return; }

        Schema::create('patrullas', function (Blueprint $table) {
            $table->id();
            $table->string('patente')->unique();
            $table->string('modelo');
            $table->string('color');
            $table->string('estado')->default('operativa');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrullas');
    }
};
