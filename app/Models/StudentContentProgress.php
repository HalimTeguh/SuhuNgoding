<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\AssignOp\Mod;

class StudentContentProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'content_id',
        'start_time',
        'end_time',
        'duration'
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
