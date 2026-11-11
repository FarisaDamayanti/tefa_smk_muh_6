<script>
    $(function() {
        // ======================================================
        // UI: show/hide form berdasarkan type
        // ======================================================
        function getQuestionType() {
            return ($('#question-type-hidden').val() || $('#question-type').val() || '').trim();
        }

        function toggleMatchingScoreBar(val) {
            const isMatching = val === 'matching';
            $('#matching-score-bar')
                .toggleClass('d-none', !isMatching)
                .toggleClass('d-flex', isMatching);
        }

        function showForm(val) {
            if (val === 'single_choice') val = 'multiple_choice';
            $('.question-type-form').addClass('d-none');
            $('#form-' + val).removeClass('d-none');
            toggleMatchingScoreBar(val);
        }

        showForm(getQuestionType());

        // ======================================================
        // Init Summernote — semua tipe
        // ======================================================
        // Pilihan Ganda
        ['question', 'answer-a', 'answer-b', 'answer-c', 'answer-d', 'answer-e'].forEach(function(id) {
            const $el = $('#summernote-' + id);
            if ($el.length && !$el.next('.note-editor').length) {
                $el.summernote({
                    height: 200
                });
            }
        });

        // Essay
        if ($('#summernote-essay-question').length && !$('#summernote-essay-question').next('.note-editor')
            .length) {
            $('#summernote-essay-question').summernote({
                height: 200
            });
        }

        // Matching
        if ($('#summernote-matching-question').length && !$('#summernote-matching-question').next(
                '.note-editor').length) {
            $('#summernote-matching-question').summernote({
                height: 200
            });
        }

        $('.js-matching-summernote').each(function() {
            const $el = $(this);
            if (!$el.next('.note-editor').length) {
                $el.summernote({
                    height: 200
                });
            }
        });

        // =========================================
        // HELPERS
        // =========================================
        function normalizeSummernote(html) {
            const val = (html ?? '').trim();
            if (!val || val === '<p><br></p>' || val === '<p>&nbsp;</p>') return '';
            return val;
        }

        function clearValidationUI() {
            $('.validation-message').text('');
            $('.is-invalid').removeClass('is-invalid');
            $('.note-editor').removeClass('border border-danger');
            $('#error-answer').html('');
        }

        function setLoading(isLoading, originalText) {
            const $btn = $('#btn-update-question');
            if (isLoading) {
                $btn.html('<span class="spinner-border spinner-border-sm me-2" role="status"></span>Loading...')
                    .prop('disabled', true);
            } else {
                $btn.html(originalText).prop('disabled', false);
            }
        }

        const redirectUrl = "{{ route('teacher.question-bank.detail', $examQuestion->questionBank->id) }}";

        // =========================================
        // SUBMIT HANDLER
        // =========================================
        $('#form-update-question').on('submit', function(e) {
            e.preventDefault();
            clearValidationUI();
            const type = getQuestionType();
            if (type === 'multiple_choice' || type === 'single_choice') submitUpdateMultipleChoice();
            else if (type === 'essay') submitUpdateEssay();
            else if (type === 'matching') submitUpdateMatching();
        });

        $('#btn-save-matching').on('click', function() {
            clearValidationUI();
            submitUpdateMatching();
        });

        // =========================================
        // MULTIPLE CHOICE — UPDATE
        // =========================================
        function buildMCPayload() {
            const fd = new FormData();
            const questionHtml = normalizeSummernote($('#summernote-question').summernote('code'));
            const optionHtml = {
                option_a: normalizeSummernote($('#summernote-answer-a').summernote('code')),
                option_b: normalizeSummernote($('#summernote-answer-b').summernote('code')),
                option_c: normalizeSummernote($('#summernote-answer-c').summernote('code')),
                option_d: normalizeSummernote($('#summernote-answer-d').summernote('code')),
                option_e: normalizeSummernote($('#summernote-answer-e').summernote('code')),
            };

            const mapLetterToKey = {
                A: 'option_a',
                B: 'option_b',
                C: 'option_c',
                D: 'option_d',
                E: 'option_e'
            };
            const checkedKeys = $('input[name="answer[]"]:checked').map(function() {
                return mapLetterToKey[$(this).val()];
            }).get().filter(Boolean);

            fd.append('_method', 'PUT');
            fd.append('question', questionHtml);
            ['option_a', 'option_b', 'option_c', 'option_d', 'option_e'].forEach((key, idx) => {
                fd.append(`options[${idx}][option_label]`, key);
                fd.append(`options[${idx}][option_text]`, optionHtml[key] ?? '');
                fd.append(`options[${idx}][is_correct]`, checkedKeys.includes(key) ? 1 : 0);
            });

            return fd;
        }

        function submitUpdateMultipleChoice() {
            if (!$('input[name="answer[]"]:checked').length) {
                $('#error-answer').html(
                    '<i class="ti ti-alert-triangle-filled" style="color:#FF0000;"></i> Jawaban wajib dipilih.'
                );
                return;
            }

            const origTxt = $('#btn-update-question').html();
            setLoading(true, origTxt);

            $.ajax({
                type: 'POST',
                url: "{{ route('api.teacher.exam-question.update', ['questionBank' => $examQuestion->questionBank->id, 'examQuestion' => $examQuestion->id]) }}",
                data: buildMCPayload(),
                processData: false,
                contentType: false,
                headers: {
                    'Authorization': 'Bearer {{ session('api_bearer') }}',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(res) {
                    setLoading(false, origTxt);
                    Swal.fire({
                        title: 'Sukses',
                        text: res.meta?.message || 'Berhasil memperbarui data.',
                        icon: 'success'
                    }).then(() => window.location.href = redirectUrl);
                },
                error: function(xhr) {
                    setLoading(false, origTxt);
                    clearValidationUI();
                    const errors = xhr.responseJSON?.errors || xhr.responseJSON?.data || {};

                    if (xhr.status === 422) {
                        let firstEl = null;

                        if (errors.question) {
                            $('#summernote-question').next('.note-editor').addClass(
                                'border border-danger');
                            $('#error-question').text(errors.question[0]);
                            firstEl ??= $('#summernote-question');
                        }
                        if (errors.options) {
                            $('#error-answer').html(
                                '<i class="ti ti-alert-triangle-filled" style="color:#FF0000;"></i> ' +
                                errors.options[0]);
                            firstEl ??= $('input[name="answer[]"]').first();
                        }
                        Object.keys(errors).forEach(field => {
                            const m = field.match(/^options\.(\d+)\.option_text$/);
                            if (!m) return;
                            const suffix = ['a', 'b', 'c', 'd', 'e'][parseInt(m[1], 10)];
                            if (!suffix) return;
                            $('#summernote-answer-' + suffix).next('.note-editor').addClass(
                                'border border-danger');
                            $('#error-option_' + suffix).text(errors[field][0]);
                            firstEl ??= $('#summernote-answer-' + suffix);
                        });

                        if (firstEl) $('html, body').animate({
                            scrollTop: firstEl.offset().top - 120
                        }, 400);
                    } else {
                        toastr.error(xhr.responseJSON?.message ||
                            'Ada kesalahan saat memperbarui data.');
                    }
                }
            });
        }

        // =========================================
        // ESSAY — UPDATE
        // =========================================
        function submitUpdateEssay() {
            const question = normalizeSummernote($('#summernote-essay-question').summernote('code'));
            const score = $('#essay-score').val();

            if (!question) {
                $('#error-essay_question').text('Soal uraian wajib diisi.');
                $('#summernote-essay-question').next('.note-editor').addClass('border border-danger');
                return;
            }
            if (!score) {
                $('#error-essay-score').text('Bobot soal wajib diisi.');
                $('#essay-score').addClass('is-invalid');
                return;
            }

            const origTxt = $('#btn-update-question').html();
            setLoading(true, origTxt);

            $.ajax({
                type: 'PUT',
                url: "{{ route('api.teacher.exam-question.essay.update', $examQuestion->id) }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    question: question,
                    score: score
                },
                headers: {
                    'Authorization': 'Bearer {{ session('api_bearer') }}',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(res) {
                    setLoading(false, origTxt);
                    if (res.success) {
                        Swal.fire({
                            title: 'Sukses',
                            text: res.message || 'Berhasil memperbarui soal esai.',
                            icon: 'success'
                        }).then(() => window.location.href = redirectUrl);
                    } else {
                        toastr.error(res.message || 'Gagal memperbarui soal esai.');
                    }
                },
                error: function(xhr) {
                    setLoading(false, origTxt);
                    clearValidationUI();
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON?.errors || {};
                        if (errors.question) {
                            $('#error-essay_question').text(errors.question[0]);
                            $('#summernote-essay-question').next('.note-editor').addClass(
                                'border border-danger');
                        }
                        if (errors.score) {
                            $('#error-essay-score').text(errors.score[0]);
                            $('#essay-score').addClass('is-invalid');
                        }
                        return;
                    }
                    toastr.error(xhr.responseJSON?.message || 'Terjadi kesalahan, coba lagi.');
                }
            });
        }

        // =========================================
        // MATCHING — UPDATE
        // =========================================
        const MAX_MATCHING = 4;
        const MIN_MATCHING = 1;
        const $list = $('#matching-list');
        const $btnAdd = $('#btn-add-matching-row');
        const $limitHint = $('#matching-limit-hint');
        let deletedIds = [];

        function isSummernoteReady() {
            return typeof $.fn.summernote === 'function';
        }

        function initMatchingSummernote($el) {
            if (!isSummernoteReady() || $el.next('.note-editor').length) {
                return;
            }

            $el.summernote({
                height: 200
            });
        }

        function destroyMatchingSummernote($el) {
            if (!isSummernoteReady() || !$el.next('.note-editor').length) {
                return;
            }

            $el.summernote('destroy');
        }

        function getRowCount() {
            return $list.find('.matching-row').length;
        }

        function toggleMatchingLimit() {
            const rowCount = getRowCount();
            const isLimitReached = rowCount >= MAX_MATCHING;

            if (isLimitReached) {
                $btnAdd.hide();
                $limitHint.removeClass('d-none');
                return;
            }

            $btnAdd.show();
            $limitHint.addClass('d-none');
        }

        function buildMatchingRow(rowKey) {
            return `
        <div class="border rounded-4 p-4 mb-3 matching-row" data-row="${rowKey}">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-lg-5">
                    <label class="fw-semibold mb-2 d-block">Pernyataan</label>
                    <textarea
                        class="form-control js-matching-summernote"
                        data-side="left"
                        data-id=""
                        id="matching-left-${rowKey}"></textarea>
                    <div class="validation-message text-danger small mt-1" id="error-matching-left-${rowKey}"></div>
                </div>

                <div class="col-12 col-lg-1 d-flex justify-content-center align-items-center">
                    <i class="ti ti-arrow-right fs-10 text-muted"></i>
                </div>

                <div class="col-12 col-lg-5">
                    <label class="fw-semibold mb-2 d-block">Jawaban</label>
                    <textarea
                        class="form-control js-matching-summernote"
                        data-side="right"
                        data-id=""
                        id="matching-right-${rowKey}"></textarea>
                    <div class="validation-message text-danger small mt-1" id="error-matching-right-${rowKey}"></div>
                </div>

                <div class="col-12 col-lg-1 d-flex justify-content-center align-items-start">
                    <button type="button"
                        class="btn btn-sm p-0 border-0 bg-transparent js-remove-matching-row"
                        title="Hapus baris">
                        <svg width="30" height="30" viewBox="0 0 40 40" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M31.6615 6.66667H25.8281L24.1615 5H15.8281L14.1615 6.66667H8.32812V10H31.6615M9.99479 31.6667C9.99479 32.5507 10.346 33.3986 10.9711 34.0237C11.5962 34.6488 12.4441 35 13.3281 35H26.6615C27.5455 35 28.3934 34.6488 29.0185 34.0237C29.6436 33.3986 29.9948 32.5507 29.9948 31.6667V11.6667H9.99479V31.6667Z"
                                fill="#FA896B"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;
        }

        function initExistingMatchingEditors() {
            $list.find('textarea.js-matching-summernote').each(function() {
                initMatchingSummernote($(this));
            });

            toggleMatchingLimit();
        }

        function validateMatchingUpdate() {
            let isValid = true;

            const scoreVal = $('#matching-score').val();
            const questionHtml = normalizeSummernote($('#summernote-matching-question').summernote('code'));

            if (!scoreVal) {
                $('#matching-score').addClass('is-invalid');
                $('#error-matching-score').text('Bobot soal wajib diisi.');
                isValid = false;
            }

            if (!questionHtml) {
                $('#summernote-matching-question').next('.note-editor').addClass('border border-danger');
                $('#error-matching-question').text('Pertanyaan utama wajib diisi.');
                isValid = false;
            }

            if (getRowCount() < MIN_MATCHING) {
                toastr.error('Minimal harus ada 1 pasangan.');
                return false;
            }

            $list.find('.matching-row').each(function() {
                const $row = $(this);
                const rowKey = $row.data('row');
                const $left = $row.find('textarea[data-side="left"]');
                const $right = $row.find('textarea[data-side="right"]');

                const leftHtml = normalizeSummernote($left.summernote('code'));
                const rightHtml = normalizeSummernote($right.summernote('code'));

                if (!leftHtml) {
                    $left.next('.note-editor').addClass('border border-danger');
                    $('#error-matching-left-' + rowKey).text('Pernyataan wajib diisi.');
                    isValid = false;
                }

                if (!rightHtml) {
                    $right.next('.note-editor').addClass('border border-danger');
                    $('#error-matching-right-' + rowKey).text('Jawaban wajib diisi.');
                    isValid = false;
                }
            });

            return isValid;
        }

        function buildMatchingUpdatePayload() {
            const fd = new FormData();

            fd.append('_method', 'PUT');
            fd.append('score', $('#matching-score').val() ?? '');
            fd.append('question', normalizeSummernote($('#summernote-matching-question').summernote('code')));

            $list.find('.matching-row').each(function(pairIndex) {
                const $row = $(this);
                const $left = $row.find('textarea[data-side="left"]');
                const $right = $row.find('textarea[data-side="right"]');

                const leftId = $left.data('id') || '';
                const rightId = $right.data('id') || '';

                fd.append(`questions[${pairIndex}][0][id]`, leftId);
                fd.append(`questions[${pairIndex}][0][item_text]`, normalizeSummernote($left.summernote(
                    'code')));
                fd.append(`questions[${pairIndex}][1][id]`, rightId);
                fd.append(`questions[${pairIndex}][1][item_text]`, normalizeSummernote($right
                    .summernote('code')));
            });

            if (deletedIds.length > 0) {
                [...new Set(deletedIds)].forEach(function(id, index) {
                    fd.append(`delete[${index}]`, id);
                });
            }

            return fd;
        }

        function applyMatchingUpdateErrors(errors) {
            let firstElement = null;

            if (errors.score) {
                $('#matching-score').addClass('is-invalid');
                $('#error-matching-score').text(errors.score[0] ?? errors.score);
                firstElement ??= $('#matching-score');
            }

            if (errors.question) {
                $('#summernote-matching-question').next('.note-editor').addClass('border border-danger');
                $('#error-matching-question').text(errors.question[0] ?? errors.question);
                firstElement ??= $('#summernote-matching-question');
            }

            if (errors.questions) {
                toastr.error(errors.questions[0] ?? errors.questions);
            }

            if (errors.delete) {
                toastr.error(errors.delete[0] ?? errors.delete);
            }

            Object.keys(errors).forEach(function(field) {
                const itemTextMatch = field.match(/^questions\.(\d+)\.(\d+)\.item_text$/);
                const idMatch = field.match(/^questions\.(\d+)\.(\d+)\.id$/);

                if (itemTextMatch) {
                    const pairIdx = parseInt(itemTextMatch[1], 10);
                    const sideIdx = parseInt(itemTextMatch[2], 10);
                    const side = sideIdx === 0 ? 'left' : 'right';

                    const $row = $list.find('.matching-row').eq(pairIdx);
                    const rowKey = $row.data('row');
                    const $textarea = $row.find(`textarea[data-side="${side}"]`);
                    const message = errors[field][0] ?? errors[field];

                    $textarea.next('.note-editor').addClass('border border-danger');
                    $('#error-matching-' + side + '-' + rowKey).text(message);

                    firstElement ??= $textarea;
                }

                if (idMatch) {
                    toastr.error(errors[field][0] ?? errors[field]);
                }

                if (field.startsWith('delete.')) {
                    toastr.error(errors[field][0] ?? errors[field]);
                }
            });

            if (firstElement && firstElement.length) {
                $('html, body').animate({
                    scrollTop: firstElement.offset().top - 120
                }, 400);
            }
        }

        $btnAdd.on('click', function() {
            if (getRowCount() >= MAX_MATCHING) {
                toastr.warning(`Maksimal ${MAX_MATCHING} pasangan penjodohan.`);
                return;
            }

            const rowKey = Date.now();
            $list.append(buildMatchingRow(rowKey));

            $list.find('.matching-row').last().find('textarea.js-matching-summernote').each(function() {
                initMatchingSummernote($(this));
            });

            toggleMatchingLimit();
        });

        $list.on('click', '.js-remove-matching-row', function() {
            if (getRowCount() <= MIN_MATCHING) {
                toastr.error(`Minimal harus ada ${MIN_MATCHING} pasangan.`);
                return;
            }

            const $row = $(this).closest('.matching-row');
            const $left = $row.find('textarea[data-side="left"]');
            const $right = $row.find('textarea[data-side="right"]');

            const leftId = $left.data('id');
            const rightId = $right.data('id');

            if (leftId) {
                deletedIds.push(leftId);
            }

            if (rightId) {
                deletedIds.push(rightId);
            }

            $row.find('textarea.js-matching-summernote').each(function() {
                destroyMatchingSummernote($(this));
            });

            $row.remove();
            toggleMatchingLimit();
        });

        function submitUpdateMatching() {
            clearValidationUI();

            if (!validateMatchingUpdate()) {
                return;
            }

            const origTxt = $('#btn-update-question').html();
            setLoading(true, origTxt);

            $.ajax({
                type: 'POST',
                url: "{{ route('api.teacher.exam-question.matching.update', $examQuestion->id) }}",
                data: buildMatchingUpdatePayload(),
                processData: false,
                contentType: false,
                headers: {
                    'Authorization': 'Bearer {{ session('api_bearer') }}',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(res) {
                    setLoading(false, origTxt);
                    deletedIds = [];

                    Swal.fire({
                        title: 'Sukses',
                        text: res.meta?.message || res.message ||
                            'Berhasil memperbarui soal penjodohan.',
                        icon: 'success'
                    }).then(() => {
                        window.location.href = redirectUrl;
                    });
                },
                error: function(xhr) {
                    setLoading(false, origTxt);
                    clearValidationUI();

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON?.errors || {};
                        applyMatchingUpdateErrors(errors);
                        return;
                    }

                    toastr.error(xhr.responseJSON?.message ||
                        'Ada kesalahan saat memperbarui data.');
                }
            });
        }

        initExistingMatchingEditors();

    });
</script>
