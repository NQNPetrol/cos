<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('alertas_admin', 'servicio_usuario_id')) {
            Schema::table('alertas_admin', function (Blueprint $table) {
                $table->foreignId('servicio_usuario_id')->nullable()->after('cliente_id')->constrained('servicios_usuario')->onDelete('set null');
                $table->foreignId('taller_id')->nullable()->after('servicio_usuario_id')->constrained('talleres')->onDelete('set null');
                $table->string('destinatario_tipo')->default('admin')->after('taller_id'); // admin, cliente, ambos
                $table->foreignId('destinatario_user_id')->nullable()->after('destinatario_tipo')->constrained('users')->onDelete('set null');
                $table->unsignedTinyInteger('dia_mes')->nullable()->after('destinatario_user_id'); // 1-31 for monthly alerts
                $table->integer('dias_anticipacion')->nullable()->after('dia_mes'); // days before to trigger
            });
        }
    }

    public function down(): void
    {
        //
    }
};
