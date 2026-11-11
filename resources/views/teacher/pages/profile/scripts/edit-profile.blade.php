@section('script')
<script>
    function setLoading(button, loading = true) {
        const btn = $(button);

        btn.prop('disabled', loading);
        btn.find('.btn-text').toggleClass('d-none', loading);
        btn.find('.spinner-border').toggleClass('d-none', !loading);
    }

    $('#profileForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        let btnSubmit = $('#btnSubmit');

        $('.error-text').text('');
        $('.form-control').removeClass('is-invalid');

        setLoading(btnSubmit, true);

        $.ajax({
            url: '/api/teacher/profile'
            , type: 'POST'
            , data: formData
            , processData: false
            , contentType: false
            , headers: {
                Authorization: 'Bearer ' + localStorage.getItem('teacher_token')
                , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
            , success: function(res) {
                Swal.fire({
                    icon: 'success'
                    , title: 'Berhasil'
                    , text: res.meta.message
                    , timer: 1500
                    , showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            }
            , error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.data;

                    $.each(errors, function(field, messages) {
                        let input = $('[name="' + field + '"]');

                        input.addClass('is-invalid');
                        input.closest('.mb-3, .d-flex')
                            .find('.error-text')
                            .first()
                            .text(messages[0]);
                    });
                } else {
                    Swal.fire('Error', 'Gagal menyimpan data', 'error');
                }
            }
            , complete: function() {
                setLoading(btnSubmit, false);
            }
        });
    });

    $('#btnRemove').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Hapus Foto Profil",
            text: "Anda yakin ingin menghapus foto profil?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal",
            reverseButtons: true
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('api.teacher.profile.delete-image') }}", 
                    type: 'PUT',
                    processData: false,
                    contentType: false,
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('teacher_token')
                        , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }, 
                    success: function(res) {
                        Swal.fire({
                            icon: 'success', 
                            title: 'Berhasil',
                            text: res.meta.message, 
                            timer: 1500, 
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message ?? 'Terjadi kesalahan'
                        });
                    }
                });
            }
        })
    });
</script>
@endsection
