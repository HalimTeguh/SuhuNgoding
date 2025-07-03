@extends('layout.dashboard')

@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            {{-- Choose Class --}}
            <div class="col-12 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex flex-wrap align-items-center gap-3">
                                <h5 class="card-title mb-0 fw-semibold text-dark">Class </h5>
                                <div class="position-relative" style="min-width: 220px;">
                                    <select id="classSelect"
                                        class="form-select py-2 ps-3 pe-5 border-light bg-light bg-opacity-25 text-dark focus-ring focus-ring-primary transition"
                                        aria-label="Pilih Kelas dan Modul">
                                        <option value="">-- Pilih Kelas dan Modul --</option>
                                        @foreach ($testingCombinations as $combo)
                                        @php
                                        $class = $classes->firstWhere('id', $combo->class_id);
                                        $module = $class?->modules->firstWhere('id', $combo->module_id);
                                        @endphp
                                        @if ($class && $module)
                                        <option value="{{ $class->id }}_{{ $module->id }}">
                                            {{ $class->name }} - {{ $module->title }}
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                    <span
                                        class="position-absolute top-50 end-0 translate-middle-y pe-3 pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-chevron-down text-muted"
                                            viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <button type="button"
                                class="btn btn-primary btn-sm d-flex align-items-center gap-2 py-2 px-3 transition"
                                data-bs-toggle="modal" data-bs-target="#assignClassModal">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-plus-lg" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2z" />
                                </svg>
                                Assign Kelas Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-6 col-md-4 order-0">
                <div class="row">
                    {{-- Amount of Student clear do pretest --}}
                    <div class="col-6 mb-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="gap-3">
                                    <!-- Kiri: Judul dan Dropdown -->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                            <div class="avatar">
                                                <div class="avatar-initial bg-label-success rounded w-px-40 h-px-40">
                                                    <i class="bx bx-user bx-lg"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="pretestMenu"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded bx-sm text-muted"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="pretestMenu">
                                                <form action="{{ route('testing.start') }}" method="POST"
                                                    onsubmit="updateFormValues(this)">
                                                    @csrf
                                                    <input type="hidden" name="class_id" class="class_pretest_start">
                                                    <input type="hidden" name="module_id" class="module_pretest_start">
                                                    <input type="hidden" name="type" value="pretest">
                                                    <button type="submit" class="dropdown-item">
                                                        <span class="badge bg-label-info">Start Test</span>
                                                    </button>
                                                </form>

                                                <form action="{{ route('testing.reset') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="class_id" class="class_pretest_reset">
                                                    <input type="hidden" name="module_id" class="module_pretest_reset">
                                                    <input type="hidden" name="type" value="pretest">
                                                    <button type="submit" class="dropdown-item">
                                                        Reset Pre-test
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-1">Pre-test Student</p>
                                    <h4 class="card-title mb-3 pretest-count">0</h4>
                                    <span class="badge bg-label-warning pretest-message"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Amount of Student clear do posttest --}}
                    <div class="col-6 mb-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="gap-3">
                                    <!-- Kiri: Judul dan Dropdown -->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                            <div class="avatar">
                                                <div class="avatar-initial bg-label-info rounded w-px-40 h-px-40">
                                                    <i class="bx bx-user bx-lg"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="posttestMenu"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded bx-sm text-muted"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="posttestMenu">
                                                <form action="{{ route('testing.start') }}" method="POST"
                                                    onsubmit="updateFormValues(this)">
                                                    @csrf
                                                    <input type="hidden" name="class_id" class="class_posttest_start">
                                                    <input type="hidden" name="module_id" class="module_posttest_start">
                                                    <input type="hidden" name="type" value="posttest">
                                                    <button type="submit" class="dropdown-item">
                                                        <span class="badge bg-label-info">Start Test</span>
                                                    </button>
                                                </form>

                                                <form action="{{ route('testing.reset') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="class_id" class="class_posttest_reset">
                                                    <input type="hidden" name="module_id" class="module_posttest_reset">
                                                    <input type="hidden" name="type" value="posttest">
                                                    <button type="submit" class="dropdown-item">
                                                        Reset Post-test
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-1">Post-test Student</p>
                                    <h4 class="card-title mb-3 posttest-count">0</h4>
                                    <span class="badge bg-label-warning posttest-message"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-4 order-1">
                <div class="row">
                    {{-- Amount of Student in Experiment Class --}}
                    <div class="col-lg-6 col-md-12 col-6 mb-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="gap-3">

                                    <!-- Kiri: Judul dan Dropdown -->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                            <div class="avatar">
                                                <div class="avatar-initial bg-label-primary rounded w-px-40 h-px-40">
                                                    <i class="bx bx-user bx-lg"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-1">Experiment Student</p>
                                    <h4 class="card-title mb-3 experiment-count">0</h4>
                                    <span class="badge bg-label-warning"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Amount of Student in Control Class --}}
                    <div class="col-lg-6 col-md-12 col-6 mb-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="gap-3">

                                    <!-- Kiri: Judul dan Dropdown -->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                            <div class="avatar">
                                                <div class="avatar-initial bg-label-warning rounded w-px-40 h-px-40">
                                                    <i class="bx bx-user bx-lg"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-1">Control Student</p>
                                    <h4 class="card-title mb-3 control-count">0</h4>
                                    <span class="badge bg-label-warning"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table of Student Class --}}
            <div class="col-12 col-xxl-12 order-2 order-md-3 order-xxl-2 mb-6">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-lg-12">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="card-title mb-0">
                                    <h5 class="m-0 me-2">Class Progress</h5>
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="totalRevenue" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded bx-lg text-muted"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalRevenue">
                                        <form action="{{ route('testing.divideClass') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="class_id" class="class_divide_class">
                                            <input type="hidden" name="module_id" class="module_divide_class">
                                            <input type="hidden" name="type" value="pretest">
                                            <button type="submit" class="dropdown-item">
                                                Divide Independent Sampling Class
                                            </button>
                                        </form>
                                        <button type="button" class="dropdown-item btn-move-student-class"
                                            data-bs-toggle="modal" data-class-id="{{ $class->id ?? '__CLASS__' }}"
                                            data-module-id="{{ $module->id ?? '__MODULE__' }}">
                                            Change Class Type Student
                                        </button>
                                        <button type="button" class="dropdown-item btn-levene-test"
                                            data-url="{{ route('testing.levenesTest', ['classId' => '__CLASS__', 'moduleId' => '__MODULE__']) }}">
                                            Uji Homogenitas (Levene's Test) Pretest
                                        </button>
                                        <button type="button" class="dropdown-item btn-paired-test"
                                            data-url="/dashboard/admin/testing/setting/class/__CLASS__/module/__MODULE__/paired-test">
                                            Uji Paired Sample T-Test
                                        </button>
                                        <button type="button" class="dropdown-item btn-independent-test"
                                            data-url="/dashboard/admin/testing/setting/class/__CLASS__/module/__MODULE__/independent-test">
                                            Uji Independent Sample T-Test
                                        </button>
                                        <button type="button" class="dropdown-item btn-export-summary"
                                            data-url-template="/dashboard/admin/testing/setting/export-summary/__CLASS__/__MODULE__">
                                            Export Data ke Excel
                                        </button>
                                        <form action="{{ route('testing.resetClass') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="class_id" class="class_reset_class">
                                            <input type="hidden" name="module_id" class="module_reset_class">
                                            <input type="hidden" name="type" value="pretest">
                                            <button type="submit" class="dropdown-item">
                                                Reset Data
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="classTable" class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Student Name</th>
                                            <th>Nim</th>
                                            <th>Class Type</th>
                                            <th>Pre-test</th>
                                            <th>Module Progress</th>
                                            <th>Post-test</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                @if($classes->isEmpty())
                                <div id="noData" class="alert alert-info text-center" role="alert">
                                    Class for testing is not started yet, please assign class first.
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <script>

            </script>
        </div>
    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

@include('admin.testing.partials.assignClassTestingModal')
@include('admin.testing.partials.levenesPretestModal')
@include('admin.testing.partials.pairedSampleTestModal')
@include('admin.testing.partials.independentSampleTestModal')
@include('admin.testing.partials.changeClassTypeModal')

<script>
    document.getElementById('classSelect').addEventListener('change', function () {
        const selected = this.value;

        console.log(selected);

        if(selected) {

            const [classId, moduleId] = selected.split('_');

            const exportButtons = document.querySelectorAll('.btn-export-summary');
            exportButtons.forEach(btn => {
                const urlTemplate = btn.getAttribute('data-url-template');
                const finalUrl = urlTemplate.replace('__CLASS__', classId).replace('__MODULE__', moduleId);
                btn.setAttribute('data-url', finalUrl);

                // Optional: langsung arahkan saat diklik
                btn.addEventListener('click', function () {
                    window.location.href = finalUrl;
                });
            });
            
            fetch(`/dashboard/admin/testing/setting/class/${classId}/module/${moduleId}/summary`)
                .then(res => res.json())
                .then(data => {
                    console.log(data);
                    // Update angka
                    document.querySelector('.pretest-count').textContent = `${data.pretest_done} / ${data.total_student}`;
                    document.querySelector('.pretest-message').textContent = data.pretest_message;
                    document.querySelector('.posttest-count').textContent = `${data.posttest_done} / ${data.total_student}`
                    document.querySelector('.posttest-message').textContent = data.posttest_message;
                    document.querySelector('.experiment-count').textContent = data.experiment_count;
                    document.querySelector('.control-count').textContent = data.control_count;



                    document.querySelector('.class_pretest_start').value = classId;
                    document.querySelector('.module_pretest_start').value = moduleId;
                    
                    document.querySelector('.class_pretest_reset').value = classId;
                    document.querySelector('.module_pretest_reset').value = moduleId;

                    document.querySelector('.class_posttest_start').value = classId;
                    document.querySelector('.module_posttest_start').value = moduleId;
                    
                    document.querySelector('.class_posttest_reset').value = classId;
                    document.querySelector('.module_posttest_reset').value = moduleId;

                    

                    document.querySelector('.class_divide_class').value = classId;
                    document.querySelector('.module_divide_class').value = moduleId;
                    
                    document.querySelector('.class_reset_class').value = classId;
                    document.querySelector('.module_reset_class').value = moduleId;
    
                    // Update tabel
                    const tbody = document.querySelector('#classTable tbody');
                    tbody.innerHTML = '';
    
                    data.students.forEach(row => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${row.no}</td>
                                <td>${row.name}</td>
                                <td>${row.nim}</td>
                                <td>${row.class_type}</td>
                                <td>${row.pre_test}</td>
                                <td>
                                    ${
                                        row.class_type === 'control'
                                            ? `<span class="badge bg-warning">Kontrol</span>`
                                            : `${row.progress}`
                                    }
                                </td>
                                <td>${row.post_test}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light p-2" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    <i class="bx bx-show me-1"></i> View Progress Module
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                });
        }

    });

    document.addEventListener('DOMContentLoaded', function () {
        setupLeveneTest();
        setupPairedTest();
        setupIndependentTest();
        setupMoveStudentModal()
    });

    function setupLeveneTest() {
        const buttons = document.querySelectorAll('.btn-levene-test');

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const urlTemplate = btn.getAttribute('data-url');
                const selected = document.getElementById('classSelect').value;
                if (!selected) return alert("Pilih kelas dan modul terlebih dahulu.");

                const [classId, moduleId] = selected.split('_');
                const url = urlTemplate.replace('__CLASS__', classId).replace('__MODULE__', moduleId);

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        // 🔽 Urutkan berdasarkan score dari terkecil ke terbesar
                        const sortedExperiment = data.experiment.sort((a, b) => a.score - b.score);
                        const sortedControl = data.control.sort((a, b) => a.score - b.score);

                        let expRows = '';
                        sortedExperiment.forEach((s, i) => {
                            expRows += `<tr><td>${i + 1}</td><td>${s.name}</td><td>${s.score}</td></tr>`;
                        });

                        let ctrlRows = '';
                        sortedControl.forEach((s, i) => {
                            ctrlRows += `<tr><td>${i + 1}</td><td>${s.name}</td><td>${s.score}</td></tr>`;
                        });

                        const modalContent = `
                            <div class="modal-header">
                                <h5 class="modal-title">Uji Homogenitas (Levene's Test)</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Experiment Group</h6>
                                        <table class="table table-bordered">
                                            <thead><tr><th>No</th><th>Nama</th><th>Score</th></tr></thead>
                                            <tbody>${expRows}</tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Control Group</h6>
                                        <table class="table table-bordered">
                                            <thead><tr><th>No</th><th>Nama</th><th>Score</th></tr></thead>
                                            <tbody>${ctrlRows}</tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="alert alert-info mt-3">
                                    <p class="mb-1"><strong>Hasil Uji Levene:</strong></p>
                                    <ul class="mb-0">
                                        <li><strong>Levene Statistic:</strong> ${data.levene_statistic}</li>
                                        <li><strong>P-Value:</strong> ${data.p_value}</li>
                                        <li><strong>Interpretasi:</strong> ${data.interpretation}</li>
                                    </ul>
                                    <hr class="my-2" />
                                    <small class="text-muted d-block">
                                        <strong>Apa itu Levene's Test?</strong><br>
                                        Levene's Test digunakan untuk menguji apakah varians (penyebaran data) antara dua kelompok sama atau berbeda. 
                                        <strong>Levene Statistic</strong> adalah nilai F hasil perhitungan, sedangkan <strong>P-Value</strong> menunjukkan signifikansi statistik dari perbedaan varians tersebut.
                                    </small>
                                </div>

                                <div class="alert alert-warning mt-3">
                                    <p class="mb-1"><strong>Catatan Interpretasi Uji Levene:</strong></p>
                                    <ul class="mb-0">
                                        <li>
                                            Jika <code>p &gt; 0.05</code> &rarr; Varians dianggap <strong>homogen</strong> (tidak berbeda signifikan)<br>
                                            ➜ Gunakan baris <em>"Equal variances assumed"</em> dalam hasil T-test.
                                        </li>
                                        <li class="mt-2">
                                            Jika <code>p &le; 0.05</code> &rarr; Varians dianggap <strong>tidak homogen</strong> (berbeda signifikan)<br>
                                            ➜ Gunakan baris <em>"Equal variances not assumed"</em> dalam hasil T-test.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        `;


                        const modalElement = document.getElementById('levenesPretestModal');
                        modalElement.querySelector('.modal-content').innerHTML = modalContent;

                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                    })

                    .catch(err => {
                        alert("Gagal memuat data Levene. Periksa jaringan atau data!");
                        console.error(err);
                    });
            });
        });
    }

    function setupPairedTest() {
        const buttons = document.querySelectorAll('.btn-paired-test');

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const urlTemplate = btn.getAttribute('data-url');
                const selected = document.getElementById('classSelect').value;
                if (!selected) return alert("Pilih kelas dan modul terlebih dahulu.");

                const [classId, moduleId] = selected.split('_');
                const url = urlTemplate.replace('__CLASS__', classId).replace('__MODULE__', moduleId);

                const pretestDone = parseInt(document.querySelector('.pretest-count').textContent.split('/')[0].trim());
                const posttestDone = parseInt(document.querySelector('.posttest-count').textContent.split('/')[0].trim());
                const totalStudent = parseInt(document.querySelector('.pretest-count').textContent.split('/')[1].trim());

                if (pretestDone < totalStudent || posttestDone < totalStudent) {
                    alert("Semua siswa harus menyelesaikan pretest dan posttest sebelum menjalankan uji Paired T-Test.");
                    return;
                }

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        const modalElement = document.getElementById('pairedSampleTestModal');
                        const modal = new bootstrap.Modal(modalElement);

                        document.getElementById('paired-class-name').textContent = data.class_name;
                        document.getElementById('paired-module-name').textContent = data.module_name;

                        const runBtn = document.getElementById('runPairedTestBtn');
                        runBtn.setAttribute('data-url', data.run_url); // endpoint POST
                        runBtn.setAttribute('data-get', url); // endpoint GET untuk refresh
                        runBtn.setAttribute('data-class', classId);
                        runBtn.setAttribute('data-module', moduleId);

                        renderPairedResult(data);
                        modal.show();
                    })
                    .catch(err => {
                        alert("Gagal memuat data Paired T-Test.");
                        console.error(err);
                    });
            });
        });

        const runBtn = document.getElementById('runPairedTestBtn');
        runBtn.addEventListener('click', function () {
            const classId = runBtn.getAttribute('data-class');
            const moduleId = runBtn.getAttribute('data-module');
            const getUrl = runBtn.getAttribute('data-get');
            const postUrl = runBtn.getAttribute('data-url');

            fetch(postUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ class_id: classId, module_id: moduleId })
            })
            .then(res => res.json())
            .then(resp => {
                alert(resp.message || 'Berhasil menjalankan paired test');
                // Setelah berhasil, ambil ulang data untuk update hasil di modal
                return fetch(getUrl);
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('paired-class-name').textContent = data.class_name;
                document.getElementById('paired-module-name').textContent = data.module_name;
                renderPairedResult(data);
            })
            .catch(err => {
                alert("Gagal menjalankan paired test");
                console.error(err);
            });
        });

        // Fungsi untuk render ulang hasil Paired T-Test
        function renderPairedResult(data) {
            const resultDiv = document.getElementById('paired-test-result');

            if (!data.exists) {
                resultDiv.innerHTML = `
                    <div class="alert alert-warning">
                        Belum ada data Paired T-Test. Pastikan siswa menyelesaikan pretest dan posttest,
                        lalu klik tombol <strong>Jalankan Paired T-Test</strong>.
                    </div>
                `;
                return;
            }

            const renderBox = (label, result) => {
                if (!result) {
                    return `<div class="alert alert-secondary">Belum ada data untuk kelas ${label}.</div>`;
                }

                return `
                    <div class="alert alert-success">
                        <strong>${label} - Interpretasi:</strong> ${result.interpretation}
                    </div>
                    <table class="table table-bordered">
                        <tr><th>Mean Difference</th><td>${result.mean_difference}</td></tr>
                        <tr><th>T-Statistic</th><td>${result.t_statistic}</td></tr>
                        <tr><th>DF</th><td>${result.degrees_freedom}</td></tr>
                        <tr><th>P-Value (1-tailed)</th><td>${result.p_value_one_tailed}</td></tr>
                        <tr><th>P-Value (2-tailed)</th><td>${result.p_value_two_tailed}</td></tr>
                        <tr><th>N</th><td>${result.n}</td></tr>
                    </table>
                `;
            };

            resultDiv.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        ${renderBox('Kelas Kontrol', data.control)}
                    </div>
                    <div class="col-md-6">
                        ${renderBox('Kelas Eksperimen', data.experiment)}
                    </div>
                </div>
                <div class="alert alert-info mt-3">
                    <strong>Penjelasan Singkat:</strong><br>
                    <ul class="mb-0">
                        <li>
                            <strong>Mean Difference</strong>: Selisih rata-rata antara nilai <em>post-test</em> dan <em>pre-test</em>. 
                            Nilai positif berarti rata-rata post-test lebih tinggi dari pre-test, menandakan peningkatan hasil belajar.
                        </li>
                        <li>
                            <strong>T-Statistic</strong>: Nilai statistik t yang menunjukkan seberapa jauh selisih rata-rata (mean difference) dibandingkan dengan variasi dalam data.
                            Semakin tinggi nilai ini, semakin kuat bukti bahwa terdapat perbedaan nyata.
                        </li>
                        <li>
                            <strong>DF (Degrees of Freedom)</strong>: Derajat kebebasan, dihitung dari jumlah data dikurangi satu. DF digunakan untuk menentukan bentuk distribusi t.
                        </li>
                        <li>
                            <strong>P-Value (1-tailed)</strong>: Peluang bahwa hasil uji terjadi secara kebetulan dengan arah satu sisi (misalnya hanya menguji apakah post-test > pre-test).
                        </li>
                        <li>
                            <strong>P-Value (2-tailed)</strong>: Peluang bahwa hasil uji terjadi secara kebetulan tanpa memperhatikan arah (baik post-test lebih tinggi atau lebih rendah). Biasanya ini yang digunakan dalam pengujian.
                        </li>
                        <li>
                            <strong>N</strong>: Jumlah peserta yang memiliki nilai <em>pre-test</em> dan <em>post-test</em> lengkap, atau jumlah pasangan data yang dianalisis.
                        </li>
                        <li class="mt-2">
                            Jika <code>p &lt; 0.05</code> <em>(atau &lt; 5.00 × 10<sup>−2</sup>)</em>, maka hasil dianggap <strong>signifikan</strong> secara statistik.<br>
                            Jika <code>p &ge; 0.05</code>, maka tidak ada cukup bukti untuk menyatakan bahwa perbedaan yang terjadi bukan karena kebetulan.
                        </li>
                    </ul>
                </div>

            `;
        }

    }

    function setupIndependentTest() {
        const buttons = document.querySelectorAll('.btn-independent-test');

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const urlTemplate = btn.getAttribute('data-url');
                const selected = document.getElementById('classSelect').value;
                if (!selected) return alert("Pilih kelas dan modul terlebih dahulu.");

                const [classId, moduleId] = selected.split('_');
                const url = urlTemplate.replace('__CLASS__', classId).replace('__MODULE__', moduleId);

                const posttestDone = parseInt(document.querySelector('.posttest-count').textContent.split('/')[0].trim());
                const totalStudent = parseInt(document.querySelector('.posttest-count').textContent.split('/')[1].trim());

                if (posttestDone < totalStudent) {
                    alert("Semua siswa harus menyelesaikan posttest sebelum menjalankan uji Independent T-Test.");
                    return;
                }

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        const modalElement = document.getElementById('IndependentSampleTestModal');
                        const modal = new bootstrap.Modal(modalElement);

                        document.getElementById('Independent-class-name').textContent = data.class_name;
                        document.getElementById('Independent-module-name').textContent = data.module_name;

                        const runBtn = document.getElementById('runIndependentTestBtn');
                        runBtn.setAttribute('data-url', data.run_url);
                        runBtn.setAttribute('data-class', classId);
                        runBtn.setAttribute('data-module', moduleId);

                        console.log(data);

                        renderIndependentResult(data);
                        modal.show();
                    })
                    .catch(err => {
                        alert("Gagal memuat data Independent T-Test.");
                        console.error(err);
                    });
            });
        });

        const runBtn = document.getElementById('runIndependentTestBtn');
        runBtn.addEventListener('click', function () {
            const classId = runBtn.getAttribute('data-class');
            const moduleId = runBtn.getAttribute('data-module');
            const postUrl = runBtn.getAttribute('data-url');
            const getUrl = `/dashboard/admin/testing/setting/class/${classId}/module/${moduleId}/independent-test`;

            fetch(postUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ class_id: classId, module_id: moduleId })
            })
            .then(res => res.json())
            .then(resp => {
                alert(resp.message || 'Berhasil menjalankan uji Independent T-Test');
                return fetch(getUrl);
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('Independent-class-name').textContent = data.class_name;
                document.getElementById('Independent-module-name').textContent = data.module_name;
                renderIndependentResult(data);
            })
            .catch(err => {
                alert("Gagal menjalankan uji Independent T-Test");
                console.error(err);
            });
        });

        function renderIndependentResult(data) {
            const resultDiv = document.getElementById('Independent-test-result');

            if (!data.exists) {
                resultDiv.innerHTML = `
                    <div class="alert alert-warning">
                        Belum ada data Independent T-Test. Pastikan semua siswa telah menyelesaikan posttest,
                        lalu klik tombol <strong>Jalankan Independent T-Test</strong>.
                    </div>
                `;
                return;
            }

            const stats = data.group_statistics;
            const tStat = data.t_statistic;
            const pVal = data.p_value;

            resultDiv.innerHTML = `
                <div class="alert alert-${data.is_significant ? 'success' : 'info'}">
                    <strong>Interpretasi:</strong> ${data.interpretation}
                </div>
                <table class="table table-bordered">
                <thead>
                    <tr>
                    <th>Kelompok</th>
                    <th>Rata-rata</th>
                    <th>Std Deviasi</th>
                    <th>N</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td>Eksperimen</td>
                    <td>${data.experiment.mean.toFixed(2)}</td>
                    <td>${data.experiment.stddev.toFixed(2)}</td>
                    <td>${data.experiment.n}</td>
                    </tr>
                    <tr>
                    <td>Kontrol</td>
                    <td>${data.control.mean.toFixed(2)}</td>
                    <td>${data.control.stddev.toFixed(2)}</td>
                    <td>${data.control.n}</td>
                    </tr>
                </tbody>
                </table>

                <table class="table table-bordered mt-4">
                    <tr><th>T-Statistic</th><td>${tStat}</td></tr>
                    <tr><th>P-Value</th><td>${pVal}</td></tr>
                </table>

                <div class="alert alert-info mt-3">
                    <strong>Penjelasan Singkat:</strong><br>
                    <ul class="mb-0">
                        <li><strong>T-Statistic</strong>: Mengukur seberapa besar perbedaan antara dua kelompok relatif terhadap variasi dalam data.</li>
                        <li><strong>P-Value</strong>: Peluang bahwa hasil perbedaan yang terlihat hanya kebetulan. Jika &lt; 0.05, maka perbedaan dianggap signifikan.</li>
                        <li><strong>Rata-rata</strong>: Nilai tengah dari data post-test tiap kelompok.</li>
                        <li><strong>Standar Deviasi</strong>: Ukuran variasi atau sebaran skor dalam satu kelompok.</li>
                        <li><strong>Jumlah (N)</strong>: Jumlah siswa dalam masing-masing kelompok.</li>
                    </ul>
                </div>
            `;
        }
    }

    function setupMoveStudentModal() {
        document.querySelectorAll('.btn-move-student-class').forEach(button => {
            button.addEventListener('click', function () {
                const classId = this.getAttribute('data-class-id');
                const moduleId = this.getAttribute('data-module-id');

                fetch(`/dashboard/admin/testing/setting/class/${classId}/module/${moduleId}/summary`)
                    .then(res => res.json())
                    .then(data => {
                    const expBody = document.getElementById('experiment-student-body');
                    const ctrlBody = document.getElementById('control-student-body');
                    expBody.innerHTML = '';
                    ctrlBody.innerHTML = '';

                    document.getElementById('MoveClass-class-name').textContent = data.class_name;
                    document.getElementById('MoveClass-module-name').textContent = data.module_name;

                    // Inject checkbox row
                    data.students.forEach(row => {
                        const checkbox = `<td><input type="checkbox" class="form-check-input student-checkbox ${row.class_type}-checkbox" data-id="${row.id}" data-class="${row.class_type}"></td>`;
                        const html = `<tr>${checkbox}<td>${row.name}</td><td>${row.pre_test}</td></tr>`;
                        if (row.class_type === 'experiment') expBody.innerHTML += html;
                        else if (row.class_type === 'control') ctrlBody.innerHTML += html;
                    });

                    // 👇 Taruh di sini SETELAH checkbox muncul
                    setupCheckboxBehaviors();

                    // Tampilkan modal
                    const modal = new bootstrap.Modal(document.getElementById('moveStudentClassModal'));
                    modal.show();

                    // Reset saat modal ditutup
                    document.getElementById('moveStudentClassModal').addEventListener('hidden.bs.modal', function () {
                        resetCheckboxStates();
                        document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = false);
                        document.getElementById('checkAllExperiment').checked = false;
                        document.getElementById('checkAllControl').checked = false;
                    });
                });

            });
        });
        
    }

    function setupCheckboxBehaviors() {
        const allExperimentCheckboxes = document.querySelectorAll('.experiment-checkbox');
        const allControlCheckboxes = document.querySelectorAll('.control-checkbox');

        function disableOtherGroup(selectedGroup) {
            if (selectedGroup === 'experiment') {
                allControlCheckboxes.forEach(cb => {
                    cb.disabled = true;
                    cb.closest('tr').style.opacity = 0.4;
                });
            } else if (selectedGroup === 'control') {
                allExperimentCheckboxes.forEach(cb => {
                    cb.disabled = true;
                    cb.closest('tr').style.opacity = 0.4;
                });
            }
        }

        function resetCheckboxStates() {
            allExperimentCheckboxes.forEach(cb => {
                cb.disabled = false;
                cb.closest('tr').style.opacity = 1;
            });
            allControlCheckboxes.forEach(cb => {
                cb.disabled = false;
                cb.closest('tr').style.opacity = 1;
            });
        }

        // Event check all
        document.getElementById('checkAllExperiment').addEventListener('change', function () {
            const isChecked = this.checked;
            allExperimentCheckboxes.forEach(cb => {
                if (!cb.disabled) cb.checked = isChecked;
            });
            allExperimentCheckboxes.forEach(cb => cb.dispatchEvent(new Event('change')));
        });

        document.getElementById('checkAllControl').addEventListener('change', function () {
            const isChecked = this.checked;
            allControlCheckboxes.forEach(cb => {
                if (!cb.disabled) cb.checked = isChecked;
            });
            allControlCheckboxes.forEach(cb => cb.dispatchEvent(new Event('change')));
        });

        // Listener validasi satu grup
        document.querySelectorAll('.student-checkbox').forEach(cb => {
            cb.addEventListener('change', () => {
                const checkedExp = Array.from(allExperimentCheckboxes).some(cb => cb.checked);
                const checkedCtrl = Array.from(allControlCheckboxes).some(cb => cb.checked);

                if (checkedExp && !checkedCtrl) disableOtherGroup('experiment');
                else if (!checkedExp && checkedCtrl) disableOtherGroup('control');
                else if (!checkedExp && !checkedCtrl) resetCheckboxStates();
            });
        });

        // Tombol submit
        const submitBtn = document.getElementById('submitMoveStudentBtn');
        submitBtn.replaceWith(submitBtn.cloneNode(true)); // clear old listeners

        document.getElementById('submitMoveStudentBtn').addEventListener('click', function () {
            const checked = document.querySelectorAll('.student-checkbox:checked');
            if (checked.length === 0) return alert("Silakan pilih siswa.");

            const firstType = checked[0].getAttribute('data-class');
            const targetType = firstType === 'experiment' ? 'control' : 'experiment';
            const studentIds = Array.from(checked)
                .map(cb => cb.getAttribute('data-id'))
                .filter(id => id && id !== 'undefined');
            
                console.log("Student IDs:", studentIds);

            if (studentIds.length === 0) return alert("ID siswa tidak valid.");

            const url = this.getAttribute('data-url');

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    student_ids: studentIds,
                    target_type: targetType
                })
            }).then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || "Terjadi kesalahan.");
                }
            }).catch(err => {
                console.error(err);
                alert("Gagal memproses permintaan.");
            });
        });
    }



    function updateFormValues(form) {
        const classId = document.getElementById('selectedClassId').value;
        const moduleId = document.getElementById('selectedModuleId').value;

        form.querySelector('input[name="class_id"]').value = classId;
        form.querySelector('input[name="module_id"]').value = moduleId;
    }
</script>




@endsection