<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Leaderboard;
use App\Models\Module;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClassController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); // Pastikan hanya user login yang bisa mengakses controller ini
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = auth()->user();

        $allStudents = User::leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where('role', 'student')
            ->get();

        if ($user->role == 'teacher') {
            $classes = Classes::whereNull('deleted_at')->where('teacher_id', $user->teacher->id)->get();
            $allTeachers = User::where('role', 'teacher')
                ->whereNull('deleted_at')
                ->where('id',  $user->teacher->user_id)
                ->get();
            return view('teacher.class.index', [
                'user' => $user,
                'classes' => $classes,
                'allTeachers' => $allTeachers,
                'allStudents' => $allStudents,
                'activeMenu' => 'classes'
            ]);
        } else {
            $classes = Classes::whereNull('deleted_at')->get();
            $allTeachers = User::where('role', 'teacher')
                ->whereNull('deleted_at')
                ->get();
            return view('admin.pembelajaran.class.class', [
                'user' => $user,
                'classes' => $classes,
                'allTeachers' => $allTeachers,
                'allStudents' => $allStudents,
                'activeMenu' => 'classes'
            ]);
        }
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
        try {
            DB::beginTransaction(); // Mulai transaksi

            $request->validate([
                'nameCreate' => 'required|string|max:255',
                'descriptionCreate' => 'nullable|string',
                'teacherCreate' => 'required|exists:users,id',
                'imageUpload' => 'nullable|image',
                // selectedStudents berupa JSON string (opsional)
            ]);

            // Buat record kelas baru
            $class = new Classes();
            $class->name = $request->nameCreate;
            $class->description = $request->descriptionCreate;

            $teacherUser = User::find($request->teacherCreate);
            if (!$teacherUser || !$teacherUser->teacher) {
                return redirect()->back()
                    ->withInput()
                    ->with('toasts', [
                        [
                            'type' => 'danger',
                            'title' => 'Gagal',
                            'message' => 'Teacher data not found',
                            'time' => now()->diffForHumans(),
                        ]
                    ])->with('form_error', 'create');
            }
            $teacher_id = $teacherUser->teacher->id;

            $class->teacher_id = $teacher_id;

            // Simpan file image jika ada
            if ($request->hasFile('imageUpload')) {
                $imagePath = $request->file('imageUpload')->store('classes', 'public');
                $class->image = $imagePath;
            }

            $class->save();

            // Proses selected students jika ada
            if ($request->filled('selectedStudents')) {
                $selectedStudents = json_decode($request->selectedStudents, true);
                if (is_array($selectedStudents)) {
                    $this->processSelectedStudents($class, $selectedStudents);
                }
            }

            DB::commit();

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => "Kelas {$class->name} berhasil ditambahkan",
                    'time' => now()->diffForHumans()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return redirect()->back()
                    ->withErrors($e->errors())
                    ->withInput()
                    ->with('toasts', [
                        [
                            'type' => 'danger',
                            'title' => 'Gagal',
                            'message' => 'Terjadi kesalahan saat validasi data.',
                            'time' => now()->diffForHumans(),
                        ]
                    ])->with('form_error', 'create');
            }

            return redirect()->back()
                ->withErrors(['general' => $errorMessage])
                ->withInput()
                ->with('toasts', [
                    [
                        'type' => 'danger',
                        'title' => 'Gagal',
                        'message' => 'Terjadi kesalahan: ' . $errorMessage,
                        'time' => now()->diffForHumans(),
                    ]
                ])->with('form_error', 'create');
        }
    }

    /**
     * Proses data selected students untuk menghubungkan user/student ke kelas.
     *
     * @param Classes $class
     * @param array   $selectedStudents
     */
    private function processSelectedStudents(Classes $class, array $selectedStudents)
    {
        foreach ($selectedStudents as $studentData) {
            // Cari user berdasarkan email
            $user = User::where('email', $studentData['email'])->first();

            if (!$user) {
                // Jika user belum ada, buat user baru
                $user = User::create([
                    'name'     => $studentData['name'],
                    'email'    => $studentData['email'],
                    'password' => Hash::make($studentData['nis']),
                    'role'     => 'student',
                ]);

                // Buat student baru
                $student = Student::create([
                    'user_id'     => $user->id,
                    'NIS'         => $studentData['nis'],
                    'institution' => $studentData['institution'] ?? null,
                    'address'     => $studentData['address'] ?? null,
                ]);
            } else {
                // Jika user sudah ada, update data user (opsional)
                $user->update([
                    'name' => $studentData['name'],
                ]);

                // Cek student berdasarkan user_id
                $student = Student::firstOrNew(['user_id' => $user->id]);
                $student->NIS = $studentData['nis'];
                $student->institution = $studentData['institution'] ?? null;
                $student->address = $studentData['address'] ?? null;
                $student->save();
            }

            // Hubungkan ke kelas jika belum ada
            if (!$class->students()->where('student_id', $student->id)->exists()) {
                $class->students()->attach($student->id);
            }
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = auth()->user();

        $class = Classes::with(['teacher.user', 'students.user', 'modules.teacher.user'])->findOrFail($id);

        $allStudents = User::leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where('role', 'student')
            ->get();

        $availableModules = Module::with('teacher.user')
            ->whereNull('deleted_at')
            ->where(function ($query) {
                $user = auth()->user();

                if ($user->role === 'teacher') {
                    // Public atau Private milik teacher tersebut
                    $query->where('status', 1)
                        ->orWhere(function ($q) use ($user) {
                            $q->where('status', 2)
                                ->where('teacher_id', $user->teacher->id);
                        });
                } elseif ($user->role === 'admin') {
                    // Public dan semua Private
                    $query->whereIn('status', [1, 2]);
                } else {
                    // Jika role lain, misalnya student: hanya lihat public
                    $query->where('status', 1);
                }
            })
            ->get();

        if ($user->role == "teacher") {
            $allTeachers = User::where('role', 'teacher')
                ->where('id',  $user->teacher->user_id)
                ->whereNull('deleted_at')
                ->get();

            return view('teacher.class.detailClass', [
                'user' => $user,
                'class' => $class,
                'teacher' => $class->teacher,
                'allTeacher' => $allTeachers,
                'allStudents' => $allStudents,
                'students' => $class->students,
                'availableModules' => $availableModules,
                'activeMenu' => 'classes'
            ]);
        } else {
            $allTeachers = User::where('role', 'teacher')
                ->whereNull('deleted_at')
                ->get();

            return view('admin.pembelajaran.class.detailClass', [
                'user' => $user,
                'class' => $class,
                'teacher' => $class->teacher,
                'allTeacher' => $allTeachers,
                'allStudents' => $allStudents,
                'students' => $class->students,
                'availableModules' => $availableModules,
                'activeMenu' => 'classes'
            ]);
        }
    }

    public function attachModules(Request $request, Classes $class)
    {
        $validated = $request->validate([
            'module_ids' => 'required|array',
            'module_ids.*' => 'exists:modules,id',
        ]);

        // Tambahkan module ke class tanpa menghapus module yang sudah ada
        $class->modules()->syncWithoutDetaching($validated['module_ids']);

        // Ambil semua student di class
        $students = $class->students;

        // Loop semua module yang ditambahkan
        foreach ($validated['module_ids'] as $moduleId) {
            foreach ($students as $student) {
                Leaderboard::firstOrCreate([
                    'class_id'   => $class->id,
                    'module_id'  => $moduleId,
                    'student_id' => $student->id,
                ], [
                    'point' => 0
                ]);
            }
        }

        return redirect()->back()->with('toasts', [
            [
                'type' => 'success',
                'title' => 'Modules Added',
                'message' => 'Modules successfully attached to the class.',
                'time' => now()->diffForHumans()
            ]
        ]);
    }


    public function detachModule(Classes $class, Module $module)
    {
        $class->modules()->detach($module->id);

        // Hapus leaderboard
        Leaderboard::where('class_id', $class->id)->where('module_id', $module->id)->delete();

        return back()->with('toasts', [
            [
                'type' => 'success',
                'title' => 'Module Removed',
                'message' => "Module {$module->title} has been removed from the class.",
                'time' => now()->diffForHumans()
            ]
        ]);
    }

    public function attachStudent(Request $request, Classes $class)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id'
        ]);

        $studentId = $request->student_id;

        if (!$class->students()->where('student_id', $studentId)->exists()) {
            $class->students()->attach($studentId);
        }

        $modules = $class->modules;

        foreach ($modules as $module) {
            Leaderboard::firstOrCreate([
                'class_id'   => $class->id,
                'module_id'  => $module->id,
                'student_id' => $studentId,
            ], [
                'point' => 0
            ]);
        }

        return redirect()->back()->with('toasts', [
            [
                'type' => 'success',
                'title' => 'Student Added',
                'message' => 'Student successfully added to the class.',
                'time' => now()->diffForHumans()
            ]
        ]);
    }


    public function detachStudent(Classes $class, Student $student)
    {
        $class->students()->detach($student->id);

        Leaderboard::where('class_id', $class->id)->where('student_id', $student->id)->delete();

        return back()->with('toasts', [
            [
                'type' => 'success',
                'title' => 'Student Removed',
                'message' => "Student {$student->user->name} has been removed from the class.",
                'time' => now()->diffForHumans()
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        // Mengambil data kelas berdasarkan ID, termasuk teacher terkait
        $class = Classes::with(['teacher.user', 'students.user'])->findOrFail($id);

        // Mengambil semua teacher untuk opsi di select
        $teachers = User::where('role', 'teacher')
            ->whereNull('deleted_at')
            ->get();

        return response()->json([
            'class' => $class,
            'teachers' => $teachers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction(); // Mulai transaksi

            $request->validate([
                'nameEdit' => 'required|string|max:255',
                'descriptionEdit' => 'nullable|string',
                'teacherCreate' => 'required|exists:users,id',
                'imageUpload' => 'nullable|image',
                'isImageReset' => 'required|in:true,false',
            ]);

            // Ambil data kelas berdasarkan ID
            $class = Classes::findOrFail($id);
            $class->name = $request->nameEdit;
            $class->description = $request->descriptionEdit;

            $teacherUser = User::find($request->teacherCreate);
            if (!$teacherUser || !$teacherUser->teacher) {
                return redirect()->back()
                    ->withInput()
                    ->with('toasts', [
                        [
                            'type' => 'danger',
                            'title' => 'Gagal',
                            'message' => 'Teacher data not found',
                            'time' => now()->diffForHumans(),
                        ]
                    ])->with('form_error', 'update');
            }
            $class->teacher_id = $teacherUser->teacher->id;

            // **Handle Image Update**
            if ($request->isImageReset === "true") {
                // **Reset Image**
                if ($class->image) {
                    $class->image = null;
                }
            } elseif ($request->hasFile('imageUpload')) {
                // **Change Image**
                $imagePath = $request->file('imageUpload')->store('classes', 'public');
                $class->image = $imagePath;
            }

            // **Save data**
            $class->save();

            DB::commit();

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => "Kelas {$class->name} berhasil diperbarui",
                    'time' => now()->diffForHumans()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['general' => $e->getMessage()])
                ->withInput()
                ->with('toasts', [
                    [
                        'type' => 'danger',
                        'title' => 'Gagal',
                        'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                        'time' => now()->diffForHumans(),
                    ]
                ])->with('form_error', 'update');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //


    }

    public function softDelete($id)
    {
        try {
            $class = Classes::findOrFail($id);
            $class->delete(); // Ini hanya mengisi `deleted_at`, tidak menghapus perma

            return redirect('dashboard/admin/pembelajaran/class')->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'successful',
                    'message' => "Class {$class->name} has been deleted",
                    'time' => now()->diffForHumans()
                ]
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('toasts', [
                [
                    'type' => 'danger',
                    'title' => 'Gagal',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                    'time' => now()->diffForHumans(),
                ]
            ]);
        }
    }
}
