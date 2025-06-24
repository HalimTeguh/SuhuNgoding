@extends('layout.dashboard')

@section('content')

@php
    $tTest = $user->student->tTests()->first();
@endphp

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    @if($tTest && $tTest->class_type == 'experiment')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4">
            <div class="col-xxl-8 mb-3 order-0">
                <div class="card">
                    <div class="d-flex align-items-start row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">
                                    Keep Going, {{ $user->name }}! 🚀
                                </h5>
                                <p class="mb-6">
                                    Kamu sedang berada di jalur belajar yang luar biasa. Yuk, lanjutkan perjalanan
                                    belajarmu!
                                </p>

                                <p class="mb-6">
                                    {{ $descLabel }}
                                </p>

                                @if($summaryModule && $nextUrl)
                                <a href="{{ $nextUrl }}" class="btn btn-sm btn-primary">
                                    {{ $buttonLabel }}
                                </a>
                                @else
                                <a href="javascript:;" class="btn btn-sm btn-outline-secondary" disabled>
                                    Kamu belum memulai belajar. Mulailah hari ini!
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left mt-auto">
                            <div class="card-body pb-0 px-0 px-md-6">
                                <img src="{{ $imagePath }}" height="175" class="scaleX-n1-rtl" alt="Motivation Image" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-4 col-md-4 order-1">
                <div class="row">
                    <!-- Card: Total Points -->
                    <div class="col-lg-6 col-md-12 col-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                    <div class="avatar flex-shrink-0 bg-primary text-white rounded d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="bx bx-medal bx-sm"></i>
                                    </div>
                                </div>
                                <p class="mb-1">Total Points</p>
                                <h4 class="card-title">{{ $totalPoints }} <small
                                        class="text-muted fw-light">Points</small> </h4>
                                <small class="text-success fw-medium">
                                    <i class="bx bx-up-arrow-alt"></i> Keep Up the Good Work!
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Progress Materi -->
                    <div class="col-lg-6 col-md-12 col-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                    <div class="avatar flex-shrink-0 bg-info text-white rounded d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="bx bx-book-reader bx-sm"></i>
                                    </div>
                                </div>
                                <p class="mb-1">Progress</p>
                                <h4 class="card-title">
                                    {{ $completedContents }}/{{ $totalContents }} <small
                                        class="text-muted fw-light">Materi</small>
                                </h4>
                                <small class="text-primary fw-medium">
                                    <i class="bx bx-book-open"></i> Teruskan perjalanan belajarmu!
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--/ Summary Leaderboard -->
            <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-2 order-md-2">
                <div class="row">
                    <!-- Posisi User -->
                    <div class="col-12 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                    <div class="avatar flex-shrink-0 bg-primary text-white rounded d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="bx bx-user-check bx-sm"></i>
                                    </div>
                                    <div class="dropdown">
                                        <select id="moduleSelectLeaderboard" class="form-select form-select-sm">
                                            <option value="overall">Overall</option>
                                            @foreach($modules as $module)
                                            <option value="{{ $module->id }}">{{ $module->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <p class="mb-1">Posisi Anda Saat Ini</p>
                                <h4 class="card-title mb-3">
                                    #<span id="myPosition">{{ $myLeaderboardPosition ?? '-' }}</span>
                                </h4>
                                <small class="text-success fw-medium">
                                    <i class="bx bx-medal"></i> Pertahankan posisimu!
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Leaderboard Top 3 -->
                    <div class="col-12 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                    <div class="avatar flex-shrink-0 bg-info text-white rounded d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="bx bx-trophy bx-sm"></i>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOptTop3" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOptTop3">
                                            <a class="dropdown-item"
                                                href="{{ route('dashboard.student.class.leaderboard') }}">Lihat
                                                Semua</a>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-3">Leaderboard Teratas</p>
                                <ul class="list-unstyled mb-0" id="topThreeLeaderboard">
                                    @foreach($topThreeLeaderboards as $index => $entry)
                                    <li class="d-flex align-items-center mb-3">
                                        <div class="avatar flex-shrink-0 bg-label-primary rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px;">
                                            {{ $index === 0 ? '🥇' : ($index === 1 ? '🥈' : '🥉') }}
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-0">{{ $entry->student->user->name ?? 'Unknown' }}</h6>
                                            <small class="text-muted">Point: {{ $entry->point }}</small>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Open Module -->
            <div class="col-xxl-8 mb-6 order-3">
                <div class="card border-0 shadow-lg rounded-3 mb-3">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="m-0 me-2 mb-3">Pilih Kelas Anda</h5>

                        <div class="row">
                            @foreach ($classes as $class)
                            <div class="{{ count($classes) === 1 ? 'col-12' : 'col-xxl-6 col-md-6' }} mb-4">
                                <a href="{{ route('dashboard.student.class', ['id' => $class->id]) }}"
                                    class="text-decoration-none">
                                    <div class="card text-white overflow-hidden border-0 rounded shadow" style="
                                transition: transform 0.3s ease, box-shadow 0.3s ease;
                                background-image: url('{{ $class->image ? asset('storage/' . $class->image) : asset('/assets/img/default-class.jpg') }}');
                                background-size: cover;
                                background-position: center;
                                min-height: 200px;
                                cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'"
                                        onmouseout="this.style.transform='translateY(0)'">

                                        {{-- Gradient overlay --}}
                                        <div style="
                                position: absolute;
                                inset: 0;
                                background: linear-gradient(to right, rgba(0,0,0,0.7), rgba(0,0,0,0.1));
                                z-index: 1;">
                                        </div>

                                        {{-- Content --}}
                                        <div class="card-body d-flex justify-content-between align-items-center h-100 position-relative"
                                            style="z-index: 2;">
                                            <div>
                                                <h5 class="card-title mb-2 text-white">
                                                    <strong>{{ $class->name }}</strong>
                                                </h5>
                                                <p class="mb-1">
                                                    <strong>Guru:</strong> {{ $class->teacher->user->name ?? '-' }}
                                                </p>
                                                <p class="mb-0 text-truncate-2">
                                                    {{ $class->description ?? 'Tidak ada deskripsi.' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-lg rounded-3">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="m-0 me-2 mb-4">History Progress</h5>

                        <div class="table-responsive">
                            <table
                                class="table table-hover table-bordered align-middle {{ $summaries->isEmpty() ? 'd-none' : '' }}"
                                id="historyProgress">
                                <thead class="table-light">
                                    <tr>
                                        <th>Module</th>
                                        <th>Content</th>
                                        <th>Status</th>
                                        <th>Score</th>
                                        <th>Study Duration</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($summaries as $summary)
                                    <tr>
                                        <td>{{ $summary->content->module->title ?? '-' }}</td>
                                        <td>{{ $summary->content->title ?? '-' }}</td>
                                        <td>
                                            @if($summary->status === 'Lulus')
                                            <span class="badge bg-success">Lulus</span>
                                            @elseif($summary->status === 'Tidak Lulus')
                                            <span class="badge bg-danger">Tidak Lulus</span>
                                            @elseif($summary->study_content_total_duration > 0 &&
                                            $summary->quiz_attemptz_count == 0)
                                            <span class="badge bg-info">Sedang Belajar</span>
                                            @else
                                            <span class="badge bg-secondary">Belum</span>
                                            @endif
                                        </td>
                                        <td>{{ $summary->total_score ?? '-' }}</td>
                                        <td>{{ gmdate("H:i:s", $summary->study_content_total_duration) }}</td>
                                        <td>
                                            {{ $summary->updated_at
                                            ? \Carbon\Carbon::parse($summary->updated_at)->format('d M Y H:i')
                                            : '-' }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="bi bi-exclamation-circle"></i> Tidak ada data progress yang
                                            tersedia.
                                        </td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                            @if($summaries->isEmpty() )
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="bi bi-exclamation-circle"></i> Tidak ada data progress yang
                                    tersedia.
                                </td>
                            </tr>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @elseif($tTest && $tTest->class_type == 'control')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4">
            <div class="col-xxl-8 mb-3 order-0">
                <div class="card">
                    <div class="d-flex align-items-start row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">
                                    Welcome, {{ $user->name }}! 🚀
                                </h5>
                                <p class="mb-6">
                                    Anda berada pada kelas kontrol. silahkan buka kelas anda dan mulai belajar
                                </p>

                                <p class="mb-6">
                                    {{ $descLabel }}
                                </p>

                                @if($summaryModule && $nextUrl)
                                <a href="{{ $nextUrl }}" class="btn btn-sm btn-primary">
                                    {{ $buttonLabel }}
                                </a>
                                @else
                                <a href="{{ route('dashboard.student.class') }}" class="btn btn-sm btn-outline-secondary" disabled>
                                    Ayo belajar hari ini!
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left mt-auto">
                            <div class="card-body pb-0 px-0 px-md-6">
                                <img src="{{ $imagePath }}" height="175" class="scaleX-n1-rtl" alt="Motivation Image" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-4 col-md-4 order-1">
                <div class="row">
                    <!-- Card: Progress Materi -->
                    <div class="col-lg-12  col-md-12 col-6 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                    <div class="avatar flex-shrink-0 bg-info text-white rounded d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="bx bx-book-reader bx-sm"></i>
                                    </div>
                                </div>
                                <p class="mb-1">Progress</p>
                                <h4 class="card-title">
                                    {{ $completedContents }}/{{ $totalContents }} <small
                                        class="text-muted fw-light">Materi</small>
                                </h4>
                                <small class="text-primary fw-medium">
                                    <i class="bx bx-book-open"></i> Teruskan perjalanan belajarmu!
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Open Module -->
            <div class="col-xxl-12 mb-6 order-3">
                <div class="card border-0 shadow-lg rounded-3 mb-3">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="m-0 me-2 mb-3">Pilih Kelas Anda</h5>

                        <div class="row">
                            @foreach ($classes as $class)
                            <div class="{{ count($classes) === 1 ? 'col-12' : 'col-xxl-6 col-md-6' }} mb-4">
                                <a href="{{ route('dashboard.student.class', ['id' => $class->id]) }}"
                                    class="text-decoration-none">
                                    <div class="card text-white overflow-hidden border-0 rounded shadow" style="
                                transition: transform 0.3s ease, box-shadow 0.3s ease;
                                background-image: url('{{ $class->image ? asset('storage/' . $class->image) : asset('/assets/img/default-class.jpg') }}');
                                background-size: cover;
                                background-position: center;
                                min-height: 200px;
                                cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'"
                                        onmouseout="this.style.transform='translateY(0)'">

                                        {{-- Gradient overlay --}}
                                        <div style="
                                position: absolute;
                                inset: 0;
                                background: linear-gradient(to right, rgba(0,0,0,0.7), rgba(0,0,0,0.1));
                                z-index: 1;">
                                        </div>

                                        {{-- Content --}}
                                        <div class="card-body d-flex justify-content-between align-items-center h-100 position-relative"
                                            style="z-index: 2;">
                                            <div>
                                                <h5 class="card-title mb-2 text-white">
                                                    <strong>{{ $class->name }}</strong>
                                                </h5>
                                                <p class="mb-1">
                                                    <strong>Guru:</strong> {{ $class->teacher->user->name ?? '-' }}
                                                </p>
                                                <p class="mb-0 text-truncate-2">
                                                    {{ $class->description ?? 'Tidak ada deskripsi.' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-lg rounded-3">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="m-0 me-2 mb-4">History Progress</h5>

                        <div class="table-responsive">
                            <table
                                class="table table-hover table-bordered align-middle {{ $summaries->isEmpty() ? 'd-none' : '' }}"
                                id="historyProgress">
                                <thead class="table-light">
                                    <tr>
                                        <th>Module</th>
                                        <th>Content</th>
                                        <th>Status</th>
                                        <th>Score</th>
                                        <th>Study Duration</th>
                                        <th>Updated At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($summaries as $summary)
                                    <tr>
                                        <td>{{ $summary->content->module->title ?? '-' }}</td>
                                        <td>{{ $summary->content->title ?? '-' }}</td>
                                        <td>
                                            @if($summary->status === 'Lulus')
                                            <span class="badge bg-success">Lulus</span>
                                            @elseif($summary->status === 'Tidak Lulus')
                                            <span class="badge bg-danger">Tidak Lulus</span>
                                            @elseif($summary->study_content_total_duration > 0 &&
                                            $summary->quiz_attemptz_count == 0)
                                            <span class="badge bg-info">Sedang Belajar</span>
                                            @else
                                            <span class="badge bg-secondary">Belum</span>
                                            @endif
                                        </td>
                                        <td>{{ $summary->total_score ?? '-' }}</td>
                                        <td>{{ gmdate("H:i:s", $summary->study_content_total_duration) }}</td>
                                        <td>
                                            {{ $summary->updated_at
                                            ? \Carbon\Carbon::parse($summary->updated_at)->format('d M Y H:i')
                                            : '-' }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            <i class="bi bi-exclamation-circle"></i> Tidak ada data progress yang
                                            tersedia.
                                        </td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                            @if($summaries->isEmpty() )
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="bi bi-exclamation-circle"></i> Tidak ada data progress yang
                                    tersedia.
                                </td>
                            </tr>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @elseif($tTest && $tTest->class_type == null)

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4">
            <div class="col-xxl-12 mb-3 order-0">
                <div class="card">
                    <div class="d-flex align-items-start row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">
                                    Welcome, {{ $user->name }}! 🚀
                                </h5>
                                <p class="mb-6">
                                    Sebelum mulai belajar, kerjakan <strong>pretest</strong> terlebih dahulu agar tau kemampuan mu sebelum belajar
                                </p>

                                <p class="mb-6">
                                    {{ $descLabel }}
                                </p>

                                @if($summaryModule && $nextUrl)
                                <a href="{{ $nextUrl }}" class="btn btn-sm btn-primary">
                                    {{ $buttonLabel }}
                                </a>
                                @else
                                <a href="{{ route('dashboard.student.pretest') }}" class="btn btn-sm btn-outline-secondary" disabled>
                                    Mulai mengerjakan Pretest!
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left mt-auto">
                            <div class="card-body pb-0 px-0 px-md-6">
                                <img src="{{ $imagePath }}" height="175" class="scaleX-n1-rtl" alt="Motivation Image" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    @endif

    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const table = $('#historyProgress');
        if (table.find('tbody tr').length > 1 || !table.find('td').hasClass('text-muted')) {
            table.DataTable({
                lengthMenu: [[5, 10, 50], [5, 10, 50]],
                paging: true,
                ordering: true,
                order: [[5, 'desc']],
                responsive: true,
                language: {
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ada data ditemukan",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                    search: "Cari:",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                },
                columnDefs: [
                    { targets: [3, 4], className: 'text-center' },
                    { targets: [2], className: 'text-center' },
                ]
            });
        }
    });

    const leaderboardData = @json($allLeaderboards); // Format: {'overall': [...], 'module_id_1': [...], ...}
    const myPositions = @json($allMyPositions); // Format: {'overall': 3, 'module_id_1': 1, ...}
    const moduleSelect = document.getElementById('moduleSelectLeaderboard');
    const leaderboardList = document.getElementById('topThreeLeaderboard');
    const myPositionLabel = document.getElementById('myPosition');

    moduleSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        // Update posisi
        myPositionLabel.textContent = myPositions[selectedValue] || '-';
        // Update top 3
        leaderboardList.innerHTML = '';
        const topEntries = leaderboardData[selectedValue]?.slice(0, 5) || [];
        if (topEntries.length > 0) {
            topEntries.forEach((entry, index) => {
                const rank = index === 0 ? '🥇' : (index === 1 ? '🥈' : '🥉');
                leaderboardList.insertAdjacentHTML('beforeend', `
                    <li class="d-flex align-items-center mb-3">
                        <div class="avatar flex-shrink-0 bg-label-primary rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 36px; height: 36px;">
                            ${rank}
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0">${entry.student.user.name || 'Unknown'}</h6>
                            <small class="text-muted">Point: ${entry.point}</small>
                        </div>
                    </li>
                `);
            });
        } else {
            leaderboardList.innerHTML = '<li class="text-muted text-center">Belum ada data leaderboard.</li>';
        }
    });

    
</script>
@endsection