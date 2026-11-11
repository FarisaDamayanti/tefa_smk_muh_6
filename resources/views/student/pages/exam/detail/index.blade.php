@extends('student.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/student/countdown-exam.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/student/detailujian.css') }}">
@endsection

@section('content')
    {{-- HEADER --}}
    <x-authenticated.breadcrumb title="Detail {{ $exam->title }}"
        breadcrumb="Sebelum anda memulai ujian pastikan anda telah memahami informasi dibawah" />

    <div class="row">
        <div class="col-lg-4 px-3 px-md-4">
            <div class="card">
                <div class="card-header bg-primary p-3">
                    <h4 class="mb-0 text-white card-title">Informasi Ujian</h4>
                </div>

                <div class="card-body p-3 bg-light-primary">
                    <div class="card mb-0">
                        <div class="card-body px-3 px-md-4">

                            <div class="exam-info-grid">
                                {{-- Tingkatan Kelas --}}
                                <div class="exam-info-label-wrap">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21.0016 15.9999V10.0999L12.9516 14.4749C12.6516 14.6416 12.3349 14.7249 12.0016 14.7249C11.6682 14.7249 11.3516 14.6416 11.0516 14.4749L2.60157 9.87492C2.41824 9.77492 2.2889 9.64992 2.21357 9.49992C2.13824 9.34992 2.1009 9.18325 2.10157 8.99992C2.10224 8.81658 2.1399 8.64992 2.21457 8.49992C2.28924 8.34992 2.41824 8.22492 2.60157 8.12492L11.0516 3.52492C11.2016 3.44158 11.3559 3.37892 11.5146 3.33692C11.6732 3.29492 11.8356 3.27425 12.0016 3.27492C12.1676 3.27558 12.3302 3.29658 12.4896 3.33792C12.6489 3.37925 12.8029 3.44158 12.9516 3.52492L22.4766 8.72492C22.6432 8.80825 22.7726 8.92925 22.8646 9.08792C22.9566 9.24658 23.0022 9.41725 23.0016 9.59992V15.9999C23.0016 16.2833 22.9056 16.5209 22.7136 16.7129C22.5216 16.9049 22.2842 17.0006 22.0016 16.9999C21.7189 16.9993 21.4816 16.9033 21.2896 16.7119C21.0976 16.5206 21.0016 16.2833 21.0016 15.9999ZM11.0516 20.4749L6.05157 17.7749C5.71824 17.5916 5.4599 17.3416 5.27657 17.0249C5.09324 16.7083 5.00157 16.3666 5.00157 15.9999V12.1999L11.0516 15.4749C11.3516 15.6416 11.6682 15.7249 12.0016 15.7249C12.3349 15.7249 12.6516 15.6416 12.9516 15.4749L19.0016 12.1999V15.9999C19.0016 16.3666 18.9099 16.7083 18.7266 17.0249C18.5432 17.3416 18.2849 17.5916 17.9516 17.7749L12.9516 20.4749C12.8016 20.5583 12.6476 20.6209 12.4896 20.6629C12.3316 20.7049 12.1689 20.7256 12.0016 20.7249C11.8342 20.7243 11.6716 20.7036 11.5136 20.6629C11.3556 20.6223 11.2016 20.5596 11.0516 20.4749Z"
                                            fill="#5D87FF" />
                                    </svg>
                                    <span class="exam-info-label">Tingkatan Kelas</span>
                                </div>
                                <div class="exam-info-value">{{ $exam->questionBank->levelClass->name ?? '-' }}</div>

                                {{-- Semester --}}
                                <div class="exam-info-label-wrap">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M4 3C3.73478 3 3.48043 3.10536 3.29289 3.29289C3.10536 3.48043 3 3.73478 3 4V20C3 20.2652 3.10536 20.5196 3.29289 20.7071C3.48043 20.8946 3.73478 21 4 21H14C14.2652 21 14.5196 20.8946 14.7071 20.7071C14.8946 20.5196 15 20.2652 15 20V15.297L16 20C16.0552 20.2594 16.2111 20.4862 16.4336 20.6306C16.656 20.775 16.9266 20.8251 17.186 20.77L21.098 19.938C21.3574 19.8828 21.5842 19.7269 21.7286 19.5044C21.873 19.282 21.9231 19.0114 21.868 18.752L18.958 5.058C18.9028 4.79864 18.7469 4.57181 18.5244 4.42741C18.302 4.28302 18.0314 4.23287 17.772 4.288L14.992 4.878C14.9622 4.63583 14.8449 4.41289 14.6622 4.25118C14.4795 4.08946 14.244 4.00013 14 4H10C10 3.73478 9.89464 3.48043 9.70711 3.29289C9.51957 3.10536 9.26522 3 9 3H4ZM10 6H13V14H10V6ZM10 19V16H13V19H10ZM8 5V15H5V5H8ZM8 17V19H5V17H8ZM17.332 16.65L19.288 16.234L19.704 18.19L17.748 18.606L17.332 16.65ZM16.916 14.693L15.253 6.868L17.209 6.452L18.873 14.278L16.916 14.693Z"
                                            fill="#5D87FF" />
                                    </svg>
                                    <span class="exam-info-label">Semester</span>
                                </div>
                                <div class="exam-info-value text-capitalize">
                                    {{ ucfirst($exam->semester ?? '-') }} {{ $exam->schoolYear->school_year ?? '-' }}
                                </div>

                                {{-- Mata Pelajaran --}}
                                <div class="exam-info-label-wrap">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6 22C5.45 22 4.97933 21.8043 4.588 21.413C4.19667 21.0217 4.00067 20.5507 4 20V4C4 3.45 4.196 2.97933 4.588 2.588C4.98 2.19667 5.45067 2.00067 6 2H18C18.55 2 19.021 2.196 19.413 2.588C19.805 2.98 20.0007 3.45067 20 4V20C20 20.55 19.8043 21.021 19.413 21.413C19.0217 21.805 18.5507 22.0007 18 22H6ZM11 11L13.5 9.5L16 11V4H11V11Z"
                                            fill="#5D87FF" />
                                    </svg>
                                    <span class="exam-info-label">Mata Pelajaran</span>
                                </div>
                                <div class="exam-info-value">{{ $exam->questionBank->subject->name ?? '-' }}</div>

                                {{-- Ujian Dimulai pada --}}
                                <div class="exam-info-label-wrap">
                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.08203 9C2.08203 7.114 2.08203 6.172 2.69245 5.586C3.30286 5 4.28411 5 6.2487 5H18.7487C20.7133 5 21.6945 5 22.3049 5.586C22.9154 6.172 22.9154 7.114 22.9154 9C22.9154 9.471 22.9154 9.707 22.7633 9.854C22.6102 10 22.3633 10 21.8737 10H3.1237C2.63307 10 2.38724 10 2.23411 9.854C2.08203 9.707 2.08203 9.47 2.08203 9ZM2.08203 18C2.08203 19.886 2.08203 20.828 2.69245 21.414C3.30286 22 4.28411 22 6.2487 22H18.7487C20.7133 22 21.6945 22 22.3049 21.414C22.9154 20.828 22.9154 19.886 22.9154 18V13C22.9154 12.529 22.9154 12.293 22.7633 12.146C22.6102 12 22.3633 12 21.8737 12H3.1237C2.63307 12 2.38724 12 2.23411 12.146C2.08203 12.293 2.08203 12.53 2.08203 13V18Z"
                                            fill="#5D87FF" />
                                        <path d="M7.28906 3V6M17.7057 3V6" stroke="#5D87FF" stroke-width="2"
                                            stroke-linecap="round" />
                                    </svg>
                                    <span class="exam-info-label">Ujian Dimulai Pada</span>
                                </div>
                                <div class="exam-info-value">
                                    {{ \Carbon\Carbon::parse($exam->exam_date)->format('d F Y, H:i') }}
                                </div>

                                {{-- Ujian Berakhir pada --}}
                                <div class="exam-info-label-wrap">
                                    <h3 class="m-0 p-0">
                                        <i class="ti ti-clock-filled text-primary"></i>
                                    </h3>
                                    <span class="exam-info-label">Ujian Berakhir Pada</span>
                                </div>
                                <div class="exam-info-value">
                                    {{ \Carbon\Carbon::parse($exam->exam_date)->format('d F Y, H:i') }}
                                </div>

                                {{-- Jumlah Soal --}}
                                <div class="exam-info-label-wrap">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8 9C7.71667 9 7.47934 8.904 7.288 8.712C7.09667 8.52 7.00067 8.28267 7 8C6.99934 7.71733 7.09534 7.48 7.288 7.288C7.48067 7.096 7.718 7 8 7H20C20.2833 7 20.521 7.096 20.713 7.288C20.905 7.48 21.0007 7.71733 21 8C20.9993 8.28267 20.9033 8.52033 20.712 8.713C20.5207 8.90567 20.2833 9.00133 20 9H8ZM8 13C7.71667 13 7.47934 12.904 7.288 12.712C7.09667 12.52 7.00067 12.2827 7 12C6.99934 11.7173 7.09534 11.48 7.288 11.288C7.48067 11.096 7.718 11 8 11H20C20.2833 11 20.521 11.096 20.713 11.288C20.905 11.48 21.0007 11.7173 21 12C20.9993 12.2827 20.9033 12.5203 20.712 12.713C20.5207 12.9057 20.2833 13.0013 20 13H8ZM8 17C7.71667 17 7.47934 16.904 7.288 16.712C7.09667 16.52 7.00067 16.2827 7 16C6.99934 15.7173 7.09534 15.48 7.288 15.288C7.48067 15.096 7.718 15 8 15H20C20.2833 15 20.521 15.096 20.713 15.288C20.905 15.48 21.0007 15.7173 21 16C20.9993 16.2827 20.9033 16.5203 20.712 16.713C20.5207 16.9057 20.2833 17.0013 20 17H8ZM4 9C3.71667 9 3.47934 8.904 3.288 8.712C3.09667 8.52 3.00067 8.28267 3 8C2.99934 7.71733 3.09534 7.48 3.288 7.288C3.48067 7.096 3.718 7 4 7C4.282 7 4.51967 7.096 4.713 7.288C4.90634 7.48 5.002 7.71733 5 8C4.998 8.28267 4.902 8.52033 4.712 8.713C4.522 8.90567 4.28467 9.00133 4 9ZM4 13C3.71667 13 3.47934 12.904 3.288 12.712C3.09667 12.52 3.00067 12.2827 3 12C2.99934 11.7173 3.09534 11.48 3.288 11.288C3.48067 11.096 3.718 11 4 11C4.282 11 4.51967 11.096 4.713 11.288C4.90634 11.48 5.002 11.7173 5 12C4.998 12.2827 4.902 12.5203 4.712 12.713C4.522 12.9057 4.28467 13.0013 4 13ZM4 17C3.71667 17 3.47934 16.904 3.288 16.712C3.09667 16.52 3.00067 16.2827 3 16C2.99934 15.7173 3.09534 15.48 3.288 15.288C3.48067 15.096 3.718 15 4 15C4.282 15 4.51967 15.096 4.713 15.288C4.90634 15.48 5.002 15.7173 5 16C4.998 16.2827 4.902 16.5203 4.712 16.713C4.522 16.9057 4.28467 17.0013 4 17Z"
                                            fill="#5D87FF" />
                                    </svg>
                                    <span class="exam-info-label">Jumlah Soal</span>
                                </div>
                                <div class="exam-info-value">{{ $exam->total_questions ?? '-' }}</div>

                                {{-- Nilai Minimal (KKM) --}}
                                <div class="exam-info-label-wrap">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.9984 17.275L7.84838 19.775C7.66504 19.8916 7.47338 19.9416 7.27338 19.9249C7.07338 19.9083 6.89838 19.8416 6.74838 19.725C6.59838 19.6083 6.48171 19.4626 6.39838 19.288C6.31504 19.1133 6.29838 18.9173 6.34838 18.7L7.44838 13.975L3.77338 10.8C3.60671 10.65 3.50271 10.479 3.46138 10.287C3.42004 10.095 3.43238 9.90762 3.49838 9.72495C3.56438 9.54228 3.66438 9.39228 3.79838 9.27495C3.93238 9.15762 4.11571 9.08262 4.34838 9.04995L9.19838 8.62495L11.0734 4.17495C11.1567 3.97495 11.286 3.82495 11.4614 3.72495C11.6367 3.62495 11.8157 3.57495 11.9984 3.57495C12.181 3.57495 12.36 3.62495 12.5354 3.72495C12.7107 3.82495 12.84 3.97495 12.9234 4.17495L14.7984 8.62495L19.6484 9.04995C19.8817 9.08328 20.065 9.15828 20.1984 9.27495C20.3317 9.39162 20.4317 9.54162 20.4984 9.72495C20.565 9.90829 20.5777 10.096 20.5364 10.288C20.495 10.48 20.3907 10.6506 20.2234 10.8L16.5484 13.975L17.6484 18.7C17.6984 18.9166 17.6817 19.1126 17.5984 19.288C17.515 19.4633 17.3984 19.609 17.2484 19.725C17.0984 19.841 16.9234 19.9076 16.7234 19.9249C16.5234 19.9423 16.3317 19.8923 16.1484 19.775L11.9984 17.275Z"
                                            fill="#5D87FF" />
                                    </svg>
                                    <span class="exam-info-label">Nilai Minimal (KKM)</span>
                                </div>
                                <div class="exam-info-value">{{ $exam->minimum_score ?? '-' }}</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Aturan Ujian --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary p-3">
                    <h4 class="mb-0 text-white card-title">Aturan Ujian</h4>
                </div>

                <div class="card-body p-4 bg-light-primary">
                    <div class="card p-3">
                        {!! trans('alert.rules.exam') !!}
                    </div>

                    @if (!empty(strip_tags($exam->rules)))
                        <div class="card p-3">
                            {!! $exam->rules !!}
                        </div>
                    @endif

                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <a href="{{ route('student.exams.index') }}"
                                class="btn btn-warning d-flex align-items-center gap-1">
                                <i class="ti ti-arrow-narrow-left fs-5 fw-semibold"></i> <span>Kembali</span>
                            </a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#reading-the-rules-confirmation-modal">
                                <span>Mulai Ujian</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="exam-start-overlay" class="exam-overlay d-none">
        <div class="exam-overlay-content">
            <h4 class="mb-2 text-white" id="exam-start-at">Ujian akan dimulai pada</h4>
            <h1 id="exam-countdown">3</h1>
            <h3 class="mt-2 text-white d-none" id="exam-start-text">Selamat mengerjakan!</h3>
        </div>
    </div>

    @include('student.pages.exam.detail.widgets.modal-reading-rules-confirmation')
@endsection

@section('script')
    @include('student.pages.exam.detail.scripts.start-exam')
@endsection
