<script>
    document.addEventListener('DOMContentLoaded', () => {

        let selectedExamId = null;

        document.querySelectorAll('.btn-delete-exam').forEach(btn => {
            btn.addEventListener('click', () => {
                selectedExamId = btn.dataset.id || null;
            });
        });

        const form = document.getElementById('exam-form');
        const modalEl = document.getElementById('exam-modal');
        if (!form || !modalEl) return;

        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')
                ?.getAttribute('content') || '';
        }

        function closeModal() {
            const instance = bootstrap.Modal.getInstance(modalEl) ||
                bootstrap.Modal.getOrCreateInstance(modalEl);

            instance.hide();

            // bersihkan backdrop kalau ada bug nyangkut
            document.body.classList.remove('modal-open');
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!selectedExamId) return;

            const url = `/api/school/exams/${selectedExamId}?_method=DELETE`;
            const token = `Bearer {{ session('api_bearer') }}`;
            const csrf = getCsrfToken();

            try {

                const res = await fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': token,
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                const ct = res.headers.get('content-type') || '';
                const bodyText = await res.text();
                const json = ct.includes('application/json') ?
                    JSON.parse(bodyText || '{}') :
                    null;

                if (res.status === 401) {
                    closeModal();
                    await Swal.fire('Unauthorized', 'Token tidak valid / belum login.', 'error');
                    return;
                }

                if (res.status === 419) {
                    closeModal();
                    await Swal.fire('Session Expired', json?.message || 'CSRF mismatch.', 'error');
                    return;
                }

                if (res.status === 409) {
                    closeModal();
                    await Swal.fire('Tidak Bisa Dihapus',
                        json?.meta?.message || json?.message || 'Ujian tidak bisa dihapus.',
                        'warning'
                    );
                    return;
                }

                if (!res.ok) {
                    closeModal();
                    await Swal.fire('Gagal',
                        json?.meta?.message || json?.message || 'Gagal menghapus ujian.',
                        'error'
                    );
                    return;
                }

                await Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: json?.meta?.message || json?.message || 'Ujian berhasil dihapus.',
                    timer: 1500,
                    showConfirmButton: false
                });

                window.location.reload();

            } catch (err) {

                await Swal.fire(
                    'Terjadi Kesalahan',
                    'Terjadi kesalahan saat menghapus ujian.',
                    'error'
                );

                console.error(err);

            } finally {

                closeModal();
                selectedExamId = null;
            }
        });

    });
</script>
