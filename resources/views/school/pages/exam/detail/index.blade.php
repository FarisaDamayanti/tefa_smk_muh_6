@extends('school.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/exam-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/detail-exam.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Detail Ujian" breadcrumb="Informasi Lengkap terkait Ujian" />

    <div class="container-fluid d-flex justify-content-end pb-4">
        <a href="{{ route('school.exams.index') }}" class="btn btn-warning d-inline-flex align-items-center gap-2">
            Kembali
        </a>
    </div>
    <div class="row d-none d-md-flex">
        <div class="col-12">
            <div class="row g-4">

                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card score-card info text-white border-0 boodered-2 rounded-2">
                        <div class="card-body position-relative overflow-hidden">
                            <div class="bg-shapes">
                                <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                    <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                        transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)" fill="white"
                                        fill-opacity="0.15" />
                                    <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                        transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)" fill="white"
                                        fill-opacity="0.15" />
                                </svg>
                                <svg class="shape-bottom" width="130" height="80" viewBox="0 0 182 70" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M39.5 37.0007C19.6814 39.5028 0 69.5006 0 69.5006L184 69.5009C184 69.5009 176.129 -0.19319 166 0.00038283C150.805 0.290765 148.863 22.0332 134.5 27.0007C120.72 31.7665 111.626 18.3884 97.5 22.0007C84.0632 25.4366 82.1577 40.0882 68.5 42.5007C57.1486 44.5058 50.9363 35.5568 39.5 37.0007Z"
                                        fill="url(#paint0_linear_7747_7444)" />
                                    <defs>
                                        <linearGradient id="paint0_linear_7747_7444" x1="92" y1="-0.000244141"
                                            x2="92" y2="69.5006" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#E6FFFA" />
                                            <stop offset="1" stop-color="#E6FFFA" stop-opacity="0" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <h4 class="mb-2 fw-semibold text-white text-capitalize text-center">
                                Siswa Telah Mengerjakan
                            </h4>
                            <h1 class="fw-bold text-white text-center display-5">
                                {{ $exam->examStatistics['student_submitted'] ?? 0 }}
                            </h1>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card score-card warning text-white border-0">
                        <div class="card-body position-relative overflow-hidden">
                            <div class="bg-shapes">
                                <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                    <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                        transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)" fill="white"
                                        fill-opacity="0.15" />
                                    <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                        transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)" fill="white"
                                        fill-opacity="0.15" />
                                </svg>

                                <svg class="shape-bottom" width="130" height="80" viewBox="0 0 184 70" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M144.5 37.0007C164.319 39.5028 184 69.5006 184 69.5006L0 69.5009C0 69.5009 7.87077 -0.19319 18 0.00038283C33.1951 0.290765 35.1369 22.0332 49.5 27.0007C63.2799 31.7665 72.3738 18.3884 86.5 22.0007C99.9368 25.4366 101.842 40.0882 115.5 42.5007C126.851 44.5058 133.064 35.5568 144.5 37.0007Z"
                                        fill="url(#paint0_linear_7747_7428)" />
                                    <defs>
                                        <linearGradient id="paint0_linear_7747_7428" x1="92" y1="0.500732"
                                            x2="92" y2="69.5007" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#FFECD7" />
                                            <stop offset="1" stop-color="#FFECD7" stop-opacity="0" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <h4 class="mb-2 fw-semibold text-white text-capitalize text-center">
                                Total Siswa Tidak Mengerjakan
                            </h4>
                            <h1 class="fw-bold text-white text-center display-5">
                                {{ $exam->examStatistics['student_not_submitted'] ?? 0 }}
                            </h1>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card score-card success text-white border-0">
                        <div class="card-body position-relative overflow-hidden">
                            <div class="bg-shapes">
                                <svg class="shape-bottom" width="90" height="80"
                                    style="right: 20px; bottom: -5px;" viewBox="0 0 132 100" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M119.161 78.5412V42.1253L68.4071 69.1286C66.5156 70.1573 64.5191 70.6717 62.4175 70.6717C60.3159 70.6717 58.3194 70.1573 56.428 69.1286L3.15245 40.7366C1.99657 40.1193 1.18115 39.3478 0.706192 38.422C0.231231 37.4962 -0.00414789 36.4675 5.53052e-05 35.3359C0.0042585 34.2043 0.24174 33.1756 0.712498 32.2498C1.18326 31.324 1.99657 30.5524 3.15245 29.9352L56.428 1.54314C57.3737 1.02879 58.3467 0.642003 59.3471 0.382771C60.3475 0.123539 61.3709 -0.00401835 62.4175 9.64414e-05C63.4641 0.00421124 64.4897 0.133827 65.4943 0.388944C66.4988 0.644062 67.4698 1.02879 68.4071 1.54314L128.46 33.6385C129.511 34.1529 130.326 34.8997 130.907 35.879C131.487 36.8584 131.774 37.9118 131.77 39.0392V78.5412C131.77 80.29 131.165 81.7569 129.955 82.942C128.744 84.1271 127.248 84.7175 125.465 84.7134C123.683 84.7093 122.187 84.1168 120.976 82.9358C119.766 81.7549 119.161 80.29 119.161 78.5412ZM56.428 106.162L24.904 89.4969C22.8024 88.3653 21.1737 86.8223 20.0178 84.8677C18.8619 82.9132 18.284 80.8044 18.284 78.5412V55.0869L56.428 75.3008C58.3194 76.3295 60.3159 76.8439 62.4175 76.8439C64.5191 76.8439 66.5156 76.3295 68.4071 75.3008L106.551 55.0869V78.5412C106.551 80.8044 105.973 82.9132 104.817 84.8677C103.661 86.8223 102.033 88.3653 99.9311 89.4969L68.4071 106.162C67.4614 106.676 66.4904 107.063 65.4943 107.322C64.4981 107.581 63.4725 107.709 62.4175 107.705C61.3625 107.701 60.337 107.573 59.3408 107.322C58.3446 107.071 57.3737 106.684 56.428 106.162Z"
                                        fill="url(#paint0_linear_7747_7329)" />
                                    <defs>
                                        <linearGradient id="paint0_linear_7747_7329" x1="66" y1="-9.05816e-09"
                                            x2="66" y2="101.5" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="white" stop-opacity="0.6" />
                                            <stop offset="1" stop-color="white" stop-opacity="0" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <h4 class="mb-2 fw-semibold text-white text-capitalize text-center">
                                Jumlah Siswa
                            </h4>
                            <h1 class="fw-bold text-white text-center display-5">
                                {{ $exam->examStatistics['total_students'] ?? 0 }}
                            </h1>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ===== MOBILE: Swiper slider (sembunyi di desktop) ===== --}}
    <div class="d-md-none">
        <div class="swiper score-swiper">
            <div class="swiper-wrapper">

                <div class="swiper-slide">
                    <div class="card score-card info text-white border-0">
                        <div class="card-body position-relative overflow-hidden">
                            <div class="bg-shapes">
                                <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                    <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                        transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)" fill="white"
                                        fill-opacity="0.15" />
                                    <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                        transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)" fill="white"
                                        fill-opacity="0.15" />
                                </svg>
                                <svg class="shape-bottom" width="130" height="80" viewBox="0 0 182 70"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M39.5 37.0007C19.6814 39.5028 0 69.5006 0 69.5006L184 69.5009C184 69.5009 176.129 -0.19319 166 0.00038283C150.805 0.290765 148.863 22.0332 134.5 27.0007C120.72 31.7665 111.626 18.3884 97.5 22.0007C84.0632 25.4366 82.1577 40.0882 68.5 42.5007C57.1486 44.5058 50.9363 35.5568 39.5 37.0007Z"
                                        fill="url(#paint0_linear_7747_7444)" />
                                    <defs>
                                        <linearGradient id="paint0_linear_7747_7444" x1="92" y1="-0.000244141"
                                            x2="92" y2="69.5006" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#E6FFFA" />
                                            <stop offset="1" stop-color="#E6FFFA" stop-opacity="0" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <h4 class="mb-2 fw-semibold text-white text-capitalize text-center">
                                Siswa Telah Mengerjakan
                            </h4>
                            <h1 class="fw-bold text-white text-center display-5">
                                {{ $exam->examStatistics['student_submitted'] ?? 0 }}
                            </h1>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="card score-card warning text-white border-0">
                        <div class="card-body position-relative overflow-hidden">
                            <div class="bg-shapes">
                                <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                    <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                        transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)" fill="white"
                                        fill-opacity="0.15" />
                                    <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                        transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)" fill="white"
                                        fill-opacity="0.15" />
                                </svg>

                                <svg class="shape-bottom" width="130" height="80" viewBox="0 0 184 70"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M144.5 37.0007C164.319 39.5028 184 69.5006 184 69.5006L0 69.5009C0 69.5009 7.87077 -0.19319 18 0.00038283C33.1951 0.290765 35.1369 22.0332 49.5 27.0007C63.2799 31.7665 72.3738 18.3884 86.5 22.0007C99.9368 25.4366 101.842 40.0882 115.5 42.5007C126.851 44.5058 133.064 35.5568 144.5 37.0007Z"
                                        fill="url(#paint0_linear_7747_7428)" />
                                    <defs>
                                        <linearGradient id="paint0_linear_7747_7428" x1="92" y1="0.500732"
                                            x2="92" y2="69.5007" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#FFECD7" />
                                            <stop offset="1" stop-color="#FFECD7" stop-opacity="0" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <h4 class="mb-2 fw-semibold text-white text-capitalize text-center">
                                Total Siswa Tidak Mengerjakan
                            </h4>
                            <h1 class="fw-bold text-white text-center display-5">
                                {{ $exam->examStatistics['student_not_submitted'] ?? 0 }}
                            </h1>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="card score-card success text-white border-0">
                        <div class="card-body position-relative overflow-hidden">
                            <div class="bg-shapes">
                                <svg class="shape-bottom" width="90" height="80"
                                    style="right: 20px; bottom: -5px;" viewBox="0 0 132 100" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M119.161 78.5412V42.1253L68.4071 69.1286C66.5156 70.1573 64.5191 70.6717 62.4175 70.6717C60.3159 70.6717 58.3194 70.1573 56.428 69.1286L3.15245 40.7366C1.99657 40.1193 1.18115 39.3478 0.706192 38.422C0.231231 37.4962 -0.00414789 36.4675 5.53052e-05 35.3359C0.0042585 34.2043 0.24174 33.1756 0.712498 32.2498C1.18326 31.324 1.99657 30.5524 3.15245 29.9352L56.428 1.54314C57.3737 1.02879 58.3467 0.642003 59.3471 0.382771C60.3475 0.123539 61.3709 -0.00401835 62.4175 9.64414e-05C63.4641 0.00421124 64.4897 0.133827 65.4943 0.388944C66.4988 0.644062 67.4698 1.02879 68.4071 1.54314L128.46 33.6385C129.511 34.1529 130.326 34.8997 130.907 35.879C131.487 36.8584 131.774 37.9118 131.77 39.0392V78.5412C131.77 80.29 131.165 81.7569 129.955 82.942C128.744 84.1271 127.248 84.7175 125.465 84.7134C123.683 84.7093 122.187 84.1168 120.976 82.9358C119.766 81.7549 119.161 80.29 119.161 78.5412ZM56.428 106.162L24.904 89.4969C22.8024 88.3653 21.1737 86.8223 20.0178 84.8677C18.8619 82.9132 18.284 80.8044 18.284 78.5412V55.0869L56.428 75.3008C58.3194 76.3295 60.3159 76.8439 62.4175 76.8439C64.5191 76.8439 66.5156 76.3295 68.4071 75.3008L106.551 55.0869V78.5412C106.551 80.8044 105.973 82.9132 104.817 84.8677C103.661 86.8223 102.033 88.3653 99.9311 89.4969L68.4071 106.162C67.4614 106.676 66.4904 107.063 65.4943 107.322C64.4981 107.581 63.4725 107.709 62.4175 107.705C61.3625 107.701 60.337 107.573 59.3408 107.322C58.3446 107.071 57.3737 106.684 56.428 106.162Z"
                                        fill="url(#paint0_linear_7747_7329)" />
                                    <defs>
                                        <linearGradient id="paint0_linear_7747_7329" x1="66" y1="-9.05816e-09"
                                            x2="66" y2="101.5" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="white" stop-opacity="0.6" />
                                            <stop offset="1" stop-color="white" stop-opacity="0" />
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </div>
                            <h4 class="mb-2 fw-semibold text-white text-capitalize text-center">
                                Jumlah Siswa
                            </h4>
                            <h1 class="fw-bold text-white text-center display-5">
                                {{ $exam->examStatistics['total_students'] ?? 0 }}
                            </h1>
                        </div>
                    </div>
                </div>

            </div>
            <div class="swiper-pagination mt-3"></div>
        </div>
    </div>

    <div class="my-1">
        <div class="card w-100 rounded-3 mb-4">
            <div class="card-header bg-primary px-4">
                <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                    <h4 class="mb-0 text-white card-title">Informasi Ujian</h4>
                </div>
            </div>

            <div class="card-body p-4 bg-light-primary">
                <div class="card mb-0">
                    <div class="card-body">

                        {{-- Title + Token --}}
                        <div class="row align-items-center g-3 mb-3">
                            <div class="col-12 col-sm-9">
                                <h3 class="mb-0">
                                    {{ $exam->title ?? '-' }}
                                </h3>
                                <div class="mt-3">
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                        Tingkat Kelas {{ $exam->questionBank?->levelClass?->name ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-12 col-sm-3 text-sm-end">
                                @if ($exam->token)
                                    <div
                                        class="badge bg-white border border-primary text-primary w-100 w-sm-auto text-center fs-5 py-2 fw-semibold">
                                        Token: {{ $exam->token }}
                                    </div>
                                @else
                                    <button type="button" class="btn btn-primary btn-md fs-4 w-100 w-sm-auto"
                                        id="btn-generate">
                                        Buat Token
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Chips (jadi bootstrap badges) --}}
                        <div class="row g-3">

                            {{-- Tanggal Ujian --}}
                            <div class="col-12 col-sm-6 col-lg-auto">
                                <div
                                    class="badge bg-white border border-primary d-flex align-items-center gap-2 py-2 px-3 rounded-3 w-100">
                                    {{-- icon calendar (re-use svg kamu) --}}
                                    <svg width="21" height="20" viewBox="0 0 21 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M0 7C0 5.114 -6.20882e-08 4.172 0.610417 3.586C1.22083 3 2.20208 3 4.16667 3H16.6667C18.6313 3 19.6125 3 20.2229 3.586C20.8333 4.172 20.8333 5.114 20.8333 7C20.8333 7.471 20.8333 7.707 20.6813 7.854C20.5281 8 20.2813 8 19.7917 8H1.04167C0.551042 8 0.305208 8 0.152083 7.854C-9.31323e-08 7.707 0 7.47 0 7ZM0 16C0 17.886 -6.20882e-08 18.828 0.610417 19.414C1.22083 20 2.20208 20 4.16667 20H16.6667C18.6313 20 19.6125 20 20.2229 19.414C20.8333 18.828 20.8333 17.886 20.8333 16V11C20.8333 10.529 20.8333 10.293 20.6813 10.146C20.5281 10 20.2813 10 19.7917 10H1.04167C0.551042 10 0.305208 10 0.152083 10.146C-9.31323e-08 10.293 0 10.53 0 11V16Z"
                                            fill="#5D87FF" />
                                        <path d="M5.20801 1V4M15.6247 1V4" stroke="#5D87FF" stroke-width="2"
                                            stroke-linecap="round" />
                                    </svg>


                                    <span class="text-dark small lh-sm">
                                        {{ $exam->exam_date ? $exam->exam_date->translatedFormat('d F Y') : '-' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-auto">
                                <div
                                    class="badge bg-white border border-primary d-flex align-items-center gap-2 py-2 px-3 rounded-3 w-100">
                                    {{-- icon calendar (re-use svg kamu) --}}
                                    <svg width="24" height="20" viewBox="0 0 24 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21.7033 14.5845V7.82235L12.4593 12.8367C12.1148 13.0277 11.7512 13.1232 11.3684 13.1232C10.9856 13.1232 10.622 13.0277 10.2775 12.8367L0.574172 7.56447C0.363646 7.44986 0.21513 7.30659 0.128622 7.13468C0.0421153 6.96276 -0.000755477 6.77173 1.0073e-05 6.56161C0.000775623 6.35149 0.0440294 6.16047 0.129771 5.98855C0.215512 5.81663 0.363646 5.67336 0.574172 5.55875L10.2775 0.28655C10.4498 0.19104 10.627 0.119215 10.8092 0.0710777C10.9914 0.0229403 11.1778 -0.000746178 11.3684 1.79084e-05C11.559 0.000781995 11.7458 0.0248507 11.9288 0.072224C12.1118 0.119597 12.2886 0.19104 12.4593 0.28655L23.3971 6.24643C23.5885 6.34194 23.737 6.48062 23.8427 6.66247C23.9483 6.84432 24.0008 7.03993 24 7.24929V14.5845C24 14.9093 23.8898 15.1817 23.6693 15.4017C23.4488 15.6218 23.1763 15.7314 22.8517 15.7306C22.5271 15.7299 22.2545 15.6199 22.0341 15.4006C21.8136 15.1813 21.7033 14.9093 21.7033 14.5845ZM10.2775 19.7134L4.53589 16.6189C4.15312 16.4088 3.85647 16.1222 3.64594 15.7593C3.43541 15.3964 3.33015 15.0048 3.33015 14.5845V10.2292L10.2775 13.9828C10.622 14.1738 10.9856 14.2693 11.3684 14.2693C11.7512 14.2693 12.1148 14.1738 12.4593 13.9828L19.4067 10.2292V14.5845C19.4067 15.0048 19.3014 15.3964 19.0909 15.7593C18.8804 16.1222 18.5837 16.4088 18.201 16.6189L12.4593 19.7134C12.2871 19.809 12.1102 19.8808 11.9288 19.9289C11.7474 19.9771 11.5606 20.0007 11.3684 20C11.1763 19.9992 10.9895 19.9755 10.808 19.9289C10.6266 19.8823 10.4498 19.8105 10.2775 19.7134Z"
                                            fill="#5D87FF" />
                                    </svg>

                                    <span class="text-dark small lh-sm">
                                        Semester {{ $exam->semester ? ucfirst($exam->semester) : '-' }},
                                    </span>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-auto">
                                <div
                                    class="badge bg-white border border-primary d-flex align-items-center gap-2 py-2 px-3 rounded-3 w-100">
                                    {{-- icon calendar (re-use svg kamu) --}}
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_10223_8707)">
                                            <path
                                                d="M7.9 2.89077C6.48867 2.21694 4.51602 1.88686 1.875 1.87514C1.62593 1.87176 1.38174 1.94439 1.175 2.08335C1.00531 2.19805 0.866403 2.35268 0.770498 2.53366C0.674593 2.71464 0.62463 2.91642 0.625002 3.12124V14.297C0.625002 15.0525 1.1625 15.6224 1.875 15.6224C4.65117 15.6224 7.43594 15.8818 9.10391 17.4583C9.12673 17.48 9.15541 17.4945 9.18639 17.5C9.21737 17.5055 9.24928 17.5018 9.27816 17.4893C9.30704 17.4768 9.33162 17.4561 9.34883 17.4298C9.36605 17.4034 9.37515 17.3726 9.375 17.3412V4.1728C9.37504 4.08396 9.35603 3.99614 9.31923 3.91528C9.28244 3.83441 9.22873 3.76238 9.16172 3.70405C8.77965 3.37742 8.35525 3.10386 7.9 2.89077ZM18.825 2.08217C18.6182 1.94356 18.374 1.87134 18.125 1.87514C15.484 1.88686 13.5113 2.21538 12.1 2.89077C11.6448 3.10347 11.2203 3.37649 10.8379 3.70249C10.771 3.76091 10.7174 3.83296 10.6807 3.91381C10.644 3.99466 10.625 4.08244 10.625 4.17124V17.3404C10.625 17.3706 10.6339 17.4002 10.6506 17.4254C10.6673 17.4506 10.6911 17.4703 10.719 17.482C10.7469 17.4937 10.7776 17.4969 10.8073 17.4913C10.837 17.4856 10.8643 17.4713 10.8859 17.4501C11.8887 16.454 13.6484 15.6212 18.1266 15.6216C18.4581 15.6216 18.776 15.4899 19.0104 15.2555C19.2449 15.0211 19.3766 14.7031 19.3766 14.3716V3.12163C19.377 2.9164 19.3269 2.71422 19.2307 2.53294C19.1346 2.35165 18.9952 2.19684 18.825 2.08217Z"
                                                fill="#5D87FF" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_10223_8707">
                                                <rect width="20" height="20" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>


                                    <span class="text-dark small lh-sm">
                                        Tahun {{ $exam->schoolYear?->school_year ?? '-' }}
                                    </span>
                                </div>
                            </div>
                            {{-- Mata Pelajaran --}}
                            <div class="col-12 col-sm-6 col-lg-auto">
                                <div
                                    class="badge bg-white border border-primary d-flex align-items-center gap-2 py-2 px-3 rounded-3 w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="22"
                                        height="22" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M6 22q-.825 0-1.412-.587T4 20V4q0-.825.588-1.412T6 2h12q.825 0 1.413.588T20 4v16q0 .825-.587 1.413T18 22zm5-11l2.5-1.5L16 11V4h-5z" />
                                    </svg>

                                    <span class="text-dark small lh-sm">
                                        {{ $exam->questionBank?->subject?->name ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Jumlah Soal --}}
                            <div class="col-12 col-sm-6 col-lg-auto">
                                <div
                                    class="badge bg-white border border-primary d-flex align-items-center gap-2 py-2 px-3 rounded-3 w-100">
                                    <i class="ti ti-list text-primary fs-5"></i>
                                    <span class="text-dark small lh-sm">
                                        Jumlah Soal {{ $exam->total_questions ?? 0 }}
                                    </span>
                                </div>
                            </div>

                            {{-- KKM --}}
                            <div class="col-12 col-sm-6 col-lg-auto">
                                <div
                                    class="badge bg-white border border-primary d-flex align-items-center gap-2 py-2 px-3 rounded-3 w-100">
                                    <i class="ti ti-star-filled text-primary fs-5"></i>
                                    <span class="text-dark small lh-sm">
                                        KKM {{ $exam->minimum_score ?? 0 }}
                                    </span>
                                </div>
                            </div>

                            {{-- Durasi --}}
                            <div class="col-12 col-sm-6 col-lg-auto">
                                <div
                                    class="badge bg-white border border-primary d-flex align-items-center gap-2 py-2 px-3 rounded-3 w-100">
                                    <svg width="25" height="25" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10 18C12.1217 18 14.1566 17.1571 15.6569 15.6569C17.1571 14.1566 18 12.1217 18 10C18 7.87827 17.1571 5.84344 15.6569 4.34315C14.1566 2.84285 12.1217 2 10 2C7.87827 2 5.84344 2.84285 4.34315 4.34315C2.84285 5.84344 2 7.87827 2 10C2 12.1217 2.84285 14.1566 4.34315 15.6569C5.84344 17.1571 7.87827 18 10 18ZM10 0C11.3132 0 12.6136 0.258658 13.8268 0.761205C15.0401 1.26375 16.1425 2.00035 17.0711 2.92893C17.9997 3.85752 18.7362 4.95991 19.2388 6.17317C19.7413 7.38642 20 8.68678 20 10C20 12.6522 18.9464 15.1957 17.0711 17.0711C15.1957 18.9464 12.6522 20 10 20C4.47 20 0 15.5 0 10C0 7.34784 1.05357 4.8043 2.92893 2.92893C4.8043 1.05357 7.34784 0 10 0ZM10.5 5V10.25L15 12.92L14.25 14.15L9 11V5H10.5Z"
                                            fill="#5D87FF" />
                                    </svg>

                                    <span class="text-dark small lh-sm">
                                        Durasi Ujian {{ $exam->duration ?? 0 }} Menit
                                    </span>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-lg-auto">
                                <div
                                    class="badge bg-white border border-primary d-flex align-items-center gap-2 py-2 px-3 rounded-3 w-100 text-wrap">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_10122_7443)">
                                            <path
                                                d="M16.5 17.25L19.36 18.94L18.61 20.16L15 18V13H16.5V17.25ZM23 17C23 20.87 19.87 24 16 24C14.09 24 12.37 23.24 11.11 22H6C4.89 22 4 21.11 4 20V4C4 3.46957 4.21071 2.96086 4.58579 2.58579C4.96086 2.21071 5.46957 2 6 2H7V9L9.5 7.5L12 9V2H18C18.5304 2 19.0391 2.21071 19.4142 2.58579C19.7893 2.96086 20 3.46957 20 4V11.26C21.81 12.53 23 14.62 23 17ZM21 17C21 14.24 18.76 12 16 12C13.24 12 11 14.24 11 17C11 19.76 13.24 22 16 22C18.76 22 21 19.76 21 17Z"
                                                fill="#5D87FF" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_10122_7443">
                                                <rect width="20" height="20" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>


                                    <span class="text-dark text-start lh-sm fs-2">
                                        Pengumpulan Kapan Saja : {{ $exam->allow_early_submission ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            </div>

                                <div class="col-12 col-sm-6 col-lg-auto">
                                    <div
                                        class="badge bg-white border border-primary d-flex align-items-center gap-2 py-2 px-3 rounded-3 w-100 text-wrap">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M15 2C15.683 2 16.287 2.343 16.647 2.866L16.732 3H18C18.5046 2.99984 18.9906 3.19041 19.3605 3.5335C19.7305 3.87659 19.9572 4.34684 19.995 4.85L20 5V17C19.9999 18.2885 19.5025 19.5272 18.6113 20.4578C17.7202 21.3884 16.5042 21.9391 15.217 21.995L15 22H6C5.49542 22.0002 5.00943 21.8096 4.63945 21.4665C4.26947 21.1234 4.04284 20.6532 4.005 20.15L4 20V5C3.99984 4.49542 4.19041 4.00943 4.5335 3.63945C4.87659 3.26947 5.34684 3.04284 5.85 3.005L6 3H7.268C7.42919 2.72075 7.6555 2.48461 7.92765 2.31171C8.1998 2.13881 8.50973 2.03427 8.831 2.007L9 2H15ZM7 5H6V20H15C15.7956 20 16.5587 19.6839 17.1213 19.1213C17.6839 18.5587 18 17.7956 18 17V5H17C17 5.53043 16.7893 6.03914 16.4142 6.41421C16.0391 6.78929 15.5304 7 15 7H9C8.46957 7 7.96086 6.78929 7.58579 6.41421C7.21071 6.03914 7 5.53043 7 5ZM12 14C12.2549 14.0003 12.5 14.0979 12.6854 14.2728C12.8707 14.4478 12.9822 14.687 12.9972 14.9414C13.0121 15.1958 12.9293 15.4464 12.7657 15.6418C12.6021 15.8373 12.3701 15.9629 12.117 15.993L12 16H9C8.74512 15.9997 8.49997 15.9021 8.31463 15.7272C8.1293 15.5522 8.01776 15.313 8.00283 15.0586C7.98789 14.8042 8.07067 14.5536 8.23426 14.3582C8.39785 14.1627 8.6299 14.0371 8.883 14.007L9 14H12ZM15 10C15.2652 10 15.5196 10.1054 15.7071 10.2929C15.8946 10.4804 16 10.7348 16 11C16 11.2652 15.8946 11.5196 15.7071 11.7071C15.5196 11.8946 15.2652 12 15 12H9C8.73478 12 8.48043 11.8946 8.29289 11.7071C8.10536 11.5196 8 11.2652 8 11C8 10.7348 8.10536 10.4804 8.29289 10.2929C8.48043 10.1054 8.73478 10 9 10H15ZM15 4H9V5H15V4Z"
                                                fill="#5D87FF" />
                                        </svg>


                                        <span class="text-dark text-start lh-sm fs-2">
                                            Tampilkan Nilai & Jawaban Benar : {{ $exam->can_view_answer_result ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 py-4">
        @forelse ($exam->classrooms as $classroom)
            <div class="col-lg-4 col-md-6 col-12 d-flex justify-content-center">
                <div class="card border-0 shadow-sm rounded-3 w-100" style="max-width:420px;">
                    <div class="card-body p-3 d-flex flex-column">

                        <h4 class="fw-bold mb-2">
                            {{ $classroom['class_name'] }}
                        </h4>

                        <div class="d-flex align-items-center gap-2 text-muted mb-3">
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8 0C9.06087 0 10.0783 0.421427 10.8284 1.17157C11.5786 1.92172 12 2.93913 12 4C12 5.06087 11.5786 6.07828 10.8284 6.82843C10.0783 7.57857 9.06087 8 8 8C6.93913 8 5.92172 7.57857 5.17157 6.82843C4.42143 6.07828 4 5.06087 4 4C4 2.93913 4.42143 1.92172 5.17157 1.17157C5.92172 0.421427 6.93913 0 8 0ZM8 10C12.42 10 16 11.79 16 14V16H0V14C0 11.79 3.58 10 8 10Z"
                                    fill="#787878" />
                            </svg>

                            <span class="fs-6">{{ $classroom['teacher_name'] }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="text-primary fw-semibold fs-6">
                                {{ $classroom['student_submitted'] ?? 0 }}/{{ $classroom['student_total'] ?? 0 }}
                                Selesai
                            </span>

                            <a href="{{ route('school.exam-detail', ['exam' => $exam->id, 'classroom' => $classroom['id']]) }}"
                                class="btn btn-primary px-4 py-2 rounded-2">
                                Detail
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border mb-0">
                    Belum ada kelas yang terdaftar pada ujian ini.
                </div>
            </div>
        @endforelse
    </div>


    <div class="card examd-card mb-4">
        <div class="examd-card__head">
            Aturan Ujian
        </div>

        <div class="examd-card__body pb-2">
            <div class="examd-rulebox">
                {!! trans('alert.rules.exam') !!}
            </div>
        </div>
        @if (!empty($exam->rules))
            <div class="examd-card__body pt-2">
                <div class="examd-rulebox">
                    <div class="mb-3 examd-rules-added">
                        {!! $exam->rules !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('script')
    @include('school.pages.exam.detail.scripts.script-index')
    @include('school.pages.exam.scripts.script-reset')

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        new Swiper('.score-swiper', {
            slidesPerView: 1,
            spaceBetween: 16,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    </script>
@endsection
