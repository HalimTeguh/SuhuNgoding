@extends('layout.dashboard')

@section('content')
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <!-- Quiz -->
            <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
                <div id="quizContainer">
                    @foreach ($quizList as $index => $item)
                    @php
                    $quiz = $item['quiz'];
                    $answers = $item['answers'];
                    $studentChoiceId = optional($answers->last())->choice_id;
                    $answerData = $answers->last();
                    @endphp
                    <div class="card mb-4 shadow-sm border border-light quiz-question">
                        <div class="card-header bg-white d-flex justify-content-between align-items-start mb-0">
                            <h2 class="fs-3 mb-0">Question {{ $index + 1 }}</h2>
                            <div>
                                <span class="badge bg-label-primary border border-primary fs-8 py-2 px-3 mt-2">
                                    {{ ucfirst($quiz->bloom_level) }}
                                </span>
                            </div>
                        </div>
                        <hr class="my-1">
                        <div class="card-body">
                            <h5>{!! $quiz->question !!}</h5>

                            @if ($quiz->type === 'multiple_choice')
                            @foreach ($quiz->choices as $choice)
                            @php
                            $isSelected = $studentChoiceId == $choice->id;
                            @endphp
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" disabled {{ $isSelected ? 'checked' : ''
                                    }}>
                                <label class="form-check-label 
                                                {{ $isSelected ? 'fw-bold text-primary' : '' }}">
                                    {{ $choice->choice_text }}
                                    @if ($isSelected)
                                    <span class="badge bg-warning text-dark ms-2">Jawaban Kamu</span>
                                    @endif
                                </label>
                            </div>
                            @endforeach

                            @if ($answerData)
                            <div class="mt-3">
                                <p>Status:
                                    @if ($answerData->is_correct)
                                    <span class="badge bg-success">Benar</span>
                                    @else
                                    <span class="badge bg-danger">Salah</span>
                                    @endif
                                </p>
                                @if ($answerData->feedback)
                                <p><strong>Feedback:</strong></p>
                                <pre class="bg-light border rounded p-2">{{ $answerData->feedback }}</pre>
                                @endif
                            </div>
                            @else
                            <div class="mt-3">
                                <span class="badge bg-label-danger ">Anda tidak menjawab</span>
                            </div>
                            @endif
                            @elseif($quiz->type === 'code')
                            @if($answers->isEmpty())
                                <div class="mt-3">
                                <textarea class="code-editor-student" readonly>{{ $answer->answer_text ?? 'Anda tidak menjawab' }}</textarea>

                                    <span class="badge bg-label-danger mt-3">Anda tidak menjawab</span>
                                </div>
                            @endif
                            @foreach ($answers as $answer)
                            @php
                            $expectedCode = $quiz->code->first()->test_cases;
                            @endphp
                            <div class="mb-3">
                                <label><strong>Jawaban Kamu:</strong></label>
                                <textarea class="code-editor-student" readonly>{{ $answer->answer_text ?? 'Anda tidak menjawab' }}</textarea>

                                <label class="mt-3"><strong>Expected Code:</strong></label>
                                <textarea class="code-editor-expected" readonly>{{ $expectedCode }}</textarea>

                                <div class="mt-3">
                                    <p>Status:
                                        @if ($answer->is_correct)
                                        <span class="badge bg-success">Benar</span>
                                        @else
                                        <span class="badge bg-danger">Salah</span>
                                        @endif
                                    </p>

                                    @if ($answer->feedback)
                                    @php
                                    $feedbackData = json_decode($answer->feedback, true);
                                    @endphp
                                    @if (is_array($feedbackData))
                                    <p><strong>Feedback:</strong></p>
                                    <ul class="list-group mb-2">
                                        @foreach ($feedbackData as $key => $value)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                            <span class="badge bg-primary rounded-pill">{{ $value }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <pre class="bg-light border rounded p-2">{{ $answer->feedback }}</pre>
                                    @endif
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            @endif

                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
                <div class="sticky-top" style="top: 100px">

                    <!-- Nilai -->
                    <div class="card shadow-sm border-0 mt-4">
                        @if($summary->total_score >= 70)
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title text-white m-0">Hasil Nilai Kamu</h5>
                        </div>
                        @else
                        <div class="card-header bg-danger text-white">
                            <h5 class="card-title text-white m-0">Hasil Nilai Kamu</h5>
                        </div>
                        @endif
                        <div class="card-body text-center">
                            <h3 class="mt-3">{{ $summary->total_score ?? 0 }}</h3>
                            @if($summary->total_score >= 70)
                            <span class="badge bg-success">Lulus</span>
                            @else
                            <span class="badge bg-danger">Belum Lulus</span>
                            @endif
                        </div>
                    </div>

                    <!-- Next Materi -->
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title text-white m-0">Next Materi</h5>
                        </div>
                        <div class="card-body text-center">
                            <p class="my-3">Klik tombol di bawah untuk melanjutkan ke materi berikutnya.</p>

                            @if ($summary->status === 'Lulus')
                            <a href="/dashboard/student/class/{{ $class->id }}/module/{{ $module->id + 1}}"
                                class="btn btn-success px-4">
                                Lanjutkan <i class="bx bx-send ms-2"></i>
                            </a>
                            @else
                            <a href="/dashboard/student/class/{{ $class->id }}/module/{{ $module->id}}"
                                class="btn btn-success px-4">
                                Belajar Kembali <i class="bx bx-send ms-2"></i>
                            </a>
                            <p class="text-danger mt-2 mb-0">
                                Kamu harus lulus quiz terlebih dahulu untuk melanjutkan ke materi berikutnya.
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".code-editor-student, .code-editor-expected").forEach((textarea) => {
            CodeMirror.fromTextArea(textarea, {
                lineNumbers: true,
                mode: "python",
                theme: "monokai",
                tabSize: 4,
                indentUnit: 4,
                lineWrapping: true,
                autoCloseBrackets: true,
                matchBrackets: true,
                readOnly: true
            });
        });
    });
</script>

@endsection