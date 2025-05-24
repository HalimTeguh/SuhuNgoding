<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentQuizAttempts extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_answer_id',
        'start_time',
        'end_time',
        'duration'
    ];

    public function studentAnswer()
    {
        return $this->belongsTo(StudentAnswers::class);
    }
}
