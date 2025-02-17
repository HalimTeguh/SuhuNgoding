<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
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
        $students = User::where('role', 'student')
            ->whereNull('deleted_at')
            ->get();

        return view('admin.users.student', [
            'students' => $students,
            'activeMenu' => 'student'
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
                'emailCreate' => 'required|email|unique:users,email',
                'NISCreate' => 'required|unique:students,NIS',
                'institutionCreate' => 'nullable',
                'addressCreate' => 'nullable',
                'passwordCreate' => 'required|min:6',
            ]);

            // Buat User
            $user = User::create([
                'name' => $request->nameCreate,
                'email' => $request->emailCreate,
                'role' => 'student',
                'password' => Hash::make($request->passwordCreate),
            ]);

            // Buat Teacher, jika gagal maka User juga dibatalkan
            Student::create([
                'user_id' => $user->id,
                'NIS' => $request->NISCreate,
                'institution' => $request->institutionCreate,
                'address' => $request->addressCreate,
            ]);

            DB::commit(); // Simpan semua perubahan ke database jika tidak ada error

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => "Siswa $request->name berhasil ditambahkan",
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
        $student = User::leftJoin('students', 'users.id', '=', 'students.user_id')
            ->where('users.id', $id)
            ->first();
        return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try {
            $student = User::findOrFail($id);

            // Validasi input
            $validatedData = $request->validate([
                'nameEdit' => 'required|string|max:255',
                'emailEdit' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($id),
                ],
                'NISEdit' => [
                    'required',
                    Rule::unique('students', 'NIS')->ignore($student->student->id),
                ],
                'institutionEdit' => 'nullable|string',
                'addressEdit' => 'nullable|string',
                'passwordEdit' => 'nullable|min:6',
            ]);

            // Perbarui data student
            $student->fill([
                'name' => $validatedData['nameEdit'],
                'email' => $student->email !== $validatedData['emailEdit'] ? $validatedData['emailEdit'] : $student->email,
                'password' => $request->filled('passwordEdit') ? HASH::make($validatedData['passwordEdit']) : $student->password,
            ]);

            // Perbarui data relasi teacher
            $student->student->update([
                'NIS' => $validatedData['NISEdit'],
                'institution' => $validatedData['institutionEdit'],
                'address' => $validatedData['addressEdit'],
            ]);

            // Simpan semua perubahan
            $student->save();

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Data siswa berhasil diperbarui.',
                    'time' => now()->diffForHumans(),
                ]
            ]);
        } catch (\Exception $e) {
            $message = $e instanceof \Illuminate\Validation\ValidationException
                ? 'Terjadi kesalahan saat validasi data.'
                : 'Gagal Mengubah data siswa. Terjadi kesalahan: ' . $e->getMessage();

            $errors = $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['general' => $e->getMessage()];

            return redirect()->back()
                ->withErrors($errors)
                ->withInput()
                ->with('toasts', [
                    [
                        'type' => 'danger',
                        'title' => 'Gagal',
                        'message' => $message,
                        'time' => now()->diffForHumans(),
                    ]
                ])
                ->with([
                    'form_error' => 'update',
                    'entity_id' => $id,
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function softDelete($id)
    {
        try {
            // Cari student berdasarkan ID
            $student = User::findOrFail($id);

            if (!$student) {
                return redirect()->back()->with('toasts', [
                    [
                        'type' => 'danger',
                        'title' => 'Gagal',
                        'message' => 'Data siswa tidak ditemukan!',
                        'time' => now()->diffForHumans(),
                    ]
                ]);
            }

            // Perbarui deleted_at dengan timestamp saat ini
            $student->deleted_at = now();
            $student->save();

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Data siswa telah dihapus.',
                    'time' => now()->diffForHumans(),
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        try {
            // Cari student berdasarkan ID
            $student = User::findOrFail($id);

            // Hapus secara permanen
            $student->forceDelete();

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Siswa berhasil dihapus secara permanen.',
                    'time' => now()->diffForHumans(),
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
