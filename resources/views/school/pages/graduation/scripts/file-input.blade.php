<script>
    $(document).ready(function() {

        const dropzone = $('#uploadDropzone');
        const fileInput = $('#additionalFileInput');
        const previewContainer = $('#uploadedFilePreviewContainer');

        // Klik dropzone
        dropzone.on('click', function() {
            fileInput.trigger('click');
        });

        // Drag over
        dropzone.on('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();

            dropzone.addClass('border-primary');
        });

        // Drag leave
        dropzone.on('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();

            dropzone.removeClass('border-primary');
        });

        // Drop file
        dropzone.on('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();

            dropzone.removeClass('border-primary');

            const files = e.originalEvent.dataTransfer.files;

            if (files.length > 0) {
                fileInput[0].files = files;

                showFilePreview(files[0]);
            }
        });

        // Saat pilih file biasa
        fileInput.on('change', function() {

            if (this.files.length > 0) {
                showFilePreview(this.files[0]);
            }
        });

        $(document).on('click', '#removeUploadedFile', function() {
            $('#uploadedFilePreview').remove();

            $('#additionalFileInput').val('');
        });

        // Function preview
        function showFilePreview(file) {
            const fileName = file.name;
            const fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
            const fileURL = URL.createObjectURL(file);

            const isFilled = @json($graduation?->additional_file) ? true : false;

            const previewHTML = `
                <div id="uploadedFilePreviewContainer">
                    <div id="uploadedFilePreview"
                        class="d-flex align-items-center justify-content-between border rounded-3 p-3 my-3 ${isFilled ? 'bg-light-warning border-warning' : 'bg-light-success border-success'}">

                        <div class="d-flex align-items-center gap-3 flex-grow-1 overflow-hidden">

                            <svg width="40" height="40" viewBox="0 0 48 48" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0">

                                <path
                                    d="M26.344 6H18C14.228 6 12.344 6 11.172 7.172C10 8.344 10 10.228 10 14V34C10 37.772 10 39.656 11.172 40.828C12.344 42 14.228 42 18 42H30C33.772 42 35.656 42 36.828 40.828C38 39.656 38 37.772 38 34V17.656C38 16.84 38 16.43 37.848 16.064C37.696 15.698 37.408 15.406 36.828 14.828L29.172 7.172C28.592 6.592 28.304 6.304 27.938 6.152C27.57 6 27.16 6 26.344 6Z"
                                    stroke="${isFilled ? '#F7B00A' : '#13DEB9'}"
                                    stroke-width="2" />

                                <path d="M26 6V14C26 15.886 26 16.828 26.586 17.414C27.172 18 28.114 18 30 18H38"
                                    stroke="${isFilled ? '#F7B00A' : '#13DEB9'}"
                                    stroke-width="2" />
                            </svg>

                            <div class="overflow-hidden">
                                <p class="mb-0 fw-semibold small text-truncate">
                                    ${fileName}
                                </p>

                                <small class="text-muted">
                                    ${fileSize}
                                </small>
                            </div>
                        </div>

                        <!-- Tombol Hapus -->
                        <button type="button"
                            id="removeUploadedFile"
                            class="btn p-0 border-0 bg-transparent text-${isFilled ? 'warning' : 'success'} ms-3 flex-shrink-0">

                            <i class="ti ti-x fs-7"></i>
                        </button>
                    </div>
                </div>
            `;

            if (isFilled) {
                previewContainer.prepend(previewHTML);
            } else {
                previewContainer.html(previewHTML);
            }

        }
    });
</script>
