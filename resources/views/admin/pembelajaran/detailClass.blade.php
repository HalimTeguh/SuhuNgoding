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
                    <ul class="nav nav-pills flex-column flex-md-row mb-6">
                        <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                                    class="bx bx-user bx-sm me-1_5"></i>Students</a></li>
                        <li class="nav-item"><a class="nav-link" href=""><i
                                    class="bx bxs-book-content bx-sm me-1_5"></i>Module</a></li>
                    </ul>
                </div>
                <div class="card mb-6">
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
                                            <button class="btn btn-icon dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded bx-md"></i>
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
            </div>
        </div>


        <hr class="my-4" />

    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>

<!-- Content wrapper -->

@include('admin.pembelajaran.partials.deleteClassesModal')
@include('admin.pembelajaran.partials.editClassCanvas')
@include('admin.pembelajaran.partials.deleteImageConfirmationModal')

<script>
    document.addEventListener('DOMContentLoaded', function(){

        var formError = "{{ session('form_error') ?? '' }}";
        var entityId = "{{ session('entity_id') ?? '' }}";
        
        
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