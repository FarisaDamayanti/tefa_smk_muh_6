@extends('teacher.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb
        title="Tambah Tugas"
        breadcrumb="Tambah tugas untuk topik {{ $topic->title }} di {{ $topic->classroom->levelClass->name }} {{ $topic->classroom->name }} {{ $topic->subject->name }}" />

    <div class="card shadow-sm">
        <div class="card-header bg-primary px-4">
            <h5 class="text-white mb-0">Tugas</h5>
        </div>
        <div class="card-body">
            <form id="form-create-task" action="">

                {{-- Judul --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="create-title-task">Judul Tugas <span class="text-danger">*</span></label>
                    <input id="create-title-task" name="title" type="text" class="form-control"
                        placeholder="Masukkan Judul Tugas disini..">
                    <div class="invalid-feedback"></div>
                </div>

                {{-- Deadline --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="create-deadline-task">Tenggat Waktu <span class="text-danger">*</span></label>
                    <input id="create-deadline-task" name="deadline" type="datetime-local"
                        class="form-control">
                    <div class="invalid-feedback"></div>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="create-description-task">Deskripsi <span class="text-danger">*</span></label>
                    <textarea id="create-description-task" name="description" class="form-control" placeholder="Masukkan Deskripsi Tugas Disini.."></textarea>
                    <div class="invalid-feedback d-block"></div>
                </div>

                {{-- Multiple Choice Task --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="create-allow_overdue-task">Tugas Pilihan Ganda</label>

                    <div class="d-flex justify-content-between">
                        <label class="form-check-label" style="width: 500px" for="allow_overdue">Jika anda mengaktifkan opsi ini maka tugas akan berbasis pilihan ganda</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="allow_overdue" type="checkbox" id="allow_overdue"/>
                        </div>
                    </div>
                </div>

                {{-- Show Correct Answer --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="create-allow_overdue-task">Tampilkan Jawaban Benar</label>

                    <div class="d-flex justify-content-between">
                        <label class="form-check-label" style="width: 500px" for="allow_overdue">Jika anda mengaktifkan opsi ini maka siswa dapat melihat jawaban benar setelah ujian</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="allow_overdue" type="checkbox" id="allow_overdue"/>
                        </div>
                    </div>
                </div>

                {{-- Jenis Pengumpulan --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="create-submission_type-task">Jenis Pengumpulan Tugas <span class="text-danger">*</span></label>
                    <select id="create-submission_type-task" name="submission_type" class="form-select">
                        <option value="" selected disabled>Pilih jenis pengumpulan tugas</option>
                        <option value="file">Unggah Berkas</option>
                        <option value="link">Kirim Tautan</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                {{-- Allow overdue --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="create-allow_overdue-task">Izinkan Pengumpulan Terlambat <span class="text-danger">*</span></label>

                    <div class="d-flex justify-content-between">
                        <label class="form-check-label" style="width: 500px" for="allow_overdue">Jika anda mengaktifkan opsi ini siswa dapat mengumpulkan jawaban meskipun melebihi deadline.</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="allow_overdue" type="checkbox" id="allow_overdue"/>
                        </div>
                    </div>
                </div>

            </form>
            <form action="">
                <div class="card">
                    <div class="">

                    </div>
                </div>
            </form>
            <form action="" id="form-create-question">
        <div class="card shadow-md border border-1">
             <div class="card-header bg-primary d-flex justify-content-between align-items-center px-4">
                <h6 class="text-white mb-0">Soal Nomor 1</h6>
            </div>
            <div class="position-relative card-body gap-2 pt-3">
                {{-- =========================================
                    SECTION: PILIHAN GANDA (NORMAL)
                ========================================= --}}
                <div class="question-type-form" id="form-multiple_choice">
                    <button class="btn-delete bg-danger-subtle position-absolute top-0 end-0 border border-1 rounded gap-1"
                    style="z-index: 10; transform: translate(115%, -130%);"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 29 32" fill="none" border="none">
                        <path d="M9.5 7.93391V3.24617C9.5 2.83175 9.66463 2.4343 9.95767 2.14126C10.2507 1.84822 10.6482 1.68359 11.0626 1.68359H17.3129C17.7273 1.68359 18.1248 1.84822 18.4178 2.14126C18.7108 2.4343 18.8755 2.83175 18.8755 3.24617V7.93391" stroke="#FA896B" stroke-width="3.36888" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M1.6875 7.93359H26.6888" stroke="#FA896B" stroke-width="3.36888" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3.25 7.93359L4.81258 26.6845C4.81258 27.5134 5.14184 28.3083 5.72792 28.8944C6.314 29.4804 7.10889 29.8097 7.93774 29.8097H20.4384C21.2672 29.8097 22.0621 29.4804 22.6482 28.8944C23.2343 28.3083 23.5635 27.5134 23.5635 26.6845L25.1261 7.93359" stroke="#FA896B" stroke-width="3.36888" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M11.0547 14.1875V23.563" stroke="#FA896B" stroke-width="3.36888" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17.3047 14.1875V23.563" stroke="#FA896B" stroke-width="3.36888" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="mb-4">
                        <label class="fw-bold text-dark mb-2">Soal <span class="text-danger">*</span></label>
                        <textarea name="question" id="create-description-task"></textarea>
                        <div class="validation-message text-danger" id="error-question"></div>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold text-dark mb-2">
                            <input type="checkbox" name="answer[]" class="answer-check" value="A"
                                onclick="markAnswer(this)">
                            Jawaban A
                            <span class="text-danger">* </span>
                            <span class="option-mark text-primary ms-2"></span>
                        </label>
                        <textarea name="option_a" id="create-description-task"></textarea>
                        <div class="validation-message text-danger" id="error-option_a"></div>
                    </div>

                    <div class="d-flex flex-column flex-md-row gap-3 justify-space-between">
                        <div class="mb-4 flex-fill">
                            <label class="fw-bold text-dark mb-2">
                                <input type="checkbox" name="answer[]" class="answer-check" value="B"
                                    onclick="markAnswer(this)">
                                Jawaban B
                                <span class="text-danger">* </span>
                                <span class="option-mark text-primary ms-2"></span>
                            </label>
                            <textarea name="option_b" id="create-description-task"></textarea>
                            <div class="validation-message text-danger" id="error-option_b"></div>
                        </div>

                        <div class="mb-4 flex-fill">
                            <label class="fw-bold text-dark mb-2">
                                <input type="checkbox" name="answer[]" class="answer-check" value="C"
                                    onclick="markAnswer(this)">
                                Jawaban C
                                <span class="text-danger">* </span>
                                <span class="option-mark text-primary ms-2"></span>
                            </label>
                            <textarea name="option_c" id="create-description-task"></textarea>
                            <div class="validation-message text-danger" id="error-option_c"></div>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-md-row gap-3 justify-space-between">
                        <div class="mb-4 flex-fill">
                            <label class="fw-bold text-dark mb-2">
                                <input type="checkbox" name="answer[]" class="answer-check" value="D"
                                    onclick="markAnswer(this)">
                                Jawaban D
                                <span class="text-danger">* </span>
                                <span class="option-mark text-primary ms-2"></span>
                            </label>
                            <textarea name="option_d" id="create-description-task"></textarea>
                            <div class="validation-message text-danger" id="error-option_d"></div>
                        </div>

                        <div class="mb-4 flex-fill">
                            <label class="fw-bold text-dark mb-2">
                                <input type="checkbox" name="answer[]" class="answer-check" value="E"
                                    onclick="markAnswer(this)">
                                Jawaban E
                                <span class="text-danger">* </span>
                                <span class="option-mark text-primary ms-2"></span>
                            </label>
                            <textarea name="option_e" id="create-description-task"></textarea>
                            <div class="validation-message text-danger" id="error-option_e"></div>
                        </div>
                    </div>

                    <div class="validation-message badge bg-light-danger w-100 text-start" style="color:#FF0000;"
                        id="error-answer"></div>
                </div>
            </div>
        </div>

        <div class="my-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <button type="submit" class="btn btn-primary create-another-btn d-flex align-items-center gap-1"
                    id="btn-create-another-question">
                    <i class="ti ti-plus fs-6 fw-semibold"></i>
                    Simpan & Tambah Soal Lainnya
                </button>

                <div class="d-flex align-items-center gap-2">
                    <a
                        class="btn btn-outline-muted">
                        Kembali
                    </a>
                    <button class="btn btn-primary create-btn" type="submit" id="btn-create-question">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </form>
        </div>
    </div>
@endsection

@section('script')
    @include('teacher.pages.task.scripts.summernote')
    @include('teacher.pages.task.scripts.create')
@endsection
