<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function create($userId, $idNumber)
    {

        $teacher = Teacher::create([
            'user_id' => $userId,
            'NIP' => $idNumber,
        ]);

    }
}
