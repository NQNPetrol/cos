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
        Schema::create('flytbase_flight_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('piloto_flytbase_id')
                ->constrained('pilotos_flytbase');
            $table->foreignId('mision_flytbase_id')
                ->nullable()
                ->constrained('misiones_flytbase');
            $table->foreignId('alert_log_id')
                ->nullable()
                ->constrained('alert_logs');
            $table->dateTime('flight_starttime')->nullable();
            $table->dateTime('flight_endtime')->nullable();
            $table->integer('flight_time')->nullable()->comment('Duración del vuelo en segundos');
            $table->float('total_distance')->nullable()->comment('Distancia total en metros');
            $table->enum('estado', ['completado', 'interrumpido', 'en_proceso'])
                ->default('en_proceso')
                ->comment('Estado del vuelo: completado, interrumpido, en_proceso');
            $table->timestamps();

            $table->index('piloto_flytbase_id');
            $table->index('mision_flytbase_id');
            $table->index('alert_log_id');
            $table->index('flight_starttime');
            $table->index('flight_endtime');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flytbase_flight_logs');
    }
};
