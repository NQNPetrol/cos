<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Seed the existing hardcoded document types
        DB::table('documentos')->insert([
            ['nombre' => 'Seguro', 'descripcion' => null, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Poliza Seguro', 'descripcion' => null, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'VTV', 'descripcion' => null, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'RTO Provincial', 'descripcion' => null, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'RTO Nacional', 'descripcion' => null, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Constancia 0km', 'descripcion' => null, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        //
    }
};
