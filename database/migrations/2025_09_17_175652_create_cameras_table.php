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
        if (Schema::hasTable('cameras')) { return; }

        Schema::create('cameras', function (Blueprint $table) {
            $table->id();
            $table->string('camera_index_code')->unique();
            $table->string('camera_name');
            $table->string('capability_set')->nullable();
            $table->string('dev_resource_type')->default('encodeDevice');
            $table->string('encode_dev_index_code')->nullable();
            $table->string('record_type')->nullable();
            $table->string('record_location')->nullable();
            $table->string('region_index_code')->nullable();
            $table->string('site_index_code')->default('0');
            $table->integer('status')->default(1);
            $table->boolean('is_support_wake_up')->default(false);
            $table->integer('wake_up_status')->default(0);
            $table->timestamps();

            // Índices para consultas frecuentes
            $table->index('camera_index_code');
            $table->index('encode_dev_index_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cameras');
    }
};
