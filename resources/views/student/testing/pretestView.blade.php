@extends('layout.dashboard')

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- KONDISI 1: Pretest belum dimulai --}}
        @if(!$canDoPretest && is_null($scorePretest))
        <div class="alert alert-warning border border-warning shadow-sm text-dark">
            <h5 class="mb-2">Pre-test Belum Dimulai</h5>
            <p>Silakan menunggu informasi dari guru atau admin sebelum memulai pre-test.</p>
        </div>

        {{-- KONDISI 2: Pretest sedang berlangsung --}}
        @elseif($canDoPretest && is_null($scorePretest))
        <form id="submitQuizForm" method="POST" action="{{ route('dashboard.student.pretest.submit') }}">
            @csrf
            <input type="hidden" name="duration" id="durationInput">
            <div class="row">
                <div class="col-lg-8 col-xl-9">
                    <div id="quizContainer">
                        @foreach ($questions as $index => $question)
                        <div class="card mb-4 quiz-question" data-index="{{ $index }}">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Question {{ $index + 1 }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">{!! $question->question !!}</div>
                                @foreach ($question->choices->shuffle() as $choice)
                                <div class="form-check mb-2">
                                    <input class="form-check-input quiz-choice" type="radio"
                                        name="answers[{{ $question->id }}]" value="{{ $choice->id }}"
                                        id="choice-{{ $choice->id }}">
                                    <label class="form-check-label" for="choice-{{ $choice->id }}">
                                        {{ $choice->choice }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-4 col-xl-3">
                    <div class="sticky-top" style="top: 100px;">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-primary text-white d-flex justify-content-between">
                                <h6 class="mb-0 text-white">Durasi</h6>
                                <span id="durationDisplay" class="badge bg-white text-primary fw-semibold px-3 py-2">00:00</span>
                            </div>
                            <div class="card-body text-center">
                                <p class="text-muted small my-3">Jika sudah yakin, klik tombol di bawah untuk submit jawaban.</p>
                                <button type="submit" class="btn btn-success px-4" id="submitQuizBtn">
                                    Kirim Jawaban <i class="bx bx-send ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- KONDISI 3: Pretest telah selesai --}}
        @elseif(!$canDoPretest && !is_null($scorePretest))
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h4 class="mb-3">Hasil Pre-test</h4>
                <p><strong>Skor Anda:</strong> {{ $scorePretest }} / 100</p>

                @if($classType)
                <p><strong>Tipe Kelas:</strong> {{ ucfirst($classType) }}</p>
                <div class="alert alert-success mt-3">
                    <strong>Selamat!</strong> Anda telah menyelesaikan pre-test dan telah tergabung dalam kelas <strong>{{ ucfirst($classType) }}</strong>. Silakan lanjutkan ke proses pembelajaran.
                </div>
                @else
                <div class="alert alert-info mt-3">
                    <strong>Pre-test selesai.</strong> Mohon menunggu. Tipe kelas Anda belum ditentukan oleh guru.
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>

@if($canDoPretest)
<!-- SCRIPT TIMER MUNDUR 30 MENIT -->
<script>
    // Total waktu dalam detik (30 menit)
    let remainingSeconds = 30 * 60;

    const durationInput = document.getElementById('durationInput');
    const durationDisplay = document.getElementById('durationDisplay');
    const submitForm = document.getElementById('submitQuizForm');

    const timer = setInterval(() => {
        if (remainingSeconds <= 0) {
            clearInterval(timer);
            durationInput.value = '30:00';
            durationDisplay.textContent = '00:00';
            alert('Waktu telah habis! Jawaban Anda akan otomatis disubmit.');
            submitForm.submit();
        } else {
            const minutes = String(Math.floor(remainingSeconds / 60)).padStart(2, '0');
            const seconds = String(remainingSeconds % 60).padStart(2, '0');
            durationInput.value = `${minutes}:${seconds}`;
            durationDisplay.textContent = `${minutes}:${seconds}`;
            remainingSeconds--;
        }
    }, 1000);
</script>
@endif
@endsection
