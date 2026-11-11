<script>
    $('.select2').select2({
        dropdownParent: $('#formQuestionBank'),
        allowClear: false
    });

    $('#subject_id').select2({
        placeholder: "Pilih atau cari mata pelajaran",
        tags: true, // memungkinkan input baru
        createTag: function(params) {
            let term = $.trim(params.term);

            if (term === '') return null;

            return {
                id: term,
                text: 'Tambah mapel: "' + term + '"',
                newTag: true
            };
        }
    });

    $('#subject_id').on('select2:select', function(e) {
        let data = e.params.data;

        if (data.newTag) {
            let subjectName = data.id;

            Swal.fire({
                title: 'Tambahkan Mata Pelajaran',
                text: 'Apakah anda ingin menambahkan mata pelajaran "' + subjectName + '"?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ route('api.school.subject.store') }}`,
                        type: 'POST',
                        headers: {
                            'Authorization': `Bearer {{ session('api_bearer') }}`,
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            name: subjectName,
                        },
                        success: function(response) {
                            Swal.fire('Berhasil', response.meta?.message || 'Mata pelajaran berhasil ditambahkan', 'success');
                            applySubject(response.data);
                        },
                        error: function() {
                            Swal.fire('Gagal', 'Gagal menambahkan mata pelajaran', 'error');
                            $('#subject_id').val(null).trigger('change');
                        }
                    });
                } else {
                    $('#subject_id').val(null).trigger('change');
                }
            });
        }
    });

    function applySubject(subject) {
        $.ajax({
            url: `{{ route('api.school.subject.index') }}`,
            type: 'GET',
            headers: {
                'Authorization': `Bearer {{ session('api_bearer') }}`,
            },
            success: function(response) {
                $('#subject_id').empty().append('<option value="" disabled selected>Pilih atau cari mata pelajaran</option><option value="" id="">Tambah Mata Pelajaran</option>');
                response.data.forEach(function(subject) {
                    let newOption = new Option(subject.name, subject.id, false, false);
                    $('#subject_id').append(newOption);
                });
                $('#subject_id').val(subject.id).trigger('change');
            },
            error: function() {
                Swal.fire('Gagal', 'Gagal memuat mata pelajaran', 'error');
            }
        });
    }

    $(document).ready(function() {
        $('#title, #description, #question_limit').on('input', function() {
            const fieldName = $(this).attr('name');
            $('#error-' + fieldName).text('');
            $(this).removeClass('is-invalid');
        });

        $(document).on('click', '[data-dropdown] .dropdown-option', function() {
            const dropdown = $(this).closest('[data-dropdown]');
            const hiddenInput = dropdown.find('input[type="hidden"]');
            const inputName = hiddenInput.attr('name');

            $('#error-' + inputName).text('');
            dropdown.find('.dropdown-selected').removeClass('is-invalid');
        });

        $('#formQuestionBank').on('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted');

            $('.invalid-feedback').text('');
            $('.is-invalid').removeClass('is-invalid');

            const btn = $('#btnSubmit');
            btn.prop('disabled', true);
            btn.find('.btn-text').addClass('d-none');
            btn.find('.spinner-border').removeClass('d-none');

            const formData = {
                title: $('#title').val(),
                subject_id: $('#subject_id').val(),
                level_class_id: $('#level_class_id').val(),
                description: $('#description').val(),
                question_limit: $('#question_limit').val(),
            };

            console.log('Data yang dikirim:', formData);

            $.ajax({
                url: '/api/school/question-bank',
                type: 'POST',
                headers: {
                    'Authorization': `Bearer {{ session('api_bearer') }}`,
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function(res) {
                    console.log('Success:', res);
                    Swal.fire('Berhasil', res.message, 'success')
                        .then(() => {
                            window.location.href =
                                "{{ route('school.question-bank.index') }}";
                        });
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                    console.log('Status:', xhr.status);
                    console.log('Response:', xhr.responseJSON);

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.data;
                        console.log('Validation errors:', errors);

                        $.each(errors, function(key, value) {
                            console.log('Error field:', key, 'Message:', value[0]);
                            $('#error-' + key).text(value[0]);

                            if (key === 'subject_id' || key === 'level_class_id') {
                                $('[name="' + key + '"]').closest(
                                    '.custom-dropdown').find(
                                    '.dropdown-selected').addClass('is-invalid');
                            } else {
                                $('#' + key).addClass('is-invalid');
                            }
                        });
                    } else {
                        Swal.fire('Gagal', xhr.responseJSON?.meta?.message ||
                            'Terjadi kesalahan', 'error');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false);
                    btn.find('.btn-text').removeClass('d-none');
                    btn.find('.spinner-border').addClass('d-none');
                }
            });
        });
    });
</script>
