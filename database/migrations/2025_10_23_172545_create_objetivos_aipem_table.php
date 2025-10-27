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
        Schema::create('objetivos_aipem', function (Blueprint $table) {
            $table->id();
            $table->string('codobj');
            $table->string('nombre', 255);
            $table->date('fecha_alta')->nullable();
            $table->date('fecha_baja')->nullable();
            $table->string('codcli', 4);
            $table->string('codsuc', 4)->nullable();
            $table->string('codsup', 4);
            $table->string('calle', 255);
            $table->string('nro', 10);
            $table->string('piso', 10)->nullable();
            $table->string('dpto', 10)->nullable();
            $table->string('localidad', 255);
            $table->string('pcia', 2);
            $table->string('codpostal', 10);
            $table->string('codzona', 4)->nullable();
            $table->string('pais', 3)->nullable();
            $table->string('coordmaps', 255)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->date('valid_ini');
            $table->date('valid_fin');
            $table->text('pto_descrip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objetivos_aipem');
    }
};
