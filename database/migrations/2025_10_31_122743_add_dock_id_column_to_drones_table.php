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
        Schema::table('drones_flytbase', function (Blueprint $table) {
            $table->foreignId('dock_id')->nullable()->constrained('flytbase_docks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drones_flytbase', function (Blueprint $table) {
            //
        });
    }
};
