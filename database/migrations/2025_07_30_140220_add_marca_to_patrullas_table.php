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

        if (! Schema::hasColumn('patrullas', 'marca')) {
            Schema::table('patrullas', function (Blueprint $table) {
                $table->string('marca')->nullable()->after('patente');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patrullas', function (Blueprint $table) {
            //
        });
    }
};
