<!-- Modal -->
<div class="modal fade" id="createStudent" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Create New Data Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/dashboard/teacher/users/student" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="row">
                        <div class="col mb-6">
                            <label for="nameCreate" class="form-label">Full Name</label>
                            <input type="text" id="nameCreate" name="nameCreate"
                                class="form-control @error('nameCreate') is-invalid @enderror" placeholder="Enter name"
                                value="{{ old('nameCreate') }}" />
                            @error('nameCreate')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="NISCreate" class="form-label">NIS</label>
                            <input type="number" id="NISCreate" name="NISCreate"
                                class="form-control @error('NISCreate') is-invalid @enderror" placeholder="Enter NIS"
                                value="{{ old('NISCreate') }}" />
                            @error('NISCreate')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="institutionCreate" class="form-label">Institution</label>
                            <input type="text" id="institutionCreate" name="institutionCreate"
                                class="form-control @error('institutionCreate') is-invalid @enderror" placeholder="Enter institution"
                                value="{{ old('institutionCreate') }}" />
                            @error('institutionCreate')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="addressCreate" class="form-label">Address</label>
                            <input type="text" id="addressCreate" name="addressCreate"
                                class="form-control @error('addressCreate') is-invalid @enderror" placeholder="Enter address"
                                value="{{ old('addressCreate') }}" />
                            @error('addressCreate')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="emailCreate" class="form-label">Email</label>
                            <input type="email" id="emailCreate" name="emailCreate"
                                class="form-control @error('emailCreate') is-invalid @enderror" placeholder="Enter email"
                                value="{{ old('emailCreate') }}" />
                            @error('emailCreate')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="passwordCreate" class="form-label">Password</label>
                            <input type="password" id="passwordCreate" name="passwordCreate"
                                class="form-control @error('passwordCreate') is-invalid @enderror"
                                placeholder="Enter password" />
                            @error('passwordCreate')
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