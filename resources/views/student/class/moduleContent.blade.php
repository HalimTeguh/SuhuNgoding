@extends('layout.dashboard')

@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Module -->
            <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">{{ $moduleContent->title }}</h5>
                            </div>
                        </div>
                        <div class="px-3 preview-content">
                            {!! $moduleContent->content !!}
                        </div>
                    </div>
                </div>
            </div>


            <!-- List Material -->
            <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
                <div class="sticky-top" style="top: 100px">

                    <div class="mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <!-- Card Header -->
                            <form id="startQuizForm" method="POST" action="">
                                <div
                                    class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                                    <h5 class="card-title text-white m-0">Reading Duration</h5>
                                    <span id="durationDisplay"
                                        class="badge bg-white text-primary fw-semibold px-3 py-2">
                                        00:00
                                    </span>
                                    @csrf
                                    <input type="hidden" name="module_content_id" value="{{ $moduleContent->id }}">
                                    <input type="hidden" name="duration" id="durationInput">

                                </div>

                                <!-- Card Body: Button to Quiz -->
                                <div
                                    class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                    <p class="my-4">Sudah selesai membaca materi ini? Lanjut ke quiz untuk menguji
                                        pemahamanmu.</p>
                                    <button type="submit" class="btn btn-outline-primary btn-m px-4">
                                        Mulai Quiz <i class="bx bx-chevron-right ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-primary d-flex align-items-center justify-content-between gap-2">
                                <h5 class="card-title m-0 text-white">Virtual Code Environment</h5>
                            </div>
                            <div
                                class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                <p class="my-3 text-muted">
                                    Ingin mencoba menulis dan menjalankan kode secara langsung? Yuk mulai coding
                                    sekarang!
                                </p>
                                <button class="btn btn-outline-primary btn-m px-4" type="button"
                                    data-bs-toggle="offcanvas" data-bs-target="#virtualCodingCanvas"
                                    aria-controls="virtualCodingCanvas">
                                    Mulai Coding <i class="bx bx-code-alt ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-header bg-primary d-flex align-items-center justify-content-between gap-2">
                                <!-- Toggle Button -->
                                <h5 class="card-title m-0 text-white">Learning</h5>
                                <button class="btn btn-sm btn-light bg-white text-primary px-2 py-1" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#listMateriBody" aria-expanded="true"
                                    aria-controls="listMateriBody">
                                    <i class="bx bx-chevron-down"></i>
                                </button>
                            </div>
                            <!-- Collapsible Body -->
                            <div id="listMateriBody" class="collapse">
                                <div class="card-body pt-3 px-0">
                                    <ul class="list-group list-group-flush">
                                        @forelse($listContent as $content)
                                        <li class="list-group-item list-group-item-action px-4 py-3
                            @if($moduleContent->id == $content->id) bg-label-primary disabled @endif">
                                            <a href="/dashboard/student/module/{{ $content->id }}"
                                                class="d-flex justify-content-between align-items-center text-dark text-decoration-none hover-effect">
                                                <span>{{ $content->title }}</span>
                                                <i class="bx bx-chevron-right fs-4 text-muted"></i>
                                            </a>
                                        </li>
                                        @empty
                                        <li class="list-group-item text-muted text-center py-4">
                                            Belum ada materi tersedia.
                                        </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

<div class="offcanvas offcanvas-end" tabindex="-1" id="virtualCodingCanvas" aria-labelledby="virtualCodingCanvas"
    style="width: 50%;">
    <div class="offcanvas-header">
        <h5 id="virtualCodingCanvasLabel" class="offcanvas-title">Virtual Code Environtment</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="alert alert-warning d-flex align-items-center border border-warning rounded shadow-sm mb-4"
            role="alert">
            <i class="bx bx-error-circle fs-4 me-3 text-warning"></i>
            <div class="text-dark small">
                <strong>Perhatian!</strong><br>
                Virtual Coding Environment <strong>tidak mendukung penggunaan fungsi <code>input()</code></strong>.
                Silakan langsung masukkan nilai yang dibutuhkan ke dalam kode.
            </div>
        </div>

        <!-- Input Kode -->
        <div class="mb-4">
            <label for="codeInput" class="form-label fw-semibold">Tulis Kode Program:</label>
            <textarea class="form-control codeEditor" id="codeEditor" rows="15" placeholder=""># Tulis kode Python kamu di sini...
</textarea>
        </div>

        <!-- Tombol Run di Tengah -->
        <div class="d-flex justify-content-start mb-4">
            <button class="btn btn-primary px-4" onclick="runVirtualCode()">▶ Run Code</button>
        </div>

        <!-- Output -->
        <div class="mb-2">
            <label for="outputArea" class="form-label fw-semibold">Output:</label>
            <pre id="outputArea" class="bg-light border rounded p-3 h-100"
                style="height: 350px; overflow-y: auto;">Klik 'Run' untuk melihat hasil...</pre>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/pyodide/v0.23.4/full/pyodide.js"></script>

<script>
    let pyodide = null;

    async function loadPyodideEnv() {
        pyodide = await loadPyodide();
    }

    
    document.addEventListener("DOMContentLoaded", () => {

        window.codeMirrorInstance = CodeMirror.fromTextArea(document.getElementById("codeEditor"), {
            lineNumbers: true,
            mode: "python",
            theme: "monokai",
            tabSize: 4,
            indentUnit: 4,
            lineWrapping: true,
            autoCloseBrackets: true,
            matchBrackets: true
        });
        

        const readingStartTime = Date.now();
        const moduleContentId = {{ $moduleContent->id }};

        function getReadingDurationInSeconds() {
            const now = Date.now();
            return Math.floor((now - readingStartTime) / 1000);
        }

        function formatSecondsToMMSS(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
        }

        // Update tampilan badge setiap detik
        setInterval(() => {
            const seconds = getReadingDurationInSeconds();
            const display = document.getElementById('durationDisplay');
            if (display) {
                display.textContent = formatSecondsToMMSS(seconds);
            }
        }, 1000);

        // Kirim durasi saat form dikirim (ke quiz)
        const form = document.getElementById('startQuizForm');
        if (form) {
            form.addEventListener('submit', function () {
                const seconds = getReadingDurationInSeconds();
                const durationInput = document.getElementById('durationInput');
                if (durationInput) {
                    durationInput.value = seconds;
                }
            });
        }

        // Kirim durasi saat keluar halaman
        window.addEventListener("beforeunload", () => {
            const duration = getReadingDurationInSeconds();
            if (duration < 1) return;

            navigator.sendBeacon(
                "{{ url('/dashboard/student/save-duration/content') }}",
                new Blob([
                    new URLSearchParams({
                        module_content_id: moduleContentId,
                        duration: duration,
                        _token: "{{ csrf_token() }}"
                    })
                ], { type: "application/x-www-form-urlencoded" })
            );
        });
    });

    loadPyodideEnv(); // Muat interpreter saat awal

    async function runVirtualCode() {
        const output = document.getElementById("outputArea");
        output.innerText = "▶ Menjalankan kode...\n\n";

        const code = window.codeMirrorInstance.getValue();

        // Injeksi fungsi ke Pyodide
        window.send_output = (text) => {
            // Konversi newline menjadi baris baru
            output.innerText += text;
        };

        pyodide.globals.set("send_output", send_output);

        // Redirect stdout/stderr
        await pyodide.runPythonAsync(`
    import sys

    class JSWriter:
        def write(self, s):
            send_output(s)
        def flush(self): pass

    sys.stdout = JSWriter()
    sys.stderr = JSWriter()
        `);

        // Jalankan kode siswa
        try {
            await pyodide.runPythonAsync(code);
        } catch (err) {
            output.innerText += `❌ Exception: ${err}`;
        }
    }


</script>





@endsection