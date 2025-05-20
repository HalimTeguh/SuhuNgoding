@extends('layout.dashboard')

@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row fv-plugins-icon-container">
            <div class="col-md-12">
                <div class="nav-align-top">
                    <h5 id="entityHeader" class="m-0 d-none">Module</h5>

                    <ul class="nav nav-pills flex-column flex-md-row mb-6">
                        <li class="nav-item"><a class="nav-link active" href="javascript:void(0);" data-tab="Basic"><i
                                    class="bx bx-sm bx-spreadsheet me-1_5"></i> Basic</a></li>
                        <li class="nav-item"><a class="nav-link" href="javascript:void(0);" data-tab="Content"><i
                                    class="bx bx-sm bx-book-content me-1_5"></i> Content</a></li>
                        <li class="nav-item"><a class="nav-link" href="javascript:void(0);" data-tab="Quiz"><i
                                    class="bx bx-sm bx-poll me-1_5"></i> Quiz</a></li>
                    </ul>
                </div>

                @include('admin.pembelajaran.module.pages.basicModuleSection')
                @include('admin.pembelajaran.module.pages.contentModuleSection')
                @include('admin.pembelajaran.module.pages.quizModuleSection')

                <div class="card deleteSection">
                    <h5 class="card-header">Delete Account</h5>
                    <div class="card-body">
                        <div class="mb-6 col-12 mb-0">
                            <div class="alert alert-warning">
                                <h5 class="alert-heading mb-1">Are you sure you want to delete your account?</h5>
                                <p class="mb-0">Once you delete your account, there is no going back. Please be certain.
                                </p>
                            </div>
                        </div>
                        <form id="formAccountDeactivation" onsubmit="return false"
                            class="fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                            <div class="form-check my-8 ms-2">
                                <input class="form-check-input" type="checkbox" name="accountActivation"
                                    id="accountActivation">
                                <label class="form-check-label" for="accountActivation">I confirm my account
                                    deactivation</label>
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-danger deactivate-account" disabled="">Deactivate
                                Account</button>
                            <input type="hidden">
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <hr class="my-4" />

    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>

<div class="modal fade" id="confirmResetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmResetModalLabel">Confirm Reset Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to reset the image? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmResetImage()">Reset</button>
            </div>
        </div>
    </div>
</div>


<script>
    var moduleId = {{ $module->id }};

    document.addEventListener("DOMContentLoaded", function () { 
        const tabs = document.querySelectorAll(".nav-link");
        const deleteSections = document.querySelectorAll(".deleteSection"); // NodeList
        const sections = {
            "Basic": document.querySelector(".basic-module"),
            "Content": document.querySelector(".content-module"),
            "Quiz": document.querySelector(".quiz-module"),
        };
        const urlParams = new URLSearchParams(window.location.search);

        // Ambil tab dan pertemuan terakhir yang disimpan di Local Storage
        let activeTab = localStorage.getItem("activeTab") || "Basic";
        let activeContent = localStorage.getItem("activeContent");

        // Sembunyikan semua tab kecuali Basic di awal
        Object.values(sections).forEach(section => section.style.display = "none");
        sections[activeTab].style.display = "block";

        // Pastikan deleteSection hanya muncul jika tab Basic ditampilkan
        deleteSections.forEach(section => {
            if (activeTab === "Basic") {
                section.classList.remove("d-none");
            } else {
                section.classList.add("d-none");
            }
        });

        function showTab(tabName) {
            tabs.forEach(t => t.classList.remove("active"));
            Object.values(sections).forEach(section => section.style.display = "none");

            if (sections[tabName]) {
                sections[tabName].style.display = "block";
                document.querySelector(`[data-tab="${tabName}"]`).classList.add("active");

                // Simpan tab yang diklik ke Local Storage
                localStorage.setItem("activeTab", tabName);
            }

            console.log("tab: ", tabName);

            // Tampilkan deleteSection hanya saat Basic tab aktif
            if (tabName === "Basic") {
                deleteSections.forEach(section => section.classList.remove("d-none"));
            } else {
                deleteSections.forEach(section => section.classList.add("d-none"));
            }

            // Jika masuk ke tab Content, load pertemuan terakhir yang aktif
            if (tabName === "Content") {
                let contentId = activeContent || 1; // Default ke pertemuan 1 jika belum ada yang disimpan
                loadContent(moduleId, contentId);
            }

            // Jika masuk ke tab Content, load pertemuan terakhir yang aktif
            if (tabName === "Content") {
                let quizId = activeContent || 1; // Default ke pertemuan 1 jika belum ada yang disimpan
                loadQuiz(moduleId, contentId);
            }
        }

        // Tambahkan event listener untuk menyimpan tab yang dipilih
        tabs.forEach(tab => {
            tab.addEventListener("click", function () {
                const targetTab = this.getAttribute("data-tab");
                showTab(targetTab);
            });
        });

        // Event listener untuk menyimpan pertemuan yang diklik
        document.querySelectorAll(".content-navigate").forEach(button => {
            button.addEventListener("click", function () {
                let contentId = this.getAttribute("data-content-id");
                localStorage.setItem("activeContent", contentId);
                loadContent(moduleId, contentId);
            });
        });

        // Tampilkan tab terakhir yang dikunjungi
        showTab(activeTab);
    });


    function uploadImage(event) {
        const file = event.target.files[0];

        if (!file) return;

        // Validasi format dan ukuran file
        const validTypes = ["image/png", "image/jpeg", "image/gif"];
        if (!validTypes.includes(file.type)) {
            alert("Invalid file format. Please upload JPG, PNG, or GIF.");
            return;
        }
        if (file.size > 800000) {
            alert("File size exceeds 800KB.");
            return;
        }

        let formData = new FormData();
        formData.append('imageUpload', file);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        $.ajax({
            url: '/dashboard/admin/pembelajaran/module/{{ $module->id }}/uploadImage',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                location.reload(); // Refresh halaman setelah upload berhasil
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("Upload failed, please try again.");
            }
        });
    }

    function confirmResetImage() {
        $.ajax({
            url: '/dashboard/admin/pembelajaran/module/{{ $module->id }}/resetImage',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PUT'
            },
            success: function(response) {
                $('#imagePreviewModul').attr('src', '').addClass('d-none'); // Sembunyikan gambar
                $('#ImageIconModul').removeClass('d-none'); // Tampilkan ikon default
                $('#confirmResetModal').modal('hide'); // Tutup modal
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("Gagal mereset gambar.");
            }
        });
    }

</script>

@endsection