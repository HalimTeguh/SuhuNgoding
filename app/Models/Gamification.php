<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gamification extends Model
{
    use HasFactory;

    protected $fillable = [
        'bloom_level',
        'point',
        'first_attempt_multiply_point',
        'second_attempt_multiply_point',
        'third_attempt_multiply_point'
    ];
}
