@extends('layout.dashboard')

@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-xxl-8 mb-6 order-0">
                <div class="card">
                    <div class="d-flex align-items-start row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">Congratulations John! 🎉</h5>
                                <p class="mb-6">
                                    You have done 72% more sales today.<br />Check your new badge in your profile.
                                </p>

                                <a href="javascript:;" class="btn btn-sm btn-outline-primary">View Badges</a>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-6">
                                <img src="{{ asset('/assets/img/illustrations/man-with-laptop.png') }}" height="175"
                                    class="scaleX-n1-rtl" alt="View Badge User" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 order-1">
                <div class="row">
                    <div class="col-12 mb-6">
                        <div class="card">
                            <div class="card-body">
                                <div
                                    class="d-flex justify-content-between align-items-center flex-sm-row flex-column gap-10">
                                    <div
                                        class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                        <div class="card-title mb-6">
                                            <h5 class="text-nowrap mb-1">Badges</h5>
                                            <span class="badge bg-label-warning">YEAR 2022</span>
                                        </div>
                                        <div class="mt-sm-auto">
                                            <span class="text-success text-nowrap fw-medium"><i
                                                    class="bx bx-up-arrow-alt"></i> 68.2%</span>
                                            <h4 class="mb-0">$84,686k</h4>
                                        </div>
                                    </div>
                                    <div id="profileReportChart"></div>
                                </div>
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

                                <div id="growthChart"></div>

                                <div class="text-center fw-medium my-6" id="progressBar">0%</div>

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
                                <ul class="p-0 m-0">
                                    @forelse($leaderboard->take(10) as $index => $entry)
                                    @php
                                    $name = $entry->student->user->name ?? 'Unknown';
                                    $words = explode(' ', $name);
                                    $initials = strtoupper(implode('', array_map(fn($word) => $word[0], $words)));
                                    $initials = substr($initials, 0, 2);

                                    $fullName = $entry->student->user->name ?? 'Unknown';
                                    $shortName = implode(' ', array_slice(explode(' ', $fullName), 0, 2));

                                    $rankDisplay = match ($index) {
                                    0 => '🥇',
                                    1 => '🥈',
                                    2 => '🥉',
                                    default => '#' . ($index + 1),
                                    };
                                    @endphp

                                    <li class="d-flex align-items-center mb-4">
                                        <div class="avatar avatar-sm mb-2 me-6">
                                            <span
                                                class="avatar-initial bg-label-primary rounded-circle text-white fw-bold text-uppercase d-inline-flex justify-content-center align-items-center"
                                                style="width: 40px; height: 40px; font-size: 14px;">
                                                {{ $initials }}
                                            </span>
                                            <span
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning"
                                                style="font-size: 12px;">
                                                {{ $rankDisplay }}
                                            </span>
                                        </div>
                                        <div
                                            class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                            <div class="me-2">
                                                <small class="text-muted d-block">{{ $entry->student->NIS ?? '-'
                                                    }}</small>
                                                <h6 class="mb-0">{{ $shortName }}</h6>
                                            </div>
                                            <div class="user-progress d-flex align-items-center gap-2">
                                                <h6 class="mb-0">{{ $entry->point }}</h6>
                                                <span class="text-muted">Point</span>
                                            </div>
                                        </div>
                                    </li>
                                    @empty
                                    <li class="text-muted text-center">Belum ada data leaderboard.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

<script>
    const modules = @json($modules); // <- Tambahkan ini di atas

    const label = document.getElementById('moduleDropdownLabel');
    const tbody = document.getElementById('contentTable');
    const totalText = document.getElementById('totalMateri');
    const doneText = document.getElementById('selesaiMateri');
    const progressBar = document.getElementById('progressBar');

    function changeModule(moduleId) {
        const module = modules.find(m => m.id === moduleId);
        if (!module) return;

        label.textContent = module.title;
        tbody.innerHTML = '';
        let total = 0, done = 0;

        module.contents.forEach(content => {
            total++;
            const status = content.progress_status || 'belum';
            const badge = status === 'done' ? 'success' : status === 'progres' ? 'info' : 'secondary';
            if (status === 'done') done++;

            const row = `
                <tr onclick="window.location='/dashboard/student/module/${content.id}'" style="cursor: pointer;">
                    <td>${content.title}</td>
                    <td class="text-end">
                        <span class="badge bg-${badge} d-inline-block">${status}</span>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', row);
        });

        totalText.textContent = total;
        doneText.textContent = done;
        const percent = total > 0 ? Math.round((done / total) * 100) : 0;
        progressBar.textContent = percent + '% Progression';
        console.log(total, done, percent, progressBar);
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (modules.length > 0) changeModule(modules[0].id);
    });
</script>

@endsection