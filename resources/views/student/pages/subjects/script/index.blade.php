<script>
    $(document).ready(function () {
        $('#reset-btn').on('click', function () {
            window.location.href = window.location.pathname;
        });

        $('#search').on('keyup', function () {
            $('#search-modal').val($(this).val());
        });
    });
</script>
