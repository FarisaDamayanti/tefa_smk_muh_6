@if ($graduationDetail->graduation->additional_file)
    <div class="p-3 bg-light-warning shadow-md rounded rounded-3 mb-3">
        <div class="d-flex align-items-center gap-2 text-warning">
            <i class="fas fa-info-circle"></i>
            <b>Hasil kelulusan telah tersedia. Silakan unduh file tambahan jika diperlukan.</b>
        </div>
    </div>
@endif

<div class="card border-5 border-bottom-0 border-start-0 border-end-0  border-{{ $graduationDetail->status == 'passed' ? 'success' : 'danger' }}">
    <img src="{{ $graduationDetail->status == 'passed' ? asset('admin_assets/dist/images/backgrounds/background-graduation.png') : asset('admin_assets/dist/images/backgrounds/background-graduation-unpassed.png') }}" class="mt-5">
    <div class="card-body d-flex flex-column justify-content-center align-items-center pt-0 p-3 p-md-5">
        @if ($graduationDetail->status == 'passed')
            <img src="{{ asset('admin_assets/dist/images/ilustrations/passed.png') }}"
                class="position-absolute passed-img" style="top: 60px" alt="passed">
        @else
            <img src="{{ asset('admin_assets/dist/images/ilustrations/unpassed.png') }}"
                class="position-absolute not-passed-img" style="top: 60px" alt="not-passed">
        @endif
        <h1 class="text-center title-graduation {{ $graduationDetail->status }}">
            <b>{{ $graduationDetail->status == 'passed' ? 'Selamat! Anda Dinyatakan Lulus' : 'Mohon Maaf, Silahkan Menghubungi Wali Kelas' }}</b>
        </h1>
        <h4 class="text-center subtitle-graduation">
            {{ $graduationDetail->status == 'passed' ? 'Hari yang ditunggu telah tiba. Terimakasih telah menyelesaikan perjalanan pendidikan Anda dengan baik.' : 'Tetap Semangat, Jangan berputus asa untuk meraih kelulusan.' }}
        </h4>
        <span
            class="badge bg-light-primary border border-primary rounded rounded-pill text-primary d-flex align-items-center gap-2 px-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-school">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M19 13.431v2.569c0 2.398 -3.205 4 -7 4s-7 -1.602 -7 -4v-2.569l5.886 2.354a3 3 0 0 0 2.011 .078l.217 -.078zm2 -2.955l-8.629 3.452a1 1 0 0 1 -.742 0l-10 -4c-.839 -.335 -.839 -1.521 0 -1.856l10 -4a1 1 0 0 1 .245 -.064l.126 -.008l.126 .008a1 1 0 0 1 .245 .064l10.032 4.013l.108 .055l.099 .068l.088 .076l.075 .082l.035 .044l.073 .115l.052 .115l.034 .102l.025 .135l.006 .058l.002 6.065a1 1 0 0 1 -2 0z" />
            </svg> <b>Tahun Ajaran {{ $graduationDetail->graduation->schoolYear->school_year }}</b>
        </span>

        <div class="card card-body p-2 p-md-3 mt-4 w-100 pb-0">
            <div class="card card-body border-1 p-3 shadow-none mb-3">
                <div class="row">
                    <div class="col-12 col-md-4 border-2 border-end">
                        <div class="d-flex align-items-center gap-2 mb-2 mb-md-0">
                            <div class="badge bg-light-primary text-primary rounded rounded-2 p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35"
                                    viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M8.5 14a1.25 1.25 0 1 0 0-2.5a1.25 1.25 0 0 0 0 2.5m0 3.5a1.25 1.25 0 1 0 0-2.5a1.25 1.25 0 0 0 0 2.5m4.75-4.75a1.25 1.25 0 1 1-2.5 0a1.25 1.25 0 0 1 2.5 0M12 17.5a1.25 1.25 0 1 0 0-2.5a1.25 1.25 0 0 0 0 2.5m4.75-4.75a1.25 1.25 0 1 1-2.5 0a1.25 1.25 0 0 1 2.5 0" />
                                    <path fill="currentColor" fill-rule="evenodd"
                                        d="M8 3.25a.75.75 0 0 1 .75.75v.75h6.5V4a.75.75 0 0 1 1.5 0v.758q.228.006.425.022c.38.03.736.098 1.073.27a2.75 2.75 0 0 1 1.202 1.202c.172.337.24.693.27 1.073c.03.365.03.81.03 1.345v7.66c0 .535 0 .98-.03 1.345c-.03.38-.098.736-.27 1.073a2.75 2.75 0 0 1-1.201 1.202c-.338.172-.694.24-1.074.27c-.365.03-.81.03-1.344.03H8.17c-.535 0-.98 0-1.345-.03c-.38-.03-.736-.098-1.073-.27a2.75 2.75 0 0 1-1.202-1.2c-.172-.338-.24-.694-.27-1.074c-.03-.365-.03-.81-.03-1.344V8.67c0-.535 0-.98.03-1.345c.03-.38.098-.736.27-1.073A2.75 2.75 0 0 1 5.752 5.05c.337-.172.693-.24 1.073-.27q.197-.016.425-.022V4A.75.75 0 0 1 8 3.25m10.25 7H5.75v6.05c0 .572 0 .957.025 1.252c.023.288.065.425.111.515c.12.236.311.427.547.547c.09.046.227.088.514.111c.296.024.68.025 1.253.025h7.6c.572 0 .957 0 1.252-.025c.288-.023.425-.065.515-.111a1.25 1.25 0 0 0 .547-.547c.046-.09.088-.227.111-.515c.024-.295.025-.68.025-1.252zM10.5 7a.75.75 0 0 0 0 1.5h3a.75.75 0 0 0 0-1.5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <b class="text-dark">Pengumuman Kelulusan</b>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 align-self-center">
                        <b
                            class="text-primary">{{ $graduationDetail->graduation->announcement_date->translatedFormat('d F Y') }}</b>
                    </div>
                </div>
            </div>
            @if ($graduationDetail->graduation->additional_file)
                <div class="card card-body border-1 p-3 shadow-none">
                    <div class="row ">
                        <div class="col-12 col-md-4 border-2 border-end">
                            <div class="d-flex align-items-center gap-2 mb-2 mb-md-0">
                                <div class="badge bg-light-primary text-primary rounded rounded-2 p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35"
                                        viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M14 11a3 3 0 0 1-3-3V4H7a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2v-8zm-2-3a2 2 0 0 0 2 2h3.59L12 4.41zM7 3h5l7 7v9a3 3 0 0 1-3 3H7a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3" />
                                    </svg>
                                </div>
                                <b class="text-dark">File Tambahan</b>
                            </div>
                        </div>
                        <div
                            class="col-12 col-md-8 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <b
                                class="text-primary">{{ basename($graduationDetail->graduation->additional_file) }}</b>
                            <a href="{{ asset('storage/' . $graduationDetail->graduation->additional_file) }}"
                                class="btn btn-primary"
                                download="{{ $graduationDetail->graduation->additional_file }}">
                                <i class="ti ti-download fs-5"></i> Unduh
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @if ($graduationDetail->graduation->additional_file)
            <div class="d-flex justify-content-center align-items-center text-muted gap-2">
                <i class="fas fa-info-circle"></i>
                <b>Harap mengikuti jadwal dan ketentuan yang tertera pada file tambahan.</b>
            </div>
        @endif
    </div>
</div>
