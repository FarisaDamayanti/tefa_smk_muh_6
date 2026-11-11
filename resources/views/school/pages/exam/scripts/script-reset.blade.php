<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ELEMENTS
        const checkAll = document.getElementById('check-all');
        const btnBulk = document.getElementById('btn-reset-bulk');

        const modalEl = document.getElementById('resetExamModal');
        const btnConfirm = document.getElementById('btn-confirm-reset');

        if (!modalEl || !btnConfirm) return;

        const modal = new bootstrap.Modal(modalEl);

        const modalTitle = modalEl.querySelector('h5');
        const modalDesc = modalEl.querySelector('p');

        let mode = null; 
        let currentUrl = null; 
        let bulkIds = []; 

        function toastSuccess(msg) {
            if (window.toastr) toastr.success(msg);
            else alert(msg);
        }

        function toastError(msg) {
            if (window.toastr) toastr.error(msg);
            else alert(msg);
        }

        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        }

        function getAllRowCheckboxes() {
            return Array.from(document.querySelectorAll('.row-checkbox'));
        }

        function getCheckedIds() {
            return Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
        }

        function updateBulkButton() {
            if (!btnBulk) return;

            const count = getCheckedIds().length;
            if (count >= 2) {
                btnBulk.classList.remove('d-none');
                btnBulk.textContent = `Reset (${count})`;
            } else {
                btnBulk.classList.add('d-none');
                btnBulk.textContent = 'Reset';
            }
        }

        function syncCheckAllState() {
            if (!checkAll) return;

            const boxes = getAllRowCheckboxes();
            const checked = getCheckedIds().length;

            if (boxes.length === 0) {
                checkAll.checked = false;
                checkAll.indeterminate = false;
                return;
            }

            checkAll.checked = (checked === boxes.length);
            checkAll.indeterminate = (checked > 0 && checked < boxes.length);
        }

        async function safeJson(res) {
            try {
                return await res.json();
            } catch {
                return null;
            }
        }

        function extractErrorMessage(data, fallback) {
            return (
                data?.data?.student_exam_ids?.[0] ||
                data?.meta?.message ||
                data?.message ||
                fallback ||
                'Request gagal.'
            );
        }

        async function postSingle(url) {
            const csrf = getCsrfToken();

            const res = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ session('api_bearer') }}',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const data = await safeJson(res);

            if (!res.ok) {
                throw new Error(extractErrorMessage(data, `Reset gagal. HTTP ${res.status}`));
            }

            return data;
        }

        async function postBulk(ids) {
            const csrf = getCsrfToken();
            const url = "{{ route('api.school.exams.reset-many') }}";

            const body = new URLSearchParams();
            ids.forEach((id, idx) => body.append(`student_exam_ids[${idx}]`, id));

            const res = await fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': 'Bearer {{ session('api_bearer') }}',
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: body.toString(),
            });

            const data = await safeJson(res);

            if (!res.ok) {
                throw new Error(extractErrorMessage(data, `Reset banyak gagal. HTTP ${res.status}`));
            }

            return data;
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                const boxes = getAllRowCheckboxes();
                boxes.forEach(cb => (cb.checked = checkAll.checked));
                checkAll.indeterminate = false;
                updateBulkButton();
            });
        }

        document.addEventListener('change', function(e) {
            if (!e.target?.classList?.contains('row-checkbox')) return;
            syncCheckAllState();
            updateBulkButton();
        });

        syncCheckAllState();
        updateBulkButton();

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-reset-exam');
            if (!btn) return;

            const url = btn.getAttribute('data-url');
            if (!url) return;

            mode = 'single';
            currentUrl = url;
            bulkIds = [];

            if (modalTitle) modalTitle.innerHTML = 'Apakah anda yakin ingin<br> mereset ujian ini?';
            if (modalDesc) modalDesc.textContent = 'Progres dan jawaban siswa akan dihapus permanen.';

            modal.show();
        });

        // OPEN MODAL: BULK
        if (btnBulk) {
            btnBulk.addEventListener('click', function() {
                const ids = getCheckedIds();
                if (ids.length < 2) {
                    toastError('Pilih minimal 2 siswa untuk reset.');
                    return;
                }

                mode = 'bulk';
                bulkIds = ids;
                currentUrl = null;

                if (modalTitle) modalTitle.innerHTML =
                    `Apakah anda yakin ingin<br> mereset ${ids.length} ujian?`;
                if (modalDesc) modalDesc.textContent =
                    'Progres dan jawaban siswa akan dihapus permanen.';

                modal.show();
            });
        }

        // CONFIRM
        btnConfirm.addEventListener('click', async function() {
            const oldText = btnConfirm.textContent;
            btnConfirm.disabled = true;
            btnConfirm.textContent = 'Memproses...';

            try {
                if (mode === 'single') {
                    if (!currentUrl) throw new Error('URL reset tidak ditemukan.');

                    const data = await postSingle(currentUrl);
                    toastSuccess(data?.meta?.message || 'Reset berhasil.');
                    modal.hide();
                    setTimeout(() => location.reload(), 600);
                    return;
                }

                if (mode === 'bulk') {
                    if (!bulkIds || bulkIds.length < 2) throw new Error('Pilih minimal 2 siswa.');

                    const data = await postBulk(bulkIds);
                    toastSuccess(data?.meta?.message || 'Reset berhasil.');
                    modal.hide();
                    setTimeout(() => location.reload(), 600);
                    return;
                }

                throw new Error('Mode reset tidak valid.');
            } catch (err) {
                toastError(err?.message || 'Terjadi kesalahan.');
            } finally {
                btnConfirm.disabled = false;
                btnConfirm.textContent = oldText;
            }
        });

        modalEl.addEventListener('hidden.bs.modal', function() {
            mode = null;
            currentUrl = null;
            bulkIds = [];
        });
    });
</script>
