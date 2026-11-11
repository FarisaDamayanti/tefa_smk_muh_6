@extends('teacher.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/teacherpresence.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/dropdown/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/material.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .preview-card {
            transition: all 0.3s ease;
        }
        .video-placeholder {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .video-info {
            background-color: #f8f9fa;
        }
    </style>
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Preview Video YouTube"
        breadcrumb="Preview dan Simpan Video YouTube Untuk Materi Pembelajaran" />
    

        <div class="container-fluid mb-4" style="margin-top: 70px">
            <div class="card border-0 shadow-sm rounded-3 mb-4 preview-card">
                <!-- Header -->
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 text-white">
                         Player Video YouTube
                        </h5>
                    </div>
                </div>
                
                <!-- Video Player -->
                <div class="card-body p-0">
                    <div class="ratio ratio-16x9" id="videoPlayer">
                        <!-- Video akan muncul di sini -->
                        <div class="d-flex flex-column align-items-center justify-content-center video-placeholder h-100">
                            <i class="fab fa-youtube fa-4x mb-3"></i>
                            <h5>Video YouTube Akan Tampil di Sini</h5>
                            <p class="text-center px-4">Masukkan link YouTube di form sebelah untuk memulai preview</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container-fluid" style="padding: 400px">
            <div class="card border-0 shadow-sm rounded-3">
                <!-- Header -->
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-link me-2"></i>Input Link YouTube
                    </h5>
                </div>
                
                <!-- Input Form -->
                <div class="card-body">
                    <div class="mb-3">
                        <label for="youtubeLink" class="form-label fw-bold">
                            <i class="fas fa-paperclip me-1"></i>Link YouTube
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fab fa-youtube text-danger"></i>
                            </span>
                            <input type="text" class="form-control" id="youtubeLink" 
                                   placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                        <div class="form-text">
                            Masukkan link lengkap YouTube (watch?v=... atau youtu.be/...)
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-bolt me-1"></i>Link Cepat:
                        </label>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-sm example-link" data-link="https://www.youtube.com/watch?v=dQw4w9WgXcQ">
                                <i class="fas fa-music me-1"></i>Video Musik
                            </button>
                            <button class="btn btn-outline-success btn-sm example-link" data-link="https://www.youtube.com/watch?v=Kp2bYWRQylk">
                                <i class="fas fa-atom me-1"></i>Video Pendidikan
                            </button>
                            <button class="btn btn-outline-info btn-sm example-link" data-link="https://www.youtube.com/watch?v=jNQXAC9IVRw">
                                <i class="fas fa-history me-1"></i>Video Sejarah
                            </button>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" id="previewBtn">
                            <i class="fas fa-play-circle me-1"></i>Tampilkan Preview
                        </button>
                        <button class="btn btn-outline-secondary" id="resetBtn">
                            <i class="fas fa-redo me-1"></i>Reset Form
                        </button>
                    </div>
                    
                    <!-- Status Message -->
                    <div id="statusMessage" class="mt-3 alert d-none"></div>
                </div>
            </div>
        </div>
@endsection 

@section('script')
    <script>
        // DOM Elements
        const youtubeLinkInput = document.getElementById('youtubeLink');
        const previewBtn = document.getElementById('previewBtn');
        const resetBtn = document.getElementById('resetBtn');
        const saveVideoBtn = document.getElementById('saveVideoBtn');
        const clearVideoBtn = document.getElementById('clearVideoBtn');
        const videoPlayer = document.getElementById('videoPlayer');
        const videoTitle = document.getElementById('videoTitle');
        const currentLink = document.getElementById('currentLink');
        const addedDate = document.getElementById('addedDate');
        const statusMessage = document.getElementById('statusMessage');
        const savedVideos = document.getElementById('savedVideos');
        const exampleLinks = document.querySelectorAll('.example-link');
        
        // State variables
        let currentVideoId = null;
        let currentVideoUrl = null;
        
        // YouTube link patterns
        const youtubePatterns = [
            /^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/,
            /^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/
        ];
        
        // Extract YouTube video ID from URL
        function extractVideoId(url) {
            for (const pattern of youtubePatterns) {
                const match = url.match(pattern);
                if (match && match[1]) {
                    return match[1];
                }
            }
            return null;
        }
        
        // Generate embed URL
        function generateEmbedUrl(videoId) {
            return `https://www.youtube.com/embed/${videoId}?rel=0&modestbranding=1`;
        }
        
        // Format current date
        function formatDate() {
            const now = new Date();
            return now.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        
        // Show status message
        function showStatus(message, type = 'success') {
            statusMessage.textContent = message;
            statusMessage.className = `alert alert-${type} mt-3`;
            statusMessage.classList.remove('d-none');
            
            setTimeout(() => {
                statusMessage.classList.add('d-none');
            }, 3000);
        }
        
        // Update video player
        function updateVideoPlayer(videoId, originalUrl) {
            const embedUrl = generateEmbedUrl(videoId);
            
            // Update video player
            videoPlayer.innerHTML = `
                <iframe 
                    src="${embedUrl}" 
                    title="YouTube video player" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen
                    class="rounded-top"
                    style="width: 100%; height: 100%;">
                </iframe>
            `;
            
            // Update video info
            videoTitle.textContent = `Video YouTube (ID: ${videoId})`;
            currentLink.textContent = originalUrl.length > 50 ? originalUrl.substring(0, 50) + '...' : originalUrl;
            currentLink.title = originalUrl;
            addedDate.textContent = formatDate();
            
            // Enable buttons
            saveVideoBtn.disabled = false;
            clearVideoBtn.disabled = false;
            
            // Store current video info
            currentVideoId = videoId;
            currentVideoUrl = originalUrl;
            
            showStatus('Video berhasil dimuat!', 'success');
        }
        
        // Clear video player
        function clearVideoPlayer() {
            videoPlayer.innerHTML = `
                <div class="d-flex flex-column align-items-center justify-content-center video-placeholder h-100">
                    <i class="fab fa-youtube fa-4x mb-3"></i>
                    <h5>Video YouTube Akan Tampil di Sini</h5>
                    <p class="text-center px-4">Masukkan link YouTube di form sebelah untuk memulai preview</p>
                </div>
            `;
            
            // Reset info
            videoTitle.textContent = 'Belum ada video dipilih';
            currentLink.textContent = '-';
            addedDate.textContent = '-';
            
            // Disable buttons
            saveVideoBtn.disabled = true;
            clearVideoBtn.disabled = true;
            
            // Clear state
            currentVideoId = null;
            currentVideoUrl = null;
            
            // Clear input
            youtubeLinkInput.value = '';
            
            showStatus('Video telah dihapus', 'info');
        }
        
        // Load saved videos from localStorage
        function loadSavedVideos() {
            const saved = JSON.parse(localStorage.getItem('savedYouTubeVideos') || '[]');
            
            if (saved.length === 0) {
                savedVideos.innerHTML = `
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-video-slash fa-2x mb-2"></i>
                        <p class="mb-0">Belum ada video tersimpan</p>
                    </div>
                `;
                return;
            }
            
            let html = '';
            saved.forEach((video, index) => {
                const date = new Date(video.added).toLocaleDateString('id-ID');
                html += `
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Video ${video.videoId}</h6>
                            <small>${date}</small>
                        </div>
                        <p class="mb-1 small text-truncate">${video.url}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-sm btn-outline-primary load-video" data-index="${index}">
                                <i class="fas fa-play me-1"></i>Putar
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-video" data-index="${index}">
                                <i class="fas fa-trash me-1"></i>Hapus
                            </button>
                        </div>
                    </div>
                `;
            });
            
            savedVideos.innerHTML = html;
            
            // Add event listeners to new buttons
            document.querySelectorAll('.load-video').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    const saved = JSON.parse(localStorage.getItem('savedYouTubeVideos') || '[]');
                    const video = saved[index];
                    
                    if (video) {
                        youtubeLinkInput.value = video.url;
                        previewYouTubeLink();
                    }
                });
            });
            
            document.querySelectorAll('.delete-video').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    const saved = JSON.parse(localStorage.getItem('savedYouTubeVideos') || '[]');
                    
                    if (confirm('Hapus video ini dari daftar?')) {
                        saved.splice(index, 1);
                        localStorage.setItem('savedYouTubeVideos', JSON.stringify(saved));
                        loadSavedVideos();
                        showStatus('Video dihapus dari daftar', 'warning');
                    }
                });
            });
        }
        
        // Save video to localStorage
        function saveVideo() {
            if (!currentVideoId || !currentVideoUrl) {
                showStatus('Tidak ada video untuk disimpan', 'danger');
                return;
            }
            
            const saved = JSON.parse(localStorage.getItem('savedYouTubeVideos') || '[]');
            
            // Check if video already exists
            const exists = saved.some(video => video.videoId === currentVideoId);
            if (exists) {
                showStatus('Video sudah ada dalam daftar', 'warning');
                return;
            }
            
            // Add new video
            saved.push({
                videoId: currentVideoId,
                url: currentVideoUrl,
                added: new Date().toISOString()
            });
            
            localStorage.setItem('savedYouTubeVideos', JSON.stringify(saved));
            loadSavedVideos();
            
            showStatus('Video berhasil disimpan!', 'success');
        }
        
        // Validate and preview YouTube link
        function previewYouTubeLink() {
            const url = youtubeLinkInput.value.trim();
            
            if (!url) {
                showStatus('Masukkan link YouTube terlebih dahulu!', 'danger');
                youtubeLinkInput.focus();
                return;
            }
            
            const videoId = extractVideoId(url);
            
            if (!videoId) {
                showStatus('Link YouTube tidak valid!', 'danger');
                youtubeLinkInput.select();
                return;
            }
            
            updateVideoPlayer(videoId, url);
        }
        
        // Event Listeners
        previewBtn.addEventListener('click', previewYouTubeLink);
        
        youtubeLinkInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                previewYouTubeLink();
            }
        });
        
        resetBtn.addEventListener('click', function() {
            clearVideoPlayer();
            youtubeLinkInput.focus();
        });
        
        saveVideoBtn.addEventListener('click', saveVideo);
        
        clearVideoBtn.addEventListener('click', function() {
            if (confirm('Hapus video yang sedang diputar?')) {
                clearVideoPlayer();
            }
        });
        
        // Example links
        exampleLinks.forEach(button => {
            button.addEventListener('click', function() {
                const link = this.getAttribute('data-link');
                youtubeLinkInput.value = link;
                previewYouTubeLink();
            });
        });
        
        // Load last video from localStorage on page load
        document.addEventListener('DOMContentLoaded', function() {
            const lastLink = localStorage.getItem('lastYouTubeLink');
            if (lastLink) {
                youtubeLinkInput.value = lastLink;
                const videoId = extractVideoId(lastLink);
                if (videoId) {
                    updateVideoPlayer(videoId, lastLink);
                }
            }
            
            // Load saved videos
            loadSavedVideos();
            
            // Auto-focus input
            youtubeLinkInput.focus();
        });
        
        // Save current link when previewing
        function saveCurrentLink() {
            if (currentVideoUrl) {
                localStorage.setItem('lastYouTubeLink', currentVideoUrl);
            }
        }
        
        // Update saveCurrentLink when video is loaded
        const originalUpdateVideoPlayer = updateVideoPlayer;
        updateVideoPlayer = function(videoId, originalUrl) {
            originalUpdateVideoPlayer(videoId, originalUrl);
            saveCurrentLink();
        };
    </script>
@endsection