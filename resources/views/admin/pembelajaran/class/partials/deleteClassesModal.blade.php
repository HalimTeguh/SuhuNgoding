<!-- Modal Delete -->
<div class="modal fade" id="deleteClass" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="deleteClassForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteClassName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteClassModal = document.getElementById('deleteClass');
        deleteClassModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const classId = button.getAttribute('data-id');
            const className = button.getAttribute('data-name');
    
            // Set teks nama kelas yang akan dihapus
            deleteClassModal.querySelector('#deleteClassName').textContent = className;
    
            // Update form action URL
            const form = deleteClassModal.querySelector('#deleteClassForm');
            form.action = `/dashboard/admin/pembelajaran/class/s/${classId}`;
            console.log("Form action set to:", form.action);
        });
    });
</script>