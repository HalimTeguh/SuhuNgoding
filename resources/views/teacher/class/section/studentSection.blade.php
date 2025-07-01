<div id="studentList" class="card mb-6">
    <div class="card-header text-center text-sm-start d-flex justify-content-between align-items-center">
        <h5>Student List</h5>
        <div>

            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#addStudentModal" data-caller="detailClass">
                + Add Student
            </button>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createStudent">
                + Create Student Account
            </button>
        </div>
    </div>
    <div class="card-body">
        <table id="projectsTable" class="table table-striped ">
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
                            $words = explode(' ', $name);
                            $initials = strtoupper(implode('', array_map(fn($word) => $word[0], $words)));
                            $initials = substr($initials, 0, 2); // Batasi maksimal 2 huruf
                            @endphp
                            <div class="avatar avatar-sm mx-5 mb-3">
                                <span
                                    class="avatar-initial bg-label-primary rounded-circle text-white fw-bold text-uppercase d-inline-flex justify-content-center align-items-center"
                                    style="width: 40px; height: 40px; font-size: 14px;">
                                    {{ $initials }}
                                </span>
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
                                <li>
                                    <a class="dropdown-item edit-student-btn" href="javascript:void(0);"
                                        data-bs-toggle="offcanvas" data-bs-target="#editStudent"
                                        data-id="{{ $student->id }}" data-name="{{ $student->user->name }}"
                                        data-nis="{{ $student->NIS }}" data-institution="{{ $student->institution }}"
                                        data-address="{{ $student->address }}" data-email="{{ $student->user->email }}"
                                        aria-controls="offcanvasEnd">Edit
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal"
                                        data-bs-target="#detachStudentModal" data-student-id="{{ $student->id }}"
                                        data-student-name="{{ $student->user->name }}">
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

<form id="attachStudentForm" method="POST" action="{{ route('dashboard.teacher.class.attachStudent', $class->id) }}"
    style="display: none;">
    @csrf
    <input type="hidden" name="student_id" id="hiddenStudentId">
</form>

@include('teacher.class.partials.inviteStudentModal')
@include('teacher.class.partials.detachStudentFromClassesModal')

<script>
    function inviteStudent() {
        if (!student) return;

        // Masukkan nilai ID ke input hidden
        document.getElementById('hiddenStudentId').value = student.id;

        // Submit form
        document.getElementById('attachStudentForm').submit();
    }

</script>