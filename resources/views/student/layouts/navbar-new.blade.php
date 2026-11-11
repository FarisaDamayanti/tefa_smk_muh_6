 <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileSidebar">

    <div class="offcanvas-header border-bottom">
        <img src="{{ asset('assets/images/logo/logo-miscool.png') }}" width="140">
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        @include('student.layouts.sidebar-content')
    </div>

</div>

<header class="app-header w-100">
    <nav class="navbar navbar-expand-lg navbar-light position-relative">
        <ul class="navbar-nav d-flex flex-row mb-0 pb-0 border-0 align-items-center">
            <!-- Toggler d-lg-none -->
            <button class="navbar-toggler border-0 d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar" aria-controls="mobileSidebar" aria-label="Toggle navigation">
                <i class="ti ti-menu-2"></i>
            </button>
            <!-- Logo untuk desktop -->
            <a class="navbar-brand d-none d-lg-block me-4" href="/">
                <img src="{{ asset('assets/images/logo/logo-miscool.png') }}" width="150" alt="Mischol Logo" style="z-index: -1;">
            </a>
        </ul>

        <!-- Logo untuk mobile (centered) -->
        <a class="navbar-brand d-lg-none position-absolute top-50 start-50 translate-middle m-0" href="/">
            <img src="{{ asset('assets/images/logo/logo-miscool.png') }}" width="120" alt="Mischol Logo" style="z-index: -1;">
        </a>
        <ul class="navbar-nav quick-links d-none d-lg-flex gap-3 align-items-center">
            <li class="nav-item">
                <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
                    href="{{ route('student.dashboard') }}" style="color: black !important;">
                    <span class="fw-normal">Beranda</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('student.attendance') ? 'active' : '' }}"
                    href="{{ route('student.attendance') }}" style="color: black !important;">
                    <span class="fw-normal">Absensi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('student.feedback.*') ? 'active' : '' }}"
                    href="{{ route('student.feedback.index') }}" style="color: black !important;">
                    <span class="fw-normal">Kelas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('student.e-learning.*') ? 'active' : '' }}"
                        href="{{ route('student.e-learning.index') }}" style="color: black !important;">
                    <span class="fw-normal">E-Learning</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('student.exams.*') ? 'active' : '' }}"
                        href="{{ route('student.exams.index') }}" style="color: black !important;">
                    <span class="fw-normal">Ujian</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('student.violations') ? 'active' : '' }}"
                    href="{{ route('student.violations') }}" style="color: black !important;">
                    <span class="fw-normal">Pelanggaran</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('student.repairs.*') ? 'active' : '' }}"
                    href="{{ route('student.repairs.index') }}" style="color: black !important;">
                    <span class="fw-normal">Perbaikan</span>
                </a>
            </li>
            @if(auth_student()->graduationDetails->isNotEmpty())
                <li class="nav-item">
                    <a class="nav-menu d-flex align-items-center py-3 {{ request()->routeIs('student.graduations.*') ? 'active' : '' }}"
                        href="{{ route('student.graduations.index') }}" style="color: linear !important;">
                        <span class="fw-normal"><b>Pengumuman Kelulusan</b></span>
                    </a>
                </li>
            @endif
        </ul>
        <div class="d-flex align-items-center justify-content-end ms-auto" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center border-0 pb-0">
                    <li class="nav-item dropdown m-0">
                        <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="me-2 text-end d-none d-lg-block">
                                    <div class="fw-semibold" style="font-size: 0.9rem; line-height: 1.2;">
                                        {{ Str::limit(auth()->user()->name, 20) }}
                                    </div>
                                    <small class="text-muted d-block" style="font-size: 0.8rem; line-height: 1.2;">
                                        {{ auth()->user()->email }}
                                    </small>
                                </div>
                                <div class="user-profile-img">
                                    <img src="{{ auth()->user()->student->image ? asset('storage/' . auth()->user()->student->image) : asset('assets/images/default-user.jpeg') }}"
                                        class="rounded-circle border" width="40" height="40" style="object-fit: cover;">
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                            aria-labelledby="drop1">
                            <div class="profile-dropdown position-relative" data-simplebar>
                                <div class="py-3 px-7 pb-0">
                                    <h5 class="mb-0 fs-5 fw-semibold">Profil Saya</h5>
                                </div>

                                @php
                                    $roleName = match (auth()->user()->getRoleNames()->first()) {
                                        'teacher' => 'Guru',
                                        'student' => 'Siswa',
                                    };
                                @endphp

                                <div class="d-flex align-items-center py-9 mx-7 gap-3 border-bottom">
                                    <img src="{{ Auth::user()->student->image ? asset('storage/' . Auth::user()->student->image) : asset('assets/images/default-user.jpeg') }}" class="rounded-circle"
                                        width="60" height="60" alt="" />
                                    <div>
                                        <div class="fw-semibold">{{ Str::limit(auth()->user()->name, 20) }}</div>

                                        <div class="d-flex align-items-center gap-2 text-muted">
                                            <i class="ti ti-mail"></i>
                                            <span>{{ auth()->user()->email }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid py-4 px-7 pt-8">
                                    <a href="{{ route('student.profile.index') }}" class="mb-3 btn btn-primary w-100 rounded-pill">
                                        Profil Saya
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary w-100 rounded-pill">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
        </div>
    </nav>
</header>
