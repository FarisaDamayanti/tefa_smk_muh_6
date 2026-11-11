<script>
    $('#btn-save-grades').click(function (e) {
        e.preventDefault();
        
        const grades = [];
        const studentsWithoutGrades = [];

        $('.grade-input').each(function () {
            const $input = $(this);
            const submissionTaskId = $input.data('submission-task-id');
            const studentName = $input.data('student-name');
            const value = ($input.val() || '').toString().trim();

            if (submissionTaskId) {
                grades.push({
                    id: submissionTaskId,
                    grade: value !== '' ? value : null      
                });

                if (value === '') {
                    studentsWithoutGrades.push(studentName);
                }
            }
        });

        if (grades.length === 0) {
            Swal.fire({
                title: 'Perhatian!',
                text: 'Tidak ada data untuk disimpan.',
                icon: 'warning'
            });
            return;
        }

        if (studentsWithoutGrades.length > 0) {
            const studentList = studentsWithoutGrades.join('<br>');
            
            Swal.fire({
                title: 'Perhatian!',
                html: `Terdapat <strong>${studentsWithoutGrades.length} siswa</strong> yang belum dinilai: Apakah Anda yakin ingin menyimpan?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    saveGrades(grades);
                }
            });
        } else {
            saveGrades(grades);
        }
    });

    function saveGrades(grades) {
        let formData = new FormData();

        grades.forEach(function(item, index) {
            formData.append(`scores[${index}][id]`, item.id);
            
            if (item.grade === null) {
                formData.append(`scores[${index}][grade]`, '');
            } else {
                formData.append(`scores[${index}][grade]`, item.grade);
            }
        });

        $.ajax({
            type: "POST",
            url: "{{ route('api.teacher.update.assessment-value') }}",
            data: formData,
            headers: {
                'Authorization': 'Bearer {{ session("api_bearer") }}',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('.grade-input').removeClass('error-input shake');
                $('.grade-input').css('border', '');
                $('.invalid-feedback').remove();
            },
            success: function(response) {
                Swal.fire({
                    title: "Sukses",
                    text: response.meta.message || 'Berhasil menyimpan nilai.',
                    icon: "success"
                }).then(() => {
                    location.reload();
                });
            },
            error: function(response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.data;
                    console.log(errors);

                    $.each(errors, function(field, messages) {
                        let match = field.match(/scores\.(\d+)\.grade/);

                        if (match) {
                            let index = match[1];
                            let $input = $('#input-grade-' + index);
                            let $td = $input.closest('td');

                            $input.removeClass('shake');
                            void $input[0].offsetWidth; 

                            $input.addClass('error-input shake');
                            $input.css('border', '1px solid #dc3545');

                            $td.append(`
                                <div class="invalid-feedback d-block text-center mt-1" style="font-size: 0.75rem; color: #dc3545;">
                                    ${messages[0]}
                                </div>
                            `);
                        }
                    });

                    setTimeout(function() {
                        $('.grade-input').removeClass('shake');
                    }, 180);

                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Ada kesalahan saat menyimpan data.",
                        icon: "error"
                    });
                }
            }
        });
    }
</script>