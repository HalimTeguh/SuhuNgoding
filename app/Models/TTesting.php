<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TTesting extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan oleh model ini, jika nama tabel tidak sesuai konvensi
    protected $table = 't_testings';

    // Tentukan kolom mana yang bisa diisi secara massal
    protected $fillable = [
        'student_id', // id dari siswa
        'class_id', // id dari kelas
        'module_id', // id dari modul
        'pre_test_score', // nilai pre-test
        'class_type', // tipe kelas (eksperimen atau kontrol)
        'post_test_score', // nilai post-test
    ];

    // Relasi dengan model Student (satu TTesting berhubungan dengan satu Student)
    public function student()
    {
        return $this->belongsTo(Student::class); // Relasi belongsTo ke model Student
    }
}
