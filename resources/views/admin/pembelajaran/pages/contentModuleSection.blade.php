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

                    <input type="hidden" name="source_uuid" class="form-control mb-3" id="source_uuid" value="">

                    <input type="hidden" name="source_type" class="form-control mb-3" id="source_type" value="">

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
                        <div class="position-absolute top-0 end-0 m-2 z-3 d-flex align-items-center gap-2">
                            <!-- Tombol Upload (Import) -->
                            <button class="btn btn-secondary shadow-container" type="button" data-bs-toggle="modal"
                                data-bs-target="#uploadModal">
                                <i class="fa fa-upload"></i>
                            </button>

                            <!-- Tombol Edit dan Preview -->
                            <div class="rounded bg-primary shadow-container">
                                <div class="rounded d-flex" style="background-color: rgba(255, 255, 255, 0.4);">
                                    <button type="button" class="btn btn-primary active border-0" id="editBtn">
                                        <i class='bx bxs-edit'></i>
                                    </button>
                                    <button type="button" class="btn btn-inactive border-0" id="previewBtn">
                                        <i class='bx bx-show-alt'></i>
                                    </button>
                                </div>
                            </div>
                        </div>


                        <!-- Preview Content -->
                        <div class="preview-content" style="border: 1px solid #ccc; padding: 10px; min-height: 100px;">
                        </div>

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

                    <div class="form-check d-flex align-items-center gap-2 mt-4 mb-4">
                        <input class="form-check-input" type="checkbox" id="generate_quiz" name="generate_quiz" value="1">
                        <label class="form-check-label mb-0" for="generate_quiz">
                            Apakah Anda ingin Generate Quiz Otomatis dari Konten?
                        </label>
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
                <input type="file" class="form-control" id="fileInput" accept=".pdf, .docx">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="uploadBtn" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</div>

<div id="loadingIndicator" style="display: none; text-align: center;">
    <div class="spinner"></div>
    <p>Mengonversi file, harap tunggu...</p>
</div>

<div id="dynamic-toast-container" class="toast-container position-fixed top-0 end-0 p-3 mt-2 me-2"
    style="z-index: 1100;"></div>


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


<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.8/mammoth.browser.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-okaidia.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-python.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    let activeToast = null;
    document.addEventListener("DOMContentLoaded", function () {
        const previewBtn = document.getElementById("previewBtn");
        const editBtn = document.getElementById("editBtn");
        const textarea = document.getElementById("moduleContent");
        const previewContent = document.querySelector(".preview-content");
        const editContent = document.querySelector(".edit-content");
        let contentEditor;

        tinymce.init({
            selector: 'textarea#moduleContent',
            plugins: [
                'autoresize', 'image', 'link', 'lists', 'code', 'table'
            ],
            height: 1000,
            toolbar: `
                undo redo | blocks | bold italic underline strikethrough |
                alignleft aligncenter alignright alignjustify |
                bullist numlist outdent indent | link image | code
            `,
            branding: false,
            menubar: "insert format table",
            contextmenu: "link blocks image | bold italic underline",
            paste_data_images: true,
            images_upload_url: '/upload-image',
            automatic_uploads: true,

            content_style: `
                body { font-family:Helvetica,Arial,sans-serif; font-size:14px }
                .code-block {
                    background: #2d2d2d;
                    color: #f8f8f2;
                    padding: 15px;
                    border-radius: 5px;
                    font-family: 'Consolas', 'Courier New', monospace;
                    font-size: 14px;
                    line-height: 1.5;
                    margin: 10px 0;
                    overflow-x: auto;
                    white-space: pre-wrap;
                }
                .code-keyword { color: #f92672; font-weight: bold; }
            `,

            init_instance_callback: function (editor) {
                contentEditor = editor;
                if (typeof setMode === "function") {
                    setMode("readonly");
                } else {
                    console.warn("setMode() tidak tersedia saat TinyMCE siap.");
                }
            }
        });

        contentEditor = tinymce.get('moduleContent');

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

    document.getElementById('uploadBtn').addEventListener('click', async function () {
        const fileInput = document.getElementById('fileInput');
        const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
        const file = fileInput.files[0];

        if (!file) {
            closePersistentToast();
            showToast("Pilih file terlebih dahulu!", 'error');
            return;
        }

        const fileName = file.name.toLowerCase();
        const fileExt = fileName.split('.').pop();

        // Tutup modal saat tombol upload diklik
        if (modal) modal.hide();

        if (fileExt === 'docx') {
            readDocxFile(file); // langsung mammoth.js
            document.getElementById('source_type').value = "pdf";
            fileInput.value = ''; // reset input setelah sukses
            return;
        }

        if (fileExt !== 'pdf') {
            closePersistentToast();
            showToast("Format file tidak didukung. Harap unggah file .docx atau .pdf", 'error');
            return;
        }

        // Upload dan mulai async convert PDF
        const formData = new FormData();
        formData.append('file', file);

        showPersistentToast("Proses konversi dimulai. Mohon tunggu...", "info", "Mengonversi");

        try {
            const response = await fetch('/api/convert-pdf-to-md', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Upload gagal.');
            }

            const { uuid } = await response.json();
            // showToast("File diterima. Menunggu hasil konversi...", "info");
            closePersistentToast();
            showPersistentToast("File diterima. Menunggu hasil konversi...", "info", "Mengonversi");

            let pollingStart = Date.now();

            // Polling status async
            const poll = async () => {
                if (Date.now() - pollingStart > 15 * 60 * 1000) { // 15 menit
                    closePersistentToast();
                    showToast("Waktu konversi melebihi batas. Silakan coba lagi.", "error");
                    fileInput.value = '';
                    return;
                }
                try {
                    const res = await fetch(`/api/convert-status/${uuid}`);
                    const { status, error } = await res.json();

                    if (status === 'done') {
                        const resultRes = await fetch(`/api/convert-result/${uuid}`);
                        const result = await resultRes.json();

                        const baseImageUrl = `/storage/convert2md/output/${result.uuid}`;
                        const markdown = Array.isArray(result.images) && result.images.length > 0
                            ? fixImagePaths(result.markdown, baseImageUrl)
                            : result.markdown;
                        
                        document.getElementById('source_uuid').value = result.uuid;
                        document.getElementById('source_type').value = "pdf";
                        
                        const html = window.marked.parse(markdown);
                        tinymce.get('moduleContent').setContent(html);

                        closePersistentToast();
                        showToast("Konversi PDF berhasil dan dimuat ke editor!", "success");
                        fileInput.value = '';
                    } else if (status === 'failed') {
                        closePersistentToast();
                        showToast("Konversi gagal. Silakan coba lagi.", "error");
                        fileInput.value = '';
                    } else {
                        setTimeout(poll, 10000);
                    }
                } catch (e) {
                    closePersistentToast();
                    showToast("Gagal mengambil status konversi. Coba lagi nanti.", "error");
                    console.error("Polling error:", e);
                    fileInput.value = '';
                }
            };


            poll();

        } catch (err) {
            console.error("Fetch failed:", err);
            closePersistentToast();
            showToast("Gagal mengunggah atau memproses PDF. " + err.message, "error");
            fileInput.value = '';
        }
    });


    async function readDocxFile(file) {
        const reader = new FileReader();
        reader.onload = async function (e) {
            try {
                const arrayBuffer = e.target.result;
                const result = await mammoth.convertToHtml({ arrayBuffer: arrayBuffer });
                const html = cleanHtmlForTinyMCE(result.value);

                tinymce.get('moduleContent').setContent(html);
                alert("File berhasil diimpor ke editor!");

            } catch (error) {
                console.error("DOCX Error:", error);
                alert("Gagal memproses file DOCX: " + error.message);
            }
        };
        reader.readAsArrayBuffer(file);
    }

    function fixImagePaths(markdown, baseUrl) {
        return markdown.replace(/!\[\]\(([^)]+)\)/g, (match, filename) => {
            return `![](${baseUrl}/${filename})`;
        });
    }

    function showToast(message, type = 'info', title = 'Notifikasi') {
        const container = document.getElementById('dynamic-toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0 show mb-2`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}:</strong> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        container.appendChild(toast);

        const bsToast = new bootstrap.Toast(toast, { delay: 5000 });
        bsToast.show();

        // Hapus setelah sembunyi
        setTimeout(() => toast.remove(), 5500);
    }

    function showPersistentToast(message, type = 'info', title = 'Notifikasi') {
        const container = document.getElementById('dynamic-toast-container');
        if (!container) return;

        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0 show mb-2`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}:</strong> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        container.appendChild(toast);

        activeToast = new bootstrap.Toast(toast, { autohide: false });
        activeToast.show();
    }


    function closePersistentToast() {
        if (activeToast) {
            activeToast.hide();
            activeToast = null;
        }
    }


    function cleanHtmlForTinyMCE(html) {
        html = html.replace(/<table[^>]*>[\s\S]*?<tr>\s*<td>([\s\S]*?)<\/td>\s*<\/tr>[\s\S]*?<\/table>/gi, (match, tableContent) => {
            // Ambil semua baris <p> dalam tableContent
            const lines = [];
            const pTagRegex = /<p[^>]*>([\s\S]*?)<\/p>/gi;
            let matchP;

            while ((matchP = pTagRegex.exec(tableContent)) !== null) {
                let line = matchP[1]
                    .replace(/<span[^>]*class="code-keyword"[^>]*>(.*?)<\/span>/gi, '$1')
                    .replace(/&nbsp;/g, ' ')
                    .replace(/&lt;/g, '<')
                    .replace(/&gt;/g, '>')
                    .replace(/&amp;/g, '&')
                    .replace(/<[^>]+>/g, '')
                    .trim();
                lines.push(line);
            }

            // Gabungkan baris-baris menjadi satu blok kode
            const codeBlock = lines.join('\n');
            return `<div class="code-editor"><pre><code>${codeBlock}</code></pre></div>`;
        });

        // Bersihkan sisa HTML
        return html
            .replace(/<em>(.*?)<\/em>/g, '<span class="code-keyword">$1</span>')
            .replace(/<!--[\s\S]*?-->/g, '')
            .replace(/<p[^>]*>Heading\s(\d+)<\/p>/gi, (match, p1) => `<h${p1}>`)
            .replace(/ style="[^"]*"/g, '')
            .replace(/<br>/g, '\n')
            .replace(/<o:p>|<\/o:p>/g, '');
    }


</script>