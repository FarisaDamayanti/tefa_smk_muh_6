<script>
    $(document).ready(function() {
        $('#title, #description, #question_limit').on('input', function() {
            const fieldName = $(this).attr('name');
            $('#error-' + fieldName).text('');
            $(this).removeClass('is-invalid');
        });

        $(document).on('click', '[data-dropdown] .dropdown-option', function() {
            const dropdown = $(this).closest('[data-dropdown]');
            const hiddenInput = dropdown.find('input[type="hidden"]');
            const inputName = hiddenInput.attr('name');

            $('#error-' + inputName).text('');
            dropdown.find('.dropdown-selected').removeClass('is-invalid');
        });

        $('#formQuestionBank').on('submit', function(e) {
            e.preventDefault();

            $('.invalid-feedback').text('');
            $('.is-invalid').removeClass('is-invalid');

            const btn = $('#btnSubmit');
            btn.prop('disabled', true);
            btn.find('.btn-text').addClass('d-none');
            btn.find('.spinner-border').removeClass('d-none');

            const formData = {
                title: $('#title').val(),
                subject_id: $('#subject_id').val(),
                level_class_id: $('#level_class_id').val(),
                description: $('#description').val(),
                question_limit: $('#question_limit').val(),
            };

            $.ajax({
                url: '{{ route('api.school.question-bank.update', ['question_bank' => $questionBank->id]) }}?_method=PUT',
                type: 'POST',
                headers: {
                    'Authorization': `Bearer {{ session('api_bearer') }}`,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function(res) {
                    Swal.fire('Berhasil', res.meta?.message || res.message, 'success')
                        .then(() => {
                            window.location.href =
                                "{{ route('school.question-bank.index') }}";
                        });
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.data;
                        $.each(errors, function(key, value) {
                            $('#error-' + key).text(value[0]);

                            if (key === 'subject_id' || key === 'level_class_id') {
                                $('[name="' + key + '"]').closest(
                                    '.custom-dropdown').find(
                                    '.dropdown-selected').addClass('is-invalid');
                            } else {
                                $('#' + key).addClass('is-invalid');
                            }
                        });
                    } else {
                        Swal.fire('Gagal', xhr.responseJSON?.meta?.message ||
                            'Terjadi kesalahan', 'error');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false);
                    btn.find('.btn-text').removeClass('d-none');
                    btn.find('.spinner-border').addClass('d-none');
                }
            });
        });
    });
</script>
