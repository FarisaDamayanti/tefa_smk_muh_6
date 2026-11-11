@extends('teacher.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Tambah Soal" breadcrumb="Menambahkan soal ujian ke dalam bank soal" />

    <div class="d-flex align-items-center justify-content-end mb-3">
        <div class="badge bg-light-primary rounded-4">
            <h5 class="text-primary mb-0 py-2 px-3 fs-4">
                {{ $questionBank->total_questions }} / {{ $questionBank->question_limit }} Soal diisi
            </h5>
        </div>
    </div>

    <form action="" id="form-create-question">
        <div class="card shadow-md border border-1">
            <div class="card-body ga-2 pt-3">

                {{-- DROPDOWN TIPE SOAL + BOBOT (KANAN) --}}
                <div class="mb-4">
                    <label class="fw-bold text-dark mb-2 d-block">Tipe Soal <span class="text-danger">*</span></label>

                    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                        <div style="min-width: 220px;">
                            <select class="form-select" id="question-type" name="question_type">
                                <option value="multiple_choice" selected>Pilih Ganda Kompleks</option>
                                <option value="matching">Menjodohkan Soal</option>
                                <option value="essay">Uraian</option>
                            </select>
                        </div>

                        <div id="bobot-wrapper" class="d-none">
                            <div class="d-flex align-items-center gap-2">
                                <label class="fw-semibold mb-0">Bobot</label>
                                <div class="bg-light rounded-1 px-3 py-1 d-flex align-items-center">
                                    <input type="number" name="score" id="question-score"
                                        class="form-control border-0 bg-transparent text-center text-primary fw-semibold p-0"
                                        style="width: 55px;" min="1" max="100" step="1" value="5">
                                </div>
                            </div>
                            <div class="validation-message text-danger mt-1" id="error-score"></div>
                        </div>
                    </div>

                </div>
                {{-- =========================================
                    SECTION: PILIHAN GANDA (NORMAL)
                ========================================= --}}



                <div class="question-type-form" id="form-multiple_choice">

                    <div class="alert alert-warning d-flex align-items-center gap-2 rounded-4 mb-4">
                        <i class="ti ti-info-circle text-warning fs-3"></i>
                        <span class="fw-formal text-warning">
                            Anda dapat memilih <b>satu</b> atau <b>dua</b> <b>jawaban yang benar</b> dalam satu soal.
                        </span>
                    </div>
                    <div class="mb-4">
                        <label class="fw-bold text-dark mb-2">Soal <span class="text-danger">*</span></label>
                        <textarea name="question" id="summernote-question"></textarea>
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
                        <textarea name="option_a" id="summernote-answer-a"></textarea>
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
                            <textarea name="option_b" id="summernote-answer-b"></textarea>
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
                            <textarea name="option_c" id="summernote-answer-c"></textarea>
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
                            <textarea name="option_d" id="summernote-answer-d"></textarea>
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
                            <textarea name="option_e" id="summernote-answer-e"></textarea>
                            <div class="validation-message text-danger" id="error-option_e"></div>
                        </div>
                    </div>

                    <div class="validation-message badge bg-light-danger w-100 text-start" style="color:#FF0000;"
                        id="error-answer"></div>
                </div>

                {{-- =========================================
                   SECTION: PENJODOHAN 
                 ========================================= --}}
                <div class="question-type-form d-none" id="form-matching">

                    <div class="alert alert-warning d-flex align-items-center gap-2 rounded-4 mb-4">
                        <i class="ti ti-info-circle text-warning fs-3"></i>
                        <span class="fw-formal text-warning">
                            Anda dapat menambahkan soal penjodohan dengan jumlah maksimal <b>4 opsi</b>.
                        </span>
                    </div>

                    {{-- SOAL (WAJIB) --}}
                    <div class="mb-4">
                        <label class="fw-bold text-dark mb-2">
                            Soal Penjodohan <span class="text-danger">*</span>
                        </label>
                        <textarea name="question" id="summernote-matching-question"></textarea>

                        <div class="validation-message text-danger mb-3" id="error-matching-question"></div>

                        <div class="mb-3">
                            <div class="fw-semibold text-dark">Cocokkan jawaban berikut dengan pernyataan yang sesuai.
                            </div>
                            <div class="text-muted small">Penjodohan</div>
                        </div>
                    </div>

                    <div id="matching-list" class="d-flex flex-column gap-2"></div>

                    <button type="button" class="btn btn-primary w-100 rounded-3 mt-3" id="btn-add-matching-row">
                        + Tambah Opsi
                    </button>

                    <div class="text-muted small mt-2" id="matching-limit-hint" style="display:none;">
                        Maksimal 4 opsi sudah tercapai.
                    </div>
                </div>
                {{-- =========================================
                    SECTION: ESSAY
                ========================================= --}}
                <div class="question-type-form d-none" id="form-essay">
                    <div class="alert alert-warning d-flex align-items-center gap-2 rounded-4 mb-4">
                        <i class="ti ti-info-circle text-warning fs-3"></i>
                        <span class="fw-formal text-warning">
                            Tentukan <b>bobot nilai</b> untuk soal ini. Bobot akan <b>memengaruhi total nilai</b> yang
                            diperoleh siswa.
                        </span>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold text-dark mb-2">
                            Soal Uraian <span class="text-danger">*</span>
                        </label>

                        <textarea name="question" id="summernote-essay-question"></textarea>
                        <div class="validation-message text-danger" id="error-essay-question"></div>
                    </div>
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
                    <a href="{{ route('teacher.question-bank.detail', $questionBank->id) }}"
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
@endsection

@section('script')
    @include('teacher.pages.question-bank.scripts.mark-answer')
    @include('teacher.pages.question-bank.scripts.summernote')
    @include('teacher.pages.question-bank.scripts.switching')
    @include('teacher.pages.question-bank.scripts.create')
@endsection
