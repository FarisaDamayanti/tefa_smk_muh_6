<script>
    $(document).ready(function() {
        function toast(type, msg) {
            if (window.toastr && toastr[type]) return toastr[type](msg);
            alert(msg);
        }

        $('#btn-download-all').on('click', async function() {
            const taskId = $(this).data('task-id');
            const token = $('meta[name="api-token"]').attr('content') || '';

            if (!taskId) return toast('error', 'Task ID tidak ditemukan.');
            if (!token) return toast('error', 'Token API kosong (session api_bearer belum ada).');

            const url = `/api/teacher/download-all-answers/${taskId}`;

            try {
                const res = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': '*/*'
                    }
                });

                if (!res.ok) {
                    let msg = `Gagal download (${res.status})`;

                    try {
                        const json = await res.json();
                        msg = json?.meta?.message || json?.message || msg;
                    } catch (_) {
                        const text = await res.text();
                        if (text) msg = text;
                    }

                    throw new Error(msg || `Gagal download (${res.status})`);
                }

                const blob = await res.blob();

                const disposition = res.headers.get('content-disposition') || '';
                let filename = '';
                const match = disposition.match(/filename\*?=(?:UTF-8'')?("?)([^";]+)\1/);

                if (match && match[2]) filename = decodeURIComponent(match[2]);
                if (!filename) filename = `task-submissions-${taskId}`;

                const blobUrl = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = blobUrl;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(blobUrl);

                toast('success', 'File berhasil diunduh.');
            } catch (err) {
                console.error(err);
                toast('error', err?.message || 'Download gagal.');
            }
        });
    });
</script>