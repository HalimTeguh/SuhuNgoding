<!-- Modal Move Student Class Modal -->
<div class="modal fade" id="moveStudentClassModal" tabindex="-1" aria-labelledby="moveStudentClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pindah Siswa ke Tipe Kelas Lain</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <strong>Kelas:</strong> <span id="MoveClass-class-name">-</span><br>
                    <strong>Modul:</strong> <span id="MoveClass-module-name">-</span>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h6>Kelas Eksperimen</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><input class="form-check-input" type="checkbox" id="checkAllExperiment"></th>
                                    <th>Nama</th>
                                    <th>NIM</th>
                                </tr>
                            </thead>
                            <tbody id="experiment-student-body">
                                <!-- Diisi via JS -->
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Kelas Kontrol</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><input class="form-check-input" type="checkbox" id="checkAllControl"></th>
                                    <th>Nama</th>
                                    <th>NIM</th>
                                </tr>
                            </thead>
                            <tbody id="control-student-body">
                                <!-- Diisi via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button type="button" id="submitMoveStudentBtn" class="btn btn-success" data-url="{{ route('testing.moveClass') }}">
                        Pindahkan Siswa ke Tipe Kelas Lain
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
