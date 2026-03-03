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
        if (! Schema::hasColumn('alert_logs', 'mision_id')) {
            Schema::table('alert_logs', function (Blueprint $table) {
                $table->foreignId('mision_id')->nullable()->constrained('misiones_flytbase');
                $table->index('mision_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alert_logs', function (Blueprint $table) {
            //
        });
    }
};
