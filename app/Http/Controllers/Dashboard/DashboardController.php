<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\Module;
use App\Models\StudentModulSummary;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth'); // Pastikan hanya user login yang bisa mengakses controller ini
    }

    public function admin()
    {
        $user = auth()->user();


        return view('admin.dashboard.index', [
            'user' => $user,
            'activeMenu' => 'dashboard'
        ]);
    }

    public function teacher()
    {
        return view('teacher.dashboard');
    }

    public function student()
    {
        $user = auth()->user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'Student data not found for this user.');
        }

        $classes = $student->classes()->whereNull('classes.deleted_at')->get();

        // Cari summaryModule yang terbaru
        $summaryModule = StudentModulSummary::with(['content.module.classes'])->where('student_id', $student->id)
            ->orderByDesc('updated_at')
            ->first();

        $nextUrl = null;
        $buttonLabel = 'Mulai Belajar Sekarang';
        $imagePath = asset('assets/img/illustrations/man-with-laptop.png');
        $descLabel = null;

        if ($summaryModule && $summaryModule->content && $summaryModule->content->module) {
            $module = $summaryModule->content->module;
            $class = $module->classes->first(); // Ambil class pertama (jika ada)

            if ($class) {
                if ($summaryModule->quiz_attempts_count > 0) {
                    if ($summaryModule->status === 'Lulus') {
                        // Cari konten berikutnya
                        $currentIndex = $module->contents->pluck('id')->search($summaryModule->content_id);
                        $nextContent = $module->contents->get($currentIndex + 1);

                        if ($nextContent) {
                            $nextUrl = route('dashboard.student.module', [
                                'classId' => $class->id,
                                'moduleId' => $nextContent->id
                            ]);
                            $descLabel = "Anda telah lulus materi \"{$summaryModule->content->title}\"";
                            $buttonLabel = 'Lanjutkan ke Materi Berikutnya';
                        } else {
                            $nextUrl = route('dashboard.student.module', [
                                'classId' => $class->id,
                                'moduleId' => $summaryModule->content_id
                            ]);
                            $descLabel = "Anda belum lulus materi \"{$summaryModule->content->title}\"";
                            $buttonLabel = 'Ulangi Materi Terakhir';
                        }
                    } else {
                        $nextUrl = route('dashboard.student.module', [
                            'classId' => $class->id,
                            'moduleId' => $summaryModule->content_id
                        ]);
                        $descLabel = "Anda belum lulus materi \"{$summaryModule->content->title}\"";

                        $buttonLabel = 'Belajar Ulang Modul Ini';
                    }
                } else {
                    $nextUrl = route('dashboard.student.module', [
                        'classId' => $class->id,
                        'moduleId' => $summaryModule->content_id
                    ]);
                    $descLabel = '';
                    $buttonLabel = 'Mulai Belajar Sekarang';
                }
            }
        }

        $totalPoints = Leaderboard::where('student_id', $student->id)->sum('point');

        $totalContents = 0;
        $completedContents = 0;

        foreach ($classes as $class) {
            foreach ($class->modules as $module) {
                $totalContents += $module->contents->count();

                foreach ($module->contents as $content) {
                    $summary = StudentModulSummary::where('student_id', $student->id)
                        ->where('content_id', $content->id)
                        ->whereNotNull('status')
                        ->first();
                    if ($summary) {
                        $completedContents++;
                    }
                }
            }
        }

        $modules = Module::whereHas('classes.students', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })->get();

        $allLeaderboards = [];
        $allMyPositions = [];

        // OVERALL MODE — Aggregate points per student
        $overallLeaderboard = Leaderboard::with('student.user')
            ->whereIn('module_id', $modules->pluck('id'))
            ->get()
            ->groupBy('student_id')
            ->map(function ($entries) {
                $firstEntry = $entries->first();
                $totalPoints = $entries->sum('point');
                // Buat object baru yang mewakili aggregate leaderboard
                $firstEntry->point = $totalPoints;
                return $firstEntry;
            })
            ->sortByDesc('point')
            ->values(); // Reset index agar search() +1 akurat

        $allLeaderboards['overall'] = $overallLeaderboard;
        $allMyPositions['overall'] = $overallLeaderboard->search(fn($entry) => $entry->student_id === $student->id) + 1;

        // PER-MODULE MODE — as is
        foreach ($modules as $module) {
            $leaderboard = Leaderboard::with('student.user')
                ->where('module_id', $module->id)
                ->orderByDesc('point')
                ->get();
            $allLeaderboards[$module->id] = $leaderboard;
            $allMyPositions[$module->id] = $leaderboard->search(fn($entry) => $entry->student_id === $student->id) + 1;
        }

        // Top 3 (or 5) — default overall
        $topThreeLeaderboards = $overallLeaderboard->take(5);


        $summaries = StudentModulSummary::with(['content.module'])
            ->where('student_id', $student->id)
            ->orderByDesc('updated_at')
            ->get();

        return view('student.dashboard.index', [
            'user' => $user,
            'student' => $student,
            'classes' => $classes,
            'summaryModule' => $summaryModule,
            'nextUrl' => $nextUrl,
            'descLabel' => $descLabel,
            'buttonLabel' => $buttonLabel,
            'imagePath' => $imagePath,
            'totalPoints' => $totalPoints,
            'completedContents' => $completedContents,
            'totalContents' => $totalContents,
            'modules' => $modules,
            'allLeaderboards' => $allLeaderboards,
            'allMyPositions' => $allMyPositions,
            'topThreeLeaderboards' => $topThreeLeaderboards,
            'myLeaderboardPosition' => $allMyPositions['overall'] ?? '-',
            'summaries' => $summaries,
            'activeMenu' => 'dashboard'
        ]);
    }
}
