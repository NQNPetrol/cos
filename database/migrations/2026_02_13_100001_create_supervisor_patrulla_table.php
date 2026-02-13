<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supervisor_patrulla', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_id')->nullable()->constrained('personal')->onDelete('set null');
            $table->foreignId('patrulla_id')->nullable()->constrained('patrullas')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique('patrulla_id');
            $table->unique('supervisor_id');
        });
    }

    public function down(): void
    {
        // 
    }
};
