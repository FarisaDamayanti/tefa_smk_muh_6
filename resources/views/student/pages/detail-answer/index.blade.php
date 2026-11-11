@extends('student.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/answer.css') }}">
@endsection

@section('content')
    <div class="student-subjects-page">

        {{-- HEADER --}}
        <div class="card bg-primary shadow-none position-relative overflow-hidden text-light mb-4">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-8 col-md-9">
                        <h4 class="fw-semibold mb-2 text-light">
                            Detail Jawaban pada {{ $studentExam->exam->title }}
                        </h4>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item text-light">Detail jawaban</li>
                        </ol>
                    </div>
                    <div class="col-4 col-md-3 text-center mb-n5">
                        <img src="{{ asset('admin_assets/dist/images/breadcrumb/ChatBc.png') }}" class="img-fluid mb-n4"
                            alt="">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('student.exams.index') }}" class="btn btn-warning px-4">
                Kembali
            </a>
        </div>

        <div class="row g-4 align-items-start">

            {{-- =============================================
             SIDEBAR KIRI — Hasil Penilaian
             ============================================= --}}
            <div class="col-12 col-lg-3">
                <div class="da-left-sticky">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                        <div class="px-4 py-3 text-white fw-semibold da-card-header">
                            Hasil Penilaian
                        </div>

                        <div class="card-body p-4">

                            {{-- Tanggal & Waktu --}}
                            <div class="d-flex gap-3 align-items-start pb-3 border-bottom">
                                <div class="da-icon bg-light rounded-3 d-flex align-items-center justify-content-center">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.3307 18.6666C11.7728 18.6666 12.1967 18.4911 12.5092 18.1785C12.8218 17.8659 12.9974 17.442 12.9974 17C12.9974 16.558 12.8218 16.134 12.5092 15.8215C12.1967 15.5089 11.7728 15.3333 11.3307 15.3333C10.8887 15.3333 10.4648 15.5089 10.1522 15.8215C9.83966 16.134 9.66406 16.558 9.66406 17C9.66406 17.442 9.83966 17.8659 10.1522 18.1785C10.4648 18.4911 10.8887 18.6666 11.3307 18.6666ZM11.3307 23.3333C11.7728 23.3333 12.1967 23.1577 12.5092 22.8452C12.8218 22.5326 12.9974 22.1087 12.9974 21.6666C12.9974 21.2246 12.8218 20.8007 12.5092 20.4881C12.1967 20.1756 11.7728 20 11.3307 20C10.8887 20 10.4648 20.1756 10.1522 20.4881C9.83966 20.8007 9.66406 21.2246 9.66406 21.6666C9.66406 22.1087 9.83966 22.5326 10.1522 22.8452C10.4648 23.1577 10.8887 23.3333 11.3307 23.3333ZM17.6641 17C17.6641 17.442 17.4885 17.8659 17.1759 18.1785C16.8633 18.4911 16.4394 18.6666 15.9974 18.6666C15.5554 18.6666 15.1314 18.4911 14.8189 18.1785C14.5063 17.8659 14.3307 17.442 14.3307 17C14.3307 16.558 14.5063 16.134 14.8189 15.8215C15.1314 15.5089 15.5554 15.3333 15.9974 15.3333C16.4394 15.3333 16.8633 15.5089 17.1759 15.8215C17.4885 16.134 17.6641 16.558 17.6641 17ZM15.9974 23.3333C16.4394 23.3333 16.8633 23.1577 17.1759 22.8452C17.4885 22.5326 17.6641 22.1087 17.6641 21.6666C17.6641 21.2246 17.4885 20.8007 17.1759 20.4881C16.8633 20.1756 16.4394 20 15.9974 20C15.5554 20 15.1314 20.1756 14.8189 20.4881C14.5063 20.8007 14.3307 21.2246 14.3307 21.6666C14.3307 22.1087 14.5063 22.5326 14.8189 22.8452C15.1314 23.1577 15.5554 23.3333 15.9974 23.3333ZM22.3307 17C22.3307 17.442 22.1551 17.8659 21.8426 18.1785C21.53 18.4911 21.1061 18.6666 20.6641 18.6666C20.222 18.6666 19.7981 18.4911 19.4856 18.1785C19.173 17.8659 18.9974 17.442 18.9974 17C18.9974 16.558 19.173 16.134 19.4856 15.8215C19.7981 15.5089 20.222 15.3333 20.6641 15.3333C21.1061 15.3333 21.53 15.5089 21.8426 15.8215C22.1551 16.134 22.3307 16.558 22.3307 17Z"
                                            fill="#5D87FF" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M10.6641 4.33331C10.9293 4.33331 11.1836 4.43867 11.3712 4.62621C11.5587 4.81374 11.6641 5.0681 11.6641 5.33331V6.33331H20.3307V5.33331C20.3307 5.0681 20.4361 4.81374 20.6236 4.62621C20.8112 4.43867 21.0655 4.33331 21.3307 4.33331C21.5959 4.33331 21.8503 4.43867 22.0378 4.62621C22.2254 4.81374 22.3307 5.0681 22.3307 5.33331V6.34398C22.5334 6.34931 22.7223 6.35909 22.8974 6.37331C23.4041 6.41331 23.8787 6.50398 24.3281 6.73331C25.0181 7.08487 25.5792 7.64591 25.9307 8.33598C26.1601 8.78531 26.2507 9.25998 26.2907 9.76665C26.3307 10.2533 26.3307 10.8466 26.3307 11.56V21.7733C26.3307 22.4866 26.3307 23.08 26.2907 23.5666C26.2507 24.0733 26.1601 24.548 25.9307 24.9973C25.5795 25.6872 25.019 26.2482 24.3294 26.6C23.8787 26.8293 23.4041 26.92 22.8974 26.96C22.4107 27 21.8174 27 21.1054 27H10.8907C10.1774 27 9.58406 27 9.0974 26.96C8.59073 26.92 8.11606 26.8293 7.66673 26.6C6.97706 26.2491 6.41608 25.6891 6.06406 25C5.83473 24.5493 5.74406 24.0746 5.70406 23.568C5.66406 23.0813 5.66406 22.488 5.66406 21.776V11.56C5.66406 10.8466 5.66406 10.2533 5.70406 9.76665C5.74406 9.25998 5.83473 8.78531 6.06406 8.33598C6.41562 7.64591 6.97666 7.08487 7.66673 6.73331C8.11606 6.50398 8.59073 6.41331 9.0974 6.37331C9.27251 6.35909 9.4614 6.34931 9.66406 6.34398V5.33331C9.66406 5.20199 9.68993 5.07196 9.74018 4.95063C9.79044 4.8293 9.8641 4.71906 9.95696 4.62621C10.0498 4.53335 10.1601 4.45969 10.2814 4.40943C10.4027 4.35918 10.5327 4.33331 10.6641 4.33331ZM24.3307 13.6666H7.66406V21.7333C7.66406 22.496 7.66406 23.0093 7.6974 23.4026C7.72806 23.7866 7.78406 23.9693 7.8454 24.0893C8.0054 24.404 8.26006 24.6586 8.57473 24.8186C8.69473 24.88 8.8774 24.936 9.26006 24.9666C9.65473 24.9986 10.1667 25 10.9307 25H21.0641C21.8267 25 22.3401 25 22.7334 24.9666C23.1174 24.936 23.3001 24.88 23.4201 24.8186C23.7342 24.6588 23.9895 24.4034 24.1494 24.0893C24.2107 23.9693 24.2667 23.7866 24.2974 23.4026C24.3294 23.0093 24.3307 22.496 24.3307 21.7333V13.6666ZM13.9974 9.33331C13.7322 9.33331 13.4778 9.43867 13.2903 9.62621C13.1028 9.81374 12.9974 10.0681 12.9974 10.3333C12.9974 10.5985 13.1028 10.8529 13.2903 11.0404C13.4778 11.228 13.7322 11.3333 13.9974 11.3333H17.9974C18.2626 11.3333 18.517 11.228 18.7045 11.0404C18.892 10.8529 18.9974 10.5985 18.9974 10.3333C18.9974 10.0681 18.892 9.81374 18.7045 9.62621C18.517 9.43867 18.2626 9.33331 17.9974 9.33331H13.9974Z"
                                            fill="#5D87FF" />
                                    </svg>
                                </div>
                                <div class="da-info-text">
                                    @php
                                        $examDate = $studentExam->exam?->exam_date;
                                        $duration = (int) ($studentExam->exam?->duration ?? 0);
                                    @endphp
                                    <div class="da-info-title fw-semibold">
                                        {{ $examDate ? \Carbon\Carbon::parse($examDate)->translatedFormat('d F Y, H:i') : '-' }}
                                        @if ($examDate && $duration > 0)
                                            - {{ \Carbon\Carbon::parse($examDate)->addMinutes($duration)->format('H:i') }}
                                        @endif
                                    </div>
                                    <div class="da-info-sub text-muted small">Tanggal Dan Waktu Ujian</div>
                                </div>
                            </div>

                            {{-- Jumlah Soal --}}
                            <div class="d-flex gap-3 align-items-start py-3 border-bottom">
                                <div class="da-icon bg-light rounded-3 d-flex align-items-center justify-content-center">
                                    <svg width="20" height="22" viewBox="0 0 20 22" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10 3.98687H3C2.73478 3.98687 2.48043 4.09223 2.29289 4.27976C2.10536 4.4673 2 4.72165 2 4.98687V15.3239C2.46884 15.1018 2.9812 14.9866 3.5 14.9869H16V9.98687H18V20.9869C18 21.2521 17.8946 21.5064 17.7071 21.694C17.5196 21.8815 17.2652 21.9869 17 21.9869H3.5C2.57174 21.9869 1.6815 21.6181 1.02513 20.9617C0.368749 20.3054 0 19.4151 0 18.4869V4.98687C0 4.19122 0.316071 3.42816 0.87868 2.86555C1.44129 2.30294 2.20435 1.98687 3 1.98687H10V3.98687ZM3.5 17.0001C3.10218 17.0001 2.72064 17.1581 2.43934 17.4394C2.15804 17.7207 2 18.1022 2 18.5001C2 18.8979 2.15804 19.2794 2.43934 19.5607C2.72064 19.842 3.10218 20.0001 3.5 20.0001H16V17.0001H3.5ZM15.53 0.316871C15.5679 0.22325 15.6328 0.143072 15.7166 0.0866166C15.8003 0.030161 15.899 0 16 0C16.101 0 16.1997 0.030161 16.2834 0.0866166C16.3672 0.143072 16.4321 0.22325 16.47 0.316871L16.724 0.926871C17.1485 1.96195 17.9538 2.7948 18.974 3.25387L19.692 3.57387C19.7838 3.61635 19.8615 3.68421 19.916 3.76944C19.9705 3.85468 19.9994 3.95372 19.9994 4.05487C19.9994 4.15602 19.9705 4.25507 19.916 4.3403C19.8615 4.42553 19.7838 4.49339 19.692 4.53587L18.932 4.87387C17.9374 5.3202 17.146 6.12295 16.714 7.12387L16.467 7.68987C16.4286 7.78204 16.3637 7.86077 16.2806 7.91614C16.1975 7.97152 16.0999 8.00107 16 8.00107C15.9001 8.00107 15.8025 7.97152 15.7194 7.91614C15.6363 7.86077 15.5714 7.79522 15.533 7.68987L15.287 7.12487C14.8547 6.12322 14.0626 5.32003 13.067 4.87387L12.307 4.53587C12.2149 4.49351 12.1369 4.42564 12.0822 4.3403C12.0276 4.25496 11.9985 4.15573 11.9985 4.05437C11.9985 3.95302 12.0276 3.85378 12.0822 3.76844C12.1369 3.6831 12.2149 3.61523 12.307 3.57287L13.025 3.26605C14.045 2.80791 14.8506 1.97618 15.276 0.942055L15.53 0.316871Z"
                                            fill="#FFAE1F" />
                                    </svg>
                                </div>
                                <div class="da-info-text">
                                    <div class="da-info-title fw-semibold">
                                        {{ $studentExam->exam?->total_questions ?? ($studentExam->studentQuestionAnswers?->count() ?? 0) }}
                                        Soal
                                    </div>
                                    <div class="da-info-sub text-muted small">Jumlah Soal</div>
                                </div>
                            </div>

                            @if ($studentExam->exam->can_view_answer_result)
                                <div class="d-flex gap-3 align-items-start py-3">
                                    <div
                                        class="da-icon bg-light rounded-3 d-flex align-items-center justify-content-center">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_8689_8600)">
                                                <path
                                                    d="M13.343 2.00005C12.9654 3.06801 12.8978 4.22105 13.148 5.32582C13.3983 6.43058 13.9562 7.44194 14.7571 8.24292C15.5581 9.0439 16.5695 9.60178 17.6742 9.85203C18.779 10.1023 19.932 10.0347 21 9.65705V21.0001C21 21.2653 20.8946 21.5196 20.7071 21.7072C20.5196 21.8947 20.2652 22.0001 20 22.0001H6.5C5.57174 22.0001 4.6815 21.6313 4.02513 20.9749C3.36875 20.3186 3 19.4283 3 18.5001V5.00005C3 4.20441 3.31607 3.44134 3.87868 2.87873C4.44129 2.31613 5.20435 2.00005 6 2.00005H13.343ZM6.5 17.0001C6.10218 17.0001 5.72064 17.1581 5.43934 17.4394C5.15804 17.7207 5 18.1022 5 18.5001C5 18.8979 5.15804 19.2794 5.43934 19.5607C5.72064 19.842 6.10218 20.0001 6.5 20.0001H19V17.0001H6.5ZM18.53 0.330055C18.5679 0.236433 18.6328 0.156256 18.7166 0.0998002C18.8003 0.0433446 18.899 0.0131836 19 0.0131836C19.101 0.0131836 19.1997 0.0433446 19.2834 0.0998002C19.3672 0.156256 19.4321 0.236433 19.47 0.330055L19.724 0.940055C20.1485 1.97513 20.9538 2.80799 21.974 3.26705L22.692 3.58705C22.7838 3.62953 22.8615 3.6974 22.916 3.78263C22.9705 3.86786 22.9994 3.9669 22.9994 4.06805C22.9994 4.16921 22.9705 4.26825 22.916 4.35348C22.8615 4.43871 22.7838 4.50658 22.692 4.54905L21.932 4.88705C20.9374 5.33338 20.146 6.13613 19.714 7.13705L19.467 7.70305C19.4286 7.79522 19.3637 7.87395 19.2806 7.92933C19.1975 7.98471 19.0999 8.01425 19 8.01425C18.9001 8.01425 18.8025 7.98471 18.7194 7.92933C18.6363 7.87395 18.5714 7.79522 18.533 7.70305L18.287 7.13805C17.8547 6.1364 17.0626 5.33322 16.067 4.88705L15.307 4.54905C15.2149 4.5067 15.1369 4.43883 15.0822 4.35348C15.0276 4.26814 14.9985 4.16891 14.9985 4.06755C14.9985 3.9662 15.0276 3.86697 15.0822 3.78163C15.1369 3.69628 15.2149 3.62841 15.307 3.58605L16.025 3.26605C17.045 2.80791 17.8506 1.97618 18.276 0.942055L18.53 0.330055Z"
                                                    fill="#13DEB9" />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_8689_8600">
                                                    <rect width="24" height="24" fill="white" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </div>
                                    <div class="da-info-text">
                                        <div class="da-info-title fw-semibold">
                                            {{ $studentExam->correct_answer_count }} Dari
                                            {{ $studentExam->total_questions_count }} Soal
                                        </div>
                                        <div class="da-info-sub text-muted small">Jawaban Benar</div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                {{-- Nilai --}}
                                    <div class="text-center">
                                        <div class="text-muted fw-semibold mb-2">Nilai Kamu</div>
                                        <div class="display-5 fw-bolder text-primary">
                                            {{ is_null($studentExam->score) ? '-' : number_format((float) $studentExam->score, 2, ',', '.') }}
                                        </div>
                                    </div>

                                    {{-- Status Lulus / Tidak --}}
                                    @php
                                        $score = $studentExam->score;
                                        $minScore = (float) ($studentExam->exam?->minimum_score ?? 0);
                                        $passed = !is_null($score) && (float) $score >= $minScore;

                                        $statusBadge = is_null($score)
                                            ? 'da-pill-pending'
                                            : ($passed
                                                ? 'da-pill-pass'
                                                : 'da-pill-wrong');
                                        $statusText = is_null($score)
                                            ? 'Belum Dinilai'
                                            : ($passed
                                                ? 'Lulus'
                                                : 'Tidak Lulus');
                                    @endphp

                                    <div class="text-center mt-3">
                                        <div class="text-muted fw-semibold mb-2">Hasil Ujian</div>
                                        <span class="badge rounded-pill px-4 py-2 {{ $statusBadge }}">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            {{-- =============================================
             KONTEN KANAN — Detail Jawaban Per Soal
             ============================================= --}}
            <div class="col-12 col-lg-9">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    <div class="px-4 py-3 text-white fw-semibold da-card-header">
                        Hasil Ujian
                    </div>

                    <div class="card-body p-0 p-md-4">

                        @php $canView = $studentExam->exam->can_view_answer_result; @endphp

                        @forelse ($studentExam->studentQuestionAnswers as $i => $ans)
                            @php
                                $type = $ans->examQuestion?->type;

                                $hasAnswered = match ($type) {
                                    'essay' => !empty($ans->answer_text),
                                    'matching' => ($ans->studentAnswerMatchings ?? collect())->isNotEmpty(),
                                    default => ($ans->studentAnswerOptions ?? collect())->isNotEmpty() ||
                                        !empty($ans->answer),
                                };

                                if (!$hasAnswered) {
                                    $badgeClass = 'da-pill-wrong';
                                    $badgeText = 'Tidak Menjawab';
                                } elseif ($type === 'essay') {
                                    if (!$canView || is_null($ans->score)) {
                                        $badgeClass = 'da-pill-pending';
                                        $badgeText = 'Menunggu Penilaian';
                                    } else {
                                        $badgeClass = $ans->is_correct ? 'da-pill-ok' : 'da-pill-wrong';
                                        $badgeText = $ans->is_correct ? 'Benar' : 'Salah';
                                    }
                                } elseif (!$canView) {
                                    $badgeClass = 'da-pill-neutral';
                                    $badgeText = 'Sudah Dijawab';
                                } else {
                                    $badgeClass = $ans->is_correct ? 'da-pill-ok' : 'da-pill-wrong';
                                    $badgeText = $ans->is_correct ? 'Benar' : 'Salah';
                                }
                            @endphp

                            <div class="p-3 rounded-4 bg-white border shadow-sm mb-4">

                                {{-- Header soal --}}
                                <div
                                    class="d-flex justify-content-between align-items-start gap-3 p-3 rounded-4 mb-3 bg-light border">
                                    <div class="fw-semibold text-dark d-flex gap-2 flex-1">
                                        <span class="question-number flex-shrink-0">{{ $i + 1 }}.</span>
                                        <div class="question-text">{!! $ans->examQuestion?->question ?? '-' !!}</div>
                                    </div>
                                    @php
                                        $badgeIcon = match ($badgeClass) {
                                            'da-pill-ok' => 'ti ti-circle-check',
                                            'da-pill-wrong' => 'ti ti-circle-x',
                                            'da-pill-pending' => 'ti ti-clock',
                                            'da-pill-neutral' => 'ti ti-circle-check',
                                            default => 'ti ti-point',
                                        };
                                    @endphp
                                    <span class="badge rounded-pill px-3 py-2 flex-shrink-0 {{ $badgeClass }}">
                                        <i class="{{ $badgeIcon }} d-md-none fs-5 lh-1" aria-hidden="true"></i>
                                        <span class="d-none d-md-inline">{{ $badgeText }}</span>
                                    </span>
                                </div>

                                {{-- Pilihan Ganda (single / multiple choice) --}}
                                @if (in_array($type, ['single_choice', 'multiple_choice']))
                                    @php
                                        $selectedLabels = ($ans->studentAnswerOptions ?? collect())
                                            ->map(
                                                fn($sao) => $sao->examQuestionOption?->option_label ??
                                                    ($sao->option_label ?? null),
                                            )
                                            ->filter()
                                            ->values()
                                            ->toArray();

                                        if (empty($selectedLabels) && !empty($ans->answer)) {
                                            $selectedLabels = [(string) $ans->answer];
                                        }
                                    @endphp

                                    <div class="d-flex flex-column gap-2">
                                        @foreach ($ans->examQuestion->sortExamQuestionOptions ?? [] as $option)
                                            @php
                                                $isSelected = in_array($option->option_label, $selectedLabels);
                                                $label = strtoupper(str_replace('option_', '', $option->option_label));
                                            @endphp

                                            <div
                                                class="d-flex align-items-start gap-2 px-3 py-2 rounded-3 border
                                            {{ $isSelected ? 'bg-primary-subtle border-primary text-primary fw-semibold' : 'bg-light border-light-subtle text-dark' }}">
                                                <span class="fw-bold flex-shrink-0"
                                                    style="min-width:1.5rem;">{{ $label }}.</span>
                                                <div class="flex-grow-1">{!! $option->option_text !!}</div>
                                                @if ($isSelected)
                                                    <i class="ti ti-check ms-auto text-primary fs-5"></i>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Essay --}}
                                @elseif ($type === 'essay')
                                    <div class="p-3 rounded-4 bg-light border">
                                        <div class="da-essay-label">Jawaban Kamu</div>
                                        <div class="da-essay-content">
                                            {!! !empty($ans->answer_text) ? $ans->answer_text : '<em class="text-muted">Tidak dijawab</em>' !!}
                                        </div>

                                        @if ($canView && !is_null($ans->score))
                                            <div class="d-flex align-items-center gap-2 mt-2 pt-2 border-top">
                                                <span class="text-muted small">Skor:</span>
                                                <span class="fw-bold text-primary">{{ $ans->score }}</span>
                                                <span class="text-muted small">/
                                                    {{ $ans->examQuestion?->score ?? 0 }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Menjodohkan --}}
                                @elseif ($type === 'matching')
                                    @php
                                        $matchedItems = $ans->studentAnswerMatchings ?? collect();
                                    @endphp

                                    @if ($matchedItems->isNotEmpty())
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
                                                @foreach ($matchedItems as $matchItem)
                                                    @php
                                                        $leftText =
                                                            $matchItem->leftExamQuestionMatching?->item_text ?? '-';
                                                        $userRightText =
                                                            $matchItem->rightExamQuestionMatching?->item_text ?? '-';
                                                    @endphp

                                                    <div class="row align-items-center g-2">
                                                        <div class="col-5">
                                                            <div
                                                                class="p-3 rounded-3 bg-light-primary border h-100 position-relative">
                                                                {!! $leftText !!}
                                                                <span class="position-absolute"
                                                                    style="top:50%;right:-7px;width:12px;height:12px;border-radius:999px;background:#5D87FF;transform:translateY(-50%);box-shadow:0 0 0 4px rgba(93,135,255,.15);z-index:1;">
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="col-2 d-flex align-items-center justify-content-center">
                                                            <div
                                                                style="width:100%; height:3px; background:#5D87FF; border-radius:2px;">
                                                            </div>
                                                        </div>
                                                        <div class="col-5">
                                                            <div
                                                                class="p-3 rounded-3 bg-light-success border h-100 position-relative">
                                                                <span class="position-absolute"
                                                                    style="top:50%;left:-7px;width:12px;height:12px;border-radius:999px;background:#5D87FF;transform:translateY(-50%);box-shadow:0 0 0 4px rgba(93,135,255,.15);z-index:1;">
                                                                </span>
                                                                {!! $userRightText !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    @else
                                        <div class="p-3 rounded-4 bg-light border">
                                            <em class="text-muted">Tidak dijawab</em>
                                        </div>
                                    @endif
                                @endif

                            </div>

                        @empty
                            <div class="d-flex flex-column justify-content-center align-items-center py-5">
                                <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt=""
                                    width="300px">
                                <p class="fs-5 text-dark text-center mt-2 mb-0">Belum ada jawaban</p>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>

        </div>

        {{-- =============================================
             KONTEN KANAN — Detail Jawaban Per Soal
             ============================================= --}}
        <div class="col-12 col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                <div class="px-4 py-3 text-white fw-semibold da-card-header">
                    Hasil Ujian
                </div>

                <div class="card-body p-0 p-md-4">

                    @php $canView = $studentExam->exam->can_view_answer_result; @endphp

                    @forelse ($studentExam->studentQuestionAnswers as $i => $ans)

                        @php
                            $type = $ans->examQuestion?->type;

                            $hasAnswered = match ($type) {
                                'essay'    => !empty($ans->answer_text),
                                'matching' => ($ans->studentAnswerMatchings ?? collect())->isNotEmpty(),
                                default    => ($ans->studentAnswerOptions ?? collect())->isNotEmpty() || !empty($ans->answer),
                            };

                            if (!$hasAnswered) {
                                $badgeClass = 'da-pill-wrong';
                                $badgeText  = 'Tidak Menjawab';
                            } elseif (!$canView) {
                                if ($type === 'essay') {
                                    if (is_null($ans->score)) {
                                        $badgeClass = 'da-pill-pending';
                                        $badgeText  = 'Menunggu Penilaian';
                                    } else {
                                        $badgeClass = 'da-pill-neutral';
                                        $badgeText  = 'Sudah Dinilai';
                                    }
                                } else {
                                    $badgeClass = 'da-pill-neutral';
                                    $badgeText  = 'Sudah Dijawab';
                                }
                            } else {
                                if ($type === 'essay' && is_null($ans->score)) {
                                    $badgeClass = 'da-pill-pending';
                                    $badgeText  = 'Menunggu Penilaian';
                                } else {
                                    $badgeClass = $ans->is_correct ? 'da-pill-ok' : 'da-pill-wrong';
                                    $badgeText  = $ans->is_correct ? 'Benar' : 'Salah';
                                }
                            }
                        @endphp

                        <div class="p-3 rounded-4 bg-white border shadow-sm mb-4">

                            {{-- Header soal --}}
                            <div class="d-flex justify-content-between align-items-start gap-3 p-3 rounded-4 mb-3 bg-light border">
                                <div class="fw-semibold text-dark d-flex gap-2 flex-1">
                                    <span class="question-number flex-shrink-0">{{ $i + 1 }}.</span>
                                    <div class="question-text">{!! $ans->examQuestion?->question ?? '-' !!}</div>
                                </div>
                                @php
                                    $badgeIcon = match ($badgeClass) {
                                        'da-pill-ok'      => 'ti ti-circle-check',
                                        'da-pill-wrong'   => 'ti ti-circle-x',
                                        'da-pill-pending' => 'ti ti-clock',
                                        'da-pill-neutral' => 'ti ti-circle-check',
                                        default           => 'ti ti-point',
                                    };
                                @endphp
                                <span class="badge rounded-pill px-3 py-2 flex-shrink-0 {{ $badgeClass }}">
                                    <i class="{{ $badgeIcon }} d-md-none fs-5 lh-1" aria-hidden="true"></i>
                                    <span class="d-none d-md-inline">{{ $badgeText }}</span>
                                </span>
                            </div>

                            {{-- Pilihan Ganda (single / multiple choice) --}}
                            @if (in_array($type, ['single_choice', 'multiple_choice']))

                                @php
                                    $selectedLabels = ($ans->studentAnswerOptions ?? collect())
                                        ->map(fn($sao) => $sao->examQuestionOption?->option_label ?? $sao->option_label ?? null)
                                        ->filter()
                                        ->values()
                                        ->toArray();

                                    if (empty($selectedLabels) && !empty($ans->answer)) {
                                        $selectedLabels = [(string) $ans->answer];
                                    }
                                @endphp

                                <div class="d-flex flex-column gap-2">
                                    @foreach ($ans->examQuestion->sortExamQuestionOptions ?? [] as $option)
                                        @php
                                            $isSelected = in_array($option->option_label, $selectedLabels);
                                            $label      = strtoupper(str_replace('option_', '', $option->option_label));
                                        @endphp

                                        <div class="d-flex align-items-start gap-2 px-3 py-2 rounded-3 border
                                            {{ $isSelected ? 'bg-primary-subtle border-primary text-primary fw-semibold' : 'bg-light border-light-subtle text-dark' }}">
                                            <span class="fw-bold flex-shrink-0" style="min-width:1.5rem;">{{ $label }}.</span>
                                            <div class="flex-grow-1">{!! $option->option_text !!}</div>
                                            @if ($isSelected)
                                                <i class="ti ti-check ms-auto text-primary fs-5"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                            {{-- Essay --}}
                            @elseif ($type === 'essay')

                                <div class="p-3 rounded-4 bg-light border">
                                    <div class="da-essay-label">Jawaban Kamu</div>
                                    <div class="da-essay-content">
                                        {!! !empty($ans->answer_text) ? $ans->answer_text : '<em class="text-muted">Tidak dijawab</em>' !!}
                                    </div>

                                    @if ($canView && !is_null($ans->score))
                                        <div class="d-flex align-items-center gap-2 mt-2 pt-2 border-top">
                                            <span class="text-muted small">Skor:</span>
                                            <span class="fw-bold text-primary">{{ $ans->score }}</span>
                                            <span class="text-muted small">/ {{ $ans->examQuestion?->score ?? 0 }}</span>
                                        </div>
                                    @endif
                                </div>

                            {{-- Menjodohkan --}}
                            @elseif ($type === 'matching')

                                @php
                                    $matchedItems = $ans->studentAnswerMatchings ?? collect();
                                @endphp

                                @if ($matchedItems->isNotEmpty())
                                    <div class="p-3 rounded-3 bg-white border border-info-subtle shadow-sm">

                                        <div class="row gx-2 mb-3">
                                            <div class="col-5 text-center">
                                                <span class="fw-semibold text-muted small text-uppercase">Pernyataan</span>
                                            </div>
                                            <div class="col-2"></div>
                                            <div class="col-5 text-center">
                                                <span class="fw-semibold text-muted small text-uppercase">Jawaban</span>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-column gap-3">
                                            @foreach ($matchedItems as $matchItem)
                                                @php
                                                    $leftText      = $matchItem->leftExamQuestionMatching?->item_text  ?? '-';
                                                    $userRightText = $matchItem->rightExamQuestionMatching?->item_text ?? '-';
                                                @endphp

                                                <div class="row align-items-center g-2">
                                                    <div class="col-5">
                                                        <div class="p-3 rounded-3 bg-light-primary border h-100 position-relative">
                                                            {!! $leftText !!}
                                                            <span class="position-absolute"
                                                                  style="top:50%;right:-7px;width:12px;height:12px;border-radius:999px;background:#5D87FF;transform:translateY(-50%);box-shadow:0 0 0 4px rgba(93,135,255,.15);z-index:1;">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-2 d-flex align-items-center justify-content-center">
                                                          <div style="width:100%; height:3px; background:#5D87FF; border-radius:2px;"></div>
                                                    </div>
                                                    <div class="col-5">
                                                        <div class="p-3 rounded-3 bg-light-success border h-100 position-relative">
                                                            <span class="position-absolute"
                                                                  style="top:50%;left:-7px;width:12px;height:12px;border-radius:999px;background:#5D87FF;transform:translateY(-50%);box-shadow:0 0 0 4px rgba(93,135,255,.15);z-index:1;">
                                                            </span>
                                                            {!! $userRightText !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                @else
                                    <div class="p-3 rounded-4 bg-light border">
                                        <em class="text-muted">Tidak dijawab</em>
                                    </div>
                                @endif

                            @endif

                        </div>

                    @empty
                        <div class="d-flex flex-column justify-content-center align-items-center py-5">
                            <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" alt="" width="300px">
                            <p class="fs-5 text-dark text-center mt-2 mb-0">Belum ada jawaban</p>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>

    </div>
@endsection
