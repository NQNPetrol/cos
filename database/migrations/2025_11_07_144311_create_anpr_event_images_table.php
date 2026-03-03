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
        if (Schema::hasTable('anpr_event_images')) { return; }

        Schema::create('anpr_event_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anpr_record_id')->constrained('anpr_passing_records')->nullable();
            $table->string('veh_pic_uri')->nullable(); // URI original de HikCentral
            $table->text('image_path')->nullable(); // Path/URL de la imagen después del fetch
            $table->text('image_base64')->nullable(); // Imagen en base64 (se llena después)
            $table->string('mime_type')->nullable()->default('image/jpeg');
            $table->integer('file_size')->nullable();
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('anpr_record_id');
            $table->index('veh_pic_uri');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anpr_event_images');
    }
};
