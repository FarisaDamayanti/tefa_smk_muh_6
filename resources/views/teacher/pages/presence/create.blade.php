@extends('teacher.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/teacherpresence.css') }}">
@endsection

@section('content')
    {{-- META untuk JS --}}
    <meta name="teacher-journal" content="{{ $teacherJournal ?? request()->route('teacherJournal') }}">
    <meta name="api-token" content="{{ session('api_token') ?? '' }}">

    <div class="card header-gradient shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold text-white mb-8">Presensi Pertemuan</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a class="text-white text-decoration-none" href="javascript:void(0)">
                                    Manajemen kehadiran tiap pertemuan dengan fitur ini
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('admin_assets/dist/images/breadcrumb/ChatBc.png') }}" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card presensi-card">
        <div class="card-body">
            <h6 class="presensi-title">Presensi Siswa</h6>

            @php
                // anti dobel baris (karena response API kamu bisa duplikat)
                $uniqueStudents = collect($students ?? [])
                    ->unique('id')
                    ->values();
            @endphp

            <div class="table-responsive">
                <table class="table presensi-table">
                    <thead>
                        <tr>
                            <th style="width: 60px">No</th>
                            <th>Nama Siswa</th>
                            <th style="width: 140px">Kelas</th>
                            <th style="width: 360px">
                                Status Kehadiran
                                <label class="hadir-semua">
                                    <input type="checkbox" id="checkAll">
                                    Hadir Semua
                                </label>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($uniqueStudents as $idx => $student)
                            @php
                                // id yang dikirim ke POST -> classroom_student_id
                                $classroomStudentId = $student['id'] ?? null;
                                $studentName = $student['student'] ?? '-';
                                $className = $student['classroom']['name'] ?? '-';
                            @endphp

                            <tr data-student-id="{{ $classroomStudentId }}">
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $studentName }}</td>
                                <td>{{ $className }}</td>
                                <td>
                                    <div class="radio-group">
                                        <label>
                                            <input type="radio" name="status{{ $idx }}" value="present"
                                                class="radio-hadir">
                                            Hadir
                                        </label>

                                        <label>
                                            <input type="radio" name="status{{ $idx }}" value="permit">
                                            Izin
                                        </label>

                                        <label>
                                            <input type="radio" name="status{{ $idx }}" value="sick">
                                            Sakit
                                        </label>

                                        <label>
                                            <input type="radio" name="status{{ $idx }}" value="alpha">
                                            Alpha
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Data siswa belum tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="presensi-footer">
                <button class="btn btn-outline-secondary" type="button" onclick="history.back()">Batal</button>
                <button class="btn btn-primary" id="btnSaveAttendance" type="button">Simpan</button>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('teacher.pages.presence.scripts.create')
@endsection
