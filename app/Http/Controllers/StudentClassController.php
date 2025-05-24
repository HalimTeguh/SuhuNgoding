<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Leaderboard;
use App\Models\User;
use Illuminate\Http\Request;

class StudentClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Pastikan relasi user -> student sudah tersedia
        $student = $user->student;

        // Jika student tidak ditemukan (misal user bukan student), kembalikan error atau redirect
        if (!$student) {
            abort(403, 'Student data not found for this user.');
        }

        // Ambil semua kelas yang terhubung dengan student, dan belum di-soft-delete
        $classes = $student->classes()->whereNull('classes.deleted_at')->get();

        return view('student.class.index', [
            'user' => $user,
            'student' => $student,
            'classes' => $classes,
            'activeMenu' => 'class'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $user = auth()->user();

        // Pastikan relasi user -> student sudah tersedia
        $student = $user->student;

        // Jika student tidak ditemukan (misal user bukan student), kembalikan error atau redirect
        if (!$student) {
            abort(403, 'Student data not found for this user.');
        }

        // Ambil semua kelas yang terhubung dengan student, dan belum di-soft-delete
        $classes = $student->classes()->whereNull('classes.deleted_at')->get();

        return view('student.class.index', [
            'user' => $user,
            'student' => $student,
            'classes' => $classes,
            'activeMenu' => 'class'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::findOrFail($id);
        
        $class = Classes::findOrFail($id);

        $leaderboard = Leaderboard::where('class_id', $class->id)->get();

        $modules = $class->modules()->get();

        return view('student.class.detailClass', [
            'user' => $user,
            'class' => $class,
            'modules' => $modules,
            'leaderboard' => $leaderboard,
            'activeMenu' => 'class'
        ]);
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
