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
        Schema::table('dispositivos', function (Blueprint $table) {
            $table->decimal('latitud', 10, 7)->nullable()->after('ubicacion');
            $table->decimal('longitud', 10, 7)->nullable()->after('latitud');

            $table->index(['latitud', 'longitud'], 'dispositivos_lat_long_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
