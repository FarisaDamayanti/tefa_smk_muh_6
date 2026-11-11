@extends('student.layouts.app')

@section('style')
<link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/teacher-meeting-detail.css') }}">
<link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection

@section('content')
<div class="mt-3">
    {{-- HEADER DENGAN DATA REAL --}}
    <x-authenticated.breadcrumb title="Selamat Datang di Kelas {{ $classroom->levelClass->name }} {{ $classroom->name }} {{ $subject->name }}" breadcrumb="Ikuti Aktivitas Kelas yang meliputi diskusi melalui forum, penugasan, serta penilaian." />

    <div class="">
        <div class="card shadow-sm position-relative overflow-hidden rounded-4">

            <!-- SVG Kiri Bawah -->
            <div class="position-absolute bottom-0 start-0">
                <svg width="109" height="113" viewBox="0 0 109 113" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="49.0625" cy="49.0625" r="49.0625" transform="matrix(0 1 1 0 -48 15.7)" fill="#5D87FF" fill-opacity="0.26" />
                    <circle cx="49.0625" cy="49.0625" r="49.0625" transform="matrix(0 1 1 0 10.875 72.6125)" fill="#5D87FF" fill-opacity="0.26" />
                    <circle cx="49.0625" cy="49.0625" r="49.0625" transform="matrix(0 1 1 0 10.875 0)" fill="#5D87FF" fill-opacity="0.26" />
                </svg>

            </div>

            <!-- SVG Kanan Atas -->
            <div class="position-absolute top-0 end-0">
                <svg width="120" height="101" viewBox="0 0 120 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="64.7617" cy="-6.9375" r="49.0625" fill="#5D87FF" fill-opacity="0.26" />
                    <circle cx="121.676" cy="51.9375" r="49.0625" fill="#5D87FF" fill-opacity="0.26" />
                    <circle cx="49.0625" cy="51.9375" r="49.0625" fill="#5D87FF" fill-opacity="0.26" />
                </svg>
            </div>

            <!-- Konten -->
            <div class="card-body p-4">
                <div class="d-flex flex-sm-row flex-column align-items-center gap-3">
                    <img src="{{ $teacher?->image && Storage::exists($teacher?->image) ? asset('storage/' . $teacher?->image) : asset('assets/images/default-user.jpeg') }}" class="rounded-circle ms-0 ms-sm-5 border" width="100" height="100" alt="avatar" style="z-index: 2;">
                    <div class="d-flex flex-column align-items-sm-start align-items-center">
                        <span class="badge bg-primary-subtle text-primary mb-1">
                            {{ $teacher?->user?->email ?? 'Email tidak tersedia' }}
                        </span>
                        <h5 class="fw-bold mb-1">
                            {{ $teacher?->user?->name ?? 'Nama guru tidak tersedia' }}
                        </h5>
                        <p class="text-muted mb-0">
                            {{ $teacher?->phone_number ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <h4 class="fw-bolder">Daftar Topik</h4>
    <div class="row my-4">
        <div class="col-lg-7 col-12">
            <form class="d-flex align-items-center gap-2 flex-nowrap" style="height: 40px;">
                <input type="text" name="search" class="form-control fs-3 ps-3 h-100" placeholder="Cari..." id="search" value="{{ request()->search ?? '' }}">

                <select class="form-select d-none d-md-block h-100" id="semester" name="semester">
                    <option value="ganjil" {{ request()->semester == 'ganjil' ? 'selected' : '' }}>
                        Ganjil</option>
                    <option value="genap" {{ request()->semester == 'genap' ? 'selected' : '' }}>
                        Genap</option>
                </select>

                <div class="d-md-block d-none h-100">
                    <button type="submit" class="btn btn-primary h-100" id="filter-btn">
                        <i class="ti ti-filter"></i>
                        <span class="d-none ms-2 d-md-block">Filter</span>
                    </button>
                </div>

                <div class="d-md-none d-block h-100">
                    <button type="button" class="btn btn-primary rounded-2 h-100" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="ti ti-filter"></i>
                    </button>
                </div>

                <button type="button" class="btn btn-outline-secondary h-100" id="reset-btn">
                    <i class="ti ti-refresh fs-4"></i>
                    <span class="d-none ms-2 d-md-block">Reset</span>
                </button>

            </form>
        </div>
        <div class="col-lg-5 col-12 text-end">
            <a href="{{ route('student.e-learning.index') }}" class="btn btn-warning h-100">
                Kembali
            </a>
        </div>
    </div>

    {{-- LIST TOPIK (PAKAI DATA DARI CONTROLLER) --}}
    <div class="meeting-list">
        @forelse ($topics as $topic)
        <div class="card meeting-card mb-4">
            <div class="meeting-header d-flex justify-content-between align-items-center p-4" style="cursor: pointer;">
                <h4 class="mb-0 text-white fw-bolder">Topik {{ $topic->topic_number }}</h4>
                <i class="fa fa-chevron-down toggle-icon fs-5"
                        id="icon-topic{{ $topic->topic_number }}"
                        style="cursor: pointer; color: white;"></i>
            </div>

            <div class="collapse-topic" id="topic{{ $topic->topic_number }}">
                <div class="meeting-body">
                    {{-- INFO --}}
                    <div class="meeting-info mb-3 d-flex align-items-start">
                        <div class="flex-grow-1">
                            <p class="mb-1"><strong>Judul Topik :</strong> {{ $topic->title }}</p>
                            <p class="mb-0 text-bold meeting-desc">
                                <strong>Deskripsi :</strong><br>
                                {{ $topic->description }}
                            </p>
                        </div>
                    </div>

                    {{-- TUGAS --}}
                    <div class="mt-3">
                        <h6 class="fw-bold mb-2">Tugas</h6>
                        <div class="task-wrap">
                            @forelse ($topic->tasks as $task)
                            <a href="{{ url('/student/task-detail') }}/{{ $task->id }}" class="text-decoration-none">
                                <div class="meeting-item task-item clickable-task">
                                    <div class="item-left">
                                        <div class="icon-box me-3 bg-primary text-white">
                                            <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.39262 29.9222H6.23557C4.97962 29.9222 3.77511 29.4231 2.88702 28.5347C1.99892 27.6464 1.5 26.4415 1.5 25.1851V23.6061H10.1819M23.5993 10.1846V4.65802C23.5993 4.03342 23.7845 3.42285 24.1314 2.90352C24.4783 2.38419 24.9713 1.97941 25.5482 1.74039C26.1251 1.50137 26.7599 1.43883 27.3723 1.56068C27.9847 1.68253 28.5472 1.98331 28.9887 2.42496C29.4303 2.86662 29.7309 3.42933 29.8528 4.04192C29.9746 4.65452 29.912 5.28949 29.6731 5.86654C29.4341 6.4436 29.0295 6.93681 28.5103 7.28382C27.9912 7.63083 27.3808 7.81604 26.7564 7.81604H23.5993M26.7564 1.5H9.39262C8.13666 1.5 6.93215 1.99908 6.04406 2.88744C5.15597 3.77581 4.65705 4.98069 4.65705 6.23703V23.6061M10.9711 7.81604H17.2852M10.9711 14.1321H17.2852M25.8408 16.6731C26.4625 16.0512 27.3057 15.7018 28.1849 15.7018C29.0641 15.7018 29.9073 16.0512 30.529 16.6731C31.1507 17.295 31.5 18.1384 31.5 19.0179C31.5 19.8974 31.1507 20.7409 30.529 21.3628L20.4423 31.5H15.7067V26.763L25.8408 16.6731Z" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-medium text-dark">{{ $task->title }}</p>
                                            <small class="text-muted meeting-desc">
                                                Deadline:
                                                {{ Carbon\Carbon::parse($task->deadline)->format('d F Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @empty
                            <p class="text-muted">Belum ada tugas</p>
                            @endforelse
                        </div>
                    </div>
                    {{-- MATERI --}}
                    <div class="mt-3">
                        <h6 class="fw-bold mb-2">Materi</h6>
                        <div class="material-wrap">
                            @forelse ($topic->modules as $module)
                            <a href="{{ route('student.material.detail', $module->id) }}" class="text-decoration-none">
                                <div class="meeting-item material-item clickable-material">
                                    <div class="item-left">
                                        <div class="icon-box me-3 bg-primary text-white">
                                            <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.66667 2.66667C7.66667 2.22464 7.49107 1.80072 7.17851 1.48816C6.86595 1.1756 6.44203 1 6 1H2.66667C2.22464 1 1.80072 1.1756 1.48816 1.48816C1.1756 1.80072 1 2.22464 1 2.66667V26C1 26.442 1.1756 26.866 1.48816 27.1785C1.80072 27.4911 2.22464 27.6667 2.66667 27.6667H6C6.44203 27.6667 6.86595 27.4911 7.17851 27.1785C7.49107 26.866 7.66667 26.442 7.66667 26M7.66667 2.66667V26M7.66667 2.66667C7.66667 2.22464 7.84226 1.80072 8.15482 1.48816C8.46738 1.1756 8.89131 1 9.33333 1H12.6667C13.1087 1 13.5326 1.1756 13.8452 1.48816C14.1577 1.80072 14.3333 2.22464 14.3333 2.66667V26C14.3333 26.442 14.1577 26.866 13.8452 27.1785C13.5326 27.4911 13.1087 27.6667 12.6667 27.6667H9.33333C8.89131 27.6667 8.46738 27.4911 8.15482 27.1785C7.84226 26.866 7.66667 26.442 7.66667 26M1 7.66667H7.66667M7.66667 21H14.3333M16 9.33333L22.6667 7.66667M19.3333 20.9992L25.8717 19.3659M15.6685 1.93422L19.3085 1.05088C20.2452 0.825885 21.1969 1.36755 21.4452 2.27088L27.6035 24.6342C27.7122 25.0436 27.6641 25.4789 27.4686 25.8547C27.2732 26.2305 26.9445 26.5198 26.5469 26.6659L26.3252 26.7342L22.6852 27.6175C21.7485 27.8425 20.7969 27.3009 20.5485 26.3976L14.3902 4.03422C14.2816 3.6248 14.3297 3.18957 14.5251 2.81377C14.7206 2.43797 15.0493 2.14868 15.4469 2.00255L15.6685 1.93422Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="mb-0 fw-medium text-dark">{{ $module->title }}</p>
                                            @if ($module->type)
                                            <small class="text-muted meeting-desc">
                                                {{ ucfirst($module->type) }}
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @empty
                            <p class="text-muted">Belum ada materi</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- KOSONG --}}
                    @if ((!$topic->tasks || $topic->tasks->count() === 0) && (!$topic->modules || $topic->modules->count() === 0))
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">Belum ada tugas dan materi pada topik ini</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="meeting-empty">
            <div class="meeting-empty__inner">
                @if (request()->has('search') || (request()->has('semester') && request('semester') != $currentSemester))
                <img class="meeting-empty__img" src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt="Tidak ada data" style="width:110rem">
                <div class="meeting-empty__title">Belum Ada Data</div>
                <p>
                    Silahkan Komunikasikan Dengan Guru Anda!
                </p>
                @else
                <img class="meeting-empty__img" src="{{ asset('admin_assets/dist/images/empty/nodata.png') }}" alt="Belum ada topik">
                <div class="meeting-empty__title">Ups! Belum ada Topik Saat Ini.</div>
                <p>
                    Silahkan Komunikasikan Dengan Guru Anda!
                </p>
                @endif
            </div>
        </div>
        @endforelse
    </div>
</div>

<x-dynamic-modal-filter
    :title="'Filter Topik'"
    :filters="[
        'semester' => [
            'type' => 'select',
            'id' => 'semester-modal',
            'options' => ['Ganjil' => 'ganjil', 'Genap' => 'genap'],
            'label' => 'Semester',
        ],
    ]"
    :hiddens="[
        'search' => 'search-modal',
    ]"
/>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        const headers = $('.meeting-header');
        const collapses = $('.collapse-topic');

        function closeAll() {
            collapses.removeClass('show');

            $('.toggle-icon')
                .removeClass('fa-chevron-up')
                .addClass('fa-chevron-down');
        }

        closeAll();

        headers.each(function () {
            $(this).on('click', function () {

                let collapse = $(this).parent().find('.collapse-topic');
                let icon = $(this).find('.toggle-icon');

                if (collapse.hasClass('show')) {
                    collapse.removeClass('show');
                    icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                    return;
                }

                closeAll();
                collapse.addClass('show');
                icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
            });
        });

        if (collapses.length > 0) {
            collapses.eq(0).addClass('show');

            headers.eq(0)
                .find('.toggle-icon')
                .removeClass('fa-chevron-down')
                .addClass('fa-chevron-up');
        }

        $('#reset-btn').on('click', function () {
            window.location.href = window.location.pathname;
        });

        $('#search').on('keyup', function () {
            $('#search-modal').val($(this).val());
        });
    });
</script>
@endsection

