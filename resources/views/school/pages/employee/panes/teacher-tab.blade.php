@if (session('import_errors'))
    @foreach (session('import_errors') as $failure)
        <div class="alert alert-danger">
            Baris {{ $failure->row() }}: {{ $failure->errors()[0] }}
        </div>
    @endforeach
@endif

<div class="card card-body">
    <div class="">
        <h4>Daftar Guru</h4>
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
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondar w-100 w-md-auto">
                            <i class="ti ti-refresh me-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="d-lg-none mt-3 mb-3">
        <form method="GET" action="{{ url()->current() }}" id="mobile-filter-form-">
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
                    onclick="toggleMobileFilterTeacher()">
                    <i class="ti ti-filter fs-5"></i>
                </button>

                <a href="{{ url()->current() }}"
                    class="btn btn-outline-secondary px-3 py-2 flex-shrink-0"
                    style="border-radius: 10px;">
                    <i class="ti ti-refresh fs-5"></i>
                </a>
            </div>

            <div id="mobile-filter-panel-teacher"
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
        <div class="table-responsive rounded-2 ">
            <table class="table border text-nowrap customize-table mb-0 align-middle">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="text-white" style="background-color: #5D87FF;">No</th>
                        <th class="text-white" style="background-color: #5D87FF;">Nama Guru</th>
                        <th class="text-white" style="background-color: #5D87FF;">Jumlah Mapel</th>
                        <th class="text-white" style="background-color: #5D87FF;">Email</th>
                        <th class="text-white" style="background-color: #5D87FF;">NIP</th>
                        <th class="text-white" style="background-color: #5D87FF;">RFID</th>
                        <th class="text-white" style="background-color: #5D87FF;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachers as $teacher)
                        <tr>
                            <td>{{ $teachers->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $teacher->image ? asset('storage/' . $teacher->image) : asset('assets/images/default-user.jpeg') }}"
                                        class="rounded-circle" width="40" height="40">
                                    <div class="ms-3">
                                        <h6 class="fs-4 fw-semibold mb-0">{{ $teacher->user->name }}</h6>
                                        <span class="fw-normal">{{ $teacher->gender->label() }}</span>
                                    </div>
                                </div>

                            </td>
                            <td>
                                <span
                                    class="badge bg-light-primary text-primary">{{ $teacher->teacherSubjects->count() }}
                                    Mapel</span>
                            </td>
                            <td>{{ $teacher->user->email }}</td>
                            <td>{{ $teacher->nip }}</td>
                            <td>{{ $teacher->modelHasRfid ? $teacher->modelHasRfid->rfid : '-' }}
                                <button type="button" class="btn btn-rounded btn-warning p-1 ms-2 btn-rfid"
                                    data-name="{{ $teacher->user->name }}" data-id="{{ $teacher->id }}"
                                    data-rfid="{{ $teacher->modelHasRfid ? $teacher->modelHasRfid->rfid : 'Kosong' }}"
                                    data-old-rfid="{{ $teacher->modelHasRfid ? $teacher->modelHasRfid->rfid : 'Kosong' }}"
                                    data-role="Employee">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M21 12a1 1 0 0 0-1 1v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h6a1 1 0 0 0 0-2H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-6a1 1 0 0 0-1-1m-15 .76V17a1 1 0 0 0 1 1h4.24a1 1 0 0 0 .71-.29l6.92-6.93L21.71 8a1 1 0 0 0 0-1.42l-4.24-4.29a1 1 0 0 0-1.42 0l-2.82 2.83l-6.94 6.93a1 1 0 0 0-.29.71m10.76-8.35l2.83 2.83l-1.42 1.42l-2.83-2.83ZM8 13.17l5.93-5.93l2.83 2.83L10.83 16H8Z" />
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
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                        style="z-index: 20000;">
                                        <li>
                                            <button type="button"
                                                data-image="{{ $teacher->image ? asset('storage/' . $teacher->image) : asset('assets/images/default-user.jpeg') }}"
                                                data-name="{{ $teacher->user->name }}"
                                                data-email="{{ $teacher->user->email }}"
                                                data-phone="{{ $teacher->phone_number }}"
                                                data-gender="{{ $teacher->gender->label() }}"
                                                data-nip="{{ $teacher->nip }}"
                                                data-rfid="{{ $teacher->modelHasRfid ? $teacher->modelHasRfid->rfid : 'Belum memiliki rfid' }}"
                                                data-address="{{ $teacher->address }}"
                                                class="dropdown-item d-flex align-items-center gap-3 btn-detail-teacher">
                                                <i class="fs-4 ti ti-eye"></i>Detail</button>
                                        </li>
                                        <li>
                                            <a href="{{ route('school.teacher.show', $teacher->user->slug) }}"
                                                type="button"
                                                class="btn-detail dropdown-item d-flex align-items-center gap-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                        d="M6 22h15v-2H6.012C5.55 19.988 5 19.805 5 19s.55-.988 1.012-1H21V4c0-1.103-.897-2-2-2H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3M5 8V5c0-.805.55-.988 1-1h13v12H5z" />
                                                    <path fill="currentColor" d="M8 6h9v2H8z" />
                                                </svg>
                                                Mapel</a>
                                        </li>
                                        <li>
                                            <button type="button"
                                                class="dropdown-item d-flex align-items-center gap-3 btn-edit-teacher"
                                                data-id="{{ $teacher->id }}"
                                                data-image="{{ $teacher->image ? asset('storage/' . $teacher->image) : asset('admin_assets/dist/images/empty/default.png') }}"
                                                data-name="{{ $teacher->user->name }}"
                                                data-nip="{{ $teacher->nip }}"
                                                data-religion_id="{{ $teacher->religion_id }}"
                                                data-birth_date="{{ $teacher->birth_date }}"
                                                data-birth_place="{{ $teacher->birth_place }}"
                                                data-gender="{{ $teacher->gender->value }}"
                                                data-nik="{{ $teacher->nik }}"
                                                data-phone="{{ $teacher->phone_number }}"
                                                data-email="{{ $teacher->user->email }}"
                                                data-active="{{ $teacher->active }}"
                                                data-address="{{ $teacher->address }}">
                                                <i class="fs-4 ti ti-edit"></i>Edit
                                            </button>
                                        </li>
                                        <li>
                                            <button
                                                class="btn-delete dropdown-item d-flex align-items-center gap-3 text-danger btn-delete-teacher"
                                                data-id="{{ $teacher->id }}">
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
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}"
                                        alt="" width="300px">
                                    <p class="fs-5 text-dark text-center mt-2">
                                        Belum ada data
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination justify-content-end mt-2 mb-0">
            <x-paginate-component :paginator="$teachers->appends(request()->input())" />
        </div>
    </div>
</div>

{{-- Script toggle mobile filter teacher --}}
<script>
    function toggleMobileFilterTeacher() {
        const panel = document.getElementById('mobile-filter-panel-teacher');
        panel.classList.toggle('collapse');
    }
</script>
