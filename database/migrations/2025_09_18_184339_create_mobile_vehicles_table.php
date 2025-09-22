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
            $table->string('person_family_name')->nullable();
            $table->string('person_given_name')->nullable();
            $table->string('person_name')->nullable();
            $table->string('phone_no')->nullable();
            $table->integer('vehicle_type')->nullable();
            $table->integer('vehicle_brand')->nullable();
            $table->integer('vehicle_color')->nullable();

            $table->foreignId('patrulla_id')->nullable()->constrained('patrullas')->onDelete('set null');
            $table->timestamps();

            $table->index('plate_no');
            $table->index('status');
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
