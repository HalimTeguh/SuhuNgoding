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
                    <h5 id="entityHeader" class="m-0">Class</h5>
                    {{-- @livewire('create-class-modal') --}}
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClass">
                        + Add New Class
                    </button>

                </div>
            </div>
            <div class="table-responsive">
                <table id="classTable" class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Teacher</th>
                            <th>description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classes as $key => $class)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $class->name }}</td>
                            <td>{{ $class->teacher->user->name }}</td>
                            <td>{{ $class->description }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-light p-2 " data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item view-class-btn"
                                                href="/dashboard/admin/pembelajaran/class/{{ $class->id }}">
                                                <i class="bx bx-show me-1"></i> view
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="javascript:void(0);"
                                                data-bs-toggle="modal" data-bs-target="#deleteClass"
                                                data-id="{{ $class->id }}" data-name="{{ $class->name }}">
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

@include('admin.pembelajaran.class.partials.createClassesModal')
@include('admin.pembelajaran.class.partials.inviteStudentModal')
@include('admin.pembelajaran.class.partials.deleteClassesModal')

@endsection