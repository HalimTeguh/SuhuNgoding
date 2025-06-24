<?php

namespace App\Http\Controllers;

use App\Models\QuestionChoice;
use App\Models\QuestionTest;
use App\Models\StudentTestAnswer;
use App\Models\TTesting;
use Illuminate\Http\Request;

class StudentTestingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function showPretest()
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            abort(403, 'Unauthorized action.');
        }

        $student = $user->student;

        $testingStudent = TTesting::where('student_id', $student->id)->first();
        $scorePretest = $testingStudent ? $testingStudent->pre_test_score : null;
        $classType = $testingStudent ? $testingStudent->class_type : null;
        $canDoPretest = $testingStudent && $testingStudent->can_do_pretest;

        if ($canDoPretest) {
            $questions = QuestionTest::with('choices')->get();
        } else {
            $questions = [];
        }

        return view('student.testing.pretestView', [
            'user' => $user,
            'canDoPretest' => $canDoPretest,
            'scorePretest' => $scorePretest,
            'classType' => $classType,
            'questions' => $questions,
            'activeMenu' => 'pretest'
        ]);
    }

    public function submitPretest(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'student') {
            abort(403, 'Unauthorized action.');
        }

        $student = $user->student;
        $answers = $request->input('answers', []);
        $duration = $request->input('duration');

        $testingStudent = TTesting::where('student_id', $student->id)->first();
        if (!$testingStudent || !$testingStudent->can_do_pretest) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengerjakan pre-test.');
        }

        $totalQuestions = count($answers);
        $correctAnswers = 0;

        foreach ($answers as $questionId => $choiceId) {
            $isCorrect = QuestionChoice::where('id', $choiceId)->value('is_correct');

            // Simpan jawaban siswa ke student_test_answers jika diinginkan
            StudentTestAnswer::create([
                'student_id' => $student->id,
                'question_test_id' => $questionId,
                'question_choice_id' => $choiceId,
                'is_correct' => $isCorrect,
            ]);

            if ($isCorrect) {
                $correctAnswers++;
            }
        }

        // Hitung skor dalam persen
        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;

        $testingStudent->pre_test_score = round($score, 2);
        $testingStudent->can_do_pretest = false;

        $testingStudent->save();

        return redirect()->route('dashboard.student.pretest')->with('toasts', [[
            'type' => 'success',
            'title' => 'Pre-Test Selesai',
            'message' => "Kamu telah menyelesaikan Pre-test, silahkan menunggu pembagian kelas",
            'time' => now()->diffForHumans()
        ]]);
    }

    public function showPosttest()
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            abort(403, 'Unauthorized action.');
        }

        $student = $user->student;

        $testingStudent = TTesting::where('student_id', $student->id)->first();
        $scorePosttest = $testingStudent ? $testingStudent->post_test_score : null;
        $classType = $testingStudent ? $testingStudent->class_type : null;
        $canDoPosttest = $testingStudent && $testingStudent->can_do_posttest;

        if ($canDoPosttest) {
            $questions = QuestionTest::with('choices')->get();
        } else {
            $questions = [];
        }

        return view('student.testing.posttestView', [
            'user' => $user,
            'canDoPosttest' => $canDoPosttest,
            'scorePosttest' => $scorePosttest,
            'classType' => $classType,
            'questions' => $questions,
            'activeMenu' => 'posttest'
        ]);
    }

    public function submitPosttest(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'student') {
            abort(403, 'Unauthorized action.');
        }

        $student = $user->student;
        $answers = $request->input('answers', []);
        $duration = $request->input('duration');

        $testingStudent = TTesting::where('student_id', $student->id)->first();
        if (!$testingStudent || !$testingStudent->can_do_posttest) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengerjakan post-test.');
        }

        $totalQuestions = count($answers);
        $correctAnswers = 0;

        foreach ($answers as $questionId => $choiceId) {
            $isCorrect = QuestionChoice::where('id', $choiceId)->value('is_correct');

            // Simpan jawaban siswa ke student_test_answers jika diinginkan
            StudentTestAnswer::create([
                'student_id' => $student->id,
                'question_test_id' => $questionId,
                'question_choice_id' => $choiceId,
                'is_correct' => $isCorrect,
            ]);

            if ($isCorrect) {
                $correctAnswers++;
            }
        }

        // Hitung skor dalam persen
        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;

        $testingStudent->post_test_score = round($score, 2);
        $testingStudent->can_do_posttest = false;

        $testingStudent->save();

        return redirect()->route('dashboard.student.posttest')->with('toasts', [[
            'type' => 'success',
            'title' => 'Post-Test Selesai',
            'message' => "Kamu telah menyelesaikan Post-test, silahkan menunggu pembagian kelas",
            'time' => now()->diffForHumans()
        ]]);
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
