@props(['question'])

<div class="bg-light-primary p-4 rounded-3 mb-4">

    <p class="text-muted small fst-italic mb-2">
        <i class="ti ti-user me-1"></i>
        {{ $question->employee->user->name ?? ($question->teacher_name ?? 'Guru') }}
        menambahkan soal essay ini
    </p>

    <div class="bg-white rounded-4 p-3">
        <div class="d-flex align-items-start gap-3">

            <div class="form-check mt-1">
                <input class="form-check-input question-checkbox" type="checkbox" data-id="{{ $question->id }}"
                    id="essay-{{ $question->id }}" {{ $question->is_active ? 'checked' : '' }}
                    style="width:20px;height:20px;">
            </div>

            <div class="flex-grow-1">

                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div class="fw-semibold text-dark mb-3">
                        {{ $loop->iteration ?? '' }}
                        {!! $question->question !!}
                    </div>

                    <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="collapse"
                        data-bs-target="#collapse-essay-{{ $question->id }}">
                        <i class="ti ti-chevron-down text-muted" style="font-size:20px;"></i>
                    </button>
                </div>

                <div class="collapse show" id="collapse-essay-{{ $question->id }}">

                    <div class="bg-light p-3 rounded-3 mt-2">

                        <small class="text-muted d-block mb-2">
                            <i class="ti ti-info-circle"></i>
                            Skor:
                            <span class="fw-semibold">
                                {{ $question->score ?? 'Tidak ditentukan' }}
                            </span>
                        </small>

                        @if (($question->sortExamQuestionOptions ?? [])->count() > 0)
                            <div class="bg-white p-3 rounded-2 border">
                                <small class="text-muted d-block mb-1">
                                    Kunci Jawaban:
                                </small>

                                <div class="text-dark">
                                    {{ optional(($question->sortExamQuestionOptions ?? collect())->first())->option_text }}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
