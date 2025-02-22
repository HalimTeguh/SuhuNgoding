<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        $classes = Classes::whereNull('deleted_at')
            ->get();

        $teachers = User::where('role', 'teacher')
            ->whereNull('deleted_at')
            ->get();

        $students = User::leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where('role', 'student')
            ->get();

        return view('admin.pembelajaran.class', [
            'classes' => $classes,
            'teachers' => $teachers,
            'students' => $students,
            'activeMenu' => 'classes'
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
            // Cek apakah user sudah ada berdasarkan id
            $user = User::find($studentData['id']);

            if (!$user) {
                // Jika belum ada, buat user baru dengan role 'student' dan password dari nis
                $user = User::create([
                    'name'     => $studentData['name'],
                    'email'    => $studentData['email'],
                    'password' => Hash::make($studentData['nis']),
                    'role'     => 'student',
                ]);

                $studentRecord = Student::create([
                    'user_id' => $user->id,
                    'NIS'     => $studentData['nis'],
                ]);
            } else {
                // Pastikan record student ada
                $studentRecord = Student::firstOrCreate(
                    ['user_id' => $user->id],
                    ['NIS' => $studentData['nis']]
                );
            }

            // Hubungkan student ke kelas (jika belum terhubung)
            if (!$class->students()->where('student_id', $studentRecord->id)->exists()) {
                $class->students()->attach($studentRecord->id);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
