<script>
    $('#btn-create').on('click', function() {
        let questionLimit = {{ $questionBank->question_limit }};
        let questionCount = {{ $questions->count() }};

        if (questionCount >= questionLimit) {
            Swal.fire({
                icon: 'warning',
                title: 'Batas Jumlah Soal Tercapai',
                text: `Batas maksimal untuk bank soal ini adalah ${questionLimit} soal.`,
                confirmButtonColor: '#5D87FF',
            });
        } else {
            window.location.href = "{{ route('teacher.question-bank.create', $questionBank->id) }}";
        }
    });

    $(function() {
        function updateSelectAllState() {
            const total = $('.question-checkbox').length;
            const checked = $('.question-checkbox:checked').length;

            $('#select-all-questions').prop('checked', total > 0 && total === checked);

            $('#btn-duplicate').prop('disabled', checked === 0);
        }

        $('#select-all-questions').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('.question-checkbox').prop('checked', isChecked);
            updateSelectAllState();
        });

        $(document).on('change', '.question-checkbox', function() {
            updateSelectAllState();
        });

        updateSelectAllState();
    });
</script>
