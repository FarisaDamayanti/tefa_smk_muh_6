@php
    $exam = collect($incomingExams ?? [])->first();

    $examDateRaw = data_get($exam, 'exam_date');
    $examTitle = data_get($exam, 'title', 'Teori');

    $examDate = null;
    if ($examDateRaw) {
        try {
            $examDate = \Carbon\Carbon::parse($examDateRaw);
        } catch (\Throwable $e) {
            $examDate = null;
        }
    }

    $daysLeft = null;
    if ($examDate) {
        $daysLeft = now()->startOfDay()->diffInDays($examDate->startOfDay(), false);
    }
@endphp

@if ($exam && $examDate && $daysLeft !== null && $daysLeft >= 0)
    <div class="exam-reminder-card mb-4">
        <div class="exam-reminder-inner d-flex flex-column flex-md-row align-items-center text-center text-md-start">

            {{-- ILUSTRASI KIRI --}}
            <div class="mb-3 mb-md-0 me-md-4">
                <img src="{{ asset('admin_assets/dist/images/empty/amico.png') }}" alt="exam" style="width:140px;">
            </div>

            {{-- KONTEN --}}
            <div class="exam-reminder-content">
                <h3 class="exam-reminder-title">
                    @if ($daysLeft == 0)
                        Ujian {{ $examTitle }} akan dilaksanakan
                    <span class="fw-semibold">hari ini</span>
                    @else
                        Ujian {{ $examTitle }} akan dilaksanakan dalam
                        <span class="fw-semibold">{{ $daysLeft }} hari lagi</span>
                    @endif
                </h3>

                <div class="exam-reminder-date">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M7 11h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2zM7 15h2v2H7zm4 0h2v2h-2zm4 0h2v2h-2zM19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2m0 16H5V9h14z" />
                    </svg>

                    <span>{{ $examDate->translatedFormat('l, d F Y') }}</span>
                </div>

                <div class="exam-reminder-sub">
                    Jangan lupa belajar dan persiapkan diri agar hasil maksimal
                </div>
            </div>
        </div>
    </div>
@endif
