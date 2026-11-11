<script>
    $(document).ready(function () {

        function setButtonLoading(button, text = 'Loading...') {
            button.prop('disabled', true);
            button.data('original-text', button.html());
            button.html(`<span class="spinner-border spinner-border-sm me-2"></span>${text}`);
        }

        function resetButton(button) {
            button.prop('disabled', false);
            button.html(button.data('original-text'));
        }

        function syncResetManyBtn() {
            const checkedCount = $('.student-check-item:not(:disabled):checked').length;
            $('#btn-reset-many').toggleClass('d-none', checkedCount === 0);
        }

        $('.action-check-all').on('change', function () {
            $('.student-check-item:not(:disabled)').prop('checked', $(this).is(':checked'));
            syncResetManyBtn();
        });

        $('.student-check-item').on('change', function () {
            const total   = $('.student-check-item:not(:disabled)').length;
            const checked = $('.student-check-item:not(:disabled):checked').length;

            $('.action-check-all').prop('checked', total > 0 && total === checked);
            $('.action-check-all').prop('indeterminate', checked > 0 && checked < total);

            syncResetManyBtn();
        });

        $(document).on('click', '.btn-reset-student-exam', function () {
            const button        = $(this);
            const studentExamId = button.data('student-exam-id');

            if (!studentExamId) {
                toastr.error('ID ujian siswa tidak ditemukan.');
                return;
            }

            $.ajax({
                url: "{{ route('api.school.exams.reset', ['studentExam' => '__ID__']) }}"
                    .replace('__ID__', studentExamId),
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer {{ session('api_bearer') }}',
                    'Accept': 'application/json',
                },
                data: { _token: "{{ csrf_token() }}" },

                beforeSend: function () {
                    setButtonLoading(button, 'Reset Ujian...');
                },

                success: function (response) {
                    toastr.success(response.message || 'Ujian berhasil direset.');

                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                },

                error: function (xhr) {
                    resetButton(button);
                    const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat mereset ujian.';
                    toastr.error(message);
                }
            });
        });

        $('#btn-reset-many').on('click', function () {
            const button         = $(this);
            const studentExamIds = $('.student-check-item:checked').map(function () {
                return $(this).val();
            }).get().filter(Boolean);

            if (studentExamIds.length === 0) {
                toastr.warning('Pilih minimal satu data siswa terlebih dahulu.');
                return;
            }

            $.ajax({
                url: "{{ route('api.school.exams.reset-many') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    student_exam_ids: studentExamIds
                },

                beforeSend: function () {
                    setButtonLoading(button, 'Reset Ujian...');
                },

                success: function (response) {
                    toastr.success(response.message || 'Ujian siswa berhasil direset.');
                    window.location.reload();
                },

                error: function (xhr) {
                    resetButton(button);
                    const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat mereset banyak ujian.';
                    toastr.error(message);
                }
            });
        });

        $(document).on('click', '#btn-export-exam', function () {
            const button         = $(this);
            const examId         = button.data('exam-id');
            const classroomId    = button.data('classroom-id');

            if (!examId) {
                toastr.error('ID exam tidak ditemukan.');
                return;
            }

            const studentExamIds = $('.student-check-item:checked').map(function () {
                return $(this).val();
            }).get().filter(Boolean);

            setButtonLoading(button, 'Export...');

            let url = "{{ route('api.exams.export', ['exam' => '__EXAM-ID__', 'classroom' => '__CLASSROOM-ID__']) }}"
                .replace('__EXAM-ID__', examId).replace('__CLASSROOM-ID__', classroomId);

            if (studentExamIds.length > 0) {
                url += '?student_exam_ids=' + studentExamIds.join(',');
            }

            window.location.href = url;

            setTimeout(function () {
                resetButton(button);
            }, 2000);
        });

        $('#btn-set-pagination').on('click', function() {
            const url = new URL(window.location.href);
            const type = $(this).data('type');

            if (type === 'pagination') {
                url.searchParams.set('per_page', 'all');
            } else {
                url.searchParams.delete('per_page');
            }

            window.location.href = url.toString();
        })

    });
</script>
