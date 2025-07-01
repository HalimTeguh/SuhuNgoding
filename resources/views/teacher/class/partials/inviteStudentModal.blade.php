<!-- Modal Add Student -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-m">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Invite Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control mb-3" id="searchStudentModal"
                    placeholder="Search by Email or NIS...">
                <div id="studentSearchResults">
                    <!-- Placeholder for dynamic student search results -->
                    <div id="studentListModal" class="invite-student-list">
                        @foreach ($allStudents as $student)
                        <div class="student-card d-flex align-items-center justify-content-between px-3 py-2 mb-1 border rounded d-none"
                            data-id="{{ $student->id }}" data-name="{{ $student->name }}"
                            data-email="{{ $student->email }}" data-nis="{{ $student->NIS }}">
                            <div>
                                <strong>{{ $student->name }}</strong><br>
                                {{ $student->NIS }} - {{ $student->email }}
                            </div>
                            <button type="button"
                                class="btn btn-sm btn-outline-danger cancel-button d-none">Cancel</button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="inviteButton" onclick="inviteStudent()" disabled>Add
                    Student</button>
            </div>
        </div>
    </div>
</div>

<script>
    let student = null;
    const searchInput = document.getElementById('searchStudentModal');
    const studentCards = document.querySelectorAll('#studentListModal .student-card');
    let selectedCard = null;
    let callerSource = null;


    document.addEventListener('DOMContentLoaded', function () {

        const addStudentModal = document.getElementById('addStudentModal');

        
        // Fungsi pencarian
        searchInput.addEventListener('input', function () {
            const searchValue = this.value.trim().toLowerCase();

            // Jangan tampilkan apa pun jika input kosong atau hanya simbol seperti "@"
            if (searchValue.length < 3 || /^[^a-zA-Z0-9]+$/.test(searchValue)) {
                studentCards.forEach(card => card.classList.add('d-none'));
                return;
            }

            studentCards.forEach(studentCard => {
                const studentEmail = studentCard.getAttribute('data-email').toLowerCase();
                const studentNis = studentCard.getAttribute('data-nis').toLowerCase();

                if (
                    studentEmail.includes(searchValue) ||
                    studentNis.includes(searchValue)
                ) {
                    studentCard.classList.remove('d-none');
                } else {
                    studentCard.classList.add('d-none');
                }
            });
        });

        // Tambahkan event listener untuk setiap card
        studentCards.forEach(card => {
            card.addEventListener('click', function () {
                // Hapus seleksi dari card sebelumnya
                if (selectedCard) {
                    selectedCard.classList.remove('border-primary');
                    selectedCard.querySelector('.cancel-button').classList.add('d-none');
                    selectedCard.classList.remove('d-none');
                }

                // Tandai card yang dipilih
                selectedCard = this;
                selectedCard.classList.add('border-primary');
                selectedCard.querySelector('.cancel-button').classList.remove('d-none');

                // Sembunyikan semua card lain
                studentCards.forEach(otherCard => {
                    if (otherCard !== selectedCard) {
                        otherCard.classList.add('d-none');
                    }
                });

                // Sembunyikan input pencarian dan kosongkan nilainya
                searchInput.value = '';
                searchInput.classList.add('d-none');

                // Ambil data dari data-attribute
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const nis = this.getAttribute('data-nis');
                const email = this.getAttribute('data-email');
                
                // Simpan data langsung ke selectedStudent
                student = { id, name, nis, email };

                // Aktifkan tombol invite
                document.getElementById('inviteButton').disabled = false;
            });

            // Tambahkan event listener untuk tombol cancel
            const cancelButton = card.querySelector('.cancel-button');
            cancelButton.addEventListener('click', function (e) {
                e.stopPropagation(); // Mencegah event click pada card

                // Batalkan seleksi
                selectedCard.classList.remove('border-primary');
                selectedCard.querySelector('.cancel-button').classList.add('d-none');
                selectedCard = null;

                // Tampilkan kembali input pencarian
                resetInviteStudentModal();

                // Nonaktifkan tombol invite
                document.getElementById('inviteButton').disabled = true;
            });
        });

        addStudentModal.addEventListener('show.bs.modal', function (event) {
            console.log("Modal Invite Student opened");
            const button = event.relatedTarget;
            callerSource = button?.getAttribute('data-caller');

            if (callerSource === 'createClass') {
                const createModal = bootstrap.Modal.getInstance(document.getElementById('createClass'));
                createModal?.hide();
            }
        });

        addStudentModal.addEventListener('hidden.bs.modal', function () {
            
            if (callerSource === 'createClass') {
                const createModalEl = document.getElementById('createClass');
                console.log("Modal Invite Student closed");
                console.log(createModalEl);
                const createModal = bootstrap.Modal.getOrCreateInstance(createModalEl);
                createModal.show();
            }
            callerSource = null; // Reset
        });

    });

    function closeInviteStudentModal() {
        // Reset input
        searchInput.value = '';
        student = null;

        const createModal = bootstrap.Modal.getInstance(document.getElementById('addStudentModal'));
        createModal?.hide();

        resetInviteStudentModal();
        renderStudentList();

        console.log("Modal Invite Student closed");
    }

    function resetInviteStudentModal() {
        // Tampilkan kembali input pencarian dan kosongkan nilai input
        searchInput.classList.remove('d-none');
        searchInput.value = '';

        // Hapus semua highlight atau border dan sembunyikan tombol cancel
        studentCards.forEach(card => {
            card.classList.remove('border-primary'); // Hapus highlight
            card.querySelector('.cancel-button').classList.add('d-none'); // Sembunyikan tombol cancel
            card.classList.add('d-none'); // Tampilkan kembali semua card
        });

        // Nonaktifkan tombol invite
        document.getElementById('inviteButton').disabled = true;

        // Reset selected student dan selected card
        student = null;
        selectedCard = null;

        console.log("Modal Invite Student reset");
    }
</script>