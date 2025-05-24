<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswers extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'quiz_id',
        'choice_id',
        'answer_text',
        'is_correct',
        'feedback',
        'submitted_at'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function attempts()
    {
        return $this->hasMany(StudentQuizAttempts::class);
    }
}
