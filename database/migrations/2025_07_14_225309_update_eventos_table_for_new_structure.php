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
        Schema::table('eventos', function (Blueprint $table) {
            // Elimina columnas viejas
            $table->dropColumn([
                'nombre',
                'descripcion',
                'fecha_inicio',
                'fecha_fin',
                'activo',
            ]);

            // Agrega las nuevas columnas
            $table->dateTime('fecha_hora')->nullable()->after('id');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->foreignId('supervisor_id')->nullable()->constrained('personal')->onDelete('set null');
            $table->decimal('longitud', 10, 7)->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->text('observaciones')->nullable();
            $table->string('url_reporte')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
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
