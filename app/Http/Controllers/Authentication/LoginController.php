<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        return redirect('/')->with('success', 'Anda telah logout.');
    }

    public function changePasswordView()
    {
        $user = auth()->user();
        return view('authentication.changePassword', [
            'user' => $user,
            'activeMenu' => 'dashboard'
        ]);
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();

        $usermodel = User::find($user->id);

        if (!$usermodel) {
            abort(403, 'User tidak ditemukan.');
        }

        // Validasi input
        $validatedData = $request->validate([
            'email' => 'required|email|exists:users,email',
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Verifikasi email agar sesuai dengan user login
        if ($validatedData['email'] !== $usermodel->email) {
            return back()->withErrors(['email' => 'Email tidak cocok dengan akun kamu.']);
        }

        // Verifikasi password lama
        if (!Hash::check($validatedData['old_password'], $usermodel->password)) {
            return back()->withErrors(['old_password' => 'Password lama salah.']);
        }

        // Update password
        $usermodel->password = Hash::make($validatedData['new_password']);
        $usermodel->save();

        // Buat toast
        $toast = [
            'type' => 'success',
            'title' => 'Password Diperbarui',
            'message' => 'Password kamu berhasil diperbarui.',
            'time' => now()->diffForHumans()
        ];

        // Redirect sesuai role
        if ($usermodel->role === 'admin') {
            return redirect()->route('dashboard.admin')->with('toasts', [$toast]);
        } elseif ($usermodel->role === 'teacher') {
            return redirect()->route('dashboard.teacher')->with('toasts', [$toast]);
        } elseif ($usermodel->role === 'student') {
            return redirect()->route('dashboard.student')->with('toasts', [$toast]);
        } else {
            return back()->with('toasts', [$toast]);
        }
    }
}
