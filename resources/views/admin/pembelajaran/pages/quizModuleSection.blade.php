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
                            <div class=" m-2">
                                
                                <label class="form-label">Code reference:</label>
                                <textarea id="codeEditor" name="codeAnswer" class="px-3 d-none rounded"></textarea>
                            </div>
                            <div class="m-2">
                                <label class="form-label">Expected output:</label>
                                <pre id="codeOutput" class="bg-light p-3 border rounded" style="height: 350px;"></pre>
                                <button type="button" class="btn btn-success mt-2" onclick="runCode()">Run Code</button>
                                <span id="loadingSpinner" style="display:none;">Running...</span>
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

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Unsaved Changes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>You have unsaved changes. Do you want to save before leaving?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="leaveWithoutSaving">Leave Without Saving</button>
                <button type="button" class="btn btn-primary" id="saveAndLeave">Save and Leave</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentQuizzes = [];
let currentStep = 0;
let isDirty = false;
let currentContentId = null;
let codeEditor = null;

document.addEventListener("DOMContentLoaded", function () {
    
    // Inisialisasi TinyMCE
    tinymce.init({
        selector: "#moduleQuiz",
        toolbar: "undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist",
        menubar: false,
        plugins: "autoresize",
        setup: function(editor) {
            editor.on('change', function() {
                isDirty = true;
            });
        }
    });

    var textareaCode = document.getElementById("codeEditor");
    if (textareaCode) {
        var codeEditor = CodeMirror.fromTextArea(textareaCode, {
            mode: "python",
            theme: "monokai",
            lineNumbers: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            indentUnit: 4,
            indentWithTabs: true,
            smartIndent: true,
            electricChars: true,
            extraKeys: {
                "Ctrl-Up": function(cm) {
                    cm.lineUp();
                },
                "Ctrl-Down": function(cm) {
                    cm.lineDown();
                },
                "Alt-Up": function(cm) {
                    cm.setSelections([cm.getCursor("from"), cm.getCursor("to")]);
                    cm.lineUp();
                },
                "Alt-Down": function(cm) {
                    cm.setSelections([cm.getCursor("from"), cm.getCursor("to")]);
                    cm.lineDown();
                }
            }
        });

        // Add snippet code
        const snippets = {
            "print": "print(${1:expression})",
            "for": "for ${1:var} in ${2:iterable}:\n\t${3:code}",
            "if": "if ${1:condition}:\n\t${2:code}",
            "class": "class ${1:name}:\n\t${2:code}",
            "def": "def ${1:name}(${2:params}):\n\t${3:code}"
        };

        codeEditor.setOption("extraKeys", {
            ...codeEditor.getOption("extraKeys"),
            "'": function(cm) {
                const cursor = cm.getCursor();
                const line = cm.getLine(cursor.line);
                const indent = line.match(/^\s+/)[0].length;
                const snippet = snippets[cm.getLine(cursor.line).trim()];
                if (snippet) {
                    cm.replaceRange(snippet, cursor);
                    cm.setCursor(cursor.line, indent);
                }
            }
        });

        window.runCode = function() {
            let userCode = codeEditor.getValue();
            let outputContainer = document.getElementById("codeOutput");
            let spinner = document.getElementById("loadingSpinner");

            if (!userCode.trim()) {
                outputContainer.innerText = "Error: No code provided!";
                return;
            }

            spinner.style.display = "inline"; // Tampilkan loading

            fetch("/execute", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ code: userCode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    outputContainer.innerText = "Error:\n" + data.error;
                } else {
                    outputContainer.innerText = data.output;
                }
            })
            .catch(error => {
                outputContainer.innerText = "Execution error!";
            })
            .finally(() => {
                spinner.style.display = "none"; // Sembunyikan loading
            });
        };
    } else {
        console.error("Element #codeEditor tidak ditemukan!");
    }
        
    // Event listener untuk perubahan typeQuestion
    document.getElementById('typeQuestion').addEventListener('change', function() {
        toggleEditor(this.value);
    });

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
});

// Load initial content
const firstContent = document.querySelector('[data-content-id]');
if (firstContent) {
    currentContentId = firstContent.dataset.contentId;
    loadQuizzes({{ $module->id }}, currentContentId);
}

document.getElementById('addOptionBtn').addEventListener('click', addNewOption);

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

function loadQuizzes(moduleId, contentId) {
    fetch(`/dashboard/admin/pembelajaran/module/${moduleId}/content/${contentId}/quiz`)
        .then(response => response.json())
        .then(data => {
            currentQuizzes = data.quizzes || [];
            currentContentId = contentId;
            updateStepperHeader();
            currentStep = 0;

            console.log(currentQuizzes[0]);
            
            if(currentQuizzes.length > 0) {
                updateFormData(currentQuizzes[0]);
            } else {
                resetForm();
            }
            updateContentNavigation(contentId);
        })
        .catch(error => {
            console.error('Error loading quizzes:', error);
            alert('Failed to load quizzes');
        });
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

function updateFormData(quiz) {
    const form = document.getElementById('quizForm');

    if(quiz.quiz_id == null){
        resetForm();

        form.action = `/dashboard/admin/pembelajaran/quiz`;
        form.method = `POST`;
        document.getElementById('currentContent').value = currentContentId;


        // Hapus input _method jika ada (karena ini store, bukan update)
        const hiddenMethod = form.querySelector('input[name="_method"]');
        if (hiddenMethod) {
            hiddenMethod.remove();
        }
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
                        <div class="col-md-11 p-2 position-relative">
                            <input type="text" class="form-control option-input"
                                name="options[${index}][text]"
                                value="${option.choice_text}"
                                data-option-id="${option.choice_id}">
                            <i class='bx bx-comment-dots feedback-toggle position-absolute end-0 top-50 translate-middle-y me-3'
                                onclick="toggleFeedback(this)"></i>
                            <input type="hidden" name="options[${index}][id]" value="${option.choice_id}">
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

function resetForm() {
    document.getElementById('levelBloom').value = "";
    document.getElementById('typeQuestion').value = "multiple_choice";
    document.getElementById('pointQuestion').value = "";
    
    if (tinymce.get('moduleQuiz')) {
        tinymce.get('moduleQuiz').setContent('');
    }

    document.getElementById('optionsContainer').innerHTML = ''; // Hapus opsi sebelumnya
    isDirty = false;
}

function addNewQuiz() {
    // Reset form
    resetForm();
    
    // Tambahkan langkah baru ke stepper
    currentQuizzes.push({}); // Tambahkan quiz kosong sebagai placeholder
    // Ubah form action agar menyimpan data baru
    updateStepperHeader();
    navigateToStep(currentQuizzes.length - 1);

    document.getElementById('quizForm').action = "/dashboard/admin/pembelajaran/quiz";
    document.querySelector('input[name="_method"]').value = "POST"; // Ubah dari PUT ke POST
    console.log(document.getElementById('quizForm'));
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
                <div class="col-md-11 p-2 position-relative">
                    <input type="text" class="form-control option-input"
                        name="options[${newIndex}][text]"
                        data-option-id="${newId}">
                    <i class='bx bx-comment-dots feedback-toggle position-absolute end-0 top-50 translate-middle-y me-3'
                        onclick="toggleFeedback(this)"></i>
                    <input type="hidden" name="options[${newIndex}][id]" value="${newId}">
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

function showConfirmation() {
    return new Promise((resolve) => {
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        modal.show();

        document.getElementById('saveAndLeave').onclick = () => {
            document.getElementById('quizForm').requestSubmit();
            modal.hide();
            resolve(true);
        };

        document.getElementById('leaveWithoutSaving').onclick = () => {
            modal.hide();
            resolve(true);
        };
    });
}

function navigateToStep(stepIndex) {
    if (stepIndex >= 0 && stepIndex < currentQuizzes.length) {
        currentStep = stepIndex;
        updateFormData(currentQuizzes[currentStep]);
        updateStepperButtons();
    }
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

window.handleContentClick = handleContentClick;
window.navigateToStep = navigateToStep;
</script>
