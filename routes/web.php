<?php

use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\RegisterController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\ExceptionPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('dashboard.index');
});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    
    Route::get('/register', [RegisterController::class, 'index']);
    Route::post('/register', [RegisterController::class, 'create']);
});



Route::middleware(['auth'])->group(function () {
    // Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    });

    // Route::middleware('role:teacher')->group(function () {
    //     Route::get('/dashboard/teacher', [DashboardController::class, 'teacher'])->name('dashboard.teacher');
    // });

    // Route::middleware('role:student')->group(function () {
    //     Route::get('/dashboard/student', [DashboardController::class, 'student'])->name('dashboard.student');
    // });
});

Route::get('/maintenance', [ExceptionPageController::class, 'maintenance'])->name('maintenance');
Route::get('/notFound', [ExceptionPageController::class, 'notFound'])->name('notFound');