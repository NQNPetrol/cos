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
        if (! Schema::hasColumn('cameras', 'dispositivo_id')) {
            Schema::table('cameras', function (Blueprint $table) {
                $table->foreignId('dispositivo_id')
                    ->nullable()
                    ->after('encode_dev_index_code')
                    ->constrained('dispositivos')
                    ->onDelete('set null');

                $table->index('dispositivo_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
