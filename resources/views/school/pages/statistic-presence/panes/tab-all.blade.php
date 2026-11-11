{{-- Filter Form --}}
<form action="" method="GET" class="d-flex flex-wrap gap-2 my-4 pt-3 pb-1">
    <input type="date" name="date" class="form-control" style="max-width: 180px;" id="date" value="{{ request('date') }}">
    <button type="submit" class="btn btn-primary">
        <i class="ti ti-filter me-2"></i>Filter
    </button>
    <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
        <i class="ti ti-refresh me-2"></i>Reset
    </a>
</form>

<div class="card card-body">

    {{-- Header: judul + tanggal + menu --}}
    <div class="d-block d-sm-flex align-items-center justify-content-between gap-2 mb-2">
        <h5 class="mb-0">Statistik Absensi Siswa</h5>

        <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
            <h5 class="mb-0">
                {{ \Carbon\Carbon::parse(request('date', now()))->translatedFormat('d F Y') }}
            </h5>

            <div class="btn-group dropstart" role="group">
                <button type="button" class="btn border-0 dropdown-toggle-split"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                              stroke-width="1.5" d="M20 7H4m16 5H4m16 5H4"/>
                    </svg>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Download SVG</a></li>
                    <li><a class="dropdown-item" href="#">Download PNG</a></li>
                    <li><a class="dropdown-item" href="#">Download CSV</a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Legend --}}
    <div id="custom-legend" class="d-flex flex-wrap align-items-center gap-2 mb-2">
        <span class="legend-text me-1">Petunjuk</span>
        <div class="legend-item">
            <span class="legend-marker" style="background-color: rgb(19, 222, 185);"></span>
            <span class="legend-text">Masuk</span>
        </div>
        <div class="legend-item">
            <span class="legend-marker" style="background-color: rgb(93, 135, 255);"></span>
            <span class="legend-text">Izin</span>
        </div>
        <div class="legend-item">
            <span class="legend-marker" style="background-color: rgb(255, 174, 31);"></span>
            <span class="legend-text">Sakit</span>
        </div>
        <div class="legend-item">
            <span class="legend-marker" style="background-color: rgb(250, 137, 107);"></span>
            <span class="legend-text">Alfa</span>
        </div>
    </div>

    <div id="chart-student"></div>
</div>

<div class="card card-body mt-3">
    <h5 class="mb-4">Data Absensi</h5>

    <div class="table-responsive rounded-2 mb-4">
        <table class="table border text-nowrap customize-table mb-0 align-middle">
            <thead class="text-dark fs-4">
                <tr>
                    <th class="text-white" style="background-color: #5D87FF;">No</th>
                    <th class="text-white" style="background-color: #5D87FF;">Nama Pengguna</th>
                    <th class="text-white" style="background-color: #5D87FF;">Masuk</th>
                    <th class="text-white" style="background-color: #5D87FF;">Pulang</th>
                    <th class="text-white" style="background-color: #5D87FF;">Point</th>
                    <th class="text-white" style="background-color: #5D87FF;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $attendance)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $attendance->model ? $attendance->model->student->user->name : 'siswa tidak ditemukan' }}</td>
                        <td>{{ $attendance->checkin ? \Carbon\Carbon::parse($attendance->checkin)->format('H.i') : '-' }}</td>
                        <td>{{ $attendance->checkout ? \Carbon\Carbon::parse($attendance->checkout)->format('H.i') : '-' }}</td>
                        <td>{{ $attendance->point }}</td>
                        <td>
                            <span class="badge {{ $attendance->status->color() }}">
                                {{ $attendance->status->label() }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="d-flex flex-column justify-content-center align-items-center py-3">
                                <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt="" width="200px">
                                <p class="fs-5 text-dark text-center mt-2">Tidak ada kehadiran siswa</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination justify-content-end mb-0">
        <x-paginate-component :paginator="$attendances->appends(request()->input())" />
    </div>
</div>