<script>
    function markAnswer(checkbox) {
        const span = checkbox.parentElement.querySelector('.option-mark');
        if (!span) return;

        if (checkbox.checked) {
            span.textContent = '(Ditandai sebagai jawaban benar)';
        } else {
            span.textContent = '';
        }
    }
</script>
