<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlModuleContent extends Model
{
    use HasFactory;
    protected $fillable = [
        'module_content_id',
        'material_link',
        'test_link',
        'notes',
    ];

    public function moduleContent()
    {
        return $this->belongsTo(ModuleContent::class);
    }
}
