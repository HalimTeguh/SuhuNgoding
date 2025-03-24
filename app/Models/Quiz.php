<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['content_id', 'question', 'type', 'point', 'bloom_level'];

    public function content()
    {
        return $this->belongsTo(ModuleContent::class, 'content_id');
    }

    public function choices()
    {
        return $this->hasMany(QuizChoice::class, 'quiz_id');
    }

    public function code()
    {
        return $this->hasMany(QuizCode::class, 'quiz_id');
    }
}
