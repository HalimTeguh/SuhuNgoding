<div class="card mb-6 content-module">
    <div class="card-body">
        <form id="contentForm" action="dashboard/admin/pembelajaran/content/{content}/?tab=Content" method="post">
            @csrf
            @method('PUT')
            <div class="d-flex align-items-start align-items-sm-center  pb-4 border-bottom justify-content-start ">
                <!-- Navigasi Kiri -->
                <div class="col-md-2 me-4 d-flex align-self-start">
                    <!-- Layer pertama sebagai background -->
                    <div class="bg-primary rounded  w-100">
                        <div class="rounded p-3 w-100" style="background-color: rgba(255, 255, 255, 0.4);">

                            <!-- Layer kedua sebagai list group -->
                            <ul class="list-group w-100 border-0">
                                @foreach($contents as $content)
                                <li class="list-group-item p-0 border-0 mb-2">
                                    <button class="btn w-100 py-2 content-navigate text-center" type="button"
                                        data-content-id="{{ $content->id }}"
                                        onclick="loadContent({{ $module->id }}, {{ $content->id }})">
                                        Pertemuan {{ $loop->iteration }}
                                    </button>
                                </li>
                                @endforeach
                                <li class="list-group-item p-0 border-0 bg-transparent">
                                    <button class="btn btn-light content-navigate w-100">+</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


                <!-- Konten Kanan -->
                <div class="col">
                    <input type="hidden" name="contentId" class="form-control mb-3" id="contentId"
                        value="{{ $content->id }}">

                    <label for="chapterName" class="form-label">Name Chapter</label>
                    <input type="text" name="chapterName"
                        class="form-control mb-3 @error('chapterName') is-invalid @enderror" id="chapterName"
                        value="{{ old('chapterName') }}">
                    @error('chapterName')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <label for="moduleSummary" class="form-label">Content Summary</label>
                    <textarea id="moduleSummary" name="moduleSummary" class="form-control mb-3 @error('moduleSummary')
                            is-invalid @enderror" style="height: 150px;"></textarea>
                    @error('moduleSummary')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror


                    <label for="moduleContent" class="form-label">Main Content</label>
                    <div class="position-relative mb-3">
                        <div class="position-absolute top-0 end-0 m-2 z-3 d-flex align-items-center">
                            <div class="d-flex justify-content-end mb-2">
                                <div class="rounded bg-primary shadow-container">
                                    <div class="rounded" style="background-color: rgba(255, 255, 255, 0.4);">
                                        <button type="button" class="btn btn-primary active border-0" id="editBtn">
                                            <i class='bx bxs-edit'></i>
                                        </button>
                                        <button type="button" class="btn btn-inactive border-0" id="previewBtn">
                                            <i class='bx bx-show-alt'></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Content -->
                        <div class="preview-content"
                            style="border: 1px solid #ccc; padding: 10px; min-height: 100px;"></div>

                        <!-- Textarea untuk TinyMCE -->
                        <div class="edit-content">
                            <textarea id="moduleContent" name="moduleContent"
                                class="form-control @error('moduleContent') is-invalid @enderror"
                                style="height: 300px;"></textarea>
                        </div>

                        @error('moduleContent')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="position-absolute top-0 end-0 m-2 z-3 d-flex align-items-center">
                        <div class="d-flex justify-content-end mb-2">
                            <div class="rounded bg-primary shadow-container">
                                <div class="rounded" style="background-color: rgba(255, 255, 255, 0.4);">
                                    <!-- Tombol Upload File -->
                                    <button class="btn btn-dark position-fixed bottom-0 end-0 m-4"
                                        data-bs-toggle="modal" data-bs-target="#uploadModal">
                                        <i class="fa fa-upload"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="mt-6 d-flex justify-content-end">
                <button type="reset" class="btn btn-label-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary me-3">Save changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Upload File -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload File Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="file" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</div>
{{-- 
<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Perubahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda ingin menyimpan perubahan sebelum berpindah?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="leaveWithoutSaving">Pindah tanpa menyimpan</button>
                <button type="button" class="btn btn-primary" id="saveAndLeave">Simpan & Pindah</button>
            </div>
        </div>
    </div>
</div> --}}



<script>
    document.addEventListener("DOMContentLoaded", function () {
        const previewBtn = document.getElementById("previewBtn");
        const editBtn = document.getElementById("editBtn");
        const textarea = document.getElementById("moduleContent");
        const previewContent = document.querySelector(".preview-content");
        const editContent = document.querySelector(".edit-content");
        let contentEditor;

        tinymce.init({
            selector: "textarea#moduleContent",
            toolbar: "undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist",
            menubar: false,
            plugins: "autoresize",
            setup: function (editor) {
                contentEditor = editor;
            }
        });

        // Tunggu TinyMCE siap, lalu atur mode awal
        setTimeout(() => {
            if (contentEditor) {
                setMode("readonly");
            }
        }, 500);

        function setMode(mode) {
            if (!contentEditor) {
                console.error("TinyMCE belum siap!");
                return;
            }

            console.log("Mode:", mode);
            if (mode === "readonly") {
                previewContent.innerHTML = contentEditor.getContent() || "<i>Tidak ada konten</i>";
                editContent.classList.add('d-none');
                previewContent.classList.remove('d-none');

                previewBtn.classList.replace("btn-inactive", "btn-primary");
                editBtn.classList.replace("btn-primary", "btn-inactive");
            } else {
                editContent.classList.remove('d-none');
                previewContent.classList.add('d-none');

                previewBtn.classList.replace("btn-primary", "btn-inactive");
                editBtn.classList.replace("btn-inactive", "btn-primary");
            }
        }

        previewBtn.addEventListener("click", () => setMode("readonly"));
        editBtn.addEventListener("click", () => setMode("design"));


    });

    function loadContent(moduleId, contentId) {
        // Tambahkan efek loading (opsional)
        document.getElementById('chapterName').value = "Loading...";
        document.getElementById('moduleSummary').value = "Loading...";
        tinymce.get('moduleContent').setContent("Loading...");

        fetch(`/dashboard/admin/pembelajaran/module/${moduleId}/content/${contentId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert("Content not found!");
                    return;
                }

                document.getElementById('contentForm').action = `/dashboard/admin/pembelajaran/content/${data.id}`;
                document.getElementById('contentId').value = data.id;
                document.getElementById('chapterName').value = data.title;
                document.getElementById('moduleSummary').value = data.summary;

                if (data.content) {
                    tinymce.get('moduleContent').setContent(data.content);
                    document.querySelector(".preview-content").innerHTML = data.content;
                } else {
                    tinymce.get('moduleContent').setContent("");
                    document.querySelector(".preview-content").innerHTML = 'Belum Ada Content';
                }

                document.querySelectorAll('.content-navigate').forEach(button => {
                    if (button.getAttribute('data-content-id') == data.id) {
                        button.classList.remove('btn-light', 'inactive');
                        button.classList.add('btn-primary', 'text-white');
                    } else {
                        button.classList.remove('btn-primary', 'text-white');
                        button.classList.add('btn-light', 'inactive');
                    }
                });
            })
            .catch(error => console.error('Error loading content:', error));
    }

    function toggleMode() {
        let modeButton = document.getElementById('modeButton');
        let contentContainer = document.getElementById('contentContainer');
        let textArea = document.getElementById('editor');



        if (modeButton.innerText === 'Design Mode') {
            modeButton.innerText = 'Readonly Mode';
            textArea.style.display = 'block';
            contentContainer.style.border = 'none';

            tinymce.init({
                selector: '#editor',
                menubar: false
            });

        } else {
            modeButton.innerText = 'Design Mode';
            let editorContent = tinymce.get('editor').getContent();
            textArea.style.display = 'none';
            contentContainer.innerHTML = editorContent;
            contentContainer.style.border = '1px solid #ccc';

            tinymce.remove('#editor'); // Hapus TinyMCE saat beralih ke readonly
        }
    }
</script>
