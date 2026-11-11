@extends('school.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/dropdown/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/dropdown/school.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb :title="'Edit Bank Soal'" breadcrumb="Edit Data Bank Soal Disini" />

    <div class="card p-4 shadow-sm rounded-4">
        <form id="formQuestionBank" data-id="{{ $questionBank->id }}">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-semibold">Judul Bank Soal<span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-custom" name="title" id="title" required
                    value="{{ $questionBank->title }}" placeholder="Masukkan Judul Bank Soal">
                <div class="invalid-feedback d-block" id="error-title"></div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mata Pelajaran<span class="text-danger">*</span></label>

                    <div class="custom-dropdown" data-dropdown>
                        <input type="hidden" name="subject_id" id="subject_id" required
                            value="{{ $questionBank->subject_id }}">

                        <div class="dropdown-selected">
                            <div class="selected-content">
                                <span class="selected-text">
                                    {{ $questionBank->subject?->name ?? 'Pilih Mata Pelajaran' }}
                                </span>
                            </div>
                            <svg class="dropdown-arrow" viewBox="0 0 24 24">
                                <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>

                        <div class="dropdown-options" data-dropdown-menu>
                            <div class="dropdown-options-content" data-dropdown-options>
                                @foreach ($filterSubjects as $subject)
                                    <div class="dropdown-option" data-value="{{ $subject->id }}">
                                        {{ $subject->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="invalid-feedback d-block" id="error-subject_id"></div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tingkatan Kelas<span class="text-danger">*</span></label>

                    <div class="custom-dropdown" data-dropdown>
                        <input type="hidden" name="level_class_id" id="level_class_id" required
                            value="{{ $questionBank->level_class_id }}">

                        <div class="dropdown-selected">
                            <div class="selected-content">
                                <span class="selected-text">
                                    {{ $questionBank->levelClass?->name ? 'Kelas ' . $questionBank->levelClass->name : 'Pilih Tingkatan Kelas' }}
                                </span>
                            </div>
                            <svg class="dropdown-arrow" viewBox="0 0 24 24">
                                <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>

                        <div class="dropdown-options" data-dropdown-menu>
                            <div class="dropdown-options-content" data-dropdown-options>
                                @foreach ($levelClasses as $level)
                                    <div class="dropdown-option" data-value="{{ $level->id }}">
                                        Kelas {{ $level->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="invalid-feedback d-block" id="error-level_class_id"></div>
                </div>
            </div>
            

            <div class="mt-4 mb-4">
                <label class="form-label fw-semibold">Deskripsi<span class="text-danger">*</span></label>
                <textarea class="form-control form-control-custom" name="description" id="description" rows="5" required placeholder="Masukkan Deskripsi">{{ $questionBank->description }}</textarea>
                <div class="invalid-feedback d-block" id="error-description"></div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Jumlah Soal Yang Harus Diinputkan<span
                        class="text-danger">*</span></label>
                <input type="number" class="form-control form-control-custom" name="question_limit" id="question_limit"
                    min="1" placeholder="Masukkan Jumlah Soal yang harus diinputkan setiap guru" required value="{{ $questionBank->question_limit }}">
                <div class="invalid-feedback d-block" id="error-question_limit"></div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('school.question-bank.index') }}" class="btn btn-outline-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary px-4" id="btnSubmit">
                    <span class="btn-text">Simpan</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                </button>
            </div>
        </form>
    </div>
@endsection


@section('script')
    @include('school.pages.question-bank.scripts.edit')
    @include('school.pages.question-bank.scripts.dropdown')
@endsection
