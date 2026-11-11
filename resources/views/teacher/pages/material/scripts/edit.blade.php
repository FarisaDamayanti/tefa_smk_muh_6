@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-id-ID.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const moduleId = '{{ $module->id ?? ($id ?? '') }}';
            let moduleData = null;
            let summernote = null; 
            let dropdownInstances = {};

            function extractYoutubeId(url) {
                const patterns = [
                    /(?:youtube\.com\/watch\?v=)([^&\s]+)/,
                    /(?:youtube\.com\/embed\/)([^?\s]+)/,
                    /(?:youtube\.com\/v\/)([^?\s]+)/,
                    /(?:youtu\.be\/)([^?\s]+)/,
                    /(?:youtube\.com\/live\/)([^?\s]+)/
                ];

                for (let pattern of patterns) {
                    const match = url.match(pattern);
                    if (match && match[1]) {
                        return match[1].split('?')[0].split('&')[0];
                    }
                }
                return null;
            }

            function isGoogleDriveLink(url) {
                return url.includes('drive.google.com') || url.includes('docs.google.com');
            }

            function extractGoogleDriveId(url) {
                const patterns = [
                    /\/d\/([a-zA-Z0-9_-]+)/,
                    /id=([a-zA-Z0-9_-]+)/,
                    /\/file\/d\/([a-zA-Z0-9_-]+)/
                ];

                for (let pattern of patterns) {
                    const match = url.match(pattern);
                    if (match && match[1]) {
                        return match[1];
                    }
                }
                return null;
            }

            function getFileTypeFromUrl(url) {
                if (url.includes('.pdf') || url.toLowerCase().includes('pdf')) return 'pdf';
                if (url.includes('.doc') || url.toLowerCase().includes('doc')) return 'word';
                if (url.includes('.xls') || url.toLowerCase().includes('xls')) return 'excel';
                if (url.includes('.ppt') || url.toLowerCase().includes('ppt') || url.includes('.pptx'))
                    return 'ppt';
                return 'other';
            }

            function initializeDropdown(dropdownId, optionsId, hiddenInputId) {
                const dropdownSelected = document.getElementById(dropdownId);
                const dropdownOptions = document.getElementById(optionsId);
                const hiddenInput = document.getElementById(hiddenInputId);

                if (!dropdownSelected || !dropdownOptions || !hiddenInput) {
                    console.error(`Dropdown element not found: ${dropdownId}`);
                    return null;
                }

                console.log(`Initializing dropdown: ${dropdownId}`);

                const newSelected = dropdownSelected.cloneNode(true);
                const newOptions = dropdownOptions.cloneNode(true);
                dropdownSelected.parentNode.replaceChild(newSelected, dropdownSelected);
                dropdownOptions.parentNode.replaceChild(newOptions, dropdownOptions);

                const currentSelected = document.getElementById(dropdownId);
                const currentOptions = document.getElementById(optionsId);
                const currentHidden = document.getElementById(hiddenInputId);

                currentSelected.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    currentOptions.classList.toggle('show');
                    currentSelected.classList.toggle('active');
                });

                currentOptions.querySelectorAll('.dropdown-option').forEach(option => {
                    option.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const value = this.getAttribute('data-value');

                        let text = '';
                        let icon = null;

                        const optionContent = this.querySelector('.option-content');
                        if (optionContent) {
                            const textElement = optionContent.querySelector('.option-text');
                            if (textElement) {
                                text = textElement.textContent;
                            }
                            icon = optionContent.querySelector('svg')?.cloneNode(true);
                        } else {
                            const textElement = this.querySelector('.option-text');
                            if (textElement) {
                                text = textElement.textContent;
                            }
                            icon = this.querySelector('svg')?.cloneNode(true);
                        }

                        const selectedIcon = currentSelected.querySelector(
                                '.selected-icon-konten') ||
                            currentSelected.querySelector('.selected-icon');
                        const selectedText = currentSelected.querySelector('.selected-text');

                        if (selectedIcon) {
                            selectedIcon.innerHTML = '';
                            if (icon) {
                                selectedIcon.appendChild(icon);
                            }
                        }

                        if (selectedText) {
                            selectedText.textContent = text;
                            selectedText.classList.remove('text-muted');
                        }

                        currentHidden.value = value;

                        currentOptions.classList.remove('show');
                        currentSelected.classList.remove('active');

                        currentHidden.dispatchEvent(new Event('change'));
                    });
                });

                document.addEventListener('click', function(e) {
                    if (!currentSelected.contains(e.target) && !currentOptions.contains(e.target)) {
                        if (currentOptions.classList.contains('show')) {
                            currentOptions.classList.remove('show');
                            currentSelected.classList.remove('active');
                        }
                    }
                });

                currentOptions.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                dropdownInstances[dropdownId] = {
                    selected: currentSelected,
                    options: currentOptions,
                    hidden: currentHidden
                };

                return dropdownInstances[dropdownId];
            }

            function setDropdownValue(dropdownId, optionsId, hiddenInputId, value) {
                const dropdownSelected = document.getElementById(dropdownId);
                const dropdownOptions = document.getElementById(optionsId);
                const hiddenInput = document.getElementById(hiddenInputId);

                if (!dropdownSelected || !dropdownOptions || !hiddenInput) return false;

                const optionToSelect = dropdownOptions.querySelector(`[data-value="${value}"]`);

                if (optionToSelect) {
                    let text = '';
                    let icon = null;

                    const optionContent = optionToSelect.querySelector('.option-content');
                    if (optionContent) {
                        const textElement = optionContent.querySelector('.option-text');
                        if (textElement) {
                            text = textElement.textContent;
                        }
                        icon = optionContent.querySelector('svg')?.cloneNode(true);
                    } else {
                        const textElement = optionToSelect.querySelector('.option-text');
                        if (textElement) {
                            text = textElement.textContent;
                        }
                        icon = optionToSelect.querySelector('svg')?.cloneNode(true);
                    }

                    const selectedIcon = dropdownSelected.querySelector('.selected-icon-konten') ||
                        dropdownSelected.querySelector('.selected-icon');
                    const selectedText = dropdownSelected.querySelector('.selected-text');

                    if (selectedIcon) {
                        selectedIcon.innerHTML = '';
                        if (icon) {
                            selectedIcon.appendChild(icon);
                        }
                    }

                    if (selectedText) {
                        selectedText.textContent = text;
                        selectedText.classList.remove('text-muted');
                    }

                    hiddenInput.value = value;

                    setTimeout(() => {
                        hiddenInput.dispatchEvent(new Event('change'));
                    }, 100);

                    return true;
                }

                console.warn(`Option with value "${value}" not found in dropdown ${dropdownId}`);
                return false;
            }

            function initializeSummernote() {
                if (!summernote) {
                    const summernoteElement = document.getElementById('summernote-editor');

                    if (!summernoteElement) {
                        console.error('Summernote element not found!');
                        return;
                    }

                    console.log('Initializing Summernote editor');

                    summernote = $(summernoteElement).summernote({
                        blockquoteBreakingLevel: 2,
                        height: 400,
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
                        fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica',
                            'Impact',
                            'Lucida Grande', 'Tahoma', 'Times New Roman', 'Verdana'
                        ],
                        fontNamesIgnoreCheck: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New',
                            'Helvetica',
                            'Impact', 'Lucida Grande', 'Tahoma', 'Times New Roman', 'Verdana'
                        ],
                        callbacks: {
                            onChange: function(contents, $editable) {
                                updatePreview();
                            },
                            onInit: function() {
                                console.log('Summernote initialized');
                            }
                        }
                    });

                    console.log('Summernote initialized successfully');
                }
            }

            async function loadData() {
                try {
                    console.log('Loading data for module:', moduleId);

                    const response = await fetch(`/api/teacher/modules/${moduleId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const result = await response.json();

                    if (!response.ok || !result.data) {
                        throw new Error('Gagal mengambil data');
                    }

                    moduleData = result.data;
                    setupForm();

                } catch (error) {
                    console.error('Error loading data:', error);
                    Swal.fire('Error', 'Gagal memuat data materi', 'error');
                }
            }

            function setupForm() {
                console.log('Setting up form with data:', moduleData);

                initializeSummernote();

                setTimeout(() => {
                    initializeDropdown('dropdownSelected', 'dropdownOptions', 'editTypeDropdown');
                    initializeDropdown('linkTypeDropdownSelected', 'linkTypeDropdownOptions',
                        'linkTypeDropdown');
                }, 100);

                const titleInput = document.getElementById('material-title-edit');
                if (titleInput && moduleData.title) {
                    titleInput.value = moduleData.title;
                }

                const isTextEditor = moduleData.link_type === 'editor';
                const editTypeValue = isTextEditor ? 'teks' : 'link';

                console.log('Content type:', {
                    original: moduleData.link_type,
                    isTextEditor,
                    editTypeValue
                });

                setTimeout(() => {
                    console.log('Setting jenis materi dropdown to:', editTypeValue);
                    const success = setDropdownValue('dropdownSelected', 'dropdownOptions',
                        'editTypeDropdown', editTypeValue);
                    console.log('Set jenis materi dropdown:', success ? 'Success' : 'Failed');
                }, 200);

                // Set content
                if (isTextEditor) {
                    console.log('Setting up text editor mode');
                    if (summernote && moduleData.content) {
                        setTimeout(() => {
                            summernote.summernote('code', moduleData.content);
                            console.log('Summernote content loaded');
                        }, 300);
                    }
                    document.getElementById('textEditorSection').style.display = 'block';
                    document.getElementById('linkEditSection').style.display = 'none';
                } else {
                    // LINK MODE
                    console.log('Setting up link mode');
                    setTimeout(() => {
                        if (moduleData.link_type) {
                            let linkTypeValue = moduleData.link_type;
                            const typeMapping = {
                                'powerpoint': 'ppt',
                                'lainnya': 'other',
                                'youtube': 'youtube',
                                'pdf': 'pdf',
                                'word': 'word',
                                'excel': 'excel'
                            };

                            if (typeMapping[linkTypeValue]) {
                                linkTypeValue = typeMapping[linkTypeValue];
                            }

                            console.log('🔧 Setting link type dropdown to:', linkTypeValue);
                            const success = setDropdownValue('linkTypeDropdownSelected',
                                'linkTypeDropdownOptions',
                                'linkTypeDropdown', linkTypeValue);
                            console.log('Set link type dropdown:', success ? 'Success' : 'Failed');
                        }

                        // Set link URL
                        const linkUrlInput = document.getElementById('linkUrlInput');
                        if (linkUrlInput && moduleData.link) {
                            linkUrlInput.value = moduleData.link;
                            console.log('Link URL set:', moduleData.link);
                        }
                    }, 300);

                    document.getElementById('textEditorSection').style.display = 'none';
                    document.getElementById('linkEditSection').style.display = 'block';
                }

                setTimeout(() => {
                    updatePreview();
                    setupEventListeners();
                    console.log('Form setup completed');
                }, 500);
            }

            function setupEventListeners() {
                console.log('Setting up event listeners');

                const titleInput = document.getElementById('material-title-edit');
                if (titleInput) {
                    titleInput.addEventListener('input', updatePreview);
                }

                const editTypeDropdown = document.getElementById('editTypeDropdown');
                if (editTypeDropdown) {
                    editTypeDropdown.addEventListener('change', function() {
                        console.log('Jenis materi changed to:', this.value);
                        const value = this.value;

                        if (value === 'teks') {
                            document.getElementById('textEditorSection').style.display = 'block';
                            document.getElementById('linkEditSection').style.display = 'none';
                        } else if (value === 'link') {
                            document.getElementById('textEditorSection').style.display = 'none';
                            document.getElementById('linkEditSection').style.display = 'block';
                        }
                        updatePreview();
                    });
                }

                const linkTypeDropdown = document.getElementById('linkTypeDropdown');
                if (linkTypeDropdown) {
                    linkTypeDropdown.addEventListener('change', updatePreview);
                }

                const linkUrlInput = document.getElementById('linkUrlInput');
                if (linkUrlInput) {
                    linkUrlInput.addEventListener('input', updatePreview);
                }
            }

            function updatePreview() {
                console.log('Updating preview...');

                const titleInput = document.getElementById('material-title-edit');
                const previewTitle = document.getElementById('preview-title');

                if (titleInput && previewTitle) {
                    const title = titleInput.value.trim();
                    previewTitle.innerHTML = title ?
                        title :
                        '<em class="text-muted">Judul akan muncul di sini...</em>';
                }

                const editTypeDropdown = document.getElementById('editTypeDropdown');
                const previewContent = document.getElementById('preview-content');

                if (!editTypeDropdown || !previewContent) return;

                const contentType = editTypeDropdown.value;
                console.log('Content type for preview:', contentType);

                if (contentType === 'teks') {
                    const content = summernote ? summernote.summernote('code') : '';
                    console.log('Summernote content length:', content.length);

                    if (content && content.trim() !== '' && content !== '<p><br></p>') {
                        previewContent.innerHTML = `<div class="note-editable">${content}</div>`;
                    } else {
                        previewContent.innerHTML =
                            '<div class="text-center py-5"><em class="text-muted">Konten editor akan muncul di sini...</em></div>';
                    }
                } else if (contentType === 'link') {
                    const linkTypeDropdown = document.getElementById('linkTypeDropdown');
                    const linkUrlInput = document.getElementById('linkUrlInput');

                    if (!linkUrlInput || !linkTypeDropdown) return;

                    const linkType = linkTypeDropdown.value;
                    const linkUrl = linkUrlInput.value.trim();

                    console.log('Link preview data:', {
                        linkType,
                        linkUrl
                    });

                    if (!linkUrl) {
                        previewContent.innerHTML =
                            '<div class="text-center py-5"><em class="text-muted">Masukkan URL untuk melihat preview...</em></div>';
                        return;
                    }

                    let previewHtml = '';
                    if (linkType === 'youtube') {
                        console.log('Generating YouTube preview');
                        previewHtml = getYouTubePreview(linkUrl);
                    } else if (isGoogleDriveLink(linkUrl)) {
                        console.log('Generating Google Drive preview');
                        previewHtml = getGoogleDrivePreview(linkUrl, linkType);
                    } else if (linkType === 'pdf' || getFileTypeFromUrl(linkUrl) === 'pdf') {
                        console.log('Generating PDF preview');
                        previewHtml = getPDFPreview(linkUrl);
                    } else if (['word', 'excel', 'ppt', 'other'].includes(linkType)) {
                        console.log('Generating office file preview for:', linkType);
                        previewHtml = getOfficeFilePreview(linkUrl, linkType);
                    } else {
                        console.log('Generating other link preview');
                        previewHtml = getOtherLinkPreview(linkUrl);
                    }

                    previewContent.innerHTML = previewHtml;
                } else {
                    console.log('No content type selected');
                    previewContent.innerHTML =
                        '<div class="text-center py-5"><em class="text-muted">Pilih jenis materi untuk melihat preview...</em></div>';
                }
            }

            function getYouTubePreview(url) {
                const videoId = extractYoutubeId(url);
                console.log('YouTube URL:', url, 'Video ID:', videoId);

                if (videoId) {
                    return `
                    <div class="preview-iframe-container">
                        <iframe 
                            src="https://www.youtube.com/embed/${videoId}?rel=0&modestbranding=1"
                            title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                        ></iframe>
                    </div>
                    <div class="mt-2 text-muted small">
                        <i class="fas fa-info-circle me-1"></i>Preview video YouTube
                    </div>
                `;
                } else {
                    return `
                    <div class="preview-error">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Format URL YouTube tidak valid
                    </div>
                `;
                }
            }

            function getGoogleDrivePreview(url, linkType) {
                const driveId = extractGoogleDriveId(url);
                const fileType = linkType !== 'other' ? linkType : getFileTypeFromUrl(url);

                console.log('Google Drive data:', {
                    url,
                    driveId,
                    fileType
                });

                let embedUrl = '';
                if (driveId) {
                    embedUrl = `https://drive.google.com/file/d/${driveId}/preview`;
                } else {
                    if (url.includes('/document/')) {
                        embedUrl = url.replace('/edit', '/preview').replace('/view', '/preview');
                    } else if (url.includes('/spreadsheets/')) {
                        embedUrl = url.replace('/edit', '/preview').replace('/view', '/preview');
                    } else if (url.includes('/presentation/')) {
                        embedUrl = url.replace('/edit', '/preview').replace('/view', '/preview');
                    } else {
                        embedUrl = url;
                    }
                }

                return `
                <div class="preview-iframe-container">
                    <iframe 
                        src="${embedUrl}"
                        frameborder="0"
                        allowfullscreen
                    ></iframe>
                </div>
                <div class="mt-2 text-muted small">
                    <i class="fab fa-google-drive me-1"></i>Preview Google Drive ${getFileTypeName(fileType)}
                </div>
                <div class="preview-alert mt-2">
                    <i class="fas fa-info-circle me-2"></i>
                    Pastikan file dapat diakses publik untuk preview.
                </div>
            `;
            }

            function getPDFPreview(url) {
                return `
                <div class="preview-iframe-container">
                    <iframe 
                        src="https://docs.google.com/viewer?url=${encodeURIComponent(url)}&embedded=true"
                        frameborder="0"
                        allowfullscreen
                    ></iframe>
                </div>
                <div class="mt-2 text-muted small">
                    <i class="fas fa-file-pdf me-1"></i>Preview PDF Document
                </div>
            `;
            }

            function getOfficeFilePreview(url, fileType) {
                if (fileType === 'other') {
                    return getOtherLinkPreview(url);
                }

                return `
                <div class="preview-iframe-container">
                    <iframe 
                        src="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(url)}"
                        frameborder="0"
                        allowfullscreen
                    ></iframe>
                </div>
                <div class="mt-2 text-muted small">
                    <i class="fas fa-file-${fileType === 'word' ? 'word' : fileType === 'excel' ? 'excel' : 'powerpoint'} me-1"></i>
                    Preview ${getFileTypeName(fileType)}
                </div>
                <div class="preview-alert mt-2">
                    <i class="fas fa-info-circle me-2"></i>
                    Pastikan file dapat diakses publik untuk preview.
                </div>
            `;
            }

            function getOtherLinkPreview(url) {
                return `
                <div class="preview-file">
                    <i class="fas fa-link fa-4x link-icon"></i>
                    <h5 class="mt-3">Tautan Eksternal</h5>
                    <p class="text-muted">File akan dibuka di tab baru</p>
                    <div class="link-display mt-3">
                        <small>${url}</small>
                    </div>
                    <div class="drive-actions mt-3">
                        <a href="${url}" target="_blank" class="btn btn-primary btn-sm">
                         Buka Link
                        </a>
                    </div>
                </div>
            `;
            }

            function getFileTypeName(fileType) {
                const types = {
                    'pdf': 'PDF',
                    'word': 'Word',
                    'excel': 'Excel',
                    'ppt': 'PowerPoint',
                    'other': 'File',
                    'youtube': 'YouTube'
                };
                return types[fileType] || 'File';
            }

            async function saveData() {
                try {
                    const titleInput = document.getElementById('material-title-edit');
                    const editTypeDropdown = document.getElementById('editTypeDropdown');

                    if (!titleInput || !editTypeDropdown) return;

                    const title = titleInput.value.trim();
                    const contentType = editTypeDropdown.value;

                    console.log('Saving data:', {
                        title,
                        contentType
                    });

                    if (!title) {
                        Swal.fire('Peringatan', 'Judul materi wajib diisi', 'warning');
                        return;
                    }

                    if (!contentType) {
                        Swal.fire('Peringatan', 'Jenis materi wajib dipilih', 'warning');
                        return;
                    }

                    const formData = {
                        title: title,
                        type: contentType === 'teks' ? 'content' : 'link'
                    };

                    if (contentType === 'teks') {
                        const summernoteContent = summernote ? summernote.summernote('code') : '';
                        const summernoteText = summernoteContent.replace(/<[^>]*>/g, '').trim();

                        console.log('Summernote content length:', summernoteText.length);

                        if (!summernoteText || summernoteText.length < 10) {
                            Swal.fire('Peringatan', 'Isi materi minimal 10 karakter', 'warning');
                            return;
                        }

                        formData.content = summernoteContent;
                    } else {
                        const linkTypeDropdown = document.getElementById('linkTypeDropdown');
                        const linkUrlInput = document.getElementById('linkUrlInput');

                        if (!linkTypeDropdown || !linkUrlInput) return;

                        const linkType = linkTypeDropdown.value;
                        const linkUrl = linkUrlInput.value.trim();

                        console.log('Link data:', {
                            linkType,
                            linkUrl
                        });

                        if (!linkType) {
                            Swal.fire('Peringatan', 'Jenis konten wajib dipilih', 'warning');
                            return;
                        }

                        if (!linkUrl) {
                            Swal.fire('Peringatan', 'URL tautan wajib diisi', 'warning');
                            return;
                        }

                        try {
                            new URL(linkUrl);
                        } catch {
                            Swal.fire('Peringatan', 'Format URL tidak valid. Contoh: https://example.com',
                                'warning');
                            return;
                        }

                        if (linkType === 'youtube') {
                            const youtubePattern = /^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+/;
                            if (!youtubePattern.test(linkUrl)) {
                                Swal.fire('Peringatan', 'URL harus berupa link YouTube yang valid',
                                    'warning');
                                return;
                            }
                        }

                        formData.link = linkUrl;
                        formData.link_type = linkType;
                    }

                    console.log('Form data to save:', formData);

                    const saveBtn = document.getElementById('saveBtn');
                    if (saveBtn) {
                        saveBtn.disabled = true;
                        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
                    }

                    const response = await fetch(`/api/teacher/modules/${moduleId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(formData)
                    });

                    const result = await response.json();
                    console.log('Save response:', result);

                    if (!response.ok) {
                        if (response.status === 422) {
                            let errorMessages = [];
                            if (result.errors) {
                                Object.values(result.errors).forEach(errors => {
                                    errorMessages = errorMessages.concat(errors);
                                });
                            } else if (result.data) {
                                Object.values(result.data).forEach(errors => {
                                    if (Array.isArray(errors)) {
                                        errorMessages = errorMessages.concat(errors);
                                    } else {
                                        errorMessages.push(errors);
                                    }
                                });
                            }
                            throw new Error('Validasi gagal: ' + errorMessages.join(', '));
                        }
                        throw new Error(result.meta?.message || 'Gagal menyimpan');
                    }

                    await Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: result.meta?.message || 'Materi berhasil diperbarui',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    moduleData = result.data || formData;
                    console.log('Data saved successfully');

                } catch (error) {
                    console.error('Error saving:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal!',
                        html: `<div style="text-align: left;">
                        <p><strong>Error:</strong> ${error.message}</p>
                        <p class="text-muted small mt-2">Pastikan semua field diisi dengan benar</p>
                    </div>`
                    });
                } finally {
                    const saveBtn = document.getElementById('saveBtn');
                    if (saveBtn) {
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = 'Simpan';
                    }
                }
            }

            const saveBtn = document.getElementById('saveBtn');
            if (saveBtn) {
                saveBtn.addEventListener('click', saveData);
            }

            console.log('Initializing edit material page...');
            loadData();
        });

        function goBack() {
            const currentReferrer = document.referrer;

            if (currentReferrer && currentReferrer.includes('/teacher/e-learning/')) {
                window.location.href = currentReferrer;
            } else {
                window.location.href = '/teacher/e-learning';
            }
        }
    </script>
@endsection
