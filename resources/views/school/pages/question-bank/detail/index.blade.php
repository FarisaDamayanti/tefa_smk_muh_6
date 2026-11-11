@extends('school.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <style>
        .question-item {
            transition: all 0.35s ease;
        }

        .highlight-move {
            background-color: #fff3cd;
        }

        .accordion-button:not(.collapsed) {
            background-color: transparent;
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: transparent;
        }

        .accordion-button::after {
            display: none;
        }
    </style>
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Detail Bank Soal" breadcrumb="Detail Informasi Bank Soal" />

    <div class="card border shadow-sm rounded-4 p-4 mb-4 mt-n1">
        <div class="row">
            <div class="col-12 col-sm-10">
                <h4 class="fw-bold mb-3">
                    {{ $questionBank->title }}
                </h4>

                <div class="d-flex gap-2 flex-wrap mb-3">
                    <span class="badge bg-light-primary text-primary fw-semibold px-3 py-2 rounded-pill">
                        {{ $questionBank->subject->name ?? '-' }}
                    </span>

                    <span class="badge bg-light-primary text-primary fw-semibold px-3 py-2 rounded-pill">
                        Kelas {{ $questionBank->levelClass->name ?? '-' }}
                    </span>
                </div>

                <p class="text-dark fs-4">
                    {{ $questionBank->description }}
                </p>
            </div>

            <div class="col-2 d-sm-flex justify-content-end d-none">
                <div class="badge bg-light-primary d-flex flex-column align-items-center justify-content-center rounded-3"
                    style="width:100px;height:100px">
                    <span class="text-primary">Soal Aktif</span>
                    <span class="text-primary" style="font-size:40px;font-weight:bold;">
                        {{ $questionBank->total_active_questions }}
                    </span>
                </div>
            </div>

            <div class="col-12 d-sm-none my-3">
                <div
                    class="badge bg-light-primary d-flex flex-column align-items-center justify-content-center rounded-3 py-3">
                    <span class="text-primary">Soal Aktif</span>
                    <span class="text-primary" style="font-size:40px;font-weight:bold;">
                        {{ $questionBank->total_active_questions }}
                    </span>
                </div>
            </div>
        </div>

        <div class="alert bg-light-warning text-warning mt-3 mb-0 rounded-4 fs-4 fw-bold">
            Seluruh soal yang Anda pilih akan digunakan sebagai materi ujian bagi siswa.
            Pastikan setiap soal telah diperiksa dengan baik sebelum digunakan.
        </div>
    </div>

    <div class="row align-items-center mb-3 g-2">
        <div class="col-12 col-lg">
            <form method="GET" class="row g-2 align-items-center">

                <div class="col-12 col-sm-6 col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Cari soal..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <select name="employee_id" class="form-select">
                        <option value="">Semua Guru</option>
                        @foreach ($filterEmployees as $employee)
                            <option value="{{ $employee->id }}"
                                {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-auto d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ url()->current() }}" class="btn btn-outline-primary">Reset</a>
                </div>

            </form>
        </div>

        <div class="col-12 col-lg-auto d-flex align-items-center justify-content-between justify-content-lg-end gap-2">
            <small class="text-primary fs-5 fw-semibold">
                <span id="total-selected-questions">{{ $questionBank->total_active_questions }}</span>
                / <span id="total-questions">{{ $questionBank->total_questions }}</span> Soal Dipilih
            </small>
            <a href="{{ route('school.question-bank.index') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>

    {{-- ================= QUESTION LIST ================= --}}
    <div class="card border shadow-md rounded-4 p-4">

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="checkAll">
            <label class="form-check-label fw-semibold" for="checkAll">
                Pilih Semua Soal
            </label>
        </div>

        <div class="accordion" id="question-list">

            @forelse ($questions as $question)
                @if ($question->leftExamQuestionMatchings->isNotEmpty())
                    <x-detail.matching :question="$question" :loop="$loop" />
                @elseif($question->sortExamQuestionOptions->isNotEmpty())
                    <x-detail.multiple-choice :question="$question" :loop="$loop" />
                @else
                    <x-detail.essay :question="$question" :loop="$loop" />
                @endif

            @empty
                <div class="text-center py-5">
                    <img src="{{ asset('admin_assets/dist/images/empty/nodata.png') }}" width="200">
                    <h5 class="fw-semibold mt-3">
                        Soal belum tersedia.
                    </h5>
                </div>
            @endforelse

        </div>

        @if ($questions->count() > 0)
            <a href="{{ route('school.question-bank.review', $questionBank->id) }}"
                class="btn btn-primary fs-6">Selanjutnya</a>
        @endif

    </div>

    <div class="d-flex justify-content-end mt-3">
        <x-paginate-component :paginator="$questions" />
    </div>
@endsection


@section('script')
    @include('school.pages.question-bank.detail.scripts.select-questions')

    @if ($questions->pluck('leftExamQuestionMatchings')->flatten()->isNotEmpty())
        @include('school.pages.question-bank.detail.scripts.matching')
    @endif
@endsection
