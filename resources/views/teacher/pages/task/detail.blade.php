@extends('teacher.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/detail.css') }}">
@endsection
@section('content')
    <x-authenticated.breadcrumb title="Detail Tugas"
        breadcrumb="E-learning / {{ $task->topic->classroom->levelClass->name ?? '' }} {{ $task->topic->classroom->name ?? '' }} - {{ $task->topic->subject->name ?? '' }} / Topik {{ $task->topic->title ?? '' }} / Detail Tugas" />
    <meta name="api-token" content="{{ session('api_bearer') ?? '' }}">

    <div class="card">
        <div class="card-header border-bottom border-2 bg-white px-4">
            <div class="row justify-content-between">
                <div class="col-md-auto col-sm-12">
                    <h5 class="text-capitalize">judul tugas</h5>
                    <p class="m-0">{{ $task->title }}</p>
                </div>
                <div class="col-auto row align-items-center gap-2 ps-4 ps-md-0 mt-2 mt-md-0">
                    <span class="badge bg-light-warning text-warning rounded-2 col-auto" style="padding: 12px 10px">Tenggat
                        : {{ $task->deadline->format('d M Y, H:i') }}</span>
                    <a href="{{ route('teacher.e-learning.detail', [$task->topic->classroom->id, $task->topic->subject->id]) }}"
                        class="btn btn-warning col-auto">Kembali</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <h5 class="card-title text-capitalize">deskripsi</h5>
            <div class="card-text">{!! $task->description !!}</div>
        </div>
    </div>

    <div class="card card-body">
        <h4>Daftar Siswa</h4>
        <div class="row mb-3 mt-3">
            <div class="col-lg-7 col-md-12 mb-3">
                <form class="d-flex flex-column flex-md-row gap-2" action="" method="GET">
                    <div class="position-relative flex-grow-1" style="flex: 1.5;">
                        <input type="text" name="search" class="form-control product-search ps-5" id="input-search"
                            placeholder="Cari..." value="{{ old('search', request('search')) }}">
                        <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                    </div>
                    <div class="d-flex flex-column flex-md-row gap-2">
                        <select name="status" class="form-select status-select w-auto">
                            <option value="">Semua status</option>
                            @foreach (App\Enums\SubmissionStatusEnum::cases() as $key => $value)
                                <option value="{{ $value->value }}"
                                    {{ request('status') == $value->value ? 'selected' : '' }}>{{ $value->label() }}
                                    {{ $value->value == 'evaluated_but_beyond_deadline' ? 'dan Terlambat Mengumpulkan' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary w-auto">
                            <i class="ti ti-filter me-2"></i>Filter
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-auto">
                            <i class="ti ti-refresh me-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-lg-5 col-md-12">
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-primary" type="button" id="btn-download-all"
                        data-task-id="{{ $task->id }}">
                        Download Semua
                        <i class="fa-solid fa-download fs-6"></i>
                    </button>
                    <button class="btn btn-primary" id="btn-save-grades">
                        Simpan Perubahan
                        <i class="fa-solid fa-save fs-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="table-responsive rounded-2 mb-4">
            <table class="table border text-nowrap customize-table mb-0 align-middle">
                <thead class="text-dark fs-4">
                    <tr class="">
                        <th class="text-white bg-primary">No</th>
                        <th class="text-white bg-primary">Nama</th>
                        <th class="text-white bg-primary text-center">Kelas</th>
                        <th class="text-white bg-primary text-center">Status</th>
                        <th class="text-white bg-primary text-center">Penilaian</th>
                        <th class="text-white bg-primary text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($task->classroomStudents as $key => $classroomStudent)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $classroomStudent->user_name }}</td>
                            <td class="text-center">{{ $classroomStudent->classroom_name }}</td>
                            <td class="text-center">
                                <span
                                    class="badge bg-light-{{ $classroomStudent->status['color'] }} text-{{ $classroomStudent->status['color'] == 'gray' ? 'muted' : $classroomStudent->status['color'] }}">{{ $classroomStudent->status['label'] }}</span>
                            </td>
                            <td class="text-center">
                                @if ($classroomStudent->status['label'] == 'Belum Mengumpulkan')
                                    <span>-</span>
                                @else
                                    <div
                                        class="d-flex align-items-center justify-content-center gap-2 grade-input-container">
                                        <input type="number" class="form-control grade-input"
                                            value="{{ $classroomStudent->grade }}" id="input-grade-{{ $key }}"
                                            placeholder="Nilai"
                                            data-submission-task-id="{{ $classroomStudent->submission_task_id }}"
                                            data-student-name="{{ $classroomStudent->user_name }}">
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    @if ($classroomStudent->status['label'] == 'Belum Mengumpulkan')
                                        <span>-</span>
                                    @else
                                        @if ($task->submission_type->value == 'file')
                                            <a href="{{ route('api.task-submission.download', ['taskSubmission' => $classroomStudent->submission_task_id]) }}"
                                                class="btn btn-primary btn-sm" title="Unduh File">
                                                <i class="fa-solid fa-download fs-6"></i> </a>
                                        @else
                                            <a href="{{ $classroomStudent->answer }}" target="_blank"
                                                class="btn btn-primary btn-sm" title="Kunjungi Tautan">
                                                <i class="ti ti-link fs-5"></i>
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-success btn-sm btn-add-note" title="Catatan"
                                            data-submission-task-id="{{ $classroomStudent->submission_task_id }}"
                                            data-student-name="{{ $classroomStudent->user_name }}"
                                            data-note="{{ $classroomStudent->note }}">
                                            <i class="ti ti-message fs-5"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center align-middle">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt=""
                                        width="300px">
                                    <p class="fs-5 text-dark text-center mt-2">
                                        Siswa belum ditambahkan
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination justify-content-end mb-0">
        </div>
    </div>

    <!-- Modal Catatan -->
    @include('teacher.pages.task.widgets.note')
@endsection

@section('script')
    @include('teacher.pages.task.scripts.detail')
    @include('teacher.pages.task.scripts.note')
    @include('teacher.pages.task.scripts.save-grades')
@endsection
