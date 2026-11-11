@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-id-ID.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('CREATE Script started - Debug Mode');

            let summernoteInstance = null;

            console.log('Checking elements...');
            console.log('dropdownSelected:', document.getElementById('dropdownSelected'));
            console.log('dropdownOptions:', document.getElementById('dropdownOptions'));
            console.log('dropdownSelectedKonten:', document.getElementById('dropdownSelectedKonten'));
            console.log('dropdownOptionsKonten:', document.getElementById('dropdownOptionsKonten'));

            const dropdownMaterial = {
                selected: document.getElementById('dropdownSelected'),
                options: document.getElementById('dropdownOptions'),
                hiddenInput: document.getElementById('jenisMateri'),
                optionsElements: null,
                selectedText: null,
                selectedIcon: null,

                init: function() {
                    console.log('Initializing Material Dropdown');

                    if (!this.selected || !this.options) {
                        console.error('Material dropdown elements not found!');
                        return;
                    }

                    this.optionsElements = this.options.querySelectorAll('.dropdown-option');
                    this.selectedText = this.selected.querySelector('.selected-text');

                    const selectedContent = this.selected.querySelector('.selected-content');
                    this.selectedIcon = this.selected.querySelector('.selected-icon');

                    if (!this.selectedIcon) {
                        this.selectedIcon = document.createElement('span');
                        this.selectedIcon.className = 'selected-icon';
                        this.selectedIcon.style.display = 'none';
                        selectedContent.insertBefore(this.selectedIcon, this.selectedText);
                    }

                    console.log('Material dropdown elements found:', {
                        selected: this.selected,
                        options: this.optionsElements.length,
                        selectedText: this.selectedText,
                        selectedIcon: this.selectedIcon
                    });

                    this.selected.addEventListener('click', (e) => this.toggleDropdown(e));
                    this.setupOptions();
                    this.setupClickOutside();
                },

                toggleDropdown: function(e) {
                    e.stopPropagation();
                    console.log('Toggling material dropdown');

                    dropdownContent.close();

                    this.selected.classList.toggle('active');
                    this.options.classList.toggle('show');
                },

                setupOptions: function() {
                    this.optionsElements.forEach(option => {
                        option.addEventListener('click', (e) => this.selectOption(e, option));
                    });
                },

                selectOption: function(e, option) {
                    e.stopPropagation();
                    const value = option.getAttribute('data-value');
                    const text = option.querySelector('.option-text').textContent.trim();
                    const icon = option.querySelector('svg');

                    console.log('Material selected:', value, text);

                    this.selectedText.textContent = text;
                    this.hiddenInput.value = value;

                    if (icon) {
                        this.selectedIcon.innerHTML = icon.outerHTML;
                        this.selectedIcon.style.display = 'inline-flex';
                    } else {
                        this.selectedIcon.innerHTML = '';
                        this.selectedIcon.style.display = 'none';
                    }

                    this.optionsElements.forEach(opt => opt.classList.remove('selected'));
                    option.classList.add('selected');

                    this.close();

                    clearError('jenisMateri');

                    this.toggleSections(value);
                },

                toggleSections: function(jenisMateri) {
                    console.log('Toggle sections for:', jenisMateri);

                    const jenisKontenSection = document.getElementById('jenisKontenSection');
                    const tautanSection = document.getElementById('tautanSection');
                    const isiMateriSection = document.getElementById('isiMateriSection');

                    if (jenisKontenSection) jenisKontenSection.style.display = 'none';
                    if (tautanSection) tautanSection.style.display = 'none';
                    if (isiMateriSection) isiMateriSection.style.display = 'none';

                    dropdownContent.reset();

                    if (jenisMateri === 'teks') {
                        if (isiMateriSection) {
                            isiMateriSection.style.display = 'block';
                            this.initializeSummernote();
                        }
                    } else if (jenisMateri === 'tautan') {
                        if (jenisKontenSection) jenisKontenSection.style.display = 'block';
                        if (tautanSection) tautanSection.style.display = 'block';
                    }
                },

                initializeSummernote: function() {
                    if (summernoteInstance) return;

                    const summernoteElement = document.getElementById('summernote-editor');
                    if (!summernoteElement) {
                        console.error('Summernote element not found!');
                        return;
                    }

                    console.log('Initializing Summernote editor');

                    $(summernoteElement).summernote({
                        blockquoteBreakingLevel: 2,
                        height: 300, 
                        placeholder: 'Tulis materi di sini...',
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                            ['fontname', ['fontname']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph', 'height']],
                            ['table', ['table']],
                            ['link', ['link']],
                            ['picture', ['picture']],
                            ['video', ['video']],
                            ['codeview', ['codeview']],
                            ['help', ['help']],
                            ['insert', ['ul', 'blockquote']]
                        ],
                        fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New',
                            'Helvetica', 'Impact',
                            'Lucida Grande', 'Tahoma', 'Times New Roman', 'Verdana'
                        ],
                        fontNamesIgnoreCheck: ['Arial', 'Arial Black', 'Comic Sans MS',
                            'Courier New', 'Helvetica',
                            'Impact', 'Lucida Grande', 'Tahoma', 'Times New Roman', 'Verdana'
                        ],
                        callbacks: {
                            onChange: function(contents, $editable) {
                                clearError('isiMateri');
                            },
                            onInit: function() {
                                console.log('Summernote initialized');
                            }
                        }
                    });

                    summernoteInstance = $(summernoteElement);
                    window.summernote = summernoteInstance;

                    console.log('Summernote initialized successfully');
                },

                close: function() {
                    this.selected.classList.remove('active');
                    this.options.classList.remove('show');
                },

                setupClickOutside: function() {
                    document.addEventListener('click', (e) => {
                        if (!e.target.closest('.custom-dropdown')) {
                            this.close();
                            dropdownContent.close();
                        }
                    });
                }
            };

            const dropdownContent = {
                selected: document.getElementById('dropdownSelectedKonten'),
                options: document.getElementById('dropdownOptionsKonten'),
                hiddenInput: document.getElementById('jenisKonten'),
                optionsElements: null,
                selectedText: null,
                selectedIcon: null,

                init: function() {
                    console.log('Initializing Content Dropdown');

                    if (!this.selected || !this.options) {
                        console.error('Content dropdown elements not found!');
                        return;
                    }

                    this.optionsElements = this.options.querySelectorAll('.dropdown-option');
                    this.selectedText = this.selected.querySelector('.selected-text');

                    const selectedContent = this.selected.querySelector('.selected-content');
                    this.selectedIcon = this.selected.querySelector('.selected-icon-konten');

                    if (!this.selectedIcon) {
                        this.selectedIcon = document.createElement('span');
                        this.selectedIcon.className = 'selected-icon-konten';
                        this.selectedIcon.style.display = 'none';
                        selectedContent.insertBefore(this.selectedIcon, this.selectedText);
                    }

                    console.log('Content dropdown elements found:', {
                        selected: this.selected,
                        options: this.optionsElements.length,
                        selectedText: this.selectedText,
                        selectedIcon: this.selectedIcon
                    });

                    this.selected.addEventListener('click', (e) => this.toggleDropdown(e));
                    this.setupOptions();
                },

                toggleDropdown: function(e) {
                    e.stopPropagation();
                    console.log('Toggling content dropdown');

                    dropdownMaterial.close();

                    this.selected.classList.toggle('active');
                    this.options.classList.toggle('show');
                },

                setupOptions: function() {
                    this.optionsElements.forEach(option => {
                        option.addEventListener('click', (e) => this.selectOption(e, option));
                    });
                },

                selectOption: function(e, option) {
                    e.stopPropagation();
                    const value = option.getAttribute('data-value');
                    const text = option.querySelector('.option-text').textContent.trim();
                    const icon = option.querySelector('svg');

                    console.log('Content selected:', value, text);

                    this.selectedText.textContent = text;
                    this.selectedText.classList.remove('text-muted');
                    this.selected.classList.add('has-value');
                    this.hiddenInput.value = value;

                    if (icon) {
                        this.selectedIcon.innerHTML = icon.outerHTML;
                        this.selectedIcon.style.display = 'inline-flex';
                    } else {
                        this.selectedIcon.innerHTML = '';
                        this.selectedIcon.style.display = 'none';
                    }

                    this.optionsElements.forEach(opt => opt.classList.remove('selected'));
                    option.classList.add('selected');

                    this.close();

                    clearError('jenisKonten');
                },

                reset: function() {
                    this.selectedText.textContent = 'Pilih Konten File';
                    this.selectedText.classList.add('text-muted');
                    this.selected.classList.remove('has-value');
                    this.hiddenInput.value = '';
                    this.selectedIcon.innerHTML = '';
                    this.selectedIcon.style.display = 'none';
                    this.optionsElements.forEach(opt => opt.classList.remove('selected'));
                    this.close();
                },

                close: function() {
                    this.selected.classList.remove('active');
                    this.options.classList.remove('show');
                }
            };

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

                switch (fieldId) {
                    case 'judulMateri':
                        targetElement = document.querySelector('input[placeholder="Masukkan judul materi"]');
                        if (targetElement) {
                            targetElement.classList.add('is-invalid');
                            targetElement.style.borderColor = '#dc3545';
                            targetElement.parentNode.appendChild(errorDiv);
                        }
                        break;

                    case 'jenisMateri':
                        targetElement = document.getElementById('dropdownSelected');
                        if (targetElement) {
                            targetElement.style.borderColor = '#dc3545';
                            targetElement.parentNode.appendChild(errorDiv);
                        }
                        break;

                    case 'jenisKonten':
                        targetElement = document.getElementById('dropdownSelectedKonten');
                        if (targetElement) {
                            targetElement.style.borderColor = '#dc3545';
                            targetElement.parentNode.appendChild(errorDiv);
                        }
                        break;

                    case 'tautan':
                        const tautanInput = document.querySelector('#tautanSection input');
                        if (tautanInput) {
                            tautanInput.classList.add('is-invalid');
                            tautanInput.style.borderColor = '#dc3545';
                            tautanInput.parentNode.appendChild(errorDiv);
                        }
                        break;

                    case 'isiMateri':
                        targetElement = document.querySelector('.note-editor.note-frame');
                        if (targetElement) {
                            targetElement.style.borderColor = '#dc3545';
                            const summernoteContainer = document.getElementById('isiMateriSection');
                            if (summernoteContainer) {
                                summernoteContainer.appendChild(errorDiv);
                            }
                        }
                        break;
                }
            }

            function clearError(fieldId) {
                const errorElement = document.querySelector(`[data-error-for="${fieldId}"]`);
                if (errorElement) {
                    errorElement.remove();
                }

                // Reset style
                switch (fieldId) {
                    case 'judulMateri':
                        const judulInput = document.querySelector('input[placeholder="Masukkan judul materi"]');
                        if (judulInput) {
                            judulInput.classList.remove('is-invalid');
                            judulInput.style.borderColor = '';
                        }
                        break;

                    case 'jenisMateri':
                        const jenisMateriDropdown = document.getElementById('dropdownSelected');
                        if (jenisMateriDropdown) {
                            jenisMateriDropdown.style.borderColor = '';
                        }
                        break;

                    case 'jenisKonten':
                        const jenisKontenDropdown = document.getElementById('dropdownSelectedKonten');
                        if (jenisKontenDropdown) {
                            jenisKontenDropdown.style.borderColor = '';
                        }
                        break;

                    case 'tautan':
                        const tautanInput = document.querySelector('#tautanSection input');
                        if (tautanInput) {
                            tautanInput.classList.remove('is-invalid');
                            tautanInput.style.borderColor = '';
                        }
                        break;

                    case 'isiMateri':
                        const summernoteContainer = document.querySelector('.note-editor.note-frame');
                        if (summernoteContainer) {
                            summernoteContainer.style.borderColor = '';
                        }
                        break;
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

                const judulInput = document.querySelector('input[placeholder="Masukkan judul materi"]');
                const judulMateri = judulInput ? judulInput.value.trim() : '';

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

                const jenisMateri = document.getElementById('jenisMateri')?.value || '';
                if (!jenisMateri) {
                    showError('jenisMateri', 'Jenis materi wajib dipilih');
                    isValid = false;
                }

                if (jenisMateri === 'teks') {
                    if (summernoteInstance) {
                        const summernoteContent = summernoteInstance.summernote('code');
                        const summernoteText = summernoteContent.replace(/<[^>]*>/g, '').trim();

                        if (!summernoteText || summernoteText.length === 0) {
                            showError('isiMateri', 'Isi materi wajib diisi');
                            isValid = false;
                        } else if (summernoteText.length < 10) {
                            showError('isiMateri', 'Isi materi minimal 10 karakter');
                            isValid = false;
                        }
                    }
                } else if (jenisMateri === 'tautan') {
                    const jenisKonten = document.getElementById('jenisKonten')?.value || '';
                    if (!jenisKonten) {
                        showError('jenisKonten', 'Jenis konten wajib dipilih');
                        isValid = false;
                    }

                    const tautanInput = document.querySelector('#tautanSection input');
                    const tautan = tautanInput ? tautanInput.value.trim() : '';

                    if (!tautan) {
                        showError('tautan', 'Tautan wajib diisi');
                        isValid = false;
                    } else if (!isValidURL(tautan)) {
                        showError('tautan', 'Format tautan tidak valid. Contoh: https://example.com');
                        isValid = false;
                    } else if (jenisKonten === 'youtube') {
                        const youtubePattern = /^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+/;
                        if (!youtubePattern.test(tautan)) {
                            showError('tautan', 'Tautan harus berupa link YouTube yang valid');
                            isValid = false;
                        }
                    }
                }

                return isValid;
            }

            function setupFormSubmission() {
                const submitButton = document.querySelector('button.btn-primary');
                if (!submitButton) {
                    console.error('Submit button not found!');
                    return;
                }

                submitButton.addEventListener('click', async function(e) {
                    e.preventDefault();

                    console.log('Submit button clicked');

                    if (!validateForm()) {
                        console.log('Validation failed');
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
                    const formData = {
                        title: document.querySelector('input[placeholder="Masukkan judul materi"]')
                            .value.trim(),
                        type: jenisMateri === 'teks' ? 'content' : 'link',
                    };

                    if (jenisMateri === 'teks') {
                        formData.content = summernoteInstance ? summernoteInstance.summernote('code') :
                            '';
                    } else {
                        formData.link = document.querySelector('#tautanSection input').value.trim();
                        formData.link_type = document.getElementById('jenisKonten').value;
                    }

                    console.log('Sending data:', formData);

                    const originalText = submitButton.innerHTML;
                    submitButton.disabled = true;
                    submitButton.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

                    try {
                        const response = await fetch(`/api/teacher/topics/${topicId}/modules`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]')?.getAttribute('content') ||
                                    ''
                            },
                            body: JSON.stringify(formData)
                        });

                        const result = await response.json();
                        console.log('Response:', result);

                        if (response.ok) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: result.meta?.message || 'Materi berhasil ditambahkan',
                                timer: 1500,
                                showConfirmButton: false,
                                willClose: () => {
                                    localStorage.setItem('material_created', 'true');
                                    localStorage.setItem('material_title', formData.title);

                                    const referrer = document.referrer;

                                    if (referrer && (referrer.includes(
                                                '/teacher/e-learning/') || referrer
                                            .includes('/teacher/dashboard'))) {
                                        const timestamp = new Date().getTime();
                                        const separator = referrer.includes('?') ? '&' :
                                            '?';
                                        window.location.href = referrer + separator +
                                            'refresh=' + timestamp;
                                    } else {
                                        window.location.href =
                                            '/teacher/e-learning?refresh=' + new Date()
                                            .getTime();
                                    }
                                }
                            });

                        } else {
                            let errorMessage = 'Gagal menambahkan materi';

                            if (result.data && typeof result.data === 'object') {
                                errorMessage = '';
                                Object.values(result.data).forEach(errors => {
                                    if (Array.isArray(errors)) {
                                        errorMessage += errors.join('<br>') + '<br>';
                                    }
                                });
                            } else if (result.meta?.message) {
                                errorMessage = result.meta.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                html: errorMessage,
                            });
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
            }

            function setupInputListeners() {
                const judulInput = document.querySelector('input[placeholder="Masukkan judul materi"]');
                if (judulInput) {
                    judulInput.addEventListener('input', function() {
                        clearError('judulMateri');
                    });
                }

                const tautanInput = document.querySelector('#tautanSection input');
                if (tautanInput) {
                    tautanInput.addEventListener('input', function() {
                        clearError('tautan');
                    });
                }
            }

            function initializeAll() {
                console.log('Initializing all components...');

                dropdownMaterial.init();
                dropdownContent.init();

                setupFormSubmission();

                setupInputListeners();

                console.log('All components initialized successfully');
            }

            initializeAll();
        });
    </script>
@endsection
