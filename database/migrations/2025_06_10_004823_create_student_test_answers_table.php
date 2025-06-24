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
        Schema::create('student_test_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade'); // Relasi ke tabel students
            $table->enum('type', ['pretest', 'posttest'])->nullable();
            $table->foreignId('question_test_id')->constrained('question_tests')->onDelete('cascade'); // Relasi ke soal_tests
            $table->foreignId('question_choice_id')->constrained('question_choices')->onDelete('cascade'); // Relasi ke soal_choices
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_test_answers');
    }
};
