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
        if (Schema::hasColumn('personal', 'cargo')) {
            Schema::table('personal', function (Blueprint $table) {
                $table->string('cargo')->nullable()->change();
                $table->string('puesto')->nullable()->change();
                $table->string('convenio')->nullable()->change();
                $table->string('tipo_doc')->nullable()->change();
                $table->string('telefono')->nullable()->change();
                $table->string('legajo')->nullable()->change();
                $table->date('fecha_ing')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal', function (Blueprint $table) {
            //
        });
    }
};
