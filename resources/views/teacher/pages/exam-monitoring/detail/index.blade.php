@extends('teacher.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/detail-exam.css') }}">
@endsection

@section('content')
    {{-- HEADER --}}
    <x-authenticated.breadcrumb title="Detail Ujian" breadcrumb="Detail Ujian Kenaikan Kelas" />

    {{-- TAB SECTION --}}
    <div class="card shadow-sm border border-1 mb-4">
        <div class="card-body p-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <ul class="nav nav-pills gap-3 m-0" id="examTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active text-capitalize" data-bs-toggle="tab" href="#description" role="tab"
                            aria-selected="true">
                            <span>Deskripsi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-capitalize" data-bs-toggle="tab" href="#student-answer" role="tab"
                            aria-selected="true">
                            <span>Jawaban Siswa</span>
                        </a>
                    </li>
                </ul>

                <a href="javascript:void(0)" onclick="window.history.back()"
                    class="btn btn-warning d-inline-flex align-items-center gap-2">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="tab-content">

        {{-- TAB DESKRIPSI --}}
        <div class="tab-pane fade show active" id="description">
            @include('teacher.pages.exam-monitoring.detail.panes.description')
        </div>

        {{-- TAB JAWABAN SISWA --}}
        <div class="tab-pane fade" id="student-answer">
            @include('teacher.pages.exam-monitoring.detail.panes.student-answer')
        </div>
    </div>
@endsection
@section('script')
    @include('teacher.pages.exam-monitoring.detail.scripts.handle-tab')
    @include('teacher.pages.exam-monitoring.detail.scripts.chart')
    @include('teacher.pages.exam-monitoring.detail.scripts.export')
@endsection
