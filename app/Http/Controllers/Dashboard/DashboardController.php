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
        return view('admin.dashboard.index');
    }

    public function teacher()
    {
        return view('teacher.dashboard');
    }

    public function student()
    {
        return view('student.dashboard');
    }
}
