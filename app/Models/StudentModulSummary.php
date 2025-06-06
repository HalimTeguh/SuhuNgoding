<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentModulSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'content_id',
        'study_content_total_duration',
        'quiz_attempts_total_duration'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function content()
    {
        return $this->belongsTo(ModuleContent::class);
    }
}
