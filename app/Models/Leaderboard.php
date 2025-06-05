<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'module_id',
        'student_id',
        'point',
    ];

    /**
     * Relasi ke model Classes
     */
    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    /**
     * Relasi ke model Module
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Relasi ke model Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
