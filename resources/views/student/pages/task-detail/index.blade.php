@extends('student.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/breadcrumb.css') }}">
    <style>
        #dropzone { cursor: pointer; transition: 0.2s; }
        #dropzone.border-primary { background-color: #eef3ff; }
        .dropzone { border: 2px dashed #ced4da; transition: all .2s ease; }
        .dropzone.is-invalid { border-color: #dc3545 !important; background-color: #fff5f5; }
        .description-container { max-width: 100%; overflow-x: auto; overflow-y: hidden; }
        .description-container * { max-width: 100%; }
    </style>
@endsection

@section('content')

    <x-authenticated.breadcrumb title="Detail Tugas"
        breadcrumb="E-learning / Kelas / {{ $classroom->levelClass->name ?? '' }} {{ $classroom->name ?? '' }} - {{ $subject->name ?? '' }} /Topik {{ $topic->title ?? '' }} / Detail Tugas" />

    @php
        use Carbon\Carbon;
        use App\Enums\SubmissionTypeEnum;

        $submission    = $task->currentUserTaskSubmission;
        $deadline      = $task->deadline instanceof Carbon ? $task->deadline : Carbon::parse($task->deadline);
        $hasSubmitted  = !empty($submission?->answer);
        $isLate        = Carbon::now()->greaterThan($deadline);
        $allowOverdue  = (int) $task->allow_overdue === 1;
        $canSubmit     = !$isLate || $allowOverdue;   // bisa kirim jika belum lewat ATAU guru izinkan terlambat
        $isGraded      = $hasSubmitted && $submission->grade !== null;
        $showForm      = $canSubmit && !$isGraded;    // tampilkan form jika bisa submit & belum dinilai
        $showDeadlineMissed = !$hasSubmitted && !$canSubmit; // belum submit & sudah tidak bisa submit
    @endphp

    {{-- Tombol Kembali: hanya tampil jika sudah dinilai atau deadline terlewat tanpa submit --}}
    @if ($isGraded || $showDeadlineMissed || !$showForm)
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('student.e-learning.detail', [$task->topic->classroom_id, $task->topic->subject_id]) }}"
                class="btn btn-warning px-4">Kembali</a>
        </div>
    @endif

    {{-- ===================== CASE: deadline terlewat & belum submit ===================== --}}
    @if ($showDeadlineMissed)
        <div class="card-body text-center mb-4">
            <img src="{{ asset('assets/images/deadline.png') }}" alt="Deadline Terlewat" class="mb-3"
                style="max-width: 400px; width: 100%;">
            <h4 class="fw-bold mb-2">Yahh… kamu melewati batas waktu 😥</h4>
            <p class="text-muted mb-0">Cobalah untuk lebih disiplin ke depannya dan jangan sampai lalai lagi.</p>
        </div>

    {{-- ===================== CASE: sudah submit (dinilai / belum dinilai) ===================== --}}
    @elseif ($hasSubmitted && (!$canSubmit || $isGraded))
        <div class="row g-3">

            {{-- Kolom Kiri: Informasi Tugas --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <svg width="24" height="27" viewBox="0 0 24 27" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.016 21.6H24V2.7C24 1.98392 23.719 1.29716 23.219 0.790812C22.7189 0.284463 22.0406 0 21.3333 0H4C2.392 0 0 1.07865 0 4.05V22.95C0 25.9214 2.392 27 4 27H24V24.3H4.016C3.4 24.2838 2.66667 24.0368 2.66667 22.95C2.66667 21.8633 3.4 21.6162 4.016 21.6ZM6.66667 5.4H18.6667V8.1H6.66667V5.4Z" fill="#5D87FF"/>
                            </svg>
                            <h5 class="fw-bold mb-0 ms-2">Informasi Tugas</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5>Judul Tugas</h5>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
                            <p class="mb-0">{{ $task->title }}</p>
                            <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-2 flex-shrink-0">
                                Tenggat: {{ $deadline->translatedFormat('d F Y, H:i') }}
                            </span>
                        </div>

                        <hr>
                        <h5 class="mt-2">Deskripsi</h5>
                        <div class="mb-4 description-container">{!! $task->description !!}</div>

                        <hr>
                        <h5 class="mt-2">Tugas</h5>

                        @if ($task->submission_type === SubmissionTypeEnum::FILE)
                            {{-- Tampilkan file yang sudah dikumpulkan --}}
                            <div id="uploadedFile">
                                <div class="d-flex justify-content-between align-items-center border border-success rounded p-2 bg-light-success">
                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                        <svg width="40" height="40" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0">
                                            <path d="M26.344 6H18C14.228 6 12.344 6 11.172 7.172C10 8.344 10 10.228 10 14V34C10 37.772 10 39.656 11.172 40.828C12.344 42 14.228 42 18 42H30C33.772 42 35.656 42 36.828 40.828C38 39.656 38 37.772 38 34V17.656C38 16.84 38 16.43 37.848 16.064C37.696 15.698 37.408 15.406 36.828 14.828L29.172 7.172C28.592 6.592 28.304 6.304 27.938 6.152C27.57 6 27.16 6 26.344 6Z" stroke="#13DEB9" stroke-width="2"/>
                                            <path d="M26 6V14C26 15.886 26 16.828 26.586 17.414C27.172 18 28.114 18 30 18H38" stroke="#13DEB9" stroke-width="2"/>
                                        </svg>
                                        <small class="fw-semibold d-block text-truncate">
                                            {{ basename($submission->answer) }}
                                        </small>
                                    </div>
                                    <a href="{{ route('api.task-submission.download', ['taskSubmission' => $submission->id]) }}" class="flex-shrink-0 ms-2">
                                        <svg width="40" height="40" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M24 32L14 22L16.8 19.1L22 24.3V8H26V24.3L31.2 19.1L34 22L24 32ZM12 40C10.9 40 9.95867 39.6087 9.176 38.826C8.39333 38.0433 8.00133 37.1013 8 36V30H12V36H36V30H40V36C40 37.1 39.6087 38.042 38.826 38.826C38.0433 39.61 37.1013 40.0013 36 40H12Z" fill="#13DEB9"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="d-flex gap-2 align-items-center">
                                <input type="text" id="url-task" name="answer" class="form-control"
                                    placeholder="Masukkan Link Tugas Anda"
                                    value="{{ $submission?->answer }}"
                                    {{ $isGraded ? 'disabled' : '' }}
                                    oninput="toggleCopyButton()">
                                <button class="btn btn-outline-primary flex-shrink-0" type="button" id="copyBtn"
                                    onclick="copyUrl()" style="display: none;">Salin Tautan</button>
                            </div>
                        @endif

                        {{-- Form upload ulang (hanya jika canSubmit & belum dinilai) --}}
                        @if ($showForm && $task->submission_type === SubmissionTypeEnum::FILE)
                            <form id="submissionForm"
                                action="{{ route('api.student.submission-task.storeOrUpdate', $task) }}"
                                method="POST" enctype="multipart/form-data" class="mt-3">
                                @csrf
                                <div class="border rounded-2 p-4 text-center bg-light dropzone" id="dropzone">
                                    <svg width="60" height="60" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M23.3346 73.3333C18.9144 73.3333 14.6751 71.5773 11.5495 68.4517C8.42392 65.3261 6.66797 61.0869 6.66797 56.6666V46.6666C6.66797 45.7825 7.01916 44.9347 7.64428 44.3096C8.2694 43.6844 9.11725 43.3333 10.0013 43.3333C10.8854 43.3333 11.7332 43.6844 12.3583 44.3096C12.9834 44.9347 13.3346 45.7825 13.3346 46.6666V56.6666C13.3346 59.3187 14.3882 61.8623 16.2636 63.7377C18.1389 65.613 20.6825 66.6666 23.3346 66.6666H56.668C59.3201 66.6666 61.8637 65.613 63.739 63.7377C65.6144 61.8623 66.668 59.3187 66.668 56.6666V46.6666C66.668 45.7825 67.0192 44.9347 67.6443 44.3096C68.2694 43.6844 69.1172 43.3333 70.0013 43.3333C70.8854 43.3333 71.7332 43.6844 72.3583 44.3096C72.9834 44.9347 73.3346 45.7825 73.3346 46.6666V56.6666C73.3346 61.0869 71.5787 65.3261 68.4531 68.4517C65.3275 71.5773 61.0882 73.3333 56.668 73.3333H23.3346Z" fill="#5D87FF"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M24.2833 33.6667C23.9768 33.3536 23.7351 32.9832 23.5719 32.5766C23.4087 32.1701 23.3273 31.7353 23.3322 31.2973C23.3372 30.8592 23.4285 30.4264 23.6008 30.0237C23.7731 29.6209 24.0232 29.2561 24.3366 28.95L39.3366 14.2833C39.9594 13.6746 40.7957 13.3337 41.6666 13.3337C42.5375 13.3337 43.3738 13.6746 43.9966 14.2833L58.9966 28.95C59.3163 29.2544 59.5724 29.6193 59.7499 30.0234C59.9275 30.4276 60.023 30.863 60.0309 31.3044C60.0388 31.7457 59.9589 32.1843 59.796 32.5945C59.633 33.0048 59.3902 33.3786 59.0816 33.6943C58.773 34.0099 58.4048 34.2611 57.9983 34.4333C57.5919 34.6055 57.1552 34.6953 56.7138 34.6974C56.2724 34.6995 55.8349 34.6138 55.4268 34.4455C55.0188 34.2771 54.6482 34.0294 54.3366 33.7167L45 24.5867V53.3333C45 54.2174 44.6488 55.0652 44.0236 55.6904C43.3985 56.3155 42.5507 56.6667 41.6666 56.6667C40.7826 56.6667 39.9347 56.3155 39.3096 55.6904C38.6845 55.0652 38.3333 54.2174 38.3333 53.3333V24.5867L29 33.7167C28.6869 34.0231 28.3165 34.2648 27.9099 34.428C27.5034 34.5912 27.0686 34.6727 26.6306 34.6677C26.1925 34.6628 25.7597 34.5715 25.357 34.3992C24.9542 34.2268 24.5893 33.9768 24.2833 33.6633V33.6667Z" fill="#5D87FF"/>
                                    </svg>
                                    <p class="mb-1 fw-semibold">Drag file here to Upload Again</p>
                                    <small class="text-primary">Max 10 MB</small>
                                    <input type="file" name="answer" class="form-control mt-3" hidden id="fileInput">
                                </div>
                                <div id="previewContainer" class="mt-3"></div>
                                <div class="d-none" id="validationErrors">
                                    <ul class="mb-0 text-danger" id="errorList"></ul>
                                </div>
                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('student.e-learning.detail', [$task->topic->classroom_id, $task->topic->subject_id]) }}"
                                        class="btn btn-outline-secondary">Kembali</a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <span class="btn-text">Kirim Ulang</span>
                                        <span class="btn-loading d-none">
                                            <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                            Mengirim...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        @elseif ($showForm && $task->submission_type !== SubmissionTypeEnum::FILE)
                            {{-- Submit ulang untuk tipe link --}}
                            <form id="submissionForm"
                                action="{{ route('api.student.submission-task.storeOrUpdate', $task) }}"
                                method="POST" class="mt-3">
                                @csrf
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="text" id="url-task-edit" name="answer" class="form-control"
                                        placeholder="Perbarui Link Tugas Anda"
                                        value="{{ $submission?->answer }}">
                                </div>
                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('student.e-learning.detail', [$task->topic->classroom_id, $task->topic->subject_id]) }}"
                                        class="btn btn-outline-secondary">Kembali</a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <span class="btn-text">Perbarui</span>
                                        <span class="btn-loading d-none">
                                            <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                            Mengirim...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Penilaian Guru --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.9987 29.3333C7.26536 29.3333 6.63781 29.0724 6.11603 28.5507C5.59425 28.0289 5.33292 27.4009 5.33203 26.6667V5.33332C5.33203 4.59999 5.59336 3.97243 6.11603 3.45066C6.6387 2.92888 7.26625 2.66755 7.9987 2.66666H17.5654C17.9209 2.66666 18.26 2.73332 18.5827 2.86666C18.9054 2.99999 19.1885 3.18888 19.432 3.43332L25.8987 9.89999C26.1431 10.1444 26.332 10.428 26.4654 10.7507C26.5987 11.0733 26.6654 11.412 26.6654 11.7667V26.6667C26.6654 27.4 26.4045 28.028 25.8827 28.5507C25.3609 29.0733 24.7329 29.3342 23.9987 29.3333H7.9987ZM17.332 10.6667C17.332 11.0444 17.46 11.3613 17.716 11.6173C17.972 11.8733 18.2885 12.0009 18.6654 12H23.9987L17.332 5.33332V10.6667ZM14.5654 20.1667L12.6987 18.3C12.5654 18.1667 12.4209 18.0667 12.2654 18C12.1098 17.9333 11.9489 17.9 11.7827 17.9C11.6165 17.9 11.4498 17.9333 11.2827 18C11.1156 18.0667 10.9654 18.1667 10.832 18.3C10.5654 18.5667 10.432 18.8835 10.432 19.2507C10.432 19.6178 10.5654 19.9342 10.832 20.2L13.6654 23.0667C13.7987 23.2 13.9431 23.2947 14.0987 23.3507C14.2543 23.4067 14.4209 23.4342 14.5987 23.4333C14.7765 23.4324 14.9431 23.4049 15.0987 23.3507C15.2543 23.2964 15.3987 23.2018 15.532 23.0667L21.1654 17.4333C21.432 17.1667 21.5654 16.8444 21.5654 16.4667C21.5654 16.0889 21.432 15.7667 21.1654 15.5C20.8987 15.2333 20.5765 15.1 20.1987 15.1C19.8209 15.1 19.4987 15.2333 19.232 15.5L14.5654 20.1667Z" fill="#5D87FF"/>
                            </svg>
                            <h5 class="fw-bold mb-0 ms-2">Penilaian Guru</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="text-muted">Nilai Kamu</h5>
                            @if ($isGraded)
                                @if ($submission->grade >= 75)
                                    <h1 class="fw-bolder mb-0"
                                        style="font-size: clamp(40px, 8vw, 60px); background: linear-gradient(180deg, #51B6FF, #4F7CFF); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: inline-block;">
                                        {{ number_format($submission->grade, 2, ',', '.') }}
                                    </h1>
                                    <h6 class="text-muted">Hebat! Pertahankan</h6>
                                @else
                                    <h1 class="fw-bolder mb-0"
                                        style="font-size: clamp(40px, 8vw, 60px); background: linear-gradient(180deg, #FA6B6B, #E72A2A); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: inline-block;">
                                        {{ number_format($submission->grade, 2, ',', '.') }}
                                    </h1>
                                    <h6 class="text-muted">Tingkatkan belajarmu dan Tetap semangat</h6>
                                @endif
                            @else
                                <h1 class="fw-bolder mb-0"
                                    style="font-size: clamp(40px, 8vw, 60px); background: linear-gradient(180deg, #FFB431, #F6BB22); -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: inline-block;">
                                    Belum Dinilai
                                </h1>
                                <h6 class="text-muted">Tugas kamu sudah dikumpulkan namun belum dinilai</h6>
                            @endif
                        </div>

                        @if ($isGraded && $submission->note)
                            <div class="mt-3">
                                <div class="d-flex align-items-center mb-2">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="32" height="32" fill="white"/>
                                        <path d="M18.668 25.3333C23.696 25.3333 26.2106 25.3333 27.772 23.7707C29.3333 22.208 29.3346 19.6947 29.3346 14.6667C29.3346 9.63867 29.3346 7.124 27.772 5.56267C26.2093 4.00133 23.696 4 18.668 4H13.3346C8.30664 4 5.79197 4 4.23064 5.56267C2.6693 7.12533 2.66797 9.63867 2.66797 14.6667C2.66797 19.6947 2.66797 22.2093 4.23064 23.7707C5.1013 24.6427 6.26797 25.028 8.0013 25.1973" fill="#CCCCCC"/>
                                        <path d="M18.667 25.3333C17.019 25.3333 15.203 26 13.5457 26.86C10.8817 28.2427 9.54965 28.9347 8.89365 28.4933C8.23765 28.052 8.36165 26.6867 8.61099 23.9547L8.66699 23.3333" fill="#CCCCCC"/>
                                        <path d="M10 14H10.5" stroke="white" stroke-width="3" stroke-linecap="round"/>
                                        <path d="M16 14H16.5" stroke="white" stroke-width="3" stroke-linecap="round"/>
                                        <path d="M22 14H22.5" stroke="white" stroke-width="3" stroke-linecap="round"/>
                                    </svg>
                                    <h5 class="fw-semibold ms-2 mb-0">Catatan Guru</h5>
                                </div>
                                <div class="bg-light rounded-3 p-3">
                                    <p class="mb-0 text-muted">"{{ $submission->note }}"</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    {{-- ===================== CASE: belum submit, masih bisa submit ===================== --}}
    @else
        {{-- Card Judul & Deskripsi --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body pb-3">
                <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-start">
                    <div class="mb-2 mb-lg-0">
                        <h5 class="fw-bold mb-1">{{ $task->title }}</h5>
                        <small class="text-muted">{{ $task->topic->employee->user->name }}</small>
                    </div>
                    <div class="text-center badge rounded-2 p-3 bg-light-warning text-warning">
                        Tenggat: {{ $deadline->translatedFormat('d F Y, H:i') }}
                    </div>
                </div>
            </div>
            <hr class="m-0">
            <div class="card-body pt-3">
                <h6 class="fw-semibold">Deskripsi</h6>
                <div class="mb-4 description-container">{!! $task->description !!}</div>
            </div>
        </div>

        {{-- Card Upload Tugas --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body pb-2">
                <h6 class="fw-bold">Upload Tugas</h6>
            </div>

            <form id="submissionForm"
                action="{{ route('api.student.submission-task.storeOrUpdate', $task) }}"
                method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card-body pt-0">
                    <div class="alert alert-primary d-flex gap-2 mb-4">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <strong class="text-primary mb-0">Informasi</strong>
                            </div>
                            <ul class="list-unstyled mb-0 d-grid gap-1">
                                @if ($task->submission_type === SubmissionTypeEnum::FILE)
                                    <li>• Format file harus berupa <b>.zip / .rar</b></li>
                                    <li>• Ukuran file maksimal <b>10 MB</b></li>
                                    <li>• Pastikan file sudah sesuai dengan kriteria dan syarat ketentuan tugas</li>
                                @else
                                    <li>• Format tugas harus berupa <b>tautan/link</b></li>
                                    <li>• Pastikan tautan/link yang dikumpulkan sudah sesuai dengan kriteria</li>
                                    <li>• Pastikan tautan/link dapat diakses oleh guru</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                @if ($task->submission_type === SubmissionTypeEnum::FILE)
                    <div class="card-body pt-0 pb-0">
                        <div class="border rounded-2 p-5 text-center bg-light dropzone" id="dropzone">
                            <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M23.3346 73.3333C18.9144 73.3333 14.6751 71.5773 11.5495 68.4517C8.42392 65.3261 6.66797 61.0869 6.66797 56.6666V46.6666C6.66797 45.7825 7.01916 44.9347 7.64428 44.3096C8.2694 43.6844 9.11725 43.3333 10.0013 43.3333C10.8854 43.3333 11.7332 43.6844 12.3583 44.3096C12.9834 44.9347 13.3346 45.7825 13.3346 46.6666V56.6666C13.3346 59.3187 14.3882 61.8623 16.2636 63.7377C18.1389 65.613 20.6825 66.6666 23.3346 66.6666H56.668C59.3201 66.6666 61.8637 65.613 63.739 63.7377C65.6144 61.8623 66.668 59.3187 66.668 56.6666V46.6666C66.668 45.7825 67.0192 44.9347 67.6443 44.3096C68.2694 43.6844 69.1172 43.3333 70.0013 43.3333C70.8854 43.3333 71.7332 43.6844 72.3583 44.3096C72.9834 44.9347 73.3346 45.7825 73.3346 46.6666V56.6666C73.3346 61.0869 71.5787 65.3261 68.4531 68.4517C65.3275 71.5773 61.0882 73.3333 56.668 73.3333H23.3346Z" fill="#5D87FF"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M24.2833 33.6667C23.9768 33.3536 23.7351 32.9832 23.5719 32.5766C23.4087 32.1701 23.3273 31.7353 23.3322 31.2973C23.3372 30.8592 23.4285 30.4264 23.6008 30.0237C23.7731 29.6209 24.0232 29.2561 24.3366 28.95L39.3366 14.2833C39.9594 13.6746 40.7957 13.3337 41.6666 13.3337C42.5375 13.3337 43.3738 13.6746 43.9966 14.2833L58.9966 28.95C59.3163 29.2544 59.5724 29.6193 59.7499 30.0234C59.9275 30.4276 60.023 30.863 60.0309 31.3044C60.0388 31.7457 59.9589 32.1843 59.796 32.5945C59.633 33.0048 59.3902 33.3786 59.0816 33.6943C58.773 34.0099 58.4048 34.2611 57.9983 34.4333C57.5919 34.6055 57.1552 34.6953 56.7138 34.6974C56.2724 34.6995 55.8349 34.6138 55.4268 34.4455C55.0188 34.2771 54.6482 34.0294 54.3366 33.7167L45 24.5867V53.3333C45 54.2174 44.6488 55.0652 44.0236 55.6904C43.3985 56.3155 42.5507 56.6667 41.6666 56.6667C40.7826 56.6667 39.9347 56.3155 39.3096 55.6904C38.6845 55.0652 38.3333 54.2174 38.3333 53.3333V24.5867L29 33.7167C28.6869 34.0231 28.3165 34.2648 27.9099 34.428C27.5034 34.5912 27.0686 34.6727 26.6306 34.6677C26.1925 34.6628 25.7597 34.5715 25.357 34.3992C24.9542 34.2268 24.5893 33.9768 24.2833 33.6633V33.6667Z" fill="#5D87FF"/>
                            </svg>
                            <p class="mb-1 fw-semibold">Drag file here to Upload</p>
                            <small class="text-primary">Max 10 MB</small>
                            <input type="file" name="answer" class="form-control mt-3" hidden id="fileInput">
                        </div>
                        @if ($submission)
                            <div id="uploadedFile" class="mt-3">
                                <div class="d-flex justify-content-between align-items-center border border-success rounded p-2 bg-light-success">
                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                        <svg width="40" height="40" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0">
                                            <path d="M26.344 6H18C14.228 6 12.344 6 11.172 7.172C10 8.344 10 10.228 10 14V34C10 37.772 10 39.656 11.172 40.828C12.344 42 14.228 42 18 42H30C33.772 42 35.656 42 36.828 40.828C38 39.656 38 37.772 38 34V17.656C38 16.84 38 16.43 37.848 16.064C37.696 15.698 37.408 15.406 36.828 14.828L29.172 7.172C28.592 6.592 28.304 6.304 27.938 6.152C27.57 6 27.16 6 26.344 6Z" stroke="#13DEB9" stroke-width="2"/>
                                            <path d="M26 6V14C26 15.886 26 16.828 26.586 17.414C27.172 18 28.114 18 30 18H38" stroke="#13DEB9" stroke-width="2"/>
                                        </svg>
                                        <small class="fw-semibold d-block text-truncate">
                                            {{ basename($submission->answer) }}
                                        </small>
                                    </div>
                                    <a href="{{ route('api.task-submission.download', ['taskSubmission' => $submission->id]) }}" class="flex-shrink-0 ms-2">
                                        <svg width="40" height="40" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M24 32L14 22L16.8 19.1L22 24.3V8H26V24.3L31.2 19.1L34 22L24 32ZM12 40C10.9 40 9.95867 39.6087 9.176 38.826C8.39333 38.0433 8.00133 37.1013 8 36V30H12V36H36V30H40V36C40 37.1 39.6087 38.042 38.826 38.826C38.0433 39.61 37.1013 40.0013 36 40H12Z" fill="#13DEB9"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div id="previewContainer" class="mt-3"></div>
                    </div>
                @else
                    <div class="card-body pt-0 pb-0">
                        <label for="url-task" class="form-label fw-semibold">Masukkan Link Tugas</label>
                        <div class="d-flex justify-content-between gap-2">
                            <input type="text" id="url-task" name="answer" class="form-control"
                                placeholder="Masukkan Link Tugas Anda" value="{{ $submission?->answer }}">
                            @if ($submission)
                                <button class="btn btn-outline-primary flex-shrink-0" type="button" id="copyBtn"
                                    onclick="copyUrl()">Salin Tautan</button>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="card-body py-0 d-none" id="validationErrors">
                    <ul class="mb-0 text-danger" id="errorList"></ul>
                </div>

                <div class="card-body d-flex justify-content-end gap-2">
                    <a href="{{ route('student.e-learning.detail', [$task->topic->classroom_id, $task->topic->subject_id]) }}"
                        class="btn btn-outline-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="btn-text">Kirim</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                            Mengirim...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    @endif

@endsection

@include('student.pages.task-detail.script.index')