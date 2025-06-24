<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleContent extends Model
{
    use HasFactory;

    protected $fillable = ['module_id', 'title', 'content', 'summary', 'source_uudid', 'media_files', 'source_type'];

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'content_id');
    }

    public function controlVersion()
    {
        return $this->hasOne(ControlModuleContent::class);
    }
}
