<script>
    const CURRENT_BANK_ID = "{{ $questionBank->id }}";

    function getSelectedQuestionIds() {
        return $('.question-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
    }

    function toggleDuplicateButton() {
        const checkedCount = $('.question-checkbox:checked').length;
        $('#btn-duplicate').prop('disabled', checkedCount === 0);
    }

    function resetDuplicateModalErrors() {
        $('#duplicate-target-bank-error').addClass('d-none').text('');
    }

    function syncSelectAllCheckbox() {
        const total = $('.question-checkbox').length;
        const checked = $('.question-checkbox:checked').length;
        $('#select-all-questions').prop('checked', total > 0 && checked === total);
    }

    function renderSelectedQuestionsPreview() {
        const $wrap = $('#duplicate-selected-list');
        $wrap.empty();

        const $checked = $('.question-checkbox:checked');

        if ($checked.length === 0) {
            $wrap.append('<span class="text-muted">Belum ada soal dipilih.</span>');
            return;
        }

        $checked.each(function() {
            const id = $(this).val();
            const label = $(this).data('label') || 'Soal';

            $wrap.append(`
                <span class="duplicate-chip" data-id="${id}">
                    <span>${label}</span>
                    <button type="button"
                        class="duplicate-chip-remove js-remove-selected"
                        data-id="${id}"
                        aria-label="Remove">×</button>
                </span>
            `);
        });
    }

    function getDuplicateTargetBankId() {
        return $('#duplicate-bank-dropdown .dropdown-selected').attr('data-selected-id') || '';
    }

    function resetDuplicateTargetBank() {
        $('#duplicate-bank-dropdown .dropdown-selected').attr('data-selected-id', '');
        $('#duplicate-bank-selected-text').text('Pilih bank soal tujuan').addClass('text-muted');
        $('#duplicate-bank-options .dropdown-option').removeClass('selected');
    }

    function closeDuplicateBankDropdown() {
        $('#duplicate-bank-dropdown .dropdown-selected').removeClass('active');
        $('#duplicate-bank-options').removeClass('show');
    }

    function openDuplicateBankDropdown() {
        $('#duplicate-bank-dropdown .dropdown-selected').addClass('active');
        $('#duplicate-bank-options').addClass('show');
    }

    $(document).on('click', '#duplicate-bank-dropdown .dropdown-selected', function(e) {
        e.preventDefault();

        if ($('#duplicate-bank-options').hasClass('show')) {
            closeDuplicateBankDropdown();
            return;
        }

        openDuplicateBankDropdown();
    });

    $(document).on('click', '#duplicate-bank-options .dropdown-option', function() {
        const isDisabled = $(this).hasClass('disabled') || $(this).hasClass('is-placeholder');
        const id = $(this).data('id') || '';
        const title = $(this).data('title') || '';

        if (isDisabled || !id) return;

        $('#duplicate-bank-options .dropdown-option').removeClass('selected');
        $(this).addClass('selected');

        $('#duplicate-bank-dropdown .dropdown-selected').attr('data-selected-id', id);
        $('#duplicate-bank-selected-text').text(title).removeClass('text-muted');

        resetDuplicateModalErrors();
        closeDuplicateBankDropdown();
    });

    $(document).on('click', function(e) {
        if ($(e.target).closest('#duplicate-bank-dropdown').length === 0) {
            closeDuplicateBankDropdown();
        }
    });

    $('#duplicate-question-modal').on('hidden.bs.modal', function() {
        resetDuplicateModalErrors();
        resetDuplicateTargetBank();
        closeDuplicateBankDropdown();
    });

    $(document).ready(function() {
        toggleDuplicateButton();
        syncSelectAllCheckbox();
    });

    $(document).on('change', '.question-checkbox', function() {
        toggleDuplicateButton();
        syncSelectAllCheckbox();

        if ($('#duplicate-question-modal').hasClass('show')) {
            renderSelectedQuestionsPreview();
        }
    });

    // Select all
    $(document).on('change', '#select-all-questions', function() {
        const isChecked = $(this).is(':checked');
        $('.question-checkbox').prop('checked', isChecked);

        toggleDuplicateButton();

        if ($('#duplicate-question-modal').hasClass('show')) {
            renderSelectedQuestionsPreview();
        }
    });

    $(document).on('click', '#btn-duplicate', function() {
        resetDuplicateModalErrors();
        renderSelectedQuestionsPreview();
        resetDuplicateTargetBank();
        $('#duplicate-question-modal').modal('show');
    });

    $(document).on('click', '.js-remove-selected', function() {
        const id = $(this).data('id');

        $(`.question-checkbox[value="${id}"]`).prop('checked', false);

        toggleDuplicateButton();
        syncSelectAllCheckbox();
        renderSelectedQuestionsPreview();
    });

    $(document).on('click', '#btn-submit-duplicate', function() {
        const $btn = $(this);
        resetDuplicateModalErrors();

        const ids = getSelectedQuestionIds();
        if (ids.length === 0) {
            toastr.warning('Please select at least one question.');
            return;
        }

        const targetBankId = getDuplicateTargetBankId();
        if (!targetBankId) {
            $('#duplicate-target-bank-error')
                .removeClass('d-none')
                .text('Bank soal tujuan wajib dipilih.');
            return;
        }

        if (targetBankId === CURRENT_BANK_ID) {
            toastr.error('Tidak bisa menyalin soal ke bank soal yang sama.');
            return;
        }

        const actionUrl = `/api/teacher/exam-questions/${targetBankId}/copy`;

        $btn.prop('disabled', true);
        console.log(ids);


        $.ajax({
            url: actionUrl,
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                questions: ids
            }),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(res) {
                $('#duplicate-question-modal').modal('hide');
                Swal.fire({
                    title: "Sukses",
                    text: res?.meta?.message || 'Berhasil menambah ujian.',
                    icon: "success"
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                let payload = xhr.responseJSON;

                if (!payload && xhr.responseText) {
                    try {
                        payload = JSON.parse(xhr.responseText);
                    } catch (e) {}
                }

                let message =
                    payload?.meta?.message ||
                    payload?.message ||
                    payload?.error?.message ||
                    'Something went wrong.';

                if (xhr.status === 422 && payload?.errors) {
                    const firstErr = Object.values(payload.errors)?.[0]?.[0];
                    if (firstErr) message = firstErr;
                }

                toastr.error(message);
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    });
</script>
