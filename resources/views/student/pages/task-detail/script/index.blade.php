@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const submissionType = "{{ $task->submission_type }}";
            const fileExist = "{{ $task?->currentUserTaskSubmission?->answer }}";

            /* ===============================
               URL INPUT + COPY BUTTON
            =============================== */
            const urlInput = document.getElementById('url-task');
            const copyBtn = document.getElementById('copyBtn');

            function toggleCopyButton() {
                if (!urlInput || !copyBtn) return;
                copyBtn.style.display = urlInput.value.trim() !== '' ? 'block' : 'none';
            }

            window.copyUrl = function() {
                if (!urlInput) return;

                navigator.clipboard.writeText(urlInput.value).then(() => {
                    copyBtn.innerText = 'Tersalin ✓';
                    setTimeout(() => copyBtn.innerText = 'Salin Tautan', 1500);
                });
            };

            if (urlInput) {
                urlInput.addEventListener('input', toggleCopyButton);
                toggleCopyButton();
            }

            /* ===============================
               FILE DROPZONE
            =============================== */
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('fileInput');
            const preview = document.getElementById('previewContainer');

            if (dropzone && fileInput) {

                dropzone.addEventListener('click', () => fileInput.click());

                dropzone.addEventListener('dragover', e => {
                    e.preventDefault();
                    dropzone.classList.add('border-primary');
                });

                dropzone.addEventListener('dragleave', () => {
                    dropzone.classList.remove('border-primary');
                });

                dropzone.addEventListener('drop', e => {
                    e.preventDefault();
                    dropzone.classList.remove('border-primary');

                    const file = e.dataTransfer.files[0];
                    if (!file) return;

                    fileInput.files = e.dataTransfer.files;
                    showPreview(file);
                });

                fileInput.addEventListener('change', () => {
                    if (fileInput.files.length) {
                        showPreview(fileInput.files[0]);
                    }
                });
            }

            function showPreview(file) {
                if (!preview) return;

                preview.innerHTML = `
      <div class="d-flex justify-content-between align-items-center
        border border-warning rounded p-2 bg-light-warning">
          <div class="d-flex align-items-center gap-2">
              <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M26.344 6H18C14.228 6 12.344 6 11.172 7.172C10 8.344 10 10.228 10 14V34C10 37.772 10 39.656 11.172 40.828C12.344 42 14.228 42 18 42H30C33.772 42 35.656 42 36.828 40.828C38 39.656 38 37.772 38 34V17.656C38 16.84 38 16.43 37.848 16.064C37.696 15.698 37.408 15.406 36.828 14.828L29.172 7.172C28.592 6.592 28.304 6.304 27.938 6.152C27.57 6 27.16 6 26.344 6Z" stroke="#e8ae3a" stroke-width="2" />
                  <path d="M26 6V14C26 15.886 26 16.828 26.586 17.414C27.172 18 28.114 18 30 18H38" stroke="#e8ae3a" stroke-width="2" />
              </svg>

              <div>
                  <small class="fw-semibold d-block">${file.name}</small>
                  <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
              </div>
          </div>
          
         <button type="button"
    class="btn p-0 border-0 bg-transparent shadow-none btn-remove"
    onclick="removeFile()"
    aria-label="Close">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M30.972 26.775L24.0945 19.8962L30.9695 13.0187L27.4358 9.4812L20.557 16.3587L13.6795 9.4812L10.1445 13.0187L17.0195 19.8962L10.1445 26.7737L13.682 30.3087L20.557 23.4312L27.432 30.3087L30.972 26.775Z" fill="#FFAE1F"/>
</svg>
</button>
      </div>
    `;
            }

            // Fungsi untuk menghapus file preview
            window.removeFile = function() {
                if (fileInput) {
                    fileInput.value = '';
                }
                if (preview) {
                    preview.innerHTML = '';
                }
            };

            /* ===============================
               load button
            =============================== */
            function setLoading(isLoading) {
                const btn = $('#submitBtn');

                if (isLoading) {
                    btn.prop('disabled', true);
                    btn.find('.btn-text').addClass('d-none');
                    btn.find('.btn-loading').removeClass('d-none');
                } else {
                    btn.prop('disabled', false);
                    btn.find('.btn-text').removeClass('d-none');
                    btn.find('.btn-loading').addClass('d-none');
                }
            }

            /* ===============================
               SUBMIT AJAX
            =============================== */
            $('#submissionForm').on('submit', function(e) {
                e.preventDefault();

                // Check if file already exists
                if (fileExist && fileExist.trim() !== '') {
                    // Show confirmation dialog
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Apakah kamu yakin untuk mengumpulkan ulang?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, kirim ulang!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitForm();
                        } else {
                            setLoading(false);
                        }
                    });
                } else {
                    submitForm();
                }
            });

            // Separate function for form submission logic
            function submitForm() {
                let formData = new FormData($('#submissionForm')[0]);
                setLoading(true);

                $('#validationErrors').addClass('d-none');
                $('#errorList').html('');
                $('.is-invalid').removeClass('is-invalid');

                let urlInput = document.getElementById('url-task');
                let fileInput = document.getElementById('fileInput');

                formData.append('submission_type', submissionType);

                // =========================
                // MODE URL
                // =========================
                if (submissionType === 'url') {
                    if (!urlInput || urlInput.value.trim() === '') {
                        urlInput.classList.add('is-invalid');
                        $('#errorList').html('<li>Link tugas wajib diisi</li>');
                        $('#validationErrors').removeClass('d-none');
                        setLoading(false);
                        return;
                    }
                }

                // =========================
                // MODE FILE
                // =========================
                if (submissionType === 'file') {
                    if (!fileInput || fileInput.files.length === 0) {
                        $('#dropzone').addClass('is-invalid');
                        $('#errorList').html('<li>File wajib diupload</li>');
                        $('#validationErrors').removeClass('d-none');
                        setLoading(false);
                        return;
                    }
                }

                // =========================
                // AJAX JALAN 
                // =========================
                $.ajax({
                    url: $('#submissionForm').attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('student_token')
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message
                        }).then(() => location.reload());
                    },
                    error: function(xhr) {
                        setLoading(false);

                        if (xhr.status === 422) {
                            Object.values(xhr.responseJSON.errors).forEach(err => {
                                $('#errorList').append(`<li>${err[0]}</li>`);
                            });
                            $('#validationErrors').removeClass('d-none');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Terjadi kesalahan saat mengirim tugas'
                            });
                        }
                    }
                });
            }

        });
    </script>
@endsection
