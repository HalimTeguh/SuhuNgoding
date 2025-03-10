<div class="card mb-6 basic-module">
    <!-- basix -->
    <div class="card-body ">
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
                    <button type="button" class="btn btn-label-secondary account-image-reset mb-4"
                        data-bs-toggle="modal" data-bs-target="#confirmResetModal">
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
            <div class="mt-6 d-flex justify-content-end">
                <button type="reset" class="btn btn-label-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary me-3">Save changes</button>
            </div>
            <input type="hidden">
        </form>
    </div>
    <!-- /Account -->
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: 'textarea#descriptionEdit', // Replace this CSS selector to match the placeholder element for TinyMCE
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist',
            menubar: false
        });

        
    });
</script>