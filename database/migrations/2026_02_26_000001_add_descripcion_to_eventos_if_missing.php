<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Evento model and controller use descripcion; ensure column exists (may have been dropped in update_eventos_table_for_new_structure).
     */
    public function up(): void
    {
        if (! Schema::hasColumn('eventos', 'descripcion')) {
            Schema::table('eventos', function (Blueprint $table) {
                $table->text('descripcion')->nullable()->after('tipo');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('eventos', 'descripcion')) {
            Schema::table('eventos', function (Blueprint $table) {
                $table->dropColumn('descripcion');
            });
        }
    }
};
