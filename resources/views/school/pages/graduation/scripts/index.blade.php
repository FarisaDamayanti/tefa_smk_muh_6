<script>
    $(document).ready(function() {
        $('#school_year_id').change(function() {
            const schoolYearId = $(this).val();
            const url = new URL(window.location.href);

            url.searchParams.set('school_year_id', schoolYearId);

            window.location.href = url.toString();
        });

        $('#reset-btn').on('click', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const schoolYearId = urlParams.get('school_year_id');
            let newUrl = window.location.pathname;

            if (schoolYearId) {
                newUrl += '?school_year_id=' + encodeURIComponent(schoolYearId);
            }

            window.location.href = newUrl;
        });

        $('#search').on('keyup', function() {
            $('#search-modal').val($(this).val());
        });
    });
</script>
