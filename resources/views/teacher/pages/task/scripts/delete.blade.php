<script>
    $('.btn-delete-task').click(function (e) {
        e.preventDefault();
        taskId = $(this).data('id');

        $('#delete-task-modal').modal('show');
    });

    $('#delete-task-form').submit(function(e) {
        e.preventDefault();

        // Cek apakah sedang proses submit
        if ($(this).data('submitting')) {
            return false;
        }

        // Tandai sedang proses submit
        $(this).data('submitting', true);

        // Simpan tombol submit
        let submitBtn = $(this).find('button[type="submit"]');
        let originalBtnText = submitBtn.html();

        // Disable tombol & tampilkan loader
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menghapus...');

        $.ajax({
            type: "POST",
            url: "{{ route('api.teacher.task.destroy', '') }}/" + taskId + "?_method=DELETE",
            headers: {
                'Authorization': 'Bearer {{ session("api_bearer") }}',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success(response.meta.message || 'Berhasil menghapus data.');
                setTimeout(() => location.reload(), 1000);
            },
            error: function(response) {
                // Reset tombol kalo error
                submitBtn.prop('disabled', false);
                submitBtn.html(originalBtnText);
                $('#delete-task-form').data('submitting', false);

                toastr.error(response.responseJSON.meta.message || 'Gagal menghapus data.');
            }
        });
    });
</script>
