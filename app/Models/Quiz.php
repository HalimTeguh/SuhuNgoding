<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = ['content_id', 'question', 'type', 'correct_answer', 'point', 'bloom_level'];

    public function content()
    {
        return $this->belongsTo(ModuleContent::class, 'content_id');
    }

    public function choices()
    {
        return $this->hasMany(QuizChoice::class, 'quiz_id');
    }
}
