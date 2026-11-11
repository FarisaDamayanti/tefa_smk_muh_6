<script>
    $(function() {
        const csrfToken = @json(csrf_token());

        if (window.toastr) {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 3000,
                extendedTimeOut: 1000,
                showDuration: 300,
                hideDuration: 300,
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut'
            };
        }

        function toast(type, message) {
            if (!window.toastr) return;
            toastr[type](message);
        }

        if ($.fn.select2) {
            $('#select2').select2({
                dropdownParent: $('#modal-rolling-student'),
                width: '100%'
            });
        }
        $('.select2-selection.select2-selection--multiple').addClass('w-100');

        // =========================
        // MODAL SHOW (LOADING EFFECT)
        // =========================
        $('#modal-rolling-student')
            .off('shown.bs.modal.rolling')
            .on('shown.bs.modal.rolling', function() {
                setTimeout(function() {
                    $('#modal-rolling-student-loading').fadeOut('fast', function() {
                        $(this).remove();
                    });
                    $('#modal-rolling-student-content').fadeIn('fast');
                }, 300);
            });

        $('#btn-rolling')
            .off('click.rolling')
            .on('click.rolling', function(e) {
                e.preventDefault();
                getStudentDoesntHaveClassroom();

                // Bootstrap 5 safe
                const modalEl = document.getElementById('modal-rolling-student');
                if (window.bootstrap?.Modal) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                } else {
                    $('#modal-rolling-student').modal('show');
                }
            });

        function getStudentDoesntHaveClassroom() {
            $.ajax({
                type: "GET",
                url: "{{ route('school.class-student.doesntHaveClassroom', request()->classroom->id) }}",
                dataType: "json",
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                success: function(response) {
                    const $select = $('#select2');
                    $select.empty();

                    (response.data || []).forEach(student => {
                        $select.append(
                            `<option value="${student.id}">${student.user.name}</option>`
                        );
                    });

                    $select.trigger('change');
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Gagal memuat daftar siswa.';
                    toast('error', msg);
                }
            });
        }

        $('#form-add-student-classroom')
            .off('submit.rolling')
            .on('submit.rolling', function(e) {
                e.preventDefault();

                const studentIds = $('#select2').val() || [];
                if (!studentIds.length) {
                    toast('warning', 'Pilih minimal 1 siswa.');
                    return;
                }

                const fd = new FormData();
                studentIds.forEach((id, i) => fd.append(`student_ids[${i}]`, id));

                const $btn = $(this).find('button[type="submit"]');
                const oldText = $btn.text();
                $btn.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('school.classroom-student.store', request()->classroom->id) }}",
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    success: function() {
                        toast('success', 'Siswa berhasil di-rolling ke kelas.');

                        $('#select2').val(null).trigger('change');

                        const modalEl = document.getElementById('modal-rolling-student');
                        if (window.bootstrap?.Modal) {
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                        } else {
                            $('#modal-rolling-student').modal('hide');
                        }

                        location.reload();
                    },
                    error: function(xhr) {
                        const msg =
                            xhr.responseJSON?.message ||
                            xhr.responseJSON?.meta?.message ||
                            (xhr.status === 401 ? 'Unauthorized: silakan login ulang' :
                                xhr.status === 403 ? 'Forbidden: role tidak sesuai' :
                                xhr.status === 422 ?
                                'Validasi gagal: pastikan pilih siswa yang valid' :
                                'Gagal rolling siswa.');

                        toast('error', msg);
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text(oldText);
                    }
                });
            });
    });
</script>
