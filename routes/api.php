<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::post('/convert-pdf-to-md', [FileController::class, 'convert'])->withoutMiddleware(['auth:sanctum']);

// Kirim file PDF dan mulai proses convert (asynchronous)
Route::post('/convert-pdf-to-md', [FileController::class, 'convert']);

// Cek status convert berdasarkan UUID (polling dari frontend)
Route::get('/convert-status/{uuid}', [FileController::class, 'status']);

// Ambil hasil convert jika sudah selesai (markdown, gambar, dll)
Route::get('/convert-result/{uuid}', [FileController::class, 'result']);

Route::get('/convert-log/{uuid}', [FileController::class, 'log']);

Route::post('/ai/generate-quiz', [AiController::class, 'generateSoalFromLLM'])->name('api.ai.generate');


