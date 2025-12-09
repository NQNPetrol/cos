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
        Schema::table('peticiones_misiones_flytbase', function (Blueprint $table) {
            $table->string('kmz_file_path')->nullable()->after('waypoints')->comment('Ruta del archivo KMZ asociado a la petición');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peticiones_misiones_flytbase', function (Blueprint $table) {
            //
        });
    }
};
