@extends('teacher.layouts.app')
@section('content')
    <div class="card header-gradient shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold text-white mb-8">Detail Presensi</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a class="text-white text-decoration-none" href="javascript:void(0)">
                                    Manajemen kegiatan belajar mengajar dengan fitur ini
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('admin_assets/dist/images/breadcrumb/ChatBc.png') }}" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- TOOLBAR --}}
    <div class="mt-4 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <form method="GET" class="d-flex align-items-center gap-3 flex-wrap flex-grow-1">
                {{-- Search --}}
                <div class="search-wrap flex-grow-1" style="min-width: 200px;">
                    <span class="search-icon text-muted">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M11 18C14.866 18 18 14.866 18 11C18 7.13401 14.866 4 11 4C7.13401 4 4 7.13401 4 11C4 14.866 7.13401 18 11 18Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <input type="text" class="form-control search-input" name="search"
                        placeholder="Cari kelas atau mapel..." value="{{ request('search') }}">
                </div>
                <select class="form-select filter-select" name="status" style="width: 180px;">
                    <option value="" {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>
                        Semua Kehadiran
                    </option>
                    <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Masuk</option>
                    <option value="permit" {{ request('status') === 'permit' ? 'selected' : '' }}>Izin</option>
                    <option value="sick" {{ request('status') === 'sick' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpha" {{ request('status') === 'alpha' ? 'selected' : '' }}>Alpha</option>
                </select>
                <div class="d-flex gap-2" style="min-width: 200px;">
                    <button type="submit" class="btn btn-primary flex-grow-1" style="height: 44px;">
                        <i class="ti ti-filter me-2"></i>Filter
                    </button>
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary flex-grow-1" style="height: 44px;">
                        <i class="ti ti-refresh me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow rounded-4 p-3">

        @if (!empty($error))
            <div class="alert alert-danger mb-3">{{ $error }}</div>
        @endif

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $statusMap = [
                            'present' => ['class' => 'badge-masuk', 'label' => 'Masuk'],
                            'permit' => ['class' => 'badge-izin', 'label' => 'Izin'],
                            'alpha' => ['class' => 'badge-alpha', 'label' => 'Alpha'],
                            'absent' => ['class' => 'badge-alpha', 'label' => 'Alpha'],
                        ];

                        $rows = $journal['data'] ?? [];
                    @endphp

                    @forelse ($rows as $i => $row)
                        @php
                            $key = strtolower($row['status'] ?? 'alpha');
                            $s = $statusMap[$key] ?? ['class' => 'badge-alpha', 'label' => ucfirst($key)];
                        @endphp
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $row['nisn'] ?? '-' }}</td>
                            <td>{{ $row['student'] ?? '-' }}</td>
                            <td>{{ $row['classroom']['name'] ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge {{ $s['class'] }}">{{ $s['label'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-5">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt=""
                                        width="300">
                                    <p class="fs-5 text-dark text-center mt-2">
                                        Data Tidak Ditemukan
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <style>
        .header-gradient {
            background: linear-gradient(180deg,
                    rgba(81, 182, 255, 1) 0%,
                    rgba(79, 124, 255, 1) 100%);
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            color: #ffffff;
        }

        .header-gradient h1,
        .header-gradient h2,
        .header-gradient h3,
        .header-gradient h4,
        .header-gradient h5,
        .header-gradient h6,
        .header-gradient p,
        .header-gradient span,
        .header-gradient a {
            color: #ffffff;
        }

        .header-gradient .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 0;
        }

        .header-gradient .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.9);
        }

        .header-gradient .breadcrumb-item a:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        .header-gradient img {
            max-height: 140px;
            object-fit: contain;
        }

        @media (max-width: 576px) {
            .header-gradient {
                border-radius: 10px;
            }

            .header-gradient img {
                max-height: 110px;
            }
        }

        .search-wrap {
            position: relative;
            width: 230px;
            max-width: 300px;
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa0a6;
            pointer-events: none;
        }

        .search-input {
            border-radius: 12px;
            padding-left: 44px;
            height: 44px;
        }

        .filter-select {
            width: 180px;
            max-width: 200px;
            height: 40px;
            border-radius: 12px;
        }

        .search-input,
        .filter-select,
        .btn {
            height: 44px !important;
            border-radius: 12px !important;
        }

        .btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        @media (max-width: 768px) {

            .search-wrap,
            .filter-select,
            .d-flex.gap-2 {
                min-width: 100% !important;
                width: 100% !important;
            }

            .d-flex.gap-2 {
                margin-top: 8px;
            }
        }

        .table thead th {
            background: #5f86ff !important;
            color: #ffffff !important;
            font-weight: 500;
            padding: 14px;
            border: none !important;
            white-space: nowrap;
        }

        /* Rounded header */
        .table thead th:first-child {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .table thead th:last-child {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .badge-masuk {
            background: #e6fffa;
            color: #00c9a7;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
        }

        .badge-izin {
            background: #fff7ed;
            color: #f59e0b;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
        }

        .badge-alpha {
            background: #fee2e2;
            color: #ef4444;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
        }

        @media (max-width: 768px) {

            table th,
            table td {
                font-size: 13px;
            }
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            min-width: 900px;
        }
    </style>
    @section('script')
        <script>
            $(function() {
                const params = new URLSearchParams(window.location.search);
                if (params.get('saved') === '1') {
                    toastr.success('Presensi berhasil disimpan');
                    window.history.replaceState({}, document.title, window.location.pathname);
                }
            });
        </script>
    @endsection
@endsection
