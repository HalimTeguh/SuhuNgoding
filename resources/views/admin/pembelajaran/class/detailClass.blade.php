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
                            <!-- Jumlah Siswa -->
                            <div class="d-flex align-items-center me-5 gap-4">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-success rounded w-px-40 h-px-40">
                                        <i class="bx bx-user bx-lg"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $class->students->count() }}</h5>
                                    <span>Students</span>
                                </div>
                            </div>

                            <!-- Jumlah Module -->
                            <div class="d-flex align-items-center gap-4">
                                <div class="avatar">
                                    <div class="avatar-initial bg-label-info rounded w-px-40 h-px-40">
                                        <i class="bx bx-book-content bx-lg"></i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $class->modules->count() }}</h5>
                                    <span>Modules</span>
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
                                        class="fw-medium mx-2">Status:</span> <span>{{ $class->deleted_at ? 'Suspended'
                                        : 'Active' }}</span></li>
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
                                    data-bs-toggle="modal" data-bs-target="#deleteClass" data-id="{{ $class->id }}" data-name="{{ $class->name }}">Suspend</a>
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
                @include('admin.pembelajaran.class.section.studentSection')

                {{-- Module List --}}
                @include('admin.pembelajaran.class.section.moduleSection')

            </div>
        </div>


        <hr class="my-4" />

    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>


<!-- Content wrapper -->

@include('admin.pembelajaran.class.partials.deleteClassesModal')
@include('admin.pembelajaran.class.partials.editClassCanvas')
@include('admin.pembelajaran.class.partials.deleteImageConfirmationModal')

@include('admin.pembelajaran.class.partials.attachModule2ClassesModal')
@include('admin.pembelajaran.class.partials.detachModuleFromClassesModal')

<script>
    document.addEventListener('DOMContentLoaded', function(){
        const tabStudents = document.getElementById('tabStudents');
        const tabModules = document.getElementById('tabModules');
        const studentList = document.getElementById('studentList');
        const moduleList = document.getElementById('moduleList');

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

        $(document).ready(function() {
            $('#projectsTable').DataTable({
                lengthMenu: [ [10, 25, 50, 75, 100], [10, 25, 50, 75, 100] ],
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                responsive: true,
                language: {
                    searchPlaceholder: "Search student...",
                    search: "",
                    lengthMenu: "Show _MENU_ entries",
                    zeroRecords: "No data found",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                },
            });
        });

        // Inisialisasi tampilan awal
        showTab('students');

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
