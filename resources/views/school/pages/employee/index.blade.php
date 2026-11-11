@extends('school.layouts.app')
@section('content')
    <div class="card bg-primary shadow-none position-relative overflow-hidden text-light">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8 text-light">Pegawai</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item" aria-current="page">Daftar - daftar guru dan staf di sekolah</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('admin_assets/dist/images/breadcrumb/ChatBc.png') }}" alt=""
                            class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <div class="card rounded-3 card-hover border position-relative">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-light-success text-success d-inline-block px-3 py-3 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mb-1 me-1" width="40" height="40"
                                viewBox="0 0 16 16">
                                <path fill="currentColor"
                                    d="M15 14s1 0 1-1s-1-4-5-4s-5 3-5 4s1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276c.593.69.758 1.457.76 1.72l-.008.002l-.014.002zM11 7a2 2 0 1 0 0-4a2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0a3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904c.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724c.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0a3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4a2 2 0 0 0 0-4" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <h4 class="card-title text-dark"><b>Jumlah Guru</b></h4>
                            <h6 style="font-size: 25px; color: #13DEB9">{{ $teachers->count() }}</h6>
                        </div>
                    </div>
                </div>
                <img src="{{ asset('assets/images/background/buble-2.png') }}" alt="Image"
                    style="position: absolute; bottom: 0; right: 0; width: auto; height: 90px; border-bottom-right-radius: 13px;">
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="card rounded-3 card-hover border position-relative">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-light-warning text-warning d-inline-block px-3 py-3 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 32 32">
                                <path fill="currentColor"
                                    d="m16 4.875l-.469.25l-13.5 7L.312 13L2 13.844v8.437c-.598.348-1 .98-1 1.719a1.999 1.999 0 1 0 4 0c0-.738-.402-1.371-1-1.719v-7.406l2 1.031V21c0 .441.203.84.438 1.094s.519.406.812.562c.59.309 1.29.528 2.156.719c1.735.387 4.047.625 6.594.625s4.86-.238 6.594-.625c.867-.191 1.566-.41 2.156-.719c.293-.156.578-.308.813-.562A1.66 1.66 0 0 0 26 21v-5.094l3.969-2.031L31.687 13l-1.718-.875l-13.5-7zm0 2.25L27.313 13l-1.782.906a3 3 0 0 0-.781-.562c-.586-.309-1.29-.528-2.156-.719C20.864 12.238 18.559 12 16 12s-4.863.238-6.594.625c-.867.191-1.57.41-2.156.719a3 3 0 0 0-.781.562L4.687 13zM16 14c2.441 0 4.637.223 6.156.563c.758.167 1.367.363 1.688.53c.101.055.117.095.156.126v3.812a11 11 0 0 0-1.406-.406C20.859 18.238 18.547 18 16 18s-4.86.238-6.594.625c-.531.117-.988.254-1.406.406V15.22c.04-.031.055-.07.156-.125c.32-.168.93-.364 1.688-.531C11.364 14.223 13.559 14 16 14m0 6c2.426 0 4.633.223 6.156.563a7 7 0 0 1 1.375.437a7 7 0 0 1-1.375.438c-1.523.34-3.73.562-6.156.562s-4.633-.223-6.156-.563A7 7 0 0 1 8.469 21a7 7 0 0 1 1.375-.438C11.367 20.223 13.574 20 16 20" />
                            </svg>
                        </div>
                        <div class="ms-4">
                            <h4 class="card-title text-dark"><b>Jumlah Staf</b></h4>
                            <h6 style="font-size: 25px; color: #FFAE1F">{{ $staffs->count() }}</h6>
                        </div>
                    </div>
                </div>
                <img src="{{ asset('assets/images/background/buble-3.png') }}" alt="Image"
                    style="position: absolute; bottom: 0; right: 0; width: auto; height: 90px; border-bottom-right-radius: 13px;">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-6">
            {{-- stat cards same as before --}}
        </div>
        <div class="col-md-6 col-lg-6">
            {{-- stat cards same as before --}}
        </div>
    </div>

    {{-- Navigation Tabs --}}
    <ul class="nav nav-pills p-2 mb-3 rounded align-items-center card flex-row flex-nowrap gap-1" id="nav-tab" role="tablist">
        {{-- Tab: Guru (icon only on mobile) --}}
        <li class="nav-item flex-shrink-0">
            <a class="nav-link note-link d-flex align-items-center justify-content-center px-3 text-body-color"
                id="teacher-tab" data-bs-toggle="pill" href="#teacher-content" role="tab"
                aria-controls="teacher-content" aria-selected="true" title="Guru">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 32 32">
                    <path fill="currentColor" d="M4 6v2h22v16H12v2h18v-2h-2V6zm4.002 3A4.016 4.016 0 0 0 4 13c0 2.199 1.804 4 4.002 4A4.014 4.014 0 0 0 12 13c0-2.197-1.802-4-3.998-4M14 10v2h5v-2zm7 0v2h3v-2zM8.002 11C9.116 11 10 11.883 10 13c0 1.12-.883 2-1.998 2C6.882 15 6 14.12 6 13c0-1.117.883-2 2.002-2M14 14v2h10v-2zM4 18v8h2v-6h3v6h2v-5.342l2.064 1.092c.585.31 1.288.309 1.872 0v.002l3.53-1.867l-.933-1.77l-3.531 1.867l-3.096-1.634A3.005 3.005 0 0 0 9.504 18z"/>
                </svg>
                <span class="ms-1 font-weight-medium">Guru</span>
            </a>
        </li>

        {{-- Tab: Staf (icon only on mobile) --}}
        <li class="nav-item flex-shrink-0">
            <a class="nav-link note-link d-flex align-items-center justify-content-center px-3 text-body-color"
                id="employee-tab" data-bs-toggle="pill" href="#employee-content" role="tab"
                aria-controls="employee-content" aria-selected="false" title="Staf">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 36 36">
                    <path fill="currentColor" d="M16.43 16.69a7 7 0 1 1 7-7a7 7 0 0 1-7 7m0-11.92a5 5 0 1 0 5 5a5 5 0 0 0-5-5M22 17.9a25.4 25.4 0 0 0-16.12 1.67a4.06 4.06 0 0 0-2.31 3.68v5.95a1 1 0 1 0 2 0v-5.95a2 2 0 0 1 1.16-1.86a22.9 22.9 0 0 1 9.7-2.11a23.6 23.6 0 0 1 5.57.66Zm.14 9.51h6.14v1.4h-6.14z"/>
                    <path fill="currentColor" d="M33.17 21.47H28v2h4.17v8.37H18v-8.37h6.3v.42a1 1 0 0 0 2 0V20a1 1 0 0 0-2 0v1.47H17a1 1 0 0 0-1 1v10.37a1 1 0 0 0 1 1h16.17a1 1 0 0 0 1-1V22.47a1 1 0 0 0-1-1"/>
                </svg>
                <span class="ms-1 font-weight-medium">Staf</span>
            </a>
        </li>

        {{-- Action buttons (auto margin-left pushes to right) --}}
        <li class="nav-item d-flex align-items-center gap-2 ms-auto guru-buttons">
            <button type="button" class="btn btn-success px-3 d-flex align-items-center gap-1"
                data-bs-toggle="modal" data-bs-target="#import-teacher">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <span class="d-none d-md-inline">Import</span>
            </button>
            <button type="button" class="btn btn-primary px-3 d-flex align-items-center gap-1"
                data-bs-toggle="modal" data-bs-target="#create-teacher">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                <span class="d-none d-md-inline">Tambah</span>
            </button>
        </li>

        <li class="nav-item d-flex align-items-center gap-2 ms-auto pegawai-buttons d-none">
            <button type="button" class="btn btn-success px-3 d-flex align-items-center gap-1"
                data-bs-toggle="modal" data-bs-target="#import-employe">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <span class="d-none d-md-inline">Import</span>
            </button>
            <button type="button" class="btn btn-primary px-3 d-flex align-items-center gap-1"
                data-bs-toggle="modal" data-bs-target="#modal-add-emplo">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                <span class="d-none d-md-inline">Tambah</span>
            </button>
        </li>
    </ul>


    {{-- Tab Content --}}
    <div class="tab-content mt-3" id="nav-tabContent">
        <div class="tab-pane fade" id="teacher-content" role="tabpanel" aria-labelledby="teacher-tab">
            @include('school.pages.employee.panes.teacher-tab')
        </div>
        <div class="tab-pane fade" id="employee-content" role="tabpanel" aria-labelledby="employee-tab">
            @include('school.pages.employee.panes.employee-tab')
        </div>
    </div>

    @include('school.pages.employee.widgets.employe.update-employe')
    @include('school.pages.employee.widgets.employe.modal-detail')
    @include('school.pages.employee.widgets.teacher.update-teacher')
    @include('school.pages.employee.widgets.teacher.modal-rfid')
    @include('school.pages.employee.widgets.teacher.import-teacher')
    @include('school.pages.employee.widgets.employe.import-employe')
    @include('school.pages.employee.widgets.employe.add-employe')
    @include('school.pages.employee.widgets.teacher.add-teacher')
    @include('school.pages.employee.widgets.teacher.modal-detail')

    <x-delete-modal-component />
@endsection

@section('script')
    @include('school.pages.employee.scripts.tab')
    @include('school.pages.employee.scripts.rfid')
    @include('school.pages.employee.scripts.detail')
    @include('school.pages.employee.scripts.delete')
    @include('school.pages.employee.scripts.create')
    @include('school.pages.employee.scripts.edit')
    @include('school.pages.employee.scripts.session')
@endsection