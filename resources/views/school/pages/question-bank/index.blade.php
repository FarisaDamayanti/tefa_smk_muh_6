@extends('school.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/dropdown/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/dropdown/school.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb :title="'Bank Soal'" breadcrumb="Kumpulan dan Pengelolaan Soal Ujian" />

    <div class="mt-4 mb-4">
        <div class="row">
            <div class="col-12">
                <form class="d-flex align-items-center gap-2 flex-nowrap" style="height: 40px;">
                    <input type="text" name="search" id="search" class="form-control filter-input h-100" placeholder="Cari..." value="{{ request('search') }}">

                    <div class="d-md-flex d-none h-100 gap-2 flex-grow-1">
                        <div class="flex-grow-1" style="z-index:2; min-width: 200px;">
                            <div class="custom-dropdown" data-name="subject_id" data-placeholder="Semua Mapel">
                                <div class="dropdown-selected">
                                    <span class="selected-text">
                                        {{ ($subjects ?? collect())->firstWhere('id', request('subject_id'))->name ?? 'Semua Mapel' }}
                                    </span>
                                    <svg class="dropdown-arrow" viewBox="0 0 24 24">
                                        <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="2.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="dropdown-options">
                                    <div class="dropdown-option" data-value="">Semua Mapel</div>
                                    @foreach ($subjects ?? [] as $subject)
                                        <div class="dropdown-option" data-value="{{ $subject->id }}">{{ $subject->name }}</div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                            </div>
                        </div>

                        <div class="flex-grow-1" style="z-index:1; min-width: 200px;">
                            <div class="custom-dropdown" data-name="level_class_id" data-placeholder="Semua Tingkatan">
                                <div class="dropdown-selected">
                                    <span class="selected-text">
                                        {{ ($levelClasses ?? collect())->firstWhere('id', request('level_class_id'))
                                            ? 'Kelas ' . ($levelClasses ?? collect())->firstWhere('id', request('level_class_id'))->name
                                            : 'Semua Tingkatan' }}
                                    </span>
                                    <svg class="dropdown-arrow" viewBox="0 0 24 24">
                                        <path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="2.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="dropdown-options">
                                    <div class="dropdown-option" data-value="">Semua Tingkatan</div>
                                    @foreach ($levelClasses ?? [] as $level)
                                        <div class="dropdown-option" data-value="{{ $level->id }}">Kelas {{ $level->name }}
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="level_class_id" value="{{ request('level_class_id') }}">
                            </div>
                        </div>
                    </div>

                    <div class="d-lg-block d-none h-100">
                        <button type="submit" class="btn btn-primary h-100 d-flex align-items-center">
                            <i class="ti ti-filter"></i>
                            <span class="d-none ms-2 d-lg-block">Filter</span>
                        </button>
                    </div>
    
                    <div class="d-lg-none d-block h-100">
                        <button type="button" class="btn btn-primary rounded-2 h-100" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="ti ti-filter"></i>
                        </button>
                    </div>
    
                    <a class="btn btn-outline-primary h-100 d-flex align-items-center" href="{{ route('school.question-bank.index') }}">
                        <i class="ti ti-refresh fs-4"></i>
                        <span class="d-none ms-2 d-lg-block">Reset</span>
                    </a>

                    <a href="{{ route('school.question-bank.create') }}" class="btn btn-primary d-flex align-items-center text-nowrap h-100">
                        <i class="ti ti-plus"></i> 
                        <span class="ms-1 d-none d-lg-block">Tambah Bank Soal</span>
                    </a>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($questionBanks as $questionBank)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h4 class="fw-bold text-dark mb-3 lh-sm"
                            style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $questionBank->title }}
                        </h4>
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span
                                class="badge rounded-pill bg-primary bg-opacity-10 text-primary fw-semibold px-3 py-2 d-inline-flex align-items-center gap-2">
                                <svg width="16" height="18" viewBox="0 0 16 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3.55556 0C2.61256 0 1.70819 0.374602 1.0414 1.0414C0.374602 1.70819 0 2.61256 0 3.55556V14.2222C0 15.1652 0.374602 16.0696 1.0414 16.7364C1.70819 17.4032 2.61256 17.7778 3.55556 17.7778H16V0H3.55556ZM7.11111 2.66667H13.3333V4.44445H7.11111V2.66667ZM1.77778 14.2222C1.77778 13.7507 1.96508 13.2985 2.29848 12.9651C2.63187 12.6317 3.08406 12.4444 3.55556 12.4444H14.2222V16H3.55556C3.08406 16 2.63187 15.8127 2.29848 15.4793C1.96508 15.1459 1.77778 14.6937 1.77778 14.2222Z"
                                        fill="#5D87FF" />
                                </svg>
                                {{ $questionBank->subject->name ?? '-' }}
                            </span>
                            <span
                                class="badge rounded-pill bg-primary bg-opacity-10 text-primary fw-semibold px-3 py-2 d-inline-flex align-items-center gap-2">
                                <svg width="20" height="17" viewBox="0 0 20 17" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M18.0861 12.1538V6.51863L10.3828 10.6972C10.0957 10.8564 9.79266 10.936 9.47369 10.936C9.15471 10.936 8.85168 10.8564 8.5646 10.6972L0.478477 6.30373C0.303038 6.20822 0.179275 6.08883 0.107185 5.94556C0.0350961 5.8023 -0.000629564 5.64311 8.39419e-06 5.46801C0.000646352 5.29291 0.0366911 5.13372 0.108142 4.99046C0.179594 4.84719 0.303038 4.7278 0.478477 4.63229L8.5646 0.238792C8.70814 0.1592 8.85582 0.099346 9.00766 0.0592314C9.15949 0.0191169 9.31483 -0.000621815 9.47369 1.49237e-05C9.63254 0.000651662 9.7882 0.0207089 9.94067 0.0601867C10.0931 0.0996645 10.2405 0.1592 10.3828 0.238792L19.4976 5.20535C19.6571 5.28495 19.7809 5.40052 19.8689 5.55206C19.9569 5.7036 20.0006 5.86661 20 6.04107V12.1538C20 12.4244 19.9081 12.6514 19.7244 12.8348C19.5407 13.0181 19.3135 13.1095 19.0431 13.1089C18.7726 13.1082 18.5455 13.0165 18.3617 12.8338C18.178 12.6511 18.0861 12.4244 18.0861 12.1538ZM8.5646 16.4279L3.77991 13.8491C3.46093 13.674 3.21372 13.4352 3.03828 13.1328C2.86285 12.8303 2.77513 12.504 2.77513 12.1538V8.52435L8.5646 11.6523C8.85168 11.8115 9.15471 11.8911 9.47369 11.8911C9.79266 11.8911 10.0957 11.8115 10.3828 11.6523L16.1722 8.52435V12.1538C16.1722 12.504 16.0845 12.8303 15.9091 13.1328C15.7336 13.4352 15.4864 13.674 15.1675 13.8491L10.3828 16.4279C10.2392 16.5075 10.0919 16.5673 9.94067 16.6074C9.78947 16.6476 9.63381 16.6673 9.47369 16.6667C9.31356 16.666 9.1579 16.6463 9.0067 16.6074C8.8555 16.5686 8.70814 16.5087 8.5646 16.4279Z"
                                        fill="#5D87FF" />
                                </svg>
                                Kelas {{ $questionBank->levelClass->name ?? '-' }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-3 mb-3">
                            <div class="fw-semibold">
                                {{ $questionBank->getActiveQuestionCount() }} Soal terpilih dari
                                {{ $questionBank->total_questions }}
                            </div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <a href="{{ route('school.question-bank.detail', $questionBank->id) }}"
                                class="btn btn-primary flex-grow-1 rounded-2 fw-semibold py-2">
                                Lihat Soal
                            </a>
                            <a href="{{ route('school.question-bank.edit', $questionBank->id) }}"
                                class="btn rounded-2 d-flex align-items-center justify-content-center"
                                style="width:46px;height:38px;background:#F4B740;border:0;">
                                <i class="ti ti-pencil fs-4 text-white"></i>
                            </a>
                            <button type="button"
                                class="btn rounded-2 d-flex align-items-center justify-content-center btn-delete-qb"
                                data-bs-toggle="modal" data-bs-target="#delete-question-bank-modal"
                                data-id="{{ $questionBank->id }}"
                                style="width:46px;height:38px;background:#FF6B6B;border:0;">
                                <i class="ti ti-trash fs-4 text-white"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="d-flex flex-column align-items-center justify-content-center py-5" style="min-height: 400px;">
                    <img src="{{ asset('admin_assets/dist/images/empty/nodata.png') }}" alt="Belum ada data"
                        style="width: 200px; height: auto; opacity: 0.8; margin-bottom: 1.5rem;">
                    <h5 class="fw-semibold text-dark mb-2">Ups! Belum ada Bank Soal.</h5>
                    <p class="text-muted text-center mb-0" style="max-width: 400px;">
                        Belum ada bank soal untuk saat ini. Silakan tambahkan bank soal baru atau cek kembali di lain waktu.
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    @if ($questionBanks->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    @if ($questionBanks->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $questionBanks->previousPageUrl() }}"
                                rel="prev">Previous</a>
                        </li>
                    @endif

                    @for ($i = 1; $i <= $questionBanks->lastPage(); $i++)
                        <li class="page-item {{ $i == $questionBanks->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $questionBanks->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    @if ($questionBanks->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $questionBanks->nextPageUrl() }}" rel="next">Next</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">Next</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    @endif


    <x-confirmation.delete id="delete-question-bank" title="Apakah anda yakin ingin menghapus bank soal ini?"
        description="Bank soal yang sudah dihapus tidak dapat dikembalikan" button="Hapus" />

    <x-dynamic-modal-filter 
        :title="'Filter Bank Soal'"
        :filters="[
            'subject_id' => [
                'type' => 'select',
                'id' => 'subject_id-modal',
                'options' => $subjects->pluck('id', 'name')->toArray(),
                'label' => 'Mata Pelajaran',
                'value' => null,
                'has_default_label' => true,
            ],
            'level_class_id' => [
                'type' => 'select',
                'id' => 'level_class_id-modal',
                'options' => $levelClasses->pluck('id', 'name')->toArray(),
                'label' => 'Tingkatan Kelas',
                'value' => null,
                'has_default_label' => true,
            ],
        ]"
        :hiddens="[
            'search' => 'search-modal',
        ]"
    />
@endsection


@section('script')
    @include('school.pages.question-bank.scripts.index')
    @include('school.pages.question-bank.scripts.dropdown')
    @include('school.pages.question-bank.scripts.delete')
@endsection
