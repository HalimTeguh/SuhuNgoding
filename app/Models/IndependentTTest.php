<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndependentTTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'module_id',
        't_statistic',
        'p_value',
        'is_significant',
        'group_statistics',
        'interpretation',
    ];

    protected $casts = [
        'is_significant' => 'boolean',
        'group_statistics' => 'array', // JSON kolom dikonversi otomatis ke array PHP
    ];

    // Relasi ke Class
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    // Relasi ke Modul
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
