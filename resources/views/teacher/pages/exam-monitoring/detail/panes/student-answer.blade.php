<div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-3">
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
                            <linearGradient id="paint0_linear_7747_7428" x1="92" y1="0.500732" x2="92"
                                y2="69.5007" gradientUnits="userSpaceOnUse">
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
                            <linearGradient id="paint0_linear_7747_7436" x1="92" y1="-20.8858" x2="92"
                                y2="48.6151" gradientUnits="userSpaceOnUse">
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
</div>
<h4>Daftar Siswa Mengikuti Ujian</h4>

<div class="my-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <form method="GET" class="d-flex align-items-center gap-3 flex-wrap flex-grow-1">

            <div class="search-wrap bg-white rounded-3">
                <span class="search-icon text-muted">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M11 18C14.866 18 18 14.866 18 11C18 7.13401 14.866 4 11 4
                                                                    C7.13401 4 4 7.13401 4 11C4 14.866 7.13401 18 11 18Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <input type="text" class="form-control search-input" name="search" placeholder="Cari..."
                    value="{{ request('search') }}">
            </div>

            <select class="form-select filter-select bg-white w-100" name="sortBy">
                <option value="">Urutkan berdasarkan</option>
                <option value="highest" {{ request('sortBy') == 'highest' ? 'selected' : '' }}>
                    Nilai Tertinggi
                </option>
                <option value="lowest" {{ request('sortBy') == 'lowest' ? 'selected' : '' }}>
                    Nilai Terendah
                </option>
            </select>


            <button type="submit" class="btn btn-primary ">
                <svg width="13" height="14" viewBox="0 0 13 14" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.75 0.75H11.75V2.28318C11.7499 2.65757 11.605 3.0166 11.3471 3.28129
                    L8.3125 6.39706V11.3382L4.1875 12.75V6.75L1.1075 3.27141
                    C0.877495 3.0116 0.750037 2.6731 0.75 2.322V0.75Z" stroke="white" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>Filter</span>
            </button>

            <a href="{{ url()->current() }}#student-answer" class="btn btn-outline-primary">
                <i class="ti ti-refresh me-1"></i> Reset
            </a>
        </form>

        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-success gap-1 d-flex align-items-center" id="export-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 48 48">
                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="4">
                        <path stroke-linejoin="round"
                            d="M8 15V6a2 2 0 0 1 2-2h28a2 2 0 0 1 2 2v36a2 2 0 0 1-2 2H10a2 2 0 0 1-2-2v-9" />
                        <path d="M31 15h3m-6 8h6m-6 8h6" />
                        <path stroke-linejoin="round" d="M4 15h18v18H4zm6 6l6 6m0-6l-6 6" />
                    </g>
                </svg>
                Ekspor Ujian
            </button>
        </div>

    </div>
</div>

<div class="card shadow-md border border-1 mb-4">
    <div class="card-body p-4">
        <div class="table-responsive rounded-2 mb-4">
            <table class="table border text-nowrap customize-table mb-0 align-middle">
                <thead class="text-dark fs-4">
                    <tr class="">
                        <th class="text-white bg-primary">No</th>
                        <th class="text-white bg-primary">Nama</th>
                        <th class="text-white bg-primary text-center">Kelas</th>
                        <th class="text-white bg-primary text-center">Keterangan</th>
                        <th class="text-white bg-primary text-center">Nilai</th>
                        <th class="text-white bg-primary text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($studentExams as $studentExam)
                        <tr>
                            <td>{{ ($studentExams->firstItem() ?? 0) + $loop->index }}</td>
                            <td>{{ $studentExam['name'] ?? '-' }}</td>
                            <td class="text-center">
                                {{ $studentExam['class'] ?? '-' }}
                            </td>
                            <td class="text-center">

                                @if (strtolower($studentExam['status'] ?? '') === 'lulus')
                                    <div class="px-3 badge bg-light-success text-success text-capitalize">
                                        {{ $studentExam['status'] }}
                                    </div>
                                @elseif (strtolower($studentExam['status'] ?? '') === 'tidak lulus')
                                    <div class="px-3 badge bg-light-danger text-danger text-capitalize">
                                        {{ $studentExam['status'] }}
                                    </div>
                                @elseif (strtolower($studentExam['status'] ?? '') === 'sedang mengerjakan')
                                    <div class="px-3 badge bg-light-warning text-warning text-capitalize">
                                        {{ $studentExam['status'] }}
                                    </div>
                                @elseif (strtolower($studentExam['status'] ?? '') === 'tidak mengerjakan')
                                    <div class="px-3 badge bg-light text-muted text-capitalize">
                                        {{ $studentExam['status'] }}
                                    </div>
                                @else
                                    <div class="px-3 badge bg-light-secondary text-secondary text-capitalize">
                                        {{ $studentExam['status'] ?? '-' }}
                                    </div>
                                @endif

                            </td>

                            <td class="text-center">
                                <div class="text-primary">
                                    {{ isset($studentExam['score']) && $studentExam['score'] > 0 ? $studentExam['score'] : '-' }}
                                </div>
                            </td>
                            <td class="text-center">

                                @if (in_array(strtolower($studentExam['status'] ?? ''), ['lulus', 'tidak lulus']) &&
                                        !empty($studentExam['student_exam_id']))
                                    <a href="{{ route('teacher.exam-monitoring.student-detail', ['studentExam' => $studentExam['student_exam_id']]) }}"
                                        class="btn btn-primary btn-sm px-4">
                                        Detail
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center align-middle">
                                <div class="d-flex flex-column justify-content-center align-items-center py-4">
                                    <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}"
                                        alt="No Data" width="250">
                                    <p class="fs-5 text-dark text-center mt-3 mb-0">
                                        Data siswa tidak tersedia
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination justify-content-end mb-0">
                <x-paginate-component :paginator="$studentExams
                    ->withPath(request()->url())
                    ->appends(array_merge(request()->query(), ['tab' => 'student-answer']))" />
            </div>
        </div>
    </div>
</div>
