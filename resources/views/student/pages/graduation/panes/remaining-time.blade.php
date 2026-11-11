<div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
    <i class="ti ti-info-circle fs-5 fw-bolder text-warning"></i>
    <span class="fw-bolder text-warning">Hasil kelulusan akan diumumkan sesuai dengan jadwal yang telah ditetapkan</span>
</div>

<div class="card" style="min-height: 500px;">
    <div class="card-body d-flex flex-column justify-content-center align-items-center">
        {{-- Label --}}
        <div class="w-100 bg-light-primary border border-primary rounded p-2 text-center mb-4" style="max-width: 700px">
            <strong class="text-primary">Hasil Kelulusan Akan Diumumkan Dalam Waktu</strong>
        </div>

        {{-- Timer --}}
        <div class="d-flex justify-content-center align-items-start text-center">

            <div class="d-flex flex-column align-items-center px-md-3">
                <h1 class="display-1 text-primary fw-bolder mb-0" id="days">00</h1>
                <span class="text-primary fw-semibold">Hari</span>
            </div>

            <span class="display-1 text-primary fw-bolder px-1">:</span>

            <div class="d-flex flex-column align-items-center px-md-3">
                <h1 class="display-1 text-primary fw-bolder mb-0" id="hours">00</h1>
                <span class="text-primary fw-semibold">Jam</span>
            </div>

            <span class="display-1 text-primary fw-bolder px-1">:</span>

            <div class="d-flex flex-column align-items-center px-md-3">
                <h1 class="display-1 text-primary fw-bolder mb-0" id="minutes">00</h1>
                <span class="text-primary fw-semibold">Menit</span>
            </div>

            <span class="display-1 text-primary fw-bolder px-1">:</span>

            <div class="d-flex flex-column align-items-center px-md-3">
                <h1 class="display-1 text-primary fw-bolder mb-0" id="seconds">00</h1>
                <span class="text-primary fw-semibold">Detik</span>
            </div>

        </div>
        <div class="justify-content-center mt-4" id="btn-view-graduation">
            <form action="{{ route('student.graduations.update') }}" method="POST">
                @csrf
                @method('PATCH')
                <button class="btn bg-dark bg-opacity-50 text-white border-0 rounded-pill px-4 py-2 fw-semibold">
                    Tampilkan Hasil
                </button>
            </form>
        </div>
    </div>
</div>
