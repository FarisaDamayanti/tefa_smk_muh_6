<script>
    let moduleIdToDelete;
    $(document).on('click', '.btn-delete-material', function() {
        moduleIdToDelete = $(this).data('id');
        $('#delete-material-modal').modal('show');
    });

    $(document).on('submit', '#delete-material-form', async function(e) {
        e.preventDefault();
        
        if (!moduleIdToDelete) return;

        const $form = $(this);
        const $btn = $form.find('button[type="submit"]');
        const originalText = $btn.html();
        
        $form.find('button').prop('disabled', true);
        $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...');

        try {
            const response = await fetch(`/api/teacher/modules/${moduleIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const result = await response.json();

            if (response.ok) {
                $('#delete-material-modal').modal('hide');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: result.meta?.message || 'Materi berhasil dihapus',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: result.meta?.message || 'Gagal menghapus materi',
                });
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menghubungi server',
            });
        } finally {
            $form.find('button').prop('disabled', false);
            $btn.html(originalText);
            moduleIdToDelete = null;
        }
    });

    $('#delete-material-modal').on('hidden.bs.modal', function() {
        moduleIdToDelete = null;
    });
</script>