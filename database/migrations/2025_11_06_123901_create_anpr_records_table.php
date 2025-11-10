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
        Schema::create('anpr_passing_records', function (Blueprint $table) {
            $table->id();
            $table->string('cross_record_syscode');
            $table->string('camera_index_code');
            $table->string('plate_no')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('contact')->nullable();
            $table->text('vehicle_pic_uri')->nullable();
            $table->timestamp('cross_time')->nullable();
            $table->integer('vehicle_color')->nullable();
            $table->integer('vehicle_type')->nullable();
            $table->integer('country')->nullable();
            $table->integer('vehicle_direction_type')->nullable();
            $table->integer('vehicle_brand')->nullable();
            $table->integer('vehicle_speed')->nullable();
            $table->timestamps();

            // Índices para mejorar el rendimiento en búsquedas
            $table->index('plate_no');
            $table->index('cross_time');
            $table->index('camera_index_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anpr_records');
    }
};
