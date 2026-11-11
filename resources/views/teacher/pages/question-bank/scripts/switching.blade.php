<script>
    $(function() {
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

        function ensureBlankSummernote($el) {
            if (!$el.length) return;
            const html = normalizeSummernote($el.summernote('code'));
            if (!html) $el.summernote('code', '<p><br></p>');
        }

        function onSwitchToMultipleChoice() {
            $('input[name="answer[]"]').prop('checked', false);
            $('.option-mark').text('');

            ensureBlankSummernote($('#summernote-question'));
            ['a', 'b', 'c', 'd', 'e'].forEach(function(s) {
                ensureBlankSummernote($('#summernote-answer-' + s));
            });

            clearValidationUI();
        }

        function onSwitchToEssay() {
            ensureBlankSummernote($('#summernote-essay-question'));
            clearValidationUI();
        }

        function showForm(val) {
            $('.question-type-form').addClass('d-none');
            $('#form-' + val).removeClass('d-none');

            // side effects tiap tipe
            if (val === 'matching') {
                if (typeof ensureDefaultMatchingRows === 'function') ensureDefaultMatchingRows();
                if (typeof toggleMatchingLimit === 'function') toggleMatchingLimit();
            }

            if (val === 'multiple_choice') {
                onSwitchToMultipleChoice();
            }

            if (val === 'essay') {
                onSwitchToEssay();
            }
        }

        showForm($('#question-type').val());

        $('#question-type').on('change', function() {
            showForm($(this).val());
        });

        function initIfNotYet($el) {
            if (!$el.length) return;
            if ($el.next('.note-editor').length) return;
            $el.summernote({
                height: 200
            });
        }

        initIfNotYet($('#summernote-question'));
        initIfNotYet($('#summernote-answer-a'));
        initIfNotYet($('#summernote-answer-b'));
        initIfNotYet($('#summernote-answer-c'));
        initIfNotYet($('#summernote-answer-d'));
        initIfNotYet($('#summernote-answer-e'));
        initIfNotYet($('#summernote-essay-question'));

        $('.js-matching').each(function() {
            initIfNotYet($(this));
        });

    });
</script>