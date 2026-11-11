@extends('student.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Daftar Tugas" breadcrumb="Ini adalah daftar Tugas" />

    <div class="mt-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <!-- Form Filter -->
            <form method="GET" action="{{ route('student.tasks.index') }}"
                class="d-flex align-items-center flex-wrap gap-3 flex-grow-1">
                <!-- Search -->
                <div class="search-wrap">
                    <input type="text" class="form-control search-input search-md" name="search"
                        value="{{ request('search') }}" placeholder="Cari tugas...">
                </div>

                <!-- Subject -->
                <select class="form-select filter-select select-md" name="subject_id">
                    <option value="">Semua Mapel</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Deadline Sort -->
                <select class="form-select filter-select select-sm" name="deadline">
                    <option value="">Semua Deadline</option>
                    <option value="terdekat" {{ request('deadline') == 'terdekat' ? 'selected' : '' }}>
                        Deadline Terdekat
                    </option>
                    <option value="terjauh" {{ request('deadline') == 'terjauh' ? 'selected' : '' }}>
                        Deadline Terlama
                    </option>
                </select>

                <!-- Academic Year & Semester -->
                <select class="form-select filter-select select-lg-custom" name="academic_year">
                    <option value="">Semua Tahun</option>
                    @foreach ($schoolYears as $year)
                        <option value="{{ $year->id }}_odd"
                            {{ request('academic_year') == $year->id . '_odd' ? 'selected' : '' }}>
                            Semester Ganjil {{ $year->school_year }}
                        </option>
                        <option value="{{ $year->id }}_even"
                            {{ request('academic_year') == $year->id . '_even' ? 'selected' : '' }}>
                            Semester Genap {{ $year->school_year }}
                        </option>
                    @endforeach
                </select>

                <!-- Buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-filter me-2"></i>Filter
                    </button>
                    <a href="{{ route('student.tasks.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-refresh me-2"></i>Reset
                    </a>
                </div>
            </form>

            <div class="ms-auto d-flex align-items-center gap-2">
                <a href="{{ route('student.e-learning.index') }}" class="btn btn-warning btn-preview">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-container">
        <div class="table-card">
            <table class="table custom-table">
                <thead class="table-header">
                    <tr>
                        <th>No</th>
                        <th>Judul Tugas</th>
                        <th>Mata Pelajaran</th>
                        <th>Tahun Ajaran</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($tasks->count() > 0)
                        @foreach ($tasks as $task)
                            @php
                                // Logika status dari kode pertama
                                $status = 'pending';
                                if ($task->currentUserTaskSubmission) {
                                    $status = 'completed';
                                } elseif (now()->gt($task->deadline)) {
                                    $status = 'not_submitted';
                                }

                                // Mapping class status sesuai desain kedua
                                $statusClasses = [
                                    'completed' => 'badge-status status-success',
                                    'pending' => 'badge-status status-warning',
                                    'not_submitted' => 'badge-status status-danger',
                                ];

                                $statusTexts = [
                                    'completed' => 'Sudah Dikerjakan',
                                    'pending' => 'Belum Dikerjakan',
                                    'not_submitted' => 'Terlewat',
                                ];
                            @endphp 
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->topic->subject->name ?? '-' }}</td>
                                <td>
                                    {{ $task->topic->classroom->schoolYear->school_year ?? '-' }}
                                    ({{ ucfirst($task->topic->semester ?? '-') }})
                                </td>

                                <td>{{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->isoFormat('D MMMM YYYY') : '-' }}
                                </td>
                                <td>
                                    <span class="{{ $statusClasses[$status] }}">
                                        {{ $statusTexts[$status] }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('student.tasks.show', $task->id) }}" class="btn btn-warning " >
                                        detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="ti ti-clipboard-off"></i>
                                    <h5 class="mt-3">Tidak ada tugas</h5>
                                    <p class="text-muted">Tidak ditemukan tugas yang sesuai dengan filter</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($tasks->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
                Menampilkan {{ $tasks->firstItem() }} - {{ $tasks->lastItem() }}
                dari {{ $tasks->total() }} tugas
            </div>
            <nav>
                {{ $tasks->appends(request()->except('page'))->links() }}
            </nav>
        </div>
    @endif

   <style>
    .select-sm {
        width: 120px;
    }

    .select-md {
        width: 220px;
    }

    .select-lg-custom {
        width: 280px !important;
        min-width: 280px !important;
        max-width: 280px !important;
    }

    .search-md {
        width: 240px;
    }

    .table-container {
        background: #ffffff;
        border-radius: 16px;
        padding: 14px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
    }

    .table-card {
        border-radius: 14px;
        overflow: hidden;
    }

    .custom-table {
        margin-bottom: 0;
        width: 100%;
        table-layout: fixed; /* INI PENTING! */
    }

    .custom-table thead {
        background: #5D87FF;
    }

    .custom-table thead th {
        color: #fff !important;
        font-weight: 600;
        font-size: 14px;
        padding: 14px;
        text-align: center;
        border: none;
        background: transparent !important;
    }

    /* Border radius untuk thead */
    .custom-table thead th:first-child {
        border-top-left-radius: 14px;
    }

    .custom-table thead th:last-child {
        border-top-right-radius: 14px;
    }

    .custom-table tbody td {
        font-size: 14px;
        padding: 14px;
        vertical-align: middle;
        text-align: center;
        background: #fff;
    }

    .custom-table tbody td:nth-child(2),
    .custom-table tbody td:nth-child(3) {
        text-align: left;
    }

    .custom-table tbody tr:not(:last-child) td {
        border-bottom: 1px solid #eee;
    }

    /* Set width untuk tiap kolom agar konsisten */
    .custom-table th:nth-child(1),
    .custom-table td:nth-child(1) {
        width: 5%;
    }

    .custom-table th:nth-child(2),
    .custom-table td:nth-child(2) {
        width: 20%;
    }

    .custom-table th:nth-child(3),
    .custom-table td:nth-child(3) {
        width: 15%;
    }

    .custom-table th:nth-child(4),
    .custom-table td:nth-child(4) {
        width: 18%;
    }

    .custom-table th:nth-child(5),
    .custom-table td:nth-child(5) {
        width: 15%;
    }

    .custom-table th:nth-child(6),
    .custom-table td:nth-child(6) {
        width: 17%;
    }

    .custom-table th:nth-child(7),
    .custom-table td:nth-child(7) {
        width: 10%;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .status-danger {
        background: #FFE2E2;
        color: #FF4D4F;
    }

    .status-warning {
        background: #FFF4D6;
        color: #FAAD14;
    }

    .status-success {
        background: #E6FFFB;
        color: #13C2C2;
    }

    .btn-detail {
        background: #5D87FF;
        color: #fff;
        border-radius: 10px;
        padding: 6px 14px;
        font-size: 13px;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-detail:hover {
        background: #4a74f5;
        transform: translateY(-1px);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state i {
        font-size: 48px;
        color: #ccc;
        margin-bottom: 16px;
    }

    .empty-state p {
        color: #666;
        margin: 0;
    }

    .btn-preview {
        background: #FFA726;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }

    .btn-preview:hover {
        background: #FF9800;
        transform: translateY(-1px);
    }
</style>
@endsection
