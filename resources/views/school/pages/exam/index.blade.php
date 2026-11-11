@extends('school.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/exam-list.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb :title="'Daftar Ujian'" breadcrumb="Kelola ujian dengan lebih cepat dan terstruktur." />

    <div class="mt-3 mb-4">
        <div class="row">
            <div class="col-12">
                <form class="d-flex align-items-center gap-2 justify-content-between" style="height: 40px;">
                    <div class="col-12 col-md-6 d-flex align-items-center gap-2 justify-content-between h-100">
                        <input type="text" name="search" id="search" class="form-control h-100" placeholder="Cari..." value="{{ request('search') }}">

                        <button type="button" class="btn btn-primary d-flex align-items-center rounded-2 h-100" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="ti ti-filter fs-4"></i>
                            <span class="d-none ms-2 d-md-block">Filter</span>
                        </button>

                        <a class="btn btn-outline-primary h-100 d-flex align-items-center h-100" href="{{ route('school.exams.index') }}">
                            <i class="ti ti-refresh fs-4"></i>
                            <span class="d-none ms-2 d-md-block">Reset</span>
                        </a>

                        <ul class="dropdown h-100 mb-0 d-block d-md-none h-100">
                            <button type="button" class="btn btn-primary h-100 d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-menu-2 fs-4"></i>
                            </button>
                            <ul class="dropdown-menu z-1">
                                @if ($exams->count() > 0)
                                    <li>
                                        <button class="dropdown-item export-button" type="button">
                                            <i class="ti ti-download me-2"></i> Ekspor Ujian 
                                        </button>
                                    </li>
                                @endif
                                <li>
                                    <a href="{{ route('school.exams.create') }}"
                                        class="dropdown-item">
                                        <i class="ti ti-plus me-2"></i> Tambah Ujian
                                    </a>
                                </li>
                            </ul>
                        </ul>
                    </div>

                    <div class="col-6 d-flex align-items-center gap-2 justify-content-end">
                        @if ($exams->count() > 0)
                            <div class="col-6 col-lg-auto">
                                <button class="btn btn-success w-100 export-button" type="button">
                                    <i class="ti ti-download"></i> Ekspor Ujian
                                </button>
                            </div>
                        @endif

                        <div class="d-md-block d-none h-100">
                            <a href="{{ route('school.exams.create') }}"
                                class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-1">
                                <i class="ti ti-plus"></i> Tambah Ujian
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="row g-4 mb-5">
        @forelse ($exams as $exam)
            <div class="col-12 col-lg-6">
                <div class="exam-card2 h-100">

                    {{-- HEAD --}}
                    <div class="exam-card2__head">
                        <div class="exam-card2__head-left">
                            <div class="exam-doc2">
                                <svg width="29" height="29" viewBox="0 0 29 29" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M26.125 5.54167C25.4951 5.54167 24.891 5.79189 24.4456 6.23729C24.0002 6.68269 23.75 7.28678 23.75 7.91667V9.5H28.5V7.91667C28.5 7.28678 28.2498 6.68269 27.8044 6.23729C27.359 5.79189 26.7549 5.54167 26.125 5.54167ZM28.5 11.0833H23.75V24.1458L26.125 27.7083L28.5 24.1458V11.0833ZM0 2.375V26.125C0 26.7549 0.250223 27.359 0.695621 27.8044C1.14102 28.2498 1.74511 28.5 2.375 28.5H19.7917C20.4216 28.5 21.0256 28.2498 21.471 27.8044C21.9164 27.359 22.1667 26.7549 22.1667 26.125V2.375C22.1667 1.74511 21.9164 1.14102 21.471 0.695621C21.0256 0.250223 20.4216 0 19.7917 0H2.375C1.74511 0 1.14102 0.250223 0.695621 0.695621C0.250223 1.14102 0 1.74511 0 2.375ZM11.0833 7.125C11.0833 6.91504 11.1667 6.71367 11.3152 6.56521C11.4637 6.41674 11.665 6.33333 11.875 6.33333H18.2083C18.4183 6.33333 18.6197 6.41674 18.7681 6.56521C18.9166 6.71367 19 6.91504 19 7.125C19 7.33496 18.9166 7.53633 18.7681 7.68479C18.6197 7.83326 18.4183 7.91667 18.2083 7.91667H11.875C11.665 7.91667 11.4637 7.83326 11.3152 7.68479C11.1667 7.53633 11.0833 7.33496 11.0833 7.125ZM11.875 9.5C11.665 9.5 11.4637 9.58341 11.3152 9.73187C11.1667 9.88034 11.0833 10.0817 11.0833 10.2917C11.0833 10.5016 11.1667 10.703 11.3152 10.8515C11.4637 10.9999 11.665 11.0833 11.875 11.0833H18.2083C18.4183 11.0833 18.6197 10.9999 18.7681 10.8515C18.9166 10.703 19 10.5016 19 10.2917C19 10.0817 18.9166 9.88034 18.7681 9.73187C18.6197 9.58341 18.4183 9.5 18.2083 9.5H11.875ZM11.0833 17.4167C11.0833 17.2067 11.1667 17.0053 11.3152 16.8569C11.4637 16.7084 11.665 16.625 11.875 16.625H18.2083C18.4183 16.625 18.6197 16.7084 18.7681 16.8569C18.9166 17.0053 19 17.2067 19 17.4167C19 17.6266 18.9166 17.828 18.7681 17.9765C18.6197 18.1249 18.4183 18.2083 18.2083 18.2083H11.875C11.665 18.2083 11.4637 18.1249 11.3152 17.9765C11.1667 17.828 11.0833 17.6266 11.0833 17.4167ZM11.875 19.7917C11.665 19.7917 11.4637 19.8751 11.3152 20.0235C11.1667 20.172 11.0833 20.3734 11.0833 20.5833C11.0833 20.7933 11.1667 20.9947 11.3152 21.1431C11.4637 21.2916 11.665 21.375 11.875 21.375H18.2083C18.4183 21.375 18.6197 21.2916 18.7681 21.1431C18.9166 20.9947 19 20.7933 19 20.5833C19 20.3734 18.9166 20.172 18.7681 20.0235C18.6197 19.8751 18.4183 19.7917 18.2083 19.7917H11.875ZM4.75 17.4167V19.7917H7.125V17.4167H4.75ZM3.95833 15.8333H7.91667C8.12663 15.8333 8.32799 15.9167 8.47646 16.0652C8.62493 16.2137 8.70833 16.415 8.70833 16.625V20.5833C8.70833 20.7933 8.62493 20.9947 8.47646 21.1431C8.32799 21.2916 8.12663 21.375 7.91667 21.375H3.95833C3.74837 21.375 3.54701 21.2916 3.39854 21.1431C3.25007 20.9947 3.16667 20.7933 3.16667 20.5833V16.625C3.16667 16.415 3.25007 16.2137 3.39854 16.0652C3.54701 15.9167 3.74837 15.8333 3.95833 15.8333ZM9.26804 7.68471C9.41225 7.5354 9.49205 7.33542 9.49024 7.12785C9.48844 6.92028 9.40518 6.72172 9.2584 6.57494C9.11162 6.42815 8.91306 6.3449 8.70548 6.34309C8.49791 6.34129 8.29793 6.42108 8.14862 6.56529L5.54167 9.17225L4.51804 8.14862C4.36873 8.00442 4.16876 7.92462 3.96118 7.92642C3.75361 7.92823 3.55505 8.01149 3.40827 8.15827C3.26149 8.30505 3.17823 8.50361 3.17642 8.71118C3.17462 8.91876 3.25442 9.11873 3.39863 9.26804L5.54167 11.4111L9.26804 7.68471Z"
                                        fill="#5D87FF" />
                                </svg>
                            </div>

                            <div class="exam-card2__title-wrap">
                                <div class="exam-card2__title">
                                    {{ $exam->title ?? '-' }}
                                </div>
                                <span class="exam-badge2">
                                    Mata Pelajaran {{ $exam->questionBank?->subject?->name ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- META --}}
                    <div class="exam-meta-wrap">
                        <div class="row gy-3">

                            {{-- Tahun Ajaran --}}
                            <div class="col-12 col-md-5">
                                <div class="exam-meta2">
                                    <span class="exam-meta2__icon">
                                        <svg width="16" height="14" viewBox="0 0 16 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 1H14.3333M1 12.6667H14.3333" stroke="#FFB649" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path
                                                d="M9.03971 5.07031H4.03971C3.11924 5.07031 2.37305 5.8165 2.37305 6.73698C2.37305 7.65745 3.11924 8.40365 4.03971 8.40365H9.03971C9.96019 8.40365 10.7064 7.65745 10.7064 6.73698C10.7064 5.8165 9.96019 5.07031 9.03971 5.07031Z"
                                                stroke="#FFB649" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    <span class="exam-meta2__label">Tahun Ajaran</span>
                                    <span class="exam-meta2__sep">:</span>
                                    <span class="exam-meta2__value">
                                        {{ $exam->schoolYear?->school_year ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Tanggal & Waktu --}}
                            <div class="col-12 col-md-7">
                                <div class="exam-meta2">
                                    <span class="exam-meta2__icon">
                                        <svg width="10" height="11" viewBox="0 0 10 11" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.75 9.75V2.16667C9.75 1.56921 9.26413 1.08333 8.66667 1.08333H7.58333V0H6.5V1.08333H3.25V0H2.16667V1.08333H1.08333C0.485875 1.08333 0 1.56921 0 2.16667V9.75C0 10.3475 0.485875 10.8333 1.08333 10.8333H8.66667C9.26413 10.8333 9.75 10.3475 9.75 9.75ZM3.25 8.66667H2.16667V7.58333H3.25V8.66667ZM3.25 6.5H2.16667V5.41667H3.25V6.5ZM5.41667 8.66667H4.33333V7.58333H5.41667V8.66667ZM5.41667 6.5H4.33333V5.41667H5.41667V6.5ZM7.58333 8.66667H6.5V7.58333H7.58333V8.66667ZM7.58333 6.5H6.5V5.41667H7.58333V6.5ZM8.66667 3.79167H1.08333V2.70833H8.66667V3.79167Z"
                                                fill="#FF8B25" />
                                        </svg>
                                    </span>
                                    <span class="exam-meta2__label">Tanggal &amp; Waktu</span>
                                    <span class="exam-meta2__sep">:</span>
                                    <span class="exam-meta2__value">
                                        {{ $exam->exam_date ? $exam->exam_date->translatedFormat('d F Y - H.i') : '-' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Semester --}}
                            <div class="col-12 col-md-5">
                                <div class="exam-meta2">
                                    <span class="exam-meta2__icon">
                                        <svg width="17" height="16" viewBox="0 0 17 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M0 2.6C0 1.91044 0.265499 1.24912 0.738091 0.761523C1.21068 0.273928 1.85165 0 2.52 0H14.28C14.9483 0 15.5893 0.273928 16.0619 0.761523C16.5345 1.24912 16.8 1.91044 16.8 2.6V13C16.8 13.6896 16.5345 14.3509 16.0619 14.8385C15.5893 15.3261 14.9483 15.6 14.28 15.6H2.52C1.85165 15.6 1.21068 15.3261 0.738091 14.8385C0.265499 14.3509 0 13.6896 0 13V2.6ZM4.2 3.46667C3.97722 3.46667 3.76356 3.55798 3.60603 3.72051C3.4485 3.88304 3.36 4.10348 3.36 4.33333V7.8C3.36 8.02985 3.4485 8.25029 3.60603 8.41283C3.76356 8.57536 3.97722 8.66667 4.2 8.66667H7.56C7.78278 8.66667 7.99644 8.57536 8.15397 8.41283C8.3115 8.25029 8.4 8.02985 8.4 7.8V4.33333C8.4 4.10348 8.3115 3.88304 8.15397 3.72051C7.99644 3.55798 7.78278 3.46667 7.56 3.46667H4.2ZM5.04 6.93333V5.2H6.72V6.93333H5.04ZM10.92 3.46667C10.6972 3.46667 10.4836 3.55798 10.326 3.72051C10.1685 3.88304 10.08 4.10348 10.08 4.33333C10.08 4.56319 10.1685 4.78363 10.326 4.94616C10.4836 5.10869 10.6972 5.2 10.92 5.2H12.6C12.8228 5.2 13.0364 5.10869 13.194 4.94616C13.3515 4.78363 13.44 4.56319 13.44 4.33333C13.44 4.10348 13.3515 3.88304 13.194 3.72051C13.0364 3.55798 12.8228 3.46667 12.6 3.46667H10.92ZM10.92 6.93333C10.6972 6.93333 10.4836 7.02464 10.326 7.18717C10.1685 7.34971 10.08 7.57015 10.08 7.8C10.08 8.02985 10.1685 8.25029 10.326 8.41283C10.4836 8.57536 10.6972 8.66667 10.92 8.66667H12.6C12.8228 8.66667 13.0364 8.57536 13.194 8.41283C13.3515 8.25029 13.44 8.02985 13.44 7.8C13.44 7.57015 13.3515 7.34971 13.194 7.18717C13.0364 7.02464 12.8228 6.93333 12.6 6.93333H10.92ZM4.2 10.4C3.97722 10.4 3.76356 10.4913 3.60603 10.6538C3.4485 10.8164 3.36 11.0368 3.36 11.2667C3.36 11.4965 3.4485 11.717 3.60603 11.8795C3.76356 12.042 3.97722 12.1333 4.2 12.1333H12.6C12.8228 12.1333 13.0364 12.042 13.194 11.8795C13.3515 11.717 13.44 11.4965 13.44 11.2667C13.44 11.0368 13.3515 10.8164 13.194 10.6538C13.0364 10.4913 12.8228 10.4 12.6 10.4H4.2Z"
                                                fill="#13DEB9" />
                                        </svg>
                                    </span>
                                    <span class="exam-meta2__label">Semester</span>
                                    <span class="exam-meta2__sep">:</span>
                                    <span class="exam-meta2__value">
                                        {{ $exam->semester === 'ganjil' ? 'Ganjil' : ($exam->semester === 'genap' ? 'Genap' : '-') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Tingkatan Kelas --}}
                            <div class="col-12 col-md-7">
                                <div class="exam-meta2">
                                    <span class="exam-meta2__icon">
                                        <svg width="11" height="11" viewBox="0 0 11 11" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.2847 1.64585L5.40973 0.0208504C5.32633 -0.00695014 5.23617 -0.00695014 5.15277 0.0208504L0.277773 1.64585C0.196881 1.67282 0.126525 1.72455 0.0766713 1.79373C0.0268174 1.8629 -6.31834e-06 1.94601 1.11635e-09 2.03128V6.09378C1.11635e-09 6.20152 0.0428012 6.30486 0.118988 6.38104C0.195175 6.45723 0.298506 6.50003 0.40625 6.50003C0.513994 6.50003 0.617325 6.45723 0.693512 6.38104C0.769699 6.30486 0.8125 6.20152 0.8125 6.09378V2.59495L2.51824 3.16319C2.06505 3.89536 1.92094 4.77745 2.11755 5.61578C2.31416 6.45411 2.83542 7.18015 3.56687 7.63448C2.65281 7.993 1.86266 8.64147 1.28477 9.52812C1.25471 9.57278 1.23384 9.62298 1.22335 9.67579C1.21287 9.7286 1.21298 9.78296 1.2237 9.83572C1.23441 9.88849 1.2555 9.93859 1.28575 9.98313C1.316 10.0277 1.35479 10.0658 1.39989 10.0952C1.44498 10.1246 1.49547 10.1447 1.54843 10.1545C1.60138 10.1642 1.65574 10.1633 1.70834 10.1518C1.76094 10.1404 1.81074 10.1186 1.85485 10.0877C1.89895 10.0568 1.93647 10.0175 1.96523 9.97194C2.73051 8.79788 3.9391 8.12503 5.28125 8.12503C6.6234 8.12503 7.83199 8.79788 8.59727 9.97194C8.65685 10.0605 8.74889 10.1221 8.8535 10.1433C8.9581 10.1645 9.06686 10.1437 9.15626 10.0854C9.24566 10.0271 9.30852 9.93597 9.33125 9.83168C9.35399 9.72739 9.33476 9.61834 9.27773 9.52812C8.69984 8.64147 7.90664 7.993 6.99562 7.63448C7.72638 7.18017 8.24715 6.4546 8.44373 5.61688C8.6403 4.77917 8.49665 3.89768 8.04426 3.16573L10.2847 2.41925C10.3656 2.3923 10.436 2.34057 10.4859 2.27139C10.5358 2.20222 10.5626 2.1191 10.5626 2.03382C10.5626 1.94854 10.5358 1.86542 10.4859 1.79625C10.436 1.72707 10.3656 1.67534 10.2847 1.64839V1.64585ZM7.71875 4.87503C7.71886 5.26039 7.6276 5.64028 7.45246 5.98353C7.27732 6.32679 7.02329 6.62363 6.71121 6.8497C6.39914 7.07577 6.03791 7.22463 5.65716 7.28406C5.27641 7.34349 4.887 7.3118 4.52088 7.19159C4.15475 7.07138 3.82234 6.86607 3.55092 6.59251C3.27951 6.31896 3.07682 5.98495 2.95949 5.61789C2.84215 5.25083 2.81352 4.86118 2.87594 4.48091C2.93835 4.10065 3.09004 3.7406 3.31855 3.4303L5.15277 4.03968C5.23617 4.06748 5.32633 4.06748 5.40973 4.03968L7.24395 3.4303C7.55256 3.84876 7.71896 4.35509 7.71875 4.87503Z"
                                                fill="#5D87FF" />
                                        </svg>
                                    </span>
                                    <span class="exam-meta2__label">Tingkatan Kelas</span>
                                    <span class="exam-meta2__sep">:</span>
                                    <span class="exam-meta2__value">
                                        {{ $exam->questionBank?->levelClass?->name ?? '-' }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="exam-card2__footer">
                        <div class="exam-classes2">
                            <div class="exam-classes2__title">Kelas yang bisa mengikuti :</div>

                            <div class="d-flex flex-wrap gap-2">
                                @forelse(collect($exam->examClassrooms ?? [])  ->map(fn($ec) => trim(    (($ec->classroom?->levelClass?->name ? $ec->classroom?->levelClass?->name.' ' : '') .   ($ec->classroom?->name ?? ''))  ))      ->filter(fn($v) => $v !== '')     ->values() as $label)
                                    <span class="exam-chip2">{{ $label }}</span>
                                @empty
                                    <span class="exam-chip2">-</span>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 align-items-center">
                        <a href="{{ route('school.exams.show', $exam) }}"
                            class="btn btn-primary flex-grow-1 rounded-2 fw-semibold py-2">
                            Lihat Detail
                        </a>
                        <a href="{{ route('school.exams.edit', $exam) }}"
                            class="btn rounded-2 d-flex align-items-center justify-content-center"
                            style="width:46px;height:38px;background:#F4B740;border:0;">
                            <i class="ti ti-pencil fs-4 text-white"></i>
                        </a>
                        <button type="button"
                            class="btn rounded-2 d-flex align-items-center justify-content-center btn-delete-exam"
                            data-bs-toggle="modal" data-bs-target="#exam-modal" data-id="{{ $exam->id }}"
                            style="width:46px;height:38px;background:#FF6B6B;border:0;">
                            <i class="ti ti-trash fs-4 text-white"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="d-flex flex-column justify-content-center align-items-center py-5">
                    <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt="" width="300px">
                    <p class="fs-5 text-dark text-center mt-2 mb-0">Belum ada ujian</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    @if ($exams->hasPages())
        <div class="d-flex justify-content-end mt-3">
            <x-paginate-component :paginator="$exams" />
        </div>
    @endif

    <x-confirmation.delete id="exam" title="Apakah anda yakin ingin menghapus ujian ini?"
        description="Ujian yang sudah dihapus tidak dapat dikembalikan" button="Hapus" />

    <x-dynamic-modal-filter 
        :title="'Filter Ujian'"
        :filters="[
            'school_year_id' => [
                'type' => 'select',
                'id' => 'school_year_id-modal',
                'options' => $schoolYears->pluck('id', 'school_year')->toArray(),
                'label' => 'Tahun ajaran',
                'value' => $schoolYears->where('active', 1)->first()?->id
            ],
            'subject_id' => [
                'type' => 'select',
                'id' => 'subject_id-modal',
                'options' => $subjects->pluck('id', 'name')->toArray(),
                'label' => 'Mata pelajaran',
                'value' => null,
                'has_default_label' => true
            ],
            'semester' => [
                'type' => 'select',
                'id' => 'semester-modal',
                'options' => [
                    'Ganjil' => 'ganjil',
                    'Genap' => 'genap'
                ],
                'label' => 'Semester',
                'value' => null,
                'has_default_label' => false,
            ]
        ]"
        :hiddens="[
            'search' => 'search-modal',
        ]"
    />
@endsection
@section('script')
    @include('school.pages.exam.scripts.script-index')
    @include('school.pages.exam.scripts.script-delete')
@endsection
