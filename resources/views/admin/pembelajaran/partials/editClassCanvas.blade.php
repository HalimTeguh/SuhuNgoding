<div class="offcanvas offcanvas-end" tabindex="-1" id="editClass" aria-labelledby="offcanvasEndLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasEndLabel" class="offcanvas-title">Edit Class</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body  mx-0 flex-grow-0">
        <form id="classForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-6 text-center">
                        <!-- Image Preview Container -->
                        <div id="imagePreviewContainer" class="image-preview-container">
                            <i id="defaultIcon"
                                class="fa-regular fa-image default-preview-icon {{ ($class->image) ? 'd-none' : '' }}"></i>
                            <img id="imagePreviewEdit"
                                class="img-fluid image-preview-edit {{ ($class->image) ? '' : 'd-none' }}"
                                src="{{ $class->image ? asset('storage/' . $class->image) : '#' }}" alt="Preview" />
                        </div>

                        <!-- Hidden File Input -->
                        <input type="file" id="imageUpload" name="imageUpload" class="d-none" accept="image/*"
                            onchange="previewImage(event)">

                        <!-- Buttons -->
                        <div class="mt-2">
                            <button type="button" class="btn btn-primary"
                                onclick="document.getElementById('imageUpload').click();">
                                {{ $class->image ? "Change" : "Upload" }}
                            </button>
                            <button type="button" class="btn btn-danger" onclick="showResetConfirmation();">
                                Reset
                            </button>
                            <input type="hidden" id="isImageReset" name="isImageReset" value="false">
                        </div>

                        @error('imageUpload')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-6">
                        <label for="nameEdit" class="form-label">Full Name</label>
                        <input type="text" id="nameEdit" name="nameEdit"
                            class="form-control @error('nameEdit') is-invalid @enderror" placeholder="Enter name"
                            value="{{ $class->name }}" />
                        @error('nameEdit')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-6">
                        <label for="teacherCreate" class="form-label">Select Teacher</label>
                        <select class="form-select" id="teacherCreate" name="teacherCreate">
                            <option value="" class="text-secondary"> Select teacher of the class
                            </option>
                            @foreach($allTeacher as $key => $selectTeacher)
                            <option value="{{ $selectTeacher->id }}" {{ ($selectTeacher->id == $teacher->user->id) ?
                                'selected' : '' }}>{{ $selectTeacher->name }}</option>
                            @endforeach
                        </select>
                        @error('teacherCreate')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-6">
                        <label for="descriptionEdit" class="form-label">Description Class</label>
                        <textarea id="descriptionEdit" name="descriptionEdit"
                            class="form-control @error('descriptionEdit') is-invalid @enderror"
                            placeholder="Enter description">{{ $class->description }}</textarea>
                        @error('descriptionEdit')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mb-2 d-grid w-100">Save</button>
            <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas">
                Cancel
            </button>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('imagePreviewEdit'); // Sesuai dengan id di HTML
        const icon = document.getElementById('defaultIcon');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = "block";
                preview.classList.remove("d-none"); // Menampilkan gambar
                icon.classList.add("d-none"); // Menyembunyikan ikon default
                document.getElementById('isImageReset').value = "false";
            };
            reader.readAsDataURL(file);
        }
    }

</script>