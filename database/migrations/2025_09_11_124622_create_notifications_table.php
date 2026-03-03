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
        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('message');
                $table->enum('type', ['global', 'user', 'client'])->default('global');
                $table->unsignedBigInteger('user_id')->nullable(); // Para notificaciones específicas de usuario
                $table->unsignedBigInteger('client_id')->nullable(); // Para notificaciones de cliente
                $table->enum('priority', ['BAJA', 'NORMAL', 'ALTA'])->default('NORMAL');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['type', 'is_active', 'created_at']);
            });
        }

        if (! Schema::hasTable('notification_user')) {
            Schema::create('notification_user', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('notification_id');
                $table->unsignedBigInteger('user_id');
                $table->boolean('is_read')->default(false);
                $table->boolean('is_dismissed')->default(false);
                $table->timestamp('read_at')->nullable();
                $table->timestamp('dismissed_at')->nullable();
                $table->timestamps();

                $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->unique(['notification_id', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
