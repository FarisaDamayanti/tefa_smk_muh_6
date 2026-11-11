<div class="accordion mb-4" id="mc-list">
    @forelse ($questions->where('type', 'multiple_choice') as $key => $q)
        @php $i = $loop->iteration; @endphp
        <div class="accordion-item card border border-1 bg-light-primary rounded-4 p-3 mb-4 question-item"
             data-id="{{ $q->id }}">

            <p class="m-0 text-capitalize mb-3 fst-italic text-muted small">
                {{ $q->teacher_name }} menambahkan soal pilihan ganda ini
            </p>

            <div class="p-0 rounded-4 bg-white text-dark accordion-header" id="heading-mc-{{ $i }}">
                <button class="accordion-button px-3 {{ $i !== 1 ? 'collapsed' : '' }}"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-mc-{{ $i }}"
                        aria-expanded="{{ $i === 1 ? 'true' : 'false' }}"
                        aria-controls="collapse-mc-{{ $i }}">
                    <div class="d-flex align-items-start gap-2 w-100">
                        <button type="button" class="btn-remove-question" data-id="{{ $q->id }}" title="Hapus soal">
                            <i class="ti ti-minus"></i>
                        </button>
                        <div class="fw-semibold question-text">{{ $q->question }}</div>
                    </div>
                </button>
            </div>

            <div class="py-1 rounded-4 bg-white text-dark mt-3 accordion-collapse collapse {{ $i === 1 ? 'show' : '' }}"
                 id="collapse-mc-{{ $i }}"
                 aria-labelledby="heading-mc-{{ $i }}"
                 data-bs-parent="#mc-list">
                <div class="accordion-body px-3">

                    @foreach (['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D', 'e' => 'E'] as $letter => $label)
                        <div class="mb-3 p-3 rounded-4 d-flex gap-2 align-items-start
                            {{ $q->answer === 'option_' . $letter
                                ? 'bg-light-success border border-1 border-success'
                                : 'bg-light-primary' }}">
                            <span class="fw-semibold">{{ $label }}.</span>
                            <span>{!! $q->{'option_' . $letter} !!}</span>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="d-flex flex-column justify-content-center align-items-center py-5">
                <img src="{{ asset('admin_assets/dist/images/empty/nodata.png') }}" alt="" width="200px">
                <h5 class="fw-semibold text-dark mb-2">Maaf, soal yang kamu cari masih belum tersedia.</h5>
                <p class="text-muted text-center mb-0" style="max-width:400px;">
                    Pastikan data yang dimasukkan sudah benar atau hubungi pihak terkait untuk konfirmasi.
                </p>
            </div>
        </div>
    @endforelse
</div>