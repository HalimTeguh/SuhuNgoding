<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionChoice extends Model
{
    use HasFactory;

    protected $fillable = ['question_test_id', 'choice', 'is_correct'];

    public function soalTest()
    {
        return $this->belongsTo(QuestionTest::class);
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentTestAnswer::class, 'question_choice_id');
    }
}
