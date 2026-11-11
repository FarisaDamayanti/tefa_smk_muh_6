@if(session('import_employee_errors'))
    @foreach(session('import_employee_errors') as $failure)
        <div class="alert alert-danger">
            Baris {{ $failure->row() }}: {{ $failure->errors()[0] }}
        </div>
    @endforeach
@endif

<div class="card card-body">
    <div class="">
        <h4>Daftar Staf</h4>
    </div>

    <div class="d-none d-lg-block">
        <div class="row">
            <div class="col-12 col-lg-8 mb-4 mt-3">
                <form class="d-flex flex-column flex-lg-row gap-2 align-items-stretch align-items-lg-center" method="GET" action="{{ url()->current() }}">
                    <div class="position-relative flex-grow-1 mb-2 mb-lg-0">
                        <input type="text" name="search" class="form-control search-chat py-2 px-4 ps-5" id="search-name"
                            placeholder="Cari" value="{{ old('search', request('search')) }}">
                        <i class="ti ti-search position-absolute top-50 translate-middle-y fs-6 text-dark ms-3"></i>
                    </div>
                    <div class="flex-grow-1 mb-2 mb-lg-0">
                        <select name="gender" class="form-select" id="search-status">
                            <option value="" {{ old('gender', request('gender')) == '' ? 'selected' : '' }}>Semua</option>
                            <option value="male" {{ old('gender', request('gender')) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender', request('gender')) == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="flex-grow-1 d-flex align-items-center" id="per_page">
                        <select name="per_page" aria-controls="zero_config" class="form-select">
                            <option value="10" {{ old('per_page', request('per_page')) == '10' ? 'selected' : '' }}>10</option>
                            <option value="25" {{ old('per_page', request('per_page')) == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ old('per_page', request('per_page')) == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ old('per_page', request('per_page')) == '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="d-flex flex-column flex-md-row flex-grow-1 gap-2">
                        <button type="submit" class="btn btn-primary w-100 w-md-auto">
                            <i class="ti ti-filter me-2"></i>Filter
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-100 w-md-auto">
                            <i class="ti ti-refresh me-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="d-lg-none mt-3 mb-3">
        <form method="GET" action="{{ url()->current() }}" id="mobile-filter-form-staff">
            {{-- Search + tombol filter + tombol reset dalam satu baris --}}
            <div class="d-flex gap-2 align-items-center">
                <div class="position-relative flex-grow-1">
                    <input type="text" name="search"
                        class="form-control search-chat py-2 px-4 ps-5"
                        placeholder="Cari"
                        value="{{ old('search', request('search')) }}">
                    <i class="ti ti-search position-absolute top-50 translate-middle-y fs-6 text-dark ms-3"></i>
                </div>

                <button type="button"
                    class="btn btn-primary px-3 py-2 flex-shrink-0"
                    style="border-radius: 10px;"
                    onclick="toggleMobileFilterStaff()">
                    <i class="ti ti-filter fs-5"></i>
                </button>

                <a href="{{ url()->current() }}"
                    class="btn btn-outline-secondary px-3 py-2 flex-shrink-0"
                    style="border-radius: 10px;">
                    <i class="ti ti-refresh fs-5"></i>
                </a>
            </div>

            <div id="mobile-filter-panel-staff"
                class="{{ (request('gender') || (request('per_page') && request('per_page') != '10')) ? '' : 'collapse' }}"
                style="margin-top: 10px;">
                <div class="card card-body p-3" style="border-radius: 12px; border: 1px solid #e0e9ff; background: #f8faff;">

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Jenis Kelamin
                        </label>
                        <select name="gender" class="form-select form-select-sm">
                            <option value="" {{ old('gender', request('gender')) == '' ? 'selected' : '' }}>Semua</option>
                            <option value="male" {{ old('gender', request('gender')) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender', request('gender')) == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Tampilkan Per Halaman
                        </label>
                        <select name="per_page" class="form-select form-select-sm">
                            <option value="10" {{ old('per_page', request('per_page', '10')) == '10' ? 'selected' : '' }}>10</option>
                            <option value="25" {{ old('per_page', request('per_page')) == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ old('per_page', request('per_page')) == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ old('per_page', request('per_page')) == '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-filter me-2"></i>Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="">
        <div class="table-responsive rounded-2 mb-4">
            <table class="table border text-nowrap customize-table mb-0 align-middle">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="text-white" style="background-color: #5D87FF;">No</th>
                        <th class="text-white" style="background-color: #5D87FF;">Nama Staf</th>
                        <th class="text-white" style="background-color: #5D87FF;">Status</th>
                        <th class="text-white" style="background-color: #5D87FF;">Email</th>
                        <th class="text-white" style="background-color: #5D87FF;">NIP</th>
                        <th class="text-white" style="background-color: #5D87FF;">RFID</th>
                        <th class="text-white" style="background-color: #5D87FF;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($staffs as $staff)
                        <tr>
                            <td>{{ $staffs->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $staff->image ? asset('storage/' . $staff->image) : asset('assets/images/default-user.jpeg') }}"
                                        class="rounded-circle" width="40" height="40">
                                    <div class="ms-3">
                                        <h6 class="fs-4 fw-semibold mb-0">{{ $staff->user->name }}</h6>
                                        <span class="fw-normal">{{ $staff->gender->label() }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light-{{ $staff->active == 1 ? 'primary' : 'danger' }} text-{{ $staff->active == 1 ? 'primary' : 'danger' }}">
                                    {{ $staff->active == 1 ? 'Aktif' : 'Tidak aktif' }}
                                </span>
                            </td>
                            <td>{{ $staff->user->email }}</td>
                            <td>{{ $staff->nip }}</td>
                            <td>{{ $staff->modelHasRfid ? $staff->modelHasRfid->rfid : '-' }}
                                <button type="button" class="btn btn-rounded btn-warning p-1 ms-2 btn-rfid"
                                    data-name="{{ $staff->user->name }}" data-id="{{ $staff->id }}"
                                    data-rfid="{{ $staff->modelHasRfid ? $staff->modelHasRfid->rfid : 'Kosong' }}"
                                    data-old-rfid="{{ $staff->modelHasRfid ? $staff->modelHasRfid->rfid : 'Kosong' }}"
                                    data-role="Employee">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M21 12a1 1 0 0 0-1 1v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h6a1 1 0 0 0 0-2H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-6a1 1 0 0 0-1-1m-15 .76V17a1 1 0 0 0 1 1h4.24a1 1 0 0 0 .71-.29l6.92-6.93L21.71 8a1 1 0 0 0 0-1.42l-4.24-4.29a1 1 0 0 0-1.42 0l-2.82 2.83l-6.94 6.93a1 1 0 0 0-.29.71m10.76-8.35l2.83 2.83l-1.42 1.42l-2.83-2.83ZM8 13.17l5.93-5.93l2.83 2.83L10.83 16H8Z" />
                                    </svg>
                                </button>
                            </td>
                            <td>
                                <div class="dropdown dropstart">
                                    <a href="#" class="text-muted" id="dropdownMenuButton"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="category">
                                            <div class="category-business"></div>
                                            <div class="category-social"></div>
                                            <span class="more-options text-dark">
                                                <i class="ti ti-dots-vertical fs-5"></i>
                                            </span>
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <button type="button"
                                                class="dropdown-item d-flex align-items-center gap-3 btn-detail-employee"
                                                data-image="{{ $staff->image ? asset('storage/' . $staff->image) : asset('assets/images/default-user.jpeg') }}"
                                                data-name="{{ $staff->user->name }}"
                                                data-email="{{ $staff->user->email }}"
                                                data-phone="{{ $staff->phone_number }}"
                                                data-gender="{{ $staff->gender->label() }}"
                                                data-nip="{{ $staff->nip }}"
                                                data-rfid="{{ $staff->modelHasRfid ? $staff->modelHasRfid->rfid : 'Belum memiliki rfid' }}"
                                                data-address="{{ $staff->address }}">
                                                <i class="fs-4 ti ti-eye"></i>Detail
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button"
                                                class="btn-update dropdown-item d-flex align-items-center gap-3 btn-edit-employee"
                                                data-id="{{ $staff->id }}"
                                                data-image="{{ $staff->image ? asset('storage/' . $staff->image) : asset('admin_assets/dist/images/empty/default.png') }}"
                                                data-name="{{ $staff->user->name }}"
                                                data-nip="{{ $staff->nip }}"
                                                data-religion_id="{{ $staff->religion_id }}"
                                                data-birth_date="{{ $staff->birth_date }}"
                                                data-birth_place="{{ $staff->birth_place }}"
                                                data-gender="{{ $staff->gender->value }}"
                                                data-nik="{{ $staff->nik }}"
                                                data-phone="{{ $staff->phone_number }}"
                                                data-email="{{ $staff->user->email }}"
                                                data-active="{{ $staff->active }}"
                                                data-address="{{ $staff->address }}">
                                                <i class="fs-4 ti ti-edit"></i>Edit
                                            </button>
                                        </li>
                                        <li>
                                            <button class="btn-delete dropdown-item d-flex align-items-center gap-3 text-danger btn-delete-staff"
                                                data-id="{{ $staff->id }}">
                                                <i class="fs-4 ti ti-trash"></i>Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center align-middle">
                                <div class="d-flex flex-column justify-content-center align-
                                
                                
                                
                                
                                items-center">
                                    <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt="" width="300px">
                                    <p class="fs-5 text-dark text-center mt-2">Belum ada data</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination justify-content-end mb-0">
            <x-paginate-component :paginator="$staffs->appends(request()->input())" />
        </div>
    </div>
</div>

{{-- Script toggle mobile filter staf --}}
<script>
    function toggleMobileFilterStaff() {
        const panel = document.getElementById('mobile-filter-panel-staff');
        panel.classList.toggle('collapse');
    }
</script>