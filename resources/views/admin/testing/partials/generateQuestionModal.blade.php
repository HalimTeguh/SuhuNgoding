<!-- Modal Generate or Upload Question -->
<div class="modal fade" id="generateQuestionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Soal Pre/Post Test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="questionForm" action="{{ route('quiz.save-json') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3" id="modeTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="upload-tab" data-bs-toggle="tab"
                                data-bs-target="#uploadTab" type="button" role="tab">Upload Rangkuman (.docx)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="json-tab" data-bs-toggle="tab" data-bs-target="#jsonTab"
                                type="button" role="tab">Input JSON Soal</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Upload Rangkuman -->
                        <div class="tab-pane fade show active" id="uploadTab" role="tabpanel">
                            <div class="mb-3">
                                <label for="rangkuman" class="form-label">File Rangkuman (.docx)</label>
                                <input type="file" id="rangkuman" class="form-control" accept=".docx">
                            </div>
                            <textarea name="rangkuman" id="rangkumanText" class="d-none"></textarea>
                        </div>

                        <!-- JSON Soal -->
                        <div class="tab-pane fade" id="jsonTab" role="tabpanel">
                            <div class="mb-3">
                                <label for="jsonInput" class="form-label">Tempel JSON Soal di sini</label>
                                <textarea id="jsonInput" class="form-control" rows="10"
                                    placeholder='[{"question":"...", "choices":[...]}]'></textarea>
                                <input type="hidden" name="questions">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <span id="jsonWarning" class="text-danger me-auto d-none">⚠️ JSON tidak valid.</span>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="saveButton">Simpan Soal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('rangkuman');
    const saveBtn = document.getElementById('saveButton');
    const jsonInput = document.getElementById('jsonInput');
    const jsonHidden = document.querySelector('input[name="questions"]');
    const jsonWarning = document.getElementById('jsonWarning');

    fileInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file || file.type !== "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
            alert("File harus .docx");
            return;
        }

        const reader = new FileReader();
        reader.onload = function () {
            mammoth.extractRawText({ arrayBuffer: reader.result })
                .then(function (result) {
                    const text = result.value.trim();
                    if (text.length > 0) {
                        document.getElementById('rangkumanText').value = text;
                    } else {
                        alert("Isi file kosong.");
                        saveBtn.disabled = true;
                    }
                })
                .catch(function (err) {
                    alert("Gagal membaca file: " + err.message);
                    saveBtn.disabled = true;
                });
        };
        reader.readAsArrayBuffer(file);
    });

    jsonInput.addEventListener('input', function () {
        try {
            const parsed = JSON.parse(jsonInput.value);
            const isArray = Array.isArray(parsed);
            saveBtn.disabled = !isArray;
            jsonWarning.classList.toggle('d-none', isArray);
            if (isArray) {
                jsonHidden.value = JSON.stringify(parsed); // kirim sebagai string JSON
            }
        } catch {
            saveBtn.disabled = true;
            jsonWarning.classList.remove('d-none');
        }
    });

    document.getElementById('questionForm').addEventListener('submit', function (e) {
        if (!jsonWarning.classList.contains('d-none') && jsonInput.value.trim().length > 0) {
            e.preventDefault();
            alert("JSON tidak valid.");
        }
    });
});
</script>