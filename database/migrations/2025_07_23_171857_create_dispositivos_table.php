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
        Schema::create('dispositivos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->comment('Nombre descriptivo del dispositivo');
            $table->enum('categoria', [
                'dispositivos de seguridad',
                'vehiculos',
                'dispositivos tecnológicos'
            ]);
            $table->enum('tipo', [
                'cámara_ip', 
                'nvr_dvr', 
                'control_acceso', 
                'intercomunicador', 
                'switch_poe', 
                'sensor_alarma',
                'dispositivo_reconocimiento',
                'gps',
                'otros'
            ]);
            $table->string('modelo')->nullable();
            $table->string('direccion_ip')->nullable();
            $table->string('puerto')->nullable();
            $table->string('version_software')->nullable();
            $table->string('direccion_ipv6')->nullable();
            $table->enum('estado_hikconnect', ['conectado', 'a conectar', 'en proceso']);
            
            // Campos adicionales para el inventario
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->string('ubicacion')->nullable(); // Ubicación física del dispositivo
            $table->text('observaciones')->nullable(); // Notas adicionales
            $table->boolean('necesita_mantenimiento')->default(false);
            $table->boolean('necesita_actualizacion')->default(false);
            $table->date('fecha_instalacion')->nullable();
            $table->date('ultimo_mantenimiento')->nullable();
            $table->date('proximo_mantenimiento')->nullable();
            $table->enum('estado_inventario', ['En stock', 'Instalado', 'En mantenimiento', 'Dado de baja'])->default('En stock');

            $table->timestamps();

            //indice
            $table->index(['estado_inventario', 'cliente_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositivos');
    }
};
