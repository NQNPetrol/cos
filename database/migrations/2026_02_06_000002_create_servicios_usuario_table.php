<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('servicios_usuario')) { return; }

        Schema::create('servicios_usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('tipo_calculo', ['fijo', 'variable'])->default('fijo');
            $table->decimal('valor_unitario', 12, 2)->nullable();
            $table->string('moneda', 5)->default('ARS');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        //
    }
};
