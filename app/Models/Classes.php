<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['teacher_id', 'name', 'image', 'description'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_student', 'class_id', 'student_id');
    }

/*************  ✨ Windsurf Command ⭐  *************/
/*******  0a936e8a-8d85-4eaf-bb09-231dc227a014  *******/
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'class_module', 'class_id', 'module_id');
    }

    public function leaderboards()
    {
        return $this->hasMany(Leaderboard::class, 'class_id');
    }
}
