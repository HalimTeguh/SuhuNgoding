<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('authentication.login');
    }

    public function login(Request $request)
    {
        // Validasi input email & password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], true)) {

            // Simpan sesi login dengan masa berlaku 1 hari (1440 menit)
            $request->session()->put('last_activity', now());

            // Ambil role user yang login
            $user = Auth::user();

            // Cek role dan arahkan ke halaman sesuai role
            if ($user->role === 'admin') {
                return redirect()->route('dashboard.admin')->with('toasts', [
                    [
                        'type' => 'success',  // Jenis toast
                        'title' => 'Login Sukses',  // Judul toast
                        'message' => 'Selamat datang kembali!',  // Pesan toast
                        'time' => now()->diffForHumans()  // Waktu toast
                    ]
                ]);
                // return redirect()->route('dashboard.admin')->with('success', 'Login berhasil sebagai Admin!');
            } elseif ($user->role === 'student') {
                return redirect()->route('dashboard.student')->with('toasts', [
                    [
                        'type' => 'success',  // Jenis toast
                        'title' => 'Login Sukses',  // Judul toast
                        'message' => 'Selamat datang kembali!',  // Pesan toast
                        'time' => now()->diffForHumans()  // Waktu toast
                    ]
                ]);
            }
            // elseif ($user->role === 'teacher') {
            //     return redirect()->route('teacher.dashboard')->with('success', 'Login berhasil sebagai Teacher!');
            // } else {
            //     return redirect()->route('student.dashboard')->with('success', 'Login berhasil sebagai Student!');
            // }
        }

        // return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput()->with('toasts', [
            [
                'type' => 'danger',  // Jenis toast
                'title' => 'Login Gagal',  // Judul toast
                'message' => 'Email atau password yang Anda masukkan salah.',  // Pesan toast
                'time' => now()->diffForHumans()  // Waktu toast
            ]
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah logout.');
    }
}
