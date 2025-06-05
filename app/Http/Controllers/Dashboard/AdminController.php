<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
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
        // Ambil hanya admin yang belum dihapus (deleted_at == null)
        $admins = User::where('role', 'admin')
            ->whereNull('deleted_at')
            ->get();
        

        return view('admin.users.admin', [
            'admins' => $admins,
            'activeMenu' => 'admin'
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
        try {
            $request->validate([
                'nameCreate' => 'required|string|max:255',
                'emailCreate' => 'required|email|unique:users,email',
                'passwordCreate' => 'required|min:6',
            ]);

            $user = User::create([
                'name' => $request->nameCreate,
                'email' => $request->emailCreate,
                'role' => 'admin',
                'password' => Hash::make($request->passwordCreate),
            ]);

            // Simpan data tambahan berdasarkan role
            Admin::create([
                'user_id' => $user->id
            ]);

            return redirect('/dashboard/admin/users/admin')->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil Menambahkan Admin',
                    'message' => "Admin $request->nameCreate berhasil ditambahkan",
                    'time' => now()->diffForHumans(),
                ]
            ]);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return redirect()->back()
                    ->withErrors($e->errors())
                    ->withInput()
                    ->with('toasts', [
                        [
                            'type' => 'danger',
                            'title' => 'Gagal Menambahkan Admin',
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
                        'title' => 'Gagal Menambahkan Admin',
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
        $admin = User::find($id);
        return response()->json($admin);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, int $id)
    {
        try {
            $admin = User::findOrFail($id);


            // Validasi input
            $validatedData = $request->validate([
                'nameEdit' => 'required|string|max:255',
                'emailEdit' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($id),
                ],
                'passwordEdit' => 'nullable|min:6',
            ]);

            // Isi atribut yang perlu diperbarui
            $admin->fill([
                'name' => $validatedData['nameEdit'],
                'email' => $validatedData['emailEdit'],
            ]);

            // Update password jika diisi
            if (!empty($validatedData['passwordEdit'])) {
                $admin->password = Hash::make($validatedData['passwordEdit']);
            }

            $admin->save();

            // Redirect dengan notifikasi berhasil
            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Admin',
                    'message' => 'Data admin berhasil diperbarui.',
                    'time' => now()->diffForHumans(),
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani error validasi

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('toasts', [
                    [
                        'type' => 'danger',
                        'title' => 'Gagal Mengubah Data Admin',
                        'message' => 'Terjadi kesalahan saat validasi data.',
                        'time' => now()->diffForHumans(),
                    ]
                ])->with([
                    'form_error' => 'update',
                    'entity_id' => $id, // Kirim ID admin yang sedang diedit
                ]);
        } catch (\Exception $e) {
            // Tangani error umum lainnya
            $errorMessage = $e->getMessage();

            return redirect()->back()
                ->withErrors(['general' => $errorMessage])
                ->withInput()
                ->with('toasts', [
                    [
                        'type' => 'danger',
                        'title' => 'Gagal Mengubah Data Admin',
                        'message' => 'Gagal mengubah data admin. Terjadi kesalahan: ' . $errorMessage,
                        'time' => now()->diffForHumans(),
                    ]
                ])->with([
                    'form_error' => 'update',
                    'entity_id' => $id, // Kirim ID admin yang sedang diedit
                ]);
        }
    }



    public function softDelete($id)
    {
        try {
            // Cari admin berdasarkan ID
            $admin = User::findOrFail($id);

            if (!$admin) {
                return redirect()->back()->with('toasts', [
                    [
                        'type' => 'danger',
                        'title' => 'Gagal',
                        'message' => 'Data admin tidak ditemukan!',
                        'time' => now()->diffForHumans(),
                    ]
                ]);
            }

            // Perbarui deleted_at dengan timestamp saat ini
            $admin->deleted_at = now();
            $admin->save();

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Admin telah dihapus.',
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
            // Cari admin berdasarkan ID
            $admin = User::findOrFail($id);

            // Hapus secara permanen
            $admin->forceDelete();

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => 'Admin berhasil dihapus secara permanen.',
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
