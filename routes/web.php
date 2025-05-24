<?php

use App\Http\Controllers\AiController;
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
use App\Http\Controllers\FileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ModuleContentController;
use App\Http\Controllers\StudentClassController;
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

Route::get('/', [LandingController::class, 'index']);

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/execute', [CodeExecutionController::class, 'executePython']);

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login');

    Route::get('/register', [RegisterController::class, 'index']);
    Route::post('/register', [RegisterController::class, 'create']);
});



Route::middleware(['auth'])->group(function () {
    // Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    Route::post('/upload-image', [FileController::class, 'uploadImage'])->name('upload.image');

    Route::post('/generate-soal', [AiController::class, 'generateSoalFromLLM']);

    Route::get('/download/template-student', [FileController::class, 'downloadTemplateStudentExcel'])->name('download.template.student');



    // ROLE => ADMIN
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
        Route::post('/dashboard/admin/pembelajaran/class/{class}/attach-modules', [ClassController::class, 'attachModules'])->name('dashboard.class.attachModule');
        Route::delete('/dashboard/admin/pembelajaran/class/{class}/modules/{module}', [ClassController::class, 'detachModule'])->name('dashboard.class.detachModule');
        Route::post('/dashboard/admin/pembelajaran/class/{class}/attach-student', [ClassController::class, 'attachStudent'])->name('dashboard.class.attachStudent');
        Route::delete('/dashboard/admin/pembelajaran/class/{class}/students/{student}', [ClassController::class, 'detachStudent'])->name('dashboard.class.detachStudent');

        Route::resource('/dashboard/admin/pembelajaran/module', ModuleController::class);
        Route::put('/dashboard/admin/pembelajaran/module/{module}/uploadImage', [ModuleController::class, 'uploadImage'])->name('dashboard.module.uploadImage');
        Route::put('/dashboard/admin/pembelajaran/module/{module}/resetImage', [ModuleController::class, 'resetImage'])->name('dashboard.module.resetImage');

        Route::get('/dashboard/admin/pembelajaran/module/{moduleId}/content/{contentId}', [ModuleController::class, 'getModuleContent'])->name('dashboard.module.content');
        Route::get('/dashboard/admin/pembelajaran/module/{moduleId}/content/{contentId}/quiz', [ModuleController::class, 'getModuleQuiz'])->name('dashboard.module.quiz');
        Route::post('/dashboard/admin/pembelajaran/module/{moduleId}/content/{contentId}/import', [ModuleController::class, 'importModule'])->name('dashboard.module.importContent');
        Route::resource('/dashboard/admin/pembelajaran/content', ModuleContentController::class);
        Route::resource('/dashboard/admin/pembelajaran/quiz', ModuleQuizController::class);

        Route::delete('/dashboard/admin/pembelajaran/quiz/option/{optionId}', [ModuleQuizController::class, 'deleteOption'])->name('dashboard.module.quiz.deleteOption');


        // Route::post('/convert-pdf', [ModuleQuizController::class, 'convertPdf']);

    });


    // ROLE => STUDENT
    Route::middleware('role:student')->group(function () {

        Route::get('/dashboard/student', [DashboardController::class, 'student'])->name('dashboard.student');
        Route::get('/dashboard/student/class/', [StudentClassController::class, 'index'])->name('dashboard.student.class');
        Route::get('/dashboard/student/class/{classid}', [StudentClassController::class, 'show'])->name('dashboard.student.class.show');


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
