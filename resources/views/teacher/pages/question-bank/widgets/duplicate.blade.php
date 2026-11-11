<div class="modal fade" id="duplicate-question-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-body p-4">
                <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div>
                        <h4 class="fw-bold mb-1">Duplikasi Soal</h4>
                        <p class="text-muted mb-0">Pilih bank soal tujuan untuk menyalin soal yang dipilih.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Target Question Bank (CUSTOM DROPDOWN) --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Duplikasi ke Bank Soal</label>

                    <div class="custom-dropdown" id="duplicate-bank-dropdown">
                        <div class="dropdown-selected" role="button" tabindex="0" data-selected-id="">
                            <div class="selected-content">
                                <span class="selected-text text-muted" id="duplicate-bank-selected-text">
                                    Pilih bank soal tujuan
                                </span>
                            </div>
                            <i class="ti ti-chevron-down dropdown-arrow"></i>
                        </div>

                        <div class="dropdown-options" id="duplicate-bank-options">
                            <div class="dropdown-options-content">
                                <div class="dropdown-option text-muted is-placeholder" data-id="">
                                    Pilih bank soal tujuan
                                </div>

                                @forelse($questionBanks as $bank)
                                    <div
                                        class="dropdown-option d-flex align-items-center justify-content-between
                                            {{ $bank->id === $questionBank->id ? 'disabled current-bank-option' : '' }}"
                                        data-id="{{ $bank->id }}"
                                        data-title="{{ $bank->title }}"
                                    >
                                        <span class="option-text">{{ $bank->title }}</span>

                                        @if($bank->id === $questionBank->id)
                                            <span class="badge text-bg-danger">Bank saat ini</span>
                                        @endif
                                    </div>
                                @empty
                                    <div class="dropdown-option text-muted">
                                        Tidak ada bank soal.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="text-danger small mt-1 d-none" id="duplicate-target-bank-error"></div>
                </div>

                {{-- Selected Questions Preview --}}
                <div class="mb-3">
                    <div class="fw-semibold mb-2">Soal yang dipilih</div>
                    <div class="border rounded-3 p-3 bg-light" style="max-height: 180px; overflow:auto;">
                        <div id="duplicate-selected-list" class="duplicate-selected-list">
                            <span class="text-muted">Belum ada soal dipilih.</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Batal</button>

                    <button type="button" class="btn btn-primary" id="btn-submit-duplicate">
                        Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>