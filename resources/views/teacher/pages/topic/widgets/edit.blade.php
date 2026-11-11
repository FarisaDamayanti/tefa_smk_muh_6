<div class="modal fade" id="edit-topic-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">

            <div class="modal-header border-0 px-4 pt-4">
                <h4 class="modal-title fw-semibold">Edit Topik</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 pb-4">
                <form id="form-edit-topic">
                    {{-- Topik --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="edit-title-topic">Judul Topik <span
                                class="text-danger">*</span></label>
                        <input type="text" id="edit-title-topic" class="form-control" name="title"
                            placeholder="Masukkan Judul Topik...">
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Semester --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="edit-semester-topic">Semester <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="edit-semester-topic" name="semester">
                            <option value="ganjil">Semester Ganjil</option>
                            <option value="genap">Semester Genap</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="edit-description-topic">Deskripsi <span
                                class="text-danger">*</span></label>
                        <textarea rows="6" class="form-control" id="edit-description-topic" name="description"
                            placeholder="Masukkan Deskripsi..."></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    {{-- Action --}}
                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="btn-submit-edit-topic">
                            Simpan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
