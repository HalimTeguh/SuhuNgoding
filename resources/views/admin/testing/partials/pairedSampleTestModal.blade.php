<!-- Modal Paired Sample Test Modal -->
<div class="modal fade" id="pairedSampleTestModal" tabindex="-1" aria-labelledby="pairedSampleTestModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Uji Paired Sample T-Test</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{-- Bagian Info Awal --}}
                <div class="mb-3">
                    <strong>Kelas:</strong> <span id="paired-class-name">-</span><br>
                    <strong>Modul:</strong> <span id="paired-module-name">-</span>
                </div>

                {{-- Tombol Eksekusi --}}
                <div class="mb-4">
                    <button id="runPairedTestBtn" class="btn btn-primary" data-url="" data-class=""
                        data-module="">Jalankan Paired T-Test</button>
                </div>

                {{-- Bagian Hasil (dinamis) --}}
                <div id="paired-test-result">
                    <div class="alert alert-warning">
                        Belum ada data Paired T-Test. Pastikan semua siswa telah menyelesaikan pretest dan posttest,
                        kemudian jalankan uji untuk melihat hasilnya.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>