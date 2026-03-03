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
        if (Schema::hasColumn('flytbase_flight_logs', 'piloto_flytbase_id')) {
            Schema::table('flytbase_flight_logs', function (Blueprint $table) {
                $table->unsignedBigInteger('piloto_flytbase_id')->nullable()->change();
            });
        }
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
