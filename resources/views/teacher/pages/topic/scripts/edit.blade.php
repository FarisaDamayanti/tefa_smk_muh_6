<script>
    let isEditSubmitting = false; 

    $('.btn-edit-topic').click(function(e) {
        e.preventDefault();
        topicId = $(this).data('id');
        let title = $(this).data('title');
        let description = $(this).data('description');
        let semester = $(this).data('semester');

        $('#edit-title-topic').val(title);
        $('#edit-description-topic').val(description);
        $('#edit-semester-topic').val(semester);
        $('#edit-topic-modal').modal('show');
    });

    $('#form-edit-topic').submit(function(e) {
        e.preventDefault();

        if (isEditSubmitting) {
            return false;
        }

        let formDataEdit = new FormData(this);
        let submitBtn = $('#btn-submit-edit-topic');
        let cancelBtn = $('#edit-topic-modal').find('[data-bs-dismiss="modal"]');
        let originalBtnText = submitBtn.html();

        isEditSubmitting = true;

        submitBtn.prop('disabled', true);
        cancelBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');

        $.ajax({
            type: "POST",
            url: "{{ route('api.teacher.topics.update', '') }}/" + topicId + "?_method=PUT",
            data: formDataEdit,
            headers: {
                'Authorization': 'Bearer {{ session('api_bearer') }}',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success(response.meta.message || 'Berhasil memperbarui data.');

                $('#edit-topic-modal').modal('hide');

                setTimeout(() => {
                    location.reload();
                }, 800);
            },
            error: function(response) {
                isEditSubmitting = false;
                submitBtn.prop('disabled', false);
                cancelBtn.prop('disabled', false);
                submitBtn.html(originalBtnText);

                if (response.status === 422) {
                    $('#form-edit-topic .form-control').removeClass('is-invalid');
                    $('#form-edit-topic .invalid-feedback').text('');

                    let errors = response.responseJSON.data;
                    $.each(errors, function(field, messages) {
                        $(`[id="edit-${field}-topic"]`).addClass('is-invalid');
                        $(`[id="edit-${field}-topic"]`).closest('.mb-4').find(
                            '.invalid-feedback').text(messages[0]);
                    });
                } else {
                    toastr.error('Ada kesalahan saat memperbarui data.');
                }
            }
        });
    });

    $('#edit-topic-modal').on('hidden.bs.modal', function() {
        $('#form-edit-topic')[0].reset();
        $('#form-edit-topic .form-control').removeClass('is-invalid');
        $('#form-edit-topic .invalid-feedback').text('');

        isEditSubmitting = false;
        let submitBtn = $('#btn-submit-edit-topic');
        submitBtn.prop('disabled', false);
        $(this).find('[data-bs-dismiss="modal"]').prop('disabled', false);
        submitBtn.html(submitBtn.data('original-text') || 'Simpan');
    });

    $('#edit-topic-modal').on('show.bs.modal', function() {
        isEditSubmitting = false;

        let submitBtn = $('#btn-submit-edit-topic');
        if (!submitBtn.data('original-text')) {
            submitBtn.data('original-text', submitBtn.html());
        }
    });
</script>
