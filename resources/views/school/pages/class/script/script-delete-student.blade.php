<script>
    $(function() {
        const csrfToken = @json(csrf_token());

        if (!window.toastr) {
            //console.warn('toastr belum ter-load. Pastikan toastr.min.js sudah di-include.');//
        } else {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 3000,
                extendedTimeOut: 1000,
                showDuration: 300,
                hideDuration: 300,
                showMethod: 'fadeIn',
                hideMethod: 'fadeOut'
            };
        }

        function toast(type, message) {
            if (!window.toastr) return;
            toastr[type](message);
        }


        function showDeleteModal() {
            const el = document.getElementById('modal-delete');
            if (!el) return;

            if (window.bootstrap?.Modal) {
                const modal = bootstrap.Modal.getOrCreateInstance(el);
                modal.show();
                return;
            }

            if ($.fn.modal) {
                $('#modal-delete').modal('show');
            }
        }

        function hideDeleteModal() {
            const el = document.getElementById('modal-delete');
            if (!el) return;

            if (window.bootstrap?.Modal) {
                const modal = bootstrap.Modal.getInstance(el);
                if (modal) modal.hide();
                return;
            }

            if ($.fn.modal) {
                $('#modal-delete').modal('hide');
            }
        }

        $(document)
            .off('click.openDelete', '.btn-open-delete')
            .on('click.openDelete', '.btn-open-delete', function(e) {
                e.preventDefault();

                const id = $(this).data('id');
                const name = $(this).data('name') || 'data ini';

                if (!id) return toast('error', 'ID tidak ditemukan.');

                $('#delete-id').val(id);
                $('#delete-text').text(`Apakah anda yakin akan menghapus ${name}?`);

                showDeleteModal();
            });

        $('#form-delete')
            .off('submit.delete')
            .on('submit.delete', function(e) {
                e.preventDefault();

                const id = $('#delete-id').val();
                if (!id) return toast('error', 'ID tidak valid.');

                const $form = $(this);
                const $btn = $form.find('button[type="submit"]');
                const oldText = $btn.text();
                $btn.prop('disabled', true).text('Menghapus...');

                $.ajax({
                    url: "{{ route('school.classroom-student.destroy') }}",
                    type: "POST",
                    data: {
                        _method: "DELETE",
                        "ids[]": [id]
                    },
                    headers: {
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    success: function(res) {
                        toast('success', 'Data berhasil dihapus');
                        hideDeleteModal();

                        // remove row
                        $(`button.btn-open-delete[data-id="${id}"]`)
                            .closest('tr')
                            .fadeOut(200, function() {
                                $(this).remove();
                            });
                    },
                    error: function(xhr) {
                        const msg =
                            xhr.responseJSON?.message ||
                            xhr.responseJSON?.meta?.message ||
                            (xhr.status === 422 ? 'Validasi gagal (ids wajib diisi).' :
                                'Gagal menghapus data.');
                        toast('error', msg);
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text(oldText);
                    }
                });
            });
    });
</script>
