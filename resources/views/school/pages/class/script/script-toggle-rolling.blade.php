<script>
    @if (request()->has('search') || request()->has('rolling-search'))
        $('#minimize').removeClass('minimize')
    @endif
    $('.toggle-rolling').click(function() {
        $('#minimize').removeClass('minimize')
    });

    $('#close-rolling-button').click(function() {
        $('#minimize').addClass('minimize')
    });

    $('#unrolling-student').submit(function(e) {
        e.preventDefault();

        let name = $('#input-search-left').val();

        $.ajax({
            type: "get",
            url: "{{ route('school.class-student.doesntHaveClassroom', request()->classroom->id) }}",
            data: {
                'name': name
            },
            dataType: "json",
            success: function(response) {
                $('#left-table tbody').empty();

                if (response.data.length > 0) {
                    response.data.forEach(student => {
                        $('#left-table tbody').append(`
                        <tr data-id="${student.id}">
                            <td>${student.user.name}</td>
                            <td>${student.nisn}</td>
                            <td class="d-flex justify-content-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                </div>
                            </td>
                        </tr>
                        `);
                    });
                } else {
                    $('#left-table tbody').append(`
                    <tr class="empty-tr">
                        <td colspan="3" class="text-center align-middle">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt="" width="200px">
                                <p class="fs-5 text-dark text-center mt-2">
                                    Siswa belum ditambahkan
                                </p>
                            </div>
                        </td>
                    </tr>
                    `);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
</script>
