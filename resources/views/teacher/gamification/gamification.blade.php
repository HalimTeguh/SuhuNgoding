@extends('layout.dashboard')

@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->

        <div class="card mb-4">
            <div class="w-100">
                <div class="alert alert-info d-flex align-items-center border border-info rounded shadow-sm m-4"
                    role="alert">
                    <i class="bx bx-info-circle fs-4 me-3 text-info"></i>
                    <div class="text-dark small">
                        <strong>Perhatian!</strong><br>
                        Fitur gamifikasi ini dirancang sesuai dengan Taksonomi Bloom, yang terdiri dari 6 level:
                        <strong>Remember</strong>, <strong>Understand</strong>, <strong>Apply</strong>,
                        <strong>Analyze</strong>, <strong>Evaluate</strong>, dan <strong>Create</strong>. Data yang
                        dimasukkan hanya akan diterima jika sesuai dengan level Taksonomi Bloom yang relevan.
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="w-100">
                <div class="d-flex justify-content-between align-items-center p-3 bg-white shadow-sm rounded">
                    <h5 id="entityHeader" class="m-0">Gamification</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#createGamification">
                        + Add New gamification
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table id="adminTable" class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Level</th>
                            <th>Point</th>
                            <th>1st multiply bonus</th>
                            <th>2nd multiply bonus</th>
                            <th>3rd multiply bonus</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gamiData as $key => $gami)
                        @php
                        $levels = [
                        1 => 'Remember',
                        2 => 'Understand',
                        3 => 'Apply',
                        4 => 'Analyze',
                        5 => 'Evaluate',
                        6 => 'Create'
                        ];
                        $level = $levels[$gami->bloom_level] ?? '';
                        @endphp
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $level }}</td>
                            <td>{{ $gami->point }} point</td>
                            <td>{{ $gami->first_attempt_multiply_point }}x</td>
                            <td>{{ $gami->second_attempt_multiply_point }}x</td>
                            <td>{{ $gami->third_attempt_multiply_point }}x</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-light p-2" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item edit-admin-btn" href="javascript:void(0);"
                                                data-bs-toggle="modal" data-bs-target="#editGamification"
                                                data-id="{{ $gami->id }}" data-bloom_level="{{ $gami->bloom_level }}"
                                                data-point="{{ $gami->point }}"
                                                data-first_attempt_multiply_point="{{ $gami->first_attempt_multiply_point }}"
                                                data-second_attempt_multiply_point="{{ $gami->second_attempt_multiply_point }}"
                                                data-third_attempt_multiply_point="{{ $gami->third_attempt_multiply_point }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger delete-gamification-btn"
                                                href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#deleteGamification" data-id="{{ $gami->id }}"
                                                data-name="{{ $level }}">
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

@include('teacher.gamification.gamiCreateModal')
@include('teacher.gamification.gamiEditModal')
@include('teacher.gamification.gamiDeleteModal')

@endsection