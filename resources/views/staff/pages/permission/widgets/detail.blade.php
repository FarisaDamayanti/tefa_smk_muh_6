<div class="modal fade" id="modal-detail" tabindex="-1" aria-labelledby="importPegawai" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary rounded-top-3">
                <h5 class="modal-title text-white">Detail Perizinan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">

                <h6 class="mb-1">Nama</h6>
                <p id="name-detail"></p>
                <h6 class="mb-1">Kelas</h6>
                <p id="classroom-detail"></p>
                <hr>
                <div class="form-group mb-3">
                    <h6 class="mb-2">Status: <span id="status-detail">Izin</span></h6>
                </div>
                <div class="d-flex gap-5">
                    <div class="form-group mb-3">
                        <h6 class="mb-2">Tanggal: <span class="text-muted" id="date-detail">12 Desember 2024</span></h6>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <h6 class="mb-2">Bukti</h6>
                    <img src="{{ asset('admin_assets/dist/images/backgrounds/profile-bg.jpg') }}" alt="" id="proof-detail" class="w-100">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn waves-effect waves-light btn-rounded btn-light-danger text-danger"
                    data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
