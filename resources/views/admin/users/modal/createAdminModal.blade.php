<!-- Modal -->
<div class="modal fade" id="createAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Create New Data Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/dashboard/admin/users/admin" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="row">
                        <div class="col mb-6">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Enter name"
                                value="{{ old('name') }}" />
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror" placeholder="Enter email"
                                value="{{ old('email') }}" />
                            @error('email')
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter password" />
                            @error('password')
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
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