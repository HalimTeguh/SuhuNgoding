<!-- Modal Progress Module -->
<div class="modal fade" id="ProgressModal" tabindex="-1" aria-labelledby="ProgressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ProgressModalLabel">Progress Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Kelas:</strong> <span id="class-name">-</span><br>
                    <strong>Modul:</strong> <span id="module-name">-</span>
                </div>
                <div id="progress-result" class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Tabel akan diisi lewat JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let currentClassId = null;
let currentModuleId = null;
let chartInstances = [];

document.addEventListener('DOMContentLoaded', function () {
    $(document).on('click', '.open-progress-modal', function (e) {
        e.preventDefault();

        currentClassId = $(this).data('class-id');
        currentModuleId = $(this).data('module-id');
        const className = $(this).data('class-name');
        const moduleTitle = $(this).data('module-title');

        $('#class-name').text(className);
        $('#module-name').text(moduleTitle);

        const modalEl = document.getElementById('ProgressModal');
        if (modalEl) {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        } else {
            console.error('Modal ProgressModal tidak ditemukan!');
        }
    });

    $('#ProgressModal').on('shown.bs.modal', function () {
        if (!currentClassId || !currentModuleId) return;

        $('#progress-result').html('<p>Loading...</p>');

        $.ajax({
            url: `/dashboard/teacher/pembelajaran/class/${currentClassId}/modules/${currentModuleId}/progress`,
            type: 'GET',
            success: function (response) {

                chartInstances.forEach(chart => chart.destroy());
                chartInstances = [];

                const students = response.students;
                const jumlahKonten = response.module_content_count;

                let labels = [];
                for (let i = 1; i <= jumlahKonten; i++) {
                    labels.push(i);
                }

                // Bangun tabel HTML
                let tableHtml = `
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 250px;">Nama & NIM</th>
                                <th>Grafik Progres Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                students.forEach(student => {
                    const canvasId = `chart-${student.student_id}`;

                    let chartColumn = '';

                    if (student.class_type === 'experiment') {
                        chartColumn = `<canvas id="${canvasId}" height="300"></canvas>`;
                    } else {
                        chartColumn = `
                            <div class="alert alert-warning mb-0" role="alert">
                                Siswa ini merupakan bagian dari kelas <strong>kontrol</strong>, sehingga grafik progres tidak tersedia.
                            </div>`;
                    }

                    tableHtml += `
                        <tr>
                            <td>
                                <strong>${student.student_name}</strong><br>
                                <small>NIM: ${student.student_nim}</small><br>
                                <span class="badge bg-${student.class_type === 'experiment' ? 'success' : 'secondary'} mt-2">
                                    ${student.class_type ?? 'Unknown'}
                                </span>
                            </td>
                            <td>
                                ${chartColumn}
                            </td>
                        </tr>
                    `;
                });


                tableHtml += `</tbody></table>`;
                $('#progress-result').html(tableHtml);

                // Render chart untuk setiap siswa
                students.forEach(student => {
                    if (student.class_type !== 'experiment') return; // hanya untuk kelas eksperimen

                    const canvas = document.getElementById(`chart-${student.student_id}`);
                    if (!canvas) return;

                    const ctx = canvas.getContext('2d');
                    const chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Nilai',
                                data: student.scores,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    title: {
                                        display: true,
                                        text: 'Nilai'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Pertemuan'
                                    }
                                }
                            }
                        }
                    });

                    chartInstances.push(chart);
                });
            },


            error: function () {
                $('#progress-result').html('<p class="text-danger">Gagal memuat data.</p>');
            }
        });
    });

    $('#ProgressModal').on('hidden.bs.modal', function () {
        $('#progress-result').html('');
        chartInstances.forEach(chart => chart.destroy());
        chartInstances = [];
        currentClassId = null;
        currentModuleId = null;
    });
});

</script>