<script>
    // untuk dihalaman exam-detail.blade.php
    $(document).ready(function() {
        const hash = window.location.hash;
    
        if (hash) {
            const trigger = document.querySelector(`a[href="${hash}"]`);
            if (trigger) {
                const tab = new bootstrap.Tab(trigger);
                tab.show();
            }
        }

        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            console.log('coba');
            
            const id = $(e.target).attr('href');
            history.replaceState(null, null, id);
        });
    });

    $('#btn-set-pagination').on('click', function() {
        const url = new URL(window.location.href);
        const type = $(this).data('type');

        if (type === 'pagination') {
            url.searchParams.set('per_page', 'all');
        } else {
            url.searchParams.delete('per_page');
        }

        window.location.href = url.toString();
    })
</script>