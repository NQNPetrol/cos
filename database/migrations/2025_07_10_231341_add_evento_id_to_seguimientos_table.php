<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('seguimientos', function (Blueprint $table) {
            // agregar columna
            $table->foreignId('evento_id')
                ->nullable()
                ->constrained('eventos')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('seguimientos', function (Blueprint $table) {
            $table->dropForeign(['evento_id']);
            $table->dropColumn('evento_id');
        });
    }
};
