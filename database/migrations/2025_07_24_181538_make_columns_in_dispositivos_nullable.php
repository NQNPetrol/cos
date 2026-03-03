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
        if (Schema::hasColumn('dispositivos', 'nombre')) {
            Schema::table('dispositivos', function (Blueprint $table) {
                // haciendo nullable las columnas que pueden no usarse
                $table->string('nombre')->nullable()->comment('Nombre descriptivo del dispositivo')->change();
                $table->string('modelo')->nullable()->change();
                $table->string('direccion_ip')->nullable()->change();
                $table->string('puerto')->nullable()->change();
                $table->string('version_software')->nullable()->change();
                $table->string('direccion_ipv6')->nullable()->change();
                $table->string('ubicacion')->nullable()->change();
                $table->text('observaciones')->nullable()->change();
                $table->text('categoria')->nullable()->change();
                $table->text('tipo')->nullable()->change();
                $table->text('estado_hikconnect')->nullable()->change();
                $table->text('necesita_mantenimiento')->nullable()->change();
                $table->text('necesita_actualizacion')->nullable()->change();
                $table->date('fecha_instalacion')->nullable()->change();
                $table->date('ultimo_mantenimiento')->nullable()->change();
                $table->date('proximo_mantenimiento')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispositivos', function (Blueprint $table) {
            //
        });
    }
};
