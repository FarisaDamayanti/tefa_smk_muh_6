<div id="essay-list" class="d-flex flex-column gap-3 mb-4">
    @forelse ($questions->where('type', 'essay') as $q)
        <div class="rounded-4 p-3 shadow-sm bg-light-primary question-item" data-id="{{ $q->id }}">
            <p class="text-muted small fst-italic mb-2">
                {{ $q->teacher_name }} menambahkan soal esai ini
            </p>
            <div class="bg-white rounded-4 px-3 py-3 d-flex align-items-center gap-2">
                <button type="button" class="btn-remove-question" data-id="{{ $q->id }}" title="Hapus soal">
                    <i class="ti ti-minus"></i>
                </button>
                <div class="fw-semibold text-dark">{{ $q->question }}</div>
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