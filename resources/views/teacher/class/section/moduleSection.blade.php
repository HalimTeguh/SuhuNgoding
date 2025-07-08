<div id="moduleList" class="card mb-6">
    <div class="card-header text-center text-sm-start d-flex justify-content-between align-items-center">
        <h5>Module List</h5>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModuleModal">
            + Add Module to Class
        </button>
    </div>
    <div class="card-body">
        <table id="projectsTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contoh baris, nanti bisa di-loop dari data dinamis -->
                @foreach($class->modules as $module)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-6">
                                <img src="{{ asset('storage/' . $module->image) }}" alt="Module Image"
                                    class="w-px-40 h-auto rounded">
                            </div>
                            <div>
                                <span class="fw-medium">{{ $module->title }}</span><br>
                                <small class="text-muted">{{ $module->teacher->user->name ?? '-'
                                    }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $module->status == 1 ? 'Public' : 'Private' }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item open-progress-modal" href="#" data-class-id="{{ $class->id }}"
                                    data-module-id="{{ $module->id }}" data-module-title="{{ $module->title }}"
                                    data-class-name="{{ $class->name }}">
                                    Details
                                </a>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                        data-bs-target="#detachModuleModal" data-module-id="{{ $module->id }}"
                                        data-module-title="{{ $module->title }}">
                                        Remove
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
                <!-- Baris lainnya bisa ditambahkan di sini -->
            </tbody>
        </table>
    </div>
</div>

@include('teacher.class.partials.progressStudentModal')