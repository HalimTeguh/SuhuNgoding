<!-- Modal Detach Student -->
<div class="modal fade" id="detachStudentModal" tabindex="-1" aria-labelledby="detachStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="detachStudentForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remove Student from Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Are you sure you want to remove <strong id="detachStudentName">this student</strong> from this class?
                    </p>
                    <p class="text-danger small">
                        All learning progress data of <strong id="detachStudentNameRepeat">this student</strong> in this class will also be permanently deleted.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Remove</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const detachModal = document.getElementById('detachStudentModal');

        detachModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const studentId = button.getAttribute('data-student-id');
            const studentName = button.getAttribute('data-student-name');
            const form = document.getElementById('detachStudentForm');

            // Ganti action form ke endpoint yang sesuai
            form.action = `/dashboard/teacher/pembelajaran/class/{{ $class->id }}/students/${studentId}`;

            // Tampilkan nama di dua tempat dalam modal
            document.getElementById('detachStudentName').textContent = studentName;
            document.getElementById('detachStudentNameRepeat').textContent = studentName;
        });
    });
</script>
