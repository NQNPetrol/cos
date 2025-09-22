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
        Schema::create('camera_stream_urls', function (Blueprint $table) {
            $table->id();
            $table->string('camera_index_code')->unique();
            $table->text('url');
            $table->text('authentication')->nullable();
            $table->string('protocol')->default('rtsp');
            $table->integer('stream_type')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_updated')->useCurrent();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('camera_index_code')
                  ->references('camera_index_code')
                  ->on('cameras')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camera_stream_urls');
    }
};
