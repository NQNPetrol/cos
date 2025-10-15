<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('misiones_flytbase', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->string('url');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['cliente_id', 'activo']);
        });
    }

    public function down()
    {
        Schema::table('misiones_flytbase', function (Blueprint $table) {
            //
        });
    }
};