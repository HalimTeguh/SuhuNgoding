<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\ControlModuleContent;
use App\Models\Leaderboard;
use App\Models\Module;
use App\Models\ModuleContent;
use App\Models\Quiz;
use App\Models\QuizChoice;
use App\Models\QuizCode;
use App\Models\StudentAnswers;
use App\Models\StudentModulSummary;
use App\Models\TTesting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StudentClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Pastikan relasi user -> student sudah tersedia
        $student = $user->student;

        // Jika student tidak ditemukan (misal user bukan student), kembalikan error atau redirect
        if (!$student) {
            abort(403, 'Student data not found for this user.');
        }

        // Ambil semua kelas yang terhubung dengan student, dan belum di-soft-delete
        $classes = $student->classes()->whereNull('classes.deleted_at')->get();

        return view('student.class.index', [
            'user' => $user,
            'student' => $student,
            'classes' => $classes,
            'activeMenu' => 'class'
        ]);
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
        $user = auth()->user();

        // Pastikan relasi user -> student sudah tersedia
        $student = $user->student;

        // Jika student tidak ditemukan (misal user bukan student), kembalikan error atau redirect
        if (!$student) {
            abort(403, 'Student data not found for this user.');
        }

        // Ambil semua kelas yang terhubung dengan student, dan belum di-soft-delete
        $classes = $student->classes()->whereNull('classes.deleted_at')->get();

        return view('student.class.index', [
            'user' => $user,
            'student' => $student,
            'classes' => $classes,
            'activeMenu' => 'class'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $user = auth()->user();
        $student = $user->student;

        $class = Classes::with(['modules.contents', 'students', 'teacher.user'])->findOrFail($id);

        // Cek apakah siswa masuk ke dalam TTesting untuk kelas ini
        $testingData = TTesting::where('class_id', $class->id)
            ->where('student_id', $student->id)
            ->first();

        $classType = $testingData->class_type ?? 'experiment'; // default ke experiment jika tidak ada data

        // Data umum
        $studentCount = $class->students->count();
        $moduleCount = $class->modules->count();
        $teacherName = $class->teacher->user->name ?? '-';

        if ($classType === 'control') {
            // Untuk kelas kontrol: hanya tampilkan ControlModuleContent
            foreach ($class->modules as $module) {
                foreach ($module->contents as $content) {
                    // Ambil data dari control
                    $controlContent = ControlModuleContent::where('module_content_id', $content->id)->first();
                    if ($controlContent) {
                        $content->control_data = [
                            'material_link' => $controlContent->material_link,
                            'test_link' => $controlContent->test_link,
                            'notes' => $controlContent->notes,
                        ];
                    }

                    // Ambil progres siswa
                    $summary = StudentModulSummary::where('student_id', $student->id)
                        ->where('content_id', $content->id)
                        ->latest()
                        ->first();

                    $content->progress_status = $summary
                        ? (
                            !is_null($summary->total_score)
                            ? ($summary->status === 'Lulus'
                                ? "Lulus ({$summary->total_score}%)"
                                : "Tidak Lulus ({$summary->total_score}%)")
                            : ($summary->study_content_total_duration > 0
                                ? "Sedang Belajar"
                                : "Belum")
                        )
                        : "Belum";
                }
            }

            return view('student.class.detailClass', [
                'user' => $user,
                'class' => $class,
                'modules' => $class->modules,
                'studentCount' => $studentCount,
                'moduleCount' => $moduleCount,
                'teacherName' => $teacherName,
                'classType' => $classType,
                'activeMenu' => 'class',
            ]);
        }

        // Untuk kelas eksperimen
        $leaderboards = [];
        foreach ($class->modules as $module) {
            $leaderboards[$module->id] = Leaderboard::with('student.user')
                ->where('class_id', $class->id)
                ->where('module_id', $module->id)
                ->orderByDesc('point')
                ->get();

            // Hitung Bloom levels juga nanti
        }

        foreach ($class->modules as $module) {
            foreach ($module->contents as $content) {
                $summary = StudentModulSummary::where('student_id', $student->id)
                    ->where('content_id', $content->id)
                    ->latest()
                    ->first();

                $content->progress_status = $summary
                    ? (
                        !is_null($summary->total_score)
                        ? ($summary->status === 'Lulus'
                            ? "Lulus ({$summary->total_score}%)"
                            : "Tidak Lulus ({$summary->total_score}%)")
                        : ($summary->study_content_total_duration > 0
                            ? "Sedang Belajar"
                            : "Belum")
                    )
                    : "Belum";
            }
        }

        // Bloom level untuk eksperimen
        $bloomLevels = [];
        foreach ($class->modules as $module) {
            $bloomLevels[$module->id] = $this->calculateBloomLevels($student->id, $module->id);
        }

        return view('student.class.detailClass', [
            'user' => $user,
            'class' => $class,
            'modules' => $class->modules,
            'leaderboards' => $leaderboards,
            'bloomLevels' => $bloomLevels,
            'studentCount' => $studentCount,
            'moduleCount' => $moduleCount,
            'teacherName' => $teacherName,
            'classType' => $classType,
            'activeMenu' => 'class',
        ]);
    }




    public function showContent(string $classId, string $moduleId)
    {
        $user = auth()->user();

        // Ambil data kelas berdasarkan classId
        $class = Classes::findOrFail($classId);

        // Ambil data siswa yang sedang login
        $student = auth()->user()->student;

        // Ambil modul berdasarkan moduleId
        $moduleContent = ModuleContent::findOrFail($moduleId);

        // Ambil daftar konten lainnya yang terkait dengan modul
        $listContent = ModuleContent::where('module_id', $moduleContent->module_id)->get();

        // Kembalikan view dengan data yang diperlukan
        return view('student.class.moduleContent', [
            'user' => $user,
            'student' => $student,
            'listContent' => $listContent,
            'moduleContent' => $moduleContent,
            'class' => $class,  // Kirimkan data kelas ke view
            'activeMenu' => 'class'
        ]);
    }

    public function showQuizContent(string $classId, string $moduleId)
    {
        $user = auth()->user();

        $class = Classes::findOrFail($classId);

        $student = auth()->user()->student;

        $moduleContent = ModuleContent::findOrFail($moduleId);

        // Ambil semua quiz dengan relasi choices dan code
        $quizzes = Quiz::with(['choices', 'code'])
            ->where('content_id', $moduleId)
            ->get();

        return view('student.class.moduleQuiz', [
            'user' => $user,
            'class' => $class,
            'moduleContent' => $moduleContent,
            'quizList' => $quizzes,
            'activeMenu' => 'class'
        ]);
    }

    public function saveQuizStudent(Request $request, $classId, $moduleId)
    {
        $student = Auth::user()->student;
        $quizAnswers = $request->input('quiz', []);
        $codeAnswers = $request->input('student_code', []);
        $duration = $request->input('duration', '0:0');
        $submittedAt = Carbon::now('Asia/Jakarta');
        $studentAnswers = collect();
        $totalPoints = 0;


        // Cek apakah ini final test (hanya 1 soal dan level create)
        $allQuizzes = Quiz::where('content_id', $moduleId)->get();
        $isFinalTest = $allQuizzes->count() === 1 && $allQuizzes->first()->bloom_level === 'create';
        $finalTestScore = 0;

        // Multiple Choice
        foreach ($quizAnswers as $quizId => $choiceId) {
            $choice = QuizChoice::find($choiceId);
            if (!$choice) continue;

            $quiz = Quiz::find($quizId);
            $quizPoint = $quiz ? $quiz->point : 0;

            $isCorrect = $choice->is_correct;

            if (!$isFinalTest && $isCorrect) {
                $totalPoints += $quizPoint;
            }

            $answer = StudentAnswers::create([
                'student_id' => $student->id,
                'quiz_id' => $quizId,
                'choice_id' => $choiceId,
                'submitted_at' => $submittedAt,
                'answer_text' => $choice->choice_text,
                'is_correct' => $isCorrect,
                'feedback' => $choice->feedback
            ]);

            $studentAnswers->push($answer);
        }


        // Code-Based Quiz
        foreach ($codeAnswers as $quizCodeId => $answerText) {
            $code = QuizCode::find($quizCodeId);
            if (!$code) continue;

            $expected = $code->test_cases ?? '';
            $quizId = $code->quiz_id;

            try {
                $response = Http::timeout(30)->post('https://iswara-code-evaluation-system.onrender.com/evaluate', [
                    'reference_code' => $expected,
                    'hypothesis_code' => $answerText,
                ]);


                if ($response->successful()) {
                    $data = $response->json();
                    $totalScore = $data['total_score'] ?? 0;
                    $isCorrect = $totalScore >= 0.7;

                    $quiz = Quiz::find($quizId);
                    $quizPoint = $quiz ? $quiz->point : 0;

                    if (!$isFinalTest && $isCorrect) {
                        $totalPoints += $quizPoint;
                    }

                    if ($isFinalTest) {
                        $finalTestScore = min($totalScore * 100, 100); // Batasi maksimal 100
                    }

                    $answer = StudentAnswers::create([
                        'student_id' => $student->id,
                        'quiz_id' => $quizId,
                        'choice_id' => null,
                        'answer_text' => $answerText,
                        'submitted_at' => $submittedAt,
                        'is_correct' => $isCorrect,
                        'feedback' => json_encode($data, JSON_PRETTY_PRINT)
                    ]);

                    $studentAnswers->push($answer);
                } else {
                    $studentAnswers->push(StudentAnswers::create([
                        'student_id' => $student->id,
                        'quiz_id' => $quizId,
                        'choice_id' => null,
                        'answer_text' => $answerText,
                        'submitted_at' => $submittedAt,
                        'is_correct' => 0,
                        'feedback' => 'Gagal menilai (API error)'
                    ]));
                }
            } catch (\Exception $e) {
                $studentAnswers->push(StudentAnswers::create([
                    'student_id' => $student->id,
                    'quiz_id' => $quizId,
                    'choice_id' => null,
                    'answer_text' => $answerText,
                    'submitted_at' => $submittedAt,
                    'is_correct' => 0,
                    'feedback' => 'Gagal menilai (Exception: ' . $e->getMessage() . ')'
                ]));
            }
        }

        $totalQuestions = $studentAnswers->count();
        $correctAnswers = $studentAnswers->where('is_correct', true)->count();

        if ($isFinalTest) {
            $averageScore = round($finalTestScore, 2);
            $status = $averageScore >= 70 ? 'Lulus' : 'Tidak Lulus';
        } else {
            $averageScore = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;
            $status = $averageScore >= 70 ? 'Lulus' : 'Tidak Lulus';
        }

        $summary = StudentModulSummary::where('student_id', $student->id)
            ->where('content_id', $moduleId)
            ->latest()
            ->first();

        list($minutes, $seconds) = explode(':', $duration);
        $totalSeconds = ((int)$minutes * 60) + (int)$seconds;

        if ($summary && is_null($summary->status)) {
            $summary->quiz_attempts_total_duration = $totalSeconds;
            $summary->total_score = $averageScore;
            $summary->status = $status;
            $summary->quiz_submitted_at = $submittedAt;
            $summary->quiz_attempts_count = 1;
            $summary->save();
        } else {
            $summary = StudentModulSummary::create([
                'student_id' => $student->id,
                'content_id' => $moduleId,
                'study_content_total_duration' => 0,
                'quiz_attempts_total_duration' => $totalSeconds,
                'total_score' => $averageScore,
                'status' => $status,
                'quiz_submitted_at' => $submittedAt,
                'quiz_attempts_count' => ($summary ? $summary->quiz_attempts_count + 1 : 1)
            ]);
        }

        $content = ModuleContent::find($moduleId);

        $leaderboard = Leaderboard::where('class_id', $classId)
            ->where('module_id', $content->module_id)
            ->where('student_id', $student->id)
            ->first();

        if ($leaderboard) {
            $leaderboard->point += $totalPoints;
            $leaderboard->save();
        } else {
            Leaderboard::create([
                'class_id' => $classId,
                'module_id' => $moduleId,
                'student_id' => $student->id,
                'point' => $totalPoints
            ]);
        }

        $class = Classes::findOrFail($classId);
        $modulContent = ModuleContent::findOrFail($moduleId);

        return redirect(route('dashboard.student.module.quiz.result', [
            'classId' => $classId,
            'moduleId' => $modulContent->id,
            'summaryId' => $summary->id
        ]))->with('toasts', [[
            'type' => 'success',
            'title' => 'Jawaban Tersimpan',
            'message' => 'Semua jawaban berhasil disimpan dan dinilai.',
            'time' => now()->diffForHumans()
        ]]);
    }



    public function saveDurationStudyContent(Request $request)
    {
        $request->validate([
            'module_content_id' => 'required|exists:module_contents,id',
            'duration' => 'required|integer|min:1'
        ]);

        $student = auth()->user()->student;

        // Cek summary terakhir untuk modul ini
        $summary = StudentModulSummary::where('student_id', $student->id)
            ->where('content_id', $request->module_content_id)
            ->latest()
            ->first();

        if ($summary) {
            if ($summary->status === 'Lulus') {
                // Jika status sudah Lulus, hanya update durasi yang sudah ada
                $summary->study_content_total_duration += (int) $request->duration;
                $summary->save();
            } elseif ($summary->status === 'Tidak Lulus' || is_null($summary->status)) {
                // Jika status "Tidak Lulus" atau belum ada status (null), buat summary baru
                StudentModulSummary::create([
                    'student_id' => $student->id,
                    'content_id' => $request->module_content_id,
                    'study_content_total_duration' => (int) $request->duration,
                    'quiz_attempts_total_duration' => 0,
                    'total_score' => null,
                    'status' => null,  // status bisa diatur null atau status lain sesuai kebutuhan
                    'quiz_submitted_at' => null,
                    'quiz_attempts_count' => ($summary ? $summary->quiz_attempts_count + 1 : 1)
                ]);
            }
        } else {
            // Jika summary belum ada, buat summary baru dengan durasi
            StudentModulSummary::create([
                'student_id' => $student->id,
                'content_id' => $request->module_content_id,
                'study_content_total_duration' => (int) $request->duration,
                'quiz_attempts_total_duration' => 0,
                'total_score' => null,
                'status' => 'Sedang Belajar',
                'quiz_submitted_at' => null,
                'quiz_attempts_count' => 1
            ]);
        }

        Log::info('Durasi belajar berhasil disimpan', [
            'student_id' => $student->id,
            'duration' => $request->duration
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Durasi belajar berhasil disimpan.'
        ]);
    }


    public function showResultQuiz(string $classId, string $moduleId, string $summaryId)
    {
        $user = auth()->user();
        $student = auth()->user()->student;
        $class = Classes::findOrFail($classId);
        $moduleContent = ModuleContent::findOrFail($moduleId);

        $summary = StudentModulSummary::where('id', $summaryId)
            ->where('student_id', $student->id)
            ->where('content_id', $moduleId)
            ->firstOrFail();

        $quizzes = Quiz::with(['choices', 'code'])
            ->where('content_id', $moduleId)
            ->get();

        $studentAnswers = StudentAnswers::where('student_id', $student->id)
            ->where('submitted_at', $summary->quiz_submitted_at)
            ->orderBy('submitted_at', 'asc')
            ->get();

        $quizAnswers = $quizzes->map(function ($quiz) use ($studentAnswers) {
            $answers = $studentAnswers->where('quiz_id', $quiz->id)->sortBy('submitted_at');
            return [
                'quiz' => $quiz,
                'answers' => $answers
            ];
        });

        // ✅ Tentukan URL halaman selanjutnya
        if ($summary->status === 'Tidak Lulus') {
            $nextUrl = route('dashboard.student.module', [
                'classId' => $classId,
                'moduleId' => $moduleContent->id
            ]);
        } else {
            $nextContent = ModuleContent::where('module_id', $moduleContent->module_id)
                ->where('id', '>', $moduleContent->id)
                ->orderBy('id')
                ->first();

            if ($nextContent) {
                $nextUrl = route('dashboard.student.module', [
                    'classId' => $classId,
                    'moduleId' => $nextContent->id
                ]);
            } else {
                $nextUrl = route('dashboard.student.class.show', ['classId' => $classId]);
            }
        }

        return view('student.class.quizResult', [
            'user' => $user,
            'class' => $class,
            'module' => $moduleContent,
            'moduleContent' => $moduleContent,
            'summary' => $summary,
            'quizList' => $quizAnswers,
            'nextUrl' => $nextUrl, // 🆕 kirim ke view
            'activeMenu' => 'class'
        ]);
    }


    public function calculateBloomLevels($studentId, $moduleId)
    {
        $module = Module::findOrFail($moduleId);
        $moduleContents = ModuleContent::where('module_id', $moduleId)->get();
        $moduleContentIds = $moduleContents->pluck('id');

        $quizzes = Quiz::whereIn('content_id', $moduleContentIds)->get();

        $levelData = [
            'remember' => ['correct' => 0, 'total' => 0],
            'understand' => ['correct' => 0, 'total' => 0],
            'apply' => ['correct' => 0, 'total' => 0],
            'analyze' => ['correct' => 0, 'total' => 0],
            'evaluate' => ['correct' => 0, 'total' => 0],
            'create' => ['score' => 0, 'percentage' => 0],
        ];

        $summaries = StudentModulSummary::where('student_id', $studentId)
            ->whereIn('content_id', $moduleContentIds)
            ->get()
            ->keyBy('content_id');

        $submittedTimes = $summaries->pluck('quiz_submitted_at')->unique();

        $studentAnswers = StudentAnswers::where('student_id', $studentId)
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->whereIn('submitted_at', $submittedTimes)
            ->get()
            ->groupBy('quiz_id');

        foreach ($quizzes as $quiz) {
            $bloomLevel = $quiz->bloom_level;

            if (!$bloomLevel || !isset($levelData[$bloomLevel])) continue;

            $summary = $summaries->get($quiz->content_id);
            if (!$summary) continue;

            $answers = $studentAnswers->get($quiz->id) ?? collect();

            if ($bloomLevel === 'create') {
                $levelData['create']['score'] = $summary->total_score;
                $levelData['create']['percentage'] = $summary->total_score;
            } else {
                $levelData[$bloomLevel]['total']++;
                foreach ($answers as $answer) {
                    if ($answer->is_correct) {
                        $levelData[$bloomLevel]['correct']++;
                    }
                }
            }
        }

        foreach ($levelData as $level => $data) {
            if ($level !== 'create') {
                if ($data['total'] === 0) {
                    $levelData[$level]['percentage'] = 0;
                } else {
                    $levelData[$level]['percentage'] = round(($data['correct'] / $data['total']) * 100, 2);
                }
            }
        }

        return $levelData;
    }




    public function showLeaderboard()
    {
        $user = auth()->user();

        $student = $user->student;

        // Ambil semua kelas yang diikuti siswa (beserta modul & contents)
        $classes = Classes::whereHas('students', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })
            ->with(['modules.contents'])
            ->orderBy('name')
            ->get();

        // Siapkan array leaderboard
        $leaderboards = [];
        foreach ($classes as $class) {
            foreach ($class->modules as $module) {
                $leaderboards[$class->id][$module->id] = Leaderboard::with('student.user')
                    ->where('class_id', $class->id)
                    ->where('module_id', $module->id)
                    ->orderByDesc('point')
                    ->get();
            }
        }

        // Ambil semua content ID dari seluruh module di semua kelas
        $contentIds = $classes->pluck('modules.*.contents.*.id')->flatten();

        // Progress
        $summaries = StudentModulSummary::with(['content.module'])
            ->where('student_id', $student->id)
            ->whereIn('content_id', $contentIds)
            ->orderByDesc('quiz_submitted_at')
            ->get();

        return view('student.class.leaderboard', [
            'user' => $user,
            'classes' => $classes,
            'leaderboards' => $leaderboards,
            'summaries' => $summaries,
            'activeMenu' => 'learderboard',
        ]);
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
