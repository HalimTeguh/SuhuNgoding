@extends('layout.dashboard')

@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <div class="col-xl-4 col-lg-5 order-1 order-md-0">
                <div class="card mb-6">
                    <div class="card-body pt-12">
                        <div class="user-avatar-section">
                            <div class=" d-flex align-items-center flex-column">
                                @if($class->image == null)
                                <div id="imagePreviewDetailContainer" class="image-preview-detail-container">
                                    <i id="defaultIconDetail"
                                        class="fa-regular fa-image default-preview-detail-icon {{ ($class->image) ? 'd-none' : '' }}"></i>
                                </div>
                                @else
                                <img class="img-fluid rounded mb-4 image-preview-detail"
                                    src="{{ asset('storage/'. $class->image) }}" height="120" width="120"
                                    alt="User avatar">
                                @endif
                                <div class="user-info text-center">
                                    <h5>{{ $class->name }}</h5>
                                    <span>{{ $class->description }}</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="d-flex justify-content-around flex-wrap my-6 gap-0 gap-md-3 gap-lg-4 pb-4 border-bottom">
                            <div class="d-flex align-items-center me-5 gap-4">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-primary rounded w-px-40 h-px-40">
                                        <i class="bx bx-check bx-lg"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-0">1.23k</h5>
                                    <span>Task Done</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-4">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-primary rounded w-px-40 h-px-40">
                                        <i class="bx bx-customize bx-lg"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-0">568</h5>
                                    <span>Project Done</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-start align-items-start mb-2">
                            <span class="badge bg-label-primary">About Teacher</span>
                        </div>
                        <div class="mb-4 border-bottom">
                            <ul class="list-unstyled my-2 py-1">
                                <li class="d-flex align-items-center mb-4"><i class="bx bx-user"></i><span
                                        class="fw-medium mx-2">Full Name:</span> <span>{{ $teacher->user->name }}</span>
                                </li>
                                <li class="d-flex align-items-center mb-4"><i class="bx bx-check"></i><span
                                        class="fw-medium mx-2">Status:</span> <span>Active</span></li>
                                <li class="d-flex align-items-center mb-4"><i class="bx bx-flag"></i><span
                                        class="fw-medium mx-2">Institution:</span> <span>{{ $teacher->institution
                                        }}</span></li>
                            </ul>
                        </div>
                        <div class="info-container ">
                            <div class="d-flex justify-content-center">
                                <a href="javascript:void(0);" class="btn btn-primary me-4 edit-class-btn"
                                    data-id="{{ $class->id }}" data-bs-target="#editClass" data-bs-toggle="offcanvas"
                                    aria-controls="offcanvasEnd">Edit</a>
                                <a class="btn btn-outline-danger suspend-user" href="javascript:void(0);"
                                    data-bs-toggle="modal" data-bs-target="#deleteClass">Suspend</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7 order-0 order-md-1">
                <div class="nav-align-top">
                    <ul class="nav nav-pills flex-column flex-md-row mb-6" id="classTabNav">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);" id="tabStudents">
                                <i class="bx bx-user bx-sm me-1_5"></i>Students
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" id="tabModules">
                                <i class="bx bxs-book-content bx-sm me-1_5"></i>Module
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Student List --}}
                <div id="studentList" class="card mb-6">
                    <div class="card-header text-center text-sm-start">
                        <h5>Student List</h5>
                    </div>
                    <div class="card-body">
                        <table id="projectsTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>NIS</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Contoh baris, nanti bisa di-loop dari data dinamis -->
                                @foreach($students as $key => $student)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                            $name = $student->user->name;
                                            $initials = implode('', array_map(fn($word) => strtoupper($word[0]),
                                            explode(' ', $name)));
                                            @endphp
                                            <div class="avatar avatar-sm me-3">
                                                <span class="avatar-initial rounded-circle bg-label-primary">{{
                                                    $initials }}</span>
                                            </div>
                                            <div>
                                                <span class="fw-medium">{{ $student->user->name }}</span><br>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student->NIS }}</td>
                                    <td>{{ $student->user->email }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#">Details</a></li>
                                                <li><a class="dropdown-item" href="#">Archive</a></li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li><a class="dropdown-item text-danger" href="#">Delete</a></li>
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

                {{-- Module List --}}
                <div id="moduleList" class="card mb-6">
                    <div
                        class="card-header text-center text-sm-start d-flex justify-content-between align-items-center">
                        <h5>Module List</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addModuleModal">
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
                                            <div class="avatar avatar-sm me-3">
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
                                                <li><a class="dropdown-item" href="#">Details</a></li>
                                                <li><a class="dropdown-item text-danger" href="#">Remove</a></li>
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

            </div>
        </div>


        <hr class="my-4" />

    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>

<form method="POST" action="{{ route('dashboard.class.attachModule', $class->id) }}">
    @csrf
    <div class="modal fade" id="addModuleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
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
            </div>
        </div>
    </div>
</form>

<!-- Content wrapper -->

@include('admin.pembelajaran.partials.deleteClassesModal')
@include('admin.pembelajaran.partials.editClassCanvas')
@include('admin.pembelajaran.partials.deleteImageConfirmationModal')

<script>
    document.addEventListener('DOMContentLoaded', function(){

        const tabStudents = document.getElementById('tabStudents');
        const tabModules = document.getElementById('tabModules');
        const studentList = document.getElementById('studentList');
        const moduleList = document.getElementById('moduleList');
        const cards = document.querySelectorAll('.selectable-card');

        var formError = "{{ session('form_error') ?? '' }}";
        var entityId = "{{ session('entity_id') ?? '' }}";
        
        // Fungsi helper untuk mengatur tampilan tab
        function showTab(tab) {
            if (tab === 'students') {
                tabStudents.classList.add('active');
                tabModules.classList.remove('active');
                studentList.style.display = 'block';
                moduleList.style.display = 'none';
            } else if (tab === 'modules') {
                tabStudents.classList.remove('active');
                tabModules.classList.add('active');
                studentList.style.display = 'none';
                moduleList.style.display = 'block';
            }
        }

        // Inisialisasi tampilan awal
        showTab('students');

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

        // Event listener untuk tab
        tabStudents.addEventListener('click', () => showTab('students'));
        tabModules.addEventListener('click', () => showTab('modules'));
        
        document.querySelectorAll(`.edit-class-btn`).forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var form = document.getElementById(`classForm`);
                // form.action = `dashboard/admin/pembelajaran/class/${id}`;
                console.log(form.action);
                console.log(id);
            });
        });

        if (formError === 'update' &&  entityId) {
            var updateModal = new bootstrap.Offcanvas(document.getElementById(`editClass`));
            updateModal.show();
        }
    })

</script>


@endsection

@push('datatables')
<script>
    $(document).ready(function() {
        $('#projectsTable').DataTable({
            lengthMenu: [ [7, 10, 25, 50, 75, 100], [7, 10, 25, 50, 75, 100] ],
            paging: true,
            searching: true,
            info: true,
            ordering: true,
        });
    });
</script>
@endpush