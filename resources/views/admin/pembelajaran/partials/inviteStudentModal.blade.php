<!-- Modal Add Student -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal">
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
                        @foreach ($students as $student)
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

    document.addEventListener('DOMContentLoaded', function () {
        
        // Fungsi pencarian
        searchInput.addEventListener('input', function () {
            const searchValue = this.value.toLowerCase();

            studentCards.forEach(studentCard => {
                const studentEmail = studentCard.getAttribute('data-email').toLowerCase();
                const studentNis = studentCard.getAttribute('data-nis').toLowerCase();

                // Tampilkan card jika cocok, sembunyikan jika tidak cocok
                if (searchValue !== '' && (studentEmail.includes(searchValue) || studentNis.includes(searchValue))) {
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

    });

    // Fungsi selectStudent untuk menyimpan data yang dipilih
    function inviteStudent() {
        console.log('invite');
        // Menyimpan data ke variable selectedStudents
        if (student) { // Pastikan ada student yang dipilih
            selectedStudents.push(student); // Menambahkan student ke dalam array selectedStudents
            console.log(selectedStudents);
            document.getElementById('inviteButton').disabled = true; // Nonaktifkan tombol setelah dipilih
            student = null;
        }


        // Menutup modal InvitedStudentModal
        closeInviteStudentModal();


        // Membuka modal CreateClass
        openCreateClassModal();
        renderStudentList();
    }

    function closeInviteStudentModal() {
            // Ambil elemen modal
            const modalElement = document.getElementById("addStudentModal");

            // Buat instance modal menggunakan Bootstrap Modal API
            const modalInstance = bootstrap.Modal.getInstance(modalElement);

            // Jika instance modal ada, tutup modal
            if (modalInstance) {
                modalInstance.hide();
            } else {
                // Jika modal belum di-inisialisasi, buat instance dan tutup
                const newModalInstance = new bootstrap.Modal(modalElement);
                newModalInstance.hide();
            }

            searchInput.value = '';
            student = null;

            
            resetInviteStudentModal();

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