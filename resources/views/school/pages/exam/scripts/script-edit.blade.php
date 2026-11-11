<script>
    $(function() {
        const $form = $('#form-edit-exam');
        const examId = $form.data('exam-id');

        const baseApi = "{{ config('app.api_url') }}/api/school/exams";
        const token = "Bearer {{ session('api_bearer') }}";
        const classroomsBase = "{{ config('app.api_url') }}/api/school/classrooms";

        const $btnSave = $('#btn-save-edit');
        const $classrooms = $('#classrooms');
        const $qbSelect = $('#question_bank_id');
        const $schoolYear = $form.find('[name="school_year_id"]');
        const $levelClass = $('#level_class_id');

        const locked = String($form.data('locked') || '0') === '1';

        function getCsrfToken() {
            return $('meta[name="csrf-token"]').attr('content') || '';
        }

        function setButtonLoading(isLoading) {
            if (!$btnSave.data('original-html')) $btnSave.data('original-html', $btnSave.html());

            if (isLoading) {
                $btnSave.prop('disabled', true).addClass('disabled');
                $btnSave.html(`
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        Menyimpan...
      `);
            } else {
                $btnSave.prop('disabled', false).removeClass('disabled');
                $btnSave.html($btnSave.data('original-html'));
            }
        }

        function clearErrors() {
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').remove();
            $form.find('.select2-selection').removeClass('is-invalid');
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
            $input.addClass('is-invalid');

            if ($input.hasClass('select2') || $input.hasClass('select2-hidden-accessible')) {
                $input.next('.select2-container').find('.select2-selection').addClass('is-invalid');
            }

            $input.after(`<div class="invalid-feedback d-block">${msg}</div>`);
        }

        $.ajaxSetup({
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            xhrFields: {
                withCredentials: true
            }
        });

        // select2
        if ($.fn.select2) {
            $classrooms.select2({
                width: '100%'
            });
        }

        // set selected awal
        let selectedIds = [];
        try {
            selectedIds = JSON.parse($classrooms.attr('data-selected') || '[]');
        } catch (e) {
            selectedIds = [];
        }

        if (selectedIds.length) $classrooms.val(selectedIds).trigger('change.select2');
        else $classrooms.trigger('change.select2');


        if (locked) {
            $form.find('input, select, textarea, button')
                .not('[name="duration"]')
                .not('#btn-save-edit')
                .prop('disabled', true);

            if ($.fn.select2) {
                $classrooms.prop('disabled', true).trigger('change.select2');
            }

            $('#exam-additional-rules').summernote('disable');
        }

        function fromDatetimeLocal(value) {
            return value ? value.replace('T', ' ') : '';
        }

        function getLevelClassId() {
            const fromOption = $qbSelect.find(':selected').data('level-class-id');
            const fromHidden = $levelClass.val();
            const levelId = (fromOption || fromHidden || '').toString().trim();
            $levelClass.val(levelId);
            return levelId;
        }

        async function fetchClassrooms(levelClassId, schoolYearId) {
            if (locked) return;
            if (!levelClassId || !schoolYearId) return;

            try {
                const url = `${classroomsBase}/${levelClassId}/${schoolYearId}`;
                const res = await $.ajax({
                    type: 'GET',
                    url,
                    headers: {
                        'Authorization': token
                    }
                });

                const items = res?.data || [];

                const currentSelected = $classrooms.val() || [];
                const selectedOptionText = {};
                currentSelected.forEach(id => {
                    const $opt = $classrooms.find(`option[value="${id}"]`);
                    if ($opt.length) selectedOptionText[id] = $opt.text();
                });

                $classrooms.empty();

                items.forEach(it => {
                    $classrooms.append(
                        `<option value="${it.id}">${(it.level_class?.name || '') + ' ' + (it.name || '')}</option>`
                    );
                });

                currentSelected.forEach(id => {
                    if ($classrooms.find(`option[value="${id}"]`).length === 0) {
                        const label = selectedOptionText[id] || `Kelas (${id})`;
                        $classrooms.append(`<option value="${id}" selected>${label}</option>`);
                    }
                });

                $classrooms.val(currentSelected).trigger('change.select2');
            } catch (err) {
                console.log('FETCH CLASSROOMS ERROR:', err);
            }
        }

        $qbSelect.on('change', function() {
            if (locked) return;
            fetchClassrooms(getLevelClassId(), $schoolYear.val());
        });

        $schoolYear.on('change', function() {
            if (locked) return;
            fetchClassrooms(getLevelClassId(), $schoolYear.val());
        });

        fetchClassrooms(getLevelClassId(), $schoolYear.val());

        $form.on('submit', function(e) {
            e.preventDefault();
            clearErrors();
            setButtonLoading(true);

            let fd;

            if (locked) {
                fd = new FormData();
                fd.set('_method', 'PUT');

                const duration = $form.find('[name="duration"]').val() || '';
                fd.set('duration', duration);

                const allowEarly = $('#allow_early_submission').is(':checked') ? 1 : 0;
                fd.set('allow_early_submission', allowEarly);

            } else {
                fd = new FormData(this);
                fd.set('_method', 'PUT');

                fd.set('level_class_id', getLevelClassId() || '');

                const qbId = $qbSelect.val();
                if (qbId) fd.set('question_bank_id', qbId);

                const tqRaw = fd.get('total_questions') ?? fd.get('total_question') ?? '';
                const tq = (tqRaw === null || tqRaw === '') ? 0 : tqRaw;
                fd.set('total_questions', tq);
                fd.set('total_question', tq);

                const semVal = String($form.find('[name="semester"]').val() || '').toLowerCase().trim();
                fd.set('semester', semVal);

                const dt = $form.find('[name="exam_date"]').val();
                if (dt) fd.set('exam_date', fromDatetimeLocal(dt));

                if ($.fn.summernote) {
                    fd.set('rules', $('#exam-additional-rules').summernote('code'));
                }

                const allowEarly = $('#allow_early_submission').is(':checked') ? 1 : 0;
                fd.set('allow_early_submission', allowEarly);

                const can_view_answer_result = $('#can_view_answer_result').is(':checked') ? 1 : 0;
                fd.set('can_view_answer_result', can_view_answer_result);
            }

            $.ajax({
                type: 'POST',
                url: `${baseApi}/${examId}`,
                data: fd,
                processData: false,
                contentType: false,
                xhrFields: {
                    withCredentials: true
                },
                headers: {
                    'Authorization': token,
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },

                success: function(res) {
                    Swal.fire({
                        title: "Sukses",
                        text: res?.meta?.message || 'Berhasil memperbarui ujian.',
                        icon: "success"
                    }).then(() => {
                        window.location.href = "{{ route('school.exams.index') }}";
                    });
                },

                error: function(xhr) {
                    console.log('STATUS:', xhr.status);
                    console.log('RESPONSE:', xhr.responseJSON || xhr.responseText);

                    if (xhr.status === 419) {
                        Swal.fire({
                            title: "Session Expired",
                            text: "CSRF mismatch / session expired. Silakan refresh halaman lalu coba lagi.",
                            icon: "error"
                        });
                        return;
                    }

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
                            text: getMessageFromXhr(xhr,
                                'Periksa kembali form yang diisi'),
                            icon: "error"
                        });
                        return;
                    }

                    Swal.fire({
                        title: "Gagal",
                        text: xhr?.responseJSON?.meta?.message || getMessageFromXhr(
                            xhr, 'Gagal update ujian'),
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
