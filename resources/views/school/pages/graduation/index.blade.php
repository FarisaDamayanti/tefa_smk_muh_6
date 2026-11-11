@extends('school.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">

    <style>
        @media (min-width: 768px) {
            .w-md-auto {
                width: auto !important;
            }
        }
    </style>
@endsection

@section('content')
    @if (session('success'))
        <script>
            console.log('remove local storage');

            localStorage.removeItem('graduation_status');
            localStorage.removeItem('graduation_original_status');
        </script>
    @endif

    <x-authenticated.breadcrumb :title="'Manajemen Kelulusan'" breadcrumb="Informasi lengkap terkait kelulusan" />
    {{-- Statistics Cards --}}
    <div class="row mb-0">
        {{-- Total Siswa --}}
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-none position-relative overflow-hidden"
                style="background: linear-gradient(135deg, #5D87FF, #4570EA);">
                <div class="card-body p-4 position-relative">
                    <h5 class="fw-semibold text-white mb-3">Total Siswa</h5>
                    <h2 class="fw-bolder text-white mb-0">{{ $graduationDetails['students']->total() }}</h2>

                    {{-- Icon Background --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="90" height="70" viewBox="0 0 118 86"
                        fill="none" style="position: absolute; bottom: 10px; right: 10px; opacity: 0.3;">
                        <path
                            d="M0 86V70.95C0 67.9042 0.783091 65.1056 2.34927 62.5543C3.91545 60.003 5.99297 58.0536 8.58182 56.7063C14.1242 53.9292 19.7561 51.8473 25.4773 50.4606C31.1985 49.0738 37.0091 48.3787 42.9091 48.3751C48.8091 48.3715 54.6197 49.0666 60.3409 50.4606C66.0621 51.8545 71.6939 53.9364 77.2364 56.7063C79.8288 58.0501 81.9081 59.9994 83.4743 62.5543C85.0405 65.1092 85.8218 67.9078 85.8182 70.95V86H0ZM96.5455 86V69.875C96.5455 65.9334 95.4513 62.1476 93.2629 58.5177C91.0745 54.8878 87.9672 51.7756 83.9409 49.1813C88.5 49.7188 92.7909 50.6379 96.8136 51.9387C100.836 53.2394 104.591 54.8286 108.077 56.7063C111.295 58.498 113.754 60.4903 115.452 62.6833C117.151 64.8763 118 67.2735 118 69.875V86H96.5455ZM27.7568 36.6845C23.5553 32.474 21.4545 27.4126 21.4545 21.5001C21.4545 15.5876 23.5553 10.5262 27.7568 6.31575C31.9583 2.10534 37.0091 0.000137818 42.9091 0.000137818C48.8091 0.000137818 53.8598 2.10534 58.0614 6.31575C62.2629 10.5262 64.3636 15.5876 64.3636 21.5001C64.3636 27.4126 62.2629 32.474 58.0614 36.6845C53.8598 40.8949 48.8091 43.0001 42.9091 43.0001C37.0091 43.0001 31.9583 40.8949 27.7568 36.6845ZM90.2432 36.6845C86.0417 40.8949 80.9909 43.0001 75.0909 43.0001C74.1076 43.0001 72.8561 42.889 71.3364 42.6668C69.8167 42.4447 68.5652 42.1974 67.5818 41.9251C69.9955 39.0584 71.8513 35.8782 73.1493 32.3845C74.4473 28.8907 75.0945 25.2626 75.0909 21.5001C75.0873 17.7376 74.4401 14.1095 73.1493 10.6157C71.8584 7.122 70.0026 3.9418 67.5818 1.07514C68.8333 0.627219 70.0849 0.33518 71.3364 0.199013C72.5879 0.062847 73.8394 -0.00344551 75.0909 0.000137818C80.9909 0.000137818 86.0417 2.10534 90.2432 6.31575C94.4447 10.5262 96.5455 15.5876 96.5455 21.5001C96.5455 27.4126 94.4447 32.474 90.2432 36.6845Z"
                            fill="white" fill-opacity="0.4" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Siswa Lulus --}}
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-none position-relative overflow-hidden"
                style="background: linear-gradient(135deg, #13DEB9, #06D6A0);">
                <div class="card-body p-4 position-relative">
                    <h5 class="fw-semibold text-white mb-3">Total Siswa Lulus</h5>
                    <h2 class="fw-bolder text-white mb-0">{{ $graduationDetails['passed_count'] ?? 0 }}</h2>

                    {{-- Icon Background --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="90" height="70" viewBox="0 0 118 86"
                        fill="none" style="position: absolute; bottom: 10px; right: 10px; opacity: 0.3;">
                        <path
                            d="M0 86V70.95C0 67.9042 0.783091 65.1056 2.34927 62.5543C3.91545 60.003 5.99297 58.0536 8.58182 56.7063C14.1242 53.9292 19.7561 51.8473 25.4773 50.4606C31.1985 49.0738 37.0091 48.3787 42.9091 48.3751C48.8091 48.3715 54.6197 49.0666 60.3409 50.4606C66.0621 51.8545 71.6939 53.9364 77.2364 56.7063C79.8288 58.0501 81.9081 59.9994 83.4743 62.5543C85.0405 65.1092 85.8218 67.9078 85.8182 70.95V86H0ZM96.5455 86V69.875C96.5455 65.9334 95.4513 62.1476 93.2629 58.5177C91.0745 54.8878 87.9672 51.7756 83.9409 49.1813C88.5 49.7188 92.7909 50.6379 96.8136 51.9387C100.836 53.2394 104.591 54.8286 108.077 56.7063C111.295 58.498 113.754 60.4903 115.452 62.6833C117.151 64.8763 118 67.2735 118 69.875V86H96.5455ZM27.7568 36.6845C23.5553 32.474 21.4545 27.4126 21.4545 21.5001C21.4545 15.5876 23.5553 10.5262 27.7568 6.31575C31.9583 2.10534 37.0091 0.000137818 42.9091 0.000137818C48.8091 0.000137818 53.8598 2.10534 58.0614 6.31575C62.2629 10.5262 64.3636 15.5876 64.3636 21.5001C64.3636 27.4126 62.2629 32.474 58.0614 36.6845C53.8598 40.8949 48.8091 43.0001 42.9091 43.0001C37.0091 43.0001 31.9583 40.8949 27.7568 36.6845ZM90.2432 36.6845C86.0417 40.8949 80.9909 43.0001 75.0909 43.0001C74.1076 43.0001 72.8561 42.889 71.3364 42.6668C69.8167 42.4447 68.5652 42.1974 67.5818 41.9251C69.9955 39.0584 71.8513 35.8782 73.1493 32.3845C74.4473 28.8907 75.0945 25.2626 75.0909 21.5001C75.0873 17.7376 74.4401 14.1095 73.1493 10.6157C71.8584 7.122 70.0026 3.9418 67.5818 1.07514C68.8333 0.627219 70.0849 0.33518 71.3364 0.199013C72.5879 0.062847 73.8394 -0.00344551 75.0909 0.000137818C80.9909 0.000137818 86.0417 2.10534 90.2432 6.31575C94.4447 10.5262 96.5455 15.5876 96.5455 21.5001C96.5455 27.4126 94.4447 32.474 90.2432 36.6845Z"
                            fill="white" fill-opacity="0.4" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Siswa Tidak Lulus --}}
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-none position-relative overflow-hidden"
                style="background: linear-gradient(135deg, #FA896B, #F76E5E);">
                <div class="card-body p-4 position-relative">
                    <h5 class="fw-semibold text-white mb-3">Total Siswa Tidak Lulus</h5>
                    <h2 class="fw-bolder text-white mb-0">{{ $graduationDetails['not_passed_count'] ?? 0 }}</h2>

                    {{-- Icon Background --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="90" height="70" viewBox="0 0 118 86"
                        fill="none" style="position: absolute; bottom: 10px; right: 10px; opacity: 0.3;">
                        <path
                            d="M0 86V70.95C0 67.9042 0.783091 65.1056 2.34927 62.5543C3.91545 60.003 5.99297 58.0536 8.58182 56.7063C14.1242 53.9292 19.7561 51.8473 25.4773 50.4606C31.1985 49.0738 37.0091 48.3787 42.9091 48.3751C48.8091 48.3715 54.6197 49.0666 60.3409 50.4606C66.0621 51.8545 71.6939 53.9364 77.2364 56.7063C79.8288 58.0501 81.9081 59.9994 83.4743 62.5543C85.0405 65.1092 85.8218 67.9078 85.8182 70.95V86H0ZM96.5455 86V69.875C96.5455 65.9334 95.4513 62.1476 93.2629 58.5177C91.0745 54.8878 87.9672 51.7756 83.9409 49.1813C88.5 49.7188 92.7909 50.6379 96.8136 51.9387C100.836 53.2394 104.591 54.8286 108.077 56.7063C111.295 58.498 113.754 60.4903 115.452 62.6833C117.151 64.8763 118 67.2735 118 69.875V86H96.5455ZM27.7568 36.6845C23.5553 32.474 21.4545 27.4126 21.4545 21.5001C21.4545 15.5876 23.5553 10.5262 27.7568 6.31575C31.9583 2.10534 37.0091 0.000137818 42.9091 0.000137818C48.8091 0.000137818 53.8598 2.10534 58.0614 6.31575C62.2629 10.5262 64.3636 15.5876 64.3636 21.5001C64.3636 27.4126 62.2629 32.474 58.0614 36.6845C53.8598 40.8949 48.8091 43.0001 42.9091 43.0001C37.0091 43.0001 31.9583 40.8949 27.7568 36.6845ZM90.2432 36.6845C86.0417 40.8949 80.9909 43.0001 75.0909 43.0001C74.1076 43.0001 72.8561 42.889 71.3364 42.6668C69.8167 42.4447 68.5652 42.1974 67.5818 41.9251C69.9955 39.0584 71.8513 35.8782 73.1493 32.3845C74.4473 28.8907 75.0945 25.2626 75.0909 21.5001C75.0873 17.7376 74.4401 14.1095 73.1493 10.6157C71.8584 7.122 70.0026 3.9418 67.5818 1.07514C68.8333 0.627219 70.0849 0.33518 71.3364 0.199013C72.5879 0.062847 73.8394 -0.00344551 75.0909 0.000137818C80.9909 0.000137818 86.0417 2.10534 90.2432 6.31575C94.4447 10.5262 96.5455 15.5876 96.5455 21.5001C96.5455 27.4126 94.4447 32.474 90.2432 36.6845Z"
                            fill="white" fill-opacity="0.4" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Informasi Kelulusan --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white px-4 py-3">
            <h5 class="mb-0 fw-semibold text-white">Informasi Kelulusan</h5>
        </div>
        <div class="card-body p-4">
            <form id="graduationSubmissionForm" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- Tahun Ajaran --}}
                    <div class="mb-4 col-md-6 col-12">
                        <label for="school_year_id" class="form-label fw-semibold">Tahun Ajaran</label>
                        <select name="school_year_id" class="form-select" id="school_year_id">
                            @forelse ($schoolYears as $year)
                                <option value="{{ $year->id }}"
                                    @if (request()->filled('school_year_id') && request('school_year_id') == $year->id) selected
                                    @elseif (!request()->filled('school_year_id') && $year->active == 1)
                                        selected @endif>
                                    {{ $year->school_year }}
                                </option>
                            @empty
                                <option value="" disabled>Tidak ada tahun ajaran</option>
                            @endforelse
                        </select>
                    </div>

                    {{-- Tanggal Pengumuman Kelulusan --}}
                    <div class="mb-4 col-md-6 col-12">
                        <label for="announcement_date" class="form-label fw-semibold">Tanggal Pengumuman Kelulusan</label>
                        <input type="datetime-local" name="announcement_date" {{ $canUpdate ? '' : 'disabled' }}
                            class="form-control @error('announcement_date') is-invalid @enderror" id="announcement_date"
                            value="{{ $graduation?->announcement_date?->format('Y-m-d\TH:i') ?? ($canUpdate ? now()->addMinutes(5)->format('Y-m-d\TH:i') : Illuminate\Support\Carbon::create(1, 1, 1, 00, 00)->format('Y-m-d\TH:i')) }}">
                        <small class="text-danger">{{ $errors->first('announcement_date') }}</small>
                    </div>
                </div>

                {{-- Alert Warning --}}
                <div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
                    <i class="ti ti-info-circle fs-5 fw-bolder text-warning"></i>
                    <span class="fw-bolder text-warning">Pembaruan data hanya dapat dilakukan apabila tanggal kelulusan
                        belum terlampaui.</span>
                </div>

                {{-- Informasi Tambahan --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Informasi Tambahan
                        <span class="text-muted fw-normal">(Opsional)</span>
                    </label>

                    {{-- Dropzone Upload --}}
                    @if ($canUpdate)
                        <div class="mb-3">
                            <div class="border border-2 @error('additional_file') border-danger @enderror border-dashed rounded-3 p-5 text-center bg-light"
                                id="uploadDropzone" style="cursor: pointer;">

                                <svg width="80" height="80" viewBox="0 0 80 80" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="mb-3">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M23.3346 73.3333C18.9144 73.3333 14.6751 71.5773 11.5495 68.4517C8.42392 65.3261 6.66797 61.0869 6.66797 56.6666V46.6666C6.66797 45.7825 7.01916 44.9347 7.64428 44.3096C8.2694 43.6844 9.11725 43.3333 10.0013 43.3333C10.8854 43.3333 11.7332 43.6844 12.3583 44.3096C12.9834 44.9347 13.3346 45.7825 13.3346 46.6666V56.6666C13.3346 59.3187 14.3882 61.8623 16.2636 63.7377C18.1389 65.613 20.6825 66.6666 23.3346 66.6666H56.668C59.3201 66.6666 61.8637 65.613 63.739 63.7377C65.6144 61.8623 66.668 59.3187 66.668 56.6666V46.6666C66.668 45.7825 67.0192 44.9347 67.6443 44.3096C68.2694 43.6844 69.1172 43.3333 70.0013 43.3333C70.8854 43.3333 71.7332 43.6844 72.3583 44.3096C72.9834 44.9347 73.3346 45.7825 73.3346 46.6666V56.6666C73.3346 61.0869 71.5787 65.3261 68.4531 68.4517C65.3275 71.5773 61.0882 73.3333 56.668 73.3333H23.3346ZM24.2833 33.6667C23.9768 33.3536 23.7351 32.9832 23.5719 32.5766C23.4087 32.1701 23.3273 31.7353 23.3322 31.2973C23.3372 30.8592 23.4285 30.4264 23.6008 30.0237C23.7731 29.6209 24.0232 29.2561 24.3366 28.95L39.3366 14.2833C39.9594 13.6746 40.7957 13.3337 41.6666 13.3337C42.5375 13.3337 43.3738 13.6746 43.9966 14.2833L58.9966 28.95C59.3163 29.2544 59.5724 29.6193 59.7499 30.0234C59.9275 30.4276 60.023 30.863 60.0309 31.3044C60.0388 31.7457 59.9589 32.1843 59.796 32.5945C59.633 33.0048 59.3902 33.3786 59.0816 33.6943C58.773 34.0099 58.4048 34.2611 57.9983 34.4333C57.5919 34.6055 57.1552 34.6953 56.7138 34.6974C56.2724 34.6995 55.8349 34.6138 55.4268 34.4455C55.0188 34.2771 54.6482 34.0294 54.3366 33.7167L45 24.5867V53.3333C45 54.2174 44.6488 55.0652 44.0236 55.6904C43.3985 56.3155 42.5507 56.6667 41.6666 56.6667C40.7826 56.6667 39.9347 56.3155 39.3096 55.6904C38.6845 55.0652 38.3333 54.2174 38.3333 53.3333V24.5867L29 33.7167C28.6869 34.0231 28.3165 34.2648 27.9099 34.428C27.5034 34.5912 27.0686 34.6727 26.6306 34.6677C26.1925 34.6628 25.7597 34.5715 25.357 34.3992C24.9542 34.2268 24.5893 33.9768 24.2833 33.6633V33.6667Z"
                                        fill="#5D87FF" />
                                </svg>

                                <p class="mb-1 fw-semibold text-dark">
                                    Seret file ke sini untuk mengunggah
                                </p>
                                <small class="text-primary">Max 10 MB</small>
                            </div>

                            <small class="text-danger"
                                id="additional_file-error">{{ $errors->first('additional_file') }}</small>

                            {{-- Input File --}}
                            <input type="file" name="additional_file" class="d-none" id="additionalFileInput">
                        </div>
                    @endif


                    {{-- Container Preview File --}}
                    <div id="uploadedFilePreviewContainer">
                        @if ($graduation?->additional_file)
                            <div
                                class="d-flex align-items-center justify-content-between border rounded-3 p-3 bg-light-success border-success">
                                <div class="d-flex align-items-center gap-3 flex-grow-1 overflow-hidden">

                                    <svg width="40" height="40" viewBox="0 0 48 48" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0">
                                        <path
                                            d="M26.344 6H18C14.228 6 12.344 6 11.172 7.172C10 8.344 10 10.228 10 14V34C10 37.772 10 39.656 11.172 40.828C12.344 42 14.228 42 18 42H30C33.772 42 35.656 42 36.828 40.828C38 39.656 38 37.772 38 34V17.656C38 16.84 38 16.43 37.848 16.064C37.696 15.698 37.408 15.406 36.828 14.828L29.172 7.172C28.592 6.592 28.304 6.304 27.938 6.152C27.57 6 27.16 6 26.344 6Z"
                                            stroke="#13DEB9" stroke-width="2" />

                                        <path d="M26 6V14C26 15.886 26 16.828 26.586 17.414C27.172 18 28.114 18 30 18H38"
                                            stroke="#13DEB9" stroke-width="2" />
                                    </svg>

                                    <div class="overflow-hidden">
                                        <p class="mb-0 fw-semibold small text-truncate">
                                            {{ $graduation->additional_file_name }}
                                        </p>

                                        <small class="text-muted">
                                            {{ $graduation->additional_file_size }}
                                        </small>
                                    </div>
                                </div>

                                <a href="{{ $graduation->additional_file_url }}"
                                    download="{{ $graduation->additional_file_name }}"
                                    class="text-success ms-3 flex-shrink-0">

                                    <i class="ti ti-download fs-7"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($canUpdate)
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4" id="btn-submit-graduation">
                            Simpan
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- Daftar Siswa --}}
    <div class="card shadow-md border border-1 mb-4">
        <div class="card-body p-4">
            <h5 class="mb-4">Daftar Siswa</h5>

            @error('graduates')
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @enderror

            <div class="mb-4">
                <div class="row">
                    <div class="col-lg-8 col-md-10 col-12 mb-md-0 mb-3">
                        <form class="d-flex align-items-center gap-2 flex-nowrap" style="height: 40px;">
                            <input type="hidden" name="school_year_id" value="{{ request('school_year_id') }}">

                            <input type="text" name="search" id="search" class="form-control fs-3 ps-3 h-100"
                                placeholder="Cari..." value="{{ request('search') }}">

                            <select name="classroom_id" class="form-select d-none d-md-block h-100" id="classroom_id">
                                <option value="" @if (!request('classroom_id')) selected @endif>Semua Kelas
                                </option>
                                @forelse ($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}"
                                        @if (request('classroom_id') == $classroom->id) selected @endif>
                                        {{ $classroom->levelClass->name . ' ' . $classroom->name }}
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada Kelas</option>
                                @endforelse
                            </select>

                            <div class="d-md-block d-none h-100">
                                <button type="submit" class="btn btn-primary h-100 d-flex align-items-center">
                                    <i class="ti ti-filter"></i>
                                    <span class="d-none ms-2 d-md-block">Filter</span>
                                </button>
                            </div>

                            <div class="d-md-none d-block h-100">
                                <button type="button" class="btn btn-primary rounded-2 h-100" data-bs-toggle="modal"
                                    data-bs-target="#filterModal">
                                    <i class="ti ti-filter"></i>
                                </button>
                            </div>

                            <button type="button" class="btn btn-outline-secondary h-100 d-flex align-items-center"
                                id="reset-btn">
                                <i class="ti ti-refresh fs-4"></i>
                                <span class="d-none ms-2 d-md-block">Reset</span>
                            </button>
                        </form>
                    </div>

                    @if ($canUpdate)
                        <div class="col-lg-4 col-md-2 col-12" style="height: 40px;">
                            <div class="d-flex justify-content-end h-100">
                                <span class="d-inline-block w-100 h-100 w-md-auto" data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="{{ !$graduation ? 'Informasi kelulusan belum diisi' : '' }}">
                                    <button type="submit" form="graduationForm" id="btn-submit-graduationDetail"
                                        class="btn btn-primary px-4 w-100 h-100 w-md-auto"
                                        {{ !$graduation ? 'disabled' : '' }}>
                                        Simpan
                                    </button>
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="alert alert-warning d-flex align-items-center gap-2 mb-4 d-none" id="alert-edited">
                <i class="ti ti-info-circle fs-5 fw-bold text-warning"></i>

                <span class="fw-bold text-warning">
                    Terdapat beberapa data yang di edit dan belum disimpan
                </span>
            </div>

            <div class="table-responsive rounded-2 mb-4">
                <form id="graduationForm" action="{{ route('school.graduations.students.storeOrUpdate') }}"
                    method="POST">
                    @csrf
                    <table class="table border text-nowrap customize-table mb-0 align-middle">
                        <thead>
                            <tr class="text-center align-middle">
                                <th class="bg-primary text-white fs-4 py-3 rounded-start">No</th>
                                <th class="bg-primary text-white fs-4 py-3">NISN</th>
                                <th class="bg-primary text-white fs-4 py-3">Nama</th>
                                <th class="bg-primary text-white fs-4 py-3">Kelas</th>

                                <th class="bg-primary text-white fs-4 py-2 rounded-end">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <span>Status Kelulusan</span>

                                        <div
                                            class="form-check d-flex align-items-center justify-content-start gap-2 mb-0 mt-1">
                                            <input class="form-check-input success fs-3" type="checkbox"
                                                id="checkAllGraduated">

                                            <label class="form-check-label text-white fs-3" for="checkAllGraduated">
                                                Lulus Semua (Halaman {{ $graduationDetails['students']->currentPage() }})
                                            </label>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($graduationDetails['students'] as $classroomStudent)
                                <tr>
                                    <td class="text-center">
                                        {{ $graduationDetails['students']->firstItem() + $loop->index }}
                                    </td>

                                    <td class="text-center">
                                        {{ $classroomStudent->student->nisn }}
                                    </td>

                                    <td class="text-center">
                                        {{ $classroomStudent->student->user->name }}
                                    </td>

                                    <td class="text-center">
                                        {{ $classroomStudent->classroom->levelClass->name . ' ' . $classroomStudent->classroom->name }}
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-4">
                                            @if ($canUpdate)
                                                {{-- LULUS --}}
                                                <div class="form-check">
                                                    <input class="form-check-input success graduation-radio"
                                                        type="radio" name="status_{{ $classroomStudent->student->id }}"
                                                        id="graduated_{{ $classroomStudent->student->id }}"
                                                        value="passed"
                                                        data-student-id="{{ $classroomStudent->student->id }}"
                                                        {{ $classroomStudent->student->graduationDetails->first()?->status === 'passed' ? 'checked' : '' }}>

                                                    <label class="form-check-label fw-semibold"
                                                        for="graduated_{{ $classroomStudent->student->id }}">
                                                        Lulus
                                                    </label>
                                                </div>

                                                {{-- TIDAK LULUS --}}
                                                <div class="form-check">
                                                    <input class="form-check-input danger graduation-radio" type="radio"
                                                        name="status_{{ $classroomStudent->student->id }}"
                                                        id="not_graduated_{{ $classroomStudent->student->id }}"
                                                        value="not_passed"
                                                        data-student-id="{{ $classroomStudent->student->id }}"
                                                        {{ $classroomStudent->student->graduationDetails->first()?->status === 'not_passed' ? 'checked' : '' }}>

                                                    <label class="form-check-label fw-semibold"
                                                        for="not_graduated_{{ $classroomStudent->student->id }}">
                                                        Tidak Lulus
                                                    </label>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center gap-4">
                                                    @if ($classroomStudent->student->graduationDetails->first()?->status === 'passed')
                                                        <span
                                                            class="badge bg-light-success text-success rounded-2 fs-4 fw-semibold">Lulus</span>
                                                    @elseif ($classroomStudent->student->graduationDetails->first()?->status === 'not_passed')
                                                        <span
                                                            class="badge bg-light-danger text-danger rounded-2 fs-4 fw-semibold">Tidak
                                                            Lulus</span>
                                                    @else
                                                        <span
                                                            class="badge bg-light text-muted rounded-2 fs-4 fw-semibold">Belum
                                                            ditentukan</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center align-middle">
                                        <div class="d-flex flex-column justify-content-center align-items-center py-4">
                                            <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}"
                                                alt="No Data" width="250">
                                            <p class="fs-5 text-dark text-center mt-3 mb-0">
                                                Data siswa tidak tersedia
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="pagination justify-content-end mb-0">
                <x-paginate-component :paginator="$graduationDetails['students']" />
            </div>
        </div>
    </div>

    <x-dynamic-modal-filter :title="'Filter Siswa'" :filters="[
        'classroom_id' => [
            'type' => 'select',
            'id' => 'classroom_id-modal',
            'options' => $classrooms->pluck('id', 'name')->toArray(),
            'label' => 'Kelas',
            'value' => request('classroom_id'),
            'has_default_label' => true,
            'default_label' => 'Semua Kelas',
        ],
    ]" :hiddens="[
        'search' => 'search-modal',
        'school_year_id' => 'school_year_id-modal',
    ]" />
@endsection

@section('script')
    @include('school.pages.graduation.scripts.index')
    @include('school.pages.graduation.scripts.file-input')
    @include('school.pages.graduation.scripts.submit-graduation')
    @include('school.pages.graduation.scripts.submit-graduation-detail')
@endsection
