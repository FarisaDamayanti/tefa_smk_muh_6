<div class="card">
    <div class="card-header bg-primary text-white">
        Reset Kata Sandi
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('update.password') }}" method="POST" id="reset-password-form">
                    @method('PUT')
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold ">Kata Sandi</label>
                        <div class="input-group">
                            <input type="password" class="form-control password-field" name="current_password" placeholder="Masukkan Kata Sandi Lama">
                            <span class="input-group-text toggle-password" style="cursor:pointer">
                                <i class="ti ti-eye fs-2"></i>
                            </span>
                        </div>
                        @error('current_password')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold ">Kata Sandi Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control password-field" name="password" placeholder="Masukkan Kata Sandi Baru">
                            <span class="input-group-text toggle-password" style="cursor:pointer">
                                <i class="ti ti-eye fs-2"></i>
                            </span>
                        </div>
                        @error('password')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold ">Konfirmasi Kata Sandi </label>
                        <div class="input-group">
                            <input type="password" class="form-control password-field" name="password_confirmation" placeholder="Konfirmasi Kata Sandi ">
                            <span class="input-group-text toggle-password" style="cursor:pointer">
                                <i class="ti ti-eye fs-2"></i>
                            </span>
                        </div>
                        @error('password_confirmation')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-outline-primary">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');

            input.type = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('ti-eye');
            icon.classList.toggle('ti-eye-off');
        });
    });
    document.getElementById('profileForm').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');

        btn.disabled = true;
        btn.querySelector('.btn-text').classList.add('d-none');
        btn.querySelector('.spinner-border').classList.remove('d-none');
    });

</script>
