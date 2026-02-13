<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supervisor_empresas_asociadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_id')->nullable()->constrained('personal')->onDelete('set null');
            $table->foreignId('empresa_asociada_id')->nullable()->constrained('empresas_asociadas')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique(['supervisor_id', 'empresa_asociada_id'], 'sup_emp_asoc_unique');
        });
    }

    public function down(): void
    {
        // 
    }
};
