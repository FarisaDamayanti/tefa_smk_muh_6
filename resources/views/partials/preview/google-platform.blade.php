@extends('teacher.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/teacherpresence.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/dropdown/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/material.css') }}">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Tombol Kembali -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-warning" onclick="window.history.back()">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </button>
                </div>
            </div>
        </div>

        <!-- Konten Utama -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <!-- Header -->
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-0 text-white" id="googleTitle">
                                    Preview Materi - Google Workspace
                                </h5>
                            </div>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4">
                        <!-- Preview Area -->
                        <div id="googlePreviewArea">
                            <!-- Default empty state -->
                            <div class="text-center py-5" id="emptyPreview">
                                <div class="mb-4">
                                    <i class="fab fa-google-drive fa-5x text-primary"></i>
                                </div>
                                <h5 class="text-muted mb-3">Belum ada konten Google</h5>
                                <p class="text-muted mb-4">
                                    Upload link Google Docs/Sheets/Slides untuk melihat preview
                                </p>
                                <button class="btn btn-primary" onclick="uploadGoogleContent()">
                                    <i class="fas fa-plus me-1"></i> Tambah Konten Google
                                </button>
                            </div>
                            
                            <!-- Google Content Preview -->
                            <div id="googleContentPreview" style="display: none;">
                                <!-- Content will be loaded here -->
                            </div>
                        </div>

                        <!-- Google Apps Navigation -->
                        <div class="row justify-content-center mt-4">
                            <div class="col-auto">
                                <button class="btn btn-app" onclick="loadGoogleContent('docs')" data-type="docs">
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto mb-2"
                                        style="width: 50px; height: 50px;">
                                        <i class="fas fa-file-word text-white"></i>
                                    </div>
                                    <small>Docs</small>
                                </button>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-app" onclick="loadGoogleContent('sheets')" data-type="sheets">
                                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center mx-auto mb-2"
                                        style="width: 50px; height: 50px;">
                                        <i class="fas fa-file-excel text-white"></i>
                                    </div>
                                    <small>Sheets</small>
                                </button>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-app" onclick="loadGoogleContent('slides')" data-type="slides">
                                    <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center mx-auto mb-2"
                                        style="width: 50px; height: 50px;">
                                        <i class="fas fa-file-powerpoint text-white"></i>
                                    </div>
                                    <small>Slides</small>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Upload Google Content -->
    <div class="modal fade" id="googleUploadModal" tabindex="-1" aria-labelledby="googleUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="googleUploadModalLabel">Upload Google Workspace Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="googleUploadForm">
                        <div class="mb-3">
                            <label for="googleType" class="form-label">Jenis Konten</label>
                            <select class="form-select" id="googleType" required>
                                <option value="">Pilih jenis konten</option>
                                <option value="docs">Google Docs</option>
                                <option value="sheets">Google Sheets</option>
                                <option value="slides">Google Slides</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="googleTitleInput" class="form-label">Judul Konten</label>
                            <input type="text" class="form-control" id="googleTitleInput" 
                                   placeholder="Contoh: Materi Matematika - Teori" required>
                        </div>
                        <div class="mb-3">
                            <label for="googleUrl" class="form-label">Google Embed URL</label>
                            <input type="url" class="form-control" id="googleUrl" 
                                   placeholder="https://docs.google.com/document/d/.../pub?embedded=true" required>
                            <div class="form-text">
                                Cara mendapatkan embed URL:
                                <ol class="small">
                                    <li>Buka file Google di browser</li>
                                    <li>Klik File → Share → Publish to web</li>
                                    <li>Pilih "Embed" dan copy link yang diberikan</li>
                                </ol>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveGoogleContent()">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Database dummy untuk Google Content
        const googleContentDB = {
            docs: [],
            sheets: [],
            slides: []
        };

        // Data dummy untuk preview
        const dummyGoogleContent = {
            docs: [
                {
                    id: 1,
                    title: "Materi Matematika - Teori Phytagoras",
                    url: "https://docs.google.com/document/d/e/2PACX-1vT_bx3qjD36m69Cx4TrCYIkgrC8N9yF-kfEePVUOHvqj-D-pF6V-EDUgjBkRfJNVGyWbw5E2VXgyPq/pub?embedded=true",
                    description: "Teori dasar dan rumus Phytagoras",
                    date: "2024-01-15"
                },
                {
                    id: 2,
                    title: "Laporan Praktikum Fisika",
                    url: "https://docs.google.com/document/d/e/2PACX-1vR_WjY0B0Y3P8ZQK4z8mN9yF-kfEePVUOHvqj-D-pF6V-EDUgjBkRfJNVGyWbw5E2VXgyPq/pub?embedded=true",
                    description: "Laporan hasil praktikum fisika dasar",
                    date: "2024-01-10"
                }
            ],
            sheets: [
                {
                    id: 1,
                    title: "Data Nilai Siswa",
                    url: "https://docs.google.com/spreadsheets/d/e/2PACX-1vQ_9kYQH8QJ4M9GtY3P8ZQK4z8mN9yF-kfEePVUOHvqj-D-pF6V-EDUgjBkRfJNVGyWbw5E2VXgyPq/pubhtml?widget=true&amp;headers=false",
                    description: "Spreadsheet berisi nilai siswa semester 1",
                    date: "2024-01-12"
                }
            ],
            slides: [
                {
                    id: 1,
                    title: "Presentasi Sejarah Indonesia",
                    url: "https://docs.google.com/presentation/d/e/2PACX-1vT_bx3qjD36m69Cx4TrCYIkgrC8N9yF-kfEePVUOHvqj-D-pF6V-EDUgjBkRfJNVGyWbw5E2VXgyPq/embed?start=false&loop=false&delayms=3000",
                    description: "Slide presentasi materi sejarah kemerdekaan",
                    date: "2024-01-08"
                }
            ]
        };

        // Inisialisasi data dummy
        Object.assign(googleContentDB, dummyGoogleContent);

        let currentContentType = '';
        let currentContentId = null;

        // Fungsi untuk membuka modal upload
        function uploadGoogleContent() {
            const modal = new bootstrap.Modal(document.getElementById('googleUploadModal'));
            modal.show();
        }

        // Fungsi untuk menyimpan konten Google
        function saveGoogleContent() {
            const type = document.getElementById('googleType').value;
            const title = document.getElementById('googleTitleInput').value;
            const url = document.getElementById('googleUrl').value;
            
            if (!type || !title || !url) {
                alert('Harap lengkapi semua field!');
                return;
            }

            // Generate ID baru
            const newId = googleContentDB[type].length > 0 
                ? Math.max(...googleContentDB[type].map(item => item.id)) + 1 
                : 1;

            // Tambahkan ke database
            const newContent = {
                id: newId,
                title: title,
                url: url,
                description: `Konten ${type} - ${title}`,
                date: new Date().toISOString().split('T')[0]
            };

            googleContentDB[type].push(newContent);

            // Tutup modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('googleUploadModal'));
            modal.hide();

            // Reset form
            document.getElementById('googleUploadForm').reset();

            // Load konten yang baru ditambahkan
            loadGoogleContent(type, newId);
            
            showNotification('Konten Google berhasil ditambahkan!', 'success');
        }

        // Fungsi untuk memuat konten Google
        function loadGoogleContent(type, contentId = null) {
            currentContentType = type;
            
            // Update active button
            document.querySelectorAll('.btn-app').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.type === type) {
                    btn.classList.add('active');
                }
            });

            // Update title
            const typeNames = {
                docs: 'Google Docs',
                sheets: 'Google Sheets',
                slides: 'Google Slides'
            };

            document.getElementById('googleTitle').innerHTML = `
                Preview Materi - ${typeNames[type]}
                <small class="d-block fs-6 mt-1">Total: ${googleContentDB[type].length} dokumen</small>
            `;

            // Sembunyikan empty state
            document.getElementById('emptyPreview').style.display = 'none';
            document.getElementById('googleContentPreview').style.display = 'block';

            // Jika ada contentId spesifik, load konten tersebut
            if (contentId) {
                const content = googleContentDB[type].find(item => item.id === contentId);
                if (content) {
                    renderGoogleContent(content, type);
                    currentContentId = contentId;
                    return;
                }
            }

            // Jika tidak ada konten untuk tipe ini
            if (googleContentDB[type].length === 0) {
                document.getElementById('googleContentPreview').innerHTML = `
                    <div class="text-center py-5">
                        <i class="fab fa-${type === 'docs' ? 'google-drive' : type === 'sheets' ? 'file-excel' : 'file-powerpoint'} fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada konten ${typeNames[type]}</h5>
                        <p class="text-muted">Tambahkan konten ${typeNames[type]} terlebih dahulu</p>
                        <button class="btn btn-primary" onclick="uploadGoogleContent()">
                            <i class="fas fa-plus me-1"></i> Tambah ${typeNames[type]}
                        </button>
                    </div>
                `;
                return;
            }

            // Load konten pertama sebagai default
            const firstContent = googleContentDB[type][0];
            currentContentId = firstContent.id;
            renderGoogleContent(firstContent, type);
        }

        // Fungsi untuk merender konten Google
        function renderGoogleContent(content, type) {
            const previewHTML = `
                <div class="google-content-wrapper">
                    <!-- Header dengan judul dan navigasi -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4>${content.title}</h4>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar-alt me-1"></i> ${content.date} | 
                                <i class="fas fa-info-circle me-1 ms-2"></i> ${content.description}
                            </p>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm" onclick="editGoogleContent(${content.id}, '${type}')">
                                <i class="fas fa-edit me-1"></i> Edit
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="deleteGoogleContent(${content.id}, '${type}')">
                                <i class="fas fa-trash me-1"></i> Hapus
                            </button>
                        </div>
                    </div>

                    <!-- IFrame Preview -->
                    <div class="google-iframe-wrapper mb-3">
                        <iframe src="${content.url}" 
                                class="google-iframe" 
                                title="${content.title}"
                                allowfullscreen>
                        </iframe>
                    </div>

                    <!-- Daftar konten lainnya -->
                    ${renderContentList(type, content.id)}
                </div>
            `;

            document.getElementById('googleContentPreview').innerHTML = previewHTML;
        }

        // Fungsi untuk merender daftar konten
        function renderContentList(type, currentId) {
            const contents = googleContentDB[type];
            if (contents.length <= 1) return '';

            const listItems = contents
                .filter(item => item.id !== currentId)
                .map(item => `
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">${item.title}</h6>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i> ${item.date}
                            </small>
                        </div>
                        <button class="btn btn-sm btn-outline-primary" onclick="loadGoogleContent('${type}', ${item.id})">
                            <i class="fas fa-eye me-1"></i> Lihat
                        </button>
                    </div>
                `).join('');

            return `
                <div class="mt-4">
                    <h5>Konten ${type === 'docs' ? 'Docs' : type === 'sheets' ? 'Sheets' : 'Slides'} Lainnya</h5>
                    <div class="list-group mt-2">
                        ${listItems}
                    </div>
                </div>
            `;
        }

        // Fungsi untuk edit konten
        function editGoogleContent(contentId, type) {
            const content = googleContentDB[type].find(item => item.id === contentId);
            if (!content) return;

            // Isi form modal dengan data yang ada
            document.getElementById('googleType').value = type;
            document.getElementById('googleTitleInput').value = content.title;
            document.getElementById('googleUrl').value = content.url;

            // Update button text
            document.getElementById('googleUploadModalLabel').textContent = 'Edit Google Content';
            document.querySelector('#googleUploadModal .btn-primary').textContent = 'Update';
            document.querySelector('#googleUploadModal .btn-primary').onclick = function() {
                updateGoogleContent(contentId, type);
            };

            // Buka modal
            const modal = new bootstrap.Modal(document.getElementById('googleUploadModal'));
            modal.show();
        }

        // Fungsi untuk update konten
        function updateGoogleContent(contentId, type) {
            const title = document.getElementById('googleTitleInput').value;
            const url = document.getElementById('googleUrl').value;

            if (!title || !url) {
                alert('Harap lengkapi semua field!');
                return;
            }

            // Update data di database
            const index = googleContentDB[type].findIndex(item => item.id === contentId);
            if (index !== -1) {
                googleContentDB[type][index].title = title;
                googleContentDB[type][index].url = url;
                googleContentDB[type][index].date = new Date().toISOString().split('T')[0];
            }

            // Tutup modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('googleUploadModal'));
            modal.hide();

            // Reset form dan button
            document.getElementById('googleUploadForm').reset();
            document.getElementById('googleUploadModalLabel').textContent = 'Upload Google Workspace Content';
            document.querySelector('#googleUploadModal .btn-primary').textContent = 'Simpan';
            document.querySelector('#googleUploadModal .btn-primary').onclick = saveGoogleContent;

            // Reload konten
            loadGoogleContent(type, contentId);
            
            showNotification('Konten berhasil diperbarui!', 'success');
        }

        // Fungsi untuk hapus konten
        function deleteGoogleContent(contentId, type) {
            if (!confirm('Apakah Anda yakin ingin menghapus konten ini?')) return;

            // Hapus dari database
            const index = googleContentDB[type].findIndex(item => item.id === contentId);
            if (index !== -1) {
                googleContentDB[type].splice(index, 1);
            }

            // Jika masih ada konten lain, load konten pertama
            if (googleContentDB[type].length > 0) {
                loadGoogleContent(type);
            } else {
                // Kembali ke empty state
                document.getElementById('emptyPreview').style.display = 'block';
                document.getElementById('googleContentPreview').style.display = 'none';
                document.getElementById('googleTitle').innerHTML = 'Preview Materi - Google Workspace';
            }

            showNotification('Konten berhasil dihapus!', 'success');
        }

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type = 'success') {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 3000);
        }

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Tambahkan style untuk active button
            const style = document.createElement('style');
            style.textContent = `
                .btn-app.active {
                    transform: scale(1.1);
                    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                }
                .btn-app:hover {
                    transform: translateY(-2px);
                    transition: transform 0.3s;
                }
                .google-iframe-wrapper {
                    position: relative;
                    width: 100%;
                    height: 0;
                    padding-bottom: 56.25%;
                    border-radius: 8px;
                    overflow: hidden;
                    border: 1px solid #e0e0e0;
                }
                .google-iframe {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    border: none;
                }
            `;
            document.head.appendChild(style);
        });
    </script>
@endsection