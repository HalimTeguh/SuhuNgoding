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
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ModuleContentController;
use App\Http\Controllers\ModuleControlController;
use App\Http\Controllers\StudentClassController;
use App\Http\Controllers\StudentTestingController;
use App\Http\Controllers\TestingQuesController;
use App\Models\Admin;
use App\Models\Classes;
use App\Models\Gamification;
use App\Models\StudentTestAnswer;
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

    Route::get('/change-password', [LoginController::class, 'changePasswordView'])->name('changePassword');
    Route::post('/change-password', [LoginController::class, 'changePassword'])->name('changePassword.submit');

    Route::post('/generate-soal/preposttest', [AiController::class, 'generatePrePostTestQuestions']);

    Route::post('/dashboard/admin/testing/quiz/generate', [TestingQuesController::class, 'saveGenerateQuestionFromLLM'])->name('quiz.generate');

    Route::post('/dashboard/admin/testing/quiz/save-question-from-json', [TestingQuesController::class, 'saveQuestionFromJson'])->name('quiz.save-json');
});



Route::middleware(['auth'])->group(function () {
    // Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    Route::post('/upload-image', [FileController::class, 'uploadImage'])->name('upload.image');

    Route::post('/generate-soal', [AiController::class, 'generateSoalFromLLM']);

    Route::post('/generate-soal/preposttest', [AiController::class, 'generatePrePostTestQuestions']);

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
        Route::get('/dashboard/admin/pembelajaran/module/{moduleId}/content/{contentId}/control', [ModuleController::class, 'getModuleControl'])->name('dashboard.module.control');
        Route::post('/dashboard/admin/pembelajaran/module/{moduleId}/content/{contentId}/import', [ModuleController::class, 'importModule'])->name('dashboard.module.importContent');
        Route::resource('/dashboard/admin/pembelajaran/content', ModuleContentController::class);
        Route::resource('/dashboard/admin/pembelajaran/quiz', ModuleQuizController::class);
        Route::post('/dashboard/admin/pembelajaran/importQuiz', [ModuleQuizController::class, 'importQuizFromJsonText'])->name('dashboard.module.importQuizJson');
        Route::put('/dashboard/admin/pembelajaran/content/control/update', [ModuleControlController::class, 'update'])->name('dashboard.module.control.update');


        Route::delete('/dashboard/admin/pembelajaran/quiz/option/{optionId}', [ModuleQuizController::class, 'deleteOption'])->name('dashboard.module.quiz.deleteOption');

        Route::resource('/dashboard/admin/pembelajaran/gamification', GamificationController::class);

        Route::resource('/dashboard/admin/testing/quiz', TestingQuesController::class);
        Route::get('/dashboard/admin/testing/setting', [TestingQuesController::class, 'testingEnvironment'])->name('dashboard.testing.setting');
        Route::post('/dashboard/admin/testing/setting/assign-class', [TestingQuesController::class, 'assignClass'])->name('dashboard.testing.assignClass');
        Route::get('/dashboard/admin/testing/setting/class/{classId}/module/{moduleId}/summary', [TestingQuesController::class, 'getClassTestingSummary']);

        Route::post('/dashboard/admin/testing/setting/start-test', [TestingQuesController::class, 'startTest'])->name('testing.start');
        Route::post('/dashboard/admin/testing/setting/reset-test', [TestingQuesController::class, 'resetTest'])->name('testing.reset');

        Route::post('/dashboard/admin/testing/setting/divide-class', [TestingQuesController::class, 'divideIndependentSampling'])->name('testing.divideClass');
        Route::post('/dashboard/admin/testing/setting/reset-class', [TestingQuesController::class, 'resetIndependentSampling'])->name('testing.resetClass');

        Route::get('/dashboard/admin/testing/setting/class/{classId}/module/{moduleId}/levenes-test', [TestingQuesController::class, 'levenesPretest'])->name('testing.levenesTest');

        Route::get('/dashboard/admin/testing/setting/class/{classId}/module/{moduleId}/paired-test', [TestingQuesController::class, 'getPairedTestResult'])->name('testing.pairedTest');
        Route::post('/dashboard/admin/testing/paired-test/run', [TestingQuesController::class, 'runPairedTTest'])->name('testing.pairedTest.run');

        Route::get('/dashboard/admin/testing/setting/class/{classId}/module/{moduleId}/independent-test', [TestingQuesController::class, 'getIndependentTestResult'])->name('testing.independentTest');
        Route::post('/dashboard/admin/testing/independent-test/run', [TestingQuesController::class, 'runIndependentTTest'])->name('testing.independentTest.run');

        Route::post('/dashboard/admin/testing/change-class', [TestingQuesController::class, 'moveStudentClass'])->name('testing.moveClass');

        Route::get('/dashboard/admin/testing/setting/export-summary/{classId}/{moduleId}', [TestingQuesController::class, 'exportSummary'])
            ->name('testing.exportSummary');

        // Route::post('/convert-pdf', [ModuleQuizController::class, 'convertPdf']);

    });

    // ROLE => Teacher
    Route::middleware('role:teacher')->group(function () {

        Route::get('/dashboard/teacher', [DashboardController::class, 'teacher'])->name('dashboard.teacher');

        Route::resource('/dashboard/teacher/users/student', StudentController::class);
        Route::put('/dashboard/teacher/users/student/s/{student}', [StudentController::class, 'softDelete'])->name('dashboard.teacher.softDelete');

        Route::resource('/dashboard/teacher/pembelajaran/class', ClassController::class);
        Route::put('/dashboard/teacher/pembelajaran/class/s/{class}', [ClassController::class, 'softDelete'])->name('dashboard.teacher.class.softDelete');
        Route::post('/dashboard/teacher/pembelajaran/class/{class}/attach-modules', [ClassController::class, 'attachModules'])->name('dashboard.teacher.class.attachModule');
        Route::delete('/dashboard/teacher/pembelajaran/class/{class}/modules/{module}', [ClassController::class, 'detachModule'])->name('dashboard.teacher.class.detachModule');
        Route::get('/dashboard/teacher/pembelajaran/class/{class}/modules/{module}/progress', [ClassController::class, 'moduleProgress'])->name('dashboard.teacher.class.moduleProgress');
        Route::post('/dashboard/teacher/pembelajaran/class/{class}/attach-student', [ClassController::class, 'attachStudent'])->name('dashboard.teacher.class.attachStudent');
        Route::delete('/dashboard/teacher/pembelajaran/class/{class}/students/{student}', [ClassController::class, 'detachStudent'])->name('dashboard.teacher.class.detachStudent');

        Route::resource('/dashboard/teacher/pembelajaran/module', ModuleController::class);
        Route::put('/dashboard/teacher/pembelajaran/module/{module}/uploadImage', [ModuleController::class, 'uploadImage'])->name('dashboard.teacher.module.uploadImage');
        Route::put('/dashboard/teacher/pembelajaran/module/{module}/resetImage', [ModuleController::class, 'resetImage'])->name('dashboard.teacher.module.resetImage');

        Route::get('/dashboard/teacher/pembelajaran/module/{moduleId}/content/{contentId}', [ModuleController::class, 'getModuleContent'])->name('dashboard.teacher.module.content');
        Route::get('/dashboard/teacher/pembelajaran/module/{moduleId}/content/{contentId}/quiz', [ModuleController::class, 'getModuleQuiz'])->name('dashboard.teacher.module.quiz');
        Route::get('/dashboard/teacher/pembelajaran/module/{moduleId}/content/{contentId}/control', [ModuleController::class, 'getModuleControl'])->name('dashboard.teacher.module.control');
        Route::post('/dashboard/teacher/pembelajaran/module/{moduleId}/content/{contentId}/import', [ModuleController::class, 'importModule'])->name('dashboard.teacher.module.importContent');
        Route::resource('/dashboard/teacher/pembelajaran/content', ModuleContentController::class);
        Route::resource('/dashboard/teacher/pembelajaran/quiz', ModuleQuizController::class);
        Route::post('/dashboard/teacher/pembelajaran/importQuiz', [ModuleQuizController::class, 'importQuizFromJsonText'])->name('dashboard.teacher.module.importQuizJson');
        Route::put('/dashboard/teacher/pembelajaran/content/control/update', [ModuleControlController::class, 'update'])->name('dashboard.teacher.module.control.update');
        Route::delete('/dashboard/teacher/pembelajaran/quiz/option/{optionId}', [ModuleQuizController::class, 'deleteOption'])->name('dashboard.teacher.module.quiz.deleteOption');

        Route::resource('/dashboard/teacher/pembelajaran/gamification', GamificationController::class);

        Route::resource('/dashboard/teacher/testing/quiz', TestingQuesController::class);
        Route::get('/dashboard/teacher/testing/setting', [TestingQuesController::class, 'testingEnvironment'])->name('dashboard.teacher.testing.setting');
        Route::post('/dashboard/teacher/testing/setting/assign-class', [TestingQuesController::class, 'assignClass'])->name('dashboard.teacher.testing.assignClass');
        Route::get('/dashboard/teacher/testing/setting/class/{classId}/module/{moduleId}/summary', [TestingQuesController::class, 'getClassTestingSummary']);

        Route::post('/dashboard/teacher/testing/setting/start-test', [TestingQuesController::class, 'startTest'])->name('teacher.testing.start');
        Route::post('/dashboard/teacher/testing/setting/reset-test', [TestingQuesController::class, 'resetTest'])->name('teacher.testing.reset');

        Route::post('/dashboard/teacher/testing/setting/divide-class', [TestingQuesController::class, 'divideIndependentSampling'])->name('teacher.testing.divideClass');
        Route::post('/dashboard/teacher/testing/setting/reset-class', [TestingQuesController::class, 'resetIndependentSampling'])->name('teacher.testing.resetClass');

        Route::get('/dashboard/teacher/testing/setting/class/{classId}/module/{moduleId}/levenes-test', [TestingQuesController::class, 'levenesPretest'])->name('teacher.testing.levenesTest');

        Route::get('/dashboard/teacher/testing/setting/class/{classId}/module/{moduleId}/paired-test', [TestingQuesController::class, 'getPairedTestResult'])->name('teacher.testing.pairedTest');
        Route::post('/dashboard/teacher/testing/paired-test/run', [TestingQuesController::class, 'runPairedTTest'])->name('teacher.testing.pairedTest.run');

        Route::get('/dashboard/teacher/testing/setting/class/{classId}/module/{moduleId}/independent-test', [TestingQuesController::class, 'getIndependentTestResult'])->name('teacher.testing.independentTest');
        Route::post('/dashboard/teacher/testing/independent-test/run', [TestingQuesController::class, 'runIndependentTTest'])->name('teacher.testing.independentTest.run');

        Route::post('/dashboard/teacher/testing/change-class', [TestingQuesController::class, 'moveStudentClass'])->name('teacher.testing.moveClass');

        Route::get('/dashboard/teacher/testing/setting/export-summary/{classId}/{moduleId}', [TestingQuesController::class, 'exportSummary'])
            ->name('testing.exportSummary');

        Route::post('/convert-pdf', [ModuleQuizController::class, 'convertPdf']);

    });


    // ROLE => STUDENT
    Route::middleware('role:student')->group(function () {

        Route::get('/dashboard/student', [DashboardController::class, 'student'])->name('dashboard.student');
        Route::get('/dashboard/student/class/', [StudentClassController::class, 'index'])->name('dashboard.student.class');
        Route::get('/dashboard/student/class/{classId}', [StudentClassController::class, 'show'])->name('dashboard.student.class.show');
        Route::get('/dashboard/student/class/{classId}/module/{moduleId}', [StudentClassController::class, 'showContent'])->name('dashboard.student.module');
        Route::get('/dashboard/student/class/{classId}/module/{moduleId}/quiz/', [StudentClassController::class, 'showQuizContent'])->name('dashboard.student.module.quiz');

        Route::post('/dashboard/student/class/{classId}/module/{moduleId}/quiz/', [StudentClassController::class, 'saveQuizStudent'])->name('dashboard.student.module.quiz.submit');
        Route::get('/dashboard/student/class/{classId}/module/{moduleId}/quiz/result/{summaryId}', [StudentClassController::class, 'showResultQuiz'])
            ->name('dashboard.student.module.quiz.result');
        Route::post('/dashboard/student/save-duration/content/', [StudentClassController::class, 'saveDurationStudyContent'])->name('dashboard.student.module.saveDurationStudyContent');

        Route::get('/dashboard/student/leaderboard/', [StudentClassController::class, 'showLeaderboard'])->name('dashboard.student.class.leaderboard');

        Route::get('/dashboard/student/change-password/', [LoginController::class, 'changePasswordView'])->name('dashboard.student.changePassword');
        Route::post('/dashboard/student/change-password/', [LoginController::class, 'changePassword'])->name('dashboard.student.changePassword.submit');

        Route::get('/dashboard/student/pre-test', [StudentTestingController::class, 'showPretest'])->name('dashboard.student.pretest');
        Route::post('/dashboard/student/pre-test/submit', [StudentTestingController::class, 'submitPretest'])->name('dashboard.student.pretest.submit');

        Route::get('/dashboard/student/post-test', [StudentTestingController::class, 'showPosttest'])->name('dashboard.student.posttest');
        Route::post('/dashboard/student/post-test/submit', [StudentTestingController::class, 'submitPosttest'])->name('dashboard.student.posttest.submit');
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
