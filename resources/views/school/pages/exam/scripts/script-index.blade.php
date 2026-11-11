<script>
    $('.export-button').on('click', function(e) {
        e.preventDefault();
        let button = $(this);

        button.prop('disabled', true);
        button.html(
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengekspor...'
        );

        let query = @json(request()->query());

        $.ajax({
            url: "{{ route('api.exams.export-all') }}",
            method: "GET",
            headers: {
                Authorzation: "Bearer {{ session('api_bearer') }}"
            },
            data: query,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response, status, xhr) {
                const contentType = xhr.getResponseHeader("Content-Type");

                if (contentType && contentType.includes("application/json")) {
                    const reader = new FileReader();
                    reader.onload = function () {
                        const json = JSON.parse(reader.result);
                        toastr.error(json.message || "Terjadi kesalahan.");
                    };
                    reader.readAsText(response);

                    return;
                }

                const today = new Date().toISOString().split('T')[0];
                const filename = `DAFTAR-NILAI-UJIAN-${today}`;

                const url = window.URL.createObjectURL(response);
                const link = document.createElement("a");
                link.href = url;
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                window.URL.revokeObjectURL(url);

                toastr.success("Berhasil export data");
            },
            error: function(xhr) {
                toastr.error("Gagal export data.");
            },
            complete: function() {
                button.prop('disabled', false);
                button.html('<i class="ti ti-download"></i> Ekspor Ujian');
            }
        });
    });

    $('#search').on('keyup', function() {
        let value = $(this).val();

        $('#search-modal').val(value);
    })
</script>