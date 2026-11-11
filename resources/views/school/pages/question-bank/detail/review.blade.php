@extends('school.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <style>
        .btn-remove-question {
            width: 34px;
            height: 34px;
            min-width: 34px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border: none;
            cursor: pointer;

            background: #F6EAD7;
            border-radius: 12px;
            padding: 0;
            cursor: pointer;
            color: #FFAE1F;
            transition: color .2s;
            flex-shrink: 0;

            transition: background .2s ease, transform .1s ease;
        }
        .btn-remove-question:hover { color: #e69900; }
        .btn-remove-question i { font-size: 20px; line-height: 1; font-weight: 900; }

        .js-matching-board .btn-remove-pair-match {
            width: 18px; height: 18px; min-width: 18px;
            display: inline-flex; align-items: center; justify-content: center;
            background: transparent !important; border: 0 !important; padding: 0 !important;
            cursor: pointer;
        }
        .js-matching-board .btn-remove-pair-match::before {
            content: "";
            width: 14px; height: 3px; border-radius: 999px;
            background: #FFAE1F; display: block; transition: .2s;
        }
        .js-matching-board .btn-remove-pair-match:hover::before {
            background: #ff9800; transform: scaleX(1.1);
        }
    </style>
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Pratinjau Soal" breadcrumb="Daftar Soal - Soal yang dipilih" />

    <div class="card border border-1 shadow-sm rounded-4 p-4 mb-4 mt-n1">
        <div class="row">
            <div class="col-12 col-sm-10">
                <h4 class="fw-bold mb-3">{{ $questionBank->title }}</h4>
                <div class="d-flex gap-2 flex-wrap mb-3">
                    <span class="badge bg-light-primary text-primary fw-semibold px-3 py-2 rounded-pill">
                        <svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.55556 0C2.61256 0 1.70819 0.374602 1.0414 1.0414C0.374602 1.70819 0 2.61256 0 3.55556V14.2222C0 15.1652 0.374602 16.0696 1.0414 16.7364C1.70819 17.4032 2.61256 17.7778 3.55556 17.7778H16V0H3.55556ZM7.11111 2.66667H13.3333V4.44445H7.11111V2.66667ZM1.77778 14.2222C1.77778 13.7507 1.96508 13.2985 2.29848 12.9651C2.63187 12.6317 3.08406 12.4444 3.55556 12.4444H14.2222V16H3.55556C3.08406 16 2.63187 15.8127 2.29848 15.4793C1.96508 15.1459 1.77778 14.6937 1.77778 14.2222Z" fill="#5D87FF"/>
                        </svg>
                        <span class="ms-2">{{ $questionBank->subject->name ?? '-' }}</span>
                    </span>
                    <span class="badge bg-light-primary text-primary fw-semibold px-3 py-2 rounded-pill">
                        <svg width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.0861 12.1538V6.51863L10.3828 10.6972C10.0957 10.8564 9.79266 10.936 9.47369 10.936C9.15471 10.936 8.85168 10.8564 8.5646 10.6972L0.478477 6.30373C0.303038 6.20822 0.179275 6.08883 0.107185 5.94556C0.0350961 5.8023 -0.000629564 5.64311 8.39419e-06 5.46801C0.000646352 5.29291 0.0366911 5.13372 0.108142 4.99046C0.179594 4.84719 0.303038 4.7278 0.478477 4.63229L8.5646 0.238792C8.70814 0.1592 8.85582 0.099346 9.00766 0.0592314C9.15949 0.0191169 9.31483 -0.000621815 9.47369 1.49237e-05C9.63254 0.000651662 9.7882 0.0207089 9.94067 0.0601867C10.0931 0.0996645 10.2405 0.1592 10.3828 0.238792L19.4976 5.20535C19.6571 5.28495 19.7809 5.40052 19.8689 5.55206C19.9569 5.7036 20.0006 5.86661 20 6.04107V12.1538C20 12.4244 19.9081 12.6514 19.7244 12.8348C19.5407 13.0181 19.3135 13.1095 19.0431 13.1089C18.7726 13.1082 18.5455 13.0165 18.3617 12.8338C18.178 12.6511 18.0861 12.4244 18.0861 12.1538ZM8.5646 16.4279L3.77991 13.8491C3.46093 13.674 3.21372 13.4352 3.03828 13.1328C2.86285 12.8303 2.77513 12.504 2.77513 12.1538V8.52435L8.5646 11.6523C8.85168 11.8115 9.15471 11.8911 9.47369 11.8911C9.79266 11.8911 10.0957 11.8115 10.3828 11.6523L16.1722 8.52435V12.1538C16.1722 12.504 16.0845 12.8303 15.9091 13.1328C15.7336 13.4352 15.4864 13.674 15.1675 13.8491L10.3828 16.4279C10.2392 16.5075 10.0919 16.5673 9.94067 16.6074C9.78947 16.6476 9.63381 16.6673 9.47369 16.6667C9.31356 16.666 9.1579 16.6463 9.0067 16.6074C8.8555 16.5686 8.70814 16.5087 8.5646 16.4279Z" fill="#5D87FF"/>
                        </svg>
                        <span class="ms-2">Kelas {{ $questionBank->levelClass->name ?? '-' }}</span>
                    </span>
                </div>
                <p class="text-dark fs-4">{{ $questionBank->description }}</p>
            </div>

            <div class="col-2 d-sm-flex justify-content-end my-3 my-sm-0 d-none">
                <div class="badge bg-light-primary d-flex flex-column align-items-center justify-content-center rounded-3"
                    style="width:100px;height:100px;">
                    <span class="text-primary">Soal Aktif</span>
                    <span class="text-primary" style="font-size:40px;font-weight:bold;">
                        {{ $questionBank->examQuestions()->where('is_active', 1)->count() }}
                    </span>
                </div>
            </div>
            <div class="col-12">
                <div class="badge bg-light-primary d-flex d-sm-none flex-column align-items-center justify-content-center rounded-3 my-3 py-3">
                    <span class="text-primary">Soal Aktif</span>
                    <span class="text-primary" style="font-size:40px;font-weight:bold;">
                        {{ $questionBank->examQuestions()->where('is_active', 1)->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="alert bg-light-warning text-warning mt-4 mb-0 rounded-4 fs-4 font-weight-bold">
            Seluruh soal yang Anda pilih akan digunakan sebagai materi ujian bagi siswa.
            Pastikan setiap soal telah diperiksa dengan baik, karena soal-soal tersebut
            akan menjadi bagian dari evaluasi pembelajaran yang dikerjakan oleh siswa
            sesuai dengan ketentuan ujian yang berlaku.
        </div>
    </div>

    <div class="d-flex justify-content-end align-items-center mb-3 gap-3">
        <small class="text-primary fs-5 fw-semibold">
            <span id="total-selected-questions">{{ $questionBank->total_active_questions }}</span>
            / <span id="total-questions">{{ $questionBank->total_questions }}</span> Soal Terpilih
        </small>
        <a href="{{ route('school.question-bank.detail', $questionBank->id) }}"
            class="btn btn-warning btn-md">Kembali</a>
    </div>

    <div class="card border border-1 shadow-sm rounded-4 p-4 mb-0">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <p class="m-0 fw-semibold text-dark">Pratinjau Soal yang Dipilih</p>
        </div>


        <div id="essay-list" class="d-flex flex-column gap-3 mb-4"></div>
        <div class="accordion mb-4" id="mc-list"></div>
        <div id="matching-list" class="d-flex flex-column gap-4 mb-4"></div>

        <div id="empty-state" class="d-none">
            <div class="d-flex flex-column justify-content-center align-items-center py-5">
                <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt="" width="300px">
                <p class="fs-5 text-dark text-center mt-2 mb-0">Soal belum ditambahkan</p>
            </div>
        </div>

        <button id="save-selected-questions" type="button" class="btn btn-primary fs-6">
            Simpan Soal
        </button>
    </div>

    @include('school.pages.question-bank.detail.widgets.decrease-active-questions')
    @include('school.pages.question-bank.detail.widgets.increase-active-questions')
@endsection

@section('script')
    @include('school.pages.question-bank.detail.scripts.review-questions')
@endsection
