<style>
    .js-select-student {
        background-color: #ffffff;
        border-left: 4px solid transparent !important;
        transition: border-color 0.15s, background-color 0.15s;
    }

    .js-select-student.is-active {
        border-left: 4px solid #4f46e5 !important;
        background-color: #eff6ff !important;
    }
</style>
<h4 class="mb-3">Penilaian Uraian</h4>
<div class="card shadow-md border border-1 mb-4">
    <div class="card-body p-3 p-lg-4">
        <div class="row g-3 g-lg-4">

            <div class="col-12 col-lg-4">
                <div class="border rounded-3 overflow-hidden">
                    <div class="bg-primary text-white px-3 py-2 fw-semibold">
                        Daftar Siswa
                    </div>

                    <div class="p-3 border-bottom bg-white">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Cari Nama..." id="student-search"
                                autocomplete="off">
                            <button class="btn btn-primary" type="button" id="btn-student-search">
                                <i class="ti ti-search"></i>
                            </button>
                        </div>

                        <div class="d-flex align-items-center justify-content-between d-none" id="info-after-grading">
                            <div class="alert border border-warning bg-warning-subtle text-warning d-flex align-items-center gap-2 rounded-3 p-2 mb-1">
                                <i class="ti ti-info-circle fs-5"></i>
                                <span class="fs-3">
                                    Silahkan refresh halaman untuk melihat nilai yang terbaru
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white" style="max-height: 640px; overflow:auto;" id="student-list-container">
                        @forelse ($studentExams as $studentExam)
                            <button type="button" class="w-100 text-start border-0 p-3 border-bottom js-select-student"
                                data-student-exam-id="{{ $studentExam['student_exam_id'] ?? '' }}"
                                data-student-name="{{ $studentExam['name'] ?? '' }}">

                                <div class="d-flex align-items-start justify-content-between gap-2">
                                    <div class="fw-semibold text-dark" style="line-height:1.2">
                                        {{ $loop->iteration }}. {{ $studentExam['name'] ?? 'Nama Siswa' }}
                                        @if (!empty($studentExam['class']))
                                            <div class="text-muted small mt-1">{{ $studentExam['class'] }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-2 d-flex align-items-center gap-2 flex-wrap">
                                    <span class="badge bg-light-primary text-primary">
                                        Nilai:
                                        {{ isset($studentExam['score']) && $studentExam['score'] > 0 ? $studentExam['score'] : '-' }}
                                    </span>

                                    @if (strtolower($studentExam['status'] ?? '') === 'lulus')
                                        <span
                                            class="badge bg-light-success text-success text-capitalize">{{ $studentExam['status'] }}</span>
                                    @elseif (strtolower($studentExam['status'] ?? '') === 'tidak lulus')
                                        <span
                                            class="badge bg-light-danger text-danger text-capitalize">{{ $studentExam['status'] }}</span>
                                    @elseif (strtolower($studentExam['status'] ?? '') === 'sedang mengerjakan')
                                        <span
                                            class="badge bg-light-warning text-warning text-capitalize">{{ $studentExam['status'] }}</span>
                                    @elseif (strtolower($studentExam['status'] ?? '') === 'tidak mengerjakan' ||
                                            strtolower($studentExam['status'] ?? '') === 'belum mengerjakan')
                                        <span
                                            class="badge bg-light text-muted text-capitalize">{{ $studentExam['status'] }}</span>
                                    @endif
                                </div>
                            </button>
                        @empty
                            <div class="text-center py-5">
                                <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" width="220"
                                    alt="No data">
                                <p class="mb-0 mt-3 text-dark">Data siswa tidak tersedia</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="border rounded-3 overflow-hidden">
                    <div
                        class="bg-primary text-white px-3 py-2 fw-semibold d-flex justify-content-between align-items-center">
                        <span>Hasil Ujian</span>
                        <span class="small fw-normal opacity-75" id="active-student-name"></span>
                    </div>
                    <div class="p-md-3 p-lg-4 bg-light-primary">
                        <div id="essay-question-list">
                            <div class="text-center py-5">
                                <img src="{{ asset('admin_assets/dist/images/empty/no-data.png') }}" width="220"
                                    alt="No data">
                                <p class="mt-3 mb-0">Pilih siswa untuk melihat jawaban uraian</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
