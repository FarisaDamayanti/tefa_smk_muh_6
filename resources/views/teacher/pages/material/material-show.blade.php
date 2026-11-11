@extends('teacher.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/teacherpresence.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/material/show.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Detail Materi"
        breadcrumb="E-learning / Kelas / {{ $classroom->levelClass->name ?? '' }} {{ $classroom->name ?? '' }} - {{ $subject->name ?? '' }} /Topik {{ $topic->title ?? '' }} / Detail Materi" />
    <div class="container-btn-show justify-content-end d-flex mb-4">
        <button type="button" class="btn btn-warning" onclick="goBack()">
            Kembali
        </button>
    </div>

    <div class="card shadow-sm border-0 full-preview">
        <div class="minimal-header p-3" style="background-color: #5f86ff;">
            <h5 id="material-title" class="text-white">{{ $module->title }}</h5>
        </div>

        <div class="card-body p-3">
            <!-- Content Section -->
            <div id="material-content" class="preview-content">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat preview materi...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('teacher.pages.material.scripts.show')
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
@endsection
