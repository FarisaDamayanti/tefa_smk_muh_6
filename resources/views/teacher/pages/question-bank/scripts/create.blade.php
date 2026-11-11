<script>
    $(function() {

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
            $('#error-matching-question').text('');
            $('#error-score').text('');
        }

        function setLoading(button, secondButton, isLoading, originalText) {
            if (isLoading) {
                button.html(
                        '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Loading...')
                    .prop('disabled', true);
                secondButton.prop('disabled', true);
            } else {
                button.html(originalText).prop('disabled', false);
                secondButton.prop('disabled', false);
            }
        }


        // =========================================
        // SUBMIT HANDLER
        // =========================================
        let submitAction = 'save';
        let activeButton = null;

        const $btnSave = $('#btn-create-question');
        const $btnSaveAnother = $('#btn-create-another-question');
        const $type = $('#question-type');

        function toggleBobot(type) {
            if (type === 'multiple_choice' || type === 'single_choice') {
                $('#bobot-wrapper').addClass('d-none');
            } else {
                $('#bobot-wrapper').removeClass('d-none');
            }
        }

        toggleBobot($type.val());

        $type.on('change', function() {
            toggleBobot($(this).val());
        });

        $btnSave.on('click', function(e) {
            e.preventDefault();
            submitAction = 'save';
            activeButton = $(this);
            dispatchSubmit();
        });

        $btnSaveAnother.on('click', function(e) {
            e.preventDefault();
            submitAction = 'save_add';
            activeButton = $(this);
            dispatchSubmit();
        });

        $('#form-create-question').on('submit', function(e) {
            e.preventDefault();
        });

        function dispatchSubmit() {
            clearValidationUI();
            const type = $type.val();

            if (type === 'multiple_choice' || type === 'single_choice') submitMultipleChoice();
            else if (type === 'essay') submitEssay();
            else if (type === 'matching') submitMatching();
        }

        function getRedirectUrl() {
            const detailUrl = "{{ route('teacher.question-bank.detail', $questionBank->id) }}";
            const createUrl = "{{ route('teacher.question-bank.create', $questionBank->id) }}";
            return submitAction === 'save_add' ? createUrl : detailUrl;
        }


        // =========================================
        // MULTIPLE CHOICE
        // =========================================
        function buildMultipleChoicePayload() {
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

            fd.append('question', questionHtml);
            ['option_a', 'option_b', 'option_c', 'option_d', 'option_e'].forEach((key, idx) => {
                fd.append(`options[${idx}][option_label]`, key);
                fd.append(`options[${idx}][option_text]`, optionHtml[key] ?? '');
                fd.append(`options[${idx}][is_correct]`, checkedKeys.includes(key) ? 1 : 0);
            });

            return fd;
        }

        function submitMultipleChoice() {
            if (!$('input[name="answer[]"]:checked').length) {
                $('#error-answer').html(
                    '<i class="ti ti-alert-triangle-filled" style="color:#FF0000;"></i> Jawaban wajib dipilih.'
                );
                return;
            }

            const $btn = activeButton;
            const $other = $btn.is($btnSave) ? $btnSaveAnother : $btnSave;
            const origTxt = $btn.html();

            setLoading($btn, $other, true, origTxt);

            $.ajax({
                type: 'POST',
                url: "{{ route('api.teacher.exam-question.create', $questionBank->id) }}",
                data: buildMultipleChoicePayload(),
                processData: false,
                contentType: false,
                headers: {
                    'Authorization': 'Bearer {{ session('api_bearer') }}',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(res) {
                    setLoading($btn, $other, false, origTxt);
                    Swal.fire({
                            title: 'Sukses',
                            text: res.meta?.message || 'Berhasil menambah data.',
                            icon: 'success'
                        })
                        .then(() => window.location.href = getRedirectUrl());
                },
                error: function(xhr) {
                    setLoading($btn, $other, false, origTxt);
                    clearValidationUI();

                    const errors = xhr.responseJSON?.data || xhr.responseJSON?.errors || {};

                    if (xhr.status === 422) {
                        let firstEl = null;

                        if (errors.question) {
                            $('#summernote-question').next('.note-editor').addClass(
                                'border border-danger');
                            $('#error-question').text(errors.question[0] ?? errors.question);
                            firstEl ??= $('#summernote-question');
                        }

                        if (errors.options) {
                            $('#error-answer').html(
                                '<i class="ti ti-alert-triangle-filled" style="color:#FF0000;"></i> ' +
                                (errors.options[0] ?? errors.options));
                            firstEl ??= $('input[name="answer[]"]').first();
                        }

                        Object.keys(errors).forEach(field => {
                            const m = field.match(/^options\.(\d+)\.option_text$/);
                            if (!m) return;
                            const suffix = ['a', 'b', 'c', 'd', 'e'][parseInt(m[1], 10)];
                            if (!suffix) return;
                            $('#summernote-answer-' + suffix).next('.note-editor').addClass(
                                'border border-danger');
                            $('#error-option_' + suffix).text(errors[field][0] ?? errors[
                                field]);
                            firstEl ??= $('#summernote-answer-' + suffix);
                        });

                        if (firstEl) $('html, body').animate({
                            scrollTop: firstEl.offset().top - 120
                        }, 400);

                    } else if (xhr.status === 403) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Batas Jumlah Soal Tercapai',
                            text: 'Batas maksimal untuk bank soal ini adalah {{ $questionBank->question_limit }} soal.',
                            confirmButtonColor: '#5D87FF',
                        });
                    } else {
                        toastr.error(xhr.responseJSON?.message ||
                            'Ada kesalahan saat menambah data.');
                    }
                }
            });
        }


        // =========================================
        // ESSAY
        // =========================================
        function submitEssay() {
            const question = normalizeSummernote($('#summernote-essay-question').summernote('code'));
            const score = $('input[name="score"]').val();

            if (!question) {
                $('#error-essay-question').text('Soal uraian wajib diisi.');
                $('#summernote-essay-question').next('.note-editor').addClass('border border-danger');
                return;
            }

            if (!score) {
                $('#error-score').text('Bobot soal diperlukan.');
                $('#question-score').addClass('is-invalid');
                return;
            }

            const $btn = activeButton;
            const $other = $btn.is($btnSave) ? $btnSaveAnother : $btnSave;
            const origTxt = $btn.html();

            setLoading($btn, $other, true, origTxt);

            $.ajax({
                type: 'POST',
                url: "{{ route('api.teacher.exam-question.essay.store', $questionBank->id) }}",
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
                    setLoading($btn, $other, false, origTxt);

                    if (res.success) {
                        toastr.success(res.message || 'Berhasil menambah soal esai.');

                        setTimeout(() => {
                            if (submitAction === 'save_add') {
                                window.location.reload();
                            } else {
                                window.location.href =
                                    "{{ route('teacher.question-bank.detail', $questionBank->id) }}";
                            }
                        }, 800);
                    } else {
                        toastr.error(res.message || 'Gagal menambah soal esai.');
                    }
                },
                error: function(xhr) {
                    setLoading($btn, $other, false, origTxt);
                    clearValidationUI();

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON?.errors || {};

                        if (errors.question) {
                            $('#error-essay-question').text(errors.question[0]);
                            $('#summernote-essay-question').next('.note-editor').addClass(
                                'border border-danger');
                        }

                        if (errors.score) {
                            $('#error-score').text(errors.score[0]);
                            $('#question-score').addClass('is-invalid');
                        }

                        return;
                    }

                    toastr.error(xhr.responseJSON?.message || 'Terjadi kesalahan, coba lagi.');
                }
            });
        }

        // =========================================
        // MATCHING
        // =========================================
        const MAX_MATCHING = 4;
        const MIN_MATCHING = 1;
        const $list = $('#matching-list');
        const $btnAdd = $('#btn-add-matching-row');
        const $limitHint = $('#matching-limit-hint');

        function isSummernoteReady() {
            return typeof $.fn.summernote === 'function';
        }

        function getMatchingRowCount() {
            return $list.find('.matching-row').length;
        }

        function toggleMatchingLimit() {
            const rowCount = getMatchingRowCount();
            const shouldHide = rowCount >= MAX_MATCHING;

            if (shouldHide) {
                $btnAdd.hide();
                $limitHint.show();
            } else {
                $btnAdd.show();
                $limitHint.hide();
            }
        }

        function initMatchingSummernote($el) {
            if (!isSummernoteReady() || $el.next('.note-editor').length) return;
            $el.summernote({
                height: 200,
            });
        }

        function destroyMatchingSummernote($el) {
            if (!isSummernoteReady()) return;
            if ($el.next('.note-editor').length) $el.summernote('destroy');
        }

        function buildMatchingRow(index) {
            return `
                <div class="matching-row border rounded-4 p-4 mb-3" data-index="${index}">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-lg-5">
                            <label class="fw-semibold text-dark mb-2">Pertanyaan</label>
                            <textarea
                                class="form-control js-matching-summernote"
                                name="matching_left[]"
                                id="matching-left-${index}">
                            </textarea>
                            <div class="validation-message text-danger small mt-1"
                                id="error-matching-left-${index}"></div>
                        </div>
                        <div class="col-12 col-lg-1 d-flex justify-content-center align-items-center">
                            <i class="ti ti-arrow-right fs-10 text-muted"></i>
                        </div>
                        <div class="col-12 col-lg-5">
                            <label class="fw-semibold text-dark mb-2">Jawaban</label>
                            <textarea
                                class="form-control js-matching-summernote"
                                name="matching_right[]"
                                id="matching-right-${index}">
                            </textarea>
                            <div class="validation-message text-danger small mt-1"
                                id="error-matching-right-${index}"></div>
                        </div>
                        <div class="col-12 col-lg-1 d-flex justify-content-center align-items-start">
                            <button type="button"
                                    class="btn btn-sm p-0 border-0 bg-transparent js-remove-matching-row">
                                <svg width="30" height="30" viewBox="0 0 40 40" fill="none">
                                    <path d="M31.6615 6.66667H25.8281L24.1615 5H15.8281L14.1615 6.66667H8.32812V10H31.6615M9.99479 31.6667C9.99479 32.5507 10.346 33.3986 10.9711 34.0237C11.5962 34.6488 12.4441 35 13.3281 35H26.6615C27.5455 35 28.3934 34.6488 29.0185 34.0237C29.6436 33.3986 29.9948 32.5507 29.9948 31.6667V11.6667H9.99479V31.6667Z"
                                        fill="#FA896B"/>
                                </svg>
                            </button>
                        </div>

                    </div>
                </div>
            `;
        }

        function addMatchingRow() {
            if (getMatchingRowCount() >= MAX_MATCHING) {
                if (typeof toastr !== 'undefined') {
                    toastr.warning(`Maksimal ${MAX_MATCHING} pasangan penjodohan.`);
                }
                return;
            }

            const index = Date.now();
            $list.append(buildMatchingRow(index));

            $list.find('.matching-row').last().find('.js-matching-summernote').each(function() {
                initMatchingSummernote($(this));
            });

            toggleMatchingLimit();
        }

        $btnAdd.on('click', function() {
            addMatchingRow();
        });

        $list.on('click', '.js-remove-matching-row', function() {
            if (getMatchingRowCount() <= MIN_MATCHING) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Minimal harus ada 1 pasangan penjodohan.');
                }
                return;
            }

            const $row = $(this).closest('.matching-row');

            $row.find('.js-matching-summernote').each(function() {
                destroyMatchingSummernote($(this));
            });

            $row.remove();
            toggleMatchingLimit();
        });

        $type.on('change', function() {
            if ($(this).val() === 'matching') {
                ensureDefaultRows();
            }
        });


        if ($type.val() === 'matching') {
            setTimeout(function() {
                ensureDefaultRows();
            }, 100);
        }

        function ensureDefaultRows() {

            if (getMatchingRowCount() === 0) {
                addMatchingRow();
            }

            toggleMatchingLimit();
        }

        if ($type.val() === 'matching') {
            ensureDefaultRows();
        }

        // =========================================
        // MATCHING SUBMIT
        // =========================================
        function buildMatchingPayload() {
            const fd = new FormData();

            const scoreVal = $('#question-score').val();
            fd.append('score', scoreVal ?? '');

            const questionHtml = normalizeSummernote(
                $('#question-type').val() === 'matching' ?
                $('#summernote-matching-question').summernote('code') :
                $('#summernote-question').summernote('code')
            );
            fd.append('question', questionHtml);

            $list.find('.matching-row').each(function(i) {
                const $tas = $(this).find('textarea.js-matching-summernote');
                const leftHtml = normalizeSummernote($($tas.get(0)).summernote('code'));
                const rightHtml = normalizeSummernote($($tas.get(1)).summernote('code'));

                fd.append(`questions[${i}][0]`, leftHtml);
                fd.append(`questions[${i}][1]`, rightHtml);
            });

            return fd;
        }

        function validateMatchingForm() {

            let ok = true;

            const questionHtml = normalizeSummernote($('#summernote-matching-question').summernote('code'));
            const scoreVal = $('#question-score').val();

            if (!questionHtml) {
                $('#error-matching-question').text('Soal penjodohan wajib diisi.');
                $('#summernote-matching-question').next('.note-editor').addClass('border border-danger');
                ok = false;
            }

            if (!scoreVal) {
                $('#error-score').text('Bobot soal wajib diisi.');
                $('#question-score').addClass('is-invalid');
                ok = false;
            }

            const $rows = $list.find('.matching-row');

            if ($rows.length === 0) {
                toastr.error('Minimal harus ada 1 pasangan penjodohan.');
                return false;
            }

            $rows.each(function() {

                const $tas = $(this).find('textarea.js-matching-summernote');

                const leftHtml = normalizeSummernote($($tas.get(0)).summernote('code'));
                const rightHtml = normalizeSummernote($($tas.get(1)).summernote('code'));
                
                const index = $(this).data('index');

                if (!leftHtml) {
                    $($tas.get(0)).next('.note-editor').addClass('border border-danger');
                    $(`#error-matching-left-${index}`).text('Pertanyaan wajib diisi.');
                    ok = false;
                }

                if (!rightHtml) {
                    $($tas.get(1)).next('.note-editor').addClass('border border-danger');
                    $(`#error-matching-right-${index}`).text('Jawaban wajib diisi.');
                    ok = false;
                }

            });

            return ok;
        }



        function submitMatching() {

            clearValidationUI();

            if (!validateMatchingForm()) {
                return;
            }

            const $btn = activeButton;
            const $other = $btn.is($btnSave) ? $btnSaveAnother : $btnSave;
            const origTxt = $btn.html();

            setLoading($btn, $other, true, origTxt);

            $.ajax({
                type: 'POST',
                url: "{{ route('api.teacher.exam-question.matching.store', $questionBank->id) }}",
                data: buildMatchingPayload(),
                processData: false,
                contentType: false,
                headers: {
                    'Authorization': 'Bearer {{ session('api_bearer') }}',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(res) {
                    setLoading($btn, $other, false, origTxt);
                    Swal.fire({
                            title: 'Sukses',
                            text: res.meta?.message || 'Berhasil menambah data.',
                            icon: 'success'
                        })
                        .then(() => window.location.href = getRedirectUrl());
                },
                error: function(xhr) {
                    setLoading($btn, $other, false, origTxt);
                    clearValidationUI();

                    const errors = xhr.responseJSON?.data || xhr.responseJSON?.errors || {};

                    if (xhr.status === 422) {
                        if (errors.score) {
                            $('#error-score').text(errors.score[0] ?? errors.score);
                            $('#question-score').addClass('is-invalid');
                        }

                        if (errors.question) {
                            $('#error-matching-question').text(errors.question[0] ?? errors.question);
                            $('#summernote-matching-question').next('.note-editor').addClass('border border-danger');
                        }

                        Object.keys(errors).forEach(field => {
                            const m = field.match(/^questions\.(\d+)\.(\d+)$/);
                            if (!m) return;

                            const rowIdx = parseInt(m[1], 10);
                            const sideIdx = parseInt(m[2], 10);
                            const $row = $list.find('.matching-row').eq(rowIdx);
                            const $ta = $row.find('textarea.js-matching-summernote').eq(
                                sideIdx);
                            $ta.next('.note-editor').addClass('border border-danger');
                            
                            const index = $row.data('index');
                            const side = sideIdx === 0 ? 'left' : 'right';
                            $(`#error-matching-${side}-${index}`).text(errors[field][0] ?? errors[field]);
                        });

                    } else if (xhr.status === 403) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Batas Jumlah Soal Tercapai',
                            text: 'Batas maksimal untuk bank soal ini adalah {{ $questionBank->question_limit }} soal.',
                            confirmButtonColor: '#5D87FF',
                        });
                    } else {
                        toastr.error(xhr.responseJSON?.message ||
                            'Ada kesalahan saat menambah data.');
                    }
                }
            });
        }

    });
</script>
