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
        Schema::table('flytbase_flight_logs', function (Blueprint $table) {
            $table->timestamp('trigger_senttime')->nullable()->after('flight_starttime');
            $table->index('trigger_senttime');
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
