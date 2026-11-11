<script>
    $(function() {

        const modalEl = document.getElementById('import-question-modal');
        if (!modalEl) return;

        const importModal = bootstrap.Modal.getOrCreateInstance(modalEl);

        const $form = $('#form-import-question');
        const $file = $('#import_excel_file');
        const $submit = $('#btn-import-submit');
        const $alert = $('#import-alert-container');

        const STORAGE_KEY = 'import_question_alert_html';

        const storedAlert = sessionStorage.getItem(STORAGE_KEY);
        if (storedAlert) {
            $alert.html(storedAlert);
            sessionStorage.removeItem(STORAGE_KEY);

            $('html, body').animate({
                scrollTop: $alert.offset().top - 120
            }, 300);
        }

        function escapeHtml(str) {
            return String(str ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function toggleImportSubmit() {
            $submit.prop('disabled', !$file.val());
        }

        const examTypeMapping = {
            multiple_choice: "Pilihan Ganda",
            matching: "Perjodohan",
            essay: "Uraian"
        }

        $(modalEl).on('hidden.bs.modal', function() {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            $('body').css({
                overflow: '',
                paddingRight: ''
            });
        });

        $('#btn-open-import-modal').on('click', function() {
            $form[0].reset();
            $submit.prop('disabled', true).text('Import');
            importModal.show();
        });

        $file.on('change', toggleImportSubmit);

        $form.on('submit', function(e) {
            e.preventDefault();

            $alert.html('');

            const fileVal = $file.val();

            if (!fileVal) {
                $alert.html(`
                <div class="alert alert-danger rounded-3">
                    Pilih file excel dulu.
                </div>
            `);
                importModal.hide();
                return;
            }

            const ext = (fileVal.split('.').pop() || '').toLowerCase();
            if (!['xls', 'xlsx'].includes(ext)) {
                $alert.html(`
                <div class="alert alert-danger rounded-3">
                    Format file harus .xls atau .xlsx.
                </div>
            `);
                importModal.hide();
                return;
            }

            const formData = new FormData($form[0]);
            const originalBtn = $submit.html();

            $submit.html(`
            <span class="spinner-border spinner-border-sm me-2"></span>
            Mengimpor...
        `).prop('disabled', true);

            $.ajax({
                type: "POST",
                url: $form.attr('action'),
                data: formData,
                headers: {
                    'Authorization': 'Bearer {{ session('api_bearer') }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                processData: false,
                contentType: false,

                success: function(response, _textStatus, jqXHR) {

                    const d = response?.data || {};
                    const successCount = d.success ?? 0;
                    const failedCount = d.failed ?? 0;
                    const errorsObj = d.errors || {};
                    const metaMsg = response?.meta?.message || response?.message || '';

                    const type = failedCount > 0 ? 'warning' : 'success';
                    const title = failedCount > 0 ?
                        'Import selesai (Sebagian gagal)' :
                        'Import berhasil';

                    let html = `
                    <div class="alert alert-${type} d-flex justify-content-between align-items-start gap-3 rounded-3 mb-0">
                        <div>
                            <div class="fw-semibold">${escapeHtml(title)}</div>
                            ${metaMsg ? `<div class="small mt-1">${escapeHtml(metaMsg)}</div>` : ''}
                            <div class="mt-2">
                                <span class="badge bg-success">Berhasil: ${successCount}</span>
                                <span class="badge bg-danger ms-2">Gagal: ${failedCount}</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                    // detail error baris
                    Object.entries(errorsObj).forEach(([type, rows]) => {
                        Object.entries(rows).forEach(([rowKey, messages]) => {
                            const rowNum = rowKey.replace('row_', '');
                            messages.forEach(msg => {
                                html += `
                                <div class="alert alert-danger rounded-3 mb-0"
                                    style="background:#FDE8E8;border:1px solid rgba(220,53,69,.25);color:#7A1E1E;">
                                    <span class="fw-semibold">
                                        ${escapeHtml(examTypeMapping[type])} - Baris ${escapeHtml(rowNum)}:
                                    </span>
                                    ${escapeHtml(msg)}
                                </div>
                                `;
                            });
                        });
                    });

                    sessionStorage.setItem(STORAGE_KEY, html);

                    $(modalEl).one('hidden.bs.modal', function() {
                        window.location.reload();
                    });

                    importModal.hide();
                },

                error: function(xhr) {

                    let msg = 'Terjadi kesalahan saat import.';

                    if (xhr.status === 401)
                        msg = 'Unauthorized. Token tidak valid atau belum login.';
                    else if (xhr.status === 422) {
                        const json = xhr.responseJSON || {};
                        const errors = json?.errors || json?.data || {};
                        const firstErr = Object.keys(errors).length ?
                            Object.values(errors)[0]?.[0] :
                            null;
                        msg = firstErr || json?.message || 'Validasi gagal.';
                    } else if(xhr.status === 403){
                        msg = 'Jumlah soal yang akan ditambah melebihi batas.';
                    }

                    $alert.html(`
                    <div class="alert alert-danger rounded-3">
                        ${escapeHtml(msg)}
                    </div>
                `);

                    importModal.hide();
                },

                complete: function() {
                    $submit.html(originalBtn);
                    toggleImportSubmit();
                }
            });

        });

        toggleImportSubmit();

    });
</script>
