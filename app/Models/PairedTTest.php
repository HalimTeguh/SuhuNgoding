<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PairedTTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'module_id',
        'class_type',
        'mean_difference',
        't_statistic',
        'degrees_freedom',
        'p_value_one_tailed',
        'p_value_two_tailed',
        'n',
        'interpretation',
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
