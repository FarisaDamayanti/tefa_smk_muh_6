@extends('school.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/exam-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/summernote/dist/summernote-lite.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb :title="'Tambah Ujian'"
        breadcrumb="Tambahkan Data Ujian Disini." />

    {{-- Form Card --}}
    <div class="card">
        <div class="card-body">
            <form id="form-create-exam" action="javascript:void(0)" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Judul Ujian <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Masukkan judul ujian">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Bank Soal <span class="text-danger">*</span></label>
                    <select name="question_bank_id" id="question_bank_id" class="form-select select2">
                        <option value="" selected disabled>Pilih bank soal</option>
                        @forelse(($questionBanks ?? []) as $qb)
                            <option value="{{ $qb->id }}" data-level-class-id="{{ $qb->level_class_id ?? '' }}">
                                {{ $qb->title ?? ($qb->name ?? 'Bank Soal') }}
                            </option>
                        @empty
                            <option value="" selected disabled>Bank soal belum tersedia</option>
                        @endforelse
                    </select>

                    <input type="hidden" name="level_class_id" id="level_class_id" value="">
                </div>

                {{-- ROW 1 --}}
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            Tanggal dan Waktu Ujian <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" name="exam_datetime" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                        <select name="school_year_id" class="form-select">
                            @forelse(($schoolYears ?? []) as $sy)
                                <option value="{{ $sy->id }}" @if ($sy->active) selected @endif>
                                    {{ $sy->school_year ?? $sy->start_year . '/' . $sy->end_year }}
                                </option>
                            @empty
                                <option value="" selected disabled>Tahun ajaran belum tersedia</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Semester <span class="text-danger">*</span></label>
                        <select name="semester" id="semester" class="form-select" required>
                            <option value="ganjil" @if ($currentSemester === 'ganjil') selected @endif>Semester Ganjil</option>
                            <option value="genap" @if ($currentSemester === 'genap') selected @endif>Semester Genap</option>
                        </select>
                    </div>
                </div>

                {{-- ROW 2 --}}
                <div class="row g-3 mt-1">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nilai Minimal <span class="text-danger">*</span></label>
                        <input type="number" name="minimum_score" class="form-control" placeholder="Nilai minimal" value="75" min="0"
                            max="100">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Durasi <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="duration" class="form-control" placeholder="Durasi ujian" value="120" min="1">
                            <span class="input-group-text">(Menit)</span>
                        </div>
                        <span class="text-danger" id="error-duration"></span>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Total Soal <span class="text-danger">*</span></label>
                        <input type="number" name="total_questions" class="form-control" placeholder="Total soal yang akan diujikan" min="0">
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-semibold">
                        Kelas yang bisa mengikuti ujian <span class="text-danger">*</span>
                    </label>

                    <select id="classrooms" name="classroom_ids[]" class="select2 form-select" multiple>
                        <option value="" disabled>Pilih level kelas & tahun ajaran terlebih dahulu</option>
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
                                <input class="form-check-input fs-5" name="allow_early_submission" type="checkbox" id="allow_early_submission"/>
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
                                <input class="form-check-input fs-5" name="can_view_answer_result" type="checkbox" id="can_view_answer_result"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="bg-primary text-white px-3 py-2 rounded-top fw-semibold">
                        Peraturan Wajib
                    </div>
                    <div class="border rounded-bottom p-3">
                        {!! trans('alert.rules.exam') !!}
                    </div>
                </div>

                <div class="mt-4">
                    <label class="form-label fw-semibold">Peraturan Tambahan (Opsional)</label>
                    <textarea id="exam-additional-rules" name="rules" class="form-control"></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('school.exams.index') }}" class="btn btn-outline-secondary">Kembali</a>

                    <button id="btn-save-exam" type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('admin_assets/dist/summernote/dist/summernote-lite.min.js') }}"></script>
    @include('school.pages.exam.scripts.summernotes')
    @include('school.pages.exam.scripts.script-create')
@endsection
