<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //
    public function index()
    {
        return view('authentication.register');
    }

    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
            'id_number' => 'required|string|max:20'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        // Simpan data tambahan berdasarkan role
        if ($request->role == 'teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'NIP' => $request->id_number,
            ]);
        } elseif ($request->role == 'student') {
            Student::create([
                'user_id' => $user->id,
                'NIS' => $request->id_number,
            ]);
        } 

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
