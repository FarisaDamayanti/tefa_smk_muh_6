@extends('teacher.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/teacherpresence.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/dropdown/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection
@section('content')
<x-authenticated.breadcrumb title="Tambah Materi" breadcrumb="E-learning / Kelas /{{ $classroom->levelClass->name ?? '' }} {{ $classroom->name ?? '' }} - {{ $subject->name ?? '' }} / Topik {{ $topic->title ?? '' }} / Tambah Materi" />
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white fw-semibold">
            Materi
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Judul Materi
                    <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control form-control-custom" placeholder="Masukkan judul materi"
                    style="border-radius: 10px;">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Jenis Materi
                    <span class="text-danger">*</span>
                </label>
                <div class="custom-dropdown dropdown-material-type">
                    <div class="dropdown-selected" id="dropdownSelected">
                        <div class="selected-content">
                            <span class="selected-text">Pilih jenis materi</span>
                        </div>
                        <svg class="dropdown-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none">
                            <path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="dropdown-options" id="dropdownOptions">
                        <div class="dropdown-option" data-value="">
                            <span class="option-text">Pilih jenis materi</span>
                            <span class="text-danger">*</span>
                        </div>
                        <div class="dropdown-option" data-value="teks">
                            <svg width="20" height="17" viewBox="0 0 20 17" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10 16.0403C8.649 15.0893 7.085 14.4833 5.445 14.2793C4.28185 14.043 3.09682 13.9313 1.91 13.9463C1.75814 13.9476 1.60752 13.9188 1.46684 13.8616C1.32616 13.8044 1.1982 13.7199 1.09035 13.613C0.982506 13.5061 0.896902 13.3789 0.838485 13.2387C0.780068 13.0985 0.749994 12.9481 0.75 12.7963L0.771 1.90328C0.770361 1.611 0.881034 1.32945 1.08053 1.11584C1.28002 0.902229 1.55336 0.772594 1.845 0.753279C3.05314 0.729263 4.2603 0.837236 5.445 1.07528C7.08322 1.28664 8.646 1.89116 10 2.83728M10 16.0403V2.83728M10 16.0403C11.351 15.0893 12.915 14.4833 14.555 14.2793C15.7182 14.043 16.9032 13.9313 18.09 13.9463C18.2419 13.9476 18.3925 13.9188 18.5332 13.8616C18.6738 13.8044 18.8018 13.7199 18.9096 13.613C19.0175 13.5061 19.1031 13.3789 19.1615 13.2387C19.2199 13.0985 19.25 12.9481 19.25 12.7963L19.228 1.90328C19.2287 1.61116 19.1181 1.32975 18.9188 1.11616C18.7196 0.902579 18.4465 0.772837 18.155 0.753279C16.9469 0.729263 15.7397 0.837236 14.555 1.07528C12.9168 1.28664 11.354 1.89116 10 2.83728"
                                    stroke="#424242" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="option-text">Teks Materi</span>
                        </div>
                        <div class="dropdown-option" data-value="tautan">
                            <svg width="20" height="10" viewBox="0 0 20 10" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.9 5C1.9 3.29 3.29 1.9 5 1.9H9V0H5C2.24 0 0 2.24 0 5C0 7.76 2.24 10 5 10H9V8.1H5C3.29 8.1 1.9 6.71 1.9 5ZM6 6H14V4H6V6ZM15 0H11V1.9H15C16.71 1.9 18.1 3.29 18.1 5C18.1 6.71 16.71 8.1 15 8.1H11V10H15C17.76 10 20 7.76 20 5C20 2.24 17.76 0 15 0Z"
                                    fill="#424242" />
                            </svg>
                            <span class="option-text">Tautan</span>
                        </div>
                    </div>
                    <input type="hidden" name="jenis_materi" id="jenisMateri">
                </div>
            </div>
            <div class="mb-3" id="jenisKontenSection" style="display: none;">
                <label class="form-label fw-semibold">
                    Jenis Konten
                    <span class="text-danger">*</span>
                </label>
                <div class="custom-dropdown">
                    <div class="dropdown-selected" id="dropdownSelectedKonten">
                        <div class="selected-content">
                            <span class="selected-text text-muted">Pilih Konten File</span>
                            <span class="selected-icon-konten"></span>
                        </div>
                        <svg class="dropdown-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none">
                            <path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="dropdown-options" id="dropdownOptionsKonten">
                        <div class="dropdown-option" data-value="pdf">
                            <div class="option-content">
                                <span class="option-text">Pdf</span>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="24" height="24" rx="4" fill="#DC3545" />
                                    <text x="12" y="16" text-anchor="middle" fill="white" font-size="10"
                                        font-weight="bold">PDF</text>
                                </svg>
                            </div>
                        </div>
                        <div class="dropdown-option" data-value="word">
                            <div class="option-content">
                                <span class="option-text">Goole Doc</span>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="24" height="24" rx="4" fill="#2B579A" />
                                    <text x="12" y="16" text-anchor="middle" fill="white" font-size="10"
                                        font-weight="bold">W</text>
                                </svg>
                            </div>
                        </div>
                        <div class="dropdown-option" data-value="ppt"> {{-- ✅ UBAH DARI powerpoint KE ppt --}}
                            <div class="option-content">
                                <span class="option-text">Google Slides</span>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="24" height="24" rx="4" fill="#D24726" />
                                    <text x="12" y="16" text-anchor="middle" fill="white" font-size="10"
                                        font-weight="bold">P</text>
                                </svg>
                            </div>
                        </div>
                        <div class="dropdown-option" data-value="excel">
                            <div class="option-content">
                                <span class="option-text">Google Sheets</span>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="24" height="24" rx="4" fill="#217346" />
                                    <text x="12" y="16" text-anchor="middle" fill="white" font-size="10"
                                        font-weight="bold">X</text>
                                </svg>
                            </div>
                        </div>
                        <div class="dropdown-option" data-value="youtube">
                            <div class="option-content">
                                <span class="option-text">Youtube</span>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="24" height="24" rx="4" fill="#FF0000" />
                                    <path d="M9.5 8.5L15.5 12L9.5 15.5V8.5Z" fill="white" />
                                </svg>
                            </div>
                        </div>
                        <div class="dropdown-option" data-value="other">
                            <div class="option-content">
                                <span class="option-text">Lainnya..</span>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="jenis_konten" id="jenisKonten">
                </div>
            </div>
            <div class="mb-3" id="tautanSection" style="display: none;">
                <label class="form-label fw-semibold">
                    Tautan
                    <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control form-control-custom" placeholder="Masukkan Tautan"
                    style="border-radius: 10px;">
            </div>
            <div class="mb-4" id="isiMateriSection" style="display: none;">
                <label class="form-label fw-semibold">Isi Materi</label>
                <span class="text-danger">*</span>
                <textarea id="summernote-editor" class="form-control summernote"></textarea>
                <input type="hidden" name="content" id="content">
            </div>
            <div class="d-flex align-items-center justify-content-end">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">
                        Kembali
                    </button>
                    <button class="btn btn-primary" style="padding: 7px 35px;">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('teacher.pages.material.scripts.create')
@endsection
