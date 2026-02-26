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
        $columnsToDrop = array_filter([
            'nombre' => Schema::hasColumn('eventos', 'nombre'),
            'descripcion' => Schema::hasColumn('eventos', 'descripcion'),
            'fecha_inicio' => Schema::hasColumn('eventos', 'fecha_inicio'),
            'fecha_fin' => Schema::hasColumn('eventos', 'fecha_fin'),
            'activo' => Schema::hasColumn('eventos', 'activo'),
        ]);

        if (! empty($columnsToDrop)) {
            Schema::table('eventos', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn(array_keys($columnsToDrop));
            });
        }

        Schema::table('eventos', function (Blueprint $table) {
            if (! Schema::hasColumn('eventos', 'fecha_hora')) {
                $table->dateTime('fecha_hora')->nullable()->after('id');
            }
            if (! Schema::hasColumn('eventos', 'cliente_id')) {
                $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            }
            if (! Schema::hasColumn('eventos', 'supervisor_id')) {
                $table->foreignId('supervisor_id')->nullable()->constrained('personal')->onDelete('set null');
            }
            if (! Schema::hasColumn('eventos', 'longitud')) {
                $table->decimal('longitud', 10, 7)->nullable();
            }
            if (! Schema::hasColumn('eventos', 'latitud')) {
                $table->decimal('latitud', 10, 7)->nullable();
            }
            if (! Schema::hasColumn('eventos', 'observaciones')) {
                $table->text('observaciones')->nullable();
            }
            if (! Schema::hasColumn('eventos', 'url_reporte')) {
                $table->string('url_reporte')->nullable();
            }
            if (! Schema::hasColumn('eventos', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            }
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
