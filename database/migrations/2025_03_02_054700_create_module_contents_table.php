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
        Schema::create('module_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->string('title');
            $table->text('summary')->nullable();
            $table->text('content')->nullable(); // HTML siap pakai
            $table->uuid('source_uuid')->nullable(); // UUID folder hasil konversi PDF
            $table->json('media_files')->nullable(); // list file gambar
            $table->string('source_type')->nullable(); // pdf/docx
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_contents');
    }
};
