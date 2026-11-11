<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileSidebar">

    <div class="offcanvas-header border-bottom">
        <img src="{{ asset('assets/images/logo/logo-miscool.png') }}" width="140">
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        @include('teacher.layouts.sidebar-content')
    </div>
</div>

{{-- <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
    <div class="container-fluid px-3">
        <div class="d-flex align-items-center">
            <!-- Logo untuk desktop -->
            <button class="navbar-toggler border-0 d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Toggle navigation">
                <i class="ti ti-menu-2"></i>
            </button>
            <a class="navbar-brand d-none d-lg-block me-4" href="/">
                <img src="{{ asset('assets/images/logo/logo-miscool.png') }}" width="150" alt="Mischol Logo">
            </a>
            <a class="navbar-brand d-lg-none" href="/">
                <img src="{{ asset('assets/images/logo/logo-miscool.png') }}" width="120" alt="Mischol Logo">
            </a>


        </div>
        <div class="collapse navbar-collapse mx-4" id="teacherNavbar">
            <ul class="navbar-nav me-auto">
                <!-- Role Teacher -->
                @role('teacher')
                    <!-- Beranda -->
                    <li class="nav-item">
                        <a class="nav-link nav-menu d-flex align-items-center py-3 {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}"
                            href="{{ route('teacher.dashboard') }}">
                            <span class="fw-normal">Beranda</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-menu d-flex align-items-center py-3" href="/teacher/attendance/history">
                            <span class="fw-normal">Absensi</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-menu d-flex align-items-center py-3
                             {{ request()->routeIs('teacher.e-learning.*') ? 'active' : '' }}"
                            href="{{ route('teacher.e-learning.index') }}">
                            <span class="fw-normal">E-Learning</span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link nav-menu d-flex align-items-center py-3 {{ request()->routeIs('teacher.journals.*') ? 'active' : '' }}"
                            href="{{ route('teacher.journals.index') }}">
                            <span class="fw-normal">Jurnal</span>
                        </a>
                    </li>
                    @if ($permission_feedback ?? false)
                        <li class="nav-item">
                            <a class="nav-link nav-menu d-flex align-items-center py-3" href="/teacher/student-feedback">
                                <span class="fw-normal">Tanggapan Siswa</span>
                            </a>
                        </li>
                    @endif



                    @if (App\Models\Classroom::where('employee_id', auth()->user()->employee->id)->exists())
                        <li class="nav-item">
                            <a class="nav-link nav-menu  d-flex align-items-center py-3 {{ request()->routeIs('teacher.list-student-class.index') ? 'active' : '' }}"
                                href="{{ route('teacher.list-student-class.index') }}">
                                <span class="fw-normal">Daftar Siswa Dikelas</span>
                            </a>
                        </li>
                    @endif
                @endrole
                @role('staff')
                    <li class="nav-item">
                        <a class="nav-link nav-menu d-flex align-items-center py-3"
                            href="{{ route('employee.dashboard') }}">
                            <i class="ti ti-aperture me-2"></i>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-menu d-flex align-items-center py-3 {{ Route::is('employee.guestbook.index') ? 'active' : '' }}"
                            href="{{ route('employee.guestbook.index') }}">
                            <i class="ti ti-book-2 me-2"></i>
                            <span>Buku Tamu</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-menu d-flex align-items-center py-3 {{ request()->routeIs('employee.journal.*') ? 'active' : '' }}"
                            href="{{ route('employee.journal.index') }}">
                            <i class="ti ti-book me-2"></i>
                            <span>Jurnal</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-menu d-flex align-items-center py-3"
                            href="{{ route('employee.permission') }}">
                            <i class="ti ti-checklist me-2"></i>
                            <span>Perizinan</span>
                        </a>
                    </li>
                @endrole
                @can('view_violation')
                    <li class="nav-item">
                        <a class="nav-link nav-menu d-flex align-items-center py-3"
                            href="{{ route('employee.violation.overview') }}">
                            <i class="ti ti-chart-bar me-2"></i>
                            <span>Overview</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-menu d-flex align-items-center py-3" href="/employee/rfid-student-violation">
                            <i class="ti ti-plus me-2"></i>
                            <span>Tambah Pelanggaran</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-menu d-flex align-items-center py-3 {{ request()->routeIs('employee.violation.student-point.*') ? 'active' : '' }}"
                            href="{{ route('employee.violation.student-point.index') }}">
                            <i class="ti ti-list-numbers me-2"></i>
                            <span>Daftar Point Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-menu d-flex align-items-center py-3"
                            href="{{ route('employee.violation.students') }}">
                            <i class="ti ti-alert-triangle me-2"></i>
                            <span>Daftar Pelanggaran</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-menu d-flex align-items-center py-3"
                            href="{{ route('employee.violation.student-repair.index') }}">
                            <i class="ti ti-tools me-2"></i>
                            <span>Daftar Perbaikan</span>
                        </a>
                    </li>
                @endcan
            </ul>
            <!-- Profile Section -->
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center" href="#" id="profileDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">

                        <div class="me-2 text-end d-none d-lg-block">
                            <div class="fw-semibold" style="font-size: 0.9rem;">
                                {{ Str::limit(auth()->user()->name, 20) }}
                            </div>
                            <small class="text-muted" style="font-size: 0.8rem;">
                                @role('teacher')
                                    @if (auth()->user()->employee && auth()->user()->employee->email)
                                        {{ auth()->user()->employee->email }}
                                    @else
                                        {{ auth()->user()->email }}
                                    @endif
                                @else
                                    {{ auth()->user()->email }}
                                @endrole
                            </small>
                        </div>
                        <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('assets/images/default-user.jpeg') }}"
                            class="rounded-circle border" width="40" height="40" style="object-fit: cover;">
                    </a>

                    @php
                        $roleLabel = match (auth()->user()->getRoleNames()->first()) {
                            'teacher' => 'Guru Profile',
                            'student' => 'Siswa Profile',
                        };

                        $roleName = match (auth()->user()->getRoleNames()->first()) {
                            'teacher' => 'Guru',
                            'student' => 'Siswa',
                        };
                    @endphp


                    <div class="dropdown-menu dropdown-menu-end p-0 border-0 shadow"
                        style="width: 320px; border-radius: 16px;">

                        <div class="p-4">
                            <h5 class="fw-semibold text-start mb-4">{{ $roleLabel }}</h5>
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <img src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image) : asset('assets/images/default-user.jpeg') }}"
                                    class="rounded-circle" width="64" height="64"
                                    style="object-fit: cover; background:#e5e7eb;">
                                <div>
                                    <div class="fw-semibold">{{ $roleName }}</div>

                                    <div class="d-flex align-items-center gap-2 text-muted small">
                                        <i class="ti ti-mail"></i>
                                        <span>{{ auth()->user()->email }}</span>
                                    </div>
                                </div>

                            </div>
                            <a href="/teacher/profile" class="mb-3 btn btn-primary w-100 rounded-pill">
                                Profil Saya
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary w-100 rounded-pill">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</nav> --}}


<header class="app-header w-100">
    <nav class="navbar navbar-expand-lg navbar-light d-flex align-items-center">

        {{-- KIRI: Hamburger + Logo --}}
        <div class="d-flex align-items-center">
            <button class="navbar-toggler border-0 d-lg-none me-2" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar"
                aria-controls="mobileSidebar">
                <i class="ti ti-menu-2"></i>
            </button>
            <a class="navbar-brand d-none d-lg-block me-4" href="/">
                <img src="{{ asset('assets/images/logo/logo-miscool.png') }}" width="150" alt="Mischol Logo">
            </a>
            <a class="navbar-brand d-lg-none mb-0" href="/">
                <img src="{{ asset('assets/images/logo/logo-miscool.png') }}" width="120" alt="Mischol Logo">
            </a>
        </div>

        {{-- TENGAH: Menu Navigasi (desktop only) --}}
        <ul class="navbar-nav quick-links d-none d-lg-flex gap-3 align-items-center flex-grow-1">
            @role('teacher')
                <li class="nav-item">
                    <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}"
                        href="{{ route('teacher.dashboard') }}" style="color: black !important;">
                        <span class="fw-normal">Beranda</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('teacher.attendance.*') ? 'active' : '' }}"
                        href="{{ route('teacher.attendance.history') }}" style="color: black !important;">
                        <span class="fw-normal">Absensi</span>
                    </a>
                </li>

                <li class="nav-item dropdown hover-dd">
                    <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('teacher.e-learning.*') || request()->routeIs('teacher.evaluation.*') ? 'active' : '' }}"
                        style="color: black !important;" href="javascript:void(0)"
                        data-bs-toggle="dropdown" role="button" aria-expanded="false">
                        E-Learning<span class="mt-1"><i class="ti ti-chevron-down"></i></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-nav dropdown-menu-animate-up py-0 position-fixed"
                        style="width: 350px">
                        <div class="row">
                            <div class="col-10">
                                <div class="ps-7 pt-7">
                                    <div class="border-bottom">
                                        <div class="col-12">
                                            <div class="position-relative">
                                                <a href="{{ route('teacher.e-learning.index') }}"
                                                    class="d-flex align-items-center pb-9 position-relative text-decoration-none">
                                                    <div class="bg-light-success rounded-1 me-3 p-6 d-flex align-items-center justify-content-center text-success">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 20 20">
                                                            <path fill="currentColor" d="M8.5 2a1.5 1.5 0 0 0-1.415 1H5.5A1.5 1.5 0 0 0 4 4.5v12A1.5 1.5 0 0 0 5.5 18h2.504a2 2 0 0 1 .054-.347l.375-1.498a3.2 3.2 0 0 1 .84-1.485l4.83-4.83A2.86 2.86 0 0 1 16 9.004V4.5A1.5 1.5 0 0 0 14.5 3h-1.585A1.5 1.5 0 0 0 11.5 2zM8 3.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m1.98 11.877l4.83-4.83a1.87 1.87 0 1 1 2.644 2.646l-4.83 4.829a2.2 2.2 0 0 1-1.02.578l-1.498.374a.89.89 0 0 1-1.079-1.078l.375-1.498a2.2 2.2 0 0 1 .578-1.02" />
                                                        </svg>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-1 fw-semibold bg-hover-primary">Kelas</h6>
                                                        <span class="fs-2 d-block text-dark">Manajemen tugas dan materi setiap kelas</span>
                                                    </div>
                                                </a>
                                                <a href="{{ route('teacher.e-learning.grade-summary') }}"
                                                    class="d-flex align-items-center pb-9 position-relative text-decoration-none">
                                                    <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 48 48">
                                                            <g fill="currentColor">
                                                                <path d="M20 18.6L17.75 24h4.5z" />
                                                                <path fill-rule="evenodd" d="M38 15L28 4H14a4 4 0 0 0-4 4v32a4 4 0 0 0 4 4h20a4 4 0 0 0 4-4zm-18 0a1 1 0 0 1 .923.615l5 12a1 1 0 0 1-1.846.77L23.083 26h-6.166l-.994 2.385a1 1 0 0 1-1.846-.77l5-12A1 1 0 0 1 20 15m-5 17a1 1 0 1 0 0 2h18a1 1 0 1 0 0-2zm-1 5a1 1 0 0 1 1-1h12a1 1 0 1 1 0 2H15a1 1 0 0 1-1-1m17-15a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2zM28 7l7 8h-6a1 1 0 0 1-1-1z" clip-rule="evenodd" />
                                                            </g>
                                                        </svg>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-1 fw-semibold bg-hover-primary">Rekap Nilai</h6>
                                                        <span class="fs-2 d-block text-dark">Ringkasan nilai seluruh tugas siswa yang Anda ajar</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown hover-dd">
                    <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('teacher.question-bank.*') || request()->routeIs('teacher.exam-monitoring.*') ? 'active' : '' }}"
                        style="color: black !important;" href="javascript:void(0)"
                        data-bs-toggle="dropdown" role="button" aria-expanded="false">
                        Ujian<span class="mt-1"><i class="ti ti-chevron-down"></i></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-nav dropdown-menu-animate-up py-0 position-fixed"
                        style="width: 350px">
                        <div class="row">
                            <div class="col-10">
                                <div class="ps-7 pt-7">
                                    <div class="border-bottom">
                                        <div class="col-12">
                                            <div class="position-relative">
                                                <a href="{{ route('teacher.question-bank.index') }}"
                                                    class="d-flex align-items-center pb-9 position-relative text-decoration-none">
                                                    <div class="bg-light-success rounded-1 me-3 p-6 d-flex align-items-center justify-content-center text-success">
                                                        <svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M0 2.2357C0 1.00257 1.03877 0 2.31643 0H7.72746C8.34276 0 8.93272 0.23405 9.36705 0.653245L13.2181 4.37709C13.6524 4.79629 13.8949 5.36569 13.8949 5.95955V9.36551L9.11731 13.9766H7.59354L7.01081 12.1042C6.8407 11.5558 6.3195 11.182 5.72592 11.182C5.31693 11.182 4.93327 11.3602 4.67991 11.6676L2.50464 14.2876C2.20423 14.6474 2.26576 15.1783 2.63856 15.4648C3.01136 15.7512 3.56151 15.6953 3.8583 15.332L5.56305 13.278L6.1132 15.0491C6.22178 15.4054 6.56201 15.6464 6.94566 15.6464H8.08578C8.05321 15.7547 8.02425 15.8665 8.00253 15.9783L7.60802 17.8821H2.31643C1.03877 17.8821 0 16.8796 0 15.6464V2.23221V2.2357ZM7.52839 2.04357V5.3098C7.52839 5.77441 7.91567 6.14819 8.39705 6.14819H11.7812L7.52839 2.04357ZM9.70728 16.3102C9.79776 15.877 10.0185 15.4788 10.3407 15.1679L14.6442 11.0143L17.5397 13.809L13.2362 17.9625C12.9141 18.2734 12.5015 18.4865 12.0527 18.5738L9.89549 18.9895C9.86292 18.9965 9.82672 19 9.79053 19C9.50097 19 9.26209 18.7729 9.26209 18.49C9.26209 18.455 9.26571 18.4236 9.27295 18.3887L9.70366 16.3067L9.70728 16.3102ZM19.4001 12.0134L18.3577 13.0195L15.4622 10.2249L16.5045 9.21879C17.3044 8.44677 18.6002 8.44677 19.4001 9.21879C20.2 9.99081 20.2 11.2414 19.4001 12.0134Z" fill="#13DEB9" />
                                                        </svg>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-1 fw-semibold bg-hover-primary">Bank Soal</h6>
                                                        <span class="fs-2 d-block text-dark">Manajemen soal ujian pada mata pelajaran yang anda miliki</span>
                                                    </div>
                                                </a>
                                                <a href="{{ route('teacher.exam-monitoring.index') }}"
                                                    class="d-flex align-items-center pb-9 position-relative text-decoration-none">
                                                    <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">
                                                        <svg width="15" height="20" viewBox="0 0 15 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M0 2.5C0 1.12109 1.12109 0 2.5 0H8.33984C9.00391 0 9.64062 0.261719 10.1094 0.730469L14.2695 4.89453C14.7383 5.36328 15 6 15 6.66406V17.5C15 18.8789 13.8789 20 12.5 20H2.5C1.12109 20 0 18.8789 0 17.5V2.5ZM8.125 2.28516V5.9375C8.125 6.45703 8.54297 6.875 9.0625 6.875H12.7148L8.125 2.28516ZM6.83594 17.2266C7.20312 17.5938 7.79687 17.5938 8.16016 17.2266L10.6602 14.7266C11.0273 14.3594 11.0273 13.7656 10.6602 13.4023C10.293 13.0391 9.69922 13.0352 9.33594 13.4023L8.4375 14.3008V10.9375C8.4375 10.418 8.01953 10 7.5 10C6.98047 10 6.5625 10.418 6.5625 10.9375V14.3008L5.66406 13.4023C5.29688 13.0352 4.70312 13.0352 4.33984 13.4023C3.97656 13.7695 3.97266 14.3633 4.33984 14.7266L6.83984 17.2266H6.83594Z" fill="#5D87FF" />
                                                        </svg>
                                                    </div>
                                                    <div class="d-inline-block">
                                                        <h6 class="mb-1 fw-semibold bg-hover-primary">Rekap nilai ujian</h6>
                                                        <span class="fs-2 d-block text-dark">Pantau hasil ujian pada mata pelajaran yang anda miliki</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('teacher.journals.*') ? 'active' : '' }}"
                        href="{{ route('teacher.journals.index') }}" style="color: black !important;">
                        <span class="fw-normal">Jurnal</span>
                    </a>
                </li>

                @if ($permission_feedback ?? false)
                    <li class="nav-item">
                        <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('teacher.student-feedback.*') ? 'active' : '' }}"
                            href="{{ route('teacher.student-feedback.index') }}" style="color: black !important;">
                            <span class="fw-normal">Tanggapan Siswa</span>
                        </a>
                    </li>
                @endif

                @if (App\Models\Classroom::where('employee_id', auth()->user()->employee->id)->exists())
                    <li class="nav-item">
                        <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('teacher.list-student-class.index') ? 'active' : '' }}"
                            href="{{ route('teacher.list-student-class.index') }}" style="color: black !important;">
                            <span class="fw-normal">Daftar Siswa Dikelas</span>
                        </a>
                    </li>
                @endif
            @endrole

            @role('staff')
                <li class="nav-item">
                    <a class="nav-menu d-flex align-items-center py-3" href="{{ route('employee.dashboard') }}" style="color: black !important;">
                        <span class="fw-normal">Beranda</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-menu d-flex align-items-center py-3 {{ Route::is('employee.guestbook.index') ? 'active' : '' }}"
                        href="{{ route('employee.guestbook.index') }}" style="color: black !important;">
                        <span class="fw-normal">Buku Tamu</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('employee.journal.*') ? 'active' : '' }}"
                        href="{{ route('employee.journal.index') }}" style="color: black !important;">
                        <span class="fw-normal">Jurnal</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-menu d-flex align-items-center py-3" href="{{ route('employee.permission') }}" style="color: black !important;">
                        <span class="fw-normal">Perizinan</span>
                    </a>
                </li>
            @endrole

            @can('view_violation')
                <li class="nav-item">
                    <a class="nav-menu d-flex align-items-center py-3" href="{{ route('employee.violation.overview') }}" style="color: black !important;">
                        <span class="fw-normal">Overview</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-menu d-flex align-items-center py-3" href="/employee/rfid-student-violation" style="color: black !important;">
                        <span class="fw-normal">Tambah Pelanggaran</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('employee.violation.student-point.*') ? 'active' : '' }}"
                        href="{{ route('employee.violation.student-point.index') }}" style="color: black !important;">
                        <span class="fw-normal">Daftar Point Siswa</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-menu d-flex align-items-center py-3" href="{{ route('employee.violation.students') }}" style="color: black !important;">
                        <span class="fw-normal">Daftar Pelanggaran</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-menu d-flex align-items-center py-3" href="{{ route('employee.violation.student-repair.index') }}" style="color: black !important;">
                        <span class="fw-normal">Daftar Perbaikan</span>
                    </a>
                </li>
            @endcan
        </ul>

        {{-- PROFILE MOBILE --}}
        <div class="d-flex d-lg-none align-items-center ms-auto me-2">
            <div class="dropdown">
                <a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ auth()->user()->employee && auth()->user()->employee->image
                            ? asset('storage/' . auth()->user()->employee->image)
                            : asset('assets/images/default-user.jpeg') }}"
                        class="rounded-circle border" width="38" height="38"
                        style="object-fit: cover;">
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0 border-0 shadow"
                    style="width: 290px; border-radius: 16px; margin-top: 8px;">
                    <div class="p-4">
                        <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                            <img src="{{ auth()->user()->employee && auth()->user()->employee->image
                                    ? asset('storage/' . auth()->user()->employee->image)
                                    : asset('assets/images/default-user.jpeg') }}"
                                class="rounded-circle" width="52" height="52"
                                style="object-fit: cover; flex-shrink: 0;">
                            <div class="overflow-hidden">
                                <div class="fw-semibold text-truncate" style="font-size: 14px;">
                                    {{ auth()->user()->name }}
                                </div>
                                <small class="text-muted text-truncate d-block">
                                    {{ auth()->user()->email }}
                                </small>
                            </div>
                        </div>
                        <a href="{{ route('teacher.profile') }}"
                            class="btn btn-outline-primary w-100 rounded-pill mb-2">
                            Profil Saya
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- PROFILE DESKTOP --}}
        <div class="d-none d-lg-flex align-items-center ms-auto">
    <div class="dropdown">
        <a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false"
            class="d-flex align-items-center gap-2 text-decoration-none">
            <div class="text-end">
                <div class="fw-semibold" style="font-size: 0.9rem; line-height: 1.2; color: #333;">
                    {{ Str::limit(auth()->user()->name, 20) }}
                </div>
                <small class="text-muted d-block" style="font-size: 0.8rem; line-height: 1.2;">
                    {{ auth()->user()->email }}
                </small>
            </div>
            <img src="{{ auth()->user()->employee && auth()->user()->employee->image
                    ? asset('storage/' . auth()->user()->employee->image)
                    : asset('assets/images/default-user.jpeg') }}"
                class="rounded-circle border" width="40" height="40"
                style="object-fit: cover;">
        </a>

        {{-- Dropdown: lebar fixed, tidak overflow ke kanan --}}
        <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0"
            style="width: 320px; border-radius: 16px; margin-top: 8px; right: 0 !important; left: auto !important;">
            <div class="p-4">

                {{-- Header nama --}}
                <div class="mb-3 pb-3 border-bottom">
                    <h6 class="fw-semibold mb-0">{{ auth()->user()->name }}</h6>
                </div>

                {{-- Info profil --}}
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="{{ Auth::user()->employee && Auth::user()->employee->image
                            ? asset('storage/' . Auth::user()->employee->image)
                            : asset('assets/images/default-user.jpeg') }}"
                        class="rounded-circle" width="72" height="72"
                        style="object-fit: cover; flex-shrink: 0;" alt="" />
                    <div class="overflow-hidden">
                        @php
                            $roleName = match (auth()->user()->getRoleNames()->first()) {
                                'teacher' => 'Guru',
                                'student' => 'Siswa',
                                default => '-',
                            };
                        @endphp
                        <div class="fw-semibold">{{ $roleName }}</div>
                        <div class="d-flex align-items-center gap-1 text-muted small mt-1">
                            <i class="ti ti-mail" style="font-size: 13px;"></i>
                            <span class="text-truncate" style="max-width: 180px;">
                                {{ auth()->user()->email }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Tombol aksi --}}
                <a href="{{ route('teacher.profile') }}"
                    class="btn btn-outline-primary w-100 rounded-pill mb-2">
                    Profil Saya
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary w-100 rounded-pill">
                        Log Out
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

    </nav>
</header>