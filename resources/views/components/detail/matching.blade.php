@props(['question', 'loop'])

<div class="bg-white border rounded-3 mb-3 p-3">

    {{-- Meta --}}
    <p class="text-muted small fst-italic mb-2">
        <i class="ti ti-user me-1"></i>
        {{ $question->employee->user->name ?? ($question->teacher_name ?? 'Guru') }}
        menambahkan soal penjodohan ini
    </p>

    {{-- Header soal + toggle --}}
    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
        <div class="d-flex align-items-start gap-2 flex-grow-1" style="min-width:0;">
            <div class="form-check flex-shrink-0 mt-1">
                <input class="form-check-input question-checkbox" type="checkbox"
                    data-id="{{ $question->id }}"
                    id="matching-{{ $question->id }}"
                    {{ $question->is_active ? 'checked' : '' }}
                    style="width:18px;height:18px;">
            </div>
            <div class="fw-semibold text-dark" style="min-width:0;">
                {!! $question->question !!}
            </div>
        </div>
        <button type="button" class="btn p-0 border-0 bg-transparent flex-shrink-0"
            data-bs-toggle="collapse"
            data-bs-target="#collapse-matching-{{ $question->id }}">
            <i class="ti ti-chevron-down text-muted" style="font-size:18px;"></i>
        </button>
    </div>

    {{-- Konten pasangan --}}
    <div class="collapse show" id="collapse-matching-{{ $question->id }}">
        @php
            $leftItems  = $question->leftExamQuestionMatchings  ?? collect();
            $rightItems = $question->rightExamQuestionMatchings ?? collect();

            $orderedRightItems = collect();
            foreach ($leftItems as $left) {
                if ($left->leftItemAnswer && $left->leftItemAnswer->right_item_id) {
                    $right = $rightItems->firstWhere('id', $left->leftItemAnswer->right_item_id);
                    if ($right) $orderedRightItems->push($right);
                }
            }

            $connections = [];
            foreach ($leftItems as $leftIndex => $left) {
                $connections[] = [
                    'from' => 'node-left-' . $question->id . '-' . $leftIndex,
                    'to'   => 'node-right-' . $question->id . '-' . $leftIndex,
                ];
            }
        @endphp

        {{-- Board --}}
        <div class="position-relative js-matching-board"
            data-connections='@json($connections)'>

            {{-- Header kolom --}}
            <div class="row g-0 mb-2">
                <div class="col-5 text-center">
                    <span class="fw-bold text-muted text-uppercase" style="font-size:10px; letter-spacing:.5px;">Pernyataan</span>
                </div>
                <div class="col-2"></div>
                <div class="col-5 text-center">
                    <span class="fw-bold text-muted text-uppercase" style="font-size:10px; letter-spacing:.5px;">Jawaban</span>
                </div>
            </div>

            <div class="row g-0">

                {{-- Kolom kiri --}}
                <div class="col-5">
                    @foreach ($leftItems as $idx => $left)
                        <div class="mb-2">
                            <div class="position-relative rounded-3 p-2 d-flex align-items-center"
                                style="background:#eef3ff; min-height:60px;">
                                <div class="fw-bold text-dark me-1 flex-shrink-0" style="min-width:16px; font-size:12px;">
                                    {{ $idx + 1 }}.
                                </div>
                                <div style="font-size:12px; word-break:break-word; min-width:0; flex:1;">
                                    {!! $left->item_text !!}
                                </div>
                                {{-- anchor dot kanan --}}
                                <span class="position-absolute top-50 translate-middle-y js-anchor"
                                    data-node="node-left-{{ $question->id }}-{{ $idx }}"
                                    style="right:-6px; width:10px; height:10px; border-radius:50%;
                                           background:#5D87FF; box-shadow:0 0 0 3px rgba(93,135,255,.2);
                                           z-index:2; display:block;"></span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Spacer tengah --}}
                <div class="col-2"></div>

                {{-- Kolom kanan --}}
                <div class="col-5">
                    @foreach ($orderedRightItems as $idx => $right)
                        <div class="mb-2">
                            <div class="position-relative rounded-3 p-2 d-flex align-items-center"
                                style="background:#d8f5ef; min-height:60px;">
                                {{-- anchor dot kiri --}}
                                <span class="position-absolute top-50 translate-middle-y js-anchor"
                                    data-node="node-right-{{ $question->id }}-{{ $idx }}"
                                    style="left:-6px; width:10px; height:10px; border-radius:50%;
                                           background:#5D87FF; box-shadow:0 0 0 3px rgba(93,135,255,.2);
                                           z-index:2; display:block;"></span>
                                <div style="font-size:12px; word-break:break-word; padding-left:6px; min-width:0; flex:1;">
                                    {!! $right->item_text !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            {{-- SVG overlay untuk path bezier --}}
            <svg class="position-absolute top-0 start-0 w-100 h-100 js-matching-svg"
                style="pointer-events:none; z-index:1; overflow:visible;"
                aria-hidden="true"></svg>

        </div>
    </div>

</div>