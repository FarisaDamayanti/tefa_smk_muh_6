<script>
    let isSubmitting = false;

    $('#form-create-topic').submit(function(e) {
        e.preventDefault();

        if (isSubmitting) {
            return false;
        }

        let formData = new FormData(this);
        let submitBtn = $('#btn-submit-create-topic');
        let cancelBtn = $('#create-topic-modal').find('[data-bs-dismiss="modal"]');
        let originalBtnText = submitBtn.html();

        isSubmitting = true;

        submitBtn.prop('disabled', true);
        cancelBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');

        $.ajax({
            type: "POST",
            url: "{{ route('api.teacher.topic.store', ['subject' => $subject->id, 'classroom' => $classroom->id]) }}",
            data: formData,
            headers: {
                'Authorization': 'Bearer {{ session('api_bearer') }}',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success(response.meta.message || 'Berhasil menambah data.');

                localStorage.setItem('show_create_topic_modal', 'true');
                localStorage.setItem('last_created_topic_title', response.data?.title || '');

                $('#create-topic-modal').modal('hide');

                setTimeout(() => {
                    location.reload();
                }, 800);

            },
            error: function(response) {
                isSubmitting = false;
                submitBtn.prop('disabled', false);
                cancelBtn.prop('disabled', false);
                submitBtn.html(originalBtnText);

                if (response.status === 422) {
                    $('#form-create-topic .form-control').removeClass('is-invalid');
                    $('#form-create-topic .invalid-feedback').text('');

                    let errors = response.responseJSON.data;
                    $.each(errors, function(field, messages) {
                        $(`[id="create-${field}-topic"]`).addClass('is-invalid');
                        $(`[id="create-${field}-topic"]`).closest('.mb-4').find(
                            '.invalid-feedback').text(messages[0]);
                    });
                } else {
                    toastr.error('Ada kesalahan saat menambah data.');
                }
            }
        });
    });

    $(document).ready(function() {
        const shouldOpenModal = localStorage.getItem('show_create_topic_modal');
        const lastCreatedTitle = localStorage.getItem('last_created_topic_title');

        if (shouldOpenModal === 'true') {
            localStorage.removeItem('show_create_topic_modal');
        }
    });

    $('#create-topic-modal').on('hidden.bs.modal', function() {
        $('#form-create-topic')[0].reset();
        $('#form-create-topic .form-control').removeClass('is-invalid');
        $('#form-create-topic .invalid-feedback').text('');

        isSubmitting = false;
        $('#btn-submit-create-topic').prop('disabled', false);
        $(this).find('[data-bs-dismiss="modal"]').prop('disabled', false);
        $('#btn-submit-create-topic').html('Tambah');
    });

    $('#create-topic-modal').on('show.bs.modal', function() {
        isSubmitting = false;
        $('#create-semester-topic').val('{{ $currentSemester }}');
    });
</script>
