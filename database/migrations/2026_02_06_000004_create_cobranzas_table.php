<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cobranzas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->foreignId('servicio_usuario_id')->nullable()->constrained('servicios_usuario')->onDelete('set null');
            $table->string('concepto');
            $table->decimal('valor_unitario', 12, 2);
            $table->integer('cantidad')->default(1);
            $table->decimal('monto_total', 12, 2);
            $table->string('moneda', 5)->default('ARS');
            $table->string('estado')->default('pendiente');
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->string('factura_path')->nullable();
            $table->string('comprobante_path')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Schema::dropIfExists('cobranzas');
    }
};
