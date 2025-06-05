<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryProgress extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'description', 'created_at'];

    public $timestamps = false;

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
