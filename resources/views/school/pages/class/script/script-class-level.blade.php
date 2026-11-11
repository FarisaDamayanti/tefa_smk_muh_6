<script>
    $('.btn-update-level').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#name-level').val(name);
        $('#form-update-level').attr('action', '{{ route('school.level-class.update', '') }}/' + id);
        $('#update-level').modal('show');
    });

    $('.btn-update-classroom').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var employee = $(this).data('employee');
        var level = $(this).data('level');
        $('#name-edit').val(name);
        $('#employee-edit').val(employee).trigger('change');
        $('#class-level').val(level).trigger('change');
        $('#edit-class-form').attr('action', '{{ route('school.classroom.update', '') }}/' + id);
        $('#update-class').modal('show');
    });

    $('.btn-delete-level').click(function() {
        var id = $(this).data('id');
        $('#form-delete').attr('action', '{{ route('school.level-class.destroy', '') }}/' + id);
        $('#modal-delete').modal('show');
    });

    $('.btn-delete-class').click(function() {
        var id = $(this).data('id');
        $('#form-delete').attr('action', '{{ route('school.classroom.destroy', '') }}/' + id);
        $('#modal-delete').modal('show');
    });

    $('.btn-export-student').on('click', function() {
        const school_year = $('#school-year').val() || $('#school_year-modal').val();
        const button = $(this);

        button.prop('disabled', true);
        if (button.attr('id') === 'btn-lg-export') {
            button.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengekspor...');
        } else {
            button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        }

        const resetButton = () => {
            button.prop('disabled', false);
            if (button.attr('id') === 'btn-lg-export') {
                button.html(`<i class="ti ti-download fs-4"></i><span class="d-none d-md-block ms-1">Ekspor Siswa</span>`);
            } else {
                button.html(`<i class="ti ti-download fs-4"></i>`);
            }
        };

        const params = new URLSearchParams({ school_year });
        const url   = `{{ route('api.school.students.export') }}?${params.toString()}`;

        fetch(url, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer {{ session('api_bearer') }}',
                'Accept'       : 'application/json, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            },
        })
        .then(async response => {
            const contentType = response.headers.get('Content-Type') || '';

            if (contentType.includes('application/json')) {
                const json = await response.json();
                const msg  = json.meta?.message || json.message || 'Terjadi kesalahan.';
                throw new Error(msg);
            }

            if (!response.ok) {
                throw new Error('Gagal export data. Silakan coba lagi.');
            }
            const disposition = response.headers.get('Content-Disposition') || '';
            let filename = 'DATA SISWA.xlsx';

            const utf8Match  = disposition.match(/filename\*=UTF-8''([^;]+)/i);
            const plainMatch = disposition.match(/filename="?([^";\n]+)"?/i);

            if (utf8Match) {
                filename = decodeURIComponent(utf8Match[1].trim());
            } else if (plainMatch) {
                filename = plainMatch[1].trim();
            }

            const blob    = await response.blob();
            const blobUrl = window.URL.createObjectURL(blob);
            const link    = document.createElement('a');

            link.href     = blobUrl;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(blobUrl);

            toastr.success('Berhasil export data.');
        })
        .catch(error => {
            toastr.error(error.message || 'Gagal export data.');
        })
        .finally(() => {
            resetButton();
        });
    });
</script>