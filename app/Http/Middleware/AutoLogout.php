<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sedang login dan ada sesi aktivitas terakhir
        if (Auth::check() && session()->has('last_activity')) {
            $lastActivity = session('last_activity');

            // Hitung waktu idle dalam menit (1 hari = 1440 menit)
            if (now()->diffInMinutes($lastActivity) > 1440) {
                Auth::logout();
                $request->session()->invalidate();
                return redirect('/login')->with('error', 'Anda telah logout karena tidak aktif selama 1 hari.');
            }
        }

        // Perbarui waktu aktivitas terakhir dalam sesi
        session(['last_activity' => now()]);

        return $next($request);
    }
}
