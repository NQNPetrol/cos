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
        if (Schema::hasTable('encoding_devices')) { return; }

        Schema::create('encoding_devices', function (Blueprint $table) {
            $table->id();
            $table->string('encode_dev_index_code')->unique();
            $table->string('name');
            $table->string('ip')->nullable();
            $table->integer('port')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encoding_device');
    }
};
