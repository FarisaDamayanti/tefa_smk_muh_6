@extends('teacher.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Rekap Nilai Ujian" breadcrumb="Daftar Ujian dengan mapel yang diajar" />



    <div class="d-none d-lg-block my-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <form method="GET" action="{{ url()->current() }}" class="row g-2 align-items-center flex-grow-1">

                <div class="col-lg-auto">
                    <div class="position-relative">
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" class="form-control ps-5" name="search" placeholder="Cari..."
                            value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-lg-auto">
                    <select class="form-select" name="subject_id">
                        <option value="">Mata pelajaran</option>
                        @foreach ($filterSubjects as $subject)
                            <option value="{{ $subject->id }}" @selected(request('subject_id') == $subject->id)>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-auto">
                    <select class="form-select" name="level_class_id">
                        <option value="">Tingkatan kelas</option>
                        @foreach ($filterLevelClasses as $levelClass)
                            <option value="{{ $levelClass->id }}" @selected(request('level_class_id') == $levelClass->id)>
                                {{ $levelClass->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-auto">
                    <select class="form-select" name="school_year_id">
                        <option value="">Tahun Ajaran</option>
                        @foreach ($filterSchoolYears as $schoolYear)
                            <option value="{{ $schoolYear->id }}" @selected(request('school_year_id') == $schoolYear->id)>
                                {{ $schoolYear->school_year ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-filter me-1"></i>
                        Filter
                    </button>
                </div>

                <div class="col-lg-auto">
                    <a href="{{ url()->current() }}" class="btn btn-outline-primary">
                        <i class="ti ti-refresh me-1"></i>
                        Reset
                    </a>
                </div>
            </form>

            <button class="btn btn-success d-flex align-items-center gap-1 btn-export-exam" id="export-button-desktop"
                type="button">
                <i class="ti ti-file-export"></i>
                Ekspor Ujian
            </button>
        </div>
    </div>

    {{-- FILTER MOBILE --}}
    <div class="d-block d-lg-none my-4">
        <div class="d-flex gap-2">
            <x-dynamic-modal-filter title="Filter" :filters="[
                'search' => [
                    'type' => 'text',
                    'id' => 'search',
                    'label' => 'Cari',
                    'placeholder' => 'Cari...',
                    'value' => request('search'),
                ],
                'subject_id' => [
                    'type' => 'select',
                    'id' => 'subject_id',
                    'label' => 'Mata Pelajaran',
                    'value' => request('subject_id'),
                    'options' => $filterSubjects
                        ->mapWithKeys(
                            fn($subject) => [
                                $subject->name => $subject->id,
                            ],
                        )
                        ->toArray(),
                ],
                'level_class_id' => [
                    'type' => 'select',
                    'id' => 'level_class_id',
                    'label' => 'Tingkatan Kelas',
                    'value' => request('level_class_id'),
                    'options' => $filterLevelClasses
                        ->mapWithKeys(
                            fn($levelClass) => [
                                $levelClass->name => $levelClass->id,
                            ],
                        )
                        ->toArray(),
                ],
                'school_year_id' => [
                    'type' => 'select',
                    'id' => 'school_year_id',
                    'label' => 'Tahun Ajaran',
                    'value' => request('school_year_id'),
                    'options' => $filterSchoolYears
                        ->mapWithKeys(
                            fn($schoolYear) => [
                                $schoolYear->school_year ?? '-' => $schoolYear->id,
                            ],
                        )
                        ->toArray(),
                ],
            ]" />

            <button class="btn btn-success d-flex align-items-center gap-1 btn-export-exam" id="export-button-mobile"
                type="button">
                <i class="ti ti-file-export"></i>
                Ekspor
            </button>
        </div>
    </div>


    <div class="card shadow-md border border-1 mb-4">
        <div class="card-body p-4">
            <div class="table-responsive rounded-2 mb-4">
                <table class="table border text-nowrap customize-table mb-0 align-middle">
                    <thead class="text-dark fs-4">
                        <tr class="">
                            <th class="text-white bg-primary">No</th>
                            <th class="text-white bg-primary">Judul Ujian</th>
                            <th class="text-white bg-primary text-center">Mata Pelajaran</th>
                            <th class="text-white bg-primary text-center">Tanggal Ujian</th>
                            <th class="text-white bg-primary text-center">Semester</th>
                            <th class="text-white bg-primary text-center">Jumlah Soal</th>
                            <th class="text-white bg-primary text-center">Siswa Lulus</th>
                            <th class="text-white bg-primary text-center">Siswa Tidak Lulus</th>
                            <th class="text-white bg-primary text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($exams as $exam)
                            <tr>
                                <td>{{ $exams->firstItem() + $loop->index }}</td>
                                <td>{{ $exam->exam->title }}</td>
                                <td class="text-center">
                                    {{ $exam->classroom->levelClass->name ?? '-' }}
                                    -
                                    {{ $exam->exam->questionBank->subject->name ?? '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $exam->exam->exam_date ?? '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $exam->exam->semester ?? '-' }}
                                </td>
                                <td class="text-center">
                                    <div class="px-3 badge bg-light-primary text-primary">
                                        {{ $exam->exam->total_questions ?? 0 }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="px-3 badge bg-light-success text-success">
                                        {{ $exam->exam->passed_students ?? 0 }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="px-3 badge bg-light-danger text-danger">
                                        {{ $exam->exam->not_passed_students ?? 0 }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('teacher.exam-monitoring.detail', $exam->exam->id) }}"
                                        class="btn btn-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-0">
                                    <div class="d-flex flex-column justify-content-center align-items-center py-5">
                                        <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}"
                                            alt="No data" width="300">
                                        <p class="fs-5 text-dark text-center mt-2 mb-0">Belum ada ujian</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <x-paginate-component :paginator="$exams" />
    </div>
@endsection

@section('script')
    @include('teacher.pages.exam-monitoring.scripts.index')
@endsection
