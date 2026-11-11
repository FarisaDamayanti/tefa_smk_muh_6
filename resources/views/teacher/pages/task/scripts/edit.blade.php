<script>
    $('#form-edit-task').submit(function(e) {
        e.preventDefault();

        let formDataEdit = new FormData(this);
        formDataEdit.set('allow_overdue', $('#allow_overdue').is(':checked') ? 1 : 0);

        if ($('#edit-submission_type-task').is(':disabled')) {
            formDataEdit.set('submission_type', '{{ $task->submission_type->value }}');
        }

        $.ajax({
            type: "POST",
            url: "{{ route('api.teacher.task.update', $task->id) }}" + "?_method=PUT",
            data: formDataEdit,
            headers: {
                'Authorization': 'Bearer {{ session("api_bearer") }}',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    title: "Sukses",
                    text: response.meta.message || 'Berhasil memperbarui data.',
                    icon: "success"
                }).then(() => {
                    window.location.href = "{{ route('teacher.e-learning.detail', [$task->topic->classroom->id, $task->topic->subject->id]) }}";
                });
            },
            error: function(response) {
                if (response.status === 422) {
                    $('#form-edit-task .form-control').removeClass('is-invalid');
                    $('#form-edit-task .form-select').removeClass('is-invalid');
                    $('#form-edit-task .invalid-feedback').text('');

                    let errors = response.responseJSON.data;
                    $.each(errors, function(field, messages) {
                        if (field === 'submission_type' && $('#edit-submission_type-task').is(':disabled')) {
                            return true;
                        }

                        $(`[id="edit-${field}-task"]`).addClass('is-invalid');
                        $(`[id="edit-${field}-task"]`).closest('.mb-4').find('.invalid-feedback').text(messages[0]);
                    });
                } else {
                    toastr.error(response.responseJSON.meta.message || 'Ada kesalahan saat memperbarui data.');
                }
            }
        });
    });
</script>
