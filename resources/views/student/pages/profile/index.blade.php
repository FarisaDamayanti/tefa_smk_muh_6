@extends('student.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/profile.css') }}">
@endsection

@section('content')
    <meta name="api-token" content="{{ session('api_bearer') ?? '' }}">

    <div class="student-subjects-page">
        {{-- HEADER --}}
        <div class="card bg-primary shadow-none position-relative overflow-hidden text-light mb-4">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-8 col-md-9">
                        <h4 class="fw-semibold mb-2 text-light">Profil</h4>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item text-light">Edit Profilmu Disini.</li>
                        </ol>
                    </div>
                    <div class="col-4 col-md-3 text-center mb-n5">
                        <img src="{{ asset('admin_assets/dist/images/breadcrumb/ChatBc.png') }}" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD TAB + KEMBALI --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body d-flex justify-content-between align-items-center py-2 px-3">
                <ul class="nav nav-pills gap-2 mb-0" id="profileTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-3 py-1" id="profile-tab" data-bs-toggle="pill"
                            data-bs-target="#profile-pane" type="button" role="tab">
                            Profil
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-3 py-1" id="password-tab" data-bs-toggle="pill"
                            data-bs-target="#password-pane" type="button" role="tab">
                            Reset Kata Sandi
                        </button>
                    </li>
                </ul>


            </div>
        </div>

        {{-- TAB CONTENT --}}
        <div class="tab-content" id="profileTabContent">

            {{-- TAB: PROFILE --}}
            <div class="tab-pane fade show active" id="profile-pane" role="tabpanel">
                <div class="card profile-card shadow-sm">
                    <div class="profile-card__header">Profil</div>

                    <div class="card-body p-4">
                        <form id="formProfile" enctype="multipart/form-data" autocomplete="off" novalidate>
                            @csrf

                            <div class="row g-4">
                                {{-- FOTO --}}
                                <div class="col-12 col-lg-4">
                                    <div class="fw-semibold mb-3">Foto Profil</div>

                                    @php
                                        $studentImage = $profile['image'];
                                        $photoSrc = $studentImage
                                            ? asset('storage/' . $studentImage)
                                            : asset('assets/images/default-user.jpeg');
                                    @endphp

                                    <div class="photo-wrap">
                                        <label class="profile-photo" role="button" for="imageInput">
                                            <img id="previewImage" src="{{ $photoSrc }}" alt="Foto Profil">
                                            <div class="profile-photo__overlay" id="photoOverlay">
                                                <div class="profile-photo__icon">
                                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M3.55556 32C2.57778 32 1.74104 31.6521 1.04533 30.9564C0.34963 30.2607 0.00118519 29.4234 0 28.4444V3.55556C0 2.57778 0.348445 1.74104 1.04533 1.04533C1.74222 0.34963 2.57896 0.00118519 3.55556 0H28.4444C29.4222 0 30.2596 0.348445 30.9564 1.04533C31.6533 1.74222 32.0012 2.57896 32 3.55556V28.4444C32 29.4222 31.6521 30.2596 30.9564 30.9564C30.2607 31.6533 29.4234 32.0012 28.4444 32H3.55556ZM5.33333 24.8889H26.6667L20 16L14.6667 23.1111L10.6667 17.7778L5.33333 24.8889Z"
                                                            fill="white" />
                                                    </svg>
                                                </div>
                                                <div class="profile-photo__text">Klik Untuk<br>Unggah</div>
                                            </div>
                                        </label>
                                    </div>

                                    <input type="file" id="imageInput" name="image" class="d-none" accept="image/*">
                                    <div class="invalid-feedback"></div>

                                    <div class="d-flex justify-content-center gap-2 mt-3">
                                        <button type="button" class="btn btn-primary btn-sm px-4"
                                            id="btnPickImage">
                                            Unggah Foto
                                        </button>
                                        @if(auth()->user()->student->image)
                                            <button type="button" class="btn btn-outline-secondary btn-sm px-4"
                                                id="btnResetImage">
                                                Hapus
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                {{-- FORM INPUT --}}
                                <div class="col-12 col-lg-8">
                                    <div class="row g-3">

                                        <div class="col-12">
                                            <label class="form-label fw-semibold">
                                                Nama Lengkap<span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name" class="form-control profile-input"
                                                value="{{ data_get($profile, 'name') }}" placeholder="Ganti Nama Lengkap.."
                                                required>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-semibold">
                                                Email<span class="text-danger">*</span>
                                            </label>
                                            <input type="email" name="email" class="form-control profile-input"
                                                value="{{ data_get($profile, 'email') }}" placeholder="Ganti Email..."
                                                required>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-semibold">
                                                Nomor Telepon
                                            </label>
                                            <input type="text" name="phone_number" class="form-control profile-input"
                                                value="{{ data_get($profile, 'telephone') }}" maxlength="15"
                                                placeholder="Ganti Nomor Telepon..">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-semibold">
                                                Jenis Kelamin<span class="text-danger">*</span>
                                            </label>

                                            <select name="gender" class="form-select profile-input" required>
                                                <option value="" disabled
                                                    {{ data_get($profile, 'gender')->value ? '' : 'selected' }}>
                                                    Pilih Jenis Kelamin..
                                                </option>
                                                <option value="male"
                                                    {{ data_get($profile, 'gender')->value === 'male' ? 'selected' : '' }}>
                                                    Laki-laki
                                                </option>
                                                <option value="female"
                                                    {{ data_get($profile, 'gender')->value === 'female' ? 'selected' : '' }}>
                                                    Perempuan
                                                </option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-semibold">
                                                Alamat
                                            </label>
                                            <input type="text" name="address" class="form-control profile-input"
                                                value="{{ data_get($profile, 'address') }}" maxlength="500"
                                                placeholder="Ganti Alamat..">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-flex justify-content-end gap-3 mt-4">
                                                <button type="reset"
                                                    class="btn btn-outline-secondary px-4 profile-action" id="btnCancel">
                                                    Batal
                                                </button>
                                                <button type="submit" class="btn btn-primary px-4 profile-action"
                                                    id="btnSave">
                                                    Simpan
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- TAB: RESET PASSWORD --}}
            <div class="tab-pane fade" id="password-pane" role="tabpanel">
                <div class="card profile-card shadow-sm">
                    <div class="profile-card__header">Reset Kata Sandi</div>
                    <div class="card-body p-4">
                        <form id="formUpdatePassword" autocomplete="off" novalidate>
                            <div class="row g-3">

                                {{-- CURRENT PASSWORD --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        Kata Sandi Saat Ini<span class="text-danger">*</span>
                                    </label>

                                    <div class="pw-field">
                                        <input type="password" name="current_password" id="current_password"
                                            class="form-control profile-input" placeholder="Kata sandi saat ini" required>

                                        <button type="button" class="pw-toggle" aria-label="Toggle password"
                                            onclick="togglePw('current_password', this)">
                                            {{-- EYE (OPEN) --}}
                                            <svg class="pw-eye pw-eye-open" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10 12C10 12.5304 10.2107 13.0391 10.5858 13.4142C10.9609 13.7893 11.4696 14 12 14C12.5304 14 13.0391 13.7893 13.4142 13.4142C13.7893 13.0391 14 12.5304 14 12C14 11.4696 13.7893 10.9609 13.4142 10.5858C13.0391 10.2107 12.5304 10 12 10C11.4696 10 10.9609 10.2107 10.5858 10.5858C10.2107 10.9609 10 11.4696 10 12Z"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M21 12C18.6 16 15.6 18 12 18C8.4 18 5.4 16 3 12C5.4 8 8.4 6 12 6C15.6 6 18.6 8 21 12Z"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>

                                            {{-- EYE OFF (CLOSED) --}}
                                            <svg class="pw-eye pw-eye-closed" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3 3L21 21" stroke="black" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M10.12 10.12C9.58 10.66 9.25 11.41 9.25 12.25C9.25 13.91 10.59 15.25 12.25 15.25C13.09 15.25 13.84 14.92 14.38 14.38"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M6.23 6.23C4.6 7.33 3.26 9 2.25 12C3.95 16.5 7.7 18.75 12.25 18.75C13.99 18.75 15.61 18.41 17.05 17.73"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.88 5.12C10.63 4.89 11.43 4.75 12.25 4.75C16.8 4.75 20.55 7 22.25 11.5C21.72 12.9 21.05 14.07 20.25 15.03"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>

                                        <span class="invalid-icon" aria-hidden="true"></span>
                                    </div>

                                    <div class="invalid-feedback"></div>
                                </div>

                                {{-- NEW PASSWORD --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        Kata Sandi Baru<span class="text-danger">*</span>
                                    </label>

                                    <div class="pw-field">
                                        <input type="password" name="password" id="password"
                                            class="form-control profile-input" placeholder="Kata sandi baru" required>

                                        <button type="button" class="pw-toggle" aria-label="Toggle password"
                                            onclick="togglePw('password', this)">
                                            {{-- EYE (OPEN) --}}
                                            <svg class="pw-eye pw-eye-open" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10 12C10 12.5304 10.2107 13.0391 10.5858 13.4142C10.9609 13.7893 11.4696 14 12 14C12.5304 14 13.0391 13.7893 13.4142 13.4142C13.7893 13.0391 14 12.5304 14 12C14 11.4696 13.7893 10.9609 13.4142 10.5858C13.0391 10.2107 12.5304 10 12 10C11.4696 10 10.9609 10.2107 10.5858 10.5858C10.2107 10.9609 10 11.4696 10 12Z"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M21 12C18.6 16 15.6 18 12 18C8.4 18 5.4 16 3 12C5.4 8 8.4 6 12 6C15.6 6 18.6 8 21 12Z"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>

                                            {{-- EYE OFF (CLOSED) --}}
                                            <svg class="pw-eye pw-eye-closed" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3 3L21 21" stroke="black" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M10.12 10.12C9.58 10.66 9.25 11.41 9.25 12.25C9.25 13.91 10.59 15.25 12.25 15.25C13.09 15.25 13.84 14.92 14.38 14.38"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M6.23 6.23C4.6 7.33 3.26 9 2.25 12C3.95 16.5 7.7 18.75 12.25 18.75C13.99 18.75 15.61 18.41 17.05 17.73"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.88 5.12C10.63 4.89 11.43 4.75 12.25 4.75C16.8 4.75 20.55 7 22.25 11.5C21.72 12.9 21.05 14.07 20.25 15.03"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>

                                        <span class="invalid-icon" aria-hidden="true"></span>
                                    </div>

                                    <div class="invalid-feedback"></div>
                                </div>

                                {{-- CONFIRM PASSWORD --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        Konfirmasi Kata Sandi Baru<span class="text-danger">*</span>
                                    </label>

                                    <div class="pw-field">
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control profile-input" placeholder="Konfirmasi kata sandi baru"
                                            required>

                                        <button type="button" class="pw-toggle" aria-label="Toggle password"
                                            onclick="togglePw('password_confirmation', this)">
                                            {{-- EYE (OPEN) --}}
                                            <svg class="pw-eye pw-eye-open" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10 12C10 12.5304 10.2107 13.0391 10.5858 13.4142C10.9609 13.7893 11.4696 14 12 14C12.5304 14 13.0391 13.7893 13.4142 13.4142C13.7893 13.0391 14 12.5304 14 12C14 11.4696 13.7893 10.9609 13.4142 10.5858C13.0391 10.2107 12.5304 10 12 10C11.4696 10 10.9609 10.2107 10.5858 10.5858C10.2107 10.9609 10 11.4696 10 12Z"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M21 12C18.6 16 15.6 18 12 18C8.4 18 5.4 16 3 12C5.4 8 8.4 6 12 6C15.6 6 18.6 8 21 12Z"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>

                                            {{-- EYE OFF (CLOSED) --}}
                                            <svg class="pw-eye pw-eye-closed" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3 3L21 21" stroke="black" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M10.12 10.12C9.58 10.66 9.25 11.41 9.25 12.25C9.25 13.91 10.59 15.25 12.25 15.25C13.09 15.25 13.84 14.92 14.38 14.38"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M6.23 6.23C4.6 7.33 3.26 9 2.25 12C3.95 16.5 7.7 18.75 12.25 18.75C13.99 18.75 15.61 18.41 17.05 17.73"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.88 5.12C10.63 4.89 11.43 4.75 12.25 4.75C16.8 4.75 20.55 7 22.25 11.5C21.72 12.9 21.05 14.07 20.25 15.03"
                                                    stroke="black" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>

                                        <span class="invalid-icon" aria-hidden="true"></span>
                                    </div>

                                    <div class="invalid-feedback"></div>
                                </div>

                                {{-- ACTIONS --}}
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-3 mt-4">
                                        <button type="reset" class="btn btn-outline-secondary px-4 profile-action">
                                            Batal
                                        </button>
                                        <button type="submit" class="btn btn-primary px-4 profile-action"
                                            id="btnSavePassword">
                                            Simpan
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- MODAL KONFIRMASI HAPUS FOTO PROFIL --}}
<div class="modal fade" id="confirmDeleteProfileImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-body text-center p-4 p-md-5">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="width:72px;height:72px;border-radius:999px;background:#ffe5e5;">
                    <span style="font-size:38px;line-height:1;color:#ff4d4f;font-weight:800;">!</span>
                </div>

                <h5 class="fw-semibold mb-2">
                    Apakah anda yakin ingin menghapus foto profil ini?
                </h5>
                <p class="text-muted mb-4">
                    Foto profil yang sudah dihapus tidak dapat dikembalikan.
                </p>

                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="btn btn-danger px-4" id="btnConfirmDeleteProfileImage">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    @include('student.pages.profile.scripts.profile')
@endsection
