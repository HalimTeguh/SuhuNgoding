<div class="offcanvas offcanvas-end" tabindex="-1" id="editAdmin" aria-labelledby="offcanvasEndLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasEndLabel" class="offcanvas-title">Edit Admin</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body my-auto mx-0 flex-grow-0">
        <form id="adminForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">

                <div class="row">
                    <div class="col mb-6">
                        <label for="nameEdit" class="form-label">Full Name</label>
                        <input type="text" id="nameEdit" name="nameEdit"
                            class="form-control @error('nameEdit') is-invalid @enderror" placeholder="Enter name" value="{{ old('nameEdit') }}"/>
                        @error('nameEdit')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-6">
                        <label for="emailEdit" class="form-label">Email</label>
                        <input type="email" id="emailEdit" name="emailEdit"
                            class="form-control @error('emailEdit') is-invalid @enderror" placeholder="Enter email"
                            value="{{ old('emailEdit') }}"
                             />
                        @error('emailEdit')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-6">
                        <label for="passwordEdit" class="form-label">Password</label>
                        <input type="password" id="passwordEdit" name="passwordEdit"
                            class="form-control @error('passwordEdit') is-invalid @enderror" placeholder="Enter password" />
                        @error('passwordEdit')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="Submit" class="btn btn-primary mb-2 d-grid w-100">Save</button>
            <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas">
                Cancel
            </button>
        </form>
    </div>
</div>

