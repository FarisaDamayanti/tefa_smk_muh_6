<div class="row">
    <div class="col-lg-8">
        <div class="row">
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
                            total siswa tidak lulus
                        </h4>
                        <h1 class="fw-bold text-white text-center display-5">
                            {{ $exam->examStatistics['not_passed_student'] ?? 0 }}
                        </h1>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
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
                            total siswa lulus
                        </h4>
                        <h1 class="fw-bold text-white text-center display-5">
                            {{ $exam->examStatistics['passed_student'] ?? 0 }}
                        </h1>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="card score-card success text-white border-0">
                    <div class="card-body position-relative overflow-hidden">
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

                            <!-- SVG bawah -->
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

                        <!-- Konten -->
                        <h4 class="mb-2 fw-semibold text-white text-capitalize text-center">
                            jumlah siswa
                        </h4>
                        <h1 class="fw-bold text-white text-center display-5">
                            {{ $exam->examStatistics['total_students'] ?? 0 }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 col-12">
        <div class="card border-0">
            <div class="card-body p-3">
                <p class="mb-2 fw-semibold">Statistik Pengerjaan</p>

                <div class="d-flex align-items-center justify-content-center">
                    <div id="chart-pie-donut" style="width: 100%;"
                        data-submitted="{{ $exam->studentExamStatistics['student_submitted'] ?? 0 }}"
                        data-not-submitted="{{ $exam->studentExamStatistics['student_not_submitted'] ?? 0 }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="my-1">
    <div class="card w-100 rounded-3">
        <div class="card-header bg-primary px-4">
            <h4 class="mb-0 text-white card-title">Informasi Ujian</h4>
        </div>

        <div class="card-body p-4 bg-light-primary">
            <div class="card mb-0">
                <div class="card-body">
                    <p class="badge bg-light-primary text-primary">
                        Semester {{ $exam->semester ? ucfirst($exam->semester) : '-' }}
                        - Tahun Ajaran {{ $exam->schoolYear->school_year ?? '-' }}
                    </p>
                    <h3 class="mb-4">
                        {{ $exam->title ?? '-' }}
                    </h3>

                    <div class="row">
                        {{-- Tanggal & Jam --}}
                        <div class="col-auto mb-3 mb-lg-0">
                            <div
                                class="badge rounded-2 bg-white border border-primary border-1 d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="22"
                                    height="22" viewBox="0 0 24 24">
                                    <g fill="none">
                                        <path
                                            d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z" />
                                        <path fill="currentColor"
                                            d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7zm-5-9a1 1 0 0 1 1 1v1h2a2 2 0 0 1 2 2v3H3V7a2 2 0 0 1 2-2h2V4a1 1 0 0 1 2 0v1h6V4a1 1 0 0 1 1-1" />
                                    </g>
                                </svg>

                                <span class="text-dark">
                                    {{ $exam->exam_date ? \Carbon\Carbon::parse($exam->exam_date)->translatedFormat('d F Y - H.i') : '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- Tingkat Kelas --}}
                        <div class="col-auto mb-3 mb-lg-0">
                            <div
                                class="badge rounded-2 bg-white border border-primary border-1 d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="22"
                                    height="22" viewBox="0 0 24 24">
                                    <g fill="currentColor" fill-rule="evenodd" stroke-width="1.5"
                                        clip-rule="evenodd">
                                        <path
                                            d="M3.75 12.641v3.271a2.75 2.75 0 0 0 1.57 2.484l5 2.374a2.75 2.75 0 0 0 2.36 0l5-2.374a2.75 2 0 0 0 1.57-2.484v-3.27l-6.475 3.237a2.85 2.85 0 0 1-2.55 0z" />
                                        <path
                                            d="M10.896 3.464c.38-.19.828-.19 1.208 0l8.658 4.329c.995.497.995 1.917 0 2.415l-8.658 4.33c-.38.19-.828.19-1.208 0l-8.658-4.33c-.995-.498-.995-1.918 0-2.415z" />
                                        <path
                                            d="M19.83 8.165a.75.75 0 0 1 1.006-.336l2 1a.75.75 0 0 1 .414.671V13a.75.75 0 1 1-1.5 0V9.964l-1.585-.793a.75.75 0 0 1-.336-1.006" />
                                    </g>
                                </svg>

                                <span class="text-dark">
                                    Tingkat Kelas {{ $exam->questionBank?->levelClass?->name ?? '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- Mata Pelajaran --}}
                        <div class="col-auto mb-3 mb-lg-0">
                            <div
                                class="badge rounded-2 bg-white border border-primary border-1 d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="22"
                                    height="22" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M6 22q-.825 0-1.412-.587T4 20V4q0-.825.588-1.412T6 2h12q.825 0 1.413.588T20 4v16q0 .825-.587 1.413T18 22zm5-11l2.5-1.5L16 11V4h-5z" />
                                </svg>

                                <span class="text-dark">
                                    {{ $exam->questionBank?->subject?->name ?? '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- Jumlah Soal --}}
                        <div class="col-auto mb-3 mb-lg-0">
                            <div
                                class="badge rounded-2 bg-white border border-primary border-1 d-flex align-items-center gap-2">
                                <i class="ti ti-list text-primary fs-5"></i>
                                <span class="text-dark">
                                    Jumlah Soal {{ $exam->total_questions ?? '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- KKM --}}
                        <div class="col-auto mb-3 mb-lg-0">
                            <div
                                class="badge rounded-2 bg-white border border-primary border-1 d-flex align-items-center gap-2">
                                <i class="ti ti-star-filled text-primary fs-5"></i>
                                <span class="text-dark">
                                    Nilai Minimal (KKM) {{ $exam->minimum_score ?? '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- Durasi --}}
                        <div class="col-auto mb-3 mb-lg-0">
                            <div
                                class="badge rounded-2 bg-white border border-primary border-1 d-flex align-items-center gap-2">
                                <i class="ti ti-clock-hour-5 text-primary fs-5"></i>
                                <span class="text-dark">
                                    Durasi Ujian {{ $exam->duration ?? '-' }} Menit
                                </span>
                            </div>
                        </div>
                    </div> {{-- row --}}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card w-100 rounded-3">
    <div class="card-header bg-primary px-4">
        <h4 class="mb-0 text-white card-title">Aturan Ujian</h4>
    </div>
    <div class="card-body p-4 bg-light-primary">
        <div class="card">
            <div class="card-body">
                {!! trans('alert.rules.exam') !!}
            </div>
        </div>

        @if ($exam->rules)
            <div class="card">
                <div class="card-body">
                    {!! $exam->rules ?? '' !!}
                </div>
            </div>
        @endif
    </div>
</div>
