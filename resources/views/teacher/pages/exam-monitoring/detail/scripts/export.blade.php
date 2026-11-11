<script>
    $('#export-button').on('click', function(e) {
        e.preventDefault();
        let button = $(this);

        button.prop('disabled', true);
        button.html(
            '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengekspor...'
        );

        $.ajax({
            url: "{{ route('api.exams.export', ['exam' => $exam->id, 'classroom' => $classroom->id]) }}",
            method: "GET",
            headers: {
                Authorzation: "Bearer {{ session('api_bearer') }}"
            },
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

                const filename = `{{ $exam->title }}`;

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
                button.html(`
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 48 48">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="4">
                            <path stroke-linejoin="round"
                                d="M8 15V6a2 2 0 0 1 2-2h28a2 2 0 0 1 2 2v36a2 2 0 0 1-2 2H10a2 2 0 0 1-2-2v-9" />
                            <path d="M31 15h3m-6 8h6m-6 8h6" />
                            <path stroke-linejoin="round" d="M4 15h18v18H4zm6 6l6 6m0-6l-6 6" />
                        </g>
                    </svg>
                    Ekspor Ujian
                `);
            }
        });
    });
</script>