<!-- Modal Create -->
<div class="modal fade" id="createGamification" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Create New Gamification Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/dashboard/admin/pembelajaran/gamification/" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-6">
                            <label for="bloomLevelCreate" class="form-label">Bloom Level</label>
                            <select id="bloomLevelCreate" name="bloom_level" class="form-select @error('bloom_level') is-invalid @enderror">
                                <option value="" selected disabled>Select Bloom Level</option>
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
                            <label for="pointCreate" class="form-label">Point</label>
                            <input type="number" id="pointCreate" name="point" class="form-control @error('point') is-invalid @enderror" placeholder="Enter point" value="{{ old('point') }}" />
                            @error('point')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="firstAttemptMultiplyCreate" class="form-label">1st Attempt Multiply Bonus</label>
                            <input type="number" step="0.01" id="firstAttemptMultiplyCreate" name="first_attempt_multiply_point" class="form-control @error('first_attempt_multiply_point') is-invalid @enderror" placeholder="Enter first attempt multiply bonus" value="{{ old('first_attempt_multiply_point') }}" />
                            @error('first_attempt_multiply_point')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="secondAttemptMultiplyCreate" class="form-label">2nd Attempt Multiply Bonus</label>
                            <input type="number" step="0.01" id="secondAttemptMultiplyCreate" name="second_attempt_multiply_point" class="form-control @error('second_attempt_multiply_point') is-invalid @enderror" placeholder="Enter second attempt multiply bonus" value="{{ old('second_attempt_multiply_point') }}" />
                            @error('second_attempt_multiply_point')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="thirdAttemptMultiplyCreate" class="form-label">3rd Attempt Multiply Bonus</label>
                            <input type="number" step="0.01" id="thirdAttemptMultiplyCreate" name="third_attempt_multiply_point" class="form-control @error('third_attempt_multiply_point') is-invalid @enderror" placeholder="Enter third attempt multiply bonus" value="{{ old('third_attempt_multiply_point') }}" />
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
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
