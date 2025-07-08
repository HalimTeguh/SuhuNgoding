<!-- Modal Add/Edit Question -->
<div class="modal fade" id="questionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="questionForm" action="{{ route('quiz.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Input for Question -->
                    <div class="mb-3">
                        <label for="question" class="form-label">Question</label>
                        <input type="text" class="form-control" id="question" name="question" required>
                    </div>

                    <!-- Dynamic Options Container -->
                    <div class="d-flex flex-wrap m-2" id="optionsContainer">
                        <!-- Options will be inserted dynamically -->
                    </div>

                    <!-- Button to add new option -->
                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addNewOption()">Add
                            Choice</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveButton">Save Question</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('questionModal');
        const form = document.getElementById('questionForm');
        const modalTitle = document.getElementById('modalTitle');
        const saveButton = document.getElementById('saveButton');
        const questionInput = document.getElementById('question');
        const optionsContainer = document.getElementById('optionsContainer');

        let isEditMode = false;

        // Show modal event
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const isEdit = button.getAttribute('data-action') === 'edit';
            isEditMode = isEdit;

            if (isEdit) {
                const questionId = button.getAttribute('data-question-id');
                const questionText = button.getAttribute('data-question');
                const choices = JSON.parse(button.getAttribute('data-choices'));

                modalTitle.textContent = 'Edit Question';
                form.action = `/dashboard/teacher/testing/quiz/${questionId}`;
                form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="PUT">');
                questionInput.value = questionText;

                optionsContainer.innerHTML = '';
                choices.forEach((choice, index) => {
                    addOption(choice.choice, choice.id, choice.is_correct);
                });

                saveButton.textContent = 'Save Changes';
            } else {
                modalTitle.textContent = 'Add New Question';
                form.action = `/dashboard/teacher/testing/quiz`;
                form.querySelector('input[name="_method"]')?.remove();
                questionInput.value = '';
                optionsContainer.innerHTML = '';
                saveButton.textContent = 'Save Question';
            }
        });

        window.addNewOption = function () {
            const inputs = optionsContainer.querySelectorAll('.option-input');
            for (const input of inputs) {
                if (!input.value.trim()) {
                    alert('Isi semua pilihan yang sudah ada sebelum menambah yang baru');
                    return;
                }
            }

            const newIndex = optionsContainer.children.length; // Angka urut
            addOption('', newIndex, false);
        }

        window.deleteOption = function (button) {
            if (confirm('Yakin ingin menghapus opsi ini?')) {
                button.closest('.option-item').remove();
            }
        }

        function addOption(value = '', id, isCorrect = false) {
            const checked = isCorrect ? 'checked' : '';
            const labelClass = isCorrect ? 'btn-success' : 'btn-label-danger';
            const iconClass = isCorrect ? 'bx-check' : 'bx-x';

            const html = `
                <div class="col-12 mb-3 option-item">
                    <div class="d-flex align-items-center">
                        <div class="col-md-1 m-1">
                            <input type="radio" class="btn-check correct-answer-checkbox"
                                name="correct_choice"
                                id="option_${id}"
                                value="${id}" ${checked}>
                            <label class="btn ${labelClass} custom-stepper-box" for="option_${id}">
                                <i class='bx ${iconClass}'></i>
                            </label>
                        </div>
                        <div class="col-md-10 p-1 position-relative">
                            <input type="text" class="form-control option-input"
                                name="options[${id}][text]"
                                value="${value}" required>
                        </div>
                        <div class="col-md-1 p-1">
                            <button type="button" class="btn btn-outline-danger form-control"
                                onclick="deleteOption(this)">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            optionsContainer.insertAdjacentHTML('beforeend', html);
        }

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('correct-answer-checkbox')) {
                document.querySelectorAll('.correct-answer-checkbox').forEach(checkbox => {
                    const label = document.querySelector(`label[for="${checkbox.id}"]`);
                    if (checkbox === e.target) {
                        label.classList.remove('btn-label-danger');
                        label.classList.add('btn-success');
                        label.innerHTML = `<i class='bx bx-check'></i>`;
                    } else {
                        checkbox.checked = false;
                        label.classList.remove('btn-success');
                        label.classList.add('btn-label-danger');
                        label.innerHTML = `<i class='bx bx-x'></i>`;
                    }
                });
            }
        });
    });
</script>