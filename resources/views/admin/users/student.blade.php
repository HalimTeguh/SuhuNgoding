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
                    <h5 id="entityHeader" class="m-0">Student</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStudent">
                        + Add New Student
                    </button>

                </div>
            </div>
            <div class="table-responsive">
                <table id="studentTable" class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $key => $student)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-light p-2 " data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item edit-student-btn" href="javascript:void(0);"
                                                data-bs-toggle="offcanvas" data-bs-target="#editStudent"
                                                data-id="{{ $student->id }}" aria-controls="offcanvasEnd">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                data-bs-toggle="modal" data-bs-target="#deleteStudent"
                                                data-id="{{ $student->id }}" data-name="{{ $student->name }}">
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

@include('admin.users.modal.createStudentModal')
@include('admin.users.modal.deleteStudentModal')
@include('admin.users.modal.editStudentCanvas')





@endsection