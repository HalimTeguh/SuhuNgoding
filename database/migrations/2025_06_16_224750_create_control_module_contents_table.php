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
        Schema::create('control_module_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_content_id')->constrained('module_contents')->onDelete('cascade');
            $table->string('material_link')->nullable(); // Link ke file materi (PDF, Google Drive, dsb)
            $table->string('test_link')->nullable();     // Link ke Google Form atau sistem tes lainnya
            $table->text('notes')->nullable();           // Catatan tambahan jika diperlukan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_module_contents');
    }
};
