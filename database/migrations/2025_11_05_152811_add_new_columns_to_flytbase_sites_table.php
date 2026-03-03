<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (! Schema::hasColumn('flytbase_sites', 'location')) {
            Schema::table('flytbase_sites', function (Blueprint $table) {
                $table->json('location')->nullable()->comment('Array con [lat, lng]');
                $table->json('devices')->nullable()->comment('Array de objetos con dock_id y drone_id');
                $table->foreignId('organization_id')->nullable()->constrained('flytbase_organizations')->onDelete('set null');
                $table->json('members')->nullable()->comment('Array de user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('=flytbase_sites', function (Blueprint $table) {
            //
        });
    }
};
