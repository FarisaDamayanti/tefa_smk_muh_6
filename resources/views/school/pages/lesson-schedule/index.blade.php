@extends('school.layouts.app')

@section('style')
    <style>
        @media (max-width: 991.98px) {
            #input-search {
                width: 50% !important;
                flex: 0 0 50% !important;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Jadwal Pelajaran" breadcrumb="Daftar Jadwal Pelajaran" />

    <div class="row mb-3 align-items-center">
        <div class="col-12">
            <div class="d-flex gap-2 align-items-center">
                <form action="" id="form-filter" class="d-flex gap-2 align-items-center flex-grow-1">
                    <input type="hidden" name="school_year" id="hidden-school-year"
                        value="{{ old('school_year', request()->school_year) }}">

                    <input type="text" name="name" class="form-control product-search" id="input-search"
                        placeholder="Cari...." value="{{ old('name', request()->name) }}">

                    <select name="school_year" class="form-select d-none d-lg-block" id="select-school-year-desktop"
                        style="min-width:180px;">
                        <option value="" {{ !request()->school_year ? 'selected' : '' }}>Pilih Tahun Ajaran</option>
                        @foreach ($schoolYears as $schoolYear)
                            <option value="{{ $schoolYear->school_year }}"
                                {{ old('school_year', request()->school_year) == $schoolYear->school_year ? 'selected' : '' }}>
                                {{ $schoolYear->school_year }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary d-none d-lg-flex align-items-center gap-1">
                        <i class="ti ti-filter fs-4"></i> Filter
                    </button>

                    <button type="button" class="btn btn-primary d-flex d-lg-none align-items-center justify-content-center px-3"
                        data-bs-toggle="modal" data-bs-target="#modal-filter" style="min-width:42px;">
                        <i class="ti ti-filter fs-4"></i>
                    </button>
                </form>

                <button type="button" class="btn btn-success d-flex align-items-center"
                    data-bs-toggle="modal" data-bs-target="#modal-import" style="white-space:nowrap;">
                    Import Jadwal
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse ($classrooms as $classroom)
            <div class="col-lg-4 col-md-6">
                <div class="card border">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h4 class="mb-2"><b>{{ $classroom->levelClass->name }} {{ $classroom->name }}</b></h4>
                            <span class="badge font-medium bg-light-primary text-primary fs-3">
                                {{ $classroom->schoolYear->school_year }}
                            </span>
                        </div>
                        <span class="fs-4">{{ $classroom->employee->user->name }}</span>
                        <div class="d-flex align-items-center mb-4 pt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 16 16">
                                <path fill="currentColor"
                                    d="M15 14s1 0 1-1s-1-4-5-4s-5 3-5 4s1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276c.593.69.758 1.457.76 1.72l-.008.002l-.014.002zM11 7a2 2 0 1 0 0-4a2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0a3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904c.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724c.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0a3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4a2 2 0 0 0 0-4" />
                            </svg>
                            <span class="ms-2 fs-4">{{ $classroom->classroomStudents->count() }} Siswa</span>
                        </div>
                        <a href="{{ route('school.lesson-schedule.detail', ['classroom' => $classroom->id]) }}"
                            class="btn waves-effect waves-light btn-primary w-100">Masuk Kelas</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="d-flex flex-column justify-content-center align-items-center">
                <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt="" width="300px">
                <p class="fs-5 text-dark text-center mt-2">Belum ada data</p>
            </div>
        @endforelse
    </div>

    <div class="modal fade" id="modal-filter" tabindex="-1" aria-labelledby="modalFilterLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFilterLabel">Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Tahun Ajaran</label>
                        <select id="select-school-year-mobile" class="form-select">
                            <option value="">Pilih Tahun Ajaran</option>
                            @foreach ($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->school_year }}"
                                    {{ old('school_year', request()->school_year) == $schoolYear->school_year ? 'selected' : '' }}>
                                    {{ $schoolYear->school_year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btn-apply-filter">Terapkan</button>
                </div>
            </div>
        </div>
    </div>

    @include('school.pages.lesson-schedule.widgets.import')

@endsection

@section('script')
    <script>
        document.getElementById('btn-apply-filter')?.addEventListener('click', function () {
            const schoolYear = document.getElementById('select-school-year-mobile').value;
            document.getElementById('hidden-school-year').value = schoolYear;
            bootstrap.Modal.getInstance(document.getElementById('modal-filter')).hide();
            document.getElementById('form-filter').submit();
        });
    </script>
@endsection