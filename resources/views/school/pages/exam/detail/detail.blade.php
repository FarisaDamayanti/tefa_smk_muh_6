@extends('school.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
@endsection

@section('content')
    <x-authenticated.breadcrumb title="Detail Siswa" breadcrumb="Detail Hasil Ujian Siswa" />

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('school.exams.show', $studentExam->exam_id) }}?tab=answers"
            class="btn btn-warning d-inline-flex align-items-center gap-2">
            Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        {{-- LEFT --}}
        <div class="col-12 col-lg-3">
            <div id="sticky-cards" style="position: sticky; top: -100px;">

                {{-- CARD: INFORMASI SISWA --}}
                <div class="card rounded-4 border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="card-header bg-primary text-white fw-bold fs-6 border-0 py-3 px-4">
                        Data Informasi Siswa
                    </div>

                    <div class="card-body text-center px-4 pt-4 pb-3">
                        <div class="mb-3">
                            <img src="{{ $studentExam->student?->user?->image_url ??
                                ($studentExam->student?->user?->image
                                    ? asset('storage/' . $studentExam->student->user->image)
                                    : asset('assets/images/default-user.jpeg')) }}"
                                alt="Foto Siswa" class="rounded-circle border border-3 border-primary-subtle" width="90"
                                height="90" style="object-fit: cover;">
                        </div>

                        <h6 class="fw-bold mb-1">
                            {{ $studentExam->student?->user?->name ?? '-' }}
                        </h6>

                        <span class="badge bg-primary-subtle text-primary ms-1 mb-3" style="font-size: 0.85rem;">
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
                                        {{ ($studentExam->student?->currentStudentClassroom?->classroom?->levelClass?->name
                                            ? str($studentExam->student->currentStudentClassroom->classroom->levelClass->name)->replace('Kelas ', '')->toString()
                                            : null) && $studentExam->student?->currentStudentClassroom?->classroom?->name
                                            ? str($studentExam->student->currentStudentClassroom->classroom->levelClass->name)->replace('Kelas ', '')->toString() .
                                                ' ' .
                                                $studentExam->student->currentStudentClassroom->classroom->name
                                            : '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD: HASIL PENILAIAN --}}
                <div class="card rounded-4 border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-primary text-white fw-bold fs-6 border-0 py-3 px-4">
                        Hasil Penilaian Siswa
                    </div>

                    <div class="card-body px-4 py-4">
                        {{-- Tanggal & waktu --}}
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="bg-primary-subtle rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="width:42px; height:42px; flex-shrink:0;">
                                <i class="ti ti-calendar text-primary fs-5"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold">
                                    {{ $studentExam->exam?->exam_date
                                        ? $studentExam->exam->exam_date->translatedFormat('d F Y, H:i')
                                        : '-' }}
                                    @if ($studentExam->exam?->exam_date && ($studentExam->exam?->duration ?? 0) > 0)
                                        -
                                        {{ $studentExam->exam->exam_date->addMinutes((int) ($studentExam->exam->duration ?? 0))->format('H:i') }}
                                    @endif
                                </p>
                                <small class="text-muted">Tanggal Dan Waktu Ujian</small>
                            </div>
                        </div>

                        {{-- Jumlah Soal --}}
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="bg-warning-subtle rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="width:42px; height:42px; flex-shrink:0;">
                                <i class="ti ti-clipboard-list text-warning fs-5"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold">
                                    {{ $studentExam->exam?->total_questions ?? 0 }} Soal
                                </p>
                                <small class="text-muted">Jumlah Soal</small>
                            </div>
                        </div>

                        {{-- Jawaban Benar --}}
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div class="bg-success-subtle rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="width:42px; height:42px; flex-shrink:0;">
                                <i class="ti ti-check text-success fs-5"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold">
                                    {{ $studentExam->correct_answer_count ?? 0 }}
                                    Dari
                                    {{ $studentExam->total_questions_count ?? 0 }} Soal
                                </p>
                                <small class="text-muted">Jawaban Benar</small>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Nilai --}}
                        <div class="text-center mb-3">
                            <p class="fw-bold mb-1 fs-5">Nilai Kamu</p>
                            <h2
                                style="font-size: 3rem; font-weight: 900; background: linear-gradient(to bottom, #5dbafc, #4371fe); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                {{ is_null($studentExam->score) ? '-' : number_format((float) $studentExam->score, 2, ',', '.') }}
                            </h2>
                        </div>

                        <hr class="my-3">

                        {{-- Status --}}
                        <div class="text-center">
                            <p class="fw-bold mb-2">Hasil Ujian</p>
                            <span
                                class="badge
                                {{ in_array(strtolower((string) ($studentExam->status ?? '')), ['lulus', 'passed'], true)
                                    ? 'bg-success-subtle text-success'
                                    : (in_array(strtolower((string) ($studentExam->status ?? '')), ['tidak lulus', 'failed'], true)
                                        ? 'bg-danger-subtle text-danger'
                                        : (in_array(strtolower((string) ($studentExam->status ?? '')), ['tidak mengerjakan', 'not_submitted'], true)
                                            ? 'bg-danger-subtle text-danger'
                                            : 'bg-warning-subtle text-warning')) }}
                                px-4 py-2 fs-6 rounded-2">
                                {{ $studentExam->status
                                    ? (in_array(strtolower((string) $studentExam->status), ['passed'], true)
                                        ? 'Lulus'
                                        : 'Tidak Lulus')
                                    : (is_null($studentExam->score)
                                        ? 'Sedang Mengerjakan'
                                        : 'Selesai') }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- RIGHT --}}
        <div class="col-12 col-lg-9">
            <div id="question-list">
                @if (($studentExam->studentQuestionAnswers?->count() ?? 0) === 0)
                    <div class="d-flex flex-column justify-content-center align-items-center py-5">
                        <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt="No Data" width="280px">
                        <p class="fs-5 text-dark text-center mt-3 mb-0">Belum ada jawaban</p>
                        <span class="text-muted">Data akan muncul setelah siswa mengerjakan ujian.</span>
                    </div>
                @else
                    @foreach ($studentExam->studentQuestionAnswers as $i => $ans)
                        <div class="card border border-1 bg-light-primary rounded-4 p-3 mb-4 question-item">
                            <div class="p-3 rounded-4 bg-white text-dark">
                                <div class="d-flex align-items-center justify-content-between gap-3">
                                    <div class="fw-semibold d-flex gap-2 align-items-start">
                                        <span class="number">{{ $i + 1 }}.</span>
                                        <div class="question">{!! $ans->examQuestion?->question ?? '-' !!}</div>
                                    </div>

                                    @if (empty($ans->answer))
                                        <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-2">Tidak
                                            Menjawab</span>
                                    @elseif ($ans->is_correct)
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-2">Benar</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-2">Salah</span>
                                    @endif
                                </div>
                            </div>

                            {{-- LIST OPSI --}}
                            <div class="p-4 rounded-4 bg-white text-dark options mt-3">

                                {{-- A --}}
                                <div
                                    class="mb-3 p-3 rounded-4 d-flex gap-2 align-items-start
                                    {{ str_replace('OPTION_', '', strtoupper((string) ($ans->answer ?? ''))) === 'A'
                                        ? 'border border-2 border-primary text-primary'
                                        : (str_replace('OPTION_', '', strtoupper((string) ($ans->examQuestion?->answer ?? ''))) === 'A' &&
                                        str_replace('OPTION_', '', strtoupper((string) ($ans->answer ?? ''))) !==
                                            str_replace('OPTION_', '', strtoupper((string) ($ans->examQuestion?->answer ?? '')))
                                            ? 'border border-success bg-success-subtle text-success'
                                            : 'bg-light-primary') }}">
                                    <div class="fw-semibold">A.</div>
                                    <div class="flex-grow-1">{!! $ans->examQuestion?->option_a ?? '-' !!}</div>
                                </div>

                                {{-- B --}}
                                <div
                                    class="mb-3 p-3 rounded-4 d-flex gap-2 align-items-start
                                    {{ str_replace('OPTION_', '', strtoupper((string) ($ans->answer ?? ''))) === 'B'
                                        ? 'border border-2 border-primary text-primary'
                                        : (str_replace('OPTION_', '', strtoupper((string) ($ans->examQuestion?->answer ?? ''))) === 'B' &&
                                        str_replace('OPTION_', '', strtoupper((string) ($ans->answer ?? ''))) !==
                                            str_replace('OPTION_', '', strtoupper((string) ($ans->examQuestion?->answer ?? '')))
                                            ? 'border border-success bg-success-subtle text-success'
                                            : 'bg-light-primary') }}">
                                    <div class="fw-semibold">B.</div>
                                    <div class="flex-grow-1">{!! $ans->examQuestion?->option_b ?? '-' !!}</div>
                                </div>

                                {{-- C --}}
                                <div
                                    class="mb-3 p-3 rounded-4 d-flex gap-2 align-items-start
                                    {{ str_replace('OPTION_', '', strtoupper((string) ($ans->answer ?? ''))) === 'C'
                                        ? 'border border-2 border-primary text-primary'
                                        : (str_replace('OPTION_', '', strtoupper((string) ($ans->examQuestion?->answer ?? ''))) === 'C' &&
                                        str_replace('OPTION_', '', strtoupper((string) ($ans->answer ?? ''))) !==
                                            str_replace('OPTION_', '', strtoupper((string) ($ans->examQuestion?->answer ?? '')))
                                            ? 'border border-success bg-success-subtle text-success'
                                            : 'bg-light-primary') }}">
                                    <div class="fw-semibold">C.</div>
                                    <div class="flex-grow-1">{!! $ans->examQuestion?->option_c ?? '-' !!}</div>
                                </div>

                                {{-- D --}}
                                <div
                                    class="mb-3 p-3 rounded-4 d-flex gap-2 align-items-start
                                    {{ str_replace('OPTION_', '', strtoupper((string) ($ans->answer ?? ''))) === 'D'
                                        ? 'border border-2 border-primary text-primary'
                                        : (str_replace('OPTION_', '', strtoupper((string) ($ans->examQuestion?->answer ?? ''))) === 'D' &&
                                        str_replace('OPTION_', '', strtoupper((string) ($ans->answer ?? ''))) !==
                                            str_replace('OPTION_', '', strtoupper((string) ($ans->examQuestion?->answer ?? '')))
                                            ? 'border border-success bg-success-subtle text-success'
                                            : 'bg-light-primary') }}">
                                    <div class="fw-semibold">D.</div>
                                    <div class="flex-grow-1">{!! $ans->examQuestion?->option_d ?? '-' !!}</div>
                                </div>

                                {{-- E --}}
                                <div
                                    class="p-3 rounded-4 d-flex gap-2 align-items-start
                                    {{ str_replace('OPTION_', '', strtoupper((string) ($ans->answer ?? ''))) === 'E'
                                        ? 'border border-2 border-primary text-primary'
                                        : (str_replace('OPTION_', '', strtoupper((string) ($ans->examQuestion?->answer ?? ''))) === 'E' &&
                                        str_replace('OPTION_', '', strtoupper((string) ($ans->answer ?? ''))) !==
                                            str_replace('OPTION_', '', strtoupper((string) ($ans->examQuestion?->answer ?? '')))
                                            ? 'border border-success bg-success-subtle text-success'
                                            : 'bg-light-primary') }}">
                                    <div class="fw-semibold">E.</div>
                                    <div class="flex-grow-1">{!! $ans->examQuestion?->option_e ?? '-' !!}</div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
