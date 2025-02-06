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
}
