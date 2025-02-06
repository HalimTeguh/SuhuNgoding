<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // Cek apakah pengguna sudah login untuk semua guard yang diberikan
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Pengguna sudah login, arahkan ke dashboard sesuai role
                if (Auth::user()->role === 'admin') {
                    return redirect()->route('dashboard.admin');
                } elseif (Auth::user()->role === 'student') {
                    return redirect()->route('dashboard.student');
                } elseif (Auth::user()->role === 'teacher') {
                    return redirect()->route('dashboard.teacher');
                }
            }
        }

        // Jika belum login, lanjutkan permintaan ke route berikutnya (misalnya login atau register)
        return $next($request);
    }
}
