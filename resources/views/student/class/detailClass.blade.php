@extends('layout.dashboard')

@section('content')

@php
$isExperiment = $classType === 'experiment';
@endphp

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">



            @if($isExperiment)
            {{-- Tampilkan leaderboard dan bloom level --}}

            <div class="col-12 col-lg-8 mb-4">
                <div class="card border-0 shadow-sm h-100 transition-shadow"
                    style="transition: box-shadow 0.3s ease-in-out;">
                    <div class="row g-0 align-items-center">
                        <!-- Text Content -->
                        <div class="col-md-7 p-4">
                            <h5 class="card-title text-primary fw-bold mb-3">Welcome to Class: {{ $class->name }} 🎉
                            </h5>
                            <div class="text-muted">
                                <p class="mb-2"><strong>Teacher:</strong> {{ $teacherName }}</p>
                                <p class="mb-2"><strong>Students:</strong> {{ $studentCount }}</p>
                                <p class="mb-2"><strong>Modules:</strong> {{ $moduleCount }}</p>
                                <p class="mb-0">{{ $class->description ?? 'No description
                                    available.' }}</p>
                            </div>
                        </div>
                        <!-- Image -->
                        <div class="col-md-5 text-center text-md-end p-4">
                            <img src="{{ $class->image ? asset('storage/' . $class->image) : asset('/assets/img/illustrations/default-class-image.png') }}"
                                alt="Class Image" class="img-fluid rounded-3"
                                style="max-height: 180px; object-fit: cover; transition: transform 0.3s ease-in-out;"
                                onmouseover="this.style.transform='scale(1.05)'"
                                onmouseout="this.style.transform='scale(1)'" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 order-1">
                <div class="row">
                    <div class="col-12 mb-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">Your Bloom Level Progress 🎉</h5>

                                <!-- Bar Chart untuk Bloom Levels -->
                                <canvas id="bloomChart" width="400" height="200"></canvas>
                                {{-- <canvas id="bloomLevelChart" width="400" height="200"></canvas> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Module -->
            <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-lg-8">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="card-title mb-0">
                                    <h5 class="m-0 me-2">Your Module</h5>
                                </div>
                            </div>
                            <div class="px-3">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Material</th>
                                            <th class="text-end">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contentTable">
                                        <!-- Diisi oleh JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-4 d-flex align-items-start">
                            <div class="card-body px-xl-9">
                                <div class="text-center mb-6">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary"
                                            id="moduleDropdownLabel">Module</button>
                                        <button type="button"
                                            class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" id="moduleDropdownMenu">
                                            @foreach ($modules as $module)
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0);"
                                                    onclick="changeModule({{ $module->id }})">
                                                    {{ $module->title }}
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div id="PreviewModuleContainer" class="preview-module-container text-center m-auto">
                                    <img id="imagePreviewModul"
                                        class="img-fluid rounded preview-module-container {{ ($module->image) ? '' : 'd-none' }}"
                                        src="{{ $module->image ? asset('storage/'. $module->image) : '' }}"
                                        alt="Module Image">
                                    <i id="ImageIconModul"
                                        class="fa-regular fa-image preview-module-icon {{ ($module->image) ? 'd-none' : '' }}"></i>
                                </div>

                                <div class="text-center fw-medium my-6" id="progressBar">0%</div>

                                <div class="text-center fw-medium my-6" id="moduleDescription"> {!! $module->description
                                    !!}</div>

                                <div class="d-flex gap-3 justify-content-between">
                                    <div class="d-flex">
                                        <div class="avatar me-2">
                                            <span class="avatar-initial rounded-2 bg-label-primary"><i
                                                    class="bx bx-dollar bx-lg text-primary"></i></span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <small>
                                                All
                                            </small>
                                            <h6 class="mb-0" id="totalMateri">-</h6>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="avatar me-2">
                                            <span class="avatar-initial rounded-2 bg-label-info"><i
                                                    class="bx bx-wallet bx-lg text-info"></i></span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <small>
                                                Completed
                                            </small>
                                            <h6 class="mb-0" id="selesaiMateri">-</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--/ LeaderBoard -->
            <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
                <div class="row">
                    <div class="mb-6">
                        <div class="card h-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">Leaderboard</h5>

                            </div>
                            <div class="card-body pt-4">
                                <ul id="leaderboardList" class="p-0 m-0">
                                    <!-- Data leaderboard akan diisi oleh JS -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Kelas Control --}}
            @if(!$isExperiment)

            <div class="col-12 col-lg-12 mb-4">
                <div class="card border-0 shadow-sm h-100 transition-shadow"
                    style="transition: box-shadow 0.3s ease-in-out;">
                    <div class="row g-0 align-items-center">
                        <!-- Text Content -->
                        <div class="col-md-7 p-4">
                            <h5 class="card-title text-primary fw-bold mb-3">Welcome to Class: {{ $class->name }} 🎉
                            </h5>
                            <div class="text-muted">
                                <p class="mb-2"><strong>Teacher:</strong> {{ $teacherName }}</p>
                                <p class="mb-2"><strong>Students:</strong> {{ $studentCount }}</p>
                                <p class="mb-2"><strong>Modules:</strong> {{ $moduleCount }}</p>
                                <p class="mb-0">{{ $class->description ?? 'No description
                                    available.' }}</p>
                            </div>
                        </div>
                        <!-- Image -->
                        <div class="col-md-5 text-center text-md-end p-4">
                            <img src="{{ $class->image ? asset('storage/' . $class->image) : asset('/assets/img/illustrations/default-class-image.png') }}"
                                alt="Class Image" class="img-fluid rounded-3"
                                style="max-height: 180px; object-fit: cover; transition: transform 0.3s ease-in-out;"
                                onmouseover="this.style.transform='scale(1.05)'"
                                onmouseout="this.style.transform='scale(1)'" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Module -->
            <div class="col-12 col-xxl-12 order-2 order-md-3 order-xxl-2 mb-6">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-lg-9">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="card-title mb-0">
                                    <h5 class="m-0 me-2">Your Module</h5>
                                </div>
                            </div>
                            <div class="px-3">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Material</th>
                                            <th class="text-end">Link Material</th>
                                            <th class="text-end">Link Test</th>
                                            <th class="text-end">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contentTable">
                                        <!-- Diisi oleh JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-3 d-flex align-items-start">
                            <div class="card-body px-xl-9">
                                <div class="text-center mb-6">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary"
                                            id="moduleDropdownLabel">Module</button>
                                        <button type="button"
                                            class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu" id="moduleDropdownMenu">
                                            @foreach ($modules as $module)
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0);"
                                                    onclick="changeModule({{ $module->id }})">
                                                    {{ $module->title }}
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div id="PreviewModuleContainer" class="preview-module-container text-center m-auto">
                                    <img id="imagePreviewModul"
                                        class="img-fluid rounded preview-module-container {{ ($module->image) ? '' : 'd-none' }}"
                                        src="{{ $module->image ? asset('storage/'. $module->image) : '' }}"
                                        alt="Module Image">
                                    <i id="ImageIconModul"
                                        class="fa-regular fa-image preview-module-icon {{ ($module->image) ? 'd-none' : '' }}"></i>
                                </div>

                                <div class="text-center fw-medium my-6" id="progressBar">0%</div>

                                <div class="text-center fw-medium my-6" id="moduleDescription"> {!! $module->description
                                    !!}</div>

                                <div class="d-flex gap-3 justify-content-between">
                                    <div class="d-flex">
                                        <div class="avatar me-2">
                                            <span class="avatar-initial rounded-2 bg-label-primary"><i
                                                    class="bx bx-dollar bx-lg text-primary"></i></span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <small>
                                                All
                                            </small>
                                            <h6 class="mb-0" id="totalMateri">-</h6>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="avatar me-2">
                                            <span class="avatar-initial rounded-2 bg-label-info"><i
                                                    class="bx bx-wallet bx-lg text-info"></i></span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <small>
                                                Completed
                                            </small>
                                            <h6 class="mb-0" id="selesaiMateri">-</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @endif


        </div>
    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const tbody = document.getElementById('contentTable');
    const modules = @json($modules);

    const label = document.getElementById('moduleDropdownLabel');
    const totalText = document.getElementById('totalMateri');
    const doneText = document.getElementById('selesaiMateri');
    const moduleDescription = document.getElementById('moduleDescription');

    const progressBar = document.getElementById('progressBar');
    const imagePreviewModul = document.getElementById('imagePreviewModul');
    const imageIconModul = document.getElementById('ImageIconModul');
    const classType = @json($classType);

    @if($isExperiment)
    const leaderboards = @json($leaderboards); // Data leaderboard untuk setiap module
    const leaderboardList = document.getElementById('leaderboardList');

    const bloomData = @json($bloomLevels);
    let bloomChartInstance = null;
    @endif

    function changeModule(moduleId) {
        const module = modules.find(m => m.id === moduleId);
        if (!module) return;

        label.textContent = module.title;
        tbody.innerHTML = '';
        let total = 0, done = 0;

        module.contents.forEach((content, index) => {
            total++;

            if (classType === 'control') {
                const control = content.control_data || {};
                const materialLink = control.material_link ? `<a href="${control.material_link}" target="_blank">Materi</a>` : '-';
                const testLink = control.test_link ? `<a href="${control.test_link}" target="_blank">Tes</a>` : '-';
                const notes = control.notes || '-';

                const row = `
                    <tr>
                        <td>${content.title}</td>
                        <td class="text-end">${materialLink}</td>
                        <td class="text-end">${testLink}</td>
                        <td class="text-end">${notes}</td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            } else {
                const status = (content.progress_status || 'belum').toLowerCase();
                let badgeClass = 'secondary';
                let statusLabel = content.progress_status || 'Belum';

                if (status.includes('lulus')) {
                    badgeClass = status.includes('tidak') ? 'danger' : 'success';
                } else if (status === 'sedang belajar') {
                    badgeClass = 'info';
                }

                if (status.includes('lulus') && !status.includes('tidak')) done++;

                let canOpen = false;
                if (index === 0) {
                    canOpen = true;
                } else {
                    const prevContent = module.contents[index - 1];
                    const prevStatus = (prevContent.progress_status || '').toLowerCase();
                    if (prevStatus.includes('lulus') && !prevStatus.includes('tidak')) {
                        canOpen = true;
                    }
                }

                const row = `
                    <tr style="cursor: ${canOpen ? 'pointer' : 'not-allowed'};"
                        ${canOpen ? `onclick="window.location='/dashboard/student/class/{{ $class->id }}/module/${content.id}'"` : ''}>
                        <td>${content.title}</td>
                        <td class="text-end">
                            <span class="badge bg-${badgeClass}">${statusLabel}</span>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            }
        });

        totalText.textContent = total;
        doneText.textContent = done;
        moduleDescription.innerHTML = module.description || '-';

        const percent = total > 0 ? Math.round((done / total) * 100) : 0;
        progressBar.textContent = `${percent}% Progression`;

        if (module.image) {
            imagePreviewModul.src = '{{ asset('storage/') }}' + '/' + module.image;
            imagePreviewModul.classList.remove('d-none');
            imageIconModul.classList.add('d-none');
        } else {
            imagePreviewModul.classList.add('d-none');
            imageIconModul.classList.remove('d-none');
        }

        if (classType === 'experiment') {
            showLeaderboard(moduleId);
            renderBloomChart(moduleId);
        }
    }


    function showLeaderboard(moduleId) {
        leaderboardList.innerHTML = ''; // Kosongkan daftar leaderboard sebelumnya
        
        if (moduleId && leaderboards[moduleId]) {
            const entries = leaderboards[moduleId];
            
            // Ambil hanya 10 data teratas
            const topEntries = entries.slice(0, 10);

            topEntries.forEach((entry, index) => {
                const name = entry.student.user.name || 'Unknown';
                const words = name.split(' ');
                const initials = words.map(word => word[0].toUpperCase()).join('').slice(0, 2);
                const shortName = words.slice(0, 2).join(' ');
                const rankDisplay = index === 0 ? '🥇' : index === 1 ? '🥈' : index === 2 ? '🥉' : `#${index + 1}`;
                
                const listItem = `
                    <li class="d-flex align-items-center mb-4">
                        <div class="avatar avatar-sm mb-2 me-6">
                            <span class="avatar-initial bg-label-primary rounded-circle text-white fw-bold text-uppercase d-inline-flex justify-content-center align-items-center" style="width: 40px; height: 40px; font-size: 14px;">
                                ${initials}
                            </span>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning" style="font-size: 12px;">
                                ${rankDisplay}
                            </span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <small class="text-muted d-block">${entry.student.NIS || '-'}</small>
                                <h6 class="mb-0">${shortName}</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">${entry.point}</h6>
                                <span class="text-muted">Point</span>
                            </div>
                        </div>
                    </li>
                `;
                leaderboardList.insertAdjacentHTML('beforeend', listItem);
            });
        } else {
            leaderboardList.innerHTML = '<li class="text-muted text-center">No leaderboard data available for this module.</li>';
        }
    }

    function renderBloomChart(moduleId) {
        const bloom = bloomData[moduleId];
        if (!bloom) return;

        const labels = [];
        const percentages = [];
        const corrects = [];
        const totals = [];

        for (const level in bloom) {
            labels.push(level.charAt(0).toUpperCase() + level.slice(1));

            // Tangani level 'create' yang tidak punya correct/total
            if (level === 'create') {
                percentages.push(bloom[level].percentage || 0);
                corrects.push('-'); // atau null
                totals.push('-');
            } else {
                percentages.push(bloom[level].percentage || 0);
                corrects.push(bloom[level].correct || 0);
                totals.push(bloom[level].total || 0);
            }
        }

        const ctx = document.getElementById('bloomChart').getContext('2d');

        if (bloomChartInstance) {
            bloomChartInstance.destroy();
        }

        bloomChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Bloom Level (%)',
                    data: percentages,
                    backgroundColor: 'rgba(105, 108, 255, 0.6)',
                    borderColor: 'rgba(105, 108, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const index = context.dataIndex;
                                const level = labels[index].toLowerCase();

                                if (level === 'create') {
                                    return `Nilai Final Project: ${percentages[index]}%`;
                                } else {
                                    return `Correct: ${corrects[index]} / ${totals[index]} (${percentages[index]}%)`;
                                }
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (modules.length > 0) changeModule(modules[0].id);
    });

</script>

@endsection