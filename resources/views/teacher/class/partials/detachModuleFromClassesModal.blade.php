<!-- Modal Detach Module -->
<div class="modal fade" id="detachModuleModal" tabindex="-1" aria-labelledby="detachModuleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="detachModuleForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Remove Module from Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Are you sure you want to remove <strong id="detachModuleName">this module</strong> from this
                        class?
                        <br>
                        <span class="text-danger small">
                            All student progress data related to this module will be permanently deleted.
                        </span>
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
    document.addEventListener('DOMContentLoaded', function(){
        const detachModal = document.getElementById('detachModuleModal');

        detachModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const moduleId = button.getAttribute('data-module-id');
            const moduleTitle = button.getAttribute('data-module-title');
            const form = document.getElementById('detachModuleForm');
            const nameHolder = document.getElementById('detachModuleName');

            // Ganti action form
            form.action = `/dashboard/teacher/pembelajaran/class/{{ $class->id }}/modules/${moduleId}`;

            // Ganti teks modul
            nameHolder.textContent = moduleTitle;
        });
    })

</script>