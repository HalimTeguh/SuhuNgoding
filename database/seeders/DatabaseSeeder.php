<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder untuk Admin
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('Admin123'),
            'role' => 'admin',
        ]);

        Admin::create([
            'user_id' => $admin->id, // Menyimpan ID user di tabel admin
        ]);

        // Seeder untuk Teacher
        $teacher = User::create([
            'name' => 'teach',
            'email' => 'teach@mail.com',
            'password' => Hash::make('Teach123'),
            'role' => 'teacher',
        ]);

        Teacher::create([
            'user_id' => $teacher->id, // Menyimpan ID user di tabel teacher
            'nip' => '0011223344',
        ]);

        $teacher1 = User::create([
            'name' => 'Usman Nurhasan, S.Kom., MT.',
            'email' => 'usman@mail.com',
            'password' => Hash::make('usmanusman'),
            'role' => 'teacher',
        ]);

        Teacher::create([
            'user_id' => $teacher1->id, // Menyimpan ID user di tabel teacher
            'nip' => '198609232015041001',
            'institution' => 'Politeknik Negeri Malang',
            'address' => 'Kota Malang',
        ]);

        // Seeder untuk Student
        $student = User::create([
            'name' => 'stud',
            'email' => 'stud@mail.com',
            'password' => Hash::make('Stud123'),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $student->id, // Menyimpan ID user di tabel student
            'nis' => '2100112233',
        ]);

        $student1 = User::create([
            'name' => 'Halim Teguh',
            'email' => 'halim@mail.com',
            'password' => Hash::make('halimhalim'),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $student1->id, // Menyimpan ID user di tabel student
            'nis' => '2141762122',
            'institution' => 'Politeknik Negeri Malang',
            'address' => 'Kota Malang'
        ]);
    }
}
