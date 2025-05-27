<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Leaderboard;
use App\Models\ModuleContent;
use App\Models\StudentModulSummary;
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
        $class = Classes::with(['modules.contents'])->findOrFail($id); // langsung eager load modules dan contents

        $leaderboard = Leaderboard::with('student.user')
            ->where('class_id', $class->id)
            ->orderByDesc('point')
            ->get();

        $student = auth()->user()->student;

        // Ambil hanya modules dari kelas ini yang dimiliki student (optional filter)
        $modules = $class->modules;

        foreach ($modules as $module) {
            foreach ($module->contents as $content) {
                // Contoh progres dummy
                $content->progress_status = "belum";
            }
        }

        return view('student.class.detailClass', [
            'user' => $student,
            'class' => $class,
            'modules' => $modules,
            'leaderboard' => $leaderboard,
            'activeMenu' => 'class'
        ]);
    }

    public function showContent(string $id)
    {
        $student = auth()->user()->student;
        $moduleContent = ModuleContent::findOrFail($id); // langsung eager load modules dan contents
        $listContent = ModuleContent::where('module_id', $moduleContent->module_id)->get();

        return view('student.class.moduleContent', [
            'user' => $student,
            'listContent' => $listContent,
            'moduleContent' => $moduleContent,
            'activeMenu' => 'class'
        ]);
    }

    public function saveDurationStudyContent(Request $request)
    {
        $request->validate([
            'module_content_id' => 'required|exists:module_contents,id',
            'duration' => 'required|integer|min:1'
        ]);

        $student = auth()->user()->student;

        $summary = StudentModulSummary::firstOrNew([
            'student_id' => $student->id,
            'content_id' => $request->module_content_id,
        ]);

        $summary->study_content_total_duration += $request->duration;
        $summary->save();

        return response()->with('toasts', [
            [
                'type' => 'success',  // Jenis toast
                'title' => 'Data tersimpan',  // Judul toast
                'message' => 'Durasi belajar berhasil disimpan',  // Pesan toast
                'time' => now()->diffForHumans()  // Waktu toast
            ]
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
