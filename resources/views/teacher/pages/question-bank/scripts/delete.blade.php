<script>
    let isDeleteSubmitting = false;

    $('.btn-delete-question').click(function (e) {
        e.preventDefault();
        questionId = $(this).data('id');

        $('#delete-question-modal').modal('show');
    });

    $('#delete-question-form').submit(function(e) {
        e.preventDefault();

        if (isDeleteSubmitting) {
            return false;
        }

        let submitBtn = $('#delete-question-modal').find('button[type="submit"]');
        let cancelBtn = $('#delete-question-modal').find('[data-bs-dismiss="modal"]');
        let originalBtnText = submitBtn.html();

        isDeleteSubmitting = true;

        submitBtn.prop('disabled', true);
        cancelBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...');

        $.ajax({
            type: "POST",
            url: "{{ route('api.teacher.exam-question.destroy', '') }}/" + questionId + "?_method=DELETE",
            headers: {
                'Authorization': 'Bearer {{ session("api_bearer") }}',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success(response.meta.message || 'Berhasil menghapus data.');

                $('#delete-question-modal').modal('hide');

                setTimeout(() => {
                    location.reload();
                }, 800);
            },
            error: function(response) {
                isDeleteSubmitting = false;
                submitBtn.prop('disabled', false);
                cancelBtn.prop('disabled', false);
                submitBtn.html(originalBtnText);

                toastr.error(response.responseJSON?.meta?.message || 'Ada kesalahan saat menghapus data.');
            }
        });
    });

    $('#delete-question-modal').on('hidden.bs.modal', function () {
        isDeleteSubmitting = false;
        let submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', false);
        $(this).find('[data-bs-dismiss="modal"]').prop('disabled', false);
        submitBtn.html(submitBtn.data('original-text') || 'Hapus');
    });

    $('#delete-question-modal').on('show.bs.modal', function () {
        isDeleteSubmitting = false;

        let submitBtn = $(this).find('button[type="submit"]');
        if (!submitBtn.data('original-text')) {
            submitBtn.data('original-text', submitBtn.html());
        }
    });
</script>
