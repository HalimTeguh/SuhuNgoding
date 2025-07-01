<div class="card mb-6 control-module">
    <div class="card-body">
        <form id="contentControlForm" method="POST"
            action="{{ route('dashboard.teacher.module.control.update', ['id' => '__ID__']) }}">
            @csrf
            @method('PUT')

            <div class="d-flex align-items-start align-items-sm-center pb-4 border-bottom justify-content-start">
                <!-- Navigasi Kiri -->
                <div class="col-md-2 me-4 d-flex align-self-start">
                    <div class="bg-primary rounded w-100">
                        <div class="rounded p-3 w-100" style="background-color: rgba(255, 255, 255, 0.4);">
                            <ul class="list-group w-100 border-0">
                                @foreach($contents as $content)
                                <li class="list-group-item p-0 border-0 mb-2">
                                    <button class="btn w-100 py-2 content-navigate text-center" type="button"
                                        data-content-id="{{ $content->id }}"
                                        onclick="loadControlContent({{ $module->id }}, {{ $content->id }})">
                                        Pertemuan {{ $loop->iteration }}
                                    </button>
                                </li>
                                @endforeach
                                <li class="list-group-item p-0 border-0 bg-transparent">
                                    <button type="button" class="btn btn-light content-navigate w-100">+</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Konten Kanan -->
                <div class="col">
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="bx bx-info-circle fs-4 me-2"></i>
                        <div>
                            Konten materi dan tes untuk <strong>Kelas Kontrol</strong> mengikuti struktur dari
                            <strong>Kelas Eksperimen</strong>.
                            <br>Silakan <em>masukkan tautan materi</em> dan <em>tautan tes </em> yang akan digunakan oleh <strong>Kelas Kontrol</strong>
                            untuk masing-masing pertemuan.
                        </div>
                    </div>

                    <input type="hidden" name="contentId" id="contentId" value="">

                    <label for="material_link" class="form-label">Link Materi</label>
                    <input type="text" name="material_link" id="material_link"
                        class="form-control mb-3 @error('material_link') is-invalid @enderror"
                        value="{{ old('material_link') }}">
                    @error('material_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label for="test_link" class="form-label">Test Link</label>
                    <input type="text" name="test_link" id="test_link"
                        class="form-control mb-3 @error('test_link') is-invalid @enderror"
                        value="{{ old('test_link') }}">
                    @error('test_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label for="notes" class="form-label">Note</label>
                    <textarea id="notes" name="notes" class="form-control mb-3 @error('notes') is-invalid @enderror"
                        style="height: 150px;">{{ old('notes') }}</textarea>
                    @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-6 d-flex justify-content-end">
                <button type="reset" class="btn btn-label-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary me-3">Save changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        tinymce.init({
            selector: 'textarea#notes',
            plugins: ['autoresize', 'link', 'lists', 'code', 'table'],
            height: 400,
            toolbar: `undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | code`,
            branding: false,
            menubar: "insert format table",
        });
    });

    function loadControlContent(moduleId, contentId) {
        // Clear form and show loading
        document.getElementById('material_link').value = "Loading...";
        document.getElementById('test_link').value = "Loading...";
        tinymce.get('notes').setContent("Loading...");

        const url = `/dashboard/teacher/pembelajaran/module/${moduleId}/content/${contentId}/control`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.error) return alert("Konten tidak ditemukan");

                document.getElementById('contentId').value = contentId;
                document.getElementById('material_link').value = data.material_link || '';
                document.getElementById('test_link').value = data.test_link || '';
                tinymce.get('notes').setContent(data.notes || '');

                document.querySelectorAll('.content-navigate').forEach(button => {
                    if (button.getAttribute('data-content-id') == contentId) {
                        button.classList.remove('btn-light');
                        button.classList.add('btn-primary', 'text-white');
                    } else {
                        button.classList.remove('btn-primary', 'text-white');
                        button.classList.add('btn-light');
                    }
                });

                // Update form action jika diperlukan
            const form = document.getElementById('contentControlForm');
            form.action = "{{ route('dashboard.teacher.module.control.update', ['id' => '__ID__']) }}".replace('__ID__', contentId);

            })
            .catch(err => {
                alert("Gagal mengambil data konten");
                console.error(err);
            });
    }
</script>