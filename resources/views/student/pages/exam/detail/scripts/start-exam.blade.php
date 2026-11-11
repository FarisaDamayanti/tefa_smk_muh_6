<script>
    $('#start-button').click(function(e) {
        e.preventDefault();
        startbButton = $(this);
        setButtonLoading(startbButton, true);

        let formData = new FormData($('#form-start-exam')[0]);

        $.ajax({
            type: "POST",
            url: "{{ route('api.student.exam.start', $exam->id) }}",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'Authorization': 'Bearer {{ session('api_bearer') }}',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(response) {
                $('#reading-the-rules-confirmation-modal').modal('hide');
                setTimeout(function() {
                    startExamCountdown();
                }, 300);
            },
            error: function(response) {
                if (response.status === 422) {
                    const errors = response.responseJSON.data;
                    
                    $.each(errors, function(field, messages) {
                        $('#error-' + field).html(messages[0]);
                        $('input[name="' + field + '"]').addClass('is-invalid');
                    });
                } else if (response.status === 401 || response.status === 404) {
                    $('#error-token').html(response.responseJSON?.meta.message || "Token tidak valid.");
                    $('#token').addClass('is-invalid');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.responseJSON?.meta.message || "Terjadi kesalahan saat memulai ujian.",
                    });
                }
            },
            complete: function() {
                setButtonLoading(startbButton, false);
            }
        });
    });

    function startExamCountdown() {
        const overlay = $('#exam-start-overlay');
        const countdownEl = $('#exam-countdown');
        const startAt = $('#exam-start-at');
        const startText = $('#exam-start-text');
        overlay.removeClass('d-none');

        let count = 3;

        countdownEl.text(count);

        const interval = setInterval(() => {
            count--;

            if (count > 0) {
                countdownEl.text(count);
            } else {
                clearInterval(interval);
                countdownEl.addClass('d-none');
                startAt.addClass('d-none');
                startText.removeClass('d-none');

                setTimeout(() => {
                    window.location.href = "{{ route('student.exams.working', ['exam' => $exam->id]) }}";
                }, 1000);
            }
        }, 1600);
    }
</script>
