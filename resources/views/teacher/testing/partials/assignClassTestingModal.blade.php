<!-- Modal Assign Class -->
<div class="modal fade" id="assignClassModal" tabindex="-1" aria-labelledby="assignClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('dashboard.teacher.testing.assignClass') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="assignClassModalLabel">Assign Kelas & Modul untuk T-Testing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    {{-- Dropdown Kelas --}}
                    <div class="mb-3">
                        <label for="classSelectModal" class="form-label">Pilih Kelas</label>
                        <select class="form-select" id="classSelectModal" name="class_id" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($allClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dropdown Modul (Tersembunyi dulu) --}}
                    <div class="mb-3 d-none" id="moduleWrapper">
                        <label for="moduleSelectModal" class="form-label">Pilih Modul Pembelajaran</label>
                        <select class="form-select" id="moduleSelectModal" name="module_id" required>
                            <option value="">-- Pilih Modul --</option>
                            {{-- Diisi lewat JavaScript --}}
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const allModules = @json($allClasses->load('modules')->mapWithKeys(function($class) {
        return [$class->id => $class->modules->map(fn($m) => ['id' => $m->id, 'title' => $m->title])];
    }));

    document.addEventListener('DOMContentLoaded', () => {
        const classSelect = document.getElementById('classSelectModal');
        const moduleWrapper = document.getElementById('moduleWrapper');
        const moduleSelect = document.getElementById('moduleSelectModal');

        classSelect.addEventListener('change', () => {
            const classId = classSelect.value;
            const modules = allModules[classId] || [];

            // Kosongkan dulu dropdown modul
            moduleSelect.innerHTML = '<option value="">-- Pilih Modul --</option>';

            if (modules.length > 0) {
                moduleWrapper.classList.remove('d-none');

                modules.forEach(mod => {
                    const option = document.createElement('option');
                    option.value = mod.id;
                    option.textContent = mod.title;
                    moduleSelect.appendChild(option);
                });
            } else {
                moduleWrapper.classList.add('d-none');
            }
        });
    });
</script>
