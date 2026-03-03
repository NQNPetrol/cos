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
        if (! Schema::hasColumn('misiones_flytbase', 'dock_id')) {
            Schema::table('misiones_flytbase', function (Blueprint $table) {
                $table->foreignId('dock_id')
                    ->after('drone_id')
                    ->nullable()
                    ->constrained('flytbase_docks')
                    ->onDelete('set null');
                $table->foreignId('site_id')
                    ->after('dock_id')
                    ->nullable()
                    ->constrained('flytbase_sites')
                    ->onDelete('set null');
                $table->decimal('route_altitude', 6, 2)
                    ->after('site_id')
                    ->default(35.00)
                    ->comment('Altitud de la ruta en metros');
                $table->decimal('route_speed', 5, 2)
                    ->after('route_altitude')
                    ->default(5.33)
                    ->comment('Velocidad de la ruta en m/s');
                $table->enum('route_waypoint_type', ['linear_route', 'transits_waypoint', 'curved_route_drone_stops', 'curved_route_drone_continues'])
                    ->after('route_speed')
                    ->default('linear_route');
                $table->json('waypoints')
                    ->after('route_waypoint_type')
                    ->nullable()
                    ->comment('Array de waypoints con coordenadas y acciones');
                $table->text('observaciones')
                    ->nullable()
                    ->comment('Observaciones de la misión');
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
