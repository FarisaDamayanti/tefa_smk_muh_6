<script>
    $(document).ready(function() {

        const STORAGE_KEY_SELECTED_QUESTIONS = 'selected_questions_{{ $questionBank->id }}';

        const $checkAllCheckbox = $('#checkAll');
        const $questionCheckboxes = $('.question-checkbox');
        const totalQuestionsCount = $questionCheckboxes.length;

        const activeQuestionIds = @json(
            $questionBank->examQuestions()->where('is_active', 1)->pluck('id')->map(fn($id) => (string) $id)
        );

        // ===============================
        // LOCAL STORAGE HELPERS
        // ===============================
        function getStoredSelectedQuestionIds() {
            const storedData = localStorage.getItem(STORAGE_KEY_SELECTED_QUESTIONS);
            return storedData ? JSON.parse(storedData).map(id => id.toString()) : [];
        }

        function storeSelectedQuestionIds(questionIds) {
            localStorage.setItem(
                STORAGE_KEY_SELECTED_QUESTIONS,
                JSON.stringify(questionIds.map(id => id.toString()))
            );
        }

        function updateSelectedQuestionCounter() {
            const storedIds = getStoredSelectedQuestionIds();
            const totalCheckedOnPage = $('.question-checkbox:checked').length;

            if (totalQuestionsCount > 0) {
                $checkAllCheckbox.prop('checked', totalCheckedOnPage === totalQuestionsCount);
                $checkAllCheckbox.prop('indeterminate', totalCheckedOnPage > 0 && totalCheckedOnPage < totalQuestionsCount);
            } else {
                $checkAllCheckbox.prop('checked', false);
                $checkAllCheckbox.prop('indeterminate', false);
            }

            $('#total-selected-questions').text(storedIds.length);
        }

        // ===============================
        // RESTORE CHECKBOX STATE
        // ===============================
        function restoreSelectedQuestionsFromStorage() {
            const currentPath = window.location.pathname;

            let referrerPath = '';
            try {
                referrerPath = document.referrer !== '' ? new URL(document.referrer).pathname : '';
            } catch (e) {}

            const isFromSamePage = referrerPath === currentPath;
            const isFromReviewPage = referrerPath.includes('review') || referrerPath.includes('pratinjau');

            if (!isFromSamePage && !isFromReviewPage) {
                storeSelectedQuestionIds(activeQuestionIds);

                $questionCheckboxes.each(function() {
                    $(this).prop('checked', activeQuestionIds.includes($(this).data('id').toString()));
                });

                updateSelectedQuestionCounter();
                return;
            }

            const storedIds = getStoredSelectedQuestionIds();
            $questionCheckboxes.each(function() {
                $(this).prop('checked', storedIds.includes($(this).data('id').toString()));
            });

            updateSelectedQuestionCounter();
        }

        // ===============================
        // CHECK ALL
        // ===============================
        $checkAllCheckbox.on('change', function() {
            const isChecked = $(this).prop('checked');
            let storedIds = getStoredSelectedQuestionIds();

            $questionCheckboxes.each(function() {
                const questionId = $(this).data('id').toString();
                $(this).prop('checked', isChecked);

                if (isChecked) {
                    if (!storedIds.includes(questionId)) storedIds.push(questionId);
                } else {
                    storedIds = storedIds.filter(id => id !== questionId);
                }
            });

            storeSelectedQuestionIds(storedIds);
            updateSelectedQuestionCounter();
        });

        // ===============================
        // SINGLE CHECKBOX CHANGE
        // ===============================
        $(document).on('change', '.question-checkbox', function() {
            const questionId = $(this).data('id').toString();
            const isChecked = $(this).prop('checked');
            let storedIds = getStoredSelectedQuestionIds();

            if (isChecked) {
                if (!storedIds.includes(questionId)) storedIds.push(questionId);
            } else {
                storedIds = storedIds.filter(id => id !== questionId);
            }

            storeSelectedQuestionIds(storedIds);
            updateSelectedQuestionCounter();
        });

        restoreSelectedQuestionsFromStorage();
        $('#save-selected-questions').on('click', function(e) {
            e.preventDefault();

            const btn = $(this);
            setButtonLoading(btn, true);

            $.ajax({
                type: 'POST',
                url: "{{ route('api.school.activate-questions', $questionBank->id) }}?_method=PUT",
                data: { questions: getStoredSelectedQuestionIds() },
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
                        localStorage.removeItem(STORAGE_KEY_SELECTED_QUESTIONS);
                        window.location.href = "{{ route('school.question-bank.index') }}";
                    });
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.data?.questions?.[0] ?? 'Ada kesalahan saat menyimpan data.');
                },
                complete: function() {
                    setButtonLoading(btn, false);
                }
            });
        });
    });

    function setButtonLoading(button, isLoading) {
        if (!button.data('original-html')) button.data('original-html', button.html());

        if (isLoading) {
            button.prop('disabled', true).addClass('disabled');
            button.html(`<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>` + button.data('original-html'));
        } else {
            button.prop('disabled', false).removeClass('disabled');
            button.html(button.data('original-html'));
        }
    }
</script>