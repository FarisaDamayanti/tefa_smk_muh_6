@extends('school.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/exam-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/detail-exam.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Detail Ujian" breadcrumb="Lihat Hasil Ujian" />

    <div class="container-fluid d-flex justify-content-end pb-4">
        <a href="{{ route('school.exam-detail', [
            'exam' => $studentExam->exam?->id,
            'classroom' => $studentExam->student?->currentStudentClassroom?->classroom?->id,
        ]) }}"
            class="btn btn-warning d-inline-flex align-items-center gap-2">
            Kembali
        </a>
    </div>

    <div class="row g-4">

        <div class="col-12 col-lg-3">

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

                    <h6 class="fw-bold mb-1">
                        {{ $studentExam->student?->user?->name ?? '-' }}
                    </h6>

                    <span class="badge bg-primary-subtle text-primary mb-3" style="font-size: 0.82rem;">
                        {{ $studentExam->student?->user?->email ?? '-' }}
                    </span>

                    <div class="d-flex justify-content-center mt-2">
                        <div class="text-start px-2">
                            <div class="d-flex gap-2 mb-1">
                                <span class="fw-semibold text-dark" style="width: 50px;">NISN</span>
                                <span class="text-muted">:</span>
                                <span>{{ $studentExam->student?->nisn ?? '-' }}</span>
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

            <div class="card rounded-4 border-0 shadow-sm overflow-hidden">
                <div class="card-header fw-bold fs-6 border-0 py-3 px-4 text-white bg-primary">
                    Hasil Penilaian Siswa
                </div>
                <div class="card-body px-4 py-4">

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

                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="bg-warning-subtle rounded-3 p-2 d-flex align-items-center justify-content-center"
                            style="width:42px; height:42px; flex-shrink:0;">
                            <i class="ti ti-clipboard-list text-warning fs-5"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold">{{ $studentExam->total_questions_count ?? 0 }} Soal</p>
                            <small class="text-muted">Jumlah Soal</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bg-success-subtle rounded-3 p-2 d-flex align-items-center justify-content-center"
                            style="width:42px; height:42px; flex-shrink:0;">
                            <i class="ti ti-check text-success fs-5"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold">
                                {{ $studentExam->correct_answer_count ?? 0 }} Dari
                                {{ $studentExam->total_questions_count ?? 0 }} Soal
                            </p>
                            <small class="text-muted">Jawaban Benar</small>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="text-center mb-3">
                        <p class="fw-bold mb-1 fs-6">Nilai</p>
                        <h2 style="font-size: 3rem; font-weight: 900;" class="text-primary">
                            {{ $studentExam->score ?? '-' }}
                        </h2>
                    </div>

                    <hr class="my-3">

                    <div class="text-center">
                        <p class="fw-bold mb-2">Hasil Ujian</p>

                        @if (($studentExam->status ?? '') === 'passed')
                            <span class="badge bg-success-subtle text-success px-4 py-2 fs-6 rounded-2">Lulus</span>
                        @elseif (($studentExam->status ?? '') === 'not_passed')
                            <span class="badge bg-danger-subtle text-danger px-4 py-2 fs-6 rounded-2">Tidak Lulus</span>
                        @elseif (($studentExam->status ?? '') === 'grading')
                            <span class="badge bg-warning-subtle text-warning px-4 py-2 fs-6 rounded-2">Belum Dinilai</span>
                        @elseif (($studentExam->status ?? '') === 'on_progress')
                            <span class="badge bg-primary-subtle text-primary px-4 py-2 fs-6 rounded-2">Sedang
                                Mengerjakan</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary px-4 py-2 fs-6 rounded-2">
                                {{ $studentExam->status ?? '-' }}
                            </span>
                        @endif
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
                <div class="card-body p-4">
                    <div id="question-list">

                        @if (($studentExam->studentQuestionAnswers ?? collect())->isEmpty())
                            <div class="text-center py-5">
                                <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" width="220"
                                    alt="No Data">
                                <p class="fw-semibold mt-3 mb-1">Siswa belum mengisi ujian.</p>
                                <small class="text-muted">Belum ada jawaban yang tersimpan untuk ujian ini.</small>
                            </div>
                        @else
                            @foreach ($studentExam->studentQuestionAnswers as $answer)
                                <div class="card border-0 shadow-sm rounded-3 p-3 mb-4">

                                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                        <div class="fw-semibold d-flex gap-2 align-items-start">
                                            <span>{{ $loop->iteration }}.</span>
                                            <div class="question-text">
                                                {!! $answer->examQuestion->question ?? '-' !!}
                                            </div>
                                        </div>

                                        @if (($answer->examQuestion->type ?? '') !== 'essay')
                                            @if (
                                                !$answer->is_correct &&
                                                    empty($answer->answer) &&
                                                    empty($answer->answer_text) &&
                                                    $answer->studentAnswerOptions->isEmpty() &&
                                                    $answer->studentAnswerMatchings->isEmpty())
                                                <span class="badge bg-warning-subtle text-warning flex-shrink-0">Tidak
                                                    Menjawab</span>
                                            @elseif ($answer->is_correct === 1)
                                                <span class="badge bg-light-success text-success flex-shrink-0">Benar</span>
                                            @elseif ($answer->is_correct === 0)
                                                <span class="badge bg-light-danger text-danger flex-shrink-0">Salah</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary flex-shrink-0">Belum
                                                    Dinilai</span>
                                            @endif
                                        @endif
                                    </div>

                                    {{-- Tipe: single_choice / multiple_choice --}}
                                    @if (in_array($answer->examQuestion->type ?? '', ['single_choice', 'multiple_choice']))
                                        <div>
                                            @forelse ($answer->examQuestion->sortExamQuestionOptions ?? [] as $option)
                                                <div
                                                    class="mb-2 py-2 px-3 rounded-3 d-flex gap-2
                                                    {{ collect($answer->examQuestion->correctQuestionOptions ?? [])->pluck('id')->contains($option->id)
                                                        ? 'border border-primary bg-primary-subtle'
                                                        : (collect($answer->studentAnswerOptions ?? [])->pluck('exam_question_option_id')->contains($option->id)
                                                            ? 'border border-danger bg-light-danger'
                                                            : 'bg-light') }}">
                                                    <strong style="min-width: 24px; flex-shrink: 0;">
                                                        {{ strtoupper(str_replace('option_', '', $option->option_label ?? '')) }}.
                                                    </strong>
                                                    <span>{!! $option->option_text ?? '-' !!}</span>
                                                </div>
                                            @empty
                                                <div class="p-3 rounded-3 bg-light text-muted">
                                                    Opsi jawaban tidak tersedia.
                                                </div>
                                            @endforelse
                                        </div>

                                        {{-- Tipe: essay --}}
                                    @elseif (($answer->examQuestion->type ?? '') === 'essay')
                                        <div class="rounded-3 bg-light p-3">
                                            <div
                                                class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
                                                <div>
                                                    <small class="text-muted fs-3">
                                                        Dinilai oleh: {{ $answer->employee?->user?->name ?? '-' }}
                                                    </small>
                                                </div>

                                                <span class="badge bg-primary-subtle text-primary flex-shrink-0">
                                                    {{ $answer->score !== null ? $answer->score : '...' }} /
                                                    {{ $answer->examQuestion->score ?? 0 }}
                                                </span>
                                            </div>

                                            <div class="rounded-3 bg-white p-3">
                                                <div class="fw-semibold mb-2">Jawaban Siswa : </div>
                                                <div class="text-dark">
                                                    {!! $answer->answer_text ?? ($answer->answer ?? '<span class="text-muted">Tidak ada jawaban</span>') !!}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Tipe: matching --}}
                                    @elseif (($answer->examQuestion->type ?? '') === 'matching')
                                        @php
                                            $correctItems = $answer->examQuestion->leftExamQuestionMatchings
                                                ->pluck('leftItemAnswer')
                                                ->filter()
                                                ->map(
                                                    fn($a) => [
                                                        'left_item_id' => $a->left_item_id,
                                                        'right_item_id' => $a->right_item_id,
                                                    ],
                                                )
                                                ->toArray();
                                        @endphp

                                        <div class="rounded-3 bg-light p-3">
                                            <div
                                                class="d-flex justify-content-between align-items-start gap-3 flex-wrap mb-3">
                                                <div class="fw-semibold">Jawaban Siswa :</div>
                                                <span class="badge bg-primary-subtle text-primary flex-shrink-0">
                                                    Bobot: {{ $answer->examQuestion->score ?? 0 }}
                                                </span>
                                            </div>

                                            @if (($answer->studentAnswerMatchings ?? collect())->isNotEmpty())
                                                <div class="p-3 rounded-3 bg-white border border-info-subtle shadow-sm">
                                                    <div class="row gx-2 mb-3">
                                                        <div class="col-5 text-center">
                                                            <span
                                                                class="fw-semibold text-muted small text-uppercase">Pernyataan</span>
                                                        </div>
                                                        <div class="col-2"></div>
                                                        <div class="col-5 text-center">
                                                            <span
                                                                class="fw-semibold text-muted small text-uppercase">Jawaban</span>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex flex-column gap-3">
                                                        @foreach ($answer->studentAnswerMatchings as $matchItem)
                                                            @php
                                                                $left = $matchItem->leftExamQuestionMatching;
                                                                $right = $matchItem->rightExamQuestionMatching;
                                                                $isCorrect = collect($correctItems)->contains(
                                                                    fn($item) => $item['left_item_id'] == $left?->id &&
                                                                        $item['right_item_id'] == $right?->id,
                                                                );
                                                            @endphp

                                                            <div class="row js-matching-board position-relative"
                                                                data-connections='@json(['left_item_id' => $left?->id, 'right_item_id' => $right?->id])'>

                                                                <div class="col-5">
                                                                    <div
                                                                        class="p-3 rounded-3 border h-100 position-relative
                                                                    {{ $isCorrect ? 'border-success bg-success-subtle' : 'border-danger bg-danger-subtle' }}">
                                                                        {!! $left?->item_text ?? '-' !!}
                                                                        <span
                                                                            class="js-anchor position-absolute bg-primary"
                                                                            data-node="{{ $left?->id }}"
                                                                            style="top:50%;right:-7px;width:12px;height:12px;border-radius:999px;transform:translateY(-50%);box-shadow:0 0 0 4px rgba(93,135,255,.15);z-index:1;">
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <div class="col-2 d-none d-md-block"></div>

                                                                <div class="col-5">
                                                                    <div
                                                                        class="p-3 rounded-3 border h-100 position-relative
                                                                       {{ $isCorrect ? 'border-success bg-success-subtle' : 'border-danger bg-danger-subtle' }}">
                                                                        <span
                                                                            class="js-anchor position-absolute bg-primary"
                                                                            data-node="{{ $right?->id }}"
                                                                            style="top:50%;left:-7px;width:12px;height:12px;border-radius:999px;transform:translateY(-50%);box-shadow:0 0 0 4px rgba(93,135,255,.15);z-index:1;">
                                                                        </span>
                                                                        {!! $right?->item_text ?? '-' !!}
                                                                    </div>
                                                                </div>

                                                                <svg class="position-absolute top-0 start-0 w-100 h-100 js-matching-svg"
                                                                    style="pointer-events:none; z-index:10;"
                                                                    aria-hidden="true"></svg>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="p-3 rounded-4 bg-light border">
                                                    <em class="text-muted">Tidak dijawab</em>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="rounded-3 bg-light p-3">
                                            <div class="fw-semibold mb-2">Jawaban Siswa</div>
                                            <div class="text-dark">
                                                {{ $answer->answer ?? '-' }}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('school.pages.exam.detail.scripts.exam-detail-show')
@endsection
