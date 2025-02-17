<!-- Modal Create -->
<div class="modal fade" id="createClass" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="stepperForm" class="bs-stepper">
                    <div class="bs-stepper-header" role="tablist">
                        <div class="step" data-target="#step-1">
                            <button type="button" class="step-trigger d-flex align-items-center">
                                <span class="bs-stepper-box custom-stepper-box bg-primary">1</span>
                                <span class="bs-stepper-label d-flex flex-column ms-3">
                                    <div class="step-title text-primary ">Account Details</div>
                                    <div class="step-subtitle text-secondary">Setup your account</div>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#step-2">
                            <div class="d-flex">
                                <i class="bx bx-chevron-right arrow-icon"></i>
                                <button type="button" class="step-trigger d-flex align-items-center">
                                    <span class="bs-stepper-box custom-stepper-box bg-primary">2</span>
                                    <span class="bs-stepper-label d-flex flex-column ms-3">
                                        <div class="step-title text-primary ">Users Class</div>
                                        <div class="step-subtitle text-secondary">Setup teachers and students in the
                                            classroom</div>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="step" data-target="#step-3">
                            <div class="d-flex">
                                <i class="bx bx-chevron-right arrow-icon"></i>
                                <button type="button" class="step-trigger d-flex align-items-center">
                                    <span class="bs-stepper-box custom-stepper-box bg-primary">3</span>
                                    <span class="bs-stepper-label d-flex flex-column ms-3">
                                        <div class="step-title text-primary ">Confirmation</div>
                                        <div class="step-subtitle text-secondary">Confirm class data</div>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="bs-stepper-content">
                        <!-- Step 1 -->
                        <div id="step-1" class="content" role="tabpanel" aria-labelledby="step-1-trigger">
                            <div class="row ">
                                <!-- Kolom 1: Input Upload Image -->
                                <div class="col-md-4 vertical-divider">
                                    <div class="mb-3">
                                        <label for="imageUpload" class="form-label">Upload Image</label>
                                        <!-- Tempat Preview Gambar -->
                                        <div id="imagePreviewContainer" class="image-preview-container">
                                            <i id="defaultIcon" class="fa-regular fa-image default-preview-icon"></i>
                                            <img id="imagePreview" src="#" alt="Preview" />
                                        </div>
                                        <input type="file" id="imageUpload" name="imageUpload"
                                            class="form-control @error('imageUpload') is-invalid @enderror"
                                            accept="image/*" onchange="previewImage(event)">
                                        @error('imageUpload')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <!-- Kolom 2: Input Name Class dan Deskripsi -->
                                <div class="col-md-8 ps-6">
                                    <div class="mb-3">
                                        <label for="nameCreate" class="form-label">Class Name</label>
                                        <input type="text" id="nameCreate" name="nameCreate"
                                            class="form-control @error('nameCreate') is-invalid @enderror"
                                            placeholder="Enter name" value="{{ old('nameCreate') }}">
                                        @error('nameCreate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3" style="height: 100%;">
                                        <label for="descriptionCreate" class="form-label">Class Description</label>
                                        <textarea id="descriptionCreate" name="descriptionCreate"
                                            class="form-control @error('descriptionCreate') is-invalid @enderror"
                                            placeholder="Enter description">{{ old('descriptionCreate') }}</textarea>
                                        @error('descriptionCreate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>



                            <div class="d-flex justify-content-between mt-10">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="stepperForm.next()">Next</button>
                            </div>

                        </div>


                        <!-- Step 2 -->
                        <div id="step-2" class="content" role="tabpanel" aria-labelledby="step-2-trigger">
                            <!-- Select Teacher -->
                            <div class="mb-3">
                                <label for="teacherCreate" class="form-label">Teacher</label>
                                <select class="form-select py-3" id="teacherCreate" name="teacherCreate">
                                    <option selected class="text-secondary"> Select teacher of the class
                                    </option>
                                    @foreach($teachers as $key => $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                                @error('teacherCreate')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Student Container -->
                            <div class="student-container p-3 border rounded">
                                <!-- Row: Search and Add Student -->
                                <div class="d-flex justify-content-between mb-3">
                                    <input type="text" class="form-control flex-grow-1" id="searchStudent"
                                        placeholder="Search student">
                                    <button type="button" class="btn btn-primary ms-2 py-3" style="white-space: nowrap;"
                                        data-bs-toggle="modal" data-bs-target="#addStudentModal">Invite Student</button>
                                </div>

                                
                                <!-- Dynamic Student List (10 duplicated cards) -->
                                <div id="studentList" class="student-list">
                                    @for ($i = 0; $i < 10; $i++) <div
                                        class="student-card d-flex align-items-center justify-content-between px-5 py-3 mb-1 border rounded">
                                        <div>
                                            <strong>Student Name {{ $i + 1 }}</strong><br>
                                            212233445{{ $i }} - student{{ $i + 1 }}@mail.com
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger">X</button>
                                </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary"
                                onclick="stepperForm.previous()">Previous</button>
                            <button type="button" class="btn btn-primary" onclick="stepperForm.next()">Next</button>
                        </div>
                    </div>


                    <!-- Step 3 -->
                    <div id="step-3" class="content" role="tabpanel" aria-labelledby="step-3-trigger">
                        <div class="mb-3">
                            <label for="descriptionCreate" class="form-label">Description</label>
                            <input type="text" id="descriptionCreate" name="descriptionCreate"
                                class="form-control @error('descriptionCreate') is-invalid @enderror"
                                placeholder="Description" value="{{ old('descriptionCreate') }}" />
                            @error('descriptionCreate')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="button" class="btn btn-secondary"
                            onclick="stepperForm.previous()">Previous</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>




<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi stepper
        window.stepperForm = new Stepper(document.querySelector('#stepperForm'));

    });

    function previewImage(event) {
        const imagePreview = document.getElementById('imagePreview');
        const defaultIcon = document.getElementById('defaultIcon');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block'; // Menampilkan gambar
                defaultIcon.style.display = 'none'; // Menyembunyikan ikon default
            };
            reader.readAsDataURL(file); // Membaca file sebagai URL
        } else {
            imagePreview.style.display = 'none'; // Sembunyikan gambar jika tidak ada file
            defaultIcon.style.display = 'block'; // Tampilkan ikon default
        }
    }

    let selectedStudents = [];

    
    // Function to simulate adding student to the list
    function addSelectedStudent() {
        const studentName = "Sample Student"; // Replace this with actual search result selection
        const studentEmail = "sample@student.com";

        // Check if student is already in the list
        if (selectedStudents.some(student => student.email === studentEmail)) {
            alert("Student is already in the list!");
            return;
        }

        // Add student to selectedStudents array
        selectedStudents.push({ name: studentName, email: studentEmail });

        // Add student card to the student list
        const studentList = document.getElementById("studentList");
        const studentCard = `
            <div class="student-card d-flex align-items-center justify-content-between px-5 py-3 mb-1 border rounded">
                <div>
                    <strong>${studentName}</strong><br>
                    ${studentEmail}
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeStudent('${studentEmail}')">X</button>
            </div>
        `;
        studentList.innerHTML += studentCard;

        // Close the modal
        const addStudentModal = bootstrap.Modal.getInstance(document.getElementById("addStudentModal"));
        addStudentModal.hide();
    }

    // Function to remove student from the list
    function removeStudent(email) {
        // Remove student from the array
        selectedStudents = selectedStudents.filter(student => student.email !== email);

        // Update the UI
        const studentList = document.getElementById("studentList");
        studentList.innerHTML = '';
        selectedStudents.forEach(student => {
            studentList.innerHTML += `
                <div class="student-card d-flex align-items-center justify-content-between px-5 py-3 mb-1 border rounded">
                    <div>
                        <strong>${student.name}</strong><br>
                        ${student.email}
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeStudent('${student.email}')">X</button>
                </div>
            `;
        });
    }
    
</script>