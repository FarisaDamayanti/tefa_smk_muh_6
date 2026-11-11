<div class="modal fade" id="modal-rolling-student" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <form id="form-add-student-classroom" class="modal-content">
            <div
                class="modal-header d-flex align-items-center justify-content-center position-relative bg-primary rounded-top-3">
                <h5 class="modal-title text-white text-center w-100">Rolling Siswa</h5>
            </div>
            <div class="modal-body">
                <!-- Kontainer Loading Spinner -->
                <div id="modal-rolling-student-loading" class="d-flex justify-content-center align-items-center"
                    style="min-height: 100px;">
                    <div class="spinner-border" style="color: #7209DB;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <!-- Kontainer Konten Aktual, awalnya disembunyikan -->
                <div id="modal-rolling-student-content" style="display: none;">
                    <div class="form-group mb-3">
                        <label class="mb-2 fw-semibold text-dark">Nama Siswa</label>
                        <select class="select2 form-control w-100" name="student_ids[]" id="select2"
                            multiple="multiple" style="height: 36px; width: 100%">
                        </select>

                        @error('student_ids')
                            <span class="text-danger error-create">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger " data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
