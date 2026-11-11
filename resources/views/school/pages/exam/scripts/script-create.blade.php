<script>
    $(function() {
        const $form = $('#form-create-exam');
        const $btn = $('#btn-save-exam');

        const apiUrl = "{{ config('app.api_url') }}/api/school/exams";
        const token = "Bearer {{ session('api_bearer') }}";

        const classroomsBase = "{{ config('app.api_url') }}/api/school/classrooms";

        const $qbSelect = $('#question_bank_id');
        const $classrooms = $('#classrooms');
        const $levelClass = $('#level_class_id');
        const $schoolYear = $form.find('[name="school_year_id"]');

        if ($.fn.select2) {
            $classrooms.select2({
                width: '100%'
            });
        }

        if (window.toastr) {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                timeOut: 2500,
                extendedTimeOut: 1200,
                positionClass: 'toast-top-right',
                newestOnTop: true
            };
        }

        function setButtonLoading(isLoading) {
            if (!$btn.data('original-html')) $btn.data('original-html', $btn.html());

            if (isLoading) {
                $btn.prop('disabled', true).addClass('disabled');
                $btn.html(`
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Menyimpan...
                `);
            } else {
                $btn.prop('disabled', false).removeClass('disabled');
                $btn.html($btn.data('original-html'));
            }
        }

        function getMessageFromRes(res, fallback) {
            return res?.meta?.message || res?.message || fallback || 'Terjadi kesalahan';
        }

        function getMessageFromXhr(xhr, fallback) {
            return (
                xhr?.responseJSON?.meta?.message ||
                xhr?.responseJSON?.message ||
                (xhr?.responseText ? String(xhr.responseText).trim() : '') ||
                fallback ||
                'Terjadi kesalahan'
            );
        }

        function showFieldError($input, msg) {
            $input.removeClass('is-invalid');
            $input.nextAll('.invalid-feedback').remove();

            if ($input.hasClass('select2-hidden-accessible')) {

                const $container = $input.next('.select2-container');
                const $selection = $container.find('.select2-selection');

                $input.addClass('is-invalid');
                $selection.addClass('is-invalid');

                $container.after(
                    `<div class="invalid-feedback d-block">${msg}</div>`
                );

            } else {
                $input.addClass('is-invalid');
                $input.after(
                    `<div class="invalid-feedback d-block">${msg}</div>`
                );
            }
        }

        function syncLevelClassFromQuestionBank() {
            const levelId = $qbSelect.find(':selected').data('level-class-id') || '';
            $levelClass.val(levelId);
            return levelId;
        }

        async function fetchClassrooms(levelClassId, schoolYearId) {
            $classrooms.val(null).trigger('change');
            $classrooms.empty();

            if (!levelClassId) {
                $classrooms.append(
                    `<option value="" disabled>Level kelas dari bank soal tidak ditemukan</option>`);
                $classrooms.trigger('change');
                return;
            }

            if (!schoolYearId) {
                $classrooms.append(`<option value="" disabled>Pilih tahun ajaran terlebih dahulu</option>`);
                $classrooms.trigger('change');
                return;
            }

            try {
                const url = `${classroomsBase}/${levelClassId}/${schoolYearId}`;
                const res = await $.ajax({
                    type: 'GET',
                    url,
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': token
                    }
                });

                const items = res?.data || [];

                if (!items.length) {
                    $classrooms.append(
                        `<option value="" disabled>Tidak ada kelas untuk filter ini</option>`);
                    $classrooms.trigger('change');
                    return;
                }

                items.forEach(it => {
                    $classrooms.append(
                        `<option value="${it.id}">${it.level_class?.name + ' ' + it.name}</option>`
                    );
                });

                $classrooms.trigger('change');
            } catch (err) {
                console.log('FETCH CLASSROOMS ERROR:', err);
                if (window.toastr) toastr.error('Gagal fetch data kelas');
                $classrooms.append(`<option value="" disabled>Gagal fetch data kelas</option>`);
                $classrooms.trigger('change');
            }
        }

        $qbSelect.on('change', function() {
            const levelId = syncLevelClassFromQuestionBank();
            fetchClassrooms(levelId, $schoolYear.val());
        });

        $schoolYear.on('change', function() {
            const levelId = syncLevelClassFromQuestionBank();
            fetchClassrooms(levelId, $schoolYear.val());
        });

        const initLevelId = syncLevelClassFromQuestionBank();
        fetchClassrooms(initLevelId, $schoolYear.val());

        $form.on('submit', function(e) {
            e.preventDefault();

            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').remove();
            $form.find('.select2-selection').removeClass('is-invalid');

            setButtonLoading(true);

            const fd = new FormData(this);

            fd.set('level_class_id', $levelClass.val() || '');

            const rulesDefault = String($('#exam-additional-rules').val() || '').trim();
            fd.set('rules', rulesDefault);

            if ($.fn.summernote) {
                const htmlAdditional = $('#exam-additional-rules').summernote('code');
                fd.set('additional_rules', htmlAdditional);
            }

            const dt = $form.find('[name="exam_datetime"]').val();
            if (dt) fd.set('exam_date', dt.replace('T', ' '));
            fd.delete('exam_datetime');

            const semVal = String($('#semester').val() || '').trim().toLowerCase();
            fd.set('semester', semVal);

            const allow_early_submission = $('#allow_early_submission').is(':checked') ? 1 : 0;
            fd.set('allow_early_submission', allow_early_submission);

            const can_view_answer_result = $('#can_view_answer_result').is(':checked') ? 1 : 0;
            fd.set('can_view_answer_result', can_view_answer_result);

            $.ajax({
                type: 'POST',
                url: apiUrl,
                data: fd,
                processData: false,
                contentType: false,
                headers: {
                    'Accept': 'application/json',
                    'Authorization': token
                },
                success: function(res) {
                    if (res && res.success === false) {
                        Swal.fire({
                            title: "Gagal",
                            text: getMessageFromRes(res, 'Gagal menambahkan data'),
                            icon: "error"
                        });
                        return;
                    }

                    Swal.fire({
                        title: "Sukses",
                        text: getMessageFromRes(res, 'Ujian berhasil ditambahkan'),
                        icon: "success"
                    }).then(() => {
                        window.location.href = "{{ route('school.exams.index') }}";
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON?.errors ?? xhr.responseJSON?.data ??
                        {};

                        if (!errors.duration) {
                            $('#error-duration').text('');
                            $form.find('[name="duration"]').removeClass('is-invalid');
                        }

                        Object.keys(errors).forEach(function(field) {
                            const msg = errors[field]?.[0] ?? 'Tidak valid';

                            let $input = $form.find(`[name="${field}"]`);

                            if (!$input.length) {
                                $input = $form.find(`[name="${field}[]"]`);
                            }

                            if (!$input.length && field === 'exam_date') {
                                $input = $form.find('[name="exam_datetime"]');
                            }

                            if (field === 'duration') {
                                $('#error-duration').text(msg);
                                if ($input.length) $input.addClass('is-invalid');
                                return;
                            }

                            if ($input.length) {
                                showFieldError($input, msg);
                            }
                        });

                        Swal.fire({
                            title: "Validasi Gagal",
                            text: 'Periksa kembali form yang diisi',
                            icon: "error"
                        });
                        return;
                    }

                    Swal.fire({
                        title: "Gagal",
                        text: getMessageFromXhr(xhr, 'Gagal menyimpan ujian'),
                        icon: "error"
                    });
                },
                complete: function() {
                    setButtonLoading(false);
                }
            });
        });
    });
</script>
