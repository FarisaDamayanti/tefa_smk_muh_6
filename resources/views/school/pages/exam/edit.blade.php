@extends('school.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/exam-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/summernote/dist/summernote-lite.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb :title="'Edit Ujian'" breadcrumb="Kelola kembali pengaturan ujian pada halaman ini." />

    <div class="card">
        <div class="card-body">
            <form id="form-edit-exam" data-exam-id="{{ $exam->id }}"
                data-locked="{{ $exam->studentExams()->exists() ? 1 : 0 }}" action="javascript:void(0)" method="POST">
                @csrf
                @method('PUT')

                @if ($exam->studentExams()->exists())
                    <div class="alert alert-warning">
                        <i class="ti ti-alert-triangle me-2"></i>
                        Ujian ini sudah dimulai oleh siswa. Beberapa pengaturan tidak dapat diubah.
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Ujian <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $exam->title ?? '') }}"
                        placeholder="Masukkan judul ujian">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Bank Soal <span class="text-danger">*</span></label>
                    <select name="question_bank_id" id="question_bank_id" class="form-select">
                        @foreach ($questionBanks ?? [] as $qb)
                            <option value="{{ $qb->id }}"
                                data-level-class-id="{{ $qb->level_class_id ?? ($qb->levelClass->id ?? '') }}"
                                @selected(old('question_bank_id', $exam->question_bank_id ?? null) == $qb->id)>
                                {{ $qb->name ?? ($qb->title ?? 'Bank Soal') }}
                            </option>
                        @endforeach
                    </select>

                    <input type="hidden" name="level_class_id" id="level_class_id"
                        value="{{ old('level_class_id', $exam->questionBank?->level_class_id ?? '') }}">
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Tanggal dan Jam Ujian <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="datetime-local" name="exam_date" class="form-control"
                                value="{{ old('exam_date', !empty($exam->exam_date) ? \Carbon\Carbon::parse($exam->exam_date)->format('Y-m-d\TH:i') : '') }}">
                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                        <select name="school_year_id" class="form-select">
                            @foreach ($schoolYears ?? [] as $sy)
                                <option value="{{ $sy->id }}" @selected(old('school_year_id', $exam->school_year_id ?? null) == $sy->id)>
                                    {{ $sy->school_year ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                        <select name="semester" class="form-select" id="semester">
                            <option value="GANJIL" @selected(strtolower(old('semester', $exam->semester ?? 'ganjil')) == 'ganjil')>
                                Semester Ganjil
                            </option>
                            <option value="GENAP" @selected(strtolower(old('semester', $exam->semester ?? 'ganjil')) == 'genap')>
                                Semester Genap
                            </option>
                        </select>
                    </div>
                </div>

                {{-- ROW 2: 3 field --}}
                <div class="row g-3 mt-1">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nilai Minimal <span class="text-danger">*</span></label>
                        <input type="number" name="minimum_score" class="form-control" placeholder="Nilai minimal"
                            value="{{ old('minimum_score', $exam->minimum_score ?? 75) }}" min="0" max="100">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Durasi <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="duration" class="form-control" placeholder="Durasi ujian"
                                value="{{ old('duration', $exam->duration ?? 120) }}" min="1">
                            <span class="input-group-text">(Menit)</span>
                        </div>
                        <span class="text-danger" id="error-duration"></span>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Total Soal <span class="text-danger">*</span></label>
                        <input type="number" name="total_questions" class="form-control"
                            value="{{ old('total_questions', $exam->total_questions ?? 0) }}" min="0"
                            placeholder="Total soal yang akan diujikan">
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-semibold">
                        Kelas yang bisa mengikuti ujian <span class="text-danger">*</span>
                    </label>

                    <select id="classrooms" name="classroom_ids[]" class="select2 form-select" multiple
                        data-selected='@json(old('classroom_ids', $exam->examClassrooms->pluck('classroom_id')))'>
                        @foreach (old('classroom_ids', $exam->examClassrooms->pluck('classroom_id')) as $cid)
                        @endforeach

                        @foreach ($exam->examClassrooms as $ec)
                            @if ($ec->classroom)
                                <option value="{{ $ec->classroom_id }}" selected>
                                    {{ $ec->classroom->levelClass->name ?? '' }} - {{ $ec->classroom->name ?? '' }}
                                </option>
                            @endif
                        @endforeach

                        @foreach ($classrooms ?? [] as $c)
                            <option value="{{ $c->id }}">
                                {{ $c->levelClass->name ?? '' }} - {{ $c->name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="mt-3 col-md-6 col-12">
                        <label class="form-label fw-semibold">
                            Izinkan pengumpulan kapan saja (Opsional)
                        </label>

                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-check-label" style="width: 80%" for="allow_early_submission">Jika anda mengaktifkan fitur ini siswa dapat mengumpulkan jawaban tanpa menunggu sisa waktu 5 menit.</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input fs-5" name="allow_early_submission" type="checkbox" id="allow_early_submission" {{ old('allow_early_submission', $exam->allow_early_submission ?? 0) ? 'checked' : '' }}/>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 col-md-6 col-12">
                        <label class="form-label fw-semibold">
                            Izinkan siswa melihat hasil jawaban benar/salah dan nilai yang diperoleh (opsional)
                        </label>

                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-check-label" style="width: 80%" for="can_view_answer_result">Jika anda mengaktifkan fitur ini siswa dapat melihat nilai serta soal mana yang benar dan salah.</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input fs-5" name="can_view_answer_result" type="checkbox" id="can_view_answer_result" {{ old('can_view_answer_result', $exam->can_view_answer_result ?? 0) ? 'checked' : '' }}/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="bg-primary text-white px-3 py-2 rounded-top fw-semibold">Peraturan Wajib</div>
                    <div class="border rounded-bottom p-3">
                        {!! trans('alert.rules.exam') !!}
                    </div>
                </div>

                <div class="mt-4">
                    <label class="form-label fw-semibold">Peraturan Tambahan (Opsional)</label>
                    <textarea id="exam-additional-rules" name="rules" class="form-control">{!! filled(old('rules')) ? old('rules') : $exam->rules ?? '' !!}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('school.exams.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    <button id="btn-save-edit" type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('admin_assets/dist/summernote/dist/summernote-lite.min.js') }}"></script>
    @include('school.pages.exam.scripts.summernotes')
    @include('school.pages.exam.scripts.script-edit')
@endsection
