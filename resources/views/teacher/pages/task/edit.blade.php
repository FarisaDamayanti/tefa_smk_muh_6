@extends('teacher.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Edit Tugas"
        breadcrumb="E-learning / {{ $task->topic->classroom->levelClass->name ?? '' }} {{ $task->topic->classroom->name ?? '' }} - {{ $task->topic->subject->name ?? '' }} / Topik {{ $task->topic->title ?? '' }} / Edit Tugas" />
    <div class="card shadow-sm">
        <div class="card-header bg-primary px-4">
            <h5 class="text-white mb-0">Tugas</h5>
        </div>
        <div class="card-body">
            <form id="form-edit-task" action="">

                {{-- Judul --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="edit-title-task">Judul Tugas</label>
                    <input id="edit-title-task" name="title" type="text" class="form-control"
                        placeholder="Masukkan Judul Tugas disini.." value="{{ $task->title }}">
                    <div class="invalid-feedback"></div>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="edit-description-task">Deskripsi</label>
                    <textarea id="edit-description-task" name="description" class="form-control"
                        placeholder="Masukkan Deskripsi Tugas Disini..">{!! $task->description !!}</textarea>
                    <div class="invalid-feedback d-block"></div>
                </div>

                {{-- Deadline --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="edit-deadline-task">Tenggat Waktu</label>
                    <input id="edit-deadline-task" name="deadline" type="datetime-local" class="form-control"
                        value="{{ $task->deadline }}">
                    <div class="invalid-feedback"></div>
                </div>

                {{-- Jenis Pengumpulan --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="edit-submission_type-task">Jenis Pengumpulan Tugas</label>
                    <select id="edit-submission_type-task" name="submission_type" class="form-select"
                        {{ $task->taskSubmissions->count() > 0 ? 'disabled' : '' }}>
                        <option value="" disabled>Pilih jenis pengumpulan tugas</option>
                        <option value="file" {{ $task->submission_type->value == 'file' ? 'selected' : '' }}>Unggah Berkas
                        </option>
                        <option value="link" {{ $task->submission_type->value == 'link' ? 'selected' : '' }}>Kirim Tautan
                        </option>
                    </select>

                    @if ($task->taskSubmissions->count() > 0)
                        <small class="text-muted d-block mt-1">
                            <i class="bi bi-info-circle"></i> Tipe pengumpulan tidak dapat diubah karena sudah ada
                            {{ $task->taskSubmissions->count() }} siswa yang mengumpulkan.
                        </small>
                    @endif

                    <div class="invalid-feedback"></div>
                </div>

                {{-- Allow overdue --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="edit-allow_overdue-task">Izinkan Pengumpulan Terlambat <span
                            class="text-danger">*</span></label>

                    <div class="d-flex justify-content-between">
                        <label class="form-check-label" style="width: 500px" for="allow_overdue">Jika anda mengaktifkan opsi
                            ini siswa dapat mengumpulkan jawaban meskipun melebihi deadline.</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="allow_overdue" type="checkbox" id="allow_overdue"
                                {{ $task->allow_overdue ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>

                {{-- Action --}}
                <div class="d-flex justify-content-end gap-2 pt-2">
                    <a href="{{ route('teacher.e-learning.detail', [$task->topic->classroom->id, $task->topic->subject->id]) }}"
                        class="btn btn-outline-secondary">
                        <p class="align-middle mb-0">Kembali</p>
                    </a>
                    <button type="submit" class="btn btn-primary" id="btn-edit-task">
                        <p class="align-middle mb-0">Simpan</p>
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('teacher.pages.task.scripts.summernote')
    @include('teacher.pages.task.scripts.edit')
@endsection
