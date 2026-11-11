<script>
    $('#form-create-task').submit(function(e) {
        e.preventDefault();

        // Cek dulu apakah sudah submittingK
        if ($(this).data('submitting')) {
            return false;
        }

        let form = $(this);
        let isAllowOverdue = $('#allow_overdue').is(':checked');

        let confirmMessage = isAllowOverdue ?
            "Apakah anda yakin untuk <b>mengizinkan</b> siswa mengumpulkan tugas meskipun <b>telah terlambat?</b>" :
            "Apakah anda yakin untuk <b>tidak mengizinkan</b> siswa mengumpulkan tugas jika <b>sudah terlambat?</b>";

        Swal.fire({
            title: 'Konfirmasi',
            html: confirmMessage,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Yakin',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.data('submitting', true);

                let submitBtn = form.find('button[type="submit"]');
                let originalBtnText = submitBtn.html();

                submitBtn.prop('disabled', true);
                submitBtn.html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...'
                );

                let formData = new FormData(form[0]);
                formData.set('allow_overdue', isAllowOverdue ? 1 : 0);

                $.ajax({
                    type: "POST",
                    url: "{{ route('api.teacher.task.store', $topic->id) }}",
                    data: formData,
                    headers: {
                        'Authorization': 'Bearer {{ session('api_bearer') }}',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        Swal.fire({
                            title: "Sukses",
                            text: response.meta.message ||
                                'Berhasil menambah data.',
                            icon: "success"
                        }).then(() => {
                            window.location.href =
                                "{{ route('teacher.e-learning.detail', [$topic->classroom->id, $topic->subject->id]) }}";
                        });
                    },
                    error: function(response) {
                        // Reset tombol kalo error
                        submitBtn.prop('disabled', false);
                        submitBtn.html(originalBtnText);
                        form.data('submitting', false);

                        if (response.status === 422) {
                            $('#form-create-task .form-control').removeClass('is-invalid');
                            $('#form-create-task .invalid-feedback').text('');

                            let errors = response.responseJSON.data;
                            $.each(errors, function(field, messages) {
                                $(`[id="create-${field}-task"]`).addClass(
                                    'is-invalid');
                                $(`[id="create-${field}-task"]`).closest('.mb-4')
                                    .find(
                                        '.invalid-feedback').text(messages[0]);
                            });
                        } else {
                            toastr.error(response.responseJSON.meta.message || 'Ada kesalahan saat memperbarui data.');
                        }
                    }
                });
            }
        });
    });
</script>
