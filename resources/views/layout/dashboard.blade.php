<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
  data-assets-path="{{ asset('/assets/') }}" data-template="vertical-menu-template-free" data-style="light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Suhu Ngoding</title>

  <meta name="description" content="" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />


  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
    class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">



  <!-- Page CSS -->

  <!-- Helpers -->
  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="{{ asset('assets/js/config.js') }}"></script>

  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>

  <script src="https://cdn.tiny.cloud/1/eybtrn69cc9l77oqefyngf2jpkb2xp0m1a4w081ebftjl8vq/tinymce/7/tinymce.min.js"
    referrerpolicy="origin"></script>

  <!-- CodeMirror Styles -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/material.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/monokai.min.css">



  <!-- CodeMirror Core -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>

  <!-- Mode Python -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/python/python.min.js"></script>

  <!-- Addons untuk Bracket Matching -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/matchbrackets.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/addon/edit/closebrackets.min.js"></script>




</head>

<body>
  <!-- Toast Notifications (Pindahkan ke sini agar tidak menggeser layout) -->
  @if(session('toasts'))
  <div class="position-fixed top-0 end-0 p-3 mt-2 mr-2" style="z-index: 1100;">
    @component('components.toast', ['toasts' => session('toasts')])
    @endcomponent
  </div>
  @endif

  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

      {{-- Menu --}}
      @include('partials.sidebar')
      {{-- /Menu --}}

      <!-- Layout container -->
      <div class="layout-page">

        @stack('datatables')
        <!-- Navbar -->
        @include('partials.header')
        <!-- / Navbar -->


        <!-- Content wrapper -->
        @yield('content')



        <!-- Content wrapper -->

        <!-- Footer -->
        <footer class="content-footer footer bg-footer-theme">
          <div class="container-xxl">
            <div
              class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
              <div class="text-body">
                ©
                <script>
                  document.write(new Date().getFullYear());
                </script>
                , made with ❤️ by
                <a href="https://themeselection.com" target="_blank" class="footer-link">ThemeSelection</a>
              </div>
              <div class="d-none d-lg-inline-block">
                <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>

                <a href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/"
                  target="_blank" class="footer-link me-4">Documentation</a>

                <a href="https://github.com/themeselection/sneat-html-admin-template-free/issues" target="_blank"
                  class="footer-link">Support</a>
              </div>
            </div>
          </div>
        </footer>
        <!-- / Footer -->

      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <!-- / Layout wrapper -->


  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->

  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>


  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

  <!-- Main JS -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <!-- Page JS -->
  <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

  <!-- Place this tag before closing body tag for github widget button. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil entity dari header dengan ID khusus
        var entity = document.getElementById("entityHeader").textContent.toLowerCase().trim();
        var formError = "{{ session('form_error') ?? '' }}";
        var entityId = "{{ session('entity_id') ?? '' }}";

        // Function: Load data for admin or teacher
        function loadData(entity, id) {
            fetch(`/dashboard/admin/users/${entity}/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    var form = document.getElementById(`${entity}Form`);
                    form.action = `/dashboard/admin/users/${entity}/${id}`;

                    // Populate form fields dynamically
                    document.getElementById('nameEdit').value = data.name || '';
                    document.getElementById('emailEdit').value = data.email || '';
                    document.getElementById('passwordEdit').value = ''; // Reset password field

                    if (entity === 'teacher') {
                        document.getElementById('NIPEdit').value = data.NIP || '';
                        document.getElementById('institutionEdit').value = data.institution || '';
                        document.getElementById('addressEdit').value = data.address || '';
                    }

                    if (entity === 'student') {
                        document.getElementById('NISEdit').value = data.NIS || '';
                        document.getElementById('institutionEdit').value = data.institution || '';
                        document.getElementById('addressEdit').value = data.address || '';
                    }
                })
                .catch(error => console.error(`Error loading ${entity} data:`, error));
        }

        // Function: Set delete modal for admin or teacher
        function setDeleteModal(entity) {
            var deleteModal = document.getElementById(`delete${capitalize(entity)}`);
            var deleteForm = document.getElementById(`delete${capitalize(entity)}Form`);
            var deleteName = document.getElementById(`delete${capitalize(entity)}Name`);

            deleteModal.addEventListener("show.bs.modal", function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute("data-id");
                var name = button.getAttribute("data-name");

                deleteName.textContent = name;
                deleteForm.action = `/dashboard/admin/users/${entity}/s/${id}`;
            });
        }

        // Function: Capitalize first letter
        function capitalize(text) {
            return text.charAt(0).toUpperCase() + text.slice(1);
        }

        function resetFormAndErrors(container) {
            // Reset semua input dalam container (modal/offcanvas)
            var inputs = container.querySelectorAll('input, textarea');
            inputs.forEach(function(input) {
                input.value = '';
            });

            // Reset pesan error
            var errorMessages = container.querySelectorAll('.invalid-feedback, .error-message'); // Sesuaikan dengan class error Anda
            errorMessages.forEach(function(error) {
                error.textContent = ''; // Kosongkan teks error
                error.style.display = 'none'; // Sembunyikan elemen error
            });

            // Hapus kelas is-invalid pada semua input
            var errorInputs = container.querySelectorAll('.is-invalid');
            errorInputs.forEach(function(input) {
                input.classList.remove('is-invalid');
            });
        }

        // Set delete modal
        setDeleteModal(entity);

        // Edit button click
        document.querySelectorAll(`.edit-${entity}-btn`).forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                loadData(entity, id);
            });
        });

        if (formError === 'create' && entity) {
            var createModal = new bootstrap.Modal(document.getElementById(`create${capitalize(entity)}`));
            createModal.show();
        }

        if (formError === 'update' && entity && entityId) {
            var updateModal = new bootstrap.Offcanvas(document.getElementById(`edit${capitalize(entity)}`));
            updateModal.show();
            loadData(entity, entityId); // Muat data entity yang gagal di-update
        }

        // Reset form on offcanvas close
        var editContainer = document.getElementById(`edit${capitalize(entity)}`);
          if (editContainer) {
              // Untuk offcanvas
              editContainer.addEventListener('hidden.bs.offcanvas', function() {
                  resetFormAndErrors(editContainer);
              });
          }

          // Reset form on offcanvas close
        var createContainer = document.getElementById(`create${capitalize(entity)}`);
          if (createContainer) {
              // Untuk modal
              createContainer.addEventListener('hidden.bs.modal', function() {
                  resetFormAndErrors(createContainer);
              });
          }

        

        // Capitalize function
        function capitalize(text) {
            return text.charAt(0).toUpperCase() + text.slice(1);
        }

         // Inisialisasi stepper
         window.stepperForm = new Stepper(document.querySelector('#stepperForm'));


    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toastElList = [].slice.call(document.querySelectorAll('.toast'))
      toastElList.forEach(function (toastEl) {
          const toast = new bootstrap.Toast(toastEl, { delay: 4000 })
          toast.show()
      });
  });
  </script>




</body>

</html>