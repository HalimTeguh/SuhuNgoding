<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTestAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'question_test_id', 'question_choice_id'];

    // Relasi ke student (jawaban milik satu siswa)
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relasi ke soal_test (jawaban untuk soal tertentu)
    public function soalTest()
    {
        return $this->belongsTo(QuestionTest::class);
    }

    // Relasi ke soal_choice (jawaban berupa pilihan tertentu)
    public function soalChoice()
    {
        return $this->belongsTo(QuestionChoice::class);
    }
}
