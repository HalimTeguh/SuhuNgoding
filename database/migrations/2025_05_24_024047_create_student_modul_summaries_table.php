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
        Schema::create('student_modul_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('content_id')->constrained('module_contents')->onDelete('cascade');
            $table->integer('study_content_total_duration')->default(0);
            $table->integer('quiz_attempts_total_duration')->default(0);
            $table->decimal('total_score', 5, 2)->nullable();
            $table->enum('status', ['Lulus', 'Tidak Lulus'])->nullable();
            $table->dateTime('quiz_submitted_at')->nullable();
            $table->integer('quiz_attempts_count')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_modul_summaries');
    }
};
