<!-- Modal Create -->
<div class="modal fade" id="createModule" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Create New Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/dashboard/admin/pembelajaran/module" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Select Teacher -->
                        <div class="col mb-6">
                            <label for="teacherCreate" class="form-label">Teacher</label>
                            <select class="form-select" id="teacherCreate" name="teacherCreate">
                                <option value="" selected class="text-secondary"> Select teacher of the class
                                </option>
                                @foreach($teachers as $key => $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            @error('teacherCreate')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="titleCreate" class="form-label">Title Module</label>
                            <input type="text" id="titleCreate" name="titleCreate"
                                class="form-control @error('titleCreate') is-invalid @enderror"
                                placeholder="Enter title module" value="{{ old('titleCreate') }}" />
                            @error('titleCreate')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="statusCreate" class="form-label">Status</label>
                            <select class="form-select" id="statusCreate" name="statusCreate">
                                <option value="1" selected>Public</option>
                                <option value="2">Private</option>
                                <option value="3">Pending</option>
                            </select>
                            @error('statusCreate')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col mb-6">
                            <label for="meetingsCreate" class="form-label">Total meetings</label>
                            <input type="number" id="meetingsCreate" name="meetingsCreate"
                                class="form-control @error('meetingsCreate') is-invalid @enderror" placeholder="10"
                                value="{{ old('meetingsCreate') }}" />
                            @error('meetingsCreate')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>