<div class="card mb-6 quiz-module">
    <div class="card-body">
        <form id="quizForm" action="dashboard/admin/pembelajaran/quiz/{quiz}" method="post">
            @csrf
            @method('PUT')
            <div class="d-flex align-items-start align-items-sm-center pb-4 border-bottom justify-content-start ">
                <!-- Navigasi Kiri -->
                <div class="col-md-2 me-4 d-flex align-self-start">
                    <div class="bg-primary rounded w-100">

                        <div class="rounded p-3 w-100" style="background-color: rgba(255, 255, 255, 0.4);">
                            <input type="hidden" id="currentContent" name="currentContent" >
                            <ul class="list-group w-100 border-0" id="contentList">
                                @foreach($contents as $content)
                                <li class="list-group-item p-0 border-0 mb-2">
                                    <button class="btn w-100 py-2 content-navigate text-center btn-light" type="button"
                                        data-content-id="{{ $content->id }}"
                                        onclick="handleContentClick({{ $module->id }}, {{ $content->id }})">
                                        Pertemuan {{ $loop->iteration }}
                                    </button>
                                </li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn btn-outline-primary bg-white mt-4 w-100" onclick="exportCurrentQuiz()">Export Quiz</button>
                        </div>


                    </div>

                </div>

                <!-- Konten Kanan -->
                <div class="col">
                    <div id="stepperForm" class="bs-stepper align-top">
                        <div class="bs-stepper-header" role="tablist" id="stepperHeader">
                            <!-- Step headers akan di-generate secara dinamis -->
                        </div>
                        <hr>

                        <!-- Form Inputs -->
                        <div class="d-flex">
                            <div class="col m-2">
                                <label for="levelBloom" class="form-label">Level</label>
                                <select class="form-select" id="levelBloom" name="levelBloom">
                                    @foreach(['remember', 'understand', 'apply', 'analyze', 'evaluate', 'create'] as $level)
                                    <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 m-2">
                                <label for="typeQuestion" class="form-label">Type</label>
                                <select class="form-select" id="typeQuestion" name="typeQuestion">
                                    <option value="multiple_choice">Multiple Choice</option>
                                    <option value="code">Code</option>
                                </select>
                            </div>
                            <div class="col-md-2 m-2">
                                <label for="pointQuestion" class="form-label">Point</label>
                                <input type="number" id="pointQuestion" name="pointQuestion" class="form-control" min="1">
                            </div>
                        </div>

                        <div class="m-2">
                            <textarea id="moduleQuiz" name="moduleQuiz" class="form-control" style="height: 300px;"></textarea>
                        </div>

                        <!-- Dynamic Options Container -->
                        <div class="d-flex flex-wrap m-2" id="optionsContainer">
                            <!-- Options akan di-generate secara dinamis -->
                        </div>

                        <div id="codeEditorContainer" class="d-none">
                            <div class="m-2">
                                <label class="form-label">Code reference:</label>
                                <div id="editor-container"></div>
                                <input type="hidden" id="codeAnswer" name="codeAnswer">
                            </div>
                            <div class="m-2">
                                <label class="form-label">Expected output:</label>
                                <pre id="codeOutput" class="bg-light p-3 border rounded" style="height: 350px;"></pre>
                                <button type="button" class="btn btn-success mt-2" onclick="runCode()">Run Code</button>
                                <span id="loadingSpinner" style="display:none;">Running...</span>
                                <input type="hidden" id="codeOutputInput" name="codeOutputInput">
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary" id="addOptionBtn">
                            <i class='bx bx-plus me-2'></i><span class="me-1">Add option</span>
                        </button>

                    </div>
                </div>
            </div>
            <div class="mt-6 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Reusable Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalTitle">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="confirmModalMessage">
                Apakah Anda yakin ingin melanjutkan?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmAction">Ya</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/pyodide/v0.25.1/full/pyodide.js"></script>

<script>
let currentQuizzes = [];
let currentStep = 0;
let isDirty = false;
let currentContentId = null;
let codeEditor = null;

document.addEventListener("DOMContentLoaded", async function () {
    try {
        
        // Memuat data kuis setelah inisialisasi selesai
        const firstContent = document.querySelector('[data-content-id]');
        if (firstContent) {
            currentContentId = firstContent.dataset.contentId;
            loadQuizzes({{ $module->id }}, currentContentId);
        }
        
        // Menunggu inisialisasi TinyMCE dan CodeMirror selesai sebelum melanjutkan
        await initTinyMCE();
        await initCodeMirror();
        initRunCode();

        // Mengatur event listener untuk submit form berdasarkan ID
        document.getElementById("quizForm").addEventListener("submit", function (event) {
            // Mencegah form submit otomatis
            event.preventDefault();

            // Pastikan CodeMirror sudah terisi
            if (codeEditor) {
                // Set nilai CodeMirror ke dalam input hidden
                document.getElementById("codeAnswer").value = codeEditor.getValue();
                console.log("CodeMirror value before submit: ", codeEditor.getValue());
            } else {
                console.log("CodeMirror is not initialized yet.");
            }

            // Kirim form setelah nilai dimasukkan
            this.submit();
        });

        // Event listener untuk perubahan typeQuestion
        document.getElementById('typeQuestion').addEventListener('change', function() {
            toggleEditor(this.value);
        });
    } catch (error) {
        console.error("Error during initialization:", error);
    }
});

// Fungsi inisialisasi TinyMCE
async function initTinyMCE() {
    return new Promise((resolve, reject) => {
        tinymce.init({
            selector: "#moduleQuiz",
            toolbar: "undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist",
            menubar: false,
            plugins: "autoresize",
            setup: function(editor) {
                editor.on('change', function() {
                    isDirty = true;
                });
            },
            init_instance_callback: function () {
                console.log("TinyMCE telah terinisialisasi.");
                resolve(); // Menyelesaikan promise setelah TinyMCE selesai inisialisasi
            }
        });
    });
}

// Fungsi inisialisasi CodeMirror
async function initCodeMirror() {
    return new Promise((resolve, reject) => {
        codeEditor = new CodeMirror(document.getElementById("editor-container"), {
            value: "print('Hello, world!')",  // Nilai awal
            mode: "python",
            theme: "monokai",
            lineNumbers: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            indentUnit: 4,
            indentWithTabs: true,
            smartIndent: true,
            electricChars: true,
        });

        codeEditor.setValue("print('Hello')");

        if (codeEditor) {
            console.log("CodeMirror telah terinisialisasi.");
            resolve();
        } else {
            reject("Error: Unable to initialize CodeMirror");
        }
    });
}

let pyodide = null;
async function initRunCode() {
    // Load Pyodide jika belum
    pyodide = await loadPyodide();

    window.runCode = async function () {
        const userCode = codeEditor.getValue();
        const outputContainer = document.getElementById("codeOutput");
        const spinner = document.getElementById("loadingSpinner");
        const codeOutputInput = document.getElementById("codeOutputInput");

        if (!userCode.trim()) {
            outputContainer.innerText = "Error: No code provided!";
            return;
        }

        spinner.style.display = "inline";
        outputContainer.innerText = ""; // Clear output sebelumnya

        try {
            // Buat Python script gabungan
            const wrappedCode = `
import sys
from io import StringIO

output = StringIO()
sys.stdout = output
sys.stderr = output

try:
${userCode.split('\n').map(line => '    ' + line).join('\n')}
except Exception as e:
    print("Runtime Error:", e)

sys.stdout = sys.__stdout__
sys.stderr = sys.__stderr__
result = output.getvalue()
            `;

            await pyodide.runPythonAsync(wrappedCode);
            const result = pyodide.globals.get("result");

            outputContainer.innerText = result || "(No output)";
            codeOutputInput.value = result || "";

        } catch (err) {
            outputContainer.innerText = "Error:\n" + err;
        } finally {
            spinner.style.display = "none";
        }
    };
}


// function initRunCode() {
//     window.runCode = function() {
//         let userCode = codeEditor.getValue();
//         let outputContainer = document.getElementById("codeOutput");
//         let spinner = document.getElementById("loadingSpinner");
//         let codeOutputInput = document.getElementById("codeOutputInput");


//         if (!userCode.trim()) {
//             outputContainer.innerText = "Error: No code provided!";
//             return;
//         }

//         spinner.style.display = "inline"; // Tampilkan loading

//         fetch("/execute", {
//             method: "POST",
//             headers: { "Content-Type": "application/json" },
//             body: JSON.stringify({ code: userCode })
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.error) {
//                 outputContainer.innerText = "Error:\n" + data.error;
//             } else {
//                 outputContainer.innerText = data.output;
//                 codeOutputInput.value = data.output; // Save output to hidden input
//             }
//         })

//         .catch(error => {
//             outputContainer.innerText = "Execution error!";
//         })

//         .finally(() => {
//             spinner.style.display = "none"; // Sembunyikan loading
//         });
//     };
// }

function loadQuizzes(moduleId, contentId) {
    fetch(`/dashboard/admin/pembelajaran/module/${moduleId}/content/${contentId}/quiz`)
        .then(response => response.json())
        .then(data => {
            currentQuizzes = data.quizzes || [];
            currentContentId = contentId;
            currentStep = 0;

            updateStepperHeader();
            updateContentNavigation(contentId);

            if(currentQuizzes.length > 0) {
                updateFormData(currentQuizzes[0]);
            } else {
                resetForm();
            }

        })
        .catch(error => {
            console.error('Error loading quizzes:', error);
            alert('Failed to load quizzes');
        });
}

function updateFormData(quiz) {
    const form = document.getElementById('quizForm');
    resetForm();

    if(quiz.quiz_id == null){

        form.action = `/dashboard/admin/pembelajaran/quiz`;
        form.method = `POST`;
        document.getElementById('currentContent').value = currentContentId;


        // Hapus input _method jika ada (karena ini store, bukan update)
        const hiddenMethod = form.querySelector('input[name="_method"]');
        if (hiddenMethod) {
            hiddenMethod.remove();
        }
        toggleEditor('multiple_choice');

    }else{
        form.action = `/dashboard/admin/pembelajaran/quiz/${quiz.quiz_id}`;
        form.method = `POST`; // Form HTML hanya mendukung POST atau GET
        document.getElementById('currentContent').value = currentContentId;
        
        // Tambahkan input hidden untuk override metode menjadi PUT
        let hiddenMethod = form.querySelector('input[name="_method"]');
        if (!hiddenMethod) {
            hiddenMethod = document.createElement('input');
            hiddenMethod.type = 'hidden';
            hiddenMethod.name = '_method';
            form.appendChild(hiddenMethod);
        }
        hiddenMethod.value = 'PUT';

        toggleEditor(quiz.type);


        // Check question type
        if (quiz.type === "code" && codeEditor) {
            // Populate code editor with test case (if any)
            codeEditor.setValue(quiz.code[0].test_cases || ''); // Set value = '';

            document.getElementById("codeOutput").textContent = quiz.code[0].expected_output || '';
            document.getElementById("codeOutputInput").value = quiz.code[0].expected_output || '';
        }

        document.getElementById('levelBloom').value = quiz.level_bloom;
        document.getElementById('typeQuestion').value = quiz.type;
        document.getElementById('pointQuestion').value = quiz.point;
        document.getElementById('currentContent').value = currentContentId;
        
        if (tinymce.get('moduleQuiz')) {
            tinymce.get('moduleQuiz').setContent(quiz.question);
        }
    
        const optionsContainer = document.getElementById('optionsContainer');
        optionsContainer.innerHTML = '';
        
        quiz.choices.forEach((option, index) => {
            const optionHTML = `
                <div class="col-12 mb-3 option-item">
                    <div class="d-flex align-items-center ">
                        <div class="col-md-1 p-2">
                            <input type="checkbox" class="btn-check correct-answer-checkbox"
                                name="correctAnswer"
                                id="option${index}"
                                ${option.is_correct ? 'checked' : ''}>
                            <label class="btn ${option.is_correct ? 'btn-success' : 'btn-label-danger'} custom-stepper-box" 
                                for="option${index}">
                                <i class='bx ${option.is_correct ? 'bx-check' : 'bx-x'}'></i>
                            </label>
                        </div>
                        <div class="col-md-10 p-2 position-relative">
                            <input type="text" class="form-control option-input"
                                name="options[${index}][text]"
                                value="${option.choice_text}"
                                data-option-id="${option.choice_id}">
                            <i class='bx bx-comment-dots feedback-toggle position-absolute end-0 top-50 translate-middle-y me-3'
                                onclick="toggleFeedback(this)"></i>
                            <input type="hidden" name="options[${index}][id]" value="${option.choice_id}">
                        </div>
                        <div class="col-md-1 p-2">
                            <button type="button" class="btn btn-outline-danger form-control"
                                onclick="deleteOption(this, ${index})">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="option-feedback mb-2 d-flex">
                        <div class="col"> </div>
                        <div class="col-md-11 p-2 position-relative">
                             <label for="options[${index}][feedback]" class="form-label">Deskripsi pilihan ${index + 1}</label>
                             <input type="hidden" name="options[${index}][is_correct]" 
                                value="${option.is_correct ? 1 : 0}">
                            <textarea class="form-control" 
                                name="options[${index}][feedback]"
                                placeholder="Feedback untuk opsi ini">${option.feedback || ''}</textarea>
                        </div>
                    </div>
                </div>
            `;
            optionsContainer.insertAdjacentHTML('beforeend', optionHTML);
        });

    }


    isDirty = false;
}

function addNewOption() {
    const options = document.querySelectorAll('.option-input');
    for(const option of options) {
        if(!option.value.trim()) {
            alert('Harap isi opsi yang sudah ada sebelum menambah baru');
            return;
        }
    }

    const newIndex = options.length;
    const newId = "new-" + options.length;
    
    const newOptionHTML = `
        <div class="col-12 mb-3 option-item">
            <div class="d-flex align-items-center">
                <div class="col-md-1 p-2">
                    <input type="checkbox" class="btn-check correct-answer-checkbox" 
                        name="correctAnswer"
                        id="option${newIndex}">
                    <label class="btn btn-label-danger custom-stepper-box" for="option${newIndex}">
                        <i class='bx bx-x'></i>
                    </label>
                </div>
                <div class="col-md-10 p-2 position-relative">
                    <input type="text" class="form-control option-input"
                        name="options[${newIndex}][text]"
                        data-option-id="${newId}">
                    <i class='bx bx-comment-dots feedback-toggle position-absolute end-0 top-50 translate-middle-y me-3'
                        onclick="toggleFeedback(this)"></i>
                    <input type="hidden" name="options[${newIndex}][id]" value="${newId}">
                </div>
                <div class="col-md-1 p-2">
                    <button type="button" class="btn btn-outline-danger form-control"
                        onclick="deleteOption(this, '${newId}')">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
            <div class="option-feedback mb-2 d-flex">
                <div class="col p-2"></div>
                <div class="col-md-11 p-2 position-relative">
                    <label for="options[${newIndex}][feedback]" class="form-label">Deskripsi pilihan ${newId}</label>
                    <input type="hidden" name="options[${newIndex}][is_correct]" value="0">
                    <textarea class="form-control" 
                        name="options[${newIndex}][feedback]"
                        placeholder="Feedback untuk opsi ini"></textarea>
                </div>
            </div>
            
        </div>
    `;
    
    document.getElementById('optionsContainer').insertAdjacentHTML('beforeend', newOptionHTML);
    document.querySelector(`[data-option-id="${newId}"]`).focus();
}

function deleteOption(button) {
    let optionToDelete = button.closest('.option-item');
    const optionId = optionToDelete.querySelector('input[name^="options["][name$="[id]"]')?.value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    showConfirmationModal({
        title: "Konfirmasi Hapus",
        message: "Apakah Anda yakin ingin menghapus opsi ini?",
        confirmText: "Hapus",
        confirmClass: "btn-danger",
        onConfirm: function () {
            if (optionId?.startsWith("new-")) {
                // Jika opsi baru (belum ada di database), langsung dihapus dari tampilan
                optionToDelete.remove();
            } else if (optionId) {
                // Hapus dari database dengan CSRF token yang valid
                fetch(`/dashboard/admin/pembelajaran/quiz/option/${optionId}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,  // Pastikan CSRF token dikirim
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    credentials: "same-origin" // Pastikan cookie dikirim dengan benar
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        optionToDelete.remove();
                    } else {
                        alert("Gagal menghapus opsi: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan saat menghapus opsi.");
                });
            }
        }
    });
}


function resetForm() {
    document.getElementById('levelBloom').value = "";
    document.getElementById('typeQuestion').value = "multiple_choice";
    document.getElementById('pointQuestion').value = "";
    document.getElementById("codeOutput").textContent = '';
    document.getElementById("codeOutputInput").value = '';
    
    if (tinymce.get('moduleQuiz')) {
        tinymce.get('moduleQuiz').setContent('');
    }

    if(codeEditor) {
        codeEditor.setValue('');
    }

    document.getElementById('optionsContainer').innerHTML = ''; // Hapus opsi sebelumnya
    isDirty = false;
}


// Load First Data
const firstContent = document.querySelector('[data-content-id]');
if (firstContent) {
    currentContentId = firstContent.dataset.contentId;
    loadQuizzes({{ $module->id }}, currentContentId);
}

// Add New Option
document.getElementById('addOptionBtn').addEventListener('click', addNewOption);

// Detect Changes in level, type, and point
document.querySelectorAll('#levelBloom, #typeQuestion, #pointQuestion').forEach(element => {
    element.addEventListener('change', () => isDirty = true);
});

// Event listener untuk checkbox sehingga hanya 1 yang aktif
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('correct-answer-checkbox')) {
        // Nonaktifkan checkbox lainnya
        document.querySelectorAll('.correct-answer-checkbox').forEach(checkbox => {
            if (checkbox !== e.target) {
                checkbox.checked = false;
                let hiddenInput = checkbox.closest('.option-item').querySelector('input[type="hidden"][name*="[is_correct]"]');
                if(hiddenInput) {
                    hiddenInput.value = 0;
                }
            }
        });
        // Update hidden input untuk opsi saat ini
        let hiddenInput = e.target.closest('.option-item').querySelector('input[type="hidden"][name*="[is_correct]"]');
        if(hiddenInput) {
            hiddenInput.value = e.target.checked ? 1 : 0;
        }
        updateCheckboxStyles(e.target);
        isDirty = true;
    }
});

// change field of question by type
function toggleEditor(type) {
    const optionsContainer = document.getElementById('optionsContainer');
    const codeEditorContainer = document.getElementById('codeEditorContainer');
    const addOptionBtn = document.getElementById('addOptionBtn');

    if (type === 'code') {
        optionsContainer.classList.add('d-none');
        addOptionBtn.classList.add('d-none');
        codeEditorContainer.classList.remove('d-none');
        
    } else {
        optionsContainer.classList.remove('d-none');
        addOptionBtn.classList.remove('d-none');
        codeEditorContainer.classList.add('d-none');
    }
}


function addNewQuiz() {
    // Reset form
    resetForm();
    
    // Tambahkan langkah baru ke stepper
    currentQuizzes.push({}); // Tambahkan quiz kosong sebagai placeholder
    // Ubah form action agar menyimpan data baru
    updateStepperHeader();
    navigateToStep(currentQuizzes.length - 1);

    toggleEditor("multiple_choice");
    document.getElementById('quizForm').action = "/dashboard/admin/pembelajaran/quiz";
    document.querySelector('input[name="_method"]').value = "POST"; // Ubah dari PUT ke POST
    console.log(document.getElementById('quizForm'));
}



function toggleFeedback(icon) {
    const feedbackSection = icon.closest('.option-item').querySelector('.option-feedback');
    feedbackSection.classList.toggle('active');
    icon.classList.toggle('bx-comment-dots');
    icon.classList.toggle('bx-comment-check');
}

function updateCheckboxStyles(selectedCheckbox) {
    document.querySelectorAll('.correct-answer-checkbox').forEach(checkbox => {
        const label = document.querySelector(`label[for="${checkbox.id}"]`);
        const isChecked = checkbox.checked;
        label.classList.remove('btn-success', 'btn-label-danger');
        label.classList.add(isChecked ? 'btn-success' : 'btn-label-danger');
        label.innerHTML = `<i class='bx ${isChecked ? 'bx-check' : 'bx-x'}'></i>`;
    });
}

function handleContentClick(moduleId, contentId) {
    if (isDirty) {
        showConfirmation().then((confirmed) => {
            if (!confirmed) return;
            loadQuizzes(moduleId, contentId);
        });
    } else {
        loadQuizzes(moduleId, contentId);
    }
}

// function showConfirmation() {
//     return new Promise((resolve) => {
//         const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
//         modal.show();

//         document.getElementById('saveAndLeave').onclick = () => {
//             document.getElementById('quizForm').requestSubmit();
//             modal.hide();
//             resolve(true);
//         };

//         document.getElementById('leaveWithoutSaving').onclick = () => {
//             modal.hide();
//             resolve(true);
//         };
//     });
// }

function navigateToStep(stepIndex) {
    if (stepIndex >= 0 && stepIndex < currentQuizzes.length) {
        currentStep = stepIndex;
        updateFormData(currentQuizzes[currentStep]);
        updateStepperButtons();
    }
}

function updateContentNavigation(activeContentId) {
    document.querySelectorAll('.content-navigate').forEach(button => {
        const isActive = button.dataset.contentId == activeContentId;
        button.classList.toggle('btn-primary', isActive);
        button.classList.toggle('text-white', isActive);
        button.classList.toggle('btn-light', !isActive);
    });
}

function updateStepperHeader() {
    const stepperHeader = document.getElementById('stepperHeader');
    stepperHeader.innerHTML = '';
    
    currentQuizzes.forEach((quiz, index) => {
        const stepHTML = `
            <div class="step" data-target="#step-${index + 1}">
                <button type="button" class="btn ${index === currentStep ? 'btn-primary' : 'btn-outline-primary'} custom-stepper-box m-2 btn-quiz"
                    onclick="navigateToStep(${index})">
                    <span>${index + 1}</span>
                </button>
            </div>
        `;
        stepperHeader.insertAdjacentHTML('beforeend', stepHTML);
    });

    stepperHeader.insertAdjacentHTML('beforeend', `
        <button type="button" class="btn btn-outline-primary custom-stepper-box m-2 " onclick="addNewQuiz()">
            <i class="bx bx-plus"></i>
        </button>
    `);
}

function updateStepperButtons() {
    document.querySelectorAll('.btn-quiz').forEach((btn, index) => {
        if (index === currentStep) {
            btn.classList.remove('btn-outline-primary'); // Hapus kelas yang tidak diinginkan
            btn.classList.add('btn-primary'); // Tambahkan kelas yang diinginkan
        } else {
            btn.classList.remove('btn-primary'); // Hapus kelas yang tidak diinginkan
            btn.classList.add('btn-outline-primary'); // Tambahkan kelas yang diinginkan
        }
    });
}

function showConfirmationModal({ title = "Konfirmasi", message = "Apakah Anda yakin?", confirmText = "Ya", confirmClass = "btn-danger", onConfirm }) {
    // Set judul, pesan, dan teks tombol modal
    document.getElementById("confirmModalTitle").innerText = title;
    document.getElementById("confirmModalMessage").innerText = message;

    const confirmButton = document.getElementById("confirmAction");
    confirmButton.innerText = confirmText;
    confirmButton.className = `btn ${confirmClass}`;

    // Hapus event listener sebelumnya agar tidak bertumpuk
    confirmButton.replaceWith(confirmButton.cloneNode(true));
    const newConfirmButton = document.getElementById("confirmAction");

    // Tambahkan event listener baru untuk aksi konfirmasi
    newConfirmButton.addEventListener("click", function () {
        if (onConfirm && typeof onConfirm === "function") {
            onConfirm();
        }

        // Tutup modal setelah aksi konfirmasi dijalankan
        const confirmModalEl = document.getElementById("confirmModal");
        const confirmModal = bootstrap.Modal.getInstance(confirmModalEl);
        confirmModal.hide();
    });

    // Tampilkan modal
    const confirmModal = new bootstrap.Modal(document.getElementById("confirmModal"));
    confirmModal.show();
}

function exportCurrentQuiz() {
    if (!currentQuizzes || currentQuizzes.length === 0) {
        alert("Tidak ada quiz yang bisa diekspor.");
        return;
    }

    let htmlContent = "<h2>Daftar Soal</h2>";
    
    currentQuizzes.forEach((quiz, index) => {
        htmlContent += `<p>${quiz.question}</p>`;

        // Multiple Choice
        if (quiz.type === 'multiple_choice') {
            if (quiz.choices && quiz.choices.length > 0) {
                const labels = ['A', 'B', 'C', 'D', 'E', 'F'];
                htmlContent += "<ul style='list-style-type: none; padding-left: 0'>";
                quiz.choices.forEach((choice, idx) => {
                    const label = labels[idx] || String.fromCharCode(65 + idx); // fallback
                    htmlContent += `<li><strong>${label}.</strong> ${choice.choice_text}</li>`;
                });
                htmlContent += "</ul>";
            }
        }

        // Code Type
        if (quiz.type === 'code') {
            htmlContent += `<p><strong>Test Case:</strong></p>`;
            htmlContent += `<pre style="background:#f4f4f4;padding:10px">${quiz.code?.[0]?.test_cases || ''}</pre>`;
            htmlContent += `<p><strong>Expected Output:</strong></p>`;
            htmlContent += `<pre style="background:#f4f4f4;padding:10px">${quiz.code?.[0]?.expected_output || ''}</pre>`;
        }

        htmlContent += `<hr>`;
    });

    const header = `
        <html xmlns:o='urn:schemas-microsoft-com:office:office' 
              xmlns:w='urn:schemas-microsoft-com:office:word' 
              xmlns='http://www.w3.org/TR/REC-html40'>
        <head><meta charset='utf-8'><title>Export Quiz</title></head><body>`;
    const footer = "</body></html>";
    const fullHTML = header + htmlContent + footer;

    const blob = new Blob(['\ufeff', fullHTML], {
        type: 'application/msword'
    });

    const downloadLink = document.createElement("a");
    downloadLink.href = URL.createObjectURL(blob);
    downloadLink.download = `quiz_${currentContentId || 'export'}.doc`;
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}


window.handleContentClick = handleContentClick;
window.navigateToStep = navigateToStep;
</script>

