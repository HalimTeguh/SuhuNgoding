<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['teacher_id', 'title', 'status', 'image', 'description'];

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_module', 'module_id', 'class_id');
    }

    public function contents()
    {
        return $this->hasMany(ModuleContent::class, 'module_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function leaderboards()
    {
        return $this->hasMany(Leaderboard::class, 'module_id');
    }
}
