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
        // Verifica si la columna ya existe antes de agregarla
        if (! Schema::hasColumn('seguimientos', 'evento_id')) {
            Schema::table('seguimientos', function (Blueprint $table) {
                $table->foreignId('evento_id')
                    ->nullable()
                    ->constrained('eventos')
                    ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::table('seguimientos', function (Blueprint $table) {
            $table->dropForeign(['evento_id']);
            $table->dropColumn('evento_id');
        });
    }
};
