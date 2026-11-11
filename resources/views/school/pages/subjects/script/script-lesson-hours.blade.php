<script>
    // Map tab ke index array $latestHour
    const dayMap = {
        'pills-senin':   { index: 0, day: 'monday' },
        'pills-selasa':  { index: 1, day: 'tuesday' },
        'pills-rabu':    { index: 2, day: 'wednesday' },
        'pills-kamis':   { index: 3, day: 'thursday' },
        'pills-jumat':   { index: 4, day: 'friday' },
        'pills-sabtu':   { index: 5, day: 'saturday' },
        'pills-minggu':  { index: 6, day: 'sunday' },
    };

    const latestHours = @json($latestHour);

    document.querySelectorAll('[data-bs-toggle="pill"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function () {
            const tabId = this.getAttribute('href').replace('#', '');
            const mapped = dayMap[tabId];
            if (!mapped) return;

            const latest = latestHours[mapped.index];
            const btn = document.querySelector('.btn-create');

            btn.setAttribute('data-day', mapped.day);
            btn.setAttribute('data-name', latest[1]);
            btn.setAttribute('data-start', latest[0] ? latest[0].end : '');
            btn.setAttribute('data-id', latest[0] ? latest[0].id : '');
        });
    });

    $('.btn-create').click(function() {
        var id = $(this).data('id');
        var start = $(this).data('start');
        var day = $(this).data('day');
        var name = $(this).data('name');

        // Set nama (jam ke) otomatis dari nextOrder
        $('#store-name').val(name);

        // Set start time, default 07:00 jika belum ada data
        $('#store-start').val(start ? start : '07:00');

        // Set action form sesuai hari aktif
        $('#form-create').attr('action', `{{ route('school.lesson-hours.store', '') }}/${day}`);

        $('#modal-create').modal('show');
    });

    $('.btn-edit').click(function() {
        var id = $(this).data('id');
        var start = $(this).data('start');
        var end = $(this).data('end');
        var name = $(this).data('name');

        if (name === 'Istirahat') {
            $('#flexSwitchCheckDefault').prop('checked', true);
            $('#name').val('').prop('disabled', true);
        } else {
            $('#flexSwitchCheckDefault').prop('checked', false);
            $('#name').prop('disabled', false);

            let matches = name.match(/\d+/);
            let hour = matches ? matches[0] : '';
            $('#name').val(hour);
        }

        $('#start').val(start);
        $('#end').val(end);
        $('#modal-update').modal('show');
        $('#form-update').attr('action', `{{ route('school.lesson-hours.update', '') }}/${id}`);
    });

    $('#flexSwitchCheckDefault').change(function() {
        if ($(this).is(':checked')) {
            $('#name').val('').prop('disabled', true);
        } else {
            $('#name').prop('disabled', false);
        }
    });

    $('.btn-delete').click(function() {
        var id = $(this).data('id');
        $('#modal-delete').modal('show');
        $('#form-delete').attr('action', `{{ route('school.lesson-hours.destroy', '') }}/${id}`);
    });
</script>