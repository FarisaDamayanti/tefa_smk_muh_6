<script>
    let deleteId = null;

    $('.btn-delete-qb').on('click', function() {
        deleteId = $(this).data('id');
    });

    $('#delete-question-bank-form').on('submit', function(e) {
        e.preventDefault();

        if (!deleteId) return;

        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...');

        $.ajax({
            url: `/api/school/question-bank/${deleteId}?_method=DELETE`,
            type: 'POST',
            headers: {
                'Authorization': `Bearer {{ session('api_bearer') }}`,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                const msg = res?.message || res?.meta?.message || 'Berhasil menghapus data';

                Swal.fire('Berhasil', msg, 'success').then(() => {
                    $('#delete-question-bank-modal').modal('hide');
                    window.location.reload();
                });
            },
            error: function(xhr) {
                const json = xhr.responseJSON || {};
                const msg = json?.message || json?.meta?.message || 'Terjadi kesalahan';

                Swal.fire('Gagal', msg, 'error').then(() => {
                    $('#delete-question-bank-modal').modal('hide');
                });
            },
            complete: function() {
                btn.prop('disabled', false).html('Hapus');
            }
        });
    });
</script>
