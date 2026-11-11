@extends('school.layouts.app')

@section('content')
    <div class="card bg-primary shadow-none position-relative overflow-hidden text-light">
        <div class="card-body px-4 py-1">
            <div class="row align-items-center">
                <div class="col-8 col-md-10">
                    <h4 class="fw-semibold mb-2 text-light">Buku Tamu</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item" aria-current="page">Daftar pengunjung di sekolah</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-4 col-md-2 text-center">
                    <img src="{{ asset('assets/images/background/bag.png') }}" class="img-fluid w-75">
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-between mb-4 mt-3">
    <form method="GET" class="col-12" id="filterForm">
        <div class="d-flex flex-nowrap align-items-center gap-2">

            {{-- Search Input --}}
            <div class="flex-grow-1">
                <div class="input-group" style="border: 1px solid #dee2e6; border-radius: 6px; overflow: hidden;">
                    <span class="input-group-text border-0"
                        style="background: #fff;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                    </span>
                    <input type="text" name="search" id="searchInput"
                        class="form-control border-0 shadow-none ps-0"
                        placeholder="Cari..."
                        value="{{ old('search', request()->input('search')) }}"
                        autocomplete="off">
                </div>
            </div>

            {{-- Date picker desktop --}}
            <div class="d-none d-md-block" style="min-width: 170px;">
                <input type="date" name="date" id="dateDesktop" class="form-control"
                    value="{{ old('date', request()->input('date')) }}">
            </div>

            {{-- Button kalender mobile --}}
            <div class="d-block d-md-none position-relative flex-shrink-0">
                <button type="button"
                    class="btn btn-primary d-flex align-items-center justify-content-center"
                    id="btnDateMobile"
                    title="Pilih Tanggal"
                    style="width: 38px; height: 38px; padding: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                </button>
                <input type="date" name="date" id="dateMobile"
                    value="{{ old('date', request()->input('date')) }}"
                    style="position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none;">
            </div>

            {{-- Button reset --}}
            <div class="flex-shrink-0">
                <a href="{{ url()->current() }}"
                    class="btn btn-outline-secondary d-flex align-items-center justify-content-center"
                    style="width: 38px; height: 38px; padding: 0; border-radius: 6px;">
                    <i class="ti ti-refresh fs-5"></i>
                </a>
            </div>

        </div>
    </form>
    </div>

    <div class="row">
        <div class="table-responsive rounded-2 mb-4">
            <table class="table border text-nowrap customize-table mb-0 align-middle">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="text-white" style="background-color: #5D87FF;">No</th>
                        <th class="text-white" style="background-color: #5D87FF;">Tanggal</th>
                        <th class="text-white" style="background-color: #5D87FF;">Nama</th>
                        <th class="text-white" style="background-color: #5D87FF;">Status</th>
                        <th class="text-white" style="background-color: #5D87FF;">Jabatan</th>
                        <th class="text-white" style="background-color: #5D87FF;">Keperluan</th>
                        <th class="text-white" style="background-color: #5D87FF;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($guestBooks as $guestBook)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ Carbon\Carbon::parse($guestBook->date)->format('d F Y') }}</td>
                            <td>{{ $guestBook->name }}</td>
                            <td>
                                <span class="badge bg-light-{{ $guestBook->status->color() }} text-{{ $guestBook->status->color() }} text-capitalize">
                                    {{ $guestBook->status }}
                                </span>
                            </td>
                            <td>{{ $guestBook->position }}</td>
                            <td>{{ Str::limit($guestBook->needs, 50) }}</td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a class="btn-detail" type="button"
                                        data-name="{{ $guestBook->name }}"
                                        data-email="{{ $guestBook->email }}"
                                        data-status="{{ $guestBook->status }}"
                                        data-date="{{ $guestBook->date }}"
                                        data-phone_number="{{ $guestBook->phone_number }}"
                                        data-position="{{ $guestBook->position }}"
                                        data-address="{{ $guestBook->address }}"
                                        data-needs="{{ $guestBook->needs }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24">
                                            <g fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="1.5">
                                                <path d="M3 13c3.6-8 14.4-8 18 0" />
                                                <path d="M12 17a3 3 0 1 1 0-6a3 3 0 0 1 0 6" />
                                            </g>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center align-middle">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt=""
                                        width="300px">
                                    <p class="fs-5 text-dark text-center mt-2">Belum ada data</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('school.pages.guest-book.widgets.detail')
@endsection

@section('script')
    @include('school.pages.guest-book.scripts.detail')

    <script>
        const filterForm    = document.getElementById('filterForm');
        const searchInput   = document.getElementById('searchInput');
        const dateDesktop   = document.getElementById('dateDesktop');
        const btnDateMobile = document.getElementById('btnDateMobile');
        const dateMobile    = document.getElementById('dateMobile');

        // Auto-submit search dengan debounce 600ms
        let debounceTimer;
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                filterForm.submit();
            }, 600);
        });

        // Auto-submit saat date desktop berubah
        if (dateDesktop) {
            dateDesktop.addEventListener('change', function () {
                filterForm.submit();
            });
        }

        // Mobile: buka date picker via tombol ikon
        if (btnDateMobile && dateMobile) {
            btnDateMobile.addEventListener('click', function () {
                dateMobile.showPicker ? dateMobile.showPicker() : dateMobile.click();
            });

            // Auto-submit saat date mobile dipilih
            dateMobile.addEventListener('change', function () {
                filterForm.submit();
            });
        }
    </script>
@endsection