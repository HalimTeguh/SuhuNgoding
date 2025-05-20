<!-- Modal attach Module -->
<div class="modal fade" id="addModuleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form method="POST" action="{{ route('dashboard.class.attachModule', $class->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Module to Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        @foreach($availableModules as $module)
                        <div class="col-md-6 col-xl-4">
                            <div class="card selectable-card h-100" data-id="{{ $module->id }}">
                                <input type="checkbox" name="module_ids[]" value="{{ $module->id }}"
                                    class="d-none real-checkbox" />

                                <div class="card-body d-flex flex-column">
                                    <img src="{{ asset('storage/' . $module->image) }}" alt="Module Image"
                                        class="rounded mb-2"
                                        style="height: 120px; object-fit: cover; width: 100%; border-radius: .5rem;">

                                    <h6 class="mb-1">{{ $module->title }}</h6>
                                    <small class="text-muted mb-2">{{ $module->teacher->user->name ?? '-' }}</small>

                                    <p class="card-text text-truncate"
                                        style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $module->description }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Selected Modules</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        const cards = document.querySelectorAll('.selectable-card');

        // Select card module handler 
        cards.forEach(function (card) {
            const checkbox = card.querySelector('input[type="checkbox"]');

            card.addEventListener('click', function (e) {
                // Jika klik bukan pada checkbox langsung, toggle manual
                if (e.target !== checkbox) {
                    checkbox.checked = !checkbox.checked;
                }

                // Tambah/hapus class active
                card.classList.toggle('active', checkbox.checked);
            });

            // Atur status awal (jika checkbox sudah ter-check karena error back/old input)
            if (checkbox.checked) {
                card.classList.add('active');
            }
        });
    })
</script>