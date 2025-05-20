<!-- Modal Delete -->
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
    function showResetConfirmation() {
        // Menampilkan modal konfirmasi
        const modal = new bootstrap.Modal(document.getElementById('confirmResetModal'));
        modal.show();
    }

    function confirmResetImage() {
        const input = document.getElementById('imageUpload');
        const preview = document.getElementById('imagePreviewEdit');
        const icon = document.getElementById('defaultIcon');
        document.getElementById('isImageReset').value = "true";


        input.value = ""; // Reset input file
        preview.src = "#";
        preview.classList.add("d-none"); // Sembunyikan gambar
        icon.classList.remove('d-none');

        // Menutup modal setelah reset
        const modal = bootstrap.Modal.getInstance(document.getElementById('confirmResetModal'));
        modal.hide();
    }
</script>
