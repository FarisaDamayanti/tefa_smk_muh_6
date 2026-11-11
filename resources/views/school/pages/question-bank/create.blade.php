@extends('school.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/dropdown/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/dropdown/school.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb :title="'Tambah Bank Soal'" breadcrumb="Tambahkan Data Bank Soal Disini" />

    <div class="card p-4 shadow-sm rounded-4">
        <form id="formQuestionBank">
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Judul Bank Soal<span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control form-control-custom" placeholder="Masukkan Judul Bank Soal"
                    name="title" id="title">
                <div class="invalid-feedback d-block" id="error-title"></div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        Mata Pelajaran<span class="text-danger">*</span>
                    </label>
                    <select name="subject_id" id="subject_id" class="form-select select2">
                        <option value="" disabled selected>Pilih Mata Pelajaran</option>
                        <option value="" id="">Tambah Mata Pelajaran</option>
                        @foreach ($filterSubjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback d-block" id="error-subject_id"></div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        Tingkatan Kelas<span class="text-danger">*</span>
                    </label>
                    <select name="level_class_id" id="level_class_id" class="form-select select2">
                        <option value="" disabled selected>Pilih Tingkatan Kelas</option>
                        @foreach ($levelClasses as $levelClass)
                            <option value="{{ $levelClass->id }}">{{ $levelClass->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback d-block" id="error-level_class_id"></div>
                </div>
            </div>

            <div class="mt-4 mb-4">
                <label class="form-label fw-semibold">
                    Deskripsi<span class="text-danger">*</span>
                </label>
                <textarea class="form-control form-control-custom" name="description" id="description" rows="5"
                    placeholder="Masukkan Deskripsi"></textarea>
                <div class="invalid-feedback d-block" id="error-description"></div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Jumlah Soal Yang Harus Diinputkan<span class="text-danger">*</span>
                </label>
                <input type="number" class="form-control form-control-custom" placeholder="Masukkan Jumlah Soal yang harus diinputkan setiap guru"
                    name="question_limit" id="question_limit" min="1">
                <div class="invalid-feedback d-block" id="error-question_limit"></div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('school.question-bank.index') }}" class="btn btn-outline-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary px-4" id="btnSubmit">
                    <span class="btn-text">Simpan</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    @include('school.pages.question-bank.scripts.create')
    @include('school.pages.question-bank.scripts.dropdown')
@endsection
