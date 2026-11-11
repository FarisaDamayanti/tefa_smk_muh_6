@extends('student.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/student/graduate.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb :title="'Kelulusan'" breadcrumb="Pengumuman hasil kelulusan" />

    @if (!$graduationDetail->is_open)
        {{-- show timer countdown --}}
        @include('student.pages.graduation.panes.remaining-time')
    @else
        @include('student.pages.graduation.panes.result')
    @endif
@endsection

@section('script')
    @include('student.pages.graduation.scripts.remaining-time')
@endsection
