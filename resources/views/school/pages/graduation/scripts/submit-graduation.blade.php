<script>
    $(document).ready(function() {
        $('#graduationSubmissionForm').submit(function(e) {
            const fileInput = $('#additionalFileInput')[0];
            const file = fileInput.files[0];

            const dropzone = $('#uploadDropzone');
            const additionalFileError = $('#additional_file-error');

            const isFilled = @json($graduation?->additional_file) ? true : false;

            const buttonSubmit = $('#btn-submit-graduation');

            dropzone.removeClass('border-danger');
            additionalFileError.html('');

            setLoading(buttonSubmit);

            if (file) {
                const allowedExtensions = ['pdf', 'docx'];
                const fileExtension = file.name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(fileExtension)) {
                    e.preventDefault();

                    dropzone.addClass('border-danger');
                    additionalFileError.html(
                        'File harus bertipe PDF atau DOCX.'
                    );

                    removeLoading(buttonSubmit, 'Simpan');
                    return;
                }

                const allowedMimeTypes = [
                    'application/pdf',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];

                if (!allowedMimeTypes.includes(file.type)) {
                    e.preventDefault();

                    dropzone.addClass('border-danger');
                    additionalFileError.html(
                        'File harus bertipe PDF atau DOCX.'
                    );

                    removeLoading(buttonSubmit, 'Simpan');
                    return;
                }

                const maxSize = 10 * 1024 * 1024;

                if (file.size > maxSize) {
                    e.preventDefault();

                    dropzone.addClass('border-danger');
                    additionalFileError.html(
                        'Ukuran file maksimal adalah 10 MB.'
                    );

                    removeLoading(buttonSubmit, 'Simpan');
                    return;
                }

                if (isFilled) {
                    e.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Perbarui File Tambahan?',
                        text: 'File tambahan sebelumnya akan digantikan dengan file yang baru.',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Perbarui',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        confirmButtonColor: '#5D87FF',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            e.target.submit();
                        }
                    });

                    removeLoading(buttonSubmit, 'Simpan');
                    return;
                }
            }
        });

        function setLoading(element) {
            element.prop('disabled', true)
                .html('<div class="spinner-border spinner-border-sm me-2" role="status"></div>Memuat...');
        }

        function removeLoading(element, originalHtml) {
            element.prop('disabled', false)
                .html(originalHtml);
        }
    });
</script>
