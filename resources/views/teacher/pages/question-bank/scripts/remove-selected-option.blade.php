<script>
    function selectAllOptions(source) {
        const checkboxes = document.querySelectorAll('.answer-options');
        checkboxes.forEach(checkbox => {
            checkbox.checked = source.checked;
        });
        toggleBulkButton();
    }

    function toggleBulkButton() {
        const checkedCount = $('.answer-options:checked').length;

        if (checkedCount > 0) {
            $('#bulk-action-btn').fadeIn();
        } else {
            $('#bulk-action-btn').fadeOut();
        }
    }

    $(document).ready(function() {
        $('#select-all-options').on('change', function() {
            selectAllOptions(this);
        });

        $(document).on('change', '.answer-options', function() {
            const total = $('.answer-options').length;
            const checked = $('.answer-options:checked').length;
            $('#select-all-options').prop('checked', total === checked);
            toggleBulkButton();
        });

        $('#bulk-action-btn').on('click', function() {
            const selectedOptions = $('.answer-options:checked');

            if (selectedOptions.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan pilih setidaknya satu opsi untuk dihapus.',
                });
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus ${selectedOptions.length} opsi yang dipilih.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('api.teacher.exam-question.option.delete') }}",
                        headers: {
                            'Authorization': 'Bearer {{ session('api_bearer') }}',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        contentType: "application/json",
                        data: JSON.stringify({
                            _method: "DELETE",
                            ids: selectedOptions.map((i, el) => $(el).val())
                                .get()
                        }),
                        dataType: "json",
                        success: function(response) {
                            toastr.success(response.meta.message ||
                                'Berhasil menghapus opsi jawaban.');

                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        },
                        error: function(response) {
                            toastr.error(response.responseJSON?.meta?.message ||
                                'Ada kesalahan saat menghapus opsi jawaban.');
                        }
                    });
                }
            });
        });

    });
</script>
