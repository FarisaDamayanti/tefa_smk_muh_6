<script>
    const STORAGE_KEY_SELECTED_QUESTIONS = 'selected_questions_{{ $questionBank->id }}';

    function getSelectedIds() {
        return (JSON.parse(localStorage.getItem(STORAGE_KEY_SELECTED_QUESTIONS)) || [])
            .map(id => id.toString());
    }

    function saveSelectedIds(ids) {
        localStorage.setItem(STORAGE_KEY_SELECTED_QUESTIONS, JSON.stringify(
            ids.map(id => id.toString())
        ));
    }

    const allQuestions = @json($questions);

    function getType(q) {
        const raw = (q.type ?? q.question_type ?? q.tipe ?? '').toString().toLowerCase().trim();
        if (raw.includes('essay') || raw.includes('esai') || raw === 'uraian') return 'essay';
        if (raw.includes('match') || raw.includes('jodoh')) return 'matching';
        return 'multiple_choice';
    }

    function updateCounter() {
        $('#total-selected-questions').text(getSelectedIds().length);
    }

    function renderSelectedQuestions() {
        const selectedIds = getSelectedIds().map(id => id.toString());
        updateCounter();

        const filtered = allQuestions.filter(q => selectedIds.includes(q.id.toString()));

        const essays = filtered.filter(q => getType(q) === 'essay');
        const matching = filtered.filter(q => getType(q) === 'matching');
        const mc = filtered.filter(q => getType(q) === 'multiple_choice');

        renderEssay(essays);
        renderMultipleChoice(mc);
        renderMatching(matching);

        const isEmpty = selectedIds.length === 0;
        $('#empty-state').toggleClass('d-none', !isEmpty);
        $('#save-selected-questions').prop('disabled', isEmpty);
    }

    function renderEssay(questions) {
        const container = $('#essay-list');
        container.empty();

        questions.forEach(q => {
            container.append(`
                <div class="rounded-4 p-3 shadow-sm bg-light-primary question-item" data-id="${q.id}">
                    <p class="text-muted small fst-italic mb-2">
                        ${q.teacher_name} menambahkan soal esai ini
                    </p>
                    <div class="bg-white rounded-4 px-3 py-3 d-flex align-items-center gap-2">
                        <button type="button" class="btn-remove-question" data-id="${q.id}" title="Hapus soal">
                            <i class="ti ti-minus"></i>
                        </button>
                        <div class="fw-semibold text-dark">${q.question}</div>
                    </div>
                </div>
            `);
        });
    }

    function renderMultipleChoice(questions) {
        const container = $('#mc-list');
        container.empty();

        questions.forEach((q, index) => {
            const i = index + 1;
            const rawType = (q.type ?? '').toLowerCase();
            const typeLabel = rawType === 'single_choice' ? 'pilihan ganda (1 jawaban)' : 'pilihan ganda';

            let optHtml = '';
            const options = q.sort_exam_question_options ?? q.sortExamQuestionOptions ?? null;

            if (options && options.length > 0) {
                optHtml = options.map((opt, idx) => {
                    const cls = opt.is_correct ?
                        'bg-light-success border border-1 border-success' :
                        'bg-light-primary';
                    return `
                        <div class="mb-3 p-3 rounded-4 ${cls} d-flex gap-2 align-items-start">
                            <span class="fw-semibold">${String.fromCharCode(65 + idx)}.</span>
                            <span>${opt.option_text ?? ''}</span>
                        </div>`;
                }).join('');
            } else {
                optHtml = ['a', 'b', 'c', 'd', 'e'].map(letter => {
                    const val = q[`option_${letter}`] ?? '';
                    if (!val) return '';
                    const active = q.answer === `option_${letter}`;
                    const cls = active ?
                        'bg-light-success border border-1 border-success' :
                        'bg-light-primary';
                    return `
                        <div class="mb-3 p-3 rounded-4 ${cls} d-flex gap-2 align-items-start">
                            <span class="fw-semibold">${letter.toUpperCase()}.</span>
                            <span>${val}</span>
                        </div>`;
                }).join('');
            }

            container.append(`
    <div class="accordion-item card border border-1 bg-light-primary rounded-4 p-3 mb-4 question-item"
         data-id="${q.id}">
        <p class="m-0 text-capitalize mb-3 fst-italic text-muted small">
            ${q.teacher_name} menambahkan soal ${typeLabel} ini
        </p>
        <div class="p-0 rounded-4 bg-white text-dark accordion-header d-flex align-items-center gap-2 px-3"
             id="heading-mc-${i}">
            <button type="button" class="btn-remove-question flex-shrink-0" data-id="${q.id}" title="Hapus soal">
                <i class="ti ti-minus"></i>
            </button>
            <button class="accordion-button px-0 ${i !== 1 ? 'collapsed' : ''}"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapse-mc-${i}"
                    aria-expanded="${i === 1 ? 'true' : 'false'}"
                    aria-controls="collapse-mc-${i}">
                <div class="fw-semibold question-text">${q.question}</div>
            </button>
        </div>
        <div class="py-1 rounded-4 bg-white text-dark mt-3 accordion-collapse collapse ${i === 1 ? 'show' : ''}"
             id="collapse-mc-${i}"
             aria-labelledby="heading-mc-${i}"
             data-bs-parent="#mc-list">
            <div class="accordion-body px-3">${optHtml}</div>
        </div>
    </div>
`);
        });
    }

    function renderMatching(questions) {
        const container = $('#matching-list');
        container.empty();

        questions.forEach(q => {
            const leftItems = q.left_exam_question_matchings ?? q.leftExamQuestionMatchings ?? [];
            const rightItems = q.right_exam_question_matchings ?? q.rightExamQuestionMatchings ?? [];

            const orderedRightItems = leftItems.map(left => {
                const answer = left.left_item_answer ?? left.leftItemAnswer;
                if (!answer?.right_item_id) return null;
                return rightItems.find(r => r.id === answer.right_item_id) ?? null;
            }).filter(Boolean);

            const connections = leftItems.map((_, i) => ({
                from: `node-left-${q.id}-${i}`,
                to: `node-right-${q.id}-${i}`,
            }));

            const leftHtml = leftItems.map((left, i) => `
                <div class="mb-2">
                    <div class="position-relative rounded-3 p-2 d-flex align-items-center"
                         style="background:#eef3ff; min-height:70px;">
                        <div class="fw-bold text-dark me-1" style="min-width:16px; font-size:0.78rem;">${i + 1}.</div>
                        <div class="flex-grow-1" style="font-size:0.78rem; word-break:break-word;">${left.item_text ?? ''}</div>
                        <span class="position-absolute top-50 translate-middle-y js-anchor"
                              style="right:-6px;width:10px;height:10px;border-radius:999px;
                                     background:#5D87FF;box-shadow:0 0 0 3px rgba(93,135,255,.2);z-index:2;"
                              data-node="node-left-${q.id}-${i}"></span>
                    </div>
                </div>
            `).join('');

            const rightHtml = orderedRightItems.map((right, i) => `
                <div class="mb-2">
                    <div class="position-relative rounded-3 p-2 d-flex align-items-center"
                         style="background:#eef3ff; min-height:70px;">
                        <span class="position-absolute top-50 translate-middle-y js-anchor"
                              style="left:-6px;width:10px;height:10px;border-radius:999px;
                                     background:#5D87FF;box-shadow:0 0 0 3px rgba(93,135,255,.2);z-index:2;"
                              data-node="node-right-${q.id}-${i}"></span>
                        <div style="font-size:0.78rem; word-break:break-word; padding-left:6px;">${right.item_text ?? ''}</div>
                    </div>
                </div>
            `).join('');

            container.append(`
                <div class="bg-light-primary p-4 rounded-3 question-item" data-id="${q.id}">
                    <p class="m-0 text-capitalize mb-3 fst-italic text-muted small">
                        ${q.teacher_name} menambahkan soal penjodohan ini
                    </p>
                    <div class="rounded-4 bg-white px-3 py-2 d-flex align-items-center gap-2 mb-3">
                        <button type="button" class="btn-remove-question" data-id="${q.id}" title="Hapus soal">
                            <i class="ti ti-minus"></i>
                        </button>
                        <div class="fw-semibold text-dark">${q.question}</div>
                    </div>
                    <div class="bg-white p-3">
                        <div class="position-relative js-matching-board"
                             data-connections='${JSON.stringify(connections)}'>

                            <div class="row g-0 mb-2">
                                <div class="col-5 text-center fw-bold small">Pernyataan</div>
                                <div class="col-2"></div>
                                <div class="col-5 text-center fw-bold small">Jawaban</div>
                            </div>

                            <div class="row g-0">
                                <div class="col-5">${leftHtml}</div>
                                <div class="col-2"></div>
                                <div class="col-5">${rightHtml}</div>
                            </div>

                            <svg class="position-absolute top-0 start-0 w-100 h-100 js-matching-svg"
                                 style="pointer-events:none; z-index:1;" aria-hidden="true"></svg>
                        </div>
                    </div>
                </div>
            `);
        });

        setTimeout(drawMatchingLines, 100);
    }

    function drawMatchingLines() {
        document.querySelectorAll('.js-matching-board').forEach(board => {
            const svg = board.querySelector('.js-matching-svg');
            const connections = JSON.parse(board.dataset.connections || '[]');
            svg.innerHTML = '';

            connections.forEach(({
                from,
                to
            }) => {
                const fromEl = board.querySelector(`.js-anchor[data-node="${from}"]`);
                const toEl = board.querySelector(`.js-anchor[data-node="${to}"]`);
                if (!fromEl || !toEl) return;

                const boardRect = board.getBoundingClientRect();
                const fRect = fromEl.getBoundingClientRect();
                const tRect = toEl.getBoundingClientRect();

                const x1 = fRect.left + fRect.width / 2 - boardRect.left;
                const y1 = fRect.top + fRect.height / 2 - boardRect.top;
                const x2 = tRect.left + tRect.width / 2 - boardRect.left;
                const y2 = tRect.top + tRect.height / 2 - boardRect.top;
                const cx = (x1 + x2) / 2;

                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('d', `M${x1},${y1} C${cx},${y1} ${cx},${y2} ${x2},${y2}`);
                path.setAttribute('fill', 'none');
                path.setAttribute('stroke', '#5D87FF');
                path.setAttribute('stroke-width', '2');
                svg.appendChild(path);
            });
        });
    }

    $(document).on('click', '.btn-remove-question', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const id = $(this).data('id').toString();
        const ids = getSelectedIds().filter(qid => qid.toString() !== id);
        saveSelectedIds(ids);
        renderSelectedQuestions();
    });

    $('#save-selected-questions').on('click', function(e) {
        e.preventDefault();

        const currentActive = @json($questionBank->examQuestions()->where('is_active', 1)->count());
        const hasExam = @json($questionBank->exams()->where('exam_date', '>=', now())->exists());
        const newCount = getSelectedIds().length;

        if (hasExam) {
            if (newCount < currentActive) {
                $('#decrease-active-questions').modal('show');
                return;
            }
            if (newCount > currentActive) {
                $('#increase-active-questions').modal('show');
                return;
            }
        }

        const btn = $(this);
        setButtonLoading(btn, true);
        updateActiveQuestions(btn);
    });

    $('#submit-decrease-active-questions').on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        setButtonLoading(btn, true);
        updateActiveQuestions(btn);
    });

    $('#submit-increase-active-questions').on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        setButtonLoading(btn, true);
        updateActiveQuestions(btn);
    });

    function updateActiveQuestions(btn) {
        $.ajax({
            type: 'POST',
            url: "{{ route('api.school.activate-questions', $questionBank->id) }}?_method=PUT",
            data: {
                questions: getSelectedIds()
            },
            headers: {
                'Authorization': 'Bearer {{ session('api_bearer') }}',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                Swal.fire({
                    title: 'Sukses',
                    text: response.meta?.message ?? 'Berhasil menyimpan data.',
                    icon: 'success'
                }).then(() => {
                    window.location.href = "{{ route('school.question-bank.index') }}";
                });
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.data?.questions?.[0] ?? 'Ada kesalahan saat menyimpan data.';
                toastr.error(msg);
            },
            complete: function() {
                $('.modal.show').each(function() {
                    bootstrap.Modal.getOrCreateInstance(this).hide();
                });
                if (btn) setButtonLoading(btn, false);
            }
        });
    }

    function setButtonLoading(btn, isLoading) {
        if (!btn.data('original-html')) btn.data('original-html', btn.html());

        if (isLoading) {
            btn.prop('disabled', true).addClass('disabled');
            btn.html(`<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>` +
                btn.data('original-html'));
        } else {
            btn.prop('disabled', false).removeClass('disabled');
            btn.html(btn.data('original-html'));
        }
    }

    renderSelectedQuestions();
    $(window).on('resize', drawMatchingLines);
</script>
