<script>
    $('#search').on('keyup', function() {
        let value = $(this).val();

        $('#search-modal').val(value);
    });
</script>