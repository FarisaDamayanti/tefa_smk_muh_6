@extends('student.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/e-learning.css') }}">
@endsection

@section('content')
    <div class="student-subjects-page">
        <div class="card bg-primary shadow-none position-relative overflow-hidden text-light mb-4">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-8 col-md-9">
                        <h4 class="fw-semibold mb-2 text-light">Selamat Datang di kelas XII RPL 1</h4>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item text-light">
                                Ikuti aktivitas kelas yang meliputi diskusi melalui forum, penugasan, serta penilaian.
                            </li>
                        </ol>
                    </div>
                    <div class="col-4 col-md-3 text-center mb-n5">
                        <img src="{{ asset('admin_assets/dist/images/breadcrumb/ChatBc.png') }}" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-2">Daftar Pertemuan</h5>
        <div class="mm-toolbar mb-3">
            <div class="mm-toolbar__left">
                <input type="text" class="form-control mm-input-sm mm-w-search" placeholder="Cari....">
                <div class="position-relative mm-w-date">
                    <input type="date" class="form-control mm-input-sm">
                </div>
                <div class="position-relative mm-w-semester">
                    <select class="form-select mm-input-sm mm-select-clean">
                        <option value="" selected>Semester</option>
                        <option>Semester 1</option>
                        <option>Semester 2</option>
                    </select>
                    <span class="mm-right-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M4 7h16M7 12h10M10 17h4" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                        </svg>
                    </span>
                </div>

                <button class="btn btn-primary mm-btn-sm">
                    <svg width="13" height="14" viewBox="0 0 13 14" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0.75 0.75H11.75V2.28318C11.7499 2.65757 11.605 3.0166 11.3471 3.28129L8.3125 6.39706V11.3382L4.1875 12.75V6.75L1.1075 3.27141C0.877495 3.0116 0.750037 2.6731 0.75 2.322V0.75Z"
                            stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Filter
                </button>

                <button class="btn btn-outline-primary mm-btn-sm">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12.75 5.99882C12.5666 4.67646 11.9543 3.4512 11.0075 2.51178C10.0607 1.57236 8.83182 0.970904 7.51025 0.800053C6.18869 0.629203 4.84772 0.898438 3.6939 1.56629C2.54009 2.23414 1.63744 3.26355 1.125 4.49595M0.75 1.49021V4.49595H3.75M0.75 7.50118C0.933419 8.82354 1.5457 10.0488 2.49252 10.9882C3.43934 11.9276 4.66818 12.5291 5.98975 12.6999C7.31131 12.8708 8.65228 12.6016 9.8061 11.9337C10.9599 11.2659 11.8626 10.2365 12.375 9.00405M12.75 12.0098V9.00405H9.75"
                            stroke="#5D87FF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Reset
                </button>
            </div>

            <a href="javascript:history.back()" class="btn btn-warning text-white mm-btn-back" style="border:0;">
                Kembali
            </a>
        </div>

        @php
            // DUMMY DATA PERTEMUAN
            $meetings = collect([
                [
                    'title' => 'Pertemuan 1',
                    'date' => '12 Desember 2025',
                    'topic' => 'Menengo Tang',
                    'desc' =>
                        'Lorem ipsum dolor sit amet, consectetur adipisicing elit. tahu tempe tahu tempe telur nasi nasgor goreng esteh anget dingin tahu tempe tahu tempe telur nasi nasgor goreng esteh anget dingintahu tempe tahu tempe telur nasi nasgor goreng esteh anget dingintahu tempe tahu tempe telur nasi nasgor goreng esteh anget dingin',
                    'items' => [
                        [
                            'type' => 'task',
                            'title' => 'Tugas: Menentukan tema aplikasi dan membuat logo',
                            'deadline' => '12 Desember 2025',
                        ],
                        [
                            'type' => 'task',
                            'title' => 'Tugas: Membuat wireframe halaman utama',
                            'deadline' => '13 Desember 2025',
                        ],
                        ['type' => 'material', 'title' => 'Materi: Business Requirements Document (BRD)'],
                        ['type' => 'material', 'title' => 'Materi: Pengenalan Laravel'],
                    ],
                    'approved' => false,
                ],
                [
                    'title' => 'Pertemuan 2',
                    'date' => '-',
                    'topic' => '-',
                    'desc' => '-',
                    'items' => [
                        ['type' => 'task', 'title' => 'Tugas: Membuat ERD database', 'deadline' => '15 Desember 2025'],
                        ['type' => 'task', 'title' => 'Tugas: Implementasi login', 'deadline' => '16 Desember 2025'],
                        ['type' => 'material', 'title' => 'Materi: Database Design'],
                        ['type' => 'material', 'title' => 'Materi: REST API Dasar'],
                    ],
                    'approved' => true, // contoh untuk stamp approved
                ],
                [
                    'title' => 'Pertemuan 3',
                    'date' => '18 Desember 2025',
                    'topic' => 'Pengenalan Project',
                    'desc' => 'Pembahasan alur kerja, pembagian tugas, dan timeline pengumpulan.',
                    'items' => [
                        [
                            'type' => 'task',
                            'title' => 'Tugas: Setup repository & branch',
                            'deadline' => '19 Desember 2025',
                        ],
                        [
                            'type' => 'task',
                            'title' => 'Tugas: Buat halaman dashboard',
                            'deadline' => '20 Desember 2025',
                        ],
                        ['type' => 'material', 'title' => 'Materi: Git Workflow'],
                        ['type' => 'material', 'title' => 'Materi: UI Guideline'],
                    ],
                    'approved' => false,
                ],
            ]);
        @endphp

        {{-- Loop Pertemuan --}}
        @foreach ($meetings as $m)
            <div class="card meet-card position-relative">
                <div class="meet-card__head">{{ $m['title'] }}</div>

                <div class="meet-card__body">
                    <div class="meet-box">
                        <div class="meet-meta"><b>Tanggal Pertemuan</b> : {{ $m['date'] }}</div>
                        <div class="meet-meta"><b>Judul Topik</b> : {{ $m['topic'] }}</div>
                        <div class="meet-meta mb-1"><b>Deskripsi</b> :</div>
                        <p class="meet-desc">{{ $m['desc'] }}</p>
                    </div>

                    {{-- Loop Items: Tugas/Materi --}}
                    @foreach ($m['items'] as $it)
                        <div class="meet-item">

                            {{-- icon kiri --}}
                            <div class="meet-item__icon">
                                @if ($it['type'] === 'task')
                                    {{-- icon tugas --}}
                                    <svg width="20" height="20" viewBox="0 0 33 33" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.39262 29.9222H6.23557C4.97962 29.9222 3.77511 29.4231 2.88702 28.5347C1.99892 27.6464 1.5 26.4415 1.5 25.1851V23.6061H10.1819M23.5993 10.1846V4.65802C23.5993 4.03342 23.7845 3.42285 24.1314 2.90352C24.4783 2.38419 24.9713 1.97941 25.5482 1.74039C26.1251 1.50137 26.7599 1.43883 27.3723 1.56068C27.9847 1.68253 28.5472 1.98331 28.9887 2.42496C29.4303 2.86662 29.7309 3.42933 29.8528 4.04192C29.9746 4.65452 29.912 5.28949 29.6731 5.86654C29.4341 6.4436 29.0295 6.93681 28.5103 7.28382C27.9912 7.63083 27.3808 7.81604 26.7564 7.81604H23.5993M26.7564 1.5H9.39262C8.13666 1.5 6.93215 1.99908 6.04406 2.88744C5.15597 3.77581 4.65705 4.98069 4.65705 6.23703V23.6061M10.9711 7.81604H17.2852M10.9711 14.1321H17.2852M25.8408 16.6731C26.4625 16.0512 27.3057 15.7018 28.1849 15.7018C29.0641 15.7018 29.9073 16.0512 30.529 16.6731C31.1507 17.295 31.5 18.1384 31.5 19.0179C31.5 19.8974 31.1507 20.7409 30.529 21.3628L20.4423 31.5H15.7067V26.763L25.8408 16.6731Z"
                                            stroke="white" stroke-width="3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                @else
                                    {{-- icon materi --}}
                                    <svg width="20" height="20" viewBox="0 0 29 29" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.66667 2.66667C7.66667 2.22464 7.49107 1.80072 7.17851 1.48816C6.86595 1.1756 6.44203 1 6 1H2.66667C2.22464 1 1.80072 1.1756 1.48816 1.48816C1.1756 1.80072 1 2.22464 1 2.66667V26C1 26.442 1.1756 26.866 1.48816 27.1785C1.80072 27.4911 2.22464 27.6667 2.66667 27.6667H6C6.44203 27.6667 6.86595 27.4911 7.17851 27.1785C7.49107 26.866 7.66667 26.442 7.66667 26M7.66667 2.66667V26M7.66667 2.66667C7.66667 2.22464 7.84226 1.80072 8.15482 1.48816C8.46738 1.1756 8.89131 1 9.33333 1H12.6667C13.1087 1 13.5326 1.1756 13.8452 1.48816C14.1577 1.80072 14.3333 2.22464 14.3333 2.66667V26C14.3333 26.442 14.1577 26.866 13.8452 27.1785C13.5326 27.4911 13.1087 27.6667 12.6667 27.6667H9.33333C8.89131 27.6667 8.46738 27.4911 8.15482 27.1785C7.84226 26.866 7.66667 26.442 7.66667 26M1 7.66667H7.66667M7.66667 21H14.3333M16 9.33333L22.6667 7.66667M19.3333 20.9992L25.8717 19.3659M15.6685 1.93422L19.3085 1.05088C20.2452 0.825885 21.1969 1.36755 21.4452 2.27088L27.6035 24.6342C27.7122 25.0436 27.6641 25.4789 27.4686 25.8547C27.2732 26.2305 26.9445 26.5198 26.5469 26.6659L26.3252 26.7342L22.6852 27.6175C21.7485 27.8425 20.7969 27.3009 20.5485 26.3976L14.3902 4.03422C14.2816 3.6248 14.3297 3.18957 14.5251 2.81377C14.7206 2.43797 15.0493 2.14868 15.4469 2.00255L15.6685 1.93422Z"
                                            stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                @endif
                            </div>

                            <div>
                                <p class="meet-item__title">{{ $it['title'] }}</p>

                                @if ($it['type'] === 'task')
                                    <p class="meet-item__sub">Deadline : {{ $it['deadline'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>
@endsection
