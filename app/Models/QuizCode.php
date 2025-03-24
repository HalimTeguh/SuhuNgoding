<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizCode extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_id', 'test_cases', 'expected_output', 'language', 'feedback'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
}

