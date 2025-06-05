<!-- Modal Delete Gamification -->
<div class="modal fade" id="deleteGamification" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Gamification Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteGamificationForm" action="" method="POST">
                @csrf
                @method('DELETE') <!-- Menggunakan method DELETE untuk menghapus data -->

                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteGamificationName"></strong>?</p>
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
    // Event listener untuk tombol Delete
document.querySelectorAll('.delete-gamification-btn').forEach(button => {
    button.addEventListener('click', function() {
        // Ambil data dari tombol delete
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        
        // Isi modal dengan data yang sesuai
        document.getElementById('deleteGamificationName').textContent = name;

        // Set action form untuk delete data
        const form = document.querySelector('#deleteGamificationForm');
        form.action = `/dashboard/admin/pembelajaran/gamification/${id}`;  // Set action ke endpoint delete
    });
});
</script>