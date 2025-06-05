<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Leaderboard;
use App\Models\Module;
use App\Models\ModuleContent;
use App\Models\Quiz;
use App\Models\QuizChoice;
use App\Models\QuizCode;
use App\Models\StudentAnswers;
use App\Models\StudentModulSummary;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\File;
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
        $class = Classes::with(['modules.contents', 'students'])->findOrFail($id);

        // Ambil data leaderboard per modul
        $leaderboards = [];
        foreach ($class->modules as $module) {
            $leaderboards[$module->id] = Leaderboard::with('student.user')
                ->where('class_id', $class->id)
                ->where('module_id', $module->id)
                ->orderByDesc('point')
                ->get();
        }

        $student = auth()->user()->student;

        $modules = $class->modules;

        // Mengambil data jumlah siswa dalam kelas
        $studentCount = $class->students()->count();

        // Mengambil jumlah modul dalam kelas
        $moduleCount = $class->modules()->count();

        // Mengambil nama guru yang mengajar
        $teacherName = $class->teacher->user->name;

        // Mengisi status progress setiap konten modul
        foreach ($class->modules as $module) {
            foreach ($module->contents as $content) {
                $summary = StudentModulSummary::where('student_id', $student->id)
                    ->where('content_id', $content->id)
                    ->latest()
                    ->first();

                if ($summary) {
                    if (!is_null($summary->total_score)) {
                        // Jika sudah ada nilai quiz
                        $content->progress_status = ($summary->status === 'Lulus')
                            ? "Lulus ({$summary->total_score}%)"
                            : "Tidak Lulus ({$summary->total_score}%)";
                    } elseif ($summary->study_content_total_duration > 0) {
                        // Jika hanya membaca materi
                        $content->progress_status = "Sedang Belajar";
                    } else {
                        // Jika tidak ada progress sama sekali
                        $content->progress_status = "Belum";
                    }
                } else {
                    $content->progress_status = "Belum";
                }
            }
        }

        // Mengambil Bloom Levels untuk semua modul
        $bloomLevels = [];
        foreach ($class->modules as $module) {
            $bloomLevels[$module->id] = $this->calculateBloomLevels($student->id, $module->id); // Panggil dengan student ID dan module ID
        }


        return view('student.class.detailClass', [
            'user' => $student,
            'class' => $class,
            'modules' => $class->modules,
            'leaderboards' => $leaderboards,
            'studentCount' => $studentCount,
            'moduleCount' => $moduleCount,
            'teacherName' => $teacherName,
            'bloomLevels' => $bloomLevels, // Kirimkan data Bloom levels untuk semua modul
            'activeMenu' => 'class'
        ]);
    }


    public function showContent(string $classId, string $moduleId)
    {
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
            'user' => $student,
            'listContent' => $listContent,
            'moduleContent' => $moduleContent,
            'class' => $class,  // Kirimkan data kelas ke view
            'activeMenu' => 'class'
        ]);
    }

    public function showQuizContent(string $classId, string $moduleId)
    {
        $class = Classes::findOrFail($classId);

        $student = auth()->user()->student;

        $moduleContent = ModuleContent::findOrFail($moduleId);

        // Ambil semua quiz dengan relasi choices dan code
        $quizzes = Quiz::with(['choices', 'code'])
            ->where('content_id', $moduleId)
            ->get();

        return view('student.class.moduleQuiz', [
            'user' => $student,
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
        $studentAnswers = collect(); // 🔥 Gunakan collect() untuk array + metode koleksi Laravel
        $totalPoints = 0;  // Untuk menyimpan total poin yang diperoleh siswa

        // Multiple Choice
        foreach ($quizAnswers as $quizId => $choiceId) {
            $choice = QuizChoice::find($choiceId);
            if (!$choice) continue;

            // Ambil point dari quiz
            $quiz = Quiz::find($quizId);
            $quizPoint = $quiz ? $quiz->point : 0;

            // Cek apakah jawaban benar
            $isCorrect = $choice->is_correct;

            // Menambahkan poin jika jawaban benar
            if ($isCorrect) {
                $totalPoints += $quizPoint;  // Tambahkan poin ke total poin
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

            $studentAnswers->push($answer); // simpan ke variabel
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
                    $isCorrect = isset($data['total_score']) && $data['total_score'] >= 0.7;

                    // Ambil point dari quiz
                    $quiz = Quiz::find($quizId);
                    $quizPoint = $quiz ? $quiz->point : 0;

                    // Menambahkan poin jika jawaban benar
                    if ($isCorrect) {
                        $totalPoints += $quizPoint;  // Tambahkan poin ke total poin
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
                    $answer = StudentAnswers::create([
                        'student_id' => $student->id,
                        'quiz_id' => $quizId,
                        'choice_id' => null,
                        'answer_text' => $answerText,
                        'submitted_at' => $submittedAt,
                        'is_correct' => 0,
                        'feedback' => 'Gagal menilai (API error)'
                    ]);

                    $studentAnswers->push($answer);
                }
            } catch (\Exception $e) {
                $answer = StudentAnswers::create([
                    'student_id' => $student->id,
                    'quiz_id' => $quizId,
                    'choice_id' => null,
                    'answer_text' => $answerText,
                    'submitted_at' => $submittedAt,
                    'is_correct' => 0,
                    'feedback' => 'Gagal menilai (Exception: ' . $e->getMessage() . ')'
                ]);

                $studentAnswers->push($answer);
            }
        }

        // 🔥 Hitung hasil quiz langsung dari variabel $studentAnswers
        $totalQuestions = $studentAnswers->count();
        $correctAnswers = $studentAnswers->where('is_correct', true)->count();
        $averageScore = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;
        $status = $averageScore >= 70 ? 'Lulus' : 'Tidak Lulus';

        // 🔥 Periksa summary: update jika status NULL, insert baru jika status sudah diisi
        $summary = StudentModulSummary::where('student_id', $student->id)
            ->where('content_id', $moduleId)
            ->latest()
            ->first();

        list($minutes, $seconds) = explode(':', $duration);
        $totalSeconds = ((int)$minutes * 60) + (int)$seconds;

        if ($summary && is_null($summary->status)) {
            // Update summary yang sudah ada
            $summary->quiz_attempts_total_duration = $totalSeconds;
            $summary->total_score = $averageScore;
            $summary->status = $status;
            $summary->quiz_submitted_at = $submittedAt;
            $summary->quiz_attempts_count = 1; // default attempt count
            $summary->save();
        } else {
            // Buat summary baru
            $summary = StudentModulSummary::create([
                'student_id' => $student->id,
                'content_id' => $moduleId,
                'study_content_total_duration' => 0, // default
                'quiz_attempts_total_duration' => $totalSeconds,
                'total_score' => $averageScore,
                'status' => $status,
                'quiz_submitted_at' => $submittedAt,
                'quiz_attempts_count' => ($summary ? $summary->quiz_attempts_count + 1 : 1)
            ]);
        }

        $content = ModuleContent::find($moduleId);

        // 🔥 Tambahkan point ke Leaderboard
        $leaderboard = Leaderboard::where('class_id', $classId)
            ->where('module_id', $content->module_id)
            ->where('student_id', $student->id)
            ->first();

        if ($leaderboard) {
            $leaderboard->point += $totalPoints;  // Tambah dengan poin yang diperoleh siswa
            $leaderboard->save();  // Simpan perubahan
        } else {
            // Jika tidak ada data leaderboard, buat entri baru
            Leaderboard::create([
                'class_id' => $classId,
                'module_id' => $moduleId,
                'student_id' => $student->id,
                'point' => $totalPoints,  // Setel poinnya
            ]);
        }

        $class = Classes::findOrFail($classId);
        $modulContent = ModuleContent::findOrFail($moduleId);

        return redirect(route('dashboard.student.module.quiz.result', [
            'classId' => $classId,
            'module' => $modulContent,
            'summardy' => $summary
        ]))->with('toasts', [
            [
                'type' => 'success',
                'title' => 'Jawaban Tersimpan',
                'message' => 'Semua jawaban berhasil disimpan dan dinilai.',
                'time' => now()->diffForHumans()
            ]
        ]);
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
                'status' => null,
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

        // Ambil jawaban siswa (filter summary_id jika sudah ada)
        $studentAnswers = StudentAnswers::where('student_id', $student->id)
            ->where('submitted_at', $summary->quiz_submitted_at)
            ->orderBy('submitted_at', 'asc')
            ->get();

        // Gabungkan quiz dengan semua jawabannya
        $quizAnswers = $quizzes->map(function ($quiz) use ($studentAnswers) {
            $answers = $studentAnswers->where('quiz_id', $quiz->id)->sortBy('submitted_at');
            return [
                'quiz' => $quiz,
                'answers' => $answers
            ];
        });

        return view('student.class.quizResult', [
            'class' => $class,
            'module' => $moduleContent,
            'user' => $student,
            'moduleContent' => $moduleContent,
            'summary' => $summary,
            'quizList' => $quizAnswers,
            'activeMenu' => 'class'
        ]);
    }

    public function calculateBloomLevels($studentId, $moduleId)
    {
        // Ambil modul dan kontennya
        $module = Module::findOrFail($moduleId);
        $moduleContents = ModuleContent::where('module_id', $moduleId)->get(); // Ambil semua konten modul
        $quizzes = Quiz::whereIn('content_id', $moduleContents->pluck('id'))->get(); // Ambil semua quiz yang berhubungan dengan konten modul

        // Array untuk menyimpan hasil perhitungan per level
        $levelData = [
            'remember' => ['correct' => 0, 'total' => 0],
            'understand' => ['correct' => 0, 'total' => 0],
            'apply' => ['correct' => 0, 'total' => 0],
            'analyze' => ['correct' => 0, 'total' => 0],
            'evaluate' => ['correct' => 0, 'total' => 0]
        ];

        // Loop melalui semua quiz yang berhubungan dengan konten modul
        foreach ($quizzes as $quiz) {
            $bloomLevel = $quiz->bloom_level;  // Ambil level Bloom dari quiz

            // Increment total soal untuk level Bloom ini berdasarkan jumlah soal quiz
            if ($bloomLevel) {
                $levelData[$bloomLevel]['total']++;

                // Ambil summary dari StudentModulSummary untuk quiz ini berdasarkan percakapan terbaru
                $summary = StudentModulSummary::where('student_id', $studentId)
                    ->where('content_id', $quiz->content_id)
                    ->latest()
                    ->first(); // Ambil percakapan terbaru berdasarkan quiz_submitted_at

                // Jika tidak ada summary, lewati quiz ini
                if (!$summary) {
                    continue;
                }

                // Ambil semua jawaban siswa untuk quiz ini berdasarkan percakapan terbaru
                $answers = StudentAnswers::where('student_id', $studentId)
                    ->where('quiz_id', $quiz->id)
                    ->where('submitted_at', $summary->quiz_submitted_at) // Menyaring dengan waktu submit yang sama
                    ->get();

                // Periksa setiap jawaban siswa
                foreach ($answers as $answer) {
                    // Cek jika jawabannya benar
                    if ($answer->is_correct) {
                        $levelData[$bloomLevel]['correct']++; // Increment soal yang benar
                    }
                }
            }
        }

        // Menambahkan total soal untuk setiap level
        foreach ($levelData as $level => $data) {
            if ($data['total'] === 0) {
                // Jika tidak ada soal untuk level ini, set correct menjadi 0 dan total tetap 0
                $levelData[$level]['percentage'] = 0;
            } else {
                // Hitung persentase pemahaman per level
                $levelData[$level]['percentage'] = round(($data['correct'] / $data['total']) * 100, 2);
            }
        }

        return $levelData;
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
