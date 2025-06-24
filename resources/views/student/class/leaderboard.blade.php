@extends('layout.dashboard')

@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y mb-4">
        <div class="row">

            <!-- Leaderboard -->
            <div class="col-12 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Leaderboard</h5>
                        <div class="ms-auto d-flex flex-wrap align-items-start gap-3">
                            <div class="d-flex flex-column">
                                <label for="classSelect" class="form-label mb-1">Class</label>
                                <select id="classSelect" class="form-select w-auto">
                                    <option value="">Select Class</option>
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex flex-column">
                                <label for="moduleSelect" class="form-label mb-1">Module</label>
                                <select id="moduleSelect" class="form-select w-auto">
                                    <option value="">Select Module</option>
                                    <!-- Module options will be filled dynamically -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <ul id="leaderboardList" class="p-0 m-0 mx-8">
                            <!-- Data leaderboard akan diisi oleh JS -->
                        </ul>
                    </div>
                </div>
            </div>

            <!-- History Progress -->
            <div class="col-12 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">History Progress</h5>
                    </div>
                    <div class="card-body pt-4">
                        <table class="table table-hover" id="historyProgress">
                            <thead>
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
                                        @elseif($summary->study_content_total_duration > 0 && $summary->quiz_attemptz_count == 0)
                                        <span class="badge bg-info">Sedang Belajar</span>
                                        @else
                                        <span class="badge bg-secondary">Belum</span>
                                        @endif
                                    </td>
                                    <td>{{ $summary->total_score ?? '-' }}</td>
                                    <td>{{ gmdate("H:i:s", $summary->study_content_total_duration) }}</td>
                                    <td>
                                        {{ $summary->created_at
                                        ? \Carbon\Carbon::parse($summary->updated_at)->format('d M Y H:i')
                                        : '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No progress data available.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- / Content -->
    <div class="content-backdrop fade"></div>
</div>

<script>
    const classes = @json($classes);
    const leaderboards = @json($leaderboards);
    const leaderboardList = document.getElementById('leaderboardList');
    const classSelect = document.getElementById('classSelect');
    const moduleSelect = document.getElementById('moduleSelect');

    

    // Populate module dropdown saat class berubah
    classSelect.addEventListener('change', function() {
        const selectedClassId = this.value;
        moduleSelect.innerHTML = '<option value="">Select Module</option>';  // Reset
        if (selectedClassId) {
            const selectedClass = classes.find(c => c.id == selectedClassId);
            if (selectedClass) {
                selectedClass.modules.forEach(module => {
                    const option = document.createElement('option');
                    option.value = module.id;
                    option.textContent = module.title;
                    moduleSelect.appendChild(option);
                });
            }
        }
        leaderboardList.innerHTML = '<li class="text-muted text-center">Select a module to see leaderboard.</li>';
    });

    // Tampilkan leaderboard saat module berubah
    moduleSelect.addEventListener('change', function() {
        const classId = classSelect.value;
        const moduleId = this.value;
        renderLeaderboard(classId, moduleId);
    });

    // Render leaderboard
    function renderLeaderboard(classId, moduleId) {
        leaderboardList.innerHTML = '';
        if (classId && moduleId && leaderboards[classId] && leaderboards[classId][moduleId]) {
            const entries = leaderboards[classId][moduleId];
            entries.forEach((entry, index) => {
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

// Optional: set default class dan module saat page load
document.addEventListener('DOMContentLoaded', () => {
    $(document).ready(function() {
            $('#historyProgress').DataTable({
                lengthMenu: [ [10, 25, 50, 75, 100], [10, 25, 50, 75, 100] ],
                paging: true,
                searching: true,
                info: true,
                ordering: true,
                responsive: true,
                language: {
                    searchPlaceholder: "Search...",
                    search: "",
                    lengthMenu: "Show _MENU_ entries",
                    zeroRecords: "No data found",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                },
                order: [[5, 'desc']]
            });
        });

    if (classes.length > 0) {
        classSelect.value = classes[0].id;
        const selectedClass = classes[0];
        if (selectedClass.modules.length > 0) {
            selectedClass.modules.forEach(module => {
                const option = document.createElement('option');
                option.value = module.id;
                option.textContent = module.title;
                moduleSelect.appendChild(option);
            });
            moduleSelect.value = selectedClass.modules[0].id;
            renderLeaderboard(selectedClass.id, selectedClass.modules[0].id);
        }
    }
});
</script>

@endsection