@extends(auth()->user()->hasRole('teacher') ? 'teacher.layouts.app' : 'student.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/teacher-meeting-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="mt-1">
        <x-authenticated.breadcrumb :title="$classroom->levelClass->name . ' ' . $classroom->name" :subtitle="$subject->name"
            breadcrumb="Ikuti Aktivitas Kelas yang meliputi diskusi melalui forum, penugasan, serta penilaian." />

        <h4 class="fw-bolder">Daftar Topik</h4>

        <div class="mt-4 mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                {{-- FORM FILTER --}}
                <form method="GET" action="{{ url()->current() }}"
                    class="d-flex align-items-center flex-wrap gap-3 flex-grow-1">
                    @foreach (request()->except(['search', 'semester', 'page']) as $key => $value)
                        @if (is_array($value))
                            @foreach ($value as $k => $v)
                                <input type="hidden" name="{{ $key }}[{{ $k }}]"
                                    value="{{ $v }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach

                    <div class="search-wrap">
                        <input type="text" name="search" class="form-control search-input" placeholder="Cari..."
                            value="{{ request('search') }}" onkeydown="if(event.key==='Enter'){ this.form.submit() }">
                    </div>

                    <select class="form-select filter-select" id="semester" name="semester">
                        <option value="">Semua Semester</option>
                        <option value="ganjil" {{ request('semester') == 'ganjil' ? 'selected' : '' }}>
                            Ganjil
                        </option>
                        <option value="genap" {{ request('semester') == 'genap' ? 'selected' : '' }}>
                            Genap
                        </option>
                    </select>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-filter me-2"></i>Filter
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                            <i class="ti ti-refresh me-2"></i>Reset
                        </a>
                    </div>
                </form>

                {{-- TOMBOL ACTION KANAN --}}
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-primary gap-2" data-bs-toggle="modal" data-bs-target="#create-topic-modal">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M13 10H7M10 7V13M10 1C17.2 1 19 2.8 19 10C19 17.2 17.2 19 10 19C2.8 19 1 17.2 1 10C1 2.8 2.8 1 10 1Z"
                                stroke="#F6F6F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Tambah Topik
                    </button>
                    <a href="{{ route('teacher.e-learning.index') }}" class="btn btn-warning">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
        {{-- LIST TOPIC --}}
        <div class="meeting-list">
            @forelse ($topics as $topic)
                {{-- @dd($topic) --}}
                <div class="card meeting-card mb-4">
                    <div class="meeting-header d-flex justify-content-between align-items-center p-4"
                        style="cursor: pointer;">
                        <h4 class="mb-0 text-white fw-bolder">Topik {{ $topic->topic_number }}</h4>
                        <i class="fa fa-chevron-down toggle-icon fs-5" id="icon-topic{{ $topic->topic_number }}"
                            style="cursor: pointer; color: white;">
                        </i>
                    </div>
                    <div class="collapse-topic" id="topic{{ $topic->topic_number }}">
                        <div class="meeting-body">

                            {{-- DROPDOWN TAMBAH (UI ONLY) --}}
                            <div class="ms-auto d-flex align-items-center pb-3">
                                <div class="dropdown ms-auto">
                                    <button class="btn btn-primary d-flex align-items-center gap-2 dropdown-toggle"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M13 10H7M10 7V13M10 1C17.2 1 19 2.8 19 10C19 17.2 17.2 19 10 19C2.8 19 1 17.2 1 10C1 2.8 2.8 1 10 1Z"
                                                stroke="#F6F6F6" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <span>Tambah</span>
                                    </button>

                                    <ul class="dropdown-menu border-0 shadow rounded-3">
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2 fw-bold text-black btn-add-task"
                                                href="{{ route('teacher.task.create', $topic->id) }}">
                                                <div class="icon">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.20939 16.1585H3.52564C2.8558 16.1585 2.21339 15.8923 1.73974 15.4185C1.26609 14.9447 1 14.3021 1 13.6321V12.7899H5.63033M12.7863 5.63176V2.68428C12.7863 2.35116 12.8851 2.02552 13.0701 1.74854C13.2551 1.47157 13.518 1.25569 13.8257 1.12821C14.1334 1.00073 14.4719 0.967375 14.7985 1.03236C15.1252 1.09735 15.4252 1.25776 15.6607 1.49331C15.8961 1.72886 16.0565 2.02897 16.1215 2.35569C16.1864 2.68241 16.1531 3.02106 16.0257 3.32882C15.8982 3.63658 15.6824 3.89963 15.4055 4.0847C15.1286 4.26977 14.8031 4.36856 14.4701 4.36856H12.7863M14.4701 1H5.20939C4.53955 1 3.89715 1.26618 3.4235 1.73997C2.94985 2.21376 2.68376 2.85637 2.68376 3.52642V12.7899M6.05127 4.36855H9.41879M6.05127 7.73711H9.41879M13.9818 9.09232C14.3133 8.76064 14.7631 8.57431 15.232 8.57431C15.7009 8.57431 16.1506 8.76064 16.4822 9.09232C16.8137 9.42399 17 9.87384 17 10.3429C17 10.812 16.8137 11.2618 16.4822 11.5935L11.1025 17H8.57691V14.4736L13.9818 9.09232Z"
                                                            stroke="#2A3547" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                                Tambah Tugas
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item d-flex align-items-center gap-2 fw-bold text-black"
                                                href="{{ route('teacher.material.create', ['topic_id' => $topic->id]) }}">
                                                <div class="icon">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.00094 1.99993C5.00094 1.73473 4.89556 1.4804 4.70798 1.29287C4.5204 1.10535 4.26598 1 4.0007 1H2.00023C1.73496 1 1.48054 1.10535 1.29296 1.29287C1.10538 1.4804 1 1.73473 1 1.99993V15.999C1 16.2642 1.10538 16.5185 1.29296 16.7061C1.48054 16.8936 1.73496 16.9989 2.00023 16.9989H4.0007C4.26598 16.9989 4.5204 16.8936 4.70798 16.7061C4.89556 16.5185 5.00094 16.2642 5.00094 15.999M5.00094 1.99993V15.999M5.00094 1.99993C5.00094 1.73473 5.10632 1.4804 5.2939 1.29287C5.48148 1.10535 5.73589 1 6.00117 1H8.00164C8.26692 1 8.52133 1.10535 8.70891 1.29287C8.8965 1.4804 9.00188 1.73473 9.00188 1.99993V15.999C9.00188 16.2642 8.8965 16.5185 8.70891 16.7061C8.52133 16.8936 8.26692 16.9989 8.00164 16.9989H6.00117C5.73589 16.9989 5.48148 16.8936 5.2939 16.7061C5.10632 16.5185 5.00094 16.2642 5.00094 15.999M1 4.99973H5.00094M5.00094 12.9992H9.00188M10.0021 5.99967L14.003 4.99973M12.0026 12.9987L15.9265 12.0188M9.80319 1.56049L11.9877 1.03053C12.5498 0.895538 13.121 1.22052 13.27 1.76248L16.9659 15.1796C17.0311 15.4252 17.0022 15.6863 16.8849 15.9118C16.7676 16.1373 16.5703 16.3108 16.3317 16.3985L16.1987 16.4395L14.0142 16.9695C13.452 17.1045 12.8809 16.7795 12.7319 16.2375L9.03601 2.82041C8.97081 2.57478 8.99968 2.31366 9.11698 2.08819C9.23428 1.86273 9.43155 1.68916 9.67016 1.60149L9.80319 1.56049Z"
                                                            stroke="#2A3547" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                                Tambah Materi
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            {{-- INFO --}}
                            <div class="meeting-info mb-3 d-flex align-items-start">
                                <div class="flex-grow-1">
                                    <p class="mb-1"><strong>Judul Topik :</strong> {{ $topic->title }}</p>
                                    <p class="mb-0 text-muted meeting-desc">
                                        <strong>Deskripsi :</strong><br>
                                        {{ $topic->description }}
                                    </p>
                                </div>

                                <div class="ms-auto d-flex align-items-start gap-2">
                                    <button class="btn btn-icon btn-delete btn-delete-topic" type="button"
                                        data-id="{{ $topic->id }}">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                    <button class="btn btn-edit-icon btn-edit-custom btn-edit-topic" type="button"
                                        data-id="{{ $topic->id }}" data-title="{{ $topic->title }}"
                                        data-description="{{ $topic->description }}"
                                        data-semester="{{ $topic->semester }}">
                                        <i class="ti ti-pencil"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- TUGAS --}}
                            <div class="mt-3">
                                <h6 class="fw-bold mb-2">Tugas</h6>

                                <div class="task-wrap">
                                    @forelse ($topic->tasks as $task)
                                        <div class="meeting-item task-item">
                                            <div class="item-left">
                                                <div class="icon-box me-3 bg-primary text-white">
                                                    <svg width="33" height="33" viewBox="0 0 33 33"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.39262 29.9222H6.23557C4.97962 29.9222 3.77511 29.4231 2.88702 28.5347C1.99892 27.6464 1.5 26.4415 1.5 25.1851V23.6061H10.1819M23.5993 10.1846V4.65802C23.5993 4.03342 23.7845 3.42285 24.1314 2.90352C24.4783 2.38419 24.9713 1.97941 25.5482 1.74039C26.1251 1.50137 26.7599 1.43883 27.3723 1.56068C27.9847 1.68253 28.5472 1.98331 28.9887 2.42496C29.4303 2.86662 29.7309 3.42933 29.8528 4.04192C29.9746 4.65452 29.912 5.28949 29.6731 5.86654C29.4341 6.4436 29.0295 6.93681 28.5103 7.28382C27.9912 7.63083 27.3808 7.81604 26.7564 7.81604H23.5993M26.7564 1.5H9.39262C8.13666 1.5 6.93215 1.99908 6.04406 2.88744C5.15597 3.77581 4.65705 4.98069 4.65705 6.23703V23.6061M10.9711 7.81604H17.2852M10.9711 14.1321H17.2852M25.8408 16.6731C26.4625 16.0512 27.3057 15.7018 28.1849 15.7018C29.0641 15.7018 29.9073 16.0512 30.529 16.6731C31.1507 17.295 31.5 18.1384 31.5 19.0179C31.5 19.8974 31.1507 20.7409 30.529 21.3628L20.4423 31.5H15.7067V26.763L25.8408 16.6731Z"
                                                            stroke="white" stroke-width="3" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>

                                                <div>
                                                    <p class="mb-0 fw-medium">{{ $task->title }}</p>
                                                    <small class="text-muted meeting-desc">
                                                        Deadline:
                                                        {{ Carbon\Carbon::parse($task->deadline)->format('d F Y, H:i') }}
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="item-action">
                                                <a href="{{ route('teacher.task.detail', $task->id) }}"
                                                    class="btn btn-icon btn-view">
                                                    <i class="ti ti-eye fs-5"></i>
                                                </a>
                                                <button class="btn btn-icon btn-delete btn-delete-task" type="button"
                                                    data-id="{{ $task->id }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                                <a href="{{ route('teacher.task.edit', $task->id) }}"
                                                    class="btn btn-warning btn-icon btn-edit-task fs-6">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                            </div>
                                        </div>
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
                                        <div class="meeting-item material-item">
                                            <div class="item-left">
                                                <div class="icon-box me-3 bg-primary text-white">
                                                    <svg width="29" height="29" viewBox="0 0 29 29"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M7.66667 2.66667C7.66667 2.22464 7.49107 1.80072 7.17851 1.48816C6.86595 1.1756 6.44203 1 6 1H2.66667C2.22464 1 1.80072 1.1756 1.48816 1.48816C1.1756 1.80072 1 2.22464 1 2.66667V26C1 26.442 1.1756 26.866 1.48816 27.1785C1.80072 27.4911 2.22464 27.6667 2.66667 27.6667H6C6.44203 27.6667 6.86595 27.4911 7.17851 27.1785C7.49107 26.866 7.66667 26.442 7.66667 26M7.66667 2.66667V26M7.66667 2.66667C7.66667 2.22464 7.84226 1.80072 8.15482 1.48816C8.46738 1.1756 8.89131 1 9.33333 1H12.6667C13.1087 1 13.5326 1.1756 13.8452 1.48816C14.1577 1.80072 14.3333 2.22464 14.3333 2.66667V26C14.3333 26.442 14.1577 26.866 13.8452 27.1785C13.5326 27.4911 13.1087 27.6667 12.6667 27.6667H9.33333C8.89131 27.6667 8.46738 27.4911 8.15482 27.1785C7.84226 26.866 7.66667 26.442 7.66667 26M1 7.66667H7.66667M7.66667 21H14.3333M16 9.33333L22.6667 7.66667M19.3333 20.9992L25.8717 19.3659M15.6685 1.93422L19.3085 1.05088C20.2452 0.825885 21.1969 1.36755 21.4452 2.27088L27.6035 24.6342C27.7122 25.0436 27.6641 25.4789 27.4686 25.8547C27.2732 26.2305 26.9445 26.5198 26.5469 26.6659L26.3252 26.7342L22.6852 27.6175C21.7485 27.8425 20.7969 27.3009 20.5485 26.3976L14.3902 4.03422C14.2816 3.6248 14.3297 3.18957 14.5251 2.81377C14.7206 2.43797 15.0493 2.14868 15.4469 2.00255L15.6685 1.93422Z"
                                                            stroke="white" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>

                                                <div>
                                                    <p class="mb-0 fw-medium">{{ $module->title }}</p>
                                                </div>
                                            </div>

                                            <div class="item-action">
                                                <a href="{{ route('teacher.material.show', $module->id) }}"
                                                    class="btn btn-icon btn-view">
                                                    <i class="ti ti-eye"></i>
                                                </a>


                                                <button class="btn btn-icon btn-delete btn-delete-material" type="button"
                                                    data-id="{{ $module->id }}">
                                                    <i class="ti ti-trash"></i>
                                                </button>

                                                <a href="{{ route('teacher.material.edit', $module->id) }}"
                                                    class="btn btn-warning btn-icon btn-edit-task fs-6">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted">Belum ada materi</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="meeting-empty">
                    <div class="meeting-empty__inner">
                        <img class="meeting-empty__img" src="{{ asset('admin_assets/dist/images/empty/nodata.png') }}"
                            alt="Belum ada pertemuan">
                        <div class="meeting-empty__title">Ups! Belum ada Topik Saat Ini.</div>
                        <p class="meeting-empty__text">
                            Belum ada topik pembelajaran untuk saat ini.
                            Silakan cek kembali di lain waktu.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @include('teacher.pages.topic.widgets.create')
    @include('teacher.pages.topic.widgets.edit')
    <x-confirmation.delete id="delete-topic" title="Apakah anda yakin ingin menghapus topik ini?"
        description="Jika Topik dihapus maka tugas dan materi pada topik ini juga akan terhapus" button="Hapus" />

    <x-confirmation.delete id="delete-task" title="Apakah anda yakin ingin menghapus tugas ini?"
        description="Jika tugas dihapus maka jawaban yg sudah dikumpulkan oleh siswa di tugas ini juga akan terhapus"
        button="Hapus" />

    <x-confirmation.delete id="delete-material" title="Apakah anda yakin ingin menghapus materi ini?"
        description="Materi yang sudah dihapus tidak dapat dikembalikan" button="Hapus" />
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (sessionStorage.getItem('materialAdded') === 'true') {
                sessionStorage.removeItem('materialAdded');
                location.reload();
            }
        });
        let topicId;
        let taskId;
        let moduleId;

        document.addEventListener('DOMContentLoaded', function() {

            const headers = document.querySelectorAll('.meeting-header');
            const collapses = document.querySelectorAll('.collapse-topic');

            function closeAll() {
                collapses.forEach(col => {
                    col.classList.remove('show');
                });

                document.querySelectorAll('.toggle-icon').forEach(icon => {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                });
            }

            closeAll();

            headers.forEach(function(header) {
                header.addEventListener('click', function() {

                    let collapse = this.parentElement.querySelector('.collapse-topic');
                    let icon = this.querySelector('.toggle-icon');

                    if (collapse.classList.contains('show')) {
                        collapse.classList.remove('show');
                        icon.classList.remove('fa-chevron-up');
                        icon.classList.add('fa-chevron-down');
                        return;
                    }

                    closeAll();
                    collapse.classList.add('show');
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                });
            });

            if (collapses.length > 0) {
                collapses[0].classList.add('show');
                headers[0]
                    .querySelector('.toggle-icon')
                    .classList.replace('fa-chevron-down', 'fa-chevron-up');
            }
        });
    </script>

    @include('teacher.pages.topic.scripts.create')
    @include('teacher.pages.topic.scripts.edit')
    @include('teacher.pages.topic.scripts.delete')

    @include('teacher.pages.task.scripts.delete')

    @include('teacher.pages.material.scripts.delete')
@endsection
