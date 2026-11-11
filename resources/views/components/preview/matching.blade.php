<div id="matching-list" class="d-flex flex-column gap-4 mb-4">
    @forelse ($questions->where('type', 'matching') as $q)
        @php $pairs = $q->pairs ?? []; $letters = range('A', 'Z'); @endphp

        <div class="bg-light-primary p-4 rounded-3 question-item" data-id="{{ $q->id }}">

            <p class="m-0 text-capitalize mb-3 fst-italic text-muted small">
                {{ $q->teacher_name }} menambahkan soal penjodohan ini
            </p>

            {{-- Question + remove button --}}
            <div class="rounded-4 bg-white px-3 py-2 d-flex align-items-center gap-2 mb-3">
                <button type="button" class="btn-remove-question" data-id="{{ $q->id }}" title="Hapus soal">
                    <i class="ti ti-minus"></i>
                </button>
                <div class="fw-semibold text-dark">{{ $q->question }}</div>
            </div>

            {{-- Board --}}
            <div class="bg-white p-4">
                <div class="position-relative js-matching-board"
                     data-connections='@json(collect($pairs)->keys()->map(fn($i) => ["from" => "left-{$q->id}-{$i}", "to" => "right-{$q->id}-{$i}"])->values())'>

                    <div class="row g-4">

                        {{-- Left column --}}
                        <div class="col-12 col-md-5">
                            <div class="text-center fw-bold mb-2">Pertanyaan</div>
                            @foreach ($pairs as $i => $pair)
                                <div class="mb-3">
                                    <div class="position-relative rounded-4 p-3 js-node"
                                         style="background:#eef3ff; min-height:{{ isset($pair['left']['image']) ? '130px' : '60px' }}; cursor:pointer;"
                                         data-node="left-{{ $q->id }}-{{ $i }}">
                                        <div class="d-flex gap-3 align-items-start">
                                            <div class="fw-bold text-dark" style="min-width:22px;">{{ $i + 1 }}.</div>
                                            @if (!empty($pair['left']['image']))
                                                <img src="{{ $pair['left']['image'] }}" alt="kiri {{ $i + 1 }}"
                                                     class="rounded-3" style="width:140px;height:100px;object-fit:cover;">
                                            @else
                                                <span class="fw-semibold text-dark">{{ $pair['left']['text'] ?? '' }}</span>
                                            @endif
                                        </div>
                                        <span class="position-absolute top-50 translate-middle-y js-anchor"
                                              style="right:-8px;width:12px;height:12px;border-radius:999px;
                                                     background:#5D87FF;box-shadow:0 0 0 4px rgba(93,135,255,.15);"
                                              data-node="left-{{ $q->id }}-{{ $i }}"></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="col-12 col-md-2 d-none d-md-block"></div>

                        {{-- Right column --}}
                        <div class="col-12 col-md-5">
                            <div class="text-center fw-bold mb-2">Jawaban</div>
                            @foreach ($pairs as $i => $pair)
                                <div class="mb-3">
                                    <div class="position-relative rounded-4 p-3 d-flex align-items-center js-node"
                                         style="background:#eef3ff; min-height:60px; cursor:pointer;"
                                         data-node="right-{{ $q->id }}-{{ $i }}">
                                        @if (!empty($pair['right']['image']))
                                            <img src="{{ $pair['right']['image'] }}" alt="kanan {{ $i + 1 }}"
                                                 class="rounded-3" style="width:140px;height:100px;object-fit:cover;">
                                        @else
                                            <span class="fw-semibold text-dark">
                                                {{ $letters[$i] }}. {{ $pair['right']['text'] ?? '' }}
                                            </span>
                                        @endif
                                        <span class="position-absolute top-50 translate-middle-y js-anchor"
                                              style="left:-8px;width:12px;height:12px;border-radius:999px;
                                                     background:#5D87FF;box-shadow:0 0 0 4px rgba(93,135,255,.15);"
                                              data-node="right-{{ $q->id }}-{{ $i }}"></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    <svg class="position-absolute top-0 start-0 w-100 h-100 js-matching-svg"
                         style="pointer-events:none;" aria-hidden="true"></svg>
                </div>
            </div>
        </div>
    @empty
        <div class="d-flex flex-column justify-content-center align-items-center py-5">
            <img src="{{ asset('admin_assets/dist/images/empty/nodata.png') }}" alt="" width="200px">
            <h5 class="fw-semibold text-dark mb-2">Maaf, soal yang kamu cari masih belum tersedia.</h5>
            <p class="text-muted text-center mb-0" style="max-width:400px;">
                Pastikan data yang dimasukkan sudah benar atau hubungi pihak terkait untuk konfirmasi.
            </p>
        </div>
    @endforelse
</div>