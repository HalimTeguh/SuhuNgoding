<?php

namespace App\Http\Controllers;

use App\Models\ModuleContent;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class ModuleContentController extends Controller
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
        //
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
            DB::beginTransaction();

            $request->validate([
                'chapterName' => 'required|string|max:255',
                'moduleSummary' => 'nullable|string',
                'moduleContent' => 'nullable|string',
                'source_uuid' => 'nullable|string',
                'source_type' => 'nullable|string|in:pdf,docx',
                'generate_quiz' => 'nullable|boolean',
            ]);

            $shouldGenerateQuiz = $request->boolean('generate_quiz', false);
            $content = ModuleContent::findOrFail($id);

            $htmlContent = $request->moduleContent;
            $sourceUuid = $request->source_uuid;
            $moduleFolder = storage_path("app/public/uploads/moduleContent/$sourceUuid");

            // Buat folder tujuan jika belum ada
            File::ensureDirectoryExists($moduleFolder);
            File::ensureDirectoryExists(storage_path('app/public/uploads/temp'));

            // Ambil semua src gambar yang digunakan
            preg_match_all('/<img[^>]+src="([^">]+)"/i', $htmlContent, $matches);
            $usedImages = collect($matches[1])->map(function ($url) {
                return basename(parse_url($url, PHP_URL_PATH));
            });

            $finalImagePaths = [];

            foreach ($usedImages as $filename) {
                $fromTemp = storage_path("app/public/uploads/temp/$filename");
                $fromOutput = storage_path("app/convert2md/output/$sourceUuid/$filename");
                $targetPath = "$moduleFolder/$filename";

                if (file_exists($fromTemp)) {
                    File::move($fromTemp, $targetPath);
                } elseif (file_exists($fromOutput)) {
                    File::copy($fromOutput, $targetPath);
                }

                $htmlContent = preg_replace_callback(
                    "#(<img[^>]+src=[\"'])[^\"']*({$filename})#",
                    fn($match) => $match[1] . "/storage/uploads/moduleContent/{$sourceUuid}/{$match[2]}",
                    $htmlContent
                );

                $finalImagePaths[] = "/storage/uploads/moduleContent/$sourceUuid/$filename";
            }


            // Pindahkan input file PDF jika ada
            $inputPdf = storage_path("app/convert2md/input/$sourceUuid.pdf");
            if (file_exists($inputPdf)) {
                File::copy($inputPdf, "$moduleFolder/$sourceUuid.pdf");
            }

            // Pindahkan output file .md dan meta.json jika ada
            $mdFile = storage_path("app/convert2md/output/$sourceUuid/$sourceUuid.md");
            $metaFile = storage_path("app/convert2md/output/$sourceUuid/{$sourceUuid}_meta.json");
            if (file_exists($mdFile)) {
                File::copy($mdFile, "$moduleFolder/$sourceUuid.md");
            }
            if (file_exists($metaFile)) {
                File::copy($metaFile, "$moduleFolder/{$sourceUuid}_meta.json");
            }

            // Bersihkan gambar orphan dari temp
            $allTempFiles = File::files(storage_path('app/public/uploads/temp'));
            foreach ($allTempFiles as $file) {
                if (!$usedImages->contains($file->getFilename())) {
                    File::delete($file->getPathname());
                }
            }

            // Simpan data ke DB
            $content->update([
                'title' => $request->chapterName,
                'summary' => $request->moduleSummary,
                'content' => $htmlContent,
                'source_uuid' => $sourceUuid,
                'source_type' => $request->source_type,
                'media_files' => $finalImagePaths,
            ]);


            if ($shouldGenerateQuiz) {
                $aiController = new AiController();
                $fakeRequest = new \Illuminate\Http\Request(['materi' => $htmlContent]);
            
                $response = $aiController->generateSoalFromLLM($fakeRequest)->getData(true);
            
                if (!($response['success'] ?? false)) {
                    throw new \Exception("Gagal generate soal dari AI: " . ($response['message'] ?? 'Tidak diketahui'));
                }
            
                $quizDataArray = $response['data'] ?? [];
            
                if (!is_array($quizDataArray)) {
                    throw new \Exception("Format data soal dari AI tidak valid.");
                }
            
                // Hapus quiz lama
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
            
                foreach ($quizDataArray as $quizData) {
                    $levelId = $quizData['level'] ?? 'Mengingat';
                    $engLevel = $levelMap[$levelId] ?? 'remember';
                    $point = $levelPoints[$levelId] ?? 1;
            
                    $quiz = Quiz::create([
                        'content_id' => $content->id,
                        'question' => $quizData['question'] ?? '-',
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
                                'feedback' => $choice['feedback'] ?? '',
                            ]);
                        }
                    }
                }
            }
            


            DB::commit();

            return redirect()->back()->with('toasts', [[
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => "Materi {$content->title} berhasil diperbarui",
                'time' => now()->diffForHumans()
            ]]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['general' => $e->getMessage()])
                ->withInput()
                ->with('toasts', [[
                    'type' => 'danger',
                    'title' => 'Gagal',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                    'time' => now()->diffForHumans()
                ]])
                ->with('form_error', 'update');
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
