@extends('layout.dashboard')

@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row fv-plugins-icon-container">
            <div class="col-md-12">
                <div class="nav-align-top">
                    <ul class="nav nav-pills flex-column flex-md-row mb-6">
                        <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                                    class="bx bx-sm bx-user me-1_5"></i> Basic</a></li>
                        <li class="nav-item"><a class="nav-link" href="javascript:void(0);"><i
                                    class="bx bx-sm bx-lock-alt me-1_5"></i> Content</a></li>
                        <li class="nav-item"><a class="nav-link" href="javascript:void(0);"><i
                                    class="bx bx-sm bx-detail me-1_5"></i> Quiz</a></li>
                    </ul>
                </div>
                <div class="card mb-6">
                    <!-- Account -->
                    <div class="card-body">

                        <div
                            class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom justify-content-start">
                            <div id="PreviewModuleContainer" class="preview-module-container">
                                <img id="imagePreviewModul"
                                    class="img-fluid rounded preview-module-container {{ ($module->image) ? '' : 'd-none' }}"
                                    src="{{ $module->image ? asset('storage/'. $module->image) : '' }}"
                                    alt="Module Image">
                                <i id="ImageIconModul"
                                    class="fa-regular fa-image preview-module-icon {{ ($module->image) ? 'd-none' : '' }}"></i>
                            </div>

                            <div class="button-wrapper">
                                <form id="imageUploadForm" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="file" id="imageUpload" name="imageUpload" class="d-none"
                                        accept="image/png, image/jpeg, image/gif" onchange="uploadImage(event)">
                                    <button type="button" class="btn btn-primary me-3 mb-4"
                                        onclick="document.getElementById('imageUpload').click();">
                                        Upload new photo
                                    </button>
                                    <button type="button" class="btn btn-label-secondary account-image-reset mb-4" data-bs-toggle="modal" data-bs-target="#confirmResetModal">
                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Reset</span>
                                    </button>

                                    <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <form id="formAccountSettings" action="/dashboard/admin/pembelajaran/module/{{ $module->id }}"
                            method="POST" class="fv-plugins-bootstrap5 fv-plugins-framework">
                            @csrf
                            @method('put')
                            <div class="row g-6">
                                <div class="col-md-12 fv-plugins-icon-container">
                                    <label for="titleEdit" class="form-label">Title Module</label>
                                    <input class="form-control" type="text" id="titleEdit" name="titleEdit"
                                        placeholder="Basic programming" autofocus="" value="{{ $module->title }}">
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="teacher">Teacher</label>
                                    <div class="position-relative">
                                        <select class="form-select" id="teacherEdit" name="teacherEdit">
                                            <option value="" class="text-secondary"> Select teacher of the
                                                module
                                            </option>
                                            @foreach($allTeacher as $key => $teacher)
                                            <option value="{{ $teacher->id }}" {{ ($teacher->id ==
                                                $module->teacher->user->id) ? 'selected' : '' }}>{{ $teacher->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('teacherEdit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="statusEdit" class="form-label">Status</label>
                                    <select class="form-select" id="statusEdit" name="statusEdit">
                                        <option value="1" {{ $module->status == 1 ? 'selected' : '' }}>Public</option>
                                        <option value="2" {{ $module->status == 2 ? 'selected' : '' }}>Private</option>
                                        <option value="3" {{ $module->status == 3 ? 'selected' : '' }}>Pending</option>
                                    </select>
                                    @error('statusEdit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label" for="description">Summary</label>
                                    <div class="position-relative">
                                        <textarea id="descriptionEdit" name="descriptionEdit" rows="5"
                                            class="form-control @error('descriptionEdit') is-invalid @enderror"
                                            placeholder="Description">{{ $module->description }}</textarea>
                                        @error('descriptionEdit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <div class="mt-6">
                                <button type="submit" class="btn btn-primary me-3">Save changes</button>
                                <button type="reset" class="btn btn-label-secondary">Cancel</button>
                            </div>
                            <input type="hidden">
                        </form>
                    </div>
                    <!-- /Account -->
                </div>
                <div class="card">
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
    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: 'textarea#descriptionEdit', // Replace this CSS selector to match the placeholder element for TinyMCE
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist',
            menubar: false
        });
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