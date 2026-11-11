<script>
    $('.btn-add-note').click(function(e) {
        e.preventDefault();
        let submissionTaskId = $(this).data('submission-task-id');
        let studentName = $(this).data('student-name');
        $('#note-title').text('Catatan untuk ' + studentName);
        let note = $(this).data('note');
        $('#input-note').val(note);
        $('#form-edit-task .form-control').removeClass('is-invalid');
        $('#form-edit-task .invalid-feedback').text('');

        $('#add-note-modal').modal('show');
    });

    $('#form-add-note').submit(function(e) {
        e.preventDefault();
        let note = $('#input-note').val();
        let submissionTaskId = $('.btn-add-note').data('submission-task-id');

        $.ajax({
            url: "{{ route('api.teacher.update.assessment-note', '') }}/" + submissionTaskId,
            method: 'POST',
            headers: {
                'Authorization': 'Bearer {{ session('api_bearer') }}',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: {
                note: note,
            },
            success: function(response) {
                Swal.fire({
                    title: "Sukses",
                    text: 'Berhasil menyimpan catatan.',
                    icon: "success"
                }).then(() => {
                    window.location.reload();
                    $('#add-note-modal').modal('hide');
                });
            },
            error: function(response) {
                if (response.status === 422) {
                    $('#form-add-note .form-control').removeClass('is-invalid');
                    $('#form-add-note .invalid-feedback').text('');

                    let errors = response.responseJSON.data;
                    $.each(errors, function(field, messages) {
                        $(`[id="input-${field}"]`).addClass('is-invalid');
                        $(`[id="input-${field}"]`).closest('.modal-body').find(
                            '.invalid-feedback').text(messages[0]);
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.responseJSON.meta.message ||
                            'Gagal menyimpan catatan.',
                        icon: "error"
                    });
                }
            }
        });
    });
</script>
