<!-- Modal Delete -->
<div class="modal fade" id="deleteAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Data Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteAdminForm" action="/dashboard/admin/users/admin/s/{admin}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteAdminName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
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
        const deleteModal = document.getElementById('deleteAdmin');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const adminId = button.getAttribute('data-id');
            const adminName = button.getAttribute('data-name');

            // Ganti teks konfirmasi
            const nameDisplay = deleteModal.querySelector('#deleteAdminName');
            nameDisplay.textContent = adminName;

            // Ganti form action
            const form = deleteModal.querySelector('#deleteAdminForm');
            form.action = `/dashboard/admin/users/admin/s/${adminId}`;
        });
    });
</script>