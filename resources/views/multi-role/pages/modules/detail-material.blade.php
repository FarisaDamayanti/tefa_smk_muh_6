@php
    $role = auth()->user()->role ?? 'staff';
    $layout = match ($role) {
        'student' => 'student.layouts.app',
        'teacher' => 'teacher.layouts.app',
        default => 'student.layouts.app',
    };

    $filePath = $module->file_path ?? '';
    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $content = $module->content ?? '';
    $link = $module->link ?? '';
    $linkType = $module->link_type ?? '';
    $moduleType = $module->type ?? '';

    $materialType = 'unknown';

    // GANTI bagian ini:
    $isYouTube = false;
    $youtubeId = null;

    $urlToCheck = !empty($content) ? $content : $link;

    if (!empty($urlToCheck) && (str_contains($urlToCheck, 'youtube.com') || str_contains($urlToCheck, 'youtu.be'))) {
        $isYouTube = true;

        // Pattern yang lebih komprehensif (sama dengan JavaScript):
        $patterns = [
            '/youtube\.com\/watch\?v=([^&\s]+)/',
            '/youtube\.com\/embed\/([^?\s]+)/',
            '/youtube\.com\/v\/([^?\s]+)/',
            '/youtu\.be\/([^?\s]+)/',
            '/youtube\.com\/live\/([^?\s]+)/',
            '/youtube\.com\/shorts\/([^?\s]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $urlToCheck, $matches)) {
                $youtubeId = $matches[1];
                $youtubeId = explode('?', $youtubeId)[0];
                $youtubeId = explode('&', $youtubeId)[0];
                break;
            }
        }

        if (
            !$youtubeId &&
            preg_match(
                '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/',
                $urlToCheck,
                $matches,
            )
        ) {
            $youtubeId = $matches[1];
        }

        // Fallback tambahan untuk youtu.be
        if (!$youtubeId && str_contains($urlToCheck, 'youtu.be/')) {
            $parts = explode('youtu.be/', $urlToCheck);
            if (isset($parts[1])) {
                $youtubeId = explode('?', $parts[1])[0];
                $youtubeId = explode('&', $youtubeId)[0];
            }
        }

        if ($youtubeId) {
            $materialType = 'youtube';
        }
    }

    $isGoogleDrive =
        !empty($link) && (str_contains($link, 'drive.google.com') || str_contains($link, 'docs.google.com'));
    $googleDriveId = null;

    if ($isGoogleDrive) {
        if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $link, $matches)) {
            $googleDriveId = $matches[1];
            $materialType = 'google-drive';
        } elseif (preg_match('/id=([a-zA-Z0-9_-]+)/', $link, $matches)) {
            $googleDriveId = $matches[1];
            $materialType = 'google-drive';
        }
    }

    if ($materialType === 'unknown') {
        if (!empty($moduleType)) {
            $materialType = $moduleType;
        } elseif (!empty($linkType)) {
            $materialType = $linkType;
        } elseif ($fileExtension) {
            $extensionMap = [
                'pdf' => 'pdf',
                'doc' => 'word',
                'docx' => 'word',
                'xls' => 'excel',
                'xlsx' => 'excel',
                'ppt' => 'ppt',
                'pptx' => 'ppt',
                'jpg' => 'image',
                'jpeg' => 'image',
                'png' => 'image',
                'gif' => 'image',
                'webp' => 'image',
                'mp4' => 'video',
                'avi' => 'video',
                'mov' => 'video',
                'mkv' => 'video',
                'webm' => 'video',
                'mp3' => 'audio',
                'wav' => 'audio',
                'ogg' => 'audio',
                'txt' => 'text',
                'zip' => 'archive',
                'rar' => 'archive',
            ];
            $materialType = $extensionMap[$fileExtension] ?? 'file';
        } elseif (!empty(trim(strip_tags($content)))) {
            $materialType = 'editor';
        } elseif (!empty($link)) {
            $materialType = 'link';
        }
    }

    $iconMap = [
        'pdf' => ['icon' => 'ti-file-type-pdf', 'color' => 'text-danger', 'label' => 'PDF'],
        'word' => ['icon' => 'ti-file-type-doc', 'color' => 'text-primary', 'label' => 'Word'],
        'excel' => ['icon' => 'ti-file-type-xls', 'color' => 'text-success', 'label' => 'Excel'],
        'ppt' => ['icon' => 'ti-file-type-ppt', 'color' => 'text-warning', 'label' => 'PowerPoint'],
        'youtube' => ['icon' => 'ti-brand-youtube', 'color' => 'text-danger', 'label' => 'YouTube'],
        'google-drive' => ['icon' => 'ti-brand-google-drive', 'color' => 'text-warning', 'label' => 'Google Drive'],
        'editor' => ['icon' => 'ti-edit', 'color' => 'text-info', 'label' => 'Editor'],
        'link' => ['icon' => 'ti-link', 'color' => 'text-success', 'label' => 'Link'],
        'image' => ['icon' => 'ti-photo', 'color' => 'text-info', 'label' => 'Gambar'],
        'video' => ['icon' => 'ti-video', 'color' => 'text-primary', 'label' => 'Video'],
        'audio' => ['icon' => 'ti-music', 'color' => 'text-warning', 'label' => 'Audio'],
        'text' => ['icon' => 'ti-file-text', 'color' => 'text-secondary', 'label' => 'Text'],
        'archive' => ['icon' => 'ti-file-zip', 'color' => 'text-muted', 'label' => 'Archive'],
        'file' => ['icon' => 'ti-file', 'color' => 'text-secondary', 'label' => 'File'],
    ];

    $iconConfig = $iconMap[$materialType] ?? $iconMap['file'];
@endphp

@extends($layout)

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <style>
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            margin-bottom: 20px;
            border-radius: 8px;
            background: #000;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .editor-content {
            font-size: 16px;
            line-height: 1.6;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #eaeaea;
        }

        .editor-content img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }

        .editor-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .editor-content table,
        .editor-content th,
        .editor-content td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .editor-content h1,
        .editor-content h2,
        .editor-content h3,
        .editor-content h4,
        .editor-content h5,
        .editor-content h6 {
            margin-top: 1em;
            margin-bottom: 0.5em;
        }

        .editor-content ul,
        .editor-content ol {
            padding-left: 2em;
        }

        .file-preview-container {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            position: relative;
            min-height: 600px;
            background: #f8f9fa;
        }

        .file-preview-container iframe,
        .file-preview-container embed {
            width: 100%;
            height: 600px;
            border: 0;
        }

        .material-header {
            background: linear-gradient(135deg, #5D87FF 0%, #4A6FCC 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 8px 8px 0 0;
        }

        .link-display {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            word-break: break-all;
            border: 1px dashed #dee2e6;
            max-width: 800px;
            margin: 0 auto;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10;
        }

        .loading-overlay.hidden {
            display: none;
        }

        .preview-warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .office-preview-unavailable {
            background: #f8f9fa;
            padding: 40px;
            text-align: center;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Detail Materi"  breadcrumb="E-learning / Kelas / {{ $module->topic->classroom->levelClass->name ?? '' }} {{ $module->topic->classroom->name ?? '' }} - {{ $module->topic->subject->name ?? '' }} / Topik {{ $module->topic->title ?? '' }} / Detail Materi" />



    <div class="mt-4 mb-4 d-flex justify-content-end">
        @if ($role === 'student')
            @php
                $classroomId = $module->topic->classroom_id ?? '';
                $subjectId = $module->topic->subject_id ?? '';
            @endphp
            @if ($classroomId && $subjectId)
                <a href="{{ route('student.topic.index', ['classroom' => $classroomId, 'subject' => $subjectId]) }}"
                    class="btn btn-light">
                    <i class="ti ti-arrow-left me-2"></i>Kembali
                </a>
            @else
                <a href="{{ url()->previous() }}" class="btn btn-light">
                    <i class="ti ti-arrow-left me-2"></i>Kembali
                </a>
            @endif
        @else
            <a href="{{ url()->previous() }}" class="btn btn-warning" style="padding: 7px 35px; margin-right: 10px;">
                Kembali
            </a>
        @endif
    </div>
    <div class="card">
        <div class="material-header">

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 text-white fw-bold">{{ $module->title }}</h5>
                    <div class="d-flex align-items-center gap-2">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="p-4 bg-light border rounded">
        <div class="row">
            <div class="col-md-6">
                <small class="text-muted">
                    <i class="ti ti-calendar me-1"></i>
                    <strong>Dibuat:</strong> {{ $module->created_at->translatedFormat('d F Y, H:i') }}
                </small>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="text-muted">
                    <i class="ti ti-refresh me-1"></i>
                    <strong>Diperbarui:</strong> {{ $module->updated_at->translatedFormat('d F Y, H:i') }}
                </small>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            {{-- ============================================ --}}
            {{-- 1. YOUTUBE --}}
            {{-- ============================================ --}}
            @if ($isYouTube && $youtubeId)
                <div class="p-4">
                    <div class="video-container">
                        <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&modestbranding=1"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- 2. EDITOR CONTENT (HTML/TEXT) --}}
                {{-- ============================================ --}}
            @elseif($materialType === 'editor' || (!empty(trim(strip_tags($content))) && !$isYouTube))
                <div class="p-4">
                    <h6 class="fw-bold mb-3">
                        Konten Materi
                    </h6>
                    <div class="editor-content">
                        {!! $content !!}
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- 3. GOOGLE DRIVE FILES --}}
                {{-- ============================================ --}}
            @elseif($isGoogleDrive && $googleDriveId)
                <div class="p-4">
                    <h6 class="fw-bold mb-3">
                        File Google Drive
                    </h6>
                    <div class="file-preview-container" id="gdrive-container">
                        <div class="loading-overlay" id="loading-overlay">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted">Memuat preview dari Google Drive...</p>
                            <small class="text-muted">Mohon tunggu beberapa saat</small>
                        </div>
                        <iframe src="https://drive.google.com/file/d/{{ $googleDriveId }}/preview" id="gdrive-iframe"
                            allow="autoplay" onload="hideLoading()">
                        </iframe>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- 4. PDF FILE (UPLOADED) --}}
                {{-- ============================================ --}}
            @elseif($materialType === 'pdf' && $filePath)
                <div class="p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="ti ti-file-type-pdf text-danger me-2"></i>
                        Dokumen PDF
                    </h6>

                    <div class="text-center mb-4">
                        <i class="ti ti-file-type-pdf fs-1 text-danger mb-3"></i>
                        <h5>{{ $module->title }}</h5>
                        <p class="text-muted">File PDF</p>
                    </div>

                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <a href="{{ asset('storage/' . $filePath) }}" class="btn btn-primary" target="_blank">
                            <i class="ti ti-eye me-2"></i>Lihat PDF
                        </a>

                        <a href="{{ asset('storage/' . $filePath) }}" class="btn btn-outline-primary" download>
                            <i class="ti ti-download me-2"></i>Download
                        </a>
                    </div>

                    {{-- PDF Preview using embed --}}
                    <div class="file-preview-container">
                        <embed src="{{ asset('storage/' . $filePath) }}" type="application/pdf" width="100%"
                            height="600px">
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="ti ti-info-circle me-2"></i>
                        <small>Jika preview tidak muncul, silakan klik tombol <strong>"Lihat PDF"</strong> atau
                            <strong>"Download"</strong> di atas.</small>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- 5. WORD DOCUMENT (UPLOADED) --}}
                {{-- ============================================ --}}
            @elseif($materialType === 'word' && $filePath)
                <div class="p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="ti ti-file-type-doc text-primary me-2"></i>
                        Dokumen Word
                    </h6>

                    <div class="text-center mb-4">
                        <i class="ti ti-file-type-doc fs-1 text-primary mb-3"></i>
                        <h5>{{ $module->title }}</h5>
                        <p class="text-muted">File Microsoft Word (.{{ $fileExtension }})</p>
                    </div>

                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <a href="{{ asset('storage/' . $filePath) }}" class="btn btn-primary" download>
                            <i class="ti ti-download me-2"></i>Download File
                        </a>
                    </div>

                    <div class="office-preview-unavailable">
                        <i class="ti ti-file-type-doc fs-1 text-primary mb-4"></i>
                        <h5>Preview Tidak Tersedia</h5>
                        <p class="text-muted mb-3">File Microsoft Word tidak dapat ditampilkan preview di localhost.</p>
                        <div class="alert alert-info d-inline-block">
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>Cara membuka:</strong>
                            <ol class="text-start mb-0 mt-2">
                                <li>Klik tombol <strong>"Download File"</strong> di atas</li>
                                <li>Buka file menggunakan <strong>Microsoft Word</strong>, <strong>Google Docs</strong>,
                                    atau aplikasi pembaca dokumen lainnya</li>
                            </ol>
                        </div>
                    </div>

                    <div class="preview-warning mt-3">
                        <i class="ti ti-lightbulb me-2"></i>
                        <strong>Info:</strong> Office Online Viewer (<code>view.officeapps.live.com</code>) hanya dapat
                        mengakses file dari server yang tersedia secara publik di internet, tidak dapat mengakses file dari
                        <strong>localhost</strong> atau server lokal.
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- 6. EXCEL SPREADSHEET (UPLOADED) --}}
                {{-- ============================================ --}}
            @elseif($materialType === 'excel' && $filePath)
                <div class="p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="ti ti-file-type-xls text-success me-2"></i>
                        Spreadsheet Excel
                    </h6>

                    <div class="text-center mb-4">
                        <i class="ti ti-file-type-xls fs-1 text-success mb-3"></i>
                        <h5>{{ $module->title }}</h5>
                        <p class="text-muted">File Microsoft Excel (.{{ $fileExtension }})</p>
                    </div>

                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <a href="{{ asset('storage/' . $filePath) }}" class="btn btn-success" download>
                            <i class="ti ti-download me-2"></i>Download File
                        </a>
                    </div>

                    <div class="office-preview-unavailable">
                        <i class="ti ti-file-type-xls fs-1 text-success mb-4"></i>
                        <h5>Preview Tidak Tersedia</h5>
                        <p class="text-muted mb-3">File Microsoft Excel tidak dapat ditampilkan preview di localhost.</p>
                        <div class="alert alert-info d-inline-block">
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>Cara membuka:</strong>
                            <ol class="text-start mb-0 mt-2">
                                <li>Klik tombol <strong>"Download File"</strong> di atas</li>
                                <li>Buka file menggunakan <strong>Microsoft Excel</strong>, <strong>Google Sheets</strong>,
                                    atau aplikasi pembaca spreadsheet lainnya</li>
                            </ol>
                        </div>
                    </div>

                    <div class="preview-warning mt-3">
                        <i class="ti ti-lightbulb me-2"></i>
                        <strong>Info:</strong> Office Online Viewer hanya dapat mengakses file dari server yang tersedia
                        secara publik di internet.
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- 7. POWERPOINT PRESENTATION (UPLOADED) --}}
                {{-- ============================================ --}}
            @elseif($materialType === 'ppt' && $filePath)
                <div class="p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="ti ti-file-type-ppt text-warning me-2"></i>
                        Presentasi PowerPoint
                    </h6>

                    <div class="text-center mb-4">
                        <i class="ti ti-file-type-ppt fs-1 text-warning mb-3"></i>
                        <h5>{{ $module->title }}</h5>
                        <p class="text-muted">File Microsoft PowerPoint (.{{ $fileExtension }})</p>
                    </div>

                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <a href="{{ asset('storage/' . $filePath) }}" class="btn btn-warning" download>
                            <i class="ti ti-download me-2"></i>Download File
                        </a>
                    </div>

                    <div class="office-preview-unavailable">
                        <i class="ti ti-file-type-ppt fs-1 text-warning mb-4"></i>
                        <h5>Preview Tidak Tersedia</h5>
                        <p class="text-muted mb-3">File Microsoft PowerPoint tidak dapat ditampilkan preview di localhost.
                        </p>
                        <div class="alert alert-info d-inline-block">
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>Cara membuka:</strong>
                            <ol class="text-start mb-0 mt-2">
                                <li>Klik tombol <strong>"Download File"</strong> di atas</li>
                                <li>Buka file menggunakan <strong>Microsoft PowerPoint</strong>, <strong>Google
                                        Slides</strong>, atau aplikasi pembaca presentasi lainnya</li>
                            </ol>
                        </div>
                    </div>

                    <div class="preview-warning mt-3">
                        <i class="ti ti-lightbulb me-2"></i>
                        <strong>Info:</strong> Office Online Viewer hanya dapat mengakses file dari server yang tersedia
                        secara publik di internet.
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- 8. IMAGE FILES --}}
                {{-- ============================================ --}}
            @elseif($materialType === 'image' && $filePath)
                <div class="p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="ti ti-photo text-info me-2"></i>
                        Gambar
                    </h6>

                    <div class="text-center mb-4">
                        <img src="{{ asset('storage/' . $filePath) }}" alt="{{ $module->title }}"
                            class="img-fluid rounded shadow" style="max-height: 600px; object-fit: contain;">
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ asset('storage/' . $filePath) }}" class="btn btn-primary" target="_blank">
                            <i class="ti ti-eye me-2"></i>Lihat Ukuran Penuh
                        </a>

                        <a href="{{ asset('storage/' . $filePath) }}" class="btn btn-outline-primary" download>
                            <i class="ti ti-download me-2"></i>Download
                        </a>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- 9. VIDEO FILES --}}
                {{-- ============================================ --}}
            @elseif($materialType === 'video' && $filePath)
                <div class="p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="ti ti-video text-primary me-2"></i>
                        Video
                    </h6>

                    <div class="mb-4">
                        <video controls class="w-100 rounded shadow" style="max-height: 600px; background: #000;">
                            <source src="{{ asset('storage/' . $filePath) }}" type="video/{{ $fileExtension }}">
                            Browser Anda tidak mendukung pemutaran video.
                        </video>
                    </div>

                    <div class="d-flex justify-content-center">
                        <a href="{{ asset('storage/' . $filePath) }}" class="btn btn-outline-primary" download>
                            <i class="ti ti-download me-2"></i>Download Video
                        </a>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- 10. AUDIO FILES --}}
                {{-- ============================================ --}}
            @elseif($materialType === 'audio' && $filePath)
                <div class="p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="ti ti-music text-warning me-2"></i>
                        Audio
                    </h6>

                    <div class="text-center mb-4">
                        <i class="ti ti-music fs-1 text-warning mb-4"></i>
                        <h5>{{ $module->title }}</h5>
                        <p class="text-muted">File Audio (.{{ $fileExtension }})</p>
                    </div>

                    <div class="mb-4 d-flex justify-content-center">
                        <audio controls class="w-100" style="max-width: 600px;">
                            <source src="{{ asset('storage/' . $filePath) }}" type="audio/{{ $fileExtension }}">
                            Browser Anda tidak mendukung pemutaran audio.
                        </audio>
                    </div>

                    <div class="d-flex justify-content-center">
                        <a href="{{ asset('storage/' . $filePath) }}" class="btn btn-outline-warning" download>
                            <i class="ti ti-download me-2"></i>Download Audio
                        </a>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- 11. EXTERNAL LINK --}}
                {{-- ============================================ --}}
            @elseif(!empty($link) && !$isYouTube && !$isGoogleDrive)
                <div class="p-5 text-center">
                    <div class="mb-4">
                        <i class="ti ti-link fs-1 text-success mb-3"></i>
                        <h4 class="mt-3">Tautan Eksternal</h4>
                        <p class="text-muted">Konten tersedia melalui tautan berikut</p>
                    </div>

                    <div class="link-display mb-4">
                        <small class="text-break">{{ $link }}</small>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ $link }}" target="_blank" class="btn btn-success">
                            <i class="ti ti-external-link me-2"></i>Buka Link
                        </a>

                        <button class="btn btn-outline-secondary" onclick="copyLink('{{ $link }}')">
                            <i class="ti ti-copy me-2"></i>Salin Link
                        </button>
                    </div>

                    <div class="alert alert-info mt-4">
                        <i class="ti ti-info-circle me-2"></i>
                        <small>Klik tombol <strong>"Buka Link"</strong> untuk mengakses konten di tab baru.</small>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- 12. OTHER FILES (DOWNLOAD ONLY) --}}
                {{-- ============================================ --}}
            @elseif($filePath)
                <div class="p-4">
                    <div class="text-center mb-4">
                        <i class="{{ $iconConfig['icon'] }} fs-1 {{ $iconConfig['color'] }} mb-3"></i>
                        <h5>{{ $module->title }}</h5>
                        <p class="text-muted">File {{ strtoupper($fileExtension) }}</p>
                    </div>

                    <div class="d-flex justify-content-center">
                        <a href="{{ asset('storage/' . $filePath) }}" class="btn btn-primary" download>
                            <i class="ti ti-download me-2"></i>Download File
                        </a>
                    </div>

                    <div class="alert alert-info mt-4">
                        <i class="ti ti-info-circle me-2"></i>
                        <small>File ini tidak dapat ditampilkan preview. Silakan download untuk membuka.</small>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- 13. NO CONTENT --}}
                {{-- ============================================ --}}
            @else
                <div class="p-5 text-center">
                    <div class="mb-4">
                        <i class="ti ti-file-unknown fs-1 text-muted mb-3"></i>
                        <h4 class="mt-3">Tidak Ada Konten</h4>
                        <p class="text-muted">Materi ini tidak memiliki konten yang dapat ditampilkan</p>
                    </div>
                </div>
            @endif

            {{-- ============================================ --}}
            {{-- DESKRIPSI --}}
            {{-- ============================================ --}}
            @if (!empty($module->description))
                <div class="p-4 border-top">
                    <h6 class="fw-bold mb-3">
                        <i class="ti ti-note me-2"></i>
                        Deskripsi
                    </h6>
                    <div class="bg-light p-3 rounded">
                        {{ $module->description }}
                    </div>
                </div>
            @endif

            {{-- ============================================ --}}
            {{-- METADATA --}}
            {{-- ============================================ --}}

        </div>
    </div>
@endsection

@section('script')
    <script>
        // Copy link function
        function copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                const button = event.target.closest('button');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="ti ti-check me-2"></i>Tersalin!';
                button.classList.remove('btn-outline-secondary');
                button.classList.add('btn-success');

                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-outline-secondary');
                }, 2000);
            }).catch(err => {
                console.error('Gagal menyalin:', err);

                // Fallback for older browsers
                const tempInput = document.createElement('input');
                tempInput.value = url;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);

                alert('Link telah disalin!');
            });
        }

        // Hide loading overlay for Google Drive
        function hideLoading() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                setTimeout(() => {
                    loadingOverlay.classList.add('hidden');
                }, 500);
            }
        }

        // Auto hide loading after timeout
        document.addEventListener('DOMContentLoaded', function() {
            // Hide Google Drive loading after 10 seconds (timeout)
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                setTimeout(() => {
                    loadingOverlay.classList.add('hidden');
                }, 10000);
            }

            // Handle all iframes
            const iframes = document.querySelectorAll('.file-preview-container iframe');
            iframes.forEach(iframe => {
                iframe.onload = function() {
                    this.style.minHeight = '600px';
                };
            });
        });
    </script>
@endsection
