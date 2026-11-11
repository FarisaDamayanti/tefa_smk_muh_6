@extends('teacher.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/copybank.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Daftar Soal {{ $questionBank->subject->name }}"
        breadcrumb="Kelola bank soal ujian dengan lebih mudah." />

    <div class="my-2">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

            <div class="d-flex align-items-center gap-3 flex-wrap flex-grow-1">
                {{-- FORM FILTER --}}
                <form method="GET" action="{{ url()->current() }}"
                    class="d-flex align-items-center flex-wrap gap-3 flex-grow-1">
                    <div class="search-wrap bg-white rounded-3">
                        <span class="search-icon text-muted">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M11 18C14.866 18 18 14.866 18 11C18 7.13401 14.866 4 11 4
                                                C7.13401 4 4 7.13401 4 11C4 14.866 7.13401 18 11 18Z" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <input type="text" class="form-control search-input" name="search" placeholder="Cari..."
                            value="{{ request('search') }}">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-filter me-2"></i>Filter
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-primary">
                            <i class="ti ti-refresh me-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-primary gap-1 d-flex align-items-center" id="btn-create" href="javascript:void(0)">
                    <i class="ti ti-plus fs-6 fw-semibold"></i>
                    Tambah Soal
                </a>

                <button type="button" class="btn btn-success d-flex align-items-center gap-2 rounded-2"
                    id="btn-open-import-modal">
                    <i class="ti ti-file-export fs-5"></i>
                    <span>Import Soal</span>
                </button>

                <a href="{{ route('teacher.question-bank.index') }}" class="btn btn-warning">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div id="import-alert-container" class="d-grid gap-3 mb-3"></div>

    <div class="my-2 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="form-check d-flex align-items-center gap-2 m-0">
                <input class="form-check-input mt-0" type="checkbox" id="select-all-questions">
                <label class="form-check-label fw-semibold" for="select-all-questions">
                    Pilih Semua Soal
                </label>
            </div>
            <button type="button" class="btn btn-outline-primary d-flex align-items-center gap-2 rounded-2"
                id="btn-duplicate" disabled data-duplicate-action="{{ $duplicateActionUrl ?? '#' }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M5.0245 10.8997C5.76611 9.86442 6.72525 9.00392 7.83464 8.37857C8.94403 7.75323 10.1768 7.37817 11.4465 7.2797V6.0797C11.4415 5.68842 11.548 5.30381 11.7536 4.97087C11.9592 4.63793 12.2554 4.37044 12.6075 4.1997C12.9443 4.03623 13.3204 3.9711 13.6926 4.01179C14.0648 4.05247 14.4179 4.19732 14.7115 4.4297L20.2025 8.7377C20.5768 9.03544 20.8389 9.45153 20.9457 9.91776C21.0526 10.384 20.9978 10.8727 20.7905 11.3037C20.6525 11.5897 20.4515 11.8406 20.2025 12.0377L14.7135 16.3457C14.4203 16.5788 14.0669 16.724 13.6945 16.7644C13.3221 16.8047 12.9458 16.7386 12.6095 16.5737C12.259 16.4021 11.9641 16.135 11.7588 15.8031C11.5536 15.4711 11.4463 15.0879 11.4495 14.6977V13.7557C6.1195 15.0397 5.2375 19.0067 5.1995 19.1967C5.15684 19.4144 5.04301 19.6117 4.87591 19.7576C4.70882 19.9035 4.49795 19.9898 4.2765 20.0027H4.2165C4.00352 20.0029 3.79603 19.9351 3.62426 19.8092C3.45248 19.6833 3.32539 19.5059 3.2615 19.3027C2.92826 17.858 2.91334 16.3581 3.21777 14.9071C3.52221 13.456 4.13871 12.0886 5.0245 10.8997Z"
                        fill="currentColor" />
                </svg>
                Duplikasi Soal Ke
            </button>
        </div>
        <div>
            <div class="badge bg-light-primary rounded-4">
                <h5 class="text-primary mb-0 py-2 px-3 fs-4">
                    {{ $questionBank->total_questions }} /
                    {{ $questionBank->question_limit }} Soal diisi
                </h5>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center my-4">
        <label for="select-all-options" class="fw-bold text-dark mb-2">
            <input type="checkbox" id="select-all-options" onclick="selectAllOptions(this)">
            Pilih Semua Jawaban
        </label>

        <button id="bulk-action-btn" class="btn btn-danger" style="display: none;">Hapus Opsi Jawaban Yang Terpilih</button>
    </div>

    @forelse ($questions as $index => $question)
        <div class="my-1">
            <div class="card w-100 rounded-3">

                {{-- ==============================
                    CARD HEADER
                ============================== --}}
                <div class="card-header bg-primary d-flex justify-content-between align-items-center px-4">
                    <div class="d-flex align-items-center gap-2">
                        <input class="form-check-input mt-0 question-checkbox" type="checkbox"
                            id="question-{{ $question->id }}" value="{{ $question->id }}"
                            data-label="Soal {{ $index + 1 }}">

                        <label for="question-{{ $question->id }}"
                            class="mb-0 text-white card-title fw-semibold cursor-pointer" style="cursor: pointer;">
                            Soal {{ $index + 1 }}
                        </label>

                        {{-- BADGE TIPE SOAL --}}
                        <span
                            class="badge bg-white ms-2
                            {{ $question->type === 'essay' ? 'text-warning' : '' }}
                            {{ $question->type === 'matching' ? 'text-info' : '' }}
                            {{ $question->type === 'multiple_choice' || $question->type === 'single_choice' ? 'text-primary' : '' }}
                        ">
                            {{ match ($question->type) {
                                'multiple_choice' => 'Pilihan Ganda',
                                'single_choice' => 'Pilihan Ganda',
                                'matching' => 'Penjodohan',
                                'essay' => 'Essay',
                                default => ucfirst($question->type),
                            } }}
                        </span>
                    </div>

                    <div class="ms-auto d-flex gap-2">
                        <div class="dropdown dropstart"
                            {{-- @if ($question->is_active || $question->studentQuestionAnswers()->exists()) data-bs-toggle="tooltip"
                                title="Tidak dapat edit dan hapus. Soal sudah digunakan"
                                data-bs-placement="top" @endif --}}
                                >
                            <a href="#" class="link text-white" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical fs-7"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item d-flex gap-1 align-items-center text-warning"
                                        href="{{ route('teacher.question-bank.edit', $question->id) }}">
                                        <i class="ti ti-edit fs-5"></i> Edit Soal
                                    </a>
                                </li>
                                @if (!($question->is_active || $question->studentQuestionAnswers()->exists()))
                                    <li>
                                        <button
                                            class="dropdown-item d-flex gap-1 align-items-center text-danger btn-delete-question"
                                            data-id="{{ $question->id }}">
                                            <i class="ti ti-trash fs-5"></i> Hapus Soal
                                        </button>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- ==============================
                    CARD BODY
                ============================== --}}
                <div class="card-body p-4 bg-light-primary">

                    {{-- Teks Soal --}}
                    <div class="p-3 rounded-2 bg-white text-dark question">
                        {!! $question->question !!}
                    </div>

                    {{-- ==============================
                        RENDER BERDASARKAN TIPE
                    ============================== --}}

                    @if ($question->type === 'multiple_choice' || $question->type === 'single_choice')
                        <div class="p-3 mt-3 rounded-2 bg-white text-dark options">
                            @php $seenLabels = []; @endphp
                            @foreach ($question->sortExamQuestionOptions as $index => $option)
                                @php
                                    $label = strtoupper(substr($option->option_label, -1));
                                    $isDuplicate = in_array($label, $seenLabels);
                                    if (!$isDuplicate) {
                                        $seenLabels[] = $label;
                                    }
                                @endphp

                                <div class="mb-3 p-3 rounded-2 d-flex gap-1
                                    {{ $option->is_correct ? 'bg-light-success border border-1 border-success' : 'bg-light-primary' }}">

                                    @if ($isDuplicate)
                                        <input type="checkbox" name="options[]" class="answer-options"
                                            value="{{ $option->id }}" checked>
                                    @endif

                                    <p class="m-0 fw-bold">{{ strtoupper(substr($option->option_label, -1)) }}.</p>
                                    {!! $option->option_text ?: '<span class="text-muted fst-italic">Opsi belum diisi</span>' !!}
                                </div>
                            @endforeach
                        </div>

                    @elseif ($question->type === 'matching')
                        {{-- PENJODOHAN / MATCHING --}}
                        @php
                            $leftItems = collect($question->leftExamQuestionMatchings ?? [])->keyBy('id');
                            $rightItems = collect($question->rightExamQuestionMatchings ?? [])->keyBy('id');
                        @endphp

                        <div class="p-3 mt-3 rounded-3 bg-white border border-info-subtle shadow-sm position-relative"
                            id="matching-container">

                            @if ($question->leftExamQuestionMatchings->count() > 0)
                                <div class="d-flex flex-column gap-3">

                                    @foreach ($leftItems as $leftItem)
                                        @php
                                            $left = $leftItem;
                                            $right = $rightItems->get($leftItem->leftItemAnswer->right_item_id);
                                        @endphp

                                        <div class="row align-items-center g-2">
                                            <div class="col-md-5">
                                                <div class="p-3 rounded-3 bg-light-primary border h-100 position-relative">
                                                    {!! $left->item_text !!}
                                                    <span class="js-anchor position-absolute"
                                                        data-node="left-{{ $loop->iteration }}"
                                                        style="
                                                            top:50%;
                                                            right:-8px;
                                                            width:12px;
                                                            height:12px;
                                                            border-radius:999px;
                                                            background:#5D87FF;
                                                            transform:translateY(-50%);
                                                            box-shadow:0 0 0 4px rgba(93,135,255,.15);
                                                        ">
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-2 d-flex align-items-center justify-content-center">
                                                <div style="width:100%;height:2px;background:#5D87FF;"></div>
                                            </div>

                                            <div class="col-md-5">
                                                <div class="p-3 rounded-3 bg-light-success border h-100 position-relative">

                                                    {!! $right->item_text !!}

                                                    <span class="js-anchor position-absolute"
                                                        data-node="right-{{ $loop->iteration }}"
                                                        style="
                                                            top:50%;
                                                            left:-8px;
                                                            width:12px;
                                                            height:12px;
                                                            border-radius:999px;
                                                            background:#5D87FF;
                                                            transform:translateY(-50%);
                                                            box-shadow:0 0 0 4px rgba(93,135,255,.15);
                                                        ">
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0 small">
                                    Belum ada pasangan penjodohan.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="d-flex flex-column align-items-center justify-content-center py-5" style="min-height: 400px;">
            <img src="{{ asset('admin_assets/dist/images/empty/nodata.png') }}" alt="Belum ada data"
                style="width: 200px; height: auto; opacity: 0.8; margin-bottom: 1.5rem;">
            <h5 class="fw-semibold text-dark mb-2">Ups! Soal tidak ditemukan.</h5>
            <p class="text-muted text-center mb-0">
                Belum ada soal yang ditemukan untuk saat ini.
            </p>
        </div>
    @endforelse

    <div class="d-flex justify-content-end">
        <x-paginate-component :paginator="$questions" />
    </div>

    <x-confirmation.delete id="delete-question" title="Apakah anda yakin ingin menghapus soal ini?"
        description="Soal yang sudah dihapus tidak dapat dikembalikan" button="Hapus" />

    @include('teacher.pages.question-bank.widgets.import-question')
    @include('teacher.pages.question-bank.widgets.duplicate')
@endsection

@section('script')
    @include('teacher.pages.question-bank.scripts.detail')
    @include('teacher.pages.question-bank.scripts.delete')
    @include('teacher.pages.question-bank.scripts.import')
    @include('teacher.pages.question-bank.scripts.duplicate')
    @include('teacher.pages.question-bank.scripts.remove-selected-option')
@endsection
