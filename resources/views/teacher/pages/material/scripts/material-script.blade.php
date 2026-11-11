@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownSelected = document.getElementById('dropdownSelected');
            const dropdownOptions = document.getElementById('dropdownOptions');
            const selectedText = dropdownSelected.querySelector('.selected-text');
            const selectedIcon = dropdownSelected.querySelector('.selected-icon');
            const hiddenInput = document.getElementById('jenisMateri');
            const options = dropdownOptions.querySelectorAll('.dropdown-option');

            dropdownSelected.addEventListener('click', function() {
                dropdownSelected.classList.toggle('active');
                dropdownOptions.classList.toggle('show');
            });

            options.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    const text = this.querySelector('.option-text').textContent.trim();
                    const icon = this.querySelector('svg');

                    selectedText.textContent = text;
                    hiddenInput.value = value;

                    clearError('jenisMateri');

                    if (icon) {
                        selectedIcon.innerHTML = icon.outerHTML;
                        selectedIcon.style.display = 'inline-flex';
                    } else {
                        selectedIcon.innerHTML = '';
                        selectedIcon.style.display = 'none';
                    }

                    options.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');

                    dropdownSelected.classList.remove('active');
                    dropdownOptions.classList.remove('show');

                    const jenisKontenSection = document.getElementById('jenisKontenSection');
                    const tautanSection = document.getElementById('tautanSection');
                    const isiMateriSection = document.getElementById('isiMateriSection');

                    document.getElementById('jenisKonten').value = '';
                    document.querySelector('[name="content"]').value = '';
                    document.querySelector('#tautanSection input').value = '';
                    quill.root.innerHTML = '';

                    const selectedTextKonten = document.querySelector(
                        '#dropdownSelectedKonten .selected-text');
                    const selectedIconKonten = document.querySelector('.selected-icon-konten');
                    selectedTextKonten.textContent = 'Pilih Konten File';
                    selectedTextKonten.classList.add('text-muted');
                    selectedIconKonten.innerHTML = '';
                    selectedIconKonten.style.display = 'none';

                    if (value === 'teks') {
                        isiMateriSection.style.display = 'block';
                        jenisKontenSection.style.display = 'none';
                        tautanSection.style.display = 'none';
                    } else if (value === 'tautan') {
                        jenisKontenSection.style.display = 'block';
                        tautanSection.style.display = 'block';
                        isiMateriSection.style.display = 'none';
                    } else {
                        isiMateriSection.style.display = 'none';
                        jenisKontenSection.style.display = 'none';
                        tautanSection.style.display = 'none';
                    }
                });
            });

            const dropdownSelectedKonten = document.getElementById('dropdownSelectedKonten');
            const dropdownOptionsKonten = document.getElementById('dropdownOptionsKonten');
            const selectedTextKonten = dropdownSelectedKonten.querySelector('.selected-text');
            const selectedIconKonten = dropdownSelectedKonten.querySelector('.selected-icon-konten');
            const hiddenInputKonten = document.getElementById('jenisKonten');
            const optionsKonten = dropdownOptionsKonten.querySelectorAll('.dropdown-option');

            dropdownSelectedKonten.addEventListener('click', function() {
                dropdownSelectedKonten.classList.toggle('active');
                dropdownOptionsKonten.classList.toggle('show');
            });

            optionsKonten.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    const text = this.querySelector('.option-text').textContent.trim();
                    const icon = this.querySelector('svg');

                    selectedTextKonten.textContent = text;
                    selectedTextKonten.classList.remove('text-muted');
                    dropdownSelectedKonten.classList.add('has-value');
                    hiddenInputKonten.value = value;

                    clearError('jenisKonten');

                    if (icon) {
                        selectedIconKonten.innerHTML = icon.outerHTML;
                        selectedIconKonten.style.display = 'inline-flex';
                    } else {
                        selectedIconKonten.innerHTML = '';
                        selectedIconKonten.style.display = 'none';
                    }

                    optionsKonten.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');

                    dropdownSelectedKonten.classList.remove('active');
                    dropdownOptionsKonten.classList.remove('show');
                });
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.custom-dropdown')) {
                    document.querySelectorAll('.dropdown-selected').forEach(dropdown => {
                        dropdown.classList.remove('active');
                    });
                    document.querySelectorAll('.dropdown-options').forEach(options => {
                        options.classList.remove('show');
                    });
                }
            });

            var quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'Tulis materi di sini...',
                modules: {
                    toolbar: [
                        [{
                            'header': [1, 2, 3, 4, 5, 6, false]
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        [{
                            'indent': '-1'
                        }, {
                            'indent': '+1'
                        }],
                        ['link', 'image', 'video'],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        [{
                            'align': []
                        }],
                        ['clean']
                    ]
                }
            });

            quill.on('text-change', function() {
                clearError('isiMateri');
            });

            function showError(fieldId, message) {
                clearError(fieldId);

                let targetElement;
                let errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback d-block';
                errorDiv.style.color = '#dc3545';
                errorDiv.style.fontSize = '0.875rem';
                errorDiv.style.marginTop = '0.25rem';
                errorDiv.textContent = message;
                errorDiv.setAttribute('data-error-for', fieldId);

                if (fieldId === 'judulMateri') {
                    targetElement = document.querySelector('input[placeholder="Masukkan judul materi"]');
                    targetElement.classList.add('is-invalid');
                    targetElement.style.borderColor = '#dc3545';
                    targetElement.parentNode.appendChild(errorDiv);
                } else if (fieldId === 'jenisMateri') {
                    targetElement = document.getElementById('dropdownSelected');
                    targetElement.style.borderColor = '#dc3545';
                    targetElement.parentNode.appendChild(errorDiv);
                } else if (fieldId === 'jenisKonten') {
                    targetElement = document.getElementById('dropdownSelectedKonten');
                    targetElement.style.borderColor = '#dc3545';
                    targetElement.parentNode.appendChild(errorDiv);
                } else if (fieldId === 'tautan') {
                    targetElement = document.querySelector('#tautanSection input');
                    targetElement.classList.add('is-invalid');
                    targetElement.style.borderColor = '#dc3545';
                    targetElement.parentNode.appendChild(errorDiv);
                } else if (fieldId === 'isiMateri') {
                    targetElement = document.querySelector('.ql-container');
                    targetElement.style.borderColor = '#dc3545';
                    document.getElementById('quill-editor').parentNode.appendChild(errorDiv);
                }
            }

            function clearError(fieldId) {
                const errorElement = document.querySelector(`[data-error-for="${fieldId}"]`);
                if (errorElement) {
                    errorElement.remove();
                }

                if (fieldId === 'judulMateri') {
                    const input = document.querySelector('input[placeholder="Masukkan judul materi"]');
                    input.classList.remove('is-invalid');
                    input.style.borderColor = '';
                } else if (fieldId === 'jenisMateri') {
                    document.getElementById('dropdownSelected').style.borderColor = '';
                } else if (fieldId === 'jenisKonten') {
                    document.getElementById('dropdownSelectedKonten').style.borderColor = '';
                } else if (fieldId === 'tautan') {
                    const input = document.querySelector('#tautanSection input');
                    input.classList.remove('is-invalid');
                    input.style.borderColor = '';
                } else if (fieldId === 'isiMateri') {
                    document.querySelector('.ql-container').style.borderColor = '';
                }
            }

            function isValidURL(string) {
                try {
                    const url = new URL(string);
                    return url.protocol === "http:" || url.protocol === "https:";
                } catch (_) {
                    return false;
                }
            }

            function validateForm() {
                let isValid = true;

                ['judulMateri', 'jenisMateri', 'jenisKonten', 'tautan', 'isiMateri'].forEach(clearError);

                const judulMateri = document.querySelector('input[placeholder="Masukkan judul materi"]').value
                    .trim();
                if (!judulMateri) {
                    showError('judulMateri', 'Judul materi wajib diisi');
                    isValid = false;
                } else if (judulMateri.length < 3) {
                    showError('judulMateri', 'Judul materi minimal 3 karakter');
                    isValid = false;
                } else if (judulMateri.length > 100) {
                    showError('judulMateri', 'Judul materi maksimal 100 karakter');
                    isValid = false;
                }

                const jenisMateri = document.getElementById('jenisMateri').value;
                if (!jenisMateri) {
                    showError('jenisMateri', 'Jenis materi wajib dipilih');
                    isValid = false;
                }

                if (jenisMateri === 'teks') {
                    const quillContent = quill.root.innerHTML;
                    const quillText = quill.getText().trim();

                    if (!quillText || quillText.length === 0 || quillContent === '<p><br></p>') {
                        showError('isiMateri', 'Isi materi wajib diisi');
                        isValid = false;
                    } else if (quillText.length < 10) {
                        showError('isiMateri', 'Isi materi minimal 10 karakter');
                        isValid = false;
                    }
                } else if (jenisMateri === 'tautan') {
                    const jenisKonten = document.getElementById('jenisKonten').value;
                    if (!jenisKonten) {
                        showError('jenisKonten', 'Jenis konten wajib dipilih');
                        isValid = false;
                    }

                    const tautan = document.querySelector('#tautanSection input').value.trim();
                    if (!tautan) {
                        showError('tautan', 'Tautan wajib diisi');
                        isValid = false;
                    } else if (!isValidURL(tautan)) {
                        showError('tautan', 'Format tautan tidak valid. Contoh: https://example.com');
                        isValid = false;
                    } else {
                        if (jenisKonten === 'youtube') {
                            const youtubePattern = /^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+/;
                            if (!youtubePattern.test(tautan)) {
                                showError('tautan', 'Tautan harus berupa link YouTube yang valid');
                                isValid = false;
                            }
                        }
                    }
                }

                return isValid;
            }

            const submitButton = document.querySelector('button.btn-primary');
            submitButton.addEventListener('click', async function(e) {
                e.preventDefault();

                if (!validateForm()) {
                    const firstError = document.querySelector('.invalid-feedback');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                    return;
                }

                const urlParams = new URLSearchParams(window.location.search);
                const topicId = urlParams.get('topic_id');

                if (!topicId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Topic ID tidak ditemukan. Silakan kembali ke halaman sebelumnya.',
                    });
                    return;
                }

                const jenisMateri = document.getElementById('jenisMateri').value;
                const formData = new FormData();

                /* ==========================
                 * WAJIB
                 * ========================== */
                formData.append(
                    'title',
                    document.querySelector('input[placeholder="Masukkan judul materi"]')
                    .value
                    .trim()
                );

                /* ==========================
                 * MATERI TEKS
                 * ========================== */
                if (jenisMateri === 'teks') {
                    formData.append('type', 'content');
                    formData.append('content', quill.root.innerHTML);
                }

                /* ==========================
                 * MATERI TAUTAN
                 * ========================== */
                if (jenisMateri === 'tautan') {
                    formData.append('type', 'link');
                    formData.append(
                        'link',
                        document.querySelector('#tautanSection input').value.trim()
                    );
                    formData.append(
                        'link_type',
                        document.getElementById('jenisKonten').value
                    );
                }


                const originalText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

                try {
                    const response = await fetch(`/api/teacher/topics/${topicId}/modules`, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: formData
                    });


                    const result = await response.json();

                    if (response.ok) {
                        sessionStorage.setItem('materialAdded', 'true');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: result.meta?.message || 'Materi berhasil ditambahkan',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = document.referrer || '/teacher/e-learning';
                        });
                    } else {
                        if (result.data && typeof result.data === 'object') {
                            let errorMessage = '';
                            Object.values(result.data).forEach(errors => {
                                if (Array.isArray(errors)) {
                                    errorMessage += errors.join('<br>') + '<br>';
                                }
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal!',
                                html: errorMessage || result.meta?.message ||
                                    'Terjadi kesalahan validasi',
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: result.meta?.message || 'Gagal menambahkan materi',
                            });
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menghubungi server',
                    });
                } finally {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            });

            document.querySelector('input[placeholder="Masukkan judul materi"]').addEventListener('input',
                function() {
                    clearError('judulMateri');
                });

            document.querySelector('#tautanSection input').addEventListener('input', function() {
                clearError('tautan');
            });
        });
    </script>
@endsection
