<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizChoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleQuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction(); // Mulai transaksi

            // Validasi input
            $request->validate([
                'moduleQuiz' => 'required|string|max:255',
                'typeQuestion' => 'nullable|string',
                'currentContent' => 'nullable|integer',
                'pointQuestion' => 'nullable|integer',
                'levelBloom' => 'nullable|string',
                'options' => 'nullable|array',
                'options.*.text' => 'nullable|string',
                'options.*.is_correct' => 'nullable|boolean',
                'options.*.feedback' => 'nullable|string',
            ]);

            // Simpan data quiz baru
            $quiz = Quiz::create([
                'content_id' => $request->currentContent,
                'question' => $request->moduleQuiz,
                'type' => $request->typeQuestion,
                'bloom_level' => $request->levelBloom,
                'point' => $request->pointQuestion,
            ]);

            // Simpan pilihan jawaban (options)
            foreach ($request->options as $choice) {
                QuizChoice::create([
                    'quiz_id' => $quiz->id,
                    'choice_text' => $choice['text'],
                    'is_correct' => $choice['is_correct'],
                    'feedback' => $choice['feedback'],
                ]);
            }

            DB::commit(); // Simpan perubahan

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => "Quiz berhasil ditambahkan",
                    'time' => now()->diffForHumans()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['general' => $e->getMessage()])
                ->withInput()
                ->with('toasts', [
                    [
                        'type' => 'danger',
                        'title' => 'Gagal',
                        'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                        'time' => now()->diffForHumans(),
                    ]
                ])->with('form_error', 'store');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        try {
            DB::beginTransaction(); // Mulai transaksi

            $request->validate([
                'moduleQuiz' => 'required|string|max:255',
                'typeQuestion' => 'nullable|string',
                'pointQuestion' => 'nullable|integer',
                'levelBloom' => 'nullable|string',
                'levelBloom' => 'nullable|string',
            ]);

            // Cari data berdasarkan ID yang dikirimkan
            $quiz = Quiz::findOrFail($id);

            // Update data
            $quiz->update([
                'question' => $request->moduleQuiz,
                'type' => $request->typeQuestion,
                'bloom_level' => $request->levelBloom,
                'point' => $request->pointQuestion,
            ]);

            // Update atau Tambahkan pilihan jawaban
            foreach ($request->options as $choice) {
                if (!empty($choice['id']) && is_numeric($choice['id'])) {
                    // Jika ID pilihan ada, update data yang sudah ada
                    $option = QuizChoice::find($choice['id']);
                    if ($option) {
                        $option->update([
                            'choice_text' => $choice['text'],
                            'is_correct' => $choice['is_correct'],
                            'feedback' => $choice['feedback'],
                        ]);
                    }
                } else {
                    // Jika tidak ada ID, buat data baru
                    QuizChoice::create([
                        'quiz_id' => $quiz->id,
                        'choice_text' => $choice['text'],
                        'is_correct' => $choice['is_correct'],
                        'feedback' => $choice['feedback'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('toasts', [
                [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'message' => "Quiz berhasil diperbarui",
                    'time' => now()->diffForHumans()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['general' => $e->getMessage()])
                ->withInput()
                ->with('toasts', [
                    [
                        'type' => 'danger',
                        'title' => 'Gagal',
                        'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                        'time' => now()->diffForHumans(),
                    ]
                ])->with('form_error', 'update');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
