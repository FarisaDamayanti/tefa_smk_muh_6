<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="/" class="text-nowrap logo-img">
                <img src="{{ asset('assets/images/logo/logo-miscool.png') }}" width="150px" alt="">
            </a>
            <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8 text-muted"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
                <!-- ============================= -->
                <!-- Home -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <!-- =================== -->
                <!-- Dashboard -->
                <!-- =================== -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('school.index') }}" aria-expanded="false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-aperture">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M3.6 15h10.55" />
                                <path d="M6.551 4.938l3.26 10.034" />
                                <path d="M17.032 4.636l-8.535 6.201" />
                                <path d="M20.559 14.51l-8.535 -6.201" />
                                <path d="M12.257 20.916l3.261 -10.034" />
                            </svg>
                        </span>
                        <span class="hide-menu">Beranda</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Master</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('school.employees.index') || request()->routeIs('school.teacher.show') ? 'active' : '' }}"
                        href="{{ route('school.employees.index') }}" aria-expanded="false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 36 36">
                                <path fill="currentColor"
                                    d="M16.43 16.69a7 7 0 1 1 7-7a7 7 0 0 1-7 7m0-11.92a5 5 0 1 0 5 5a5 5 0 0 0-5-5M22 17.9a25.4 25.4 0 0 0-16.12 1.67a4.06 4.06 0 0 0-2.31 3.68v5.95a1 1 0 1 0 2 0v-5.95a2 2 0 0 1 1.16-1.86a22.9 22.9 0 0 1 9.7-2.11a23.6 23.6 0 0 1 5.57.66Zm.14 9.51h6.14v1.4h-6.14z" />
                                <path fill="currentColor"
                                    d="M33.17 21.47H28v2h4.17v8.37H18v-8.37h6.3v.42a1 1 0 0 0 2 0V20a1 1 0 0 0-2 0v1.47H17a1 1 0 0 0-1 1v10.37a1 1 0 0 0 1 1h16.17a1 1 0 0 0 1-1V22.47a1 1 0 0 0-1-1" />
                            </svg>
                        </span>
                        <span class="hide-menu">Pegawai</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('school.students.index') }}" aria-expanded="false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="1.5"
                                    d="M17.928 19.634h2.138a1.165 1.165 0 0 0 1.116-1.555a6.851 6.851 0 0 0-6.117-3.95m0-2.759a3.664 3.664 0 0 0 3.665-3.664a3.664 3.664 0 0 0-3.665-3.674m-1.04 16.795a1.908 1.908 0 0 0 1.537-3.035a8.026 8.026 0 0 0-6.222-3.196a8.026 8.026 0 0 0-6.222 3.197a1.909 1.909 0 0 0 1.536 3.034zM9.34 11.485a4.16 4.16 0 0 0 4.15-4.161a4.151 4.151 0 0 0-8.302 0a4.16 4.16 0 0 0 4.151 4.16" />
                            </svg>
                        </span>
                        <span class="hide-menu">Siswa</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('school.extracurricular.index') || request()->routeIs('school.extracurricular.show') ? 'active' : '' }}"
                        href="{{ route('school.extracurricular.index') }}" aria-expanded="false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="m20.65 9.375l-1.4-1.4L20 7.2L16.8 4l-.775.75L14.6 3.325l.75-.775q.575-.575 1.425-.563t1.425.588L21.425 5.8Q22 6.375 22 7.212t-.575 1.413zM8.65 21.4q-.575.575-1.412.575T5.825 21.4L2.6 18.175q-.575-.575-.575-1.412T2.6 15.35l.75-.75l1.425 1.425l-.775.75L7.225 20l.75-.775L9.4 20.65zm9.925-8.4L20 11.575L12.425 4L11 5.425zm-7 7L13 18.55L5.45 11L4 12.425zm-.15-5.85l2.75-2.725l-1.6-1.6l-2.725 2.75zM13 21.4q-.575.575-1.425.575T10.15 21.4L2.6 13.85q-.575-.575-.575-1.425T2.6 11l1.425-1.425Q4.6 9 5.437 9t1.413.575l1.575 1.575l2.75-2.75L9.6 6.85q-.575-.575-.575-1.425T9.6 4l1.425-1.425Q11.6 2 12.438 2t1.412.575l7.575 7.575q.575.575.575 1.412t-.575 1.413L20 14.4q-.575.575-1.425.575T17.15 14.4l-1.55-1.575l-2.75 2.75l1.575 1.575q.575.575.575 1.413t-.575 1.412z" />
                            </svg>
                        </span>
                        <span class="hide-menu">Ekstrakurikuler</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Pengaturan</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('school.school-years.index') }}" aria-expanded="false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="1.5"
                                    d="M15 4V2m0 2v2m0-2h-4.5M3 10v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-9zm0 0V6a2 2 0 0 1 2-2h2m0-2v4m14 4V6a2 2 0 0 0-2-2h-.5" />
                            </svg>
                        </span>
                        <span class="hide-menu">Tahun Ajaran</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('school.classroom.index') || request()->routeIs('school.class-student.index') ? 'active' : '' }}"
                        href="{{ route('school.classroom.index') }}" aria-expanded="false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                <path fill="currentColor"
                                    d="M232 212h-20V40a20 20 0 0 0-20-20H64a20 20 0 0 0-20 20v172H24a12 12 0 0 0 0 24h208a12 12 0 0 0 0-24M68 44h120v168H68Zm104 88a16 16 0 1 1-16-16a16 16 0 0 1 16 16" />
                            </svg>
                        </span>
                        <span class="hide-menu">Kelas</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <span class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 14 14">
                                <path fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 .5v6l3.59 4.57a1.5 1.5 0 0 1-1.18 2.43H2.59a1.5 1.5 0 0 1-1.18-2.43L5 6.5v-6M3.5.5h7" />
                            </svg>
                        </span>
                        <span class="hide-menu">Mata Pelajaran</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('school.subject.index') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Mata Pelajaran</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('school.lesson-hours.index') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Jam Pelajaran</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('school.lesson-schedule.index') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Jadwal Pelajaran</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <li class="sidebar-item">
                    <a class="sidebar-link" href="/attendance-test" aria-expanded="false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2"
                                    d="M9 12V8m6 4v-2m-3 2v-1M3 4h18M4 4v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4m-8 12v4m-3 0h6" />
                            </svg>
                        </span>
                        <span class="hide-menu">Test Absensi</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/attendance-test-teacher" aria-expanded="false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2"
                                    d="M9 12V8m6 4v-2m-3 2v-1M3 4h18M4 4v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4m-8 12v4m-3 0h6" />
                            </svg>
                        </span>
                        <span class="hide-menu">Test Absensi Guru</span>
                    </a>
                </li> --}}
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <span class="d-flex">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 1024 1024">
                                <path fill="currentColor"
                                    d="M600.704 64a32 32 0 0 1 30.464 22.208l35.2 109.376c14.784 7.232 28.928 15.36 42.432 24.512l112.384-24.192a32 32 0 0 1 34.432 15.36L944.32 364.8a32 32 0 0 1-4.032 37.504l-77.12 85.12a357 357 0 0 1 0 49.024l77.12 85.248a32 32 0 0 1 4.032 37.504l-88.704 153.6a32 32 0 0 1-34.432 15.296L708.8 803.904c-13.44 9.088-27.648 17.28-42.368 24.512l-35.264 109.376A32 32 0 0 1 600.704 960H423.296a32 32 0 0 1-30.464-22.208L357.696 828.48a352 352 0 0 1-42.56-24.64l-112.32 24.256a32 32 0 0 1-34.432-15.36L79.68 659.2a32 32 0 0 1 4.032-37.504l77.12-85.248a357 357 0 0 1 0-48.896l-77.12-85.248A32 32 0 0 1 79.68 364.8l88.704-153.6a32 32 0 0 1 34.432-15.296l112.32 24.256c13.568-9.152 27.776-17.408 42.56-24.64l35.2-109.312A32 32 0 0 1 423.232 64H600.64zm-23.424 64H446.72l-36.352 113.088l-24.512 11.968a294 294 0 0 0-34.816 20.096l-22.656 15.36l-116.224-25.088l-65.28 113.152l79.68 88.192l-1.92 27.136a293 293 0 0 0 0 40.192l1.92 27.136l-79.808 88.192l65.344 113.152l116.224-25.024l22.656 15.296a294 294 0 0 0 34.816 20.096l24.512 11.968L446.72 896h130.688l36.48-113.152l24.448-11.904a288 288 0 0 0 34.752-20.096l22.592-15.296l116.288 25.024l65.28-113.152l-79.744-88.192l1.92-27.136a293 293 0 0 0 0-40.256l-1.92-27.136l79.808-88.128l-65.344-113.152l-116.288 24.96l-22.592-15.232a288 288 0 0 0-34.752-20.096l-24.448-11.904L577.344 128zM512 320a192 192 0 1 1 0 384a192 192 0 0 1 0-384m0 64a128 128 0 1 0 0 256a128 128 0 0 0 0-256" />
                            </svg>
                        </span>
                        <span class="hide-menu">Setting</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('school.settings-information.index') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Informasi</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('school.rfid-active.index') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">RFID</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Kelulusan</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('school.graduations.index') ? 'active' : '' }}"
                        href="{{ route('school.graduations.index') }}" aria-expanded="false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 18" fill="none">
                                <path d="M0.979797 5.04257L9.6388 0.71357C9.72203 0.67202 9.81377 0.650391 9.9068 0.650391C9.99982 0.650391 10.0916 0.67202 10.1748 0.71357L18.8338 5.04357C18.9334 5.09343 19.0171 5.17003 19.0756 5.26478C19.1342 5.35953 19.1652 5.4687 19.1652 5.58007C19.1652 5.69144 19.1342 5.80061 19.0756 5.89536C19.0171 5.99011 18.9334 6.06671 18.8338 6.11657L10.1748 10.4456C10.0916 10.4871 9.99982 10.5088 9.9068 10.5088C9.81377 10.5088 9.72203 10.4871 9.6388 10.4456L0.979797 6.11557C0.880216 6.06571 0.796477 5.98911 0.737955 5.89436C0.679433 5.79961 0.648438 5.69044 0.648438 5.57907C0.648438 5.4677 0.679433 5.35853 0.737955 5.26378C0.796477 5.16903 0.880216 5.09243 0.979797 5.04257Z" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M20.9062 9.58008V6.08008L18.9062 5.08008M2.90625 7.08008V12.4921C2.90618 12.8705 3.01344 13.2411 3.21558 13.5609C3.41773 13.8808 3.70646 14.1368 4.04825 14.2991L9.04825 16.6731C9.31636 16.8004 9.60944 16.8665 9.90625 16.8665C10.2031 16.8665 10.4961 16.8004 10.7642 16.6731L15.7642 14.2991C16.106 14.1368 16.3948 13.8808 16.5969 13.5609C16.7991 13.2411 16.9063 12.8705 16.9062 12.4921V7.08008" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="hide-menu">Manajemen Kelulusan</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Ujian</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('school.question-bank.*') ? 'active' : '' }}" href="{{ route('school.question-bank.index') }}" aria-expanded="false">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_8206_7842)">
                                <path
                                    d="M16.875 7.35C16.525 7.35 16.25 7.075 16.25 6.725V1.875C16.25 1.525 15.975 1.25 15.625 1.25H4.375C4.025 1.25 3.75 1.525 3.75 1.875V4.375C3.75 4.725 3.475 5 3.125 5C2.775 5 2.5 4.725 2.5 4.375V1.875C2.5 0.8375 3.3375 0 4.375 0H15.625C16.6625 0 17.5 0.8375 17.5 1.875V6.725C17.5 7.075 17.225 7.35 16.875 7.35Z"
                                    fill="currentColor" />
                                <path
                                    d="M16.875 7.35C16.525 7.35 16.25 7.075 16.25 6.725V1.875C16.25 1.525 15.975 1.25 15.625 1.25H4.375C4.025 1.25 3.75 1.525 3.75 1.875V4.375C3.75 4.725 3.475 5 3.125 5C2.775 5 2.5 4.725 2.5 4.375V1.875C2.5 0.8375 3.3375 0 4.375 0H15.625C16.6625 0 17.5 0.8375 17.5 1.875V6.725C17.5 7.075 17.225 7.35 16.875 7.35Z"
                                    fill="currentColor" fill-opacity="0.2" />
                                <path
                                    d="M18.125 20H1.875C0.8375 20 0 19.1625 0 18.125V5.625C0 4.5875 0.8375 3.75 1.875 3.75H7.8125C8.0125 3.75 8.2 3.8375 8.3125 4L10 6.25H18.125C19.1625 6.25 20 7.0875 20 8.125V18.125C20 19.1625 19.1625 20 18.125 20ZM1.875 5C1.525 5 1.25 5.275 1.25 5.625V18.125C1.25 18.475 1.525 18.75 1.875 18.75H18.125C18.475 18.75 18.75 18.475 18.75 18.125V8.125C18.75 7.775 18.475 7.5 18.125 7.5H9.6875C9.59013 7.50204 9.49372 7.48034 9.40661 7.43679C9.31949 7.39323 9.24429 7.32912 9.1875 7.25L7.5 5H1.875Z"
                                    fill="currentColor" />
                                <path
                                    d="M18.125 20H1.875C0.8375 20 0 19.1625 0 18.125V5.625C0 4.5875 0.8375 3.75 1.875 3.75H7.8125C8.0125 3.75 8.2 3.8375 8.3125 4L10 6.25H18.125C19.1625 6.25 20 7.0875 20 8.125V18.125C20 19.1625 19.1625 20 18.125 20ZM1.875 5C1.525 5 1.25 5.275 1.25 5.625V18.125C1.25 18.475 1.525 18.75 1.875 18.75H18.125C18.475 18.75 18.75 18.475 18.75 18.125V8.125C18.75 7.775 18.475 7.5 18.125 7.5H9.6875C9.59013 7.50204 9.49372 7.48034 9.40661 7.43679C9.31949 7.39323 9.24429 7.32912 9.1875 7.25L7.5 5H1.875Z"
                                    fill="currentColor" fill-opacity="0.2" />
                                <path
                                    d="M6.875 16.25H4.375C4.025 16.25 3.75 15.975 3.75 15.625C3.75 15.275 4.025 15 4.375 15H6.875C7.225 15 7.5 15.275 7.5 15.625C7.5 15.975 7.225 16.25 6.875 16.25Z"
                                    fill="currentColor" />
                                <path
                                    d="M6.875 16.25H4.375C4.025 16.25 3.75 15.975 3.75 15.625C3.75 15.275 4.025 15 4.375 15H6.875C7.225 15 7.5 15.275 7.5 15.625C7.5 15.975 7.225 16.25 6.875 16.25Z"
                                    fill="currentColor" fill-opacity="0.2" />
                            </g>
                            <defs>
                                <clipPath id="clip0_8206_7842">
                                    <rect width="20" height="20" fill="currentColor" />
                                </clipPath>
                            </defs>
                        </svg>
                        <span class="hide-menu">Bank Soal</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('school.exams.*') ? 'active' : '' }}"href="{{ route('school.exams.index') }}"
                        aria-expanded="false">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_8170_7840)">
                                <path
                                    d="M11.0413 2.29167H13.1247C13.4562 2.29167 13.7741 2.42336 14.0086 2.65778C14.243 2.8922 14.3747 3.21015 14.3747 3.54167M10.8747 15.95L7.77467 16.3917L8.21634 13.3333L16.1747 5.38333C16.5301 5.05213 17.0002 4.87183 17.486 4.8804C17.9717 4.88897 18.4352 5.08575 18.7787 5.42928C19.1223 5.77281 19.319 6.23627 19.3276 6.72203C19.3362 7.20778 19.1559 7.6779 18.8247 8.03333L10.8747 15.95ZM4.58301 0.625H10.4163C10.4163 0.625 11.0413 0.625 11.0413 1.25V3.33333C11.0413 3.33333 11.0413 3.95833 10.4163 3.95833H4.58301C4.58301 3.95833 3.95801 3.95833 3.95801 3.33333V1.25C3.95801 1.25 3.95801 0.625 4.58301 0.625Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M14.375 15.6243V18.1243C14.375 18.4559 14.2433 18.7738 14.0089 19.0082C13.7745 19.2427 13.4565 19.3743 13.125 19.3743H1.875C1.54348 19.3743 1.22554 19.2427 0.991117 19.0082C0.756696 18.7738 0.625 18.4559 0.625 18.1243V3.54102C0.625 3.2095 0.756696 2.89155 0.991117 2.65713C1.22554 2.42271 1.54348 2.29102 1.875 2.29102H3.95833M4.375 7.29102H10.2083M4.375 11.041H6.45833"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_8170_7840">
                                    <rect width="20" height="20" fill="currentColor" />
                                </clipPath>
                            </defs>
                        </svg>
                        <span class="hide-menu">Daftar Ujian</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Absensi</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('school.clock-settings.index') }}" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2">
                                <path
                                    d="M21 12a9 9 0 1 0-9.002 9m5.003-2a2 2 0 1 0 4 0a2 2 0 1 0-4 0m2-3.5V17m0 4v1.5m3.031-5.25l-1.299.75m-3.463 2l-1.3.75m0-3.5l1.3.75m3.463 2l1.3.75" />
                                <path d="M12 7v5l2 2" />
                            </g>
                        </svg>
                        <span class="hide-menu">Pengaturan Jam</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow {{ request()->routeIs('school.detail-presence-class.index') ? 'active' : '' }}"
                        href="javascript:void(0)" aria-expanded="false">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M9 17H7v-7h2zm4 0h-2V7h2zm4 0h-2v-4h2zm2 2H5V5h14zm0-16H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2" />
                            </svg>
                        </span>
                        <span class="hide-menu">Statistik Absensi</span>
                    </a>
                    <ul aria-expanded="false"
                        class="collapse first-level {{ request()->routeIs('school.detail-presence-class.index') ? 'in' : '' }}">
                        <li class="sidebar-item">
                            <a href="{{ route('school.statistic-presence.index') }}"
                                class="sidebar-link {{ request()->routeIs('school.detail-presence-class.index') ? 'active' : '' }}">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Siswa</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('school.statistic-presence-employee.index') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Guru</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Jurnal</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('school.journals.show') || request()->routeIs('school.journals.detail') || request()->routeIs('school.export-journal.index') ? 'active' : '' }}"
                        href="{{ route('school.journals.detail') }}" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16">
                            <g fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083L6.757 8.43A.5.5 0 0 1 6 8" />
                                <path
                                    d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                                <path
                                    d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                            </g>
                        </svg>
                        <span class="hide-menu">Jurnal Guru</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('school.employee-journal.show') || request()->routeIs('school.employee-journal.export') ? 'active' : '' }}"
                        href="{{ route('school.employee-journal.show') }}" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 16 16">
                            <g fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083L6.757 8.43A.5.5 0 0 1 6 8" />
                                <path
                                    d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2" />
                                <path
                                    d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z" />
                            </g>
                        </svg>
                        <span class="hide-menu">Jurnal Staff</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('school.feedback') }}" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                            <path fill="currentColor"
                                d="M11.5 1A1.5 1.5 0 0 0 10 2.5v5A1.5 1.5 0 0 0 11.5 9h6A1.5 1.5 0 0 0 19 7.5v-5A1.5 1.5 0 0 0 17.5 1zm1 5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1 0-1M12 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5M4.6 3H9v1H4.6C3.704 4 3 4.713 3 5.566v6.71c0 .853.704 1.566 1.6 1.566h1.6V17h.003l.002-.001l4.276-3.157H15.4c.896 0 1.6-.713 1.6-1.566V10h.5q.257 0 .5-.05v2.326c0 1.418-1.164 2.566-2.6 2.566h-4.59l-4.011 2.961a1.01 1.01 0 0 1-1.4-.199a.98.98 0 0 1-.199-.59v-2.172h-.6c-1.436 0-2.6-1.149-2.6-2.566v-6.71C2 4.149 3.164 3 4.6 3" />
                        </svg>
                        <span class="hide-menu">Tanggapan Siswa</span>
                    </a>
                </li>

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Pelanggaran</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('school.access-violation.index') }}"
                        aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M5.8 10C5.4 8.8 4.3 8 3 8c-1.7 0-3 1.3-3 3s1.3 3 3 3c1.3 0 2.4-.8 2.8-2H7v2h2v-2h2v-2zM3 12c-.6 0-1-.4-1-1s.4-1 1-1s1 .4 1 1s-.4 1-1 1m13-8c-2.2 0-4 1.8-4 4s1.8 4 4 4s4-1.8 4-4s-1.8-4-4-4m0 6.1c-1.2 0-2.1-.9-2.1-2.1s.9-2.1 2.1-2.1s2.1.9 2.1 2.1s-.9 2.1-2.1 2.1m0 2.9c-2.7 0-8 1.3-8 4v3h16v-3c0-2.7-5.3-4-8-4m6.1 5.1H9.9V17c0-.6 3.1-2.1 6.1-2.1s6.1 1.5 6.1 2.1z" />
                        </svg>
                        <span class="hide-menu">Akses Pelanggaran</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->routeIs('school.violation.index') ? 'active' : '' }}"
                        href="{{ route('school.violation.index') }}" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                            <path fill="currentColor"
                                d="M240.26 186.1L152.81 34.23a28.74 28.74 0 0 0-49.62 0L15.74 186.1a27.45 27.45 0 0 0 0 27.71A28.31 28.31 0 0 0 40.55 228h174.9a28.31 28.31 0 0 0 24.79-14.19a27.45 27.45 0 0 0 .02-27.71m-20.8 15.7a4.46 4.46 0 0 1-4 2.2H40.55a4.46 4.46 0 0 1-4-2.2a3.56 3.56 0 0 1 0-3.73L124 46.2a4.77 4.77 0 0 1 8 0l87.44 151.87a3.56 3.56 0 0 1 .02 3.73M116 136v-32a12 12 0 0 1 24 0v32a12 12 0 0 1-24 0m28 40a16 16 0 1 1-16-16a16 16 0 0 1 16 16" />
                        </svg>
                        <span class="hide-menu">Pelanggaran</span>
                    </a>
                </li>

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Buku tamu</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ Route::is('school.guest-book.index') ? 'active' : '' }}"
                        href="{{ route('school.guest-book.index') }}" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M5.8 10C5.4 8.8 4.3 8 3 8c-1.7 0-3 1.3-3 3s1.3 3 3 3c1.3 0 2.4-.8 2.8-2H7v2h2v-2h2v-2zM3 12c-.6 0-1-.4-1-1s.4-1 1-1s1 .4 1 1s-.4 1-1 1m13-8c-2.2 0-4 1.8-4 4s1.8 4 4 4s4-1.8 4-4s-1.8-4-4-4m0 6.1c-1.2 0-2.1-.9-2.1-2.1s.9-2.1 2.1-2.1s2.1.9 2.1 2.1s-.9 2.1-2.1 2.1m0 2.9c-2.7 0-8 1.3-8 4v3h16v-3c0-2.7-5.3-4-8-4m6.1 5.1H9.9V17c0-.6 3.1-2.1 6.1-2.1s6.1 1.5 6.1 2.1z" />
                        </svg>
                        <span class="hide-menu">Buku Tamu</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <!-- End Sidebar scroll-->
</aside>
