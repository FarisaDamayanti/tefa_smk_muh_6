<script>
    $(function() {

        $(document).off('click', '#btnSaveAttendance');

        $('#checkAll').on('change', function() {
            if ($(this).is(':checked')) {
                $('.radio-hadir').prop('checked', true);

                $('.radio-hadir').each(function() {
                    const name = $(this).attr('name');
                    $('input[name="' + name + '"][value!="present"]').prop('checked', false);
                });
            } else {
                $('input[type="radio"]').prop('checked', false);
            }
        });

        $(document).on('change', 'input[type="radio"]', function() {
            let allPresent = true;

            $('.radio-hadir').each(function() {
                if (!$(this).is(':checked')) {
                    allPresent = false;
                    return false;
                }
            });
            
            $('#checkAll').prop('checked', allPresent);
        });

        let isSubmitting = false;

        $(document).on('click', '#btnSaveAttendance', function() {
            if (isSubmitting) return;
            isSubmitting = true;

            const teacherJournal = $('meta[name="teacher-journal"]').attr('content');
            const apiToken = $('meta[name="api-token"]').attr('content');

            if (!teacherJournal) {
                isSubmitting = false;
                alert('teacherJournal tidak ditemukan.');
                return;
            }

            let attendances = [];
            $('.presensi-table tbody tr[data-student-id]').each(function() {
                const studentId = Number($(this).data('student-id'));
                const status = $(this).find('input[type="radio"]:checked').val();

                if (studentId && status) {
                    attendances.push({
                        classroom_student_id: studentId,
                        status: status
                    });
                }
            });

            const map = new Map();
            attendances.forEach(a => map.set(a.classroom_student_id, a));
            attendances = Array.from(map.values());

            if (!attendances.length) {
                isSubmitting = false;
                alert('Pilih status kehadiran minimal 1 siswa.');
                return
            }

            const $btn = $('#btnSaveAttendance');
            $btn.prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: `/api/teacher/store-student-attendances/${teacherJournal}`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    attendances
                }),
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json',
                    ...(apiToken ? {
                        'Authorization': `Bearer ${apiToken}`
                    } : {})
                },
                success: function(res) {
                    const code = res?.meta?.code ?? 200;

                    if (code !== 200 && code !== 201) {
                        alert(res?.meta?.message ?? 'Gagal menyimpan presensi.');
                        return;
                    }

                    window.location.href =
                        `/teacher/presence/detail/${teacherJournal}?saved=1`;
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert('Gagal menyimpan presensi. Cek console.');
                },
                complete: function() {
                    isSubmitting = false;
                    $btn.prop('disabled', false).text('Simpan');
                }
            });
        });

    });
</script>
