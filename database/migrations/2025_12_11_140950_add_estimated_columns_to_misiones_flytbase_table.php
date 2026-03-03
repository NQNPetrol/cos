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
        if (! Schema::hasColumn('misiones_flytbase', 'est_total_distance')) {
            Schema::table('misiones_flytbase', function (Blueprint $table) {
                $table->float('est_total_distance')
                    ->nullable()
                    ->after('kmz_file_path')
                    ->comment('Distancia total estimada de la misión en metros');

                $table->integer('est_total_duration')
                    ->nullable()
                    ->after('est_total_distance')
                    ->comment('Duración total estimada de vuelo en segundos');

                $table->integer('waypoints_count')
                    ->nullable()
                    ->after('est_total_duration')
                    ->comment('Recuento de waypoints en la ruta de la misión');
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
