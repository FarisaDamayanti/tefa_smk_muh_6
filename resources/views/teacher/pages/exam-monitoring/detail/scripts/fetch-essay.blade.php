<script>
    const style = document.createElement('style');
    style.textContent = `
        #essay-question-list img {
            max-width: 100% !important;
            height: auto !important;
            display: block;
            border-radius: 6px;
            object-fit: contain;
        }

        #essay-question-list .answer-content {
            overflow: hidden;
            word-break: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
        }

        #essay-question-list .answer-content * {
            max-width: 100% !important;
            height: auto !important;
        }

        #essay-question-list .answer-content table {
            width: 100% !important;
            table-layout: fixed;
        }

        @media (max-width: 768px) {
            #essay-question-list img {
                width: 100% !important;
            }

            #essay-question-list .answer-content iframe,
            #essay-question-list .answer-content video {
                width: 100% !important;
                height: auto !important;
            }
        }
    `;
    document.head.appendChild(style);
    // ==========================================

    const csrfToken = @json(csrf_token());
    $(document).ready(function() {

        const firstStudent = $('.js-select-student[data-student-exam-id!=""]').first();
        if (firstStudent.length) {
            setActiveStudent(firstStudent);
            fetchEssay(firstStudent.data('student-exam-id'), firstStudent.data('student-name'));
        }

        $(document).on('click', '.js-select-student', function() {
            const studentExamId = $(this).data('student-exam-id');
            const studentName = $(this).data('student-name');

            if (!studentExamId) {
                toastr.warning('Siswa ini belum mengerjakan ujian');
                return;
            }

            setActiveStudent($(this));
            fetchEssay(studentExamId, studentName);
        });

        $(document).on('click', '.js-save-score', function() {
            const btn = $(this);
            const questionId = btn.data('question-id');
            const studentExamId = btn.data('student-exam-id');
            const scoreInput = $(`.js-score-input[data-question-id="${questionId}"]`);
            const score = scoreInput.val();
            const max = parseFloat(scoreInput.attr('max'));

            if (score === '' || score === null) {
                toastr.warning('Masukkan nilai terlebih dahulu');
                return;
            }
            if (parseFloat(score) < 0) {
                toastr.warning('Nilai tidak boleh kurang dari 0');
                return;
            }
            if (parseFloat(score) > max) {
                toastr.warning(`Nilai tidak boleh melebihi ${max}`);
                return;
            }

            btn.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan...
            `);

            $.ajax({
                url: `/api/teacher/exam-question/essay/${questionId}/grading?_method=PUT`,
                method: 'POST',
                dataType: 'json',
                headers: {
                    Authorization: "Bearer {{ session('api_bearer') }}",
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                data: {
                    score
                },
                success: function(res) {
                    toastr.success('Nilai berhasil disimpan!');
                    $('#info-after-grading').removeClass('d-none').addClass('d-inline')
                },
                error: function(xhr) {
                    const message = xhr?.responseJSON?.meta?.message ??
                        'Gagal menyimpan nilai';
                    toastr.error(message);
                    btn.prop('disabled', false).text('Simpan');
                },
                complete: function() {
                    btn.prop('disabled', false).html(`Simpan`);
                }
            });
        });

        $('#student-search').on('input', function() {
            const query = $(this).val().toLowerCase();
            $('.js-select-student').each(function() {
                const name = $(this).data('student-name')?.toLowerCase() ?? '';
                $(this).toggle(name.includes(query));
            });
        });

    });

    function setActiveStudent(el) {
        $('.js-select-student').removeClass('is-active');
        el.addClass('is-active');
    }

    function fetchEssay(studentExamId, studentName) {
        const container = $('#essay-question-list');

        $('#active-student-name').text(studentName ?? '');

        container.html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <div class="mt-3 text-muted">Memuat jawaban uraian...</div>
            </div>
        `);

        $.ajax({
            url: `{{ route('api.teacher.exam-question.show', '') }}/${studentExamId}`,
            method: 'GET',
            dataType: 'json',
            headers: {
                Authorization: "Bearer {{ session('api_bearer') }}"
            },
            success: function(res) {
                const items = res?.data ?? [];

                if (!items.length) {
                    container.html(renderEmpty('Soal uraian tidak tersedia'));
                    return;
                }

                let html = '';
                $.each(items, function(index, item) {
                    const no = index + 1;
                    const isScored = String(item.status ?? '').toLowerCase() === 'sudah dinilai';
                    const maxScore = item.max_score ?? 0;
                    const scoreVal = item.score ?? '';

                    const statusBadge = isScored ?
                        `<span class="badge bg-light-success text-success">Sudah Dinilai</span>` :
                        `<span class="badge bg-light-danger text-danger">Belum Dinilai</span>`;

                    html += `
                    <div class="mb-3">
                        <div class="bg-white rounded-3 py-2 px-3 px-md-4 py-md-3 d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex gap-2">
                                <div class="fw-semibold" style="min-width:22px;">${no}.</div>
                                <div class="fw-semibold">${item.question ?? '-'}
                                       
                                </div>
                            </div>
                            ${statusBadge}
                        </div>

                        <div class="bg-white rounded-3 p-2 p-md-4">
                            <div class="row g-3">
                                <div class="col-12 col-lg-8">
                                    <!-- [PERBAIKAN] Tambahkan class answer-content dan style overflow:hidden -->
                                    <div class="border rounded-3 p-3 h-100" style="overflow:hidden;">
                                        <div class="text-muted small mb-2">Jawaban Siswa</div>
                                        <div class="answer-content">${item.answer ?? '-'}</div>
                                    </div>
                                    <!-- [PERBAIKAN] end -->
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="bg-light-primary rounded-3 p-3 h-100">
                                        <div class="fw-semibold mb-2">Berikan Penilaian</div>
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <input type="number"
                                                min="0"
                                                max="${maxScore}"
                                                class="form-control js-score-input"
                                                placeholder="0"
                                                value="${scoreVal}"
                                                data-question-id="${item.id}">
                                            <span class="fw-semibold text-muted">/</span>
                                            <input type="text" class="form-control" value="${maxScore}" disabled>
                                        </div>
                                        <button type="button"
                                            class="btn btn-primary w-100 js-save-score"
                                            data-question-id="${item.id}"
                                            data-student-exam-id="${studentExamId}">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                });

                container.html(html);
            },
            error: function(xhr) {
                const message = xhr?.responseJSON?.meta?.message ?? 'Gagal mengambil data';
                toastr.error(message);
                container.html(renderEmpty(message));
            }
        });
    }

    function renderEmpty(text) {
        return `
        <div class="text-center py-5">
            <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" width="220" alt="No data">
            <p class="mt-3 mb-0">${escapeHtml(text)}</p>
        </div>`;
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
</script>