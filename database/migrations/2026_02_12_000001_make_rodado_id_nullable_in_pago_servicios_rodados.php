<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('pago_servicios_rodados', 'rodado_id')) {
            DB::statement('ALTER TABLE pago_servicios_rodados MODIFY COLUMN rodado_id BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        //
    }
};
