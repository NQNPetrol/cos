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
        Schema::table('flytbase_flight_logs', function (Blueprint $table) {
            $table->string('site')->nullable();
            $table->json('event_coordinates')->nullable();
            $table->string('automation')->nullable();
            $table->string('drone_battery')->nullable();
            $table->string('flight_details')->nullable();
            $table->string('event_id')->nullable()->after('id');
            $table->string('message')->nullable()->after('event_id');
            $table->string('severity')->nullable()->after('message');
            $table->string('drone_name')->nullable()->after('severity');
            $table->string('dock_name')->nullable()->after('drone_name');
            $table->string('organization')->nullable()->after('dock_name');

            $table->timestamp('event_timestamp')->nullable()->after('organization');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flytbase_flight_logs', function (Blueprint $table) {
            //
        });
    }
};
