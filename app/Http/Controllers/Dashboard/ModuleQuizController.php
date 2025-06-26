<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ModuleContent;
use App\Models\Quiz;
use App\Models\QuizChoice;
use App\Models\QuizCode;
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
                'moduleQuiz' => 'required|string',
                'typeQuestion' => 'required|string|in:multiple_choice,code',
                'currentContent' => 'nullable|integer',
                'pointQuestion' => 'nullable|integer',
                'levelBloom' => 'nullable|string',
                'options' => 'nullable|array|required_if:typeQuestion,multiple_choice',
                'options.*.text' => 'required_if:typeQuestion,multiple_choice|string',
                'options.*.is_correct' => 'required_if:typeQuestion,multiple_choice|boolean',
                'options.*.feedback' => 'nullable|string',
                'codeAnswer' => 'nullable|string|required_if:typeQuestion,code',
                'codeOutputInput' => 'nullable|string|required_if:typeQuestion,code',
                'codeFeedback' => 'nullable|string|',
            ]);

            // Simpan data quiz baru
            $quiz = Quiz::create([
                'content_id' => $request->currentContent,
                'question' => $request->moduleQuiz,
                'type' => $request->typeQuestion,
                'bloom_level' => $request->levelBloom,
                'point' => $request->pointQuestion,
            ]);

            // Jika tipe soal adalah multiple choice, simpan pilihan jawaban
            if ($request->typeQuestion === 'multiple_choice') {
                foreach ($request->options as $choice) {
                    QuizChoice::create([
                        'quiz_id' => $quiz->id,
                        'choice_text' => $choice['text'],
                        'is_correct' => $choice['is_correct'],
                        'feedback' => $choice['feedback'] ?? null,
                    ]);
                }
            }
            // Jika tipe soal adalah code, simpan kode dan test case
            elseif ($request->typeQuestion === 'code') {
                $quizCode = new QuizCode([
                    'quiz_id' => $quiz->id,
                    'test_cases' => $request->codeAnswer,
                    'expected_output' => $request->codeOutputInput,
                    'language' => "python",
                    'feedback' => $request->codeFeedback ?? null,
                ]);
                $quizCode->save();
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
        try {
            DB::beginTransaction(); // Mulai transaksi

            $request->validate([
                'moduleQuiz' => 'required|string',
                'typeQuestion' => 'nullable|string|in:multiple_choice,code',
                'pointQuestion' => 'nullable|integer',
                'levelBloom' => 'nullable|string',
                'options' => 'nullable|array|required_if:typeQuestion,multiple_choice',
                'options.*.id' => 'nullable',
                'options.*.text' => 'required_if:typeQuestion,multiple_choice|string',
                'options.*.is_correct' => 'required_if:typeQuestion,multiple_choice|boolean',
                'options.*.feedback' => 'nullable|string',
                'codeAnswer' => 'nullable|string|required_if:typeQuestion,code',
                'codeOutputInput' => 'nullable|string|required_if:typeQuestion,code',
            ]);

            $quiz = Quiz::findOrFail($id);

            $quiz->update([
                'question' => $request->moduleQuiz,
                'type' => $request->typeQuestion,
                'bloom_level' => $request->levelBloom,
                'point' => $request->pointQuestion,
            ]);

            if ($request->typeQuestion === 'multiple_choice') {
                foreach ($request->options as $choice) {
                    if (!empty($choice['id']) && is_numeric($choice['id'])) {
                        $option = QuizChoice::find($choice['id']);
                        if ($option) {
                            $option->update([
                                'choice_text' => $choice['text'],
                                'is_correct' => $choice['is_correct'],
                                'feedback' => $choice['feedback'],
                            ]);
                        }
                    } else {
                        QuizChoice::create([
                            'quiz_id' => $quiz->id,
                            'choice_text' => $choice['text'],
                            'is_correct' => $choice['is_correct'],
                            'feedback' => $choice['feedback'],
                        ]);
                    }
                }
            } elseif ($request->typeQuestion === 'code') {
                $quizCode = $quiz->code()->firstOrNew();
                $quizCode->test_cases = $request->codeAnswer;
                $quizCode->expected_output = $request->codeOutputInput;
                $quizCode->language = "python";
                $quizCode->feedback = $request->codeFeedback;
                $quizCode->save();
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

    public function importQuizFromJsonText(Request $request)
    {
        $request->validate([
            'quiz_json' => 'required|string',
            'content_id' => 'required|exists:module_contents,id',
        ]);

        $jsonContent = json_decode($request->input('quiz_json'), true);

        if (!is_array($jsonContent)) {
            return redirect()->back()->with('toasts', [[
                'type' => 'error',
                'title' => 'Gagal',
                'message' => 'Format JSON tidak valid. Pastikan struktur sudah benar dan tidak kosong.',
                'time' => now()->diffForHumans()
            ]]);
        }

        $content = ModuleContent::findOrFail($request->input('content_id'));

        DB::beginTransaction();
        try {
            $content->quizzes()->delete();

            $levelPoints = [
                'Mengingat' => 1,
                'Memahami' => 2,
                'Menerapkan' => 3,
                'Menganalisis' => 4,
                'Mengevaluasi' => 5
            ];

            $levelMap = [
                'Mengingat' => 'remember',
                'Memahami' => 'understand',
                'Menerapkan' => 'apply',
                'Menganalisis' => 'analyze',
                'Mengevaluasi' => 'evaluate'
            ];

            foreach ($jsonContent as $quizData) {
                $levelId = $quizData['level'] ?? 'Mengingat';
                $engLevel = $levelMap[$levelId] ?? 'remember';
                $point = $levelPoints[$levelId] ?? 1;

                $questionText = $quizData['question'] ?? '-';

                if (!empty($quizData['reference_code']) && $engLevel !== 'apply') {
                    $referenceCodeDiv = '<div class="referensi_code"><pre>' . htmlentities($quizData['reference_code']) . '</pre></div>';
                    $questionText .= '<br>' . $referenceCodeDiv;
                }

                $quiz = Quiz::create([
                    'content_id' => $content->id,
                    'question' => $questionText,
                    'type' => $engLevel === 'apply' ? 'code' : 'multiple_choice',
                    'bloom_level' => $engLevel,
                    'point' => $point
                ]);

                if ($engLevel === 'apply') {
                    $quiz->code()->create([
                        'quiz_id' => $quiz->id,
                        'test_cases' => $quizData['reference_code'] ?? '',
                        'expected_output' => $quizData['output'] ?? '',
                        'language' => 'python',
                        'feedback' => $quizData['feedback'] ?? 'Output sesuai dengan format yang diminta.'
                    ]);
                } else {
                    foreach ($quizData['choices'] ?? [] as $choice) {
                        $quiz->choices()->create([
                            'quiz_id' => $quiz->id,
                            'choice_text' => $choice['answer'] ?? '-',
                            'is_correct' => $choice['is_correct'] ?? false,
                            'feedback' => $choice['feedback'] ?? ''
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('toasts', [[
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => "Quiz berhasil diimpor ke {$content->title}",
                'time' => now()->diffForHumans()
            ]]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toasts', [[
                'type' => 'error',
                'title' => 'Gagal',
                'message' => "Gagal mengimpor quiz: " . $e->getMessage(),
                'time' => now()->diffForHumans()
            ]]);
        }
    }


    public function deleteOption(Request $request, $optionId)
    {
        try {
            // Cari opsi berdasarkan ID
            $option = QuizChoice::find($optionId);

            // Jika opsi tidak ditemukan, kirim respons error
            if (!$option) {
                return response()->json([
                    'success' => false,
                    'message' => 'Opsi tidak ditemukan.'
                ], 404);
            }

            // Hapus opsi
            $option->delete();

            // Berhasil menghapus, kirim respons sukses
            return response()->json([
                'success' => true,
                'message' => 'Opsi berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            // Tangani error dan kirim respons error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
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
