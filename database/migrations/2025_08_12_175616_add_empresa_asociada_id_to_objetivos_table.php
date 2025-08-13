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
        if(!Schema::hasColumn('objetivos', 'empresa_asociada_id')){
            Schema::table('objetivos', function (Blueprint $table) {
                $table->foreignId('empresa_asociada_id')->constrained('empresas_asociadas')->after('cliente_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objetivos', function (Blueprint $table) {
            //
        });
    }
};
