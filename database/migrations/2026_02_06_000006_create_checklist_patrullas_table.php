<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('checklist_patrullas')) { return; }

        Schema::create('checklist_patrullas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patrulla_id')->nullable()->constrained('patrullas')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('fecha');
            $table->integer('ruedas_auxilio')->default(2);
            $table->boolean('antena_starlink')->default(false);
            $table->boolean('camaras_dvr')->default(false);
            $table->boolean('parabrisas')->default(false);
            $table->boolean('luces')->default(false);
            $table->boolean('balizas')->default(false);
            $table->boolean('antivuelco')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Schema::dropIfExists('checklist_patrullas');
    }
};
