<?php

namespace App\Http\Controllers;

use App\Exports\SummaryTestExport;
use App\Exports\TTestingExport;
use App\Helpers\StatisticalTestHelper;
use App\Models\Classes;
use App\Models\IndependentTTest;
use App\Models\Module;
use App\Models\PairedTTest;
use App\Models\QuestionChoice;
use App\Models\QuestionTest;
use App\Models\StudentModulSummary;
use App\Models\StudentTestAnswer;
use App\Models\TTesting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use MathPHP\Statistics\Average;
use MathPHP\Probability\Distribution\Continuous\F;
use MathPHP\Probability\Distribution\Table\TDistribution;
use MathPHP\Statistics\Descriptive;
use MathPHP\Statistics\Significance;
use ZipArchive;


class TestingQuesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mendapatkan data user yang sedang login
        $user = auth()->user();

        // Ambil data soal dan pilihan jawaban yang terkait
        $questions = QuestionTest::with('choices')->get();  // Ambil semua soal beserta pilihan jawabannya

        if ($user->role == 'teacher') {
            return view('teacher.testing.index', [
                'user' => $user,
                'questions' => $questions,
                'activeMenu' => 'questionTest'
            ]);
        } elseif ($user->role == 'admin') {
            return view('admin.testing.index', [
                'user' => $user,
                'questions' => $questions,
                'activeMenu' => 'questionTest'
            ]);
        }
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
            // Ubah key options jadi numerik dan ambil isi text-nya
            $options = array_values($request->input('options', []));

            // Gabungkan ulang ke dalam request untuk validasi
            $request->merge(['options' => $options]);

            // Validasi data
            $validated = $request->validate([
                'question' => 'required|string|max:255',
                'options' => 'required|array|min:2',
                'options.*.text' => 'required|string|max:255',
                'correct_choice' => 'required|integer|min:0',
            ]);

            // Pastikan correct_choice valid
            if (!array_key_exists($validated['correct_choice'], $validated['options'])) {
                return redirect()->back()->with('toasts', [[
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => "Pilihan jawaban yang benar tidak valid.",
                    'time' => now()->diffForHumans()
                ]]);
            }

            // Simpan pertanyaan
            $soal = QuestionTest::create([
                'question' => $validated['question'],
            ]);

            // Simpan pilihan jawaban
            foreach ($validated['options'] as $index => $choice) {
                QuestionChoice::create([
                    'question_test_id' => $soal->id,
                    'choice' => $choice['text'], // ambil text
                    'is_correct' => $index == $validated['correct_choice'],
                ]);
            }

            return redirect()->back()->with('toasts', [[
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => "Soal berhasil ditambahkan.",
                'time' => now()->diffForHumans()
            ]]);
        } catch (\Exception $e) {
            return redirect()->back()->with('toasts', [[
                'type' => 'error',
                'title' => 'Error',
                'message' => "Terjadi kesalahan: " . $e->getMessage(),
                'time' => now()->diffForHumans()
            ]]);
        }
    }

    public function saveGenerateQuestionFromLLM(Request $request)
    {
        set_time_limit(300); // 5 menit

        $request->validate([
            'rangkuman' => 'required|string|min:10'
        ]);

        $rangkumanText = trim($request->input('rangkuman'));

        // Susun prompt lengkap
        $prompt = <<<PROMPT
Kamu adalah guru profesional pemrograman Python untuk siswa Sekolah Menengah Atas (SMA). Buatkan soal pretest dan posttest dalam bentuk soal pilihan ganda berdasarkan rangkuman materi berikut ini:

$rangkumanText

### Kriteria Soal:
- Buat **25 soal pilihan ganda**, masing-masing mewakili 5 soal untuk setiap level **Taksonomi Bloom** (Remember, Understand, Apply, Analyze, Evaluate).

### Assessment Terms per Level (wajib digunakan untuk membuat soal):
**Remember**:
  - Mengidentifikasi, Mengenali implementasi, Mengingat konsep materi dan bagian kode yang dipelajari
**Understand**:
  - Memahami, Menerjemahkan, dan Menjelaskan konsep algoritma tertentu
**Apply**:
  - Mengimplementasikan konsep yang dipelajari dan Menyelesaikan studi kasus sederhana
**Analyze**:
  - Memecah tugas program menjadi beberapa komponen
  - Mengidentifikasi komponen penting dan yang tidak penting
**Evaluate**:
  - Menentukan apakah sebuah kode dapat menyelesaikan studi kasus tertentu
  - Menilai kualitas dan standar kode dengan benar

**Format setiap soal**:
  - `question`: pertanyaan dalam Bahasa Indonesia
  - `reference_code`: contoh code python (opsional)
  - `choices`: 4 pilihan jawaban (1 benar, 3 salah)
    - Setiap pilihan memiliki:
      - `answer`: isi jawaban
      - `is_correct`: true/false

- Semua soal harus relevan dan sesuai dengan level Bloom dan rangkuman.

### Format Output:
Berikan jawaban **langsung dalam format JSON array**, **tanpa teks narasi**, dan **dimulai dengan tanda kurung siku** `[`.
PROMPT;

        try {
            $response = Http::timeout(300)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('https://iswara-code-evaluation-system.onrender.com/askLlama', [
                    'prompt' => $prompt
                ]);

            if (!$response->successful()) {
                return redirect()->back()->with('toasts', [[
                    'type' => 'error',
                    'title' => 'Gagal',
                    'message' => 'Gagal memanggil API.',
                    'time' => now()->diffForHumans(),
                    'duration' => 8000
                ]]);
            }

            $result = $response->json();

            if (!is_array($result)) {
                return redirect()->back()->with('toasts', [[
                    'type' => 'error',
                    'title' => 'Gagal',
                    'message' => 'Format hasil tidak valid.',
                    'time' => now()->diffForHumans(),
                    'duration' => 8000
                ]]);
            }
            StudentTestAnswer::query()->delete();
            QuestionChoice::query()->delete();
            QuestionTest::query()->delete();

            DB::statement('ALTER TABLE student_test_answers AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE question_choices AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE question_tests AUTO_INCREMENT = 1');

            $questionsData = $result['data'] ?? [];

            foreach ($questionsData as $q) {
                if (!isset($q['question'], $q['choices']) || !is_array($q['choices'])) continue;

                $questionText = $q['question'];

                if (!empty($q['reference_code'])) {
                    $referenceCode = htmlentities($q['reference_code'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $questionText .= '<br><div class="referensi_code"><pre>' . $referenceCode . '</pre></div>';
                }

                $question = QuestionTest::create(['question' => $questionText]);

                foreach ($q['choices'] as $choice) {
                    if (!isset($choice['answer'], $choice['is_correct'])) continue;

                    QuestionChoice::create([
                        'question_test_id' => $question->id,
                        'choice' => $choice['answer'],
                        'is_correct' => (bool) $choice['is_correct'],
                    ]);
                }
            }

            return redirect()->back()->with('toasts', [[
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Soal berhasil digenerate dan disimpan.',
                'time' => now()->diffForHumans(),
                'duration' => 8000
            ]]);
        } catch (\Exception $e) {
            return redirect()->back()->with('toasts', [[
                'type' => 'error',
                'title' => 'Exception',
                'message' => $e->getMessage(),
                'time' => now()->diffForHumans(),
                'duration' => 8000
            ]]);
        }
    }

    public function saveQuestionFromJson(Request $request)
    {
        try {
            $questionsJson = $request->input('questions');

            // Decode string JSON ke array
            $decoded = json_decode($questionsJson, true); // true => as associative array

            if (!is_array($decoded)) {
                return redirect()->back()->with('toasts', [[
                    'type' => 'error',
                    'title' => 'Format JSON Tidak Valid',
                    'message' => 'Pastikan format JSON soal valid dan merupakan array.',
                    'time' => now()->diffForHumans(),
                    'duration' => 10000
                ]]);
            }

            // Replace input untuk validasi & simpan
            $request->merge(['questions' => $decoded]);

            // Validasi
            $request->validate([
                'questions' => 'required|array|min:1',
                'questions.*.question' => 'required|string',
                'questions.*.choices' => 'required|array|min:4',
                'questions.*.choices.*.answer' => 'required|string',
                'questions.*.choices.*.is_correct' => 'required|boolean',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->with('toasts', [[
                'type' => 'error',
                'title' => 'Validasi Gagal',
                'message' => $e->getMessage(), // atau gunakan json_encode($e->errors())
                'time' => now()->diffForHumans(),
                'duration' => 10000
            ]]);
        }

        try {
            StudentTestAnswer::query()->delete();
            QuestionChoice::query()->delete();
            QuestionTest::query()->delete();

            DB::statement('ALTER TABLE student_test_answers AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE question_choices AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE question_tests AUTO_INCREMENT = 1');

            $questionsData = $request->input('questions');

            foreach ($questionsData as $q) {
                $questionText = $q['question'];

                if (!empty($q['reference_code'])) {
                    $referenceCode = htmlentities($q['reference_code'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $questionText .= '<br><div class="referensi_code"><pre>' . $referenceCode . '</pre></div>';
                }

                $question = QuestionTest::create(['question' => $questionText]);

                foreach ($q['choices'] as $choice) {
                    QuestionChoice::create([
                        'question_test_id' => $question->id,
                        'choice' => $choice['answer'],
                        'is_correct' => (bool) $choice['is_correct'],
                    ]);
                }
            }

            return redirect()->back()->with('toasts', [[
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Soal dari JSON berhasil disimpan.',
                'time' => now()->diffForHumans(),
                'duration' => 8000
            ]]);
        } catch (\Exception $e) {
            return redirect()->back()->with('toasts', [[
                'type' => 'error',
                'title' => 'Gagal',
                'message' => $e->getMessage(),
                'time' => now()->diffForHumans(),
                'duration' => 8000
            ]]);
        }
    }

    public function testingEnvironment()
    {
        $user = auth()->user();

        // Ambil semua kombinasi class & module yang sudah ikut T-Testing
        $testingCombinations = TTesting::select('class_id', 'module_id')
            ->distinct()
            ->get();

        $classes = Classes::with('modules')
            ->whereHas('students', function ($q) {
                $q->whereHas('tTests');
            })->get();

        // Ambil class yang belum di-assign
        $assignedStudentIds = TTesting::pluck('student_id');

        $allClass = Classes::whereHas('students', function ($q) use ($assignedStudentIds) {
            $q->whereNotIn('students.id', $assignedStudentIds);
        })->with('modules')->get();

        if ($user->role == 'teacher') {
            return view('teacher.testing.environmentTesting', [
                'user' => $user,
                'classes' => $classes,
                'allClasses' => $allClass,
                'testingCombinations' => $testingCombinations,
                'activeMenu' => 'testingEnvirontment',
            ]);
        } elseif ($user->role == 'admin') {
            return view('admin.testing.environmentTesting', [
                'user' => $user,
                'classes' => $classes,
                'allClasses' => $allClass,
                'testingCombinations' => $testingCombinations,
                'activeMenu' => 'testingEnvirontment',
            ]);
        }
    }


    public function assignClass(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'module_id' => 'required|exists:modules,id'
        ]);

        try {
            $class = Classes::with('students')->findOrFail($request->class_id);
            $moduleId = $request->module_id;

            foreach ($class->students as $student) {
                TTesting::firstOrCreate([
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'module_id' => $moduleId
                ], [
                    'can_do_pretest' => false,
                    'pre_test_score' => null,
                    'can_do_posttest' => false,
                    'post_test_score' => null,
                    'class_type' => null
                ]);
            }

            return redirect()->back()->with('toasts', [[
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => "Semua siswa di kelas \"{$class->name}\" berhasil di-assign untuk modul yang dipilih.",
                'time' => now()->diffForHumans()
            ]]);
        } catch (\Exception $e) {
            return redirect()->back()->with('toasts', [[
                'type' => 'error',
                'title' => 'Error',
                'message' => "Terjadi kesalahan: " . $e->getMessage(),
                'time' => now()->diffForHumans()
            ]]);
        }
    }

    public function getClassTestingSummary($classId, $moduleId)
    {
        $class = Classes::with(['students.user', 'modules.contents'])->findOrFail($classId);
        $students = $class->students;
        $totalStudent = $students->count();

        $tTestings = TTesting::where('class_id', $classId)
            ->where('module_id', $moduleId)
            ->get()
            ->keyBy('student_id');

        $experimentStudent = $tTestings->where('class_type', 'experiment')->count();
        $controlStudent = $tTestings->where('class_type', 'control')->count();
        $pretest_count = $tTestings->whereNotNull('pre_test_score')->count();
        $posttest_count = $tTestings->whereNotNull('post_test_score')->count();
        $pretest_start = $tTestings->where('can_do_pretest', true)->count();
        $posttest_start = $tTestings->where('can_do_posttest', true)->count();

        // --- PRETEST MESSAGE ---
        if ($pretest_start == 0 && $pretest_count == 0) {
            $pretest_message = "doesn't start yet";
        } elseif ($pretest_count == $totalStudent) {
            $pretest_message = 'All student have done pretest';
        } elseif ($pretest_count > 0 && $pretest_count < $totalStudent / 2) {
            $pretest_message = "$pretest_count student have done pretest";
        } elseif ($pretest_count > $totalStudent / 2) {
            $pretest_message = 'Over than 50% student have done pretest';
        } else {
            $pretest_message = 'all student have not done pretest';
        }

        // --- POSTTEST MESSAGE ---
        if ($posttest_start == 0 && $posttest_count == 0) {
            $posttest_message = "doesn't start yet";
        } elseif ($posttest_count == $totalStudent) {
            $posttest_message = 'All student have done posttest';
        } elseif ($posttest_count > 0 && $posttest_count < $totalStudent / 2) {
            $posttest_message = "$posttest_count student have done posttest";
        } elseif ($posttest_count > $totalStudent / 2) {
            $posttest_message = 'Over than 50% student have done posttest';
        } else {
            $posttest_message = 'all student have not done posttest';
        }

        $module = $class->modules->firstWhere('id', $moduleId);
        $totalContent = $module?->contents->count() ?? 0;
        $contentIds = $module?->contents->pluck('id') ?? collect();

        $summary = [
            'pretest_done' => $pretest_count,
            'posttest_done' => $posttest_count,
            'experiment_count' => $experimentStudent,
            'control_count' => $controlStudent,
            'pretest_message' => $pretest_message,
            'posttest_message' => $posttest_message,
            'total_student' => $totalStudent,
            'class_name' => $class->name,
            'module_name' => $module->title,
            'students' => [],
        ];

        foreach ($students as $index => $student) {
            $testing = $tTestings->get($student->id);

            if ($testing) {


                $progressData = StudentModulSummary::where('student_id', $student->id)
                    ->whereIn('content_id', $contentIds)
                    ->where('status', 'Lulus')
                    ->groupBy('content_id')
                    ->selectRaw('content_id, count(*) as total_lulus')
                    ->get()
                    ->pluck('total_lulus', 'content_id')
                    ->toArray();

                $progressCount = count($progressData);

                $summary['students'][] = [
                    'no' => $index + 1,
                    'id' => $student->id,
                    'name' => $student->user->name,
                    'nim' => $student->NIS,
                    'class_type' => $testing->class_type ?? '-',
                    'pre_test' => $testing->pre_test_score ?? '-',
                    'post_test' => $testing->post_test_score ?? '-',
                    'progress' => "$progressCount/$totalContent",
                    'progress_data' => $progressData
                ];
            }
        }

        return response()->json($summary);
    }



    public function startTest(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'module_id' => 'required|exists:modules,id',
            'type' => 'required|in:pretest,posttest',
        ]);


        try {
            $students = Classes::findOrFail($request->class_id)->students;

            foreach ($students as $student) {
                $testing = TTesting::where([
                    'student_id' => $student->id,
                    'class_id' => $request->class_id,
                    'module_id' => $request->module_id,
                ])->first();

                if ($testing) {
                    if ($request->type == 'pretest') {
                        $testing->can_do_pretest = true;
                    } else if ($request->type == 'posttest') {
                        $testing->can_do_posttest = true;
                    }
                    $testing->save();
                }
            }

            return back()->with('toasts', [[
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => ucfirst($request->type) . ' berhasil diaktifkan untuk siswa.',
                'time' => now()->diffForHumans()
            ]]);
        } catch (\Exception $e) {
            return back()->with('toasts', [[
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'time' => now()->diffForHumans()
            ]]);
        }
    }

    public function resetTest(Request $request)
    {

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'module_id' => 'required|exists:modules,id',
            'type' => 'required|in:pretest,posttest',
        ]);

        try {
            $students = Classes::findOrFail($request->class_id)->students;

            foreach ($students as $student) {
                $testing = TTesting::where([
                    'student_id' => $student->id,
                    'class_id' => $request->class_id,
                    'module_id' => $request->module_id,
                ])->first();

                if ($testing) {
                    if ($request->type == 'pretest') {
                        $testing->pre_test_score = null;
                        $testing->can_do_pretest = false;
                    } else if ($request->type == 'posttest') {
                        $testing->post_test_score = null;
                        $testing->can_do_posttest = false;
                    }
                    $testing->save();
                }
            }

            return back()->with('toasts', [[
                'type' => 'info',
                'title' => 'Di-reset',
                'message' => ucfirst($request->type) . ' berhasil di-reset untuk siswa.',
                'time' => now()->diffForHumans()
            ]]);
        } catch (\Exception $e) {
            return back()->with('toasts', [[
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'time' => now()->diffForHumans()
            ]]);
        }
    }

    public function divideIndependentSampling(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'module_id' => 'required|exists:modules,id',
        ]);

        // Ambil semua data testing siswa untuk class dan module ini
        $ttesting = TTesting::where([
            'class_id' => $request->class_id,
            'module_id' => $request->module_id,
        ])->whereNotNull('pre_test_score')->get();

        // Hitung mean pre-test
        $mean = $ttesting->avg('pre_test_score');

        // Kelompokkan siswa berdasarkan strata
        $aboveOrEqual = $ttesting->filter(fn($s) => $s->pre_test_score >= $mean)->shuffle()->values();
        $below = $ttesting->filter(fn($s) => $s->pre_test_score < $mean)->shuffle()->values();

        // Fungsi pembagi 1:1 untuk setiap strata
        $assignClassType = function ($collection) {
            foreach ($collection as $index => $testing) {
                $testing->class_type = $index % 2 === 0 ? 'experiment' : 'control';
                $testing->save();
            }
        };

        // Lakukan pembagian
        $assignClassType($aboveOrEqual);
        $assignClassType($below);

        return redirect()->back()->with('toasts', [[
            'type' => 'success',
            'title' => 'Pembagian Kelas Sukses',
            'message' => 'Siswa telah dibagi secara acak dengan stratifikasi berdasarkan nilai pre-test.',
            'time' => now()->diffForHumans()
        ]]);
    }

    public function levenesPretest($classId, $moduleId)
    {
        $students = TTesting::with('student.user')
            ->where('class_id', $classId)
            ->where('module_id', $moduleId)
            ->whereNotNull('pre_test_score')
            ->whereNotNull('class_type')
            ->get();

        $grouped = $students->groupBy('class_type');
        $experiment = $grouped['experiment'] ?? collect();
        $control = $grouped['control'] ?? collect();

        $expScores = $experiment->pluck('pre_test_score')->toArray();
        $ctrlScores = $control->pluck('pre_test_score')->toArray();

        // 1. Rata-rata
        $expMean = array_sum($expScores) / count($expScores);
        $ctrlMean = array_sum($ctrlScores) / count($ctrlScores);

        // 2. Deviasi absolut dari mean masing-masing
        $expDev = array_map(fn($x) => abs($x - $expMean), $expScores);
        $ctrlDev = array_map(fn($x) => abs($x - $ctrlMean), $ctrlScores);

        // 3. Gabungkan data deviasi dan hitung mean total
        $all = array_merge(
            array_map(fn($x) => ['group' => 'experiment', 'dev' => $x], $expDev),
            array_map(fn($x) => ['group' => 'control', 'dev' => $x], $ctrlDev)
        );

        $overallMean = array_sum(array_column($all, 'dev')) / count($all);

        // 4. Hitung SSB dan SSW
        $groupMeans = [
            'experiment' => array_sum($expDev) / count($expDev),
            'control' => array_sum($ctrlDev) / count($ctrlDev),
        ];

        $ssBetween = 0;
        foreach ($groupMeans as $group => $mean) {
            $n = count(array_filter($all, fn($row) => $row['group'] === $group));
            $ssBetween += $n * pow($mean - $overallMean, 2);
        }

        $ssWithin = 0;
        foreach ($all as $row) {
            $ssWithin += pow($row['dev'] - $groupMeans[$row['group']], 2);
        }

        $dfBetween = 1;
        $dfWithin = count($all) - 2;

        $msBetween = $ssBetween / $dfBetween;
        $msWithin = $dfWithin > 0 ? $ssWithin / $dfWithin : 0;

        $leveneStat = $msWithin > 0 ? $msBetween / $msWithin : 0;

        $pValue = null;
        if ($dfWithin > 0) {
            $fDist = new F($dfBetween, $dfWithin);
            $pValue = 1 - $fDist->cdf($leveneStat);
        }
        return response()->json([
            'experiment' => $experiment->map(fn($s) => [
                'name' => $s->student->user->name,
                'score' => $s->pre_test_score
            ])->values(),
            'control' => $control->map(fn($s) => [
                'name' => $s->student->user->name,
                'score' => $s->pre_test_score
            ])->values(),
            'levene_statistic' => round($leveneStat, 3),
            'p_value' => $pValue !== null ? round($pValue, 4) : 'n/a',
            'interpretation' => $leveneStat < 4
                ? 'Varian kedua kelompok relatif homogen'
                : 'Varian berbeda signifikan',
        ]);
    }

    public function runPairedTTest(Request $request)
    {
        try {
            $classId = $request->class_id;
            $moduleId = $request->module_id;

            foreach (['experiment', 'control'] as $classType) {
                // Ambil data berdasarkan tipe kelas
                $data = TTesting::where('class_id', $classId)
                    ->where('module_id', $moduleId)
                    ->where('class_type', $classType)
                    ->whereNotNull('pre_test_score')
                    ->whereNotNull('post_test_score')
                    ->get(['pre_test_score', 'post_test_score']);

                if ($data->count() < 2) {
                    continue; // Lewati jika data tidak cukup
                }

                $diffs = $data->map(fn($item) => $item->post_test_score - $item->pre_test_score)->toArray();
                $tResult = Significance::tTest($diffs, 0);

                // Konversi p-value ke notasi ilmiah agar presisi
                $p1 = sprintf('%e', $tResult['p1']);
                $p2 = sprintf('%e', $tResult['p2']);

                PairedTTest::updateOrCreate(
                    ['class_id' => $classId, 'module_id' => $moduleId, 'class_type' => $classType],
                    [
                        'mean_difference'    => Average::mean($diffs),
                        't_statistic'        => $tResult['t'],
                        'degrees_freedom'    => $tResult['df'],
                        'p_value_one_tailed' => $p1,
                        'p_value_two_tailed' => $p2,
                        'n'                  => count($diffs),
                        'interpretation'     => $tResult['p2'] < 0.05
                            ? 'Terdapat perbedaan signifikan antara pre-test dan post-test'
                            : 'Tidak terdapat perbedaan signifikan',
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Hasil Paired T-Test berhasil disimpan untuk kelas kontrol dan eksperimen.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menjalankan Paired T-Test: ' . $e->getMessage(),
            ], 500);
        }
    }



    public function getPairedTestResult($classId, $moduleId)
    {
        $results = PairedTTest::where('class_id', $classId)
            ->where('module_id', $moduleId)
            ->get()
            ->keyBy('class_type');

        $className = Classes::find($classId)?->name;
        $moduleName = Module::find($moduleId)?->title;

        if ($results->isEmpty()) {
            return response()->json([
                'exists' => false,
                'class_name' => $className,
                'module_name' => $moduleName,
                'run_url' => route('testing.pairedTest.run', ['classId' => $classId, 'moduleId' => $moduleId]),
            ]);
        }

        return response()->json([
            'exists' => true,
            'class_name' => $className,
            'module_name' => $moduleName,
            'experiment' => $results['experiment'] ?? null,
            'control'    => $results['control'] ?? null,
            'run_url'    => route('testing.pairedTest.run'),
        ]);
    }


    public function runIndependentTTest(Request $request)
    {
        try {
            $classId = $request->class_id;
            $moduleId = $request->module_id;

            $experiment = TTesting::where('class_id', $classId)
                ->where('module_id', $moduleId)
                ->where('class_type', 'experiment')
                ->whereNotNull('post_test_score')
                ->pluck('post_test_score')
                ->toArray();

            $control = TTesting::where('class_id', $classId)
                ->where('module_id', $moduleId)
                ->where('class_type', 'control')
                ->whereNotNull('post_test_score')
                ->pluck('post_test_score')
                ->toArray();

            if (count($experiment) < 2 || count($control) < 2) {
                throw new \Exception("Minimal 2 data dari masing-masing kelompok (eksperimen dan kontrol) diperlukan.");
            }

            $result = StatisticalTestHelper::independentTTestManual($experiment, $control);

            // Simpan hasil ke DB
            IndependentTTest::updateOrCreate(
                ['class_id' => $classId, 'module_id' => $moduleId],
                [
                    't_statistic'     => $result['t_statistic'],
                    'p_value'         => $result['p_value'],
                    'is_significant'  => $result['is_significant'],
                    'interpretation'  => $result['interpretation'],
                    'group_statistics' => json_encode($result['group_statistics']),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Hasil Independent T-Test berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menjalankan Independent T-Test: ' . $e->getMessage(),
            ], 500);
        }
    }




    public function getIndependentTestResult($classId, $moduleId)
    {
        $result = IndependentTTest::where('class_id', $classId)
            ->where('module_id', $moduleId)
            ->first();

        $className = Classes::find($classId)?->name;
        $moduleName = Module::find($moduleId)?->title;

        if (!$result) {
            return response()->json([
                'exists' => false,
                'class_name' => $className,
                'module_name' => $moduleName,
                'run_url' => route('testing.independentTest.run', ['classId' => $classId, 'moduleId' => $moduleId]),
            ]);
        }

        $stats = json_decode($result->group_statistics, true);

        return response()->json([
            'exists' => true,
            'class_name'       => $className,
            'module_name'      => $moduleName,
            't_statistic'      => $result->t_statistic,
            'p_value'          => $result->p_value,
            'is_significant'   => $result->is_significant,
            'interpretation'   => $result->interpretation,
            'experiment'       => $stats['experiment'] ?? null,
            'control'          => $stats['control'] ?? null,
            'run_url'          => route('testing.independentTest.run'),
        ]);
    }

    public function moveStudentClass(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'target_type' => 'required|in:experiment,control'
        ]);

        try {
            foreach ($request->student_ids as $id) {
                $student = TTesting::where('student_id', $id)->first(); // atau firstOrFail()

                if ($student) {
                    $student->class_type = $request->target_type;
                    $student->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil dipindahkan ke kelas ' . ucfirst($request->target_type) . '.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memindahkan siswa: ' . $e->getMessage()
            ], 500);
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
            $options = $request->input('options', []);

            $validated = $request->validate([
                'question' => 'required|string|max:255',
                'options' => 'required|array|min:2',
                'options.*.text' => 'required|string|max:255',
                'correct_choice' => 'required|string', // karena ini ID dari opsi
            ]);

            if (!array_key_exists($validated['correct_choice'], $options)) {
                return redirect()->back()->with('toasts', [[
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => "Pilihan jawaban yang benar tidak valid.",
                    'time' => now()->diffForHumans()
                ]]);
            }

            $soal = QuestionTest::findOrFail($id);
            $soal->update([
                'question' => $validated['question'],
            ]);

            $soal->choices()->delete();

            foreach ($options as $choiceId => $choice) {
                QuestionChoice::create([
                    'question_test_id' => $soal->id,
                    'choice' => $choice['text'],
                    'is_correct' => $choiceId == $validated['correct_choice'],
                ]);
            }

            return redirect()->back()->with('toasts', [[
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => "Soal berhasil diperbarui.",
                'time' => now()->diffForHumans()
            ]]);
        } catch (\Exception $e) {
            return redirect()->back()->with('toasts', [[
                'type' => 'error',
                'title' => 'Error',
                'message' => "Terjadi kesalahan: " . $e->getMessage(),
                'time' => now()->diffForHumans()
            ]]);
        }
    }

    public function exportSummary($classId, $moduleId)
    {
        $data = TTesting::with('student')
            ->where('class_id', $classId)
            ->where('module_id', $moduleId)
            ->get();

        return Excel::download(new TTestingExport($data), 'summary_kelas_' . now()->format('Ymd_His') . '.xlsx');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $question = QuestionTest::findOrFail($id);

            // Hapus pilihan jawaban terkait terlebih dahulu (jika foreign key tidak cascade)
            $question->choices()->delete();

            // Hapus soalnya
            $question->delete();

            return redirect()->back()->with('toasts', [[
                'type' => 'success',
                'title' => 'Berhasil',
                'message' => 'Soal berhasil dihapus.',
                'time' => now()->diffForHumans()
            ]]);
        } catch (\Exception $e) {
            return redirect()->back()->with('toasts', [[
                'type' => 'error',
                'title' => 'Gagal',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'time' => now()->diffForHumans()
            ]]);
        }
    }
}
