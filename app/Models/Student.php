<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends User
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'user_id',
        'NIS',
        'institution',
        'address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_student', 'student_id', 'class_id');
    }

    public function leaderboards()
    {
        return $this->hasMany(Leaderboard::class, 'student_id');
    }

    public function tTests()
    {
        return $this->hasMany(TTesting::class); // Relasi one-to-many
    }
}
