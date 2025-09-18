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
        Schema::create('mobile_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('mobile_vehicle_index_code')->unique();
            $table->string('mobile_vehicle_name');
            $table->integer('status');
            $table->string('dev_index_code');
            $table->string('region_index_code');
            $table->string('plate_no');
            $table->string('person_family_name');
            $table->string('person_given_name');
            $table->string('person_name');
            $table->string('phone_no');
            $table->integer('vehicle_type');
            $table->integer('vehicle_brand');
            $table->integer('vehicle_color');

            $table->foreignId('')
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_vehicles');
    }
};
