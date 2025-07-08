<?php

namespace App\Http\Controllers;

use App\Models\Gamification;
use App\Models\User;
use Illuminate\Http\Request;

class GamificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        // Ambil hanya admin yang belum dihapus (deleted_at == null)
        $gamiData = Gamification::all();

        if ($user->role == 'admin') {
            return view('admin.pembelajaran.gamification.gamification', [
                'user' => $user,
                'gamiData' => $gamiData,
                'activeMenu' => 'gamification'
            ]);
        } elseif ($user->role == 'teacher') {
            return view('teacher.gamification.gamification', [
                'user' => $user,
                'gamiData' => $gamiData,
                'activeMenu' => 'gamification'
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
     * Store a newly created gamification record in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'bloom_level' => 'required|integer|in:1,2,3,4,5,6',
            'point' => 'required|integer|min:0',
            'first_attempt_multiply_point' => 'required|numeric|min:0',
            'second_attempt_multiply_point' => 'required|numeric|min:0',
            'third_attempt_multiply_point' => 'required|numeric|min:0',
        ]);

        // Simpan data gamifikasi baru ke dalam database
        Gamification::create([
            'bloom_level' => $validated['bloom_level'],
            'point' => $validated['point'],
            'first_attempt_multiply_point' => $validated['first_attempt_multiply_point'],
            'second_attempt_multiply_point' => $validated['second_attempt_multiply_point'],
            'third_attempt_multiply_point' => $validated['third_attempt_multiply_point'],
        ]);

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('toasts', [
            [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => "Data berhasil ditambahkan",
                'time' => now()->diffForHumans()
            ]
        ]);
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
     * Update the specified gamification record in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'bloom_level' => 'required|integer|in:1,2,3,4,5,6',
            'point' => 'required|integer|min:0',
            'first_attempt_multiply_point' => 'required|numeric|min:0',
            'second_attempt_multiply_point' => 'required|numeric|min:0',
            'third_attempt_multiply_point' => 'required|numeric|min:0',
        ]);

        // Temukan data gamifikasi berdasarkan ID
        $gamification = Gamification::findOrFail($id);

        // Perbarui data gamifikasi
        $gamification->update([
            'bloom_level' => $validated['bloom_level'],
            'point' => $validated['point'],
            'first_attempt_multiply_point' => $validated['first_attempt_multiply_point'],
            'second_attempt_multiply_point' => $validated['second_attempt_multiply_point'],
            'third_attempt_multiply_point' => $validated['third_attempt_multiply_point'],
        ]);

        // Redirect ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('toasts', [
            [
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => "Data berhasil diperbarui",
                'time' => now()->diffForHumans()
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Mencari data gamifikasi berdasarkan ID
        $gamification = Gamification::findOrFail($id);

        // Menghapus data gamifikasi
        $gamification->delete();

        // Mengarahkan kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Data gamifikasi berhasil dihapus!');
    }
}
