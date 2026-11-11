<script>
    $('#btn-generate').on('click', function(e) {
        e.preventDefault();
        let button = $(this);

        button.prop('disabled', true);
        button.html(
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Membuat...'
        );

        $.ajax({
            url: "{{ route('api.school.exams.generate-token', ['exam' => $exam->id]) }}",
            method: "PATCH",
            headers: {
                Authorization: "Bearer {{ session('api_bearer') }}",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.meta.message || "Token ujian berhasil dibuat.",
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: xhr.responseJSON?.message || "Token ujian gagal dibuat.",
                });
            },
            complete: function() {
                button.prop('disabled', false);
                button.html('Buat Token');
            }
        });
    })



</script>
