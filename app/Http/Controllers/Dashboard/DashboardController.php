<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth'); // Pastikan hanya user login yang bisa mengakses controller ini
    }

    public function admin()
    {
        $user = auth()->user();


        return view('admin.dashboard.index', [
            'activeMenu' => 'dashboard'
        ]);
    }

    public function teacher()
    {
        return view('teacher.dashboard');
    }

    public function student()
    {
        return view('student.dashboard.index', [
            'activeMenu' => 'dashboard'
        ]);
    }
}
