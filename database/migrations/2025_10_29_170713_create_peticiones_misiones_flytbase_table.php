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
        Schema::create('peticiones_misiones_flytbase', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('user_id')->constrained('users')->comment('Usuario cliente que hizo la petición');
            $table->foreignId('site_id')->nullable()->constrained('flytbase_sites')->onDelete('set null');
            $table->foreignId('drone_id')->nullable()->constrained('drones_flytbase')->onDelete('set null');

            $table->decimal('route_altitude', 6, 2)->default(35.00);
            $table->decimal('route_speed', 5, 2)->default(5.33);
            $table->enum('route_waypoint_type', [
                'linear_route',
                'transits_waypoint',
                'curved_route_drone_stops',
                'curved_route_drone_continues',
            ])->default('linear_route');
            $table->json('waypoints')->nullable();

            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->foreignId('revisado_por')->nullable()->constrained('users')->comment('Operador que revisó la petición');
            $table->text('observaciones')->nullable();

            $table->foreignId('mision_aprobada_id')->nullable()->constrained('misiones_flytbase')->comment('ID de la misión creada tras aprobación');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peticiones_misiones_flytbase');
    }
};
