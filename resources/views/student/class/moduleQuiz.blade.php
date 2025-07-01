@extends('layout.dashboard')

@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <form id="submitQuizForm" method="POST" action="/dashboard/student/class/{{ $class->id }}/module/{{ $moduleContent->id }}/quiz/">
            <div class="row">
                <!-- Quiz -->
                <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
                    @csrf
                    <input type="hidden" name="module_content_id" value="{{ $moduleContent->id }}">
                    <input type="hidden" name="duration" id="durationInput">
                    <div id="quizContainer">
                        @foreach ($quizList as $index => $quiz)
                        <div class="card mb-4 shadow-sm border border-light quiz-question" data-index="{{ $index }}"
                            style="@if ($index !== 0) display: none; @endif">
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
                                @foreach ($quiz->choices->shuffle() as $choice)
                                <div class="form-check mb-2">
                                    <input class="form-check-input quiz-choice" type="radio"
                                        name="quiz[{{ $quiz->id }}]" value="{{ $choice->id }}"
                                        id="choice-{{ $choice->id }}" data-question-index="{{ $index }}">
                                    <label class="form-check-label" for="choice-{{ $choice->id }}">
                                        {{ $choice->choice_text }}
                                    </label>
                                </div>
                                @endforeach
                                @elseif ($quiz->type === 'code')
                                @foreach ($quiz->code as $code)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-1 text-muted"><strong>Expected Output:</strong></p>
                                        <p class="mb-1 text-muted"><strong>Language:</strong> {{ $code->language }}</p>
                                    </div>
                                    <pre class="bg-light border rounded p-2">{{ $code->expected_output }}</pre>
                                    <textarea name="student_code[{{ $code->id }}]" class="code-editor"
                                        data-question-index="{{ $index }}"></textarea>

                                    <!-- Tombol Run di Tengah -->
                                    <div class="d-flex justify-content-start mb-4">
                                        <button class="btn btn-primary px-4" onclick="runVirtualCode(this)"
                                            type="button">▶ Run Code</button>
                                    </div>

                                    <!-- Output -->
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold">Output:</label>
                                        <pre class="output-area bg-light border rounded p-3 h-100"
                                            style="height: 350px; overflow-y: auto;">Klik 'Run' untuk melihat hasil...</pre>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        @endforeach

                        <!-- Tombol Navigasi -->
                        <div class="d-flex justify-content-between mt-3">
                            <button class="btn btn-outline-secondary" id="prevBtn" type="button"
                                disabled>Sebelumnya</button>
                            <button class="btn btn-primary" id="nextBtn" type="button">Selanjutnya</button>
                        </div>
                    </div>

                </div>

                <!-- Navigasi Soal -->
                <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
                    <div class="sticky-top" style="top: 100px">
                        <div class="card shadow-sm border-0 mb-4">
                            <div
                                class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="card-title m-0 text-white">Navigasi Soal</h5>
                                <span id="durationDisplay"
                                    class="badge bg-white text-primary fw-semibold px-3 py-2">00:00</span>
                            </div>
                            <div class="card-body py-4">
                                <div class="d-flex flex-wrap gap-2 justify-content-start align-items-center"
                                    id="questionNav">
                                    @foreach ($quizList as $index => $quiz)
                                    <button type="button" class="btn btn-outline-secondary btn-sm nav-btn"
                                        data-index="{{ $index }}">
                                        {{ $index + 1 }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Kirim -->
                        <div class="card shadow-sm border-0 my-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title text-white m-0">Selesai?</h5>
                            </div>
                            <div class="card-body text-center">
                                <p class="my-3">Sudah yakin dengan jawabanmu? Klik tombol di bawah untuk mengirim
                                    jawaban.</p>
                                <button type="submit" class="btn btn-success px-4">
                                    Kirim Jawaban <i class="bx bx-send ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/pyodide/v0.23.4/full/pyodide.js"></script>

<script>
    let pyodide = null;
    async function loadPyodideEnv() {
        pyodide = await loadPyodide();
    }

    document.addEventListener("DOMContentLoaded", () => {
        const questions = document.querySelectorAll(".quiz-question");
        const navButtons = document.querySelectorAll(".nav-btn");
        const choiceInputs = document.querySelectorAll(".quiz-choice");
        const editors = [];

        document.querySelectorAll(".code-editor").forEach((textarea, idx) => {
            const editor = CodeMirror.fromTextArea(textarea, {
                lineNumbers: true,
                mode: "python",
                theme: "monokai",
                tabSize: 4,
                indentUnit: 4,
                lineWrapping: true,
                autoCloseBrackets: true,
                matchBrackets: true
            });
            editors.push({ editor, questionIndex: parseInt(textarea.dataset.questionIndex) });

            editor.on("change", () => {
                const navBtn = navButtons[textarea.dataset.questionIndex];
                navBtn.classList.remove("btn-outline-secondary", "btn-outline-primary");
                navBtn.classList.add("btn-primary");
            });
        });

        window.editors = editors;

        function runVirtualCode(button) {
            const container = button.closest(".mb-3");
            const entry = editors.find(e => e.editor.getTextArea().closest(".mb-3") === container);
            const output = container.querySelector(".output-area");
            output.innerText = "▶ Menjalankan kode...\n\n";

            const code = entry.editor.getValue();

            window.send_output = (text) => {
                output.innerText += text;
            };

            pyodide.globals.set("send_output", send_output);

            pyodide.runPythonAsync(`
                import sys
                class JSWriter:
                    def write(self, s): send_output(s)
                    def flush(self): pass
                sys.stdout = JSWriter()
                sys.stderr = JSWriter()
            `).then(() => {
                return pyodide.runPythonAsync(code);
            }).catch(err => {
                output.innerText += `❌ Exception: ${err}`;
            });
        }

        window.runVirtualCode = runVirtualCode;

        let currentIndex = 0;
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");

        function showQuestion(index) {
            questions.forEach((q, i) => q.style.display = i === index ? "block" : "none");

            navButtons.forEach((btn, i) => {
                btn.classList.remove("btn-primary", "btn-outline-primary", "btn-outline-secondary");
                const inputName = `quiz[${@json($quizList)[i].id}]`;
                const answered = document.querySelector(`input[name='${inputName}']:checked`);
                const isCodeFilled = editors.find(e => e.questionIndex === i && e.editor.getValue().trim() !== "");
                if (index === i) {
                    btn.classList.add("btn-outline-primary");
                } else if (answered || isCodeFilled) {
                    btn.classList.add("btn-primary");
                } else {
                    btn.classList.add("btn-outline-secondary");
                }
            });

            currentIndex = index;
            prevBtn.disabled = index === 0;

            if (index === questions.length - 1) {
                nextBtn.classList.add('d-none'); // sembunyikan
            } else {
                nextBtn.classList.remove('d-none'); // tampilkan kembali jika belum akhir
                nextBtn.textContent = "Selanjutnya";
            }
        }



        navButtons.forEach(btn => {
            btn.addEventListener("click", () => {
                const index = parseInt(btn.getAttribute("data-index"));
                showQuestion(index);
            });
        });

        prevBtn.addEventListener("click", () => {
            if (currentIndex > 0) showQuestion(currentIndex - 1);
        });

        nextBtn.addEventListener("click", () => {
            if (currentIndex < questions.length - 1) {
                showQuestion(currentIndex + 1);
            } else {
                document.getElementById("submitQuizForm").submit();
            }
        });

        choiceInputs.forEach(input => {
            input.addEventListener("change", () => {
                const index = parseInt(input.getAttribute("data-question-index"));
                navButtons[index].classList.remove("btn-outline-secondary", "btn-outline-primary");
                navButtons[index].classList.add("btn-primary");
            });
        });

        showQuestion(currentIndex);
    });

    loadPyodideEnv();

    // Timer setup
    let startTime = Date.now();
    setInterval(() => {
        const elapsed = Date.now() - startTime;
        const totalSeconds = Math.floor(elapsed / 1000);
        const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
        const seconds = String(totalSeconds % 60).padStart(2, '0');
        document.getElementById('durationDisplay').textContent = `${minutes}:${seconds}`;
        document.getElementById('durationInput').value = `${minutes}:${seconds}`;
    }, 1000);
</script>

@endsection