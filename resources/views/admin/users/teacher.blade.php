@extends('layout.dashboard')

@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="w-100">
                <div class="d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded">
                    <h5 id="entityHeader" class="m-0">Teacher</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTeacher">
                        + Add New Teacher
                    </button>

                </div>
            </div>
            <div class="table-responsive">
                <table id="teacherTable" class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teachers as $key => $teacher)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $teacher->name }}</td>
                            <td>{{ $teacher->email }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-light p-2 " data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item edit-teacher-btn" href="javascript:void(0);"
                                                data-bs-toggle="offcanvas" data-bs-target="#editTeacher"
                                                data-id="{{ $teacher->id }}" aria-controls="offcanvasEnd">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                data-bs-toggle="modal" data-bs-target="#deleteTeacher"
                                                data-id="{{ $teacher->id }}" data-name="{{ $teacher->name }}">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!--/ Basic Bootstrap Table -->

        <hr class="my-4" />

    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>

<!-- Content wrapper -->

@include('admin.users.modal.createTeacherModal')
@include('admin.users.modal.deleteTeacherModal')
@include('admin.users.modal.editTeacherCanvas')





@endsection