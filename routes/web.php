<?php

use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\RegisterController;
use App\Http\Controllers\CodeExecutionController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\ClassController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ModuleController;
use App\Http\Controllers\Dashboard\ModuleQuizController;
use App\Http\Controllers\Dashboard\StudentController;
use App\Http\Controllers\Dashboard\TeacherController;
use App\Http\Controllers\ExceptionPageController;
use App\Http\Controllers\ModuleContentController;
use App\Models\Admin;
use App\Models\Classes;
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

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/execute', [CodeExecutionController::class, 'executePython']);

Route::get('/test-docker', function () {
    return shell_exec("docker --version 2>&1");
});

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

        Route::resource('/dashboard/admin/users/admin', AdminController::class);
        Route::put('/dashboard/admin/users/admin/s/{admin}', [AdminController::class, 'softDelete'])->name('dashboard.admin.softDelete');
        
        Route::resource('/dashboard/admin/users/teacher', TeacherController::class);
        Route::put('/dashboard/admin/users/teacher/s/{teacher}', [TeacherController::class, 'softDelete'])->name('dashboard.teacher.softDelete');

        Route::resource('/dashboard/admin/users/student', StudentController::class);
        Route::put('/dashboard/admin/users/student/s/{student}', [StudentController::class, 'softDelete'])->name('dashboard.student.softDelete');
        
        Route::resource('/dashboard/admin/pembelajaran/class', ClassController::class);
        Route::put('/dashboard/admin/pembelajaran/class/s/{class}', [ClassController::class, 'softDelete'])->name('dashboard.class.softDelete');

        Route::resource('/dashboard/admin/pembelajaran/module', ModuleController::class);
        Route::put('/dashboard/admin/pembelajaran/module/{module}/uploadImage', [ModuleController::class, 'uploadImage'])->name('dashboard.module.uploadImage');
        Route::put('/dashboard/admin/pembelajaran/module/{module}/resetImage', [ModuleController::class, 'resetImage'])->name('dashboard.module.resetImage');
        
        Route::get('/dashboard/admin/pembelajaran/module/{moduleId}/content/{contentId}', [ModuleController::class, 'getModuleContent'])->name('dashboard.module.content');
        Route::get('/dashboard/admin/pembelajaran/module/{moduleId}/content/{contentId}/quiz', [ModuleController::class, 'getModuleQuiz'])->name('dashboard.module.quiz');
        Route::resource('/dashboard/admin/pembelajaran/content', ModuleContentController::class);
        Route::resource('/dashboard/admin/pembelajaran/quiz', ModuleQuizController::class);

        Route::delete('/dashboard/admin/pembelajaran/quiz/option/{optionId}', [ModuleQuizController::class, 'deleteOption'])->name('dashboard.module.quiz.deleteOption');

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