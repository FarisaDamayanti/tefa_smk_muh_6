@extends('teacher.layouts.app')

@section('style')
    <style>
        .score-swiper-2 .swiper-wrapper {
            align-items: stretch;
        }

        .score-swiper-2 .swiper-slide {
            height: auto;
        }

        .score-swiper-2 .swiper-slide .card {
            height: 100%;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/detail-exam.css') }}">
@endsection

@section('content')
    <div class="container-fluid">
        <x-authenticated.breadcrumb title="Detail Ujian {{ $classroom->levelClass->name . ' ' . $classroom->name }}"
            breadcrumb="Informasi Lengkap terkait Ujian" />

        <div class="container-fluid d-flex justify-content-end pb-4">
            <a href="{{ route('teacher.exam-monitoring.detail', $exam->id) }}"
                class="btn btn-warning d-inline-flex align-items-center gap-2">
                Kembali
            </a>
        </div>


        <div class="my-1">
            <div class="card w-100 rounded-3">
                <div class="card-header bg-primary px-4">
                    <h4 class="mb-0 text-white card-title">Informasi Ujian</h4>
                </div>

                <div class="card-body p-4 bg-light-primary">
                    <div class="card mb-0">
                        <div class="card-body">

                            <h3 class="mb-3">
                                {{ $exam->title ?? '-' }}
                            </h3>
                            <div class="mb-3">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                                    Tingkat Kelas {{ $exam->questionBank?->levelClass?->name ?? '' }}
                                </span>
                            </div>

                            <div class="row g-3">

                                {{-- Tanggal & Jam --}}
                                <div class="col-12 col-sm-6 col-lg-auto">
                                    <div
                                        class="badge bg-white border border-primary d-flex align-items-center gap-2 py-2 px-3 rounded-3 w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="22"
                                            height="22" viewBox="0 0 24 24">
                                            <g fill="none">
                                                <path
                                                    d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z" />
                                                <path fill="currentColor"
                                                    d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7zm-5-9a1 1 0 0 1 1 1v1h2a2 2 0 0 1 2 2v3H3V7a2 2 0 0 1 2-2h2V4a1 1 0 0 1 2 0v1h6V4a1 1 0 0 1 1-1" />
                                            </g>
                                        </svg>

                                        <span class="text-dark small lh-sm">
                                            {{ $exam->exam_date ? $exam->exam_date->translatedFormat('d F Y - H.i') : '-' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-auto">
                                    <div
                                        class="badge bg-white border border-primary d-flex align-items-center gap-2 py-2 px-3 rounded-3 w-100">
                                        <svg width="24" height="20" viewBox="0 0 24 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M21.7033 14.5845V7.82235L12.4593 12.8367C12.1148 13.0277 11.7512 13.1232 11.3684 13.1232C10.9856 13.1232 10.622 13.0277 10.2775 12.8367L0.574172 7.56447C0.363646 7.44986 0.21513 7.30659 0.128622 7.13468C0.0421153 6.96276 -0.000755477 6.77173 1.0073e-05 6.56161C0.000775623 6.35149 0.0440294 6.16047 0.129771 5.98855C0.215512 5.81663 0.363646 5.67336 0.574172 5.55875L10.2775 0.28655C10.4498 0.19104 10.627 0.119215 10.8092 0.0710777C10.9914 0.0229403 11.1778 -0.000746178 11.3684 1.79084e-05C11.559 0.000781995 11.7458 0.0248507 11.9288 0.072224C12.1118 0.119597 12.2886 0.19104 12.4593 0.28655L23.3971 6.24643C23.5885 6.34194 23.737 6.48062 23.8427 6.66247C23.9483 6.84432 24.0008 7.03993 24 7.24929V14.5845C24 14.9093 23.8898 15.1817 23.6693 15.4017C23.4488 15.6218 23.1763 15.7314 22.8517 15.7306C22.5271 15.7299 22.2545 15.6199 22.0341 15.4006C21.8136 15.1813 21.7033 14.9093 21.7033 14.5845ZM10.2775 19.7134L4.53589 16.6189C4.15312 16.4088 3.85647 16.1222 3.64594 15.7593C3.43541 15.3964 3.33015 15.0048 3.33015 14.5845V10.2292L10.2775 13.9828C10.622 14.1738 10.9856 14.2693 11.3684 14.2693C11.7512 14.2693 12.1148 14.1738 12.4593 13.9828L19.4067 10.2292V14.5845C19.4067 15.0048 19.3014 15.3964 19.0909 15.7593C18.8804 16.1222 18.5837 16.4088 18.201 16.6189L12.4593 19.7134C12.2871 19.809 12.1102 19.8808 11.9288 19.9289C11.7474 19.9771 11.5606 20.0007 11.3684 20C11.1763 19.9992 10.9895 19.9755 10.808 19.9289C10.6266 19.8823 10.4498 19.8105 10.2775 19.7134Z"
                                                fill="#5D87FF" />
                                        </svg>


                                        <span class="text-dark small lh-sm">
                                            Semester {{ $exam->semester ? ucfirst($exam->semester) : '-' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-auto">
                                    <div
                                        class="badge bg-white border border-primary d-flex align-items-center gap-2 py-2 px-3 rounded-3 w-100">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7.9 2.89077C6.48867 2.21694 4.51602 1.88686 1.875 1.87514C1.62593 1.87176 1.38174 1.94439 1.175 2.08335C1.00531 2.19805 0.866403 2.35268 0.770498 2.53366C0.674593 2.71464 0.62463 2.91642 0.625002 3.12124V14.297C0.625002 15.0525 1.1625 15.6224 1.875 15.6224C4.65117 15.6224 7.43594 15.8818 9.10391 17.4583C9.12673 17.48 9.15541 17.4945 9.18639 17.5C9.21737 17.5055 9.24928 17.5018 9.27816 17.4893C9.30704 17.4768 9.33162 17.4561 9.34883 17.4298C9.36605 17.4034 9.37515 17.3726 9.375 17.3412V4.1728C9.37504 4.08396 9.35603 3.99614 9.31923 3.91528C9.28244 3.83441 9.22873 3.76238 9.16172 3.70405C8.77965 3.37742 8.35525 3.10386 7.9 2.89077ZM18.825 2.08217C18.6182 1.94356 18.374 1.87134 18.125 1.87514C15.484 1.88686 13.5113 2.21538 12.1 2.89077C11.6448 3.10347 11.2203 3.37649 10.8379 3.70249C10.771 3.76091 10.7174 3.83296 10.6807 3.91381C10.644 3.99466 10.625 4.08244 10.625 4.17124V17.3404C10.625 17.3706 10.6339 17.4002 10.6506 17.4254C10.6673 17.4506 10.6911 17.4703 10.719 17.482C10.7469 17.4937 10.7776 17.4969 10.8073 17.4913C10.837 17.4856 10.8643 17.4713 10.8859 17.4501C11.8887 16.454 13.6484 15.6212 18.1266 15.6216C18.4581 15.6216 18.776 15.4899 19.0104 15.2555C19.2449 15.0211 19.3766 14.7031 19.3766 14.3716V3.12163C19.377 2.9164 19.3269 2.71422 19.2307 2.53294C19.1346 2.35165 18.9952 2.19684 18.825 2.08217Z"
                                                fill="#5D87FF" />
                                        </svg>


                                        <span class="text-dark small lh-sm">
                                            Tahun Ajaran {{ $exam->schoolYear->school_year ?? '-' }}
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
                                            Jumlah Soal {{ $exam->total_questions ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- KKM --}}
                                <div class="col-12 col-sm-6 col-lg-auto">
                                    <div
                                        class="badge bg-white border border-primary d-flex align-items-center gap-2 py-2 px-3 rounded-3 w-100">
                                        <i class="ti ti-star-filled text-primary fs-5"></i>
                                        <span class="text-dark small lh-sm">
                                            Nilai Minimal (KKM) {{ $exam->minimum_score ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Durasi --}}
                                <div class="col-12 col-sm-6 col-lg-auto">
                                    <div
                                        class="badge bg-white border border-primary d-flex align-items-center gap-2 py-2 px-3 rounded-3 w-100">
                                        <i class="ti ti-clock-hour-5 text-primary fs-5"></i>
                                        <span class="text-dark small lh-sm">
                                            Durasi Ujian {{ $exam->duration ?? '-' }} Menit
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
                                            Pengumpulan Kapan Saja :
                                            {{ $exam->allow_early_submission ? 'Aktif' : 'Nonaktif' }}
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
                                            Tampilkan Nilai & Jawaban Benar :
                                            {{ $exam->can_view_answer_result ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-3 d-none d-md-flex">

            <div class="col">
                <div class="card score-card danger text-white border-0">
                    <div class="card-body p-3 position-relative overflow-hidden">
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
                        <h5 class="fw-semibold mb-4 text-white text-capitalize">nilai terendah</h5>
                        <h1 class="fw-bold text-white">
                            {{ $exam->studentExamStatistics['lowest_score'] ?? 0 }}
                        </h1>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card score-card warning text-white border-0">
                    <div class="card-body p-3 position-relative overflow-hidden">
                        <div class="bg-shapes">
                            <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                    transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)" fill="white"
                                    fill-opacity="0.15" />
                                <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                    transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)" fill="white"
                                    fill-opacity="0.15" />
                            </svg>

                            <svg class="shape-bottom" width="130" height="80" viewBox="0 0 184 49" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M32 12.6151C12.1814 15.1173 0 48.6152 0 48.6152H192.5C192.5 48.6152 176.5 12.6151 147.5 12.6151C104 12.6151 110 1.61542 91 0.115234C72 -1.38495 71.1577 12.2026 57.5 14.6151C46.1486 16.6202 43.4363 11.1712 32 12.6151Z"
                                    fill="url(#paint0_linear_7747_7436)" />
                                <defs>
                                    <linearGradient id="paint0_linear_7747_7436" x1="92" y1="-20.8858"
                                        x2="92" y2="48.6151" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#E6FFFA" />
                                        <stop offset="1" stop-color="#E6FFFA" stop-opacity="0" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <h5 class="fw-semibold mb-4 text-white text-capitalize">nilai rata-rata</h5>
                        <h1 class="fw-bold text-white">
                            {{ number_format($exam->studentExamStatistics['average_score'] ?? 0, 1) }}
                        </h1>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card score-card success text-white border-0">
                    <div class="card-body p-3 position-relative overflow-hidden">
                        <div class="bg-shapes">
                            <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                    transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)" fill="white"
                                    fill-opacity="0.15" />
                                <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                    transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)" fill="white"
                                    fill-opacity="0.15" />
                            </svg>

                            <svg class="shape-bottom" width="70" height="60" style="bottom: 0px; right: 20px;"
                                viewBox="0 0 75 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M60 41.6667C62.625 41.6667 65.1375 42.2083 67.5 43.125V25L45 0H7.5C3.3375 0 0 3.70833 0 8.33333V66.6667C0 68.8768 0.790176 70.9964 2.1967 72.5592C3.60322 74.122 5.51088 75 7.5 75H38.8125C37.9875 72.375 37.5 69.5833 37.5 66.6667C37.5 52.875 47.5875 41.6667 60 41.6667ZM41.25 6.25L61.875 29.1667H41.25V6.25ZM75 62.5V70.8333H45V62.5H75Z"
                                    fill="white" fill-opacity="0.4" />
                            </svg>
                        </div>
                        <h5 class="fw-semibold mb-4 text-white text-capitalize">nilai tertinggi</h5>
                        <h1 class="fw-bold text-white">
                            {{ $exam->studentExamStatistics['highest_score'] ?? 0 }}
                        </h1>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card score-card info text-white border-0">
                    <div class="card-body p-3 position-relative overflow-hidden">
                        <div class="bg-shapes">
                            <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                    transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)" fill="white"
                                    fill-opacity="0.15" />
                                <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                    transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)" fill="white"
                                    fill-opacity="0.15" />
                            </svg>

                            <svg class="shape-bottom" width="70" height="60" style="bottom: 0px; right: 20px;"
                                viewBox="0 0 75 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M60 41.6667C62.625 41.6667 65.1375 42.2083 67.5 43.125V25L45 0H7.5C3.3375 0 0 3.70833 0 8.33333V66.6667C0 68.8768 0.790176 70.9964 2.1967 72.5592C3.60322 74.122 5.51088 75 7.5 75H38.8125C37.9875 72.375 37.5 69.5833 37.5 66.6667C37.5 52.875 47.5875 41.6667 60 41.6667ZM41.25 6.25L61.875 29.1667H41.25V6.25ZM75 62.5V70.8333H45V62.5H75Z"
                                    fill="white" fill-opacity="0.4" />
                            </svg>
                        </div>
                        <h5 class="fw-semibold mb-1 text-white text-capitalize">Total Siswa Belum Mengumpulkan</h5>
                        <h1 class="fw-bold text-white">
                            {{ $exam->studentExamStatistics['student_not_submitted'] ?? 0 }}
                        </h1>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card score-card primary text-white border-0">
                    <div class="card-body p-3 position-relative overflow-hidden">
                        <div class="bg-shapes">
                            <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                    transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)" fill="white"
                                    fill-opacity="0.15" />
                                <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                    transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)" fill="white"
                                    fill-opacity="0.15" />
                            </svg>

                            <svg class="shape-bottom" width="70" height="80" style="bottom: -8px; right: 20px;"
                                viewBox="0 0 95 75" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M85.05 57.2626V30.7126L48.825 50.4001C47.475 51.1501 46.05 51.5251 44.55 51.5251C43.05 51.5251 41.625 51.1501 40.275 50.4001L2.25004 29.7001C1.42504 29.2501 0.843039 28.6876 0.504039 28.0126C0.165039 27.3376 -0.00296053 26.5876 3.94737e-05 25.7626C0.00303947 24.9376 0.17254 24.1876 0.50854 23.5126C0.84454 22.8376 1.42504 22.2751 2.25004 21.8251L40.275 1.12507C40.95 0.75007 41.6445 0.468069 42.3585 0.279069C43.0725 0.0900694 43.803 -0.00292969 44.55 7.03132e-05C45.297 0.00307031 46.029 0.0975702 46.746 0.28357C47.463 0.46957 48.156 0.75007 48.825 1.12507L91.6875 24.5251C92.4375 24.9001 93.0195 25.4446 93.4335 26.1586C93.8475 26.8726 94.053 27.6406 94.05 28.4626V57.2626C94.05 58.5376 93.618 59.6071 92.754 60.4711C91.89 61.3351 90.822 61.7656 89.55 61.7626C88.278 61.7596 87.21 61.3276 86.346 60.4666C85.482 59.6056 85.05 58.5376 85.05 57.2626ZM40.275 77.4001L17.775 65.2501C16.275 64.4251 15.1125 63.3001 14.2875 61.8751C13.4625 60.4501 13.05 58.9126 13.05 57.2626V40.1626L40.275 54.9001C41.625 55.6501 43.05 56.0251 44.55 56.0251C46.05 56.0251 47.475 55.6501 48.825 54.9001L76.05 40.1626V57.2626C76.05 58.9126 75.6375 60.4501 74.8125 61.8751C73.9875 63.3001 72.825 64.4251 71.325 65.2501L48.825 77.4001C48.15 77.7751 47.457 78.0571 46.746 78.2461C46.035 78.4351 45.303 78.5281 44.55 78.5251C43.797 78.5221 43.065 78.4291 42.354 78.2461C41.643 78.0631 40.95 77.7811 40.275 77.4001Z"
                                    fill="white" fill-opacity="0.4" />
                            </svg>
                        </div>
                        <h5 class="fw-semibold mb-1 text-white text-capitalize">Total Siswa Sudah Mengumpulkan</h5>
                        <h1 class="fw-bold text-white">
                            {{ $exam->studentExamStatistics['student_submitted'] ?? 0 }}
                        </h1>
                    </div>
                </div>
            </div>

        </div>

        {{-- ===== MOBILE: Swiper slider (sembunyi di desktop) ===== --}}
        <div class="d-md-none">
            <div class="swiper score-swiper-2">
                <div class="swiper-wrapper">

                    <div class="swiper-slide">
                        <div class="card score-card danger text-white border-0">
                            <div class="card-body p-3 position-relative overflow-hidden">
                                <div class="bg-shapes">
                                    <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                        <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                            transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)"
                                            fill="white" fill-opacity="0.15" />
                                        <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                            transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)"
                                            fill="white" fill-opacity="0.15" />
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
                                <h5 class="fw-semibold mb-4 text-white text-capitalize">nilai terendah</h5>
                                <h1 class="fw-bold text-white">
                                    {{ $exam->studentExamStatistics['lowest_score'] ?? 0 }}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="card score-card warning text-white border-0">
                            <div class="card-body p-3 position-relative overflow-hidden">
                                <div class="bg-shapes">
                                    <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                        <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                            transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)"
                                            fill="white" fill-opacity="0.15" />
                                        <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                            transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)"
                                            fill="white" fill-opacity="0.15" />
                                    </svg>

                                    <svg class="shape-bottom" width="130" height="80" viewBox="0 0 184 49"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M32 12.6151C12.1814 15.1173 0 48.6152 0 48.6152H192.5C192.5 48.6152 176.5 12.6151 147.5 12.6151C104 12.6151 110 1.61542 91 0.115234C72 -1.38495 71.1577 12.2026 57.5 14.6151C46.1486 16.6202 43.4363 11.1712 32 12.6151Z"
                                            fill="url(#paint0_linear_7747_7436)" />
                                        <defs>
                                            <linearGradient id="paint0_linear_7747_7436" x1="92" y1="-20.8858"
                                                x2="92" y2="48.6151" gradientUnits="userSpaceOnUse">
                                                <stop stop-color="#E6FFFA" />
                                                <stop offset="1" stop-color="#E6FFFA" stop-opacity="0" />
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                </div>
                                <h5 class="fw-semibold mb-4 text-white text-capitalize">nilai rata-rata</h5>
                                <h1 class="fw-bold text-white">
                                    {{ number_format($exam->studentExamStatistics['average_score'] ?? 0, 1) }}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="card score-card success text-white border-0">
                            <div class="card-body p-3 position-relative overflow-hidden">
                                <div class="bg-shapes">
                                    <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                        <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                            transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)"
                                            fill="white" fill-opacity="0.15" />
                                        <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                            transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)"
                                            fill="white" fill-opacity="0.15" />
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
                                <h5 class="fw-semibold mb-4 text-white text-capitalize">nilai tertinggi</h5>
                                <h1 class="fw-bold text-white">
                                    {{ $exam->studentExamStatistics['highest_score'] ?? 0 }}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="card score-card info text-white border-0">
                            <div class="card-body p-3 position-relative overflow-hidden">
                                <div class="bg-shapes">
                                    <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                        <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                            transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)"
                                            fill="white" fill-opacity="0.15" />
                                        <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                            transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)"
                                            fill="white" fill-opacity="0.15" />
                                    </svg>

                                    <svg class="shape-bottom" width="70" height="60"
                                        style="bottom: 0px; right: 20px;" viewBox="0 0 75 70" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M60 41.6667C62.625 41.6667 65.1375 42.2083 67.5 43.125V25L45 0H7.5C3.3375 0 0 3.70833 0 8.33333V66.6667C0 68.8768 0.790176 70.9964 2.1967 72.5592C3.60322 74.122 5.51088 75 7.5 75H38.8125C37.9875 72.375 37.5 69.5833 37.5 66.6667C37.5 52.875 47.5875 41.6667 60 41.6667ZM41.25 6.25L61.875 29.1667H41.25V6.25ZM75 62.5V70.8333H45V62.5H75Z"
                                            fill="white" fill-opacity="0.4" />
                                    </svg>
                                </div>
                                <h5 class="fw-semibold mb-1 text-white text-capitalize">Total Siswa Belum Mengumpulkan</h5>
                                <h1 class="fw-bold text-white">
                                    {{ $exam->studentExamStatistics['student_not_submitted'] ?? 0 }}
                                </h1>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <div class="card score-card primary text-white border-0">
                            <div class="card-body p-3 position-relative overflow-hidden">
                                <div class="bg-shapes">
                                    <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                        <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                            transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)"
                                            fill="white" fill-opacity="0.15" />
                                        <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                            transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)"
                                            fill="white" fill-opacity="0.15" />
                                    </svg>

                                    <svg class="shape-bottom" width="70" height="80"
                                        style="bottom: -8px; right: 20px;" viewBox="0 0 95 75" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M85.05 57.2626V30.7126L48.825 50.4001C47.475 51.1501 46.05 51.5251 44.55 51.5251C43.05 51.5251 41.625 51.1501 40.275 50.4001L2.25004 29.7001C1.42504 29.2501 0.843039 28.6876 0.504039 28.0126C0.165039 27.3376 -0.00296053 26.5876 3.94737e-05 25.7626C0.00303947 24.9376 0.17254 24.1876 0.50854 23.5126C0.84454 22.8376 1.42504 22.2751 2.25004 21.8251L40.275 1.12507C40.95 0.75007 41.6445 0.468069 42.3585 0.279069C43.0725 0.0900694 43.803 -0.00292969 44.55 7.03132e-05C45.297 0.00307031 46.029 0.0975702 46.746 0.28357C47.463 0.46957 48.156 0.75007 48.825 1.12507L91.6875 24.5251C92.4375 24.9001 93.0195 25.4446 93.4335 26.1586C93.8475 26.8726 94.053 27.6406 94.05 28.4626V57.2626C94.05 58.5376 93.618 59.6071 92.754 60.4711C91.89 61.3351 90.822 61.7656 89.55 61.7626C88.278 61.7596 87.21 61.3276 86.346 60.4666C85.482 59.6056 85.05 58.5376 85.05 57.2626ZM40.275 77.4001L17.775 65.2501C16.275 64.4251 15.1125 63.3001 14.2875 61.8751C13.4625 60.4501 13.05 58.9126 13.05 57.2626V40.1626L40.275 54.9001C41.625 55.6501 43.05 56.0251 44.55 56.0251C46.05 56.0251 47.475 55.6501 48.825 54.9001L76.05 40.1626V57.2626C76.05 58.9126 75.6375 60.4501 74.8125 61.8751C73.9875 63.3001 72.825 64.4251 71.325 65.2501L48.825 77.4001C48.15 77.7751 47.457 78.0571 46.746 78.2461C46.035 78.4351 45.303 78.5281 44.55 78.5251C43.797 78.5221 43.065 78.4291 42.354 78.2461C41.643 78.0631 40.95 77.7811 40.275 77.4001Z"
                                            fill="white" fill-opacity="0.4" />
                                    </svg>
                                </div>
                                <h5 class="fw-semibold mb-1 text-white text-capitalize">Total Siswa Sudah Mengumpulkan</h5>
                                <h1 class="fw-bold text-white">
                                    {{ $exam->studentExamStatistics['student_submitted'] ?? 0 }}
                                </h1>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="swiper-pagination-2 text-center mt-2"></div>
        </div>
        {{-- <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-3">
            <div class="col">
                <div class="card score-card danger text-white border-0">
                    <div class="card-body p-3 position-relative overflow-hidden">
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

                        <h5 class="fw-semibold mb-4 text-white text-capitalize">nilai terendah</h5>
                        <h1 class="fw-bold text-white">
                            {{ $exam->studentExamStatistics['lowest_score'] ?? 0 }}
                        </h1>

                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card score-card warning text-white border-0">
                    <div class="card-body p-3 position-relative overflow-hidden">
                        <div class="bg-shapes">
                            <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                    transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)" fill="white"
                                    fill-opacity="0.15" />
                                <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                    transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)" fill="white"
                                    fill-opacity="0.15" />
                            </svg>

                            <svg class="shape-bottom" width="130" height="80" viewBox="0 0 184 49" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M32 12.6151C12.1814 15.1173 0 48.6152 0 48.6152H192.5C192.5 48.6152 176.5 12.6151 147.5 12.6151C104 12.6151 110 1.61542 91 0.115234C72 -1.38495 71.1577 12.2026 57.5 14.6151C46.1486 16.6202 43.4363 11.1712 32 12.6151Z"
                                    fill="url(#paint0_linear_7747_7436)" />
                                <defs>
                                    <linearGradient id="paint0_linear_7747_7436" x1="92" y1="-20.8858"
                                        x2="92" y2="48.6151" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#E6FFFA" />
                                        <stop offset="1" stop-color="#E6FFFA" stop-opacity="0" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>

                        <h5 class="fw-semibold mb-4 text-white text-capitalize">nilai rata-rata</h5>
                        <h1 class="fw-bold text-white">
                            {{ number_format($exam->studentExamStatistics['average_score'] ?? 0, 1) }}
                        </h1>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card score-card success text-white border-0">
                    <div class="card-body p-3 position-relative overflow-hidden">
                        <div class="bg-shapes">
                            <!-- SVG kiri atas -->
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

                        <h5 class="fw-semibold mb-4 text-white text-capitalize">nilai tertinggi</h5>
                        <h1 class="fw-bold text-white">
                            {{ $exam->studentExamStatistics['highest_score'] ?? 0 }}
                        </h1>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card score-card info text-white border-0">
                    <div class="card-body p-3 position-relative overflow-hidden">
                        <div class="bg-shapes">
                            <!-- SVG kiri atas -->
                            <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                    transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)" fill="white"
                                    fill-opacity="0.15" />
                                <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                    transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)" fill="white"
                                    fill-opacity="0.15" />
                            </svg>

                            <svg class="shape-bottom" width="70" height="60" style="bottom: 0px; right: 20px;"
                                viewBox="0 0 75 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M60 41.6667C62.625 41.6667 65.1375 42.2083 67.5 43.125V25L45 0H7.5C3.3375 0 0 3.70833 0 8.33333V66.6667C0 68.8768 0.790176 70.9964 2.1967 72.5592C3.60322 74.122 5.51088 75 7.5 75H38.8125C37.9875 72.375 37.5 69.5833 37.5 66.6667C37.5 52.875 47.5875 41.6667 60 41.6667ZM41.25 6.25L61.875 29.1667H41.25V6.25ZM75 62.5V70.8333H45V62.5H75Z"
                                    fill="white" fill-opacity="0.4" />
                            </svg>
                        </div>

                        <h5 class="fw-semibold mb-1 text-white text-capitalize">Total Siswa Belum Mengumpulkan</h5>
                        <h1 class="fw-bold text-white">
                            {{ $exam->studentExamStatistics['student_not_submitted'] ?? 0 }}
                        </h1>
                    </div>
                </div>
            </div>


            <div class="col">
                <div class="card score-card primary text-white border-0">
                    <div class="card-body p-3 position-relative overflow-hidden">
                        <div class="bg-shapes">
                            <svg class="shape-top" width="82" height="113" viewBox="0 0 82 113">
                                <ellipse cx="85.3563" cy="70.3856" rx="85.3563" ry="70.3856"
                                    transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 81.8789 112.598)" fill="white"
                                    fill-opacity="0.15" />
                                <ellipse cx="59.931" cy="48.839" rx="59.931" ry="48.839"
                                    transform="matrix(3.45736e-08 -1 -1 -5.52643e-08 60.0117 88.8621)" fill="white"
                                    fill-opacity="0.15" />
                            </svg>

                            <svg class="shape-bottom" width="70" height="80" style="bottom: -8px; right: 20px;"
                                viewBox="0 0 95 75" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M85.05 57.2626V30.7126L48.825 50.4001C47.475 51.1501 46.05 51.5251 44.55 51.5251C43.05 51.5251 41.625 51.1501 40.275 50.4001L2.25004 29.7001C1.42504 29.2501 0.843039 28.6876 0.504039 28.0126C0.165039 27.3376 -0.00296053 26.5876 3.94737e-05 25.7626C0.00303947 24.9376 0.17254 24.1876 0.50854 23.5126C0.84454 22.8376 1.42504 22.2751 2.25004 21.8251L40.275 1.12507C40.95 0.75007 41.6445 0.468069 42.3585 0.279069C43.0725 0.0900694 43.803 -0.00292969 44.55 7.03132e-05C45.297 0.00307031 46.029 0.0975702 46.746 0.28357C47.463 0.46957 48.156 0.75007 48.825 1.12507L91.6875 24.5251C92.4375 24.9001 93.0195 25.4446 93.4335 26.1586C93.8475 26.8726 94.053 27.6406 94.05 28.4626V57.2626C94.05 58.5376 93.618 59.6071 92.754 60.4711C91.89 61.3351 90.822 61.7656 89.55 61.7626C88.278 61.7596 87.21 61.3276 86.346 60.4666C85.482 59.6056 85.05 58.5376 85.05 57.2626ZM40.275 77.4001L17.775 65.2501C16.275 64.4251 15.1125 63.3001 14.2875 61.8751C13.4625 60.4501 13.05 58.9126 13.05 57.2626V40.1626L40.275 54.9001C41.625 55.6501 43.05 56.0251 44.55 56.0251C46.05 56.0251 47.475 55.6501 48.825 54.9001L76.05 40.1626V57.2626C76.05 58.9126 75.6375 60.4501 74.8125 61.8751C73.9875 63.3001 72.825 64.4251 71.325 65.2501L48.825 77.4001C48.15 77.7751 47.457 78.0571 46.746 78.2461C46.035 78.4351 45.303 78.5281 44.55 78.5251C43.797 78.5221 43.065 78.4291 42.354 78.2461C41.643 78.0631 40.95 77.7811 40.275 77.4001Z"
                                    fill="white" fill-opacity="0.4" />
                            </svg>
                        </div>

                        <h5 class="fw-semibold mb-1 text-white text-capitalize">Total Siswa Sudah Mengumpulkan</h5>
                        <h1 class="fw-bold text-white">
                            {{ $exam->studentExamStatistics['student_submitted'] ?? 0 }}
                        </h1>
                    </div>
                </div>
            </div>
        </div> --}}

        <div
            class="alert border border-warning bg-warning-subtle text-warning d-flex align-items-center gap-2 rounded-3 px-4 py-3">
            <i class="ti ti-info-circle fs-5"></i>
            <span>
                Untuk melakukan penilaian soal uraian, silahkan klik tab <b>Penilaian Uraian</b> terlebih dahulu.
            </span>
        </div>

        <div class="card shadow-sm border border-1 mb-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <ul class="nav nav-pills gap-3 m-0">
                        <li class="nav-item">
                            <a class="nav-link active text-capitalize" data-bs-toggle="tab" href="#student-list"
                                role="tab">
                                <span>Daftar Siswa</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-capitalize" data-bs-toggle="tab" href="#essay-assessment"
                                role="tab">
                                <span>Penilaian Uraian</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="exam-tab-content-anchor"></div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="student-list">
                @include('teacher.pages.exam-monitoring.detail.panes.student-list')
            </div>

            <div class="tab-pane fade" id="essay-assessment">
                @include('teacher.pages.exam-monitoring.detail.panes.essay-assessment')
            </div>
        </div>
    @endsection
    @section('script')
        @include('teacher.pages.exam-monitoring.detail.scripts.handle-tab')
        @include('teacher.pages.exam-monitoring.detail.scripts.fetch-essay')
        @include('teacher.pages.exam-monitoring.detail.scripts.chart')
        @include('teacher.pages.exam-monitoring.detail.scripts.export')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            new Swiper('.score-swiper-2', {
                slidesPerView: 1,
                spaceBetween: 16,
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination-2',
                    clickable: true,
                },
            });
        </script>
    @endsection
