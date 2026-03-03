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
        if (! Schema::hasColumn('misiones_flytbase', 'drone_id')) {
            Schema::table('misiones_flytbase', function (Blueprint $table) {
                $table->foreignId('drone_id')
                    ->after('cliente_id')
                    ->nullable()
                    ->constrained('drones_flytbase')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('misiones_flytbase', function (Blueprint $table) {
            //
        });
    }
};
