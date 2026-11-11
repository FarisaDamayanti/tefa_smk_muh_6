<style>
    .avatar-wrapper {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        overflow: hidden;
    }

    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
    }

</style>

<div class="card">
    <div class="card-header bg-primary text-white">
        Profil
    </div>
    <div class="card-body">
        <form id="profileForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="row">
                <div class="col-12 col-md-3">
                    <p class="mb-4 form-labeal">Foto Profil</p>
                    <div class="d-flex flex-column align-items-center gap-2 p-3">
                        <div class="position-relative">
                            <div class="avatar-wrapper">
                                <img src="{{ auth()->user()->employee->image ? asset('storage/' . auth()->user()->employee->image) : asset('assets/images/default-user.jpeg') }}" id="photoPreview" alt="Profile">
                            </div>

                            <div id="photoLoader" class="position-absolute top-50 start-50 translate-middle d-none">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>

                        </div>
                        <input type="file" name="image" id="photoInput" class="d-none" accept="image/*">
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <button type="button" id="btnUpload" class="btn btn-primary btn-sm">
                                Unggah Foto
                            </button>
                            @if(auth()->user()->employee->image)
                                <button type="button" id="btnRemove" class="btn btn-outline-primary btn-sm">
                                    Hapus
                                </button>
                            @endif
                        </div>
                        <small class="text-danger error-text image_error"></small>
                    </div>
                </div>
                <div class="col-12 col-md-9">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" placeholder="Ubah Nama Pengguna" id="name" name="name" value="{{ auth()->user()->name }}">
                        <small class="text-danger error-text name_error"></small>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Ubah Email" value="{{ auth()->user()->email }}">
                        <small class="text-danger error-text email_error"></small>
                    </div>
                    <div class="mb-3">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" placeholder="Ubah NIP" value="{{ auth()->user()->employee->nip }}">
                        <small class="text-danger error-text nip_error"></small>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomor Telepon</label>
                        <input type="number" class="form-control" id="phone_number" name="phone_number" placeholder="Ubah Nomor Telepon" value="{{ auth()->user()->employee->phone_number }}">
                        <small class="text-danger error-text phone_number_error"></small>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Ubah Alamat" value="{{ auth()->user()->employee->address }}">
                        <small class="text-danger error-text address_error"></small>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-outline-primary">Batal</button>
                        <button type="submit" id="btnSubmit" class="btn btn-primary">Simpan
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>
<script>
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    const btnUpload = document.getElementById('btnUpload');
    const photoLoader = document.getElementById('photoLoader');

    photoPreview.addEventListener('click', () => {
        photoInput.click();
    });

    btnUpload.addEventListener('click', () => {
        photoInput.click();
    });

    // preview gambar
    photoInput.addEventListener('change', () => {
        const file = photoInput.files[0];
        if (!file) return;

        photoLoader.classList.remove('d-none');

        const imgUrl = URL.createObjectURL(file);

        photoPreview.onload = () => {
            photoLoader.classList.add('d-none');
            URL.revokeObjectURL(imgUrl);
        };

        photoPreview.src = imgUrl;
    });
</script>

@include('teacher.pages.profile.scripts.edit-profile')
