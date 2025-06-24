<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
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
        $teachers = User::where('role', 'teacher')
            ->whereNull('deleted_at')
            ->get();

        return view('admin.users.teacher', [
            'user' => $user,
            'teachers' => $teachers,
            'activeMenu' => 'teacher'
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
                'NIPCreate' => 'required|unique:teachers,NIP',
                'institutionCreate' => 'nullable',
                'addressCreate' => 'nullable',
                'passwordCreate' => 'required|min:6',
            ]);

            // Buat User
            $user = User::create([
                'name' => $request->nameCreate,
                'email' => $request->emailCreate,
                'role' => 'teacher',
                'password' => Hash::make($request->passwordCreate),
            ]);

            // Buat Teacher, jika gagal maka User juga dibatalkan
            Teacher::create([
                'user_id' => $user->id,
                'NIP' => $request->NIPCreate,
                'institution' => $request->institutionCreate,
                'address' => $request->addressCreate,
            ]);

            DB::commit(); // Simpan semua perubahan ke database jika tidak ada error

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => "Pengajar $request->name berhasil ditambahkan",
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
        $teacher = User::leftJoin('teachers', 'users.id', '=', 'teachers.user_id')
            ->where('users.id', $id)
            ->first();
        return response()->json($teacher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $teacher = User::findOrFail($id);

            // Validasi input
            $validatedData = $request->validate([
                'nameEdit' => 'required|string|max:255',
                'emailEdit' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($id),
                ],
                'NIPEdit' => [
                    'required',
                    Rule::unique('teachers', 'NIP')->ignore($teacher->teacher->id),
                ],
                'institutionEdit' => 'nullable|string',
                'addressEdit' => 'nullable|string',
                'passwordEdit' => 'nullable|min:6',
            ]);

            // Perbarui data teacher
            $teacher->fill([
                'name' => $validatedData['nameEdit'],
                'email' => $teacher->email !== $validatedData['emailEdit'] ? $validatedData['emailEdit'] : $teacher->email,
                'password' => $request->filled('passwordEdit') ? HASH::make($validatedData['passwordEdit']) : $teacher->password,
            ]);

            // Perbarui data relasi teacher
            $teacher->teacher->update([
                'NIP' => $validatedData['NIPEdit'],
                'institution' => $validatedData['institutionEdit'],
                'address' => $validatedData['addressEdit'],
            ]);

            // Simpan semua perubahan
            $teacher->save();

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Data pengajar berhasil diperbarui.',
                    'time' => now()->diffForHumans(),
                ]
            ]);
        } catch (\Exception $e) {
            $message = $e instanceof \Illuminate\Validation\ValidationException
                ? 'Terjadi kesalahan saat validasi data.'
                : 'Gagal Mengubah data pengajar. Terjadi kesalahan: ' . $e->getMessage();

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
            // Cari teacher berdasarkan ID
            $teacher = User::findOrFail($id);

            if (!$teacher) {
                return redirect()->back()->with('toasts', [
                    [
                        'type' => 'danger',
                        'title' => 'Gagal',
                        'message' => 'Data pengajar tidak ditemukan!',
                        'time' => now()->diffForHumans(),
                    ]
                ]);
            }

            // Perbarui deleted_at dengan timestamp saat ini
            $teacher->deleted_at = now();
            $teacher->save();

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Data pengajar telah dihapus.',
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
            // Cari teacher berdasarkan ID
            $teacher = User::findOrFail($id);

            // Hapus secara permanen
            $teacher->forceDelete();

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Pengajar berhasil dihapus secara permanen.',
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
