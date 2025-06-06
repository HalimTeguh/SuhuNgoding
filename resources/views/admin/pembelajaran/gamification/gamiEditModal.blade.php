<!-- Modal Edit -->
<div class="modal fade" id="editGamification" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Edit Gamification Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form Action: Update Data -->
            <form action="/dashboard/gamification/update" method="POST">
                @csrf
                @method('PUT')
                <!-- Menggunakan method PUT untuk update data -->

                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-6">
                            <label for="bloomLevelEdit" class="form-label">Bloom Level</label>
                            <select id="bloomLevelEdit" name="bloom_level"
                                class="form-select @error('bloom_level') is-invalid @enderror">
                                <option value="1">Remember</option>
                                <option value="2">Understand</option>
                                <option value="3">Apply</option>
                                <option value="4">Analyze</option>
                                <option value="5">Evaluate</option>
                                <option value="6">Create</option>
                            </select>
                            @error('bloom_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="pointEdit" class="form-label">Point</label>
                            <input type="number" id="pointEdit" name="point"
                                class="form-control @error('point') is-invalid @enderror" placeholder="Enter point"
                                value="{{ old('point') }}" />
                            @error('point')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="firstAttemptMultiplyEdit" class="form-label">1st Attempt Multiply Bonus</label>
                            <input type="number" step="0.01" id="firstAttemptMultiplyEdit"
                                name="first_attempt_multiply_point"
                                class="form-control @error('first_attempt_multiply_point') is-invalid @enderror"
                                placeholder="Enter first attempt multiply bonus"
                                value="{{ old('first_attempt_multiply_point') }}" />
                            @error('first_attempt_multiply_point')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="secondAttemptMultiplyEdit" class="form-label">2nd Attempt Multiply Bonus</label>
                            <input type="number" step="0.01" id="secondAttemptMultiplyEdit"
                                name="second_attempt_multiply_point"
                                class="form-control @error('second_attempt_multiply_point') is-invalid @enderror"
                                placeholder="Enter second attempt multiply bonus"
                                value="{{ old('second_attempt_multiply_point') }}" />
                            @error('second_attempt_multiply_point')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="thirdAttemptMultiplyEdit" class="form-label">3rd Attempt Multiply Bonus</label>
                            <input type="number" step="0.01" id="thirdAttemptMultiplyEdit"
                                name="third_attempt_multiply_point"
                                class="form-control @error('third_attempt_multiply_point') is-invalid @enderror"
                                placeholder="Enter third attempt multiply bonus"
                                value="{{ old('third_attempt_multiply_point') }}" />
                            @error('third_attempt_multiply_point')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Update changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    // Event listener untuk tombol Edit
document.querySelectorAll('.edit-admin-btn').forEach(button => {
    button.addEventListener('click', function() {
        // Ambil data dari tombol edit
        const id = this.getAttribute('data-id');
        const bloomLevel = parseInt(this.getAttribute('data-bloom_level'));
        const point = this.getAttribute('data-point');
        const firstAttempt = this.getAttribute('data-first_attempt_multiply_point');
        const secondAttempt = this.getAttribute('data-second_attempt_multiply_point');
        const thirdAttempt = this.getAttribute('data-third_attempt_multiply_point');
        
        // Isi modal dengan data yang sesuai
        document.getElementById('pointEdit').value = point;
        document.getElementById('firstAttemptMultiplyEdit').value = firstAttempt;
        document.getElementById('secondAttemptMultiplyEdit').value = secondAttempt;
        document.getElementById('thirdAttemptMultiplyEdit').value = thirdAttempt;

        // Set nilai dropdown Bloom Level sesuai data
        const bloomLevelSelect = document.getElementById('bloomLevelEdit');
        bloomLevelSelect.value = bloomLevel;  // Mengatur nilai dropdown berdasarkan angka

        // Set action form untuk update data
        const form = document.querySelector('#editGamification form');
        form.action = `/dashboard/admin/pembelajaran/gamification/${id}`;  // Set action ke endpoint update
    });
});

</script>