@props(['question'])

<div class="bg-white border rounded-3 mb-3 p-3">

    {{-- Meta --}}
    <p class="text-muted small fst-italic mb-2">
        <i class="ti ti-user me-1"></i>
        {{ $question->employee->user->name ?? ($question->teacher_name ?? 'Guru') }}
        menambahkan soal pilihan ganda ini
    </p>

    {{-- Header soal + toggle --}}
    <div class="d-flex align-items-start gap-2 mb-2">
        <div class="form-check flex-shrink-0 mt-1">
            <input class="form-check-input question-checkbox" type="checkbox"
                data-id="{{ $question->id }}"
                id="mc2-{{ $question->id }}"
                {{ $question->is_active ? 'checked' : '' }}
                style="width:18px;height:18px;">
        </div>

        <div class="fw-semibold text-dark flex-grow-1" style="min-width:0;">
            {!! $question->question !!}
        </div>

        <button type="button" class="btn p-0 border-0 bg-transparent flex-shrink-0"
            data-bs-toggle="collapse"
            data-bs-target="#collapse-mc2-{{ $question->id }}">
            <i class="ti ti-chevron-down text-muted" style="font-size:18px;"></i>
        </button>
    </div>

    {{-- Opsi --}}
    <div class="collapse show" id="collapse-mc2-{{ $question->id }}">

        @forelse(($question->sortExamQuestionOptions ?? []) as $index => $option)
            <div class="mb-2 p-2 rounded-3 d-flex align-items-center gap-2
                {{ $option->is_correct ? 'bg-light-success border border-success' : 'bg-light-primary' }}"
                style="font-size:13px;">

                <span class="fw-bold flex-shrink-0" style="min-width:24px;">
                    {{ chr(65 + $index) }}.
                </span>

                <div style="min-width:0; flex:1; word-break:break-word;">
                    {!! $option->option_text !!}
                </div>

                @if ($option->is_correct)
                    <i class="ti ti-check text-success flex-shrink-0"></i>
                @endif

            </div>
        @empty
            <div class="text-center text-muted py-3" style="font-size:13px;">
                <i class="ti ti-alert-circle"></i>
                Data opsi tidak tersedia
            </div>
        @endforelse

        @php
            $correctCount = ($question->sortExamQuestionOptions ?? collect())
                ->filter(fn($opt) => $opt->is_correct)->count();
        @endphp

        @if ($correctCount > 0)
            <div class="mt-2 text-info small">
                <i class="ti ti-info-circle"></i>
                Soal ini memiliki {{ $correctCount }} jawaban benar
            </div>
        @endif

    </div>

</div>