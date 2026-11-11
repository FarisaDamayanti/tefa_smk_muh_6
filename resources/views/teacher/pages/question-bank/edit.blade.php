@extends('teacher.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Edit Soal" breadcrumb="Kelola soal ujian dengan mudah dengan fitur ini" />

    <div class="d-flex align-items-center justify-content-end mb-3">
        <div class="badge bg-light-primary rounded-4">
            <h5 class="text-primary mb-0 py-2 px-3 fs-4">
                {{ $questionBank->total_questions }} / {{ $questionBank->question_limit }} Soal diisi
            </h5>
        </div>
    </div>

    <form action="" id="form-update-question">
        <input type="hidden" id="question-type-hidden" name="question_type" value="{{ $examQuestion->type }}">

        {{-- BOBOT KHUSUS MATCHING --}}
        <div class="d-flex justify-content-end mb-4">
            <div class="d-none align-items-center gap-2" id="matching-score-bar">
                <label class="fw-semibold mb-0">Bobot</label>
                <div class="bg-light rounded-1 px-3 py-1 d-flex align-items-center">
                    <input type="number" name="matching_score" id="matching-score"
                        class="form-control border-0 bg-transparent text-center text-primary fw-semibold p-0"
                        style="width: 70px;" min="0" step="1" value="{{ old('score', $examQuestion->score) }}">
                </div>
            </div>
        </div>

        <div class="validation-message text-danger mt-2" id="error-matching-score"></div>

        {{-- =========================
            SECTION: PILIHAN GANDA
        ========================= --}}
        <div class="question-type-form" id="form-multiple_choice">
            <div class="card shadow-md border border-1">
                <div class="card-body ga-2 pt-3">

                    <div class="mb-4">
                        <label class="fw-bold text-dark mb-2">
                            Soal <span class="text-danger">*</span>
                        </label>
                        <textarea name="question" id="summernote-question">{!! $examQuestion->question !!}</textarea>
                        <div class="validation-message text-danger" id="error-question"></div>
                    </div>

                    @foreach ($examQuestion->sortExamQuestionOptions as $option)
                        @if ($loop->first)
                            <div class="mb-4">
                                <label class="fw-bold text-dark mb-2">
                                    <input type="checkbox" name="answer[]" class="answer-check"
                                        value="{{ strtoupper(substr($option->option_label, -1)) }}"
                                        onclick="markAnswer(this)" {{ $option->is_correct ? 'checked' : '' }}>
                                    Jawaban A <span class="text-danger">*</span>
                                    <span class="option-mark text-primary ms-2">
                                        {{ $option->is_correct ? '(Ditandai sebagai jawaban benar)' : '' }}
                                    </span>
                                </label>
                                <textarea name="{{ $option->option_label }}" id="summernote-answer-{{ substr($option->option_label, -1) }}">{!! $option->option_text !!}</textarea>
                                <div class="validation-message text-danger" id="error-{{ $option->option_label }}"></div>
                            </div>
                        @else
                            @if (($loop->index - 1) % 2 == 0)
                                <div class="d-flex flex-column flex-md-row gap-3 justify-space-between">
                            @endif

                            <div class="mb-4 flex-fill">
                                <label class="fw-bold text-dark mb-2">
                                    <input type="checkbox" name="answer[]" class="answer-check"
                                        value="{{ strtoupper(substr($option->option_label, -1)) }}"
                                        onclick="markAnswer(this)" {{ $option->is_correct ? 'checked' : '' }}>
                                    Jawaban {{ strtoupper(substr($option->option_label, -1)) }} <span class="text-danger">*</span>
                                    <span class="option-mark text-primary ms-2">
                                        {{ $option->is_correct ? '(Ditandai sebagai jawaban benar)' : '' }}
                                    </span>
                                </label>
                                <textarea name="{{ $option->option_label }}" id="summernote-answer-{{ substr($option->option_label, -1) }}">{!! $option->option_text !!}</textarea>
                                <div class="validation-message text-danger" id="error-{{ $option->option_label }}"></div>
                            </div>

                            @if (($loop->index - 1) % 2 == 1 || $loop->last)
                                </div>
                            @endif
                        @endif
                @endforeach

                <div class="validation-message badge bg-light-danger w-100 text-start" style="color:#FF0000;" id="error-answer"></div>
            </div>
        </div>
        </div>
        {{-- END #form-multiple_choice --}}

        {{-- =========================
            SECTION: MATCHING
        ========================= --}}
        <div class="question-type-form d-none" id="form-matching">
            <div class="card shadow-md border border-1">
                <div class="card-body pt-3">

                    <div class="alert alert-warning d-flex align-items-center gap-2 rounded-4 mb-4">
                        <i class="ti ti-info-circle text-warning fs-3"></i>
                        <span class="fw-formal text-warning">
                            Anda dapat menambahkan soal penjodohan dengan jumlah maksimal <b>4 opsi</b>.
                        </span>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold text-dark mb-2">
                            Pertanyaan <span class="text-danger">*</span>
                        </label>
                        <textarea name="question" id="summernote-matching-question">{!! old('question', $examQuestion->question) !!}</textarea>
                        <div class="validation-message text-danger" id="error-matching-question"></div>
                    </div>

                    @php
                        $leftItems = collect($examQuestion->leftExamQuestionMatchings ?? [])->keyBy('id');
                        $rightItems = collect($examQuestion->rightExamQuestionMatchings ?? [])->keyBy('id');
                        $existingCount = max($leftItems->count(), $rightItems->count());
                        $rowCount = max(1, min(4, $existingCount));
                    @endphp

                    <div id="matching-list" class="d-flex flex-column gap-3">
                        @foreach ($leftItems as $key => $leftItem)
                            @php
                                $left = $leftItem;
                                $right = $rightItems->get($leftItem->leftItemAnswer->right_item_id);
                            @endphp

                            <div class="border rounded-4 p-4 mb-3 matching-row" data-row="{{ $key }}">
                                <div class="row g-3 align-items-center">
                                    <div class="col-12 col-lg-5">
                                        <label class="fw-semibold mb-2 d-block">Pernyataan</label>
                                        <textarea class="form-control js-matching-summernote" data-side="left" data-id="{{ $left->id ?? '' }}"
                                            id="matching-left-{{ $key }}">{!! $left->item_text ?? '' !!}</textarea>

                                        <div class="validation-message text-danger small mt-1"
                                            id="error-matching-left-{{ $key }}"></div>
                                    </div>
                                    <div class="col-12 col-lg-1 d-flex justify-content-center align-items-center">
                                        <i class="ti ti-arrow-right fs-10 text-muted"></i>
                                    </div>
                                    <div class="col-12 col-lg-5">
                                        <label class="fw-semibold mb-2 d-block">Jawaban</label>
                                        <textarea class="form-control js-matching-summernote" data-side="right" data-id="{{ $right->id ?? '' }}"
                                            id="matching-right-{{ $key }}">{!! $right->item_text ?? '' !!}</textarea>

                                        <div class="validation-message text-danger small mt-1"
                                            id="error-matching-right-{{ $key }}"></div>
                                    </div>
                                    <div class="col-12 col-lg-1 d-flex justify-content-center align-items-start">
                                        <button type="button"
                                            class="btn btn-sm p-0 border-0 bg-transparent js-remove-matching-row"
                                            title="Hapus baris">

                                            <svg width="30" height="30" viewBox="0 0 40 40" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M31.6615 6.66667H25.8281L24.1615 5H15.8281L14.1615 6.66667H8.32812V10H31.6615M9.99479 31.6667C9.99479 32.5507 10.346 33.3986 10.9711 34.0237C11.5962 34.6488 12.4441 35 13.3281 35H26.6615C27.5455 35 28.3934 34.6488 29.0185 34.0237C29.6436 33.3986 29.9948 32.5507 29.9948 31.6667V11.6667H9.99479V31.6667Z"
                                                    fill="#FA896B" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <button type="button" class="btn btn-primary w-100 rounded-3 mt-3" id="btn-add-matching-row">
                            + Tambah Opsi
                        </button>
                        <div class="text-muted small mt-2 d-none" id="matching-limit-hint">
                            Maksimal 4 opsi sudah tercapai.
                        </div>
                    </div>

                </div>
            </div>
        </div>
        {{-- END #form-matching --}}

        {{-- =========================
            SECTION: ESSAY
        ========================= --}}
        <div class="question-type-form d-none" id="form-essay">
            <div class="card shadow-md border border-1">
                <div class="card-body pt-3">

                    {{-- BAR ATAS: Bobot di kanan --}}
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
                        <div></div> {{-- spacer kiri biar bobot tetep nempel kanan --}}
                        <div class="d-flex align-items-center gap-2">
                            <label class="fw-semibold mb-0">Bobot</label>

                            <div class="bg-light rounded-1 px-3 py-1 d-flex align-items-center">
                                <input type="number"
                                    class="form-control border-0 bg-transparent text-center text-primary fw-semibold p-0"
                                    id="essay-score" name="essay_score" style="width:70px;" min="1"
                                    max="100" step="1"
                                    value="{{ old('essay_score', $examQuestion->score) }}">
                            </div>
                        </div>
                    </div>
                    <div class="validation-message text-danger mt-2" id="error-essay-score"></div>

                    {{-- ISI: Soal --}}
                    <div class="mb-4">
                        <label class="fw-bold text-dark mb-2">
                            Soal <span class="text-danger">*</span>
                        </label>

                        <textarea name="essay_question" id="summernote-essay-question">{!! $examQuestion->question !!}</textarea>
                        <div class="validation-message text-danger" id="error-essay_question"></div>
                    </div>

                </div>
            </div>
        </div>
        {{-- END #form-essay --}}

        {{-- BUTTONS --}}
        <div class="my-4">
            <div class="d-flex justify-content-end align-items-start flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('teacher.question-bank.detail', $questionBank->id) }}"
                        class="btn btn-outline-muted">
                        Kembali
                    </a>
                    <button class="btn btn-primary update-btn" type="submit" id="btn-update-question">
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
    @include('teacher.pages.question-bank.scripts.edit')
@endsection
