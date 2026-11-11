@extends('teacher.layouts.app')
@section('style')
<link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection
@section('content')
<x-authenticated.breadcrumb title="Profil" breadcrumb="Edit Profilmu Disini" />

<ul class="nav nav-pills p-3 mb-3 rounded align-items-center card flex-row" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ request('tab', 'profile') === 'profile' ? 'active' : '' }}" href="{{ url()->current() }}?tab=profile">
            Profil
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request('tab') === 'password' ? 'active' : '' }}" href="{{ url()->current() }}?tab=password">
            Reset Kata Sandi
        </a>
    </li>
</ul>


<div class="tab-content mt-4">
    <div class="tab-pane fade 
    {{ request('tab', 'profile') === 'profile' ? 'show active' : '' }}" id="pills-edit-profile">
        @include('teacher.pages.profile.panes.edit-profile')
    </div>

    <div class="tab-pane fade 
    {{ request('tab') === 'password' ? 'show active' : '' }}" id="pills-reset-password">
        @include('teacher.pages.profile.panes.reset-password')
    </div>
</div>


@endsection
