<script>
    const QUESTIONS_OBJ = @json($data->questions);
    const QUESTIONS = Object.values(QUESTIONS_OBJ);
    const QUESTION_TOTAL = QUESTIONS.length;
    let currentQuestionIndex = 0;
    let button
</script>
<script>

    // ===============================
    // AUDIO
    // ===============================
    const audioCTX = new AudioContext();
    let audioBuffer;

    async function loadViolationAudio() {
        const res = await fetch("{{ asset('file/student-exam-violation.mp3') }}");
        const arrayBuffer = await res.arrayBuffer();
        audioBuffer = await audioCTX.decodeAudioData(arrayBuffer);
    }

    function playViolationSound() {
        const source = audioCTX.createBufferSource();
        source.buffer = audioBuffer;
        source.connect(audioCTX.destination);
        source.start();
    }

    // preload
    loadViolationAudio();

    // ===============================
    // RENDER QUESTION
    // ===============================
    function renderQuestion(index) {
        const data = QUESTIONS[index];

        $('#question-number').text((index + 1) + '.');
        $('#question-text').html(data.question);

        if (data.type === 'matching') {
            $('#btn-video-hint').removeClass('d-none');
        } else {
            $('#btn-video-hint').addClass('d-none');
        }

        let html = '';
        const optionLabels = {
            option_a: 'A',
            option_b: 'B',
            option_c: 'C',
            option_d: 'D',
            option_e: 'E'
        };

        if (data.type == 'matching') {

            let leftHtml = '';
            let rightHtml = '';

            // LEFT
            $.each(data.options.left, function(index, value) {
                leftHtml += `
                    <div class="mb-3">
                        <div class="position-relative rounded-4 p-3 js-node bg-light-primary" style="cursor:pointer;"
                            data-node="left-${value.id}">

                            <div class="d-flex gap-3 align-items-start">
                                <div class="fw-bold text-dark" style="min-width:22px;">
                                    ${index + 1}.
                                </div>
                                ${value.item_text}
                            </div>

                            <span class="position-absolute top-50 translate-middle-y js-anchor"
                                style="right:-8px;width:12px;height:12px;border-radius:999px;background:#5D87FF;"
                                data-node="left-${value.id}"></span>
                        </div>
                    </div>
                `;
            });

            // RIGHT
            $.each(data.options.right, function(index, value) {
                rightHtml += `
                    <div class="mb-3">
                        <div class="position-relative rounded-4 p-3 d-flex align-items-center js-node bg-light-primary" style="cursor:pointer;"
                            data-node="right-${value.id}">

                            <div class="fw-semibold text-dark">
                                ${String.fromCharCode(65 + index)}.
                                ${value.item_text}
                            </div>

                            <span class="position-absolute top-50 translate-middle-y js-anchor"
                                style="left:-8px;width:12px;height:12px;border-radius:999px;background:#5D87FF;"
                                data-node="right-${value.id}"></span>
                        </div>
                    </div>
                `;
            });

            html += `
                <div class="position-relative js-matching-board" data-connections='[]' style="padding: 6px 0;">
                    <div class="row g-4">

                        <div class="col-12 col-md-5">
                            ${leftHtml}
                        </div>

                        <div class="col-12 col-md-2 d-none d-md-block"></div>

                        <div class="col-12 col-md-5">
                            ${rightHtml}
                        </div>

                    </div>

                    <svg class="position-absolute top-0 start-0 w-100 h-100 js-matching-svg"
                        style="pointer-events:none;"></svg>
                </div>
            `;
        } else if (data.type == 'essay') {
            html += `
            <div class="mb-4">
                <label class="fw-bold text-dark mb-2">
                    Jawaban
                    <span class="text-danger">* </span>
                </label>
                <textarea name="answer" id="summernote-answer" class="essay-answer"></textarea>
                <div class="validation-message text-danger" id="error-answer"></div>
            </div>
            `;
        } else {
            if (data.type == 'multiple_choice') {
                html +=
                    '<p class="badge p-2 bg-light-warning text-warning fs-3 d-inline-flex align-items-center gap-1"><i class="ti ti-info-circle fs-6"></i>Jawaban lebih dari 1</p>'
            }
            Object.entries(data.options).forEach(([key, value]) => {
                html += `
                <label class="mb-3 p-3 rounded-4 bg-light-primary d-flex gap-2 option-item">
                    <input type="${data.type == 'multiple_choice' ? 'checkbox' : 'radio'}" name="answer" value="${value.id}" hidden data-question-id="${data.id}">
                    <p class="m-0 fw-bold">${optionLabels[key]}.</p>
                    <div>${value.text}</div>
                </label>
            `;
            });
        }

        $('#options-container').html(html);
        $('#question-number-bottom').text((index + 1));

        if (data.type == 'essay') {
            $('#summernote-answer').summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['table', ['table']],
                ],
                callbacks: {
                    onChange: function(contents) {

                        const question = QUESTIONS[currentQuestionIndex];

                        if (!contents.trim() || contents === '<p><br></p>') {
                            const answers = getAllAnswers();
                            delete answers[question.id];
                            localStorage.setItem(
                                STORAGE_KEY_EXAM_ANSWERS,
                                JSON.stringify(answers)
                            );
                            updateQuestionNav();
                            handleSubmitVisibility();
                            return;
                        }

                        saveQuestionAnswer({
                            id: question.id,
                            type: "essay",
                            answer_text: contents
                        });

                        updateQuestionNav();
                        handleSubmitVisibility();
                    }
                }
            });
        }

        if (index == 0) {
            $('#prev-btn').addClass('d-none');
            $('#next-btn').removeClass('d-none');
        } else if (index == QUESTION_TOTAL - 1) {
            $('#next-btn').addClass('d-none');
            $('#prev-btn').removeClass('d-none');
        } else {
            $('#prev-btn').removeClass('d-none');
            $('#next-btn').removeClass('d-none');
        }

        restoreSelectedAnswer();
        updateFlagCheckbox(data.id);
        updateQuestionNav();
    }

    function nextQuestion() {
        if (currentQuestionIndex < QUESTION_TOTAL - 1) {
            currentQuestionIndex++;
            renderQuestion(currentQuestionIndex);
        }
    }

    function prevQuestion() {
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            renderQuestion(currentQuestionIndex);
        }
    }


    // ===============================
    // TIMER
    // ===============================
    let remainingSeconds = {{ $data->remaining_time }};
    let remainingEndExam = {{ $data->remaining_exam_end_time }};

    function formatSecondsToTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secondsLeft = seconds % 60;

        return String(hours).padStart(2, '0') + ':' +
            String(minutes).padStart(2, '0') + ':' +
            String(secondsLeft).padStart(2, '0');
    }

    function startExamTimer() {
        const timerElement = $('#timer');
        timerElement.text(formatSecondsToTime(remainingSeconds));

        const interval = setInterval(() => {
            remainingSeconds--;
            remainingEndExam--;

            timerElement.text(formatSecondsToTime(remainingSeconds));
            handleSubmitVisibility();

            if (remainingEndExam == 0 && remainingSeconds > 300) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Waktu ujian sudah habis. Kami berikan waktu tambahan untuk menyelesaikan ujian selama 5 menit',
                    confirmButtonColor: '#5D87FF',
                });
                remainingSeconds = 300;
            }

            if (remainingSeconds <= 0) {
                clearInterval(interval);
                timerElement.text("00:00");

                $('#times-up-modal').modal('show');
                setTimeout(() => {
                    $('#times-up-modal').modal('hide');
                    submitExam();
                }, 1000);
            }
        }, 1000);
    }


    // ===============================
    // LOCAL STORAGE HELPERS
    // ===============================
    const STORAGE_KEY_EXAM_ANSWERS = 'exam_answers';
    const STORAGE_KEY_FLAGGED = 'exam_flagged';

    function getAllAnswers() {
        return JSON.parse(localStorage.getItem(STORAGE_KEY_EXAM_ANSWERS)) || {};
    }

    function saveAnswer(questionId, answer) {
        const answers = getAllAnswers();
        answers[questionId] = answer;
        localStorage.setItem(STORAGE_KEY_EXAM_ANSWERS, JSON.stringify(answers));
    }

    function saveQuestionAnswer(questionObj) {
        const answers = getAllAnswers();
        answers[questionObj.id] = questionObj;
        localStorage.setItem(STORAGE_KEY_EXAM_ANSWERS, JSON.stringify(answers));
    }

    function getFlagged() {
        return JSON.parse(localStorage.getItem(STORAGE_KEY_FLAGGED)) || {};
    }

    function toggleFlag(questionId) {
        const flagged = getFlagged();
        flagged[questionId] = !flagged[questionId];
        localStorage.setItem(STORAGE_KEY_FLAGGED, JSON.stringify(flagged));
        updateQuestionNav();
    }

    function updateFlagCheckbox(questionId) {
        const checkbox = $('#ragu-ragu');
        const flagged = getFlagged();
        checkbox.prop('checked', !!flagged[questionId]);
    }

    function clearAllAnswersAndFlag() {
        localStorage.removeItem(STORAGE_KEY_FLAGGED);
        localStorage.removeItem(STORAGE_KEY_EXAM_ANSWERS);
    }


    // ===============================
    // EXAM STATE
    // ===============================
    let examState = {
        isFinished: false
    };


    // ===============================
    // SUBMIT VISIBILITY
    // ===============================
    function handleSubmitVisibility() {
        const answers = getAllAnswers();
        const answeredCount = Object.keys(answers).length;

        if ({{ $exam->allow_early_submission }} == false) {
            if (remainingSeconds <= 300 && answeredCount === QUESTION_TOTAL) {
                $('#submit-exam').show();
            } else {
                $('#submit-exam').hide();
            }
        } else {
            if (answeredCount === QUESTION_TOTAL) {
                $('#submit-exam').show();
            } else {
                $('#submit-exam').hide();
            }
        }
    }

    // ===============================
    // NAV STATUS
    // ===============================
    function updateQuestionNav() {
        const answers = getAllAnswers();
        const flagged = getFlagged();

        const currentQuestionId = QUESTIONS[currentQuestionIndex].id;

        document.querySelectorAll('.question-number-btn').forEach(btn => {
            const questionId = btn.dataset.questionId;

            btn.classList.remove(
                'bg-primary', 'text-white',
                'bg-light-warning', 'text-warning', 'border-warning',
                'bg-white', 'text-primary', 'border-primary',
                'text-muted'
            );

            if (questionId === currentQuestionId) {
                btn.classList.remove('border-0', 'text-muted');
                btn.classList.add('bg-white', 'text-primary', 'border', 'border-primary');
            } else if (flagged[questionId]) {
                btn.classList.remove('border-0', 'text-muted');
                btn.classList.add('bg-light-warning', 'text-warning', 'border', 'border-warning');
            } else if (answers[questionId]) {
                btn.classList.add('bg-primary', 'text-white');
            } else {
                btn.classList.add('bg-white', 'text-muted');
            }
        });
    }


    // ===============================
    // RESTORE SELECTED ANSWER
    // ===============================
    function restoreSelectedAnswer() {
        const question = QUESTIONS[currentQuestionIndex];
        const answers = getAllAnswers();
        const saved = answers[question.id];

        if (!saved) return;

        // SINGLE CHOICE
        if (saved.type === "single_choice") {
            const val = saved.answer?.[0]?.option_id;
            if (val) {
                $(`input[type="radio"][value="${val}"]`).prop('checked', true).trigger('change');
            }
        }

        // MULTIPLE CHOICE
        if (saved.type === "multiple_choice") {
            saved.answer.forEach(a => {
                $(`input[type="checkbox"][value="${a.option_id}"]`).prop('checked', true);
            });
        }

        // ESSAY
        if (saved.type === "essay") {
            setTimeout(() => {
                if ($('#summernote-answer').length) {
                    $('#summernote-answer').summernote('code', saved.answer_text);
                }
            }, 150);
        }

        // MATCHING
        if (saved.type === "matching") {
            const $board = $('.js-matching-board');
            const connections = saved.answer.map(a => ({
                from: 'left-' + a.left_item_id,
                to: 'right-' + a.right_item_id
            }));

            $board.attr('data-connections', JSON.stringify(connections));
            setTimeout(() => {
                redrawAllMatchingBoards();
            }, 50);
        }
    }


    // ===============================
    // PAYLOAD
    // ===============================
    function buildPayloadForBackend() {
        const answers = getAllAnswers();

        const formattedQuestions = Object.values(answers)
            .filter(question => {
                if (question.type === "essay") return question.answer_text?.trim();
                return question.answer?.length;
            })
            .map(question => {

                if (question.type === "essay") {
                    return {
                        id: question.id,
                        type: question.type,
                        answers_text: question.answer_text
                    };
                }

                if (question.type === "single_choice" || question.type === "multiple_choice") {
                    return {
                        id: question.id,
                        type: question.type,
                        answers: question.answer.map(a => ({ option_id: a.option_id }))
                    };
                }

                if (question.type === "matching") {
                    return {
                        id: question.id,
                        type: question.type,
                        answers: question.answer.map(a => ({
                            left_item_id: a.left_item_id,
                            right_item_id: a.right_item_id
                        }))
                    };
                }

            });

        return { questions: formattedQuestions };
    }


    // ===============================
    // HANDLE FULLSCREEN MODE
    // ===============================
    let isHandlingViolation = false;

    function enterFullscreen() {
        const elem = document.documentElement;

        if (elem.requestFullscreen) {
            return elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen();
            return Promise.resolve();
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
            return Promise.resolve();
        } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
            return Promise.resolve();
        }

        return Promise.reject();
    }

    function exitFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        }
    }

    function handleFullscreenChange() {
        const isFullscreen = !!(document.fullscreenElement ||
            document.webkitFullscreenElement ||
            document.mozFullScreenElement ||
            document.msFullscreenElement);

        if (!isFullscreen && !examState.isFinished && !isHandlingViolation) {
            playViolationSound();
            $('#fullscreen-exit-warning-modal').modal('show');
        }
    }


    // ===============================
    // HANDLE SWITCH TAB
    // ===============================
    let tabSwitchCount = 0;
    const MAX_TAB_SWITCHES = 0;

    function handleVisibilityChange() {
        if (document.hidden) {
            if (!examState.isFinished) {
                tabSwitchCount++;
                isHandlingViolation = true;

                if (tabSwitchCount > MAX_TAB_SWITCHES) {
                    playViolationSound();
                    showViolationModalWithCountdown(1000);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Jangan pindah tab! Pelanggaran berikutnya akan mengumpulkan ujian otomatis.',
                        timer: 5000,
                        showConfirmButton: false,
                        allowOutsideClick: false
                    }).then(() => {
                        isHandlingViolation = false;
                    });
                }
            }
        } else {
            setTimeout(() => {
                isHandlingViolation = false;
            }, 1000);
        }
    }

    function showViolationModalWithCountdown(duration = 1000) {
        const modal = $('#violation-detected-modal');
        const progressBar = $('#violation-progress');

        $('#enter-fullscreen-modal, #exam-submit-lock-modal, #flagged-warning-modal, #fullscreen-exit-warning-modal')
            .modal('hide');

        modal.modal({ backdrop: 'static', keyboard: false });
        modal.modal('show');

        let elapsed = 0;
        const intervalTime = 50;
        const totalSteps = duration / intervalTime;

        const interval = setInterval(() => {
            elapsed++;
            const percent = (elapsed / totalSteps) * 100;
            progressBar.css('width', percent + '%');

            if (elapsed >= totalSteps) {
                clearInterval(interval);
                modal.modal('hide');
                submitExam();
            }
        }, intervalTime);
    }

    // =========================
    // TOGGLE KOSONG <-> CENTANG
    // =========================
    $(document).on('click', '.js-toggle-active', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $btn = $(this);
        $btn.find('.js-icon-empty').toggleClass('d-none');
        $btn.find('.js-icon-check').toggleClass('d-none d-inline-flex');
    });

    // =========================
    // HELPERS: CONNECTIONS STORE
    // =========================
    function parseConnections($board) {
        const raw = $board.attr('data-connections') || '[]';
        try {
            const parsed = JSON.parse(raw);
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            return [];
        }
    }

    function saveConnections($board, connections) {
        $board.attr('data-connections', JSON.stringify(connections));
    }

    function isLeft(node) {
        return String(node || '').startsWith('left-');
    }

    function isRight(node) {
        return String(node || '').startsWith('right-');
    }

    // Hapus semua highlight aktif di board
    function clearActiveHighlight($board) {
        $board.find('.js-node').removeClass('border border-1 border-primary');
    }

    // Highlight node yang diklik + pasangannya jika ada koneksi
    function highlightNodeAndPair($board, node) {
        $board.find(`.js-node[data-node="${node}"]`).addClass('border border-1 border-primary');

        const connections = parseConnections($board);
        connections.forEach(function(conn) {
            if (conn.from === node) {
                $board.find(`.js-node[data-node="${conn.to}"]`).addClass('border border-1 border-primary');
            } else if (conn.to === node) {
                $board.find(`.js-node[data-node="${conn.from}"]`).addClass('border border-1 border-primary');
            }
        });
    }

    function clearSelections($board) {
        $board.removeAttr('data-selected-left');
        $board.removeAttr('data-selected-right');
        $board.find('.js-node, .js-anchor').removeClass('is-selected');
        clearActiveHighlight($board);
    }

    function setSelected($board, node) {
        if (isLeft(node)) $board.attr('data-selected-left', node);
        if (isRight(node)) $board.attr('data-selected-right', node);
        $board.find(`.js-anchor[data-node="${node}"]`).addClass('is-selected');
    }

    function getSelectedLeft($board) {
        return $board.attr('data-selected-left') || '';
    }

    function getSelectedRight($board) {
        return $board.attr('data-selected-right') || '';
    }

    // =========================
    // SVG DRAWING
    // =========================
    function drawMatchingLines($board, connections) {
        const $svg = $board.find('.js-matching-svg');
        if (!$svg.length) return;

        const boardRect = $board[0].getBoundingClientRect();
        if (!boardRect.width || !boardRect.height) return;

        $svg.empty();
        $svg.attr('viewBox', `0 0 ${boardRect.width} ${boardRect.height}`);

        connections.forEach(function(conn) {
            const $from = $board.find(`.js-anchor[data-node="${conn.from}"]`);
            const $to   = $board.find(`.js-anchor[data-node="${conn.to}"]`);
            if (!$from.length || !$to.length) return;

            const fromRect = $from[0].getBoundingClientRect();
            const toRect   = $to[0].getBoundingClientRect();

            const x1 = (fromRect.left + fromRect.width / 2)  - boardRect.left;
            const y1 = (fromRect.top  + fromRect.height / 2) - boardRect.top;
            const x2 = (toRect.left   + toRect.width / 2)    - boardRect.left;
            const y2 = (toRect.top    + toRect.height / 2)   - boardRect.top;

            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.setAttribute('x1', x1);
            line.setAttribute('y1', y1);
            line.setAttribute('x2', x2);
            line.setAttribute('y2', y2);
            line.setAttribute('stroke', '#5D87FF');
            line.setAttribute('stroke-width', '2');
            line.setAttribute('stroke-linecap', 'round');
            $svg[0].appendChild(line);
        });
    }

    function updatePinsState($board, connections) {
        $board.find('.js-node, .js-anchor').removeClass('is-connected');

        connections.forEach(function(conn) {
            $board.find(`.js-node[data-node="${conn.from}"], .js-node[data-node="${conn.to}"]`)
                .addClass('is-connected');
            $board.find(`.js-anchor[data-node="${conn.from}"], .js-anchor[data-node="${conn.to}"]`)
                .addClass('is-connected');
        });
    }

    function redrawBoard($board) {
        const connections = parseConnections($board);
        updatePinsState($board, connections);
        drawMatchingLines($board, connections);
    }

    function redrawAllMatchingBoards() {
        $('.js-matching-board').each(function() {
            redrawBoard($(this));
        });
    }

    // =========================
    // PAIRING LOGIC
    // =========================
    function removeConnectionByNode(connections, node) {
        return connections.filter(function(c) {
            return c.from !== node && c.to !== node;
        });
    }

    function upsertConnection(connections, leftNode, rightNode) {
        const filtered = connections.filter(function(connection) {
            return connection.from !== leftNode &&
                connection.to   !== leftNode &&
                connection.from !== rightNode &&
                connection.to   !== rightNode;
        });

        filtered.push({ from: leftNode, to: rightNode });
        return filtered;
    }

    function resetSideSelection($board, node) {
        if (isLeft(node)) {
            $board.removeAttr('data-selected-left');
            $board.find('.js-anchor[data-node^="left-"]').removeClass('is-selected');
        } else if (isRight(node)) {
            $board.removeAttr('data-selected-right');
            $board.find('.js-anchor[data-node^="right-"]').removeClass('is-selected');
        }
    }

    // =========================
    // INIT & REDRAW EVENTS
    // =========================
    $(function() {
        $('.js-matching-board').each(function() {
            const $b = $(this);
            if (!$b.attr('data-connections')) {
                $b.attr('data-connections', '[]');
            }
        });

        redrawAllMatchingBoards();
        requestAnimationFrame(redrawAllMatchingBoards);
    });

    $(window).on('resize', redrawAllMatchingBoards);

    $(document).on('shown.bs.collapse', '.accordion-collapse', function() {
        setTimeout(redrawAllMatchingBoards, 50);
    });

    // ===============================
    // READY
    // ===============================
    $(document).ready(function() {
        startExamTimer();
        renderQuestion(0);

        if (currentQuestionIndex === 0) {
            $('#enter-fullscreen-modal').modal('show');
        }

        document.addEventListener('fullscreenchange', handleFullscreenChange);
        document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
        document.addEventListener('mozfullscreenchange', handleFullscreenChange);
        document.addEventListener('MSFullscreenChange', handleFullscreenChange);
        document.addEventListener('visibilitychange', handleVisibilityChange);

        document.addEventListener('contextmenu', function(e) {
            if (!examState.isFinished) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Klik kanan tidak diperbolehkan selama ujian.',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });

        document.addEventListener('copy',  function(e) { if (!examState.isFinished) e.preventDefault(); });
        document.addEventListener('cut',   function(e) { if (!examState.isFinished) e.preventDefault(); });
        document.addEventListener('paste', function(e) { if (!examState.isFinished) e.preventDefault(); });

        document.addEventListener('keydown', function(e) {
            if (!examState.isFinished) {
                if (e.key === 'F11' || e.keyCode === 122) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
                if (e.key === 'Escape' || e.key === 'Esc' || e.keyCode === 27) {
                    playViolationSound();
                    e.preventDefault();
                    e.stopPropagation();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Anda tidak dapat keluar dari mode fullscreen selama ujian berlangsung.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return false;
                }
            }
        }, true);


        // =========================
        // PILIH JAWABAN
        // =========================
        $(document).on('change', 'input[type="radio"][name="answer"]', function() {
            const question = QUESTIONS[currentQuestionIndex];

            saveQuestionAnswer({
                id: question.id,
                type: "single_choice",
                answer: [{ option_id: $(this).val() }]
            });

            updateQuestionNav();
            handleSubmitVisibility();
        });

        $(document).on('change', 'input[type="checkbox"][name="answer"]', function() {
            const question = QUESTIONS[currentQuestionIndex];
            let selected = [];

            $('input[name="answer"]:checked').each(function() {
                selected.push({ option_id: $(this).val() });
            });

            if (selected.length === 0) {
                const answers = getAllAnswers();
                delete answers[question.id];
                localStorage.setItem(STORAGE_KEY_EXAM_ANSWERS, JSON.stringify(answers));
                return;
            }

            saveQuestionAnswer({
                id: question.id,
                type: "multiple_choice",
                answer: selected
            });

            updateQuestionNav();
            handleSubmitVisibility();
        });

        // =========================
        // MATCHING NODE CLICK
        // =========================
        $(document).on('click', '.js-matching-board .js-node, .js-matching-board .js-anchor', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const node   = $(this).attr('data-node');
            const $board = $(this).closest('.js-matching-board');

            if (!node || !$board.length) return;

            // Step 1: hapus semua highlight aktif
            clearActiveHighlight($board);

            // Step 2: highlight node yang diklik (dan pasangannya jika sudah ada koneksi)
            highlightNodeAndPair($board, node);

            let connections = parseConnections($board);

            // Jika node sudah punya koneksi → hapus koneksi
            const alreadyConnected = connections.some(c => c.from === node || c.to === node);
            if (alreadyConnected) {
                connections = removeConnectionByNode(connections, node);
                saveConnections($board, connections);
                clearSelections($board);
                redrawBoard($board);

                const question  = QUESTIONS[currentQuestionIndex];
                const formatted = connections.map(c => ({
                    left_item_id:  c.from.replace('left-', ''),
                    right_item_id: c.to.replace('right-', '')
                }));

                if (formatted.length > 0) {
                    saveQuestionAnswer({ id: question.id, type: "matching", answer: formatted });
                } else {
                    const answers = getAllAnswers();
                    delete answers[question.id];
                    localStorage.setItem(STORAGE_KEY_EXAM_ANSWERS, JSON.stringify(answers));
                }

                updateQuestionNav();
                handleSubmitVisibility();
                return;
            }

            // Jika node sudah active selected → deselect
            const selectedLeft  = getSelectedLeft($board);
            const selectedRight = getSelectedRight($board);
            const isAlreadySelected = (node === selectedLeft || node === selectedRight);

            if (isAlreadySelected) {
                resetSideSelection($board, node);
                clearActiveHighlight($board);
                return;
            }

            // Reset sisi yang sama, lalu set node ini sebagai selected
            resetSideSelection($board, node);
            setSelected($board, node);

            const leftSel  = getSelectedLeft($board);
            const rightSel = getSelectedRight($board);

            // Jika kedua sisi sudah dipilih → buat koneksi
            if (leftSel && rightSel) {
                connections = upsertConnection(connections, leftSel, rightSel);
                saveConnections($board, connections);

                const question  = QUESTIONS[currentQuestionIndex];
                const formatted = connections.map(c => ({
                    left_item_id:  c.from.replace('left-', ''),
                    right_item_id: c.to.replace('right-', '')
                }));

                saveQuestionAnswer({ id: question.id, type: "matching", answer: formatted });
                updateQuestionNav();
                handleSubmitVisibility();

                clearSelections($board);
                redrawBoard($board);

                // Highlight kedua node yang baru saja dipasangkan
                $board.find(`.js-node[data-node="${leftSel}"]`).addClass('border border-1 border-primary');
                $board.find(`.js-node[data-node="${rightSel}"]`).addClass('border border-1 border-primary');
            }
        });

        // tombol ragu-ragu
        $('#ragu-ragu').on('change', function() {
            const questionId = QUESTIONS[currentQuestionIndex].id;
            toggleFlag(questionId);
            updateFlagCheckbox(questionId);
        });

        $(document).on('click', '.question-number-btn', function() {
            const number = parseInt($(this).data('number')) - 1;
            currentQuestionIndex = number;
            renderQuestion(currentQuestionIndex);
        });

        $('#next-btn').click(() => {
            if (currentQuestionIndex < QUESTION_TOTAL - 1) {
                currentQuestionIndex++;
                renderQuestion(currentQuestionIndex);
            }
        });

        $('#prev-btn').click(() => {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                renderQuestion(currentQuestionIndex);
            }
        });

        // submit
        $('#submit-exam').on('click', function() {
            const flagged      = getFlagged();
            const flaggedCount = Object.entries(flagged).filter(([key, value]) => value === true).length;
            const canSubmitEarly = @json($exam->allow_early_submission ? 'true' : 'false');

            if (remainingSeconds <= 300 || canSubmitEarly) {
                if (flaggedCount > 0) {
                    $('#flagged-warning-modal').modal('show');
                } else {
                    $('#confirmation-submit-exam-modal').modal('show');
                }
            } else {
                $('#exam-submit-lock-modal').modal('show');
            }
        });

        $('#submit-flagged-exam').click(function(e) {
            e.preventDefault();
            button = $('#submit-flagged-exam');
            setButtonLoading(button, true);
            submitExam();
        });

        $('#submit-exam-btn').click(function(e) {
            e.preventDefault();
            button = $('#submit-exam-btn');
            setButtonLoading(button, true);
            submitExam();
        });
    });

    function submitExam() {
        let data = buildPayloadForBackend();

        $.ajax({
            type: "POST",
            url: `{{ route('api.student.exam.finish', $data->student_exam_id) }}`,
            headers: {
                'Authorization': 'Bearer {{ session('api_bearer') }}',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: data,
            success: function(response) {
                clearAllAnswersAndFlag();
                examState.isFinished = true;

                $('.modal.show').each(function() {
                    var modal = bootstrap.Modal.getInstance(this);
                    if (modal) modal.hide();
                });

                $('#success-submit-exam-modal').modal('show');
                $('#btn-show-result').click(function(e) {
                    e.preventDefault();
                    $('#success-submit-exam-modal').modal('hide');
                    window.location.href =
                        "{{ route('student.exams.result', ['studentExam' => $data->student_exam_id]) }}";
                });
            },
            error: function(response) {
                Swal.fire({
                    title: "Gagal menyelesaikan ujian",
                    text: response.responseJSON.meta.message,
                    icon: "error"
                }).then(() => {
                    window.location.href =
                        "{{ route('student.exams.result', ['studentExam' => $data->student_exam_id]) }}";
                });
            },
            complete: function() {
                setButtonLoading(button, false);
            }
        });
    }

    const modalVideoHint = new bootstrap.Modal(document.getElementById('modalVideoHint'));
    const videoHint = document.getElementById('video-hint');

    document.getElementById('btn-video-hint').addEventListener('click', function() {
        modalVideoHint.show();
    });

    document.getElementById('modalVideoHint').addEventListener('shown.bs.modal', function() {
        videoHint.play();
    });

    document.getElementById('modalVideoHint').addEventListener('hidden.bs.modal', function() {
        videoHint.pause();
        videoHint.currentTime = 0;
    });
</script>