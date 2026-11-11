<div class="card card-body">
    <h4>Daftar Alumni</h4>

    {{-- ===== DESKTOP FILTER (lg ke atas) ===== --}}
    <div class="row mb-3 mt-3">
        <div class="col-lg-9 col-md-12 mb-3 d-none d-lg-block">
            <form class="d-flex flex-column flex-md-row gap-2" action="/school/students">
                <div class="position-relative flex-grow-1">
                    <input type="text" name="name" class="form-control product-search ps-5" id="input-search"
                        placeholder="Cari..." value="{{ old('name', request('name')) }}">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </div>
                <div class="d-flex flex-column flex-md-row gap-2">
                    <select name="gender" class="form-select">
                        <option value="">Tampilkan semua</option>
                        <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <select name="class" class="form-select">
                        <option value="">Pilih Kelas</option>
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->name }}" {{ request('class') == $classroom->name ? 'selected' : '' }}>
                                {{ $classroom->levelClass->name }} {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary w-100 w-md-auto">
                        <i class="ti ti-filter me-2"></i>Filter
                    </button>
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-100 w-md-auto">
                        <i class="ti ti-refresh me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- ===== MOBILE FILTER (di bawah lg) ===== --}}
        <div class="col-12 d-lg-none mb-2">
            <form method="GET" action="/school/students" id="mobile-filter-form-alumni">
                {{-- Search + tombol filter + tombol reset dalam satu baris --}}
                <div class="d-flex gap-2 align-items-center">
                    <div class="position-relative flex-grow-1">
                        <input type="text" name="name"
                            class="form-control product-search py-2 px-4 ps-5"
                            placeholder="Cari..."
                            value="{{ old('name', request('name')) }}">
                        <i class="ti ti-search position-absolute top-50 translate-middle-y fs-6 text-dark ms-3"></i>
                    </div>

                    {{-- Tombol buka/tutup panel filter --}}
                    <button type="button"
                        class="btn btn-primary px-3 py-2 flex-shrink-0"
                        style="border-radius: 10px;"
                        onclick="toggleMobileFilterAlumni()">
                        <i class="ti ti-filter fs-5"></i>
                    </button>

                    {{-- Tombol reset --}}
                    <a href="{{ url()->current() }}"
                        class="btn btn-outline-secondary px-3 py-2 flex-shrink-0"
                        style="border-radius: 10px;">
                        <i class="ti ti-refresh fs-5"></i>
                    </a>
                </div>

                {{-- Panel filter yang bisa dibuka/tutup --}}
                <div id="mobile-filter-panel-alumni"
                    class="{{ (request('gender') || request('class')) ? '' : 'collapse' }}"
                    style="margin-top: 10px;">
                    <div class="card card-body p-3" style="border-radius: 12px; border: 1px solid #e0e9ff; background: #f8faff;">

                        {{-- Gender --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted"
                                style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                                Jenis Kelamin
                            </label>
                            <select name="gender" class="form-select form-select-sm">
                                <option value="">Tampilkan semua</option>
                                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        {{-- Kelas --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted"
                                style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                                Kelas
                            </label>
                            <select name="class" class="form-select form-select-sm">
                                <option value="">Pilih Kelas</option>
                                @foreach ($classrooms as $classroom)
                                    <option value="{{ $classroom->name }}" {{ request('class') == $classroom->name ? 'selected' : '' }}>
                                        {{ $classroom->levelClass->name }} {{ $classroom->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol apply --}}
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-filter me-2"></i>Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive rounded-2 mb-4">
        <table class="table border text-nowrap customize-table mb-0 align-middle">
            <thead class="text-dark fs-4">
                <tr class="">
                    <th class="text-white" style="background-color: #5D87FF;">No</th>
                    <th class="text-white" style="background-color: #5D87FF;">Nama</th>
                    <th class="text-white" style="background-color: #5D87FF;">Jenis Kelamin</th>
                    <th class="text-white" style="background-color: #5D87FF;">NISN</th>
                    <th class="text-white" style="background-color: #5D87FF;">RFID</th>
                    <th class="text-white" style="background-color: #5D87FF;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($alumnus as $student)
                    <tr>
                        <td>{{ $alumnus->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $student->image ? asset('storage/' . $student->image) : asset('assets/images/default-user.jpeg') }}"
                                    class="rounded-circle me-2 user-profile" style="object-fit: cover" width="40"
                                    height="40" alt="" />
                                <div class="ms-3">
                                    <h6 class="fs-4 fw-semibold mb-0 text-start">{{ $student->student->user->name }}</h6>
                                    <span class="fw-normal">{{ $student->classroom->name }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $student->student->gender == 'male' ? 'Laki Laki' : 'Perempuan' }}</td>
                        <td>{{ $student->student->nisn }}</td>
                        <td>{{ $student->modelHasRfid ? $student->modelHasRfid->rfid : 'Kosong' }}</td>
                        <td>
                            <div class="dropdown dropstart">
                                <a href="#" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                    aria-expanded="false">
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
                                        <a class="dropdown-item d-flex align-items-center gap-3 btn-detail-alumni" type="button"
                                            data-id="{{ $student->student->id }}" data-name="{{ $student->student->user->name }}"
                                            data-email="{{ $student->student->user->email }}" data-nisn="{{ $student->student->nisn }}"
                                            data-religion_id="{{ $student->student->religion_id }}"
                                            data-gender="{{ $student->student->gender }}"
                                            data-birth_place="{{ $student->student->birth_place }}"
                                            data-birth_date="{{ $student->student->birth_date }}" data-nik="{{ $student->student->nik }}"
                                            data-number_kk="{{ $student->student->number_kk }}"
                                            data-number_akta="{{ $student->student->number_akta }}"
                                            data-order_child="{{ $student->student->order_child }}"
                                            data-count_siblings="{{ $student->student->count_siblings }}"
                                            data-address="{{ $student->student->address }}"
                                            data-rfid="{{ $student->student->modelHasRfid ? $student->student->modelHasRfid->rfid : 'Kosong' }}"
                                            data-image="{{ $student->student->image ? asset('storage/' . $student->student->image) : asset('assets/images/default-user.jpeg') }}">
                                            <i class="fs-4 ti ti-eye"></i>Detail
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center align-middle">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt=""
                                    width="300px">
                                <p class="fs-5 text-dark text-center mt-2">
                                    Siswa belum ditambahkan
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Script toggle mobile filter alumni --}}
<script>
    function toggleMobileFilterAlumni() {
        const panel = document.getElementById('mobile-filter-panel-alumni');
        panel.classList.toggle('collapse');
    }
</script>