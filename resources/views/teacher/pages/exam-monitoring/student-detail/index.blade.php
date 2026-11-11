@extends('teacher.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection
@section('content')
    <x-authenticated.breadcrumb title="Detail Siswa" breadcrumb="Detail Hasil Ujian Siswa" />

    <div class="d-flex justify-content-end align-content-end mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-warning d-inline-flex align-items-center gap-2"> Kembali</a>
    </div>

    <div class="row g-4">
        {{-- KOLOM KIRI --}}
        <div class="col-12 col-lg-3">
            <div>

                {{-- Data Informasi Siswa --}}
                <div class="card rounded-4 border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="card-header fw-bold fs-6 border-0 py-3 px-4 text-white bg-primary">
                        Data Informasi Siswa
                    </div>
                    <div class="card-body text-center px-4 pt-4 pb-3">
                        <div class="mb-3">
                            <img src="{{ asset($studentExam->student->image ? 'storage/' . $studentExam->student->image : 'assets/images/default-user.jpeg') }}"
                                alt="Foto Siswa" width="90" height="90"
                                class="rounded-circle border border-3 border-primary-subtle" style="object-fit: cover;">
                        </div>
                        <h6 class="fw-bold mb-1">{{ $studentExam->student->user->name }}</h6>
                        <span class="badge bg-primary-subtle text-primary mb-3"
                            style="font-size: 0.82rem;">{{ $studentExam->student->user->email }}</span>
                        <div class="d-flex justify-content-center mt-2">
                            <div class="text-start px-2">
                                <div class="d-flex gap-2 mb-1">
                                    <span class="fw-semibold text-dark" style="width: 50px;">NISN</span>
                                    <span class="text-muted">:</span>
                                    <span>{{ $studentExam->student->nisn }}</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <span class="fw-semibold text-dark" style="width: 50px;">Kelas</span>
                                    <span class="text-muted">:</span>
                                    <span>
                                        {{ $studentExam->student?->currentStudentClassroom?->classroom?->levelClass?->name ?? '-' }}
                                        {{ $studentExam->student?->currentStudentClassroom?->classroom?->name ?? '' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hasil Penilaian Siswa --}}
                <div class="card rounded-4 border-0 shadow-sm overflow-hidden">
                    <div class="card-header fw-bold fs-6 border-0 py-3 px-4 text-white bg-primary">
                        Hasil Penilaian Siswa
                    </div>

                    <div class="card-body px-4 py-4">
                        {{-- Tanggal & waktu ujian --}}
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="bg-primary-subtle rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="width:42px; height:42px; flex-shrink:0;">
                                <i class="ti ti-calendar text-primary fs-5"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold">
                                    {{ $studentExam->started_at->translatedFormat('d F Y') }}
                                </p>
                                <p class="mb-0 fw-semibold">
                                    {{ $studentExam->started_at->translatedFormat('H:i:s') . ' - ' . $studentExam->finished_at->translatedFormat('H:i:s') }}
                                </p>
                                <small class="text-muted">Tanggal Dan Waktu Ujian</small>
                            </div>
                        </div>

                        {{-- Jumlah soal --}}
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="bg-warning-subtle rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="width:42px; height:42px; flex-shrink:0;">
                                <i class="ti ti-clipboard-list text-warning fs-5"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold">
                                    {{ $studentExam->exam->total_questions }}
                                    Soal
                                </p>
                                <small class="text-muted">Jumlah Soal</small>
                            </div>
                        </div>

                        {{-- Jawaban benar --}}
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="bg-success-subtle rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="width:42px; height:42px; flex-shrink:0;">
                                <i class="ti ti-check text-success fs-5"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold">
                                    {{ $studentExam->correct_answer_count ?? 0 }}
                                    Dari
                                    {{ $studentExam->total_questions_count }}
                                    Soal
                                </p>
                                <small class="text-muted">Jawaban Benar</small>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Nilai --}}
                        <div class="text-center mb-3">
                            <p class="fw-bold mb-1 fs-6">Nilai</p>
                            <h2 style="font-size: 3rem; font-weight: 900;" class="text-primary">
                                {{ $studentExam->score ?? '-' }}
                            </h2>
                        </div>

                        <hr class="my-3">

                        {{-- Status --}}
                        <div class="text-center">
                            <p class="fw-bold mb-2">Hasil Ujian</p>

                            @if ($studentExam->status == 'passed')
                                <span class="badge bg-success-subtle text-success px-4 py-2 fs-6 rounded-2">
                                    Lulus
                                </span>
                            @elseif ($studentExam->status == 'not_passed')
                                <span class="badge bg-danger-subtle text-danger px-4 py-2 fs-6 rounded-2">
                                    Tidak Lulus
                                </span>
                            @elseif ($studentExam->status == 'grading')
                                <span class="badge bg-warning-subtle text-warning px-4 py-2 fs-6 rounded-2">
                                    Belum Dinilai
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning px-4 py-2 fs-6 rounded-2">
                                    {{ $studentExam->status ?? '-' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- KOLOM KANAN --}}
        <div class="col-12 col-lg-9">
            <div class="card rounded-4 border-0 shadow-sm overflow-hidden">
                <div class="card-header fw-bold fs-6 border-0 py-3 px-4 text-white bg-primary">
                    Hasil Ujian
                </div>

                <div class="card-body p-md-4 p-0">
                    <div id="question-list">
                        @forelse ($studentExam->studentQuestionAnswers as $answer)
                            <div class="card border bg-light-primary shadow-sm rounded-2 p-md-3 p-0 mb-4">
                                <p>
                                    @if ($answer->examQuestion->type == 'essay')
                                        Dinilai oleh: {{ $answer->employee?->user?->name ?? '-' }}
                                    @endif
                                </p>
                                @if ($answer->examQuestion->type == 'single_choice' || $answer->examQuestion->type == 'multiple_choice')
                                    <div
                                        class="bg-white rounded-4 d-flex justify-content-between align-items-start gap-3 mb-3 px-4 py-3">
                                        <div class="fw-semibold d-flex gap-2">
                                            <span>
                                                {{ $loop->iteration }}
                                            </span>
                                            <span>
                                                {!! $answer->examQuestion->question ?? '-' !!}
                                                @if ($answer->examQuestion->type == 'multiple_choice')
                                                    <span class="text-muted">(Jawaban lebih dari satu)</span>
                                                @endif
                                            </span>
                                        </div>

                                        @if ($answer->studentAnswerOptions->isEmpty())
                                            <span class="badge bg-warning-subtle text-warning flex-shrink-0">Tidak
                                                Menjawab</span>
                                        @else
                                            @if ($answer->is_correct)
                                                <span class="badge bg-light-success text-success flex-shrink-0">Benar</span>
                                            @else
                                                <span class="badge bg-light-danger text-danger flex-shrink-0">Salah</span>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="bg-white rounded-4 px-4 py-3 d-flex flex-column gap-3">
                                        @foreach ($answer->examQuestion->sortExamQuestionOptions as $option)
                                            <div
                                                class="p-3 rounded-2 d-flex gap-2
                                            @if (in_array($option->id, $answer->examQuestion->correctQuestionOptions->pluck('id')->toArray())) border border-success bg-success-subtle
                                            @elseif (in_array($option->id, $answer->studentAnswerOptions->pluck('exam_question_option_id')->toArray()))
                                                border border-danger bg-danger-subtle
                                            @else
                                                bg-light-primary @endif">
                                                <strong
                                                    style="min-width: 24px; flex-shrink: 0;">{{ strtoupper(substr($option->option_label, -1)) }}.</strong>
                                                <span>{!! $option->option_text !!}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif ($answer->examQuestion->type == 'matching')
                                    @php
                                        $correctItems = $answer->examQuestion->leftExamQuestionMatchings
                                            ->pluck('leftItemAnswer')
                                            ->map(function ($answer) {
                                                return [
                                                    'left_item_id' => $answer->left_item_id,
                                                    'right_item_id' => $answer->right_item_id,
                                                ];
                                            })
                                            ->toArray();
                                    @endphp

                                    <div
                                        class="bg-white rounded-4 d-flex justify-content-between align-items-start gap-3 mb-3 px-4 py-3">
                                        <div class="fw-semibold d-flex gap-2">
                                            <span>
                                                {{ $loop->iteration }}
                                            </span>
                                            <span>
                                                {!! $answer->examQuestion->question ?? '-' !!}
                                            </span>
                                        </div>

                                        @if ($answer->studentAnswerMatchings->isEmpty())
                                            <span class="badge bg-warning-subtle text-warning flex-shrink-0">Tidak
                                                Menjawab</span>
                                        @else
                                            @if ($answer->is_correct)
                                                <span class="badge bg-light-success text-success flex-shrink-0">{{ $answer->examQuestion->score . '/' .$answer->examQuestion->score }}</span>
                                            @else
                                                <span class="badge bg-light-danger text-danger flex-shrink-0">{{  '0/' .$answer->examQuestion->score }}</span>
                                            @endif
                                        @endif
                                    </div>

                                    @if ($answer->studentAnswerMatchings->count() > 0)
                                        <div class="rounded-4 d-flex flex-column bg-white px-4 py-3 position-relative">
                                            @foreach ($answer->studentAnswerMatchings as $index => $studentAnswerMatching)
                                                @php
                                                    $left = $studentAnswerMatching->leftExamQuestionMatching;
                                                    $right = $studentAnswerMatching->rightExamQuestionMatching;

                                                    $answerArray = [
                                                        'left_item_id' => $left->id,
                                                        'right_item_id' => $right->id,
                                                    ];

                                                    $isCorrect = collect($correctItems)->contains(function ($item) use (
                                                        $answerArray,
                                                    ) {
                                                        return $item['left_item_id'] == $answerArray['left_item_id'] &&
                                                            $item['right_item_id'] == $answerArray['right_item_id'];
                                                    });
                                                @endphp

                                                <div class="row js-matching-board position-relative"
                                                    data-connections=@json($answerArray)>
                                                    <div class="col-5 mb-2">
                                                        <div
                                                            class="p-3 rounded-3 border @if ($isCorrect) border-success bg-success-subtle @else border-danger bg-danger-subtle @endif h-100 position-relative">

                                                            {!! $left->item_text !!}

                                                            <span class="js-anchor position-absolute bg-primary"
                                                                data-node="{{ $left->id }}"
                                                                style="
                                                                    top:50%;
                                                                    right:-8px;
                                                                    width:12px;
                                                                    height:12px;
                                                                    border-radius:999px;
                                                                    transform:translateY(-50%);
                                                                    box-shadow:0 0 0 4px rgba(93,135,255,.15);
                                                            "></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-2 mb-2"></div>

                                                    <div class="col-5 mb-2">
                                                        <div
                                                            class="p-3 rounded-3 border @if ($isCorrect) border-success bg-success-subtle @else border-danger bg-danger-subtle @endif h-100 position-relative">

                                                            {!! $right->item_text !!}

                                                            <span class="js-anchor position-absolute bg-primary"
                                                                data-node="{{ $right->id }}"
                                                                style="
                                                                    top:50%;
                                                                    left:-8px;
                                                                    width:12px;
                                                                    height:12px;
                                                                    border-radius:999px;
                                                                    transform:translateY(-50%);
                                                                    box-shadow:0 0 0 4px rgba(93,135,255,.15);
                                                            "></span>
                                                        </div>
                                                    </div>
                                                    <svg class="position-absolute top-0 start-0 w-100 h-100 js-matching-svg"
                                                        style="pointer-events:none; z-index: 10;" aria-hidden="true"></svg>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted mb-0 small">
                                            Belum ada pasangan penjodohan.
                                        </p>
                                    @endif
                                @elseif ($answer->examQuestion->type == 'essay')
                                    <div
                                        class="bg-white rounded-4 d-flex justify-content-between align-items-start gap-3 mb-3 px-4 py-3">
                                        <div class="fw-semibold d-flex gap-2">
                                            <span>
                                                {{ $loop->iteration }}
                                            </span>
                                            <span>
                                                {!! $answer->examQuestion->question ?? '-' !!}
                                            </span>
                                        </div>

                                        @if (is_null($answer->is_correct))
                                            <span class="badge bg-warning-subtle text-warning flex-shrink-0">... /
                                                {{ $answer->examQuestion->score }}</span>
                                        @else
                                            @if ($answer->is_correct)
                                                <span
                                                    class="badge bg-light-success text-success flex-shrink-0">{{ $answer->score . ' / ' . $answer->examQuestion->score }}</span>
                                            @else
                                                <span
                                                    class="badge bg-light-danger text-danger flex-shrink-0">{{ $answer->score . ' / ' . $answer->examQuestion->score }}</span>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="px-4 py-3 rounded-4 bg-white position-relative">
                                        <div class="p-3 rounded-4 bg-light-primary">
                                            <div class="text-dark">
                                                {!! $answer->answer_text ?? ($answer->answer ?? '<span class="text-muted">Tidak ada jawaban</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" width="220"
                                    alt="No Data">
                                <p class="fw-semibold mt-3 mb-1">Siswa belum mengisi ujian.</p>
                                <small class="text-muted">Belum ada jawaban yang tersimpan untuk ujian ini.</small>
                            </div>

                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('teacher.pages.exam-monitoring.student-detail.scripts.index')
@endsection
