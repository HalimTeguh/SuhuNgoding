@extends('layout.dashboard')

@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="w-100">
                <div class="d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded">
                    <h5 id="entityHeader" class="m-0">Testing Question</h5>
                    <div>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#questionModal" data-action="add">
                            + Add New Question
                        </button>

                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" id="importJsonButton"
                            data-bs-target="#generateQuestionModal">
                            + Generate Question
                        </button>
                    </div>


                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    @forelse($questions as $index => $soal)
                    <div class="card mb-6 border-light shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title fw-bold text-dark mb-3">
                                    {{ $index + 1 }}. {!! $soal->question !!}
                                </h6>
                                <div>
                                    <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#questionModal" data-action="edit"
                                        data-question-id="{{ $soal->id }}" data-question="{{ $soal->question }}"
                                        data-choices="{{ json_encode($soal->choices) }}">
                                        <i class="bx bx-edit me-1"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#deleteQuestion" data-question-id="{{ $soal->id }}"
                                        data-action="{{ route('quiz.destroy', $soal->id) }}">
                                        <i class="bx bx-trash me-1"></i> Delete
                                    </button>
                                </div>
                            </div>
                            <div class="list-group list-group-flush">
                                @foreach($soal->choices as $choice)
                                <label class="list-group-item d-flex align-items-center py-3 border-bottom">
                                    <input type="radio" name="question_{{ $soal->id }}" value="{{ $choice->id }}"
                                        class="form-check-input me-3" disabled {{ $choice->is_correct ? 'checked' : ''
                                    }}>
                                    <span class="text-dark">{{ $choice->choice }} <span
                                            class="ms-2 badge bg-label-success">{{ $choice->is_correct ? 'Jawaban benar'
                                            : '' }}</span></span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-info text-center" role="alert">
                        No questions available. Add a new question to get started!
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        <!--/ Basic Bootstrap Table -->

        <hr class="my-4" />

    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>

<!-- Content wrapper -->

@include('teacher.testing.partials.addQuestionModal')
@include('teacher.testing.partials.deleteQuestionModal')
@include('teacher.testing.partials.generateQuestionModal')

<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.8/mammoth.browser.min.js"></script>


@endsection