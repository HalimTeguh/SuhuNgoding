<!-- Modal Add Student -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Search by Email or NIS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control mb-3" id="searchStudentModal"
                    placeholder="Search by Email or NIS...">
                <div id="studentSearchResults">
                    <!-- Placeholder for dynamic student search results -->

                    <div id="studentListModal" class="student-list">
                        @foreach ($students as $student)
                            <div class="student-card d-flex align-items-center justify-content-between px-3 py-2 mb-1 border rounded" data-email="{{ $student->email }}" data-nis="{{ $student->NIS }}">
                                <div>
                                    <strong>{{ $student->name }}</strong><br>
                                    {{ $student->NIS }} - {{ $student->email }}
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addStudentToList({{ $student->id }}, '{{ $student->name }}', '{{ $student->NIS }}', '{{ $student->email }}')">Add</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addSelectedStudent()">Add Student</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchStudentModal').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        console.log(searchValue);
        const students = document.querySelectorAll('#studentListModal .student-card');

        students.forEach(student => {
            const studentEmail = student.getAttribute('data-email').toLowerCase();
            const studentNis = student.getAttribute('data-nis').toLowerCase();

            // Jika nilai pencarian cocok dengan email atau NIS
            if (studentEmail.includes(searchValue) || studentNis.includes(searchValue)) {
                student.style.display = 'block'; // Tampilkan
            } else {
                student.style.display = 'none'; // Sembunyikan
            }
        });
    });
</script>


