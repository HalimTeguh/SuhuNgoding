<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionTest extends Model
{
    use HasFactory;

    protected $fillable = ['question'];

    // Relasi ke soal_choices (soal bisa memiliki banyak pilihan jawaban)
    public function choices()
    {
        return $this->hasMany(QuestionChoice::class, 'question_test_id');
    }

    // Relasi ke student_answers (soal bisa memiliki banyak jawaban dari siswa)
    public function studentAnswers()
    {
        return $this->hasMany(StudentTestAnswer::class, 'question_test_id');
    }
}
