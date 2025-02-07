<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        //
        $admins = User::where('role', 'admin')->get();
        return view('admin.users.admin', compact('admins'));
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

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 'admin',
                'password' => Hash::make($request->password),
            ]);

            // Simpan data tambahan berdasarkan role
            Admin::create([
                'user_id' => $user->id
            ]);

            return redirect('/dashboard/admin/users/admin')->with('toasts', [
                [
                    'type' => 'success',  // Jenis toast
                    'title' => 'Berhasil Menambahkan Admin',  // Judul toast
                    'message' => "admin $request->name berhasil ditambahkan",  // Pesan toast
                    'time' => now()->diffForHumans()  // Waktu toast
                ]
            ]);
        } catch (\Exception $e) {
            // Ambil pesan error dari exception
            $errorMessage = $e->getMessage();

            // Jika exception berkaitan dengan validasi, ambil pesan errornya
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return redirect()->back()
                    ->withErrors($e->errors()) // Mengembalikan semua error validasi
                    ->withInput()
                    ->with('toasts', [
                        [
                            'type' => 'danger',
                            'title' => 'Gagal Menambahkan Admin',
                            'message' => 'Terjadi kesalahan saat validasi data.',
                            'time' => now()->diffForHumans(),
                        ]
                    ]);
            }

            return redirect()->back()
                ->withErrors(['general' => $errorMessage]) // Simpan error lain ke dalam 'general'
                ->withInput()
                ->with('toasts', [
                    [
                        'type' => 'danger',
                        'title' => 'Gagal Menambahkan Admin',
                        'message' => 'Terjadi kesalahan: ' . $errorMessage,
                        'time' => now()->diffForHumans(),
                    ]
                ]);
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
