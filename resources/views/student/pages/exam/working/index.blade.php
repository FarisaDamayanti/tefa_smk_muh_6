@extends('student.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/student/working-exam.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/summernote/dist/summernote-lite.min.css') }}">
@endsection

@section('content')
    <div class="mt-n5">

        <x-authenticated.breadcrumb title="{{ $exam->title }}" breadcrumb="Selamat Mengerjakan dan Patuhi Peraturan Ujian" />

        @if ($data->success)
            <div class="card mb-3">
                <div class="card-body rounded rounded-2 p-3 bg-primary d-flex justify-content-between align-items-center">
                    <h4 class="text-white m-0 fw-semibold">Soal Ujian</h4>
                    <div class="row align-items-center gap-2 px-2">
                        <div
                            class="col-auto badge bg-light-warning text-warning border border-1 border-warning d-flex align-items-center gap-2 rounded-2">
                            <span>Ragu-ragu</span>
                            <input type="checkbox" class="form-check-input" id="ragu-ragu">
                        </div>
                        <div class="col-auto badge bg-light-primary text-primary d-flex align-items-center gap-2 rounded-2">
                            <h5 class="m-0 text-primary fw-semibold" id="timer">0</h5>
                            <span class="text-muted">Waktu Tersisa</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-9">
                    <div class="card border border-1 bg-light-primary rounded-2 shadow-md p-3 mb-4 question-item">

                        <div
                            class="p-3 rounded-4 bg-white text-dark d-flex align-items-center justify-content-between gap-3">
                            <label class="form-check-label fw-semibold d-flex gap-2 mb-0">
                                <span class="number" id="question-number"></span>
                                <div class="question" id="question-text"></div>
                            </label>

                            {{-- Icon ? sebagai trigger modal video --}}
                            <button type="button" id="btn-video-hint"
                                class="bg-light-primary border-0 rounded-4 d-flex align-items-center justify-content-center flex-shrink-0 d-none"
                                style="width:36px;height:36px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.6667 23.3333C5.22317 23.3333 0 18.1102 0 11.6667C0 5.22317 5.22317 0 11.6667 0C18.1102 0 23.3333 5.22317 23.3333 11.6667C23.3333 18.1102 18.1102 23.3333 11.6667 23.3333ZM10.5 15.1667V17.5H12.8333V15.1667H10.5ZM12.8333 13.2475C13.7709 12.9649 14.5759 12.3549 15.1015 11.5287C15.6272 10.7025 15.8385 9.7149 15.6972 8.74588C15.5558 7.77686 15.0711 6.89085 14.3313 6.2492C13.5916 5.60755 12.6459 5.25296 11.6667 5.25C10.7226 5.24971 9.8076 5.57654 9.07737 6.17489C8.34715 6.77324 7.84681 7.60613 7.6615 8.53183L9.9505 8.99033C10.0155 8.66534 10.1714 8.36547 10.4001 8.12562C10.6288 7.88577 10.9209 7.7158 11.2425 7.63549C11.564 7.55518 11.9018 7.56783 12.2164 7.67197C12.5311 7.7761 12.8097 7.96744 13.0198 8.22373C13.2299 8.48002 13.363 8.79071 13.4035 9.11965C13.4439 9.4486 13.3902 9.78227 13.2484 10.0818C13.1066 10.3814 12.8827 10.6346 12.6027 10.8119C12.3227 10.9892 11.9981 11.0833 11.6667 11.0833C11.3572 11.0833 11.0605 11.2063 10.8417 11.425C10.6229 11.6438 10.5 11.9406 10.5 12.25V14H12.8333V13.2475Z"
                                        fill="#549BFF" />
                                </svg>
                            </button>
                        </div>

                        <div class="p-4 rounded-4 bg-white text-dark options mt-3" id="options-container">
                        </div>
                    </div>

                    <div class="card border border-1 bg-light-primary rounded-2 shadow-md">
                        <div class="card-body p-2 d-flex justify-content-between align-items-center">
                            <div class="previous-btn">
                                <button type="button" id="prev-btn"
                                    class="btn bg-white text-primary border border-1 border-primary gap-2 d-flex justify-content-between align-items-center px-2">
                                    <div class="badge bg-light-primary p-2 rounded-2">
                                        <i class="ti ti-arrow-narrow-left text-primary fs-6"></i>
                                    </div>
                                    <span class="me-2 d-none d-sm-inline" id="text-previous">Sebelumnya</span>
                                </button>
                            </div>
                            <p class="text-muted m-0"><span id="question-number-bottom"></span> Dari
                                {{ $data->question_total }}</p>
                            <div class="next-btn">
                                <button type="button" id="next-btn"
                                    class="btn bg-white text-primary border border-1 border-primary gap-2 d-flex justify-content-between align-items-center px-2">
                                    <span class="me-2 d-none d-sm-inline" id="text-next">Selanjutnya</span>
                                    <div class="badge bg-light-primary p-2 rounded-2">
                                        <i class="ti ti-arrow-narrow-right text-primary fs-6"></i>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="card shadow-md border border-1">
                        <div class="card-body rounded-2 p-3 bg-light-primary">
                            <h5 class="m-0 text-primary mb-3">Daftar Soal</h5>
                            <div class="row g-2">
                                @forelse ($data->question_ids as $key => $questionId)
                                    <div class="col-2 col-md-1 col-lg-2 my-3">
                                        <button type="button"
                                            class="w-100 rounded-2 question-number-btn py-2 text-muted border border-0"
                                            data-number="{{ ++$key }}" data-question-id="{{ $questionId }}">
                                            {{ $key }}
                                        </button>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt=""
                                            width="200px">
                                        <p class="fs-6 text-dark text-center mt-2 mb-0">Tidak ada soal</p>
                                    </div>
                                @endforelse

                                @if ($exam->allow_early_submission == false)
                                    <p class="mt-4">Anda bisa menyelesaikan ujian ketika waktu ujian sisa 5 menit</p>
                                @endif

                                <button id="submit-exam"
                                    class="btn bg-white border border-1 border-primary text-primary fw-semibold">
                                    Selesaikan Ujian
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="d-flex flex-column justify-content-center align-items-center py-5">
                <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt="" width="300px">
                <p class="fs-5 text-dark text-center mt-2 mb-0">{{ $data->message }}</p>
            </div>
        @endif

    </div>
    @include('student.pages.exam.working.widgets.exam-submit-lock-modal')
    @include('student.pages.exam.working.widgets.enter-fullscreen-modal')
    @include('student.pages.exam.working.widgets.fullscreen-exit-warning')
    @include('student.pages.exam.working.widgets.violation-detected-modal')
    @include('student.pages.exam.working.widgets.flagged-warning-modal')
    @include('student.pages.exam.working.widgets.success-submit-exam-modal')
    @include('student.pages.exam.working.widgets.timesp-up-modal')
    @include('student.pages.exam.working.widgets.confirmation-submit-exam')
    @include('student.pages.exam.working.widgets.video-hint-modal')

@endsection

@section('script')
    @if ($data->success)
        <script src="{{ asset('admin_assets/dist/summernote/dist/summernote-lite.min.js') }}"></script>
        @include('student.pages.exam.working.scripts.index')
    @endif
@endsection
