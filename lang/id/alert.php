<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Alert Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for various alert
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
    'fetch_success' => 'Berhasil fetch data',
    'not_found' => 'Data tidak ditemukan',
    'internal_error' => 'Terjadi kesalahan pada server',
    'fetch_failed' => 'Gagal fetch data',
    'download_success' => 'Berhasil download data',
    'download_failed' => 'Gagal download data',
    'add_success' => 'Berhasil menambahkan data',
    'update_success' => 'Berhasil memperbarui data',
    'draft_success' => 'berhasil diubah ke status draft',
    'restore_success' => 'berhasil mengembalikan data',
    'force_delete_success' => 'Data berhasil dihapus permanen.',
    'add_failed' => 'Gagal menambahkan data',
    'accept_failed' => 'Gagal menyetujui permintaan',
    'failed_to_update_status' => 'Gagal merubah status',
    'accept_success' => 'Berhasil menyetujui permintaan',
    'reject_success' => 'Berhasil menolak permintaan',
    'return_success' => 'Berhasil mengembalikan permintaan',
    'update_failed' => 'Gagal memperbarui data',
    'soft_delete_success' => 'Berhasil mengarsipkan data',
    'restore_success' => 'Berhasil mengaktifkan data',
    'delete_success' => 'Berhasil menghapus data',
    'delete_constrained' => 'Gagal menghapus! data sedang digunakan',
    'delete_failed' => 'Gagal menghapus data',
    'profile_updated' => 'Berhasil memperbarui profil',
    'password_updated' => 'Berhasil memperbarui password',
    'file_exist' => 'File berkas panduan telah tersedia di server. Silahkan ubah nama file',
    'contact_us_feedback' => 'Terimakasih atas masukan anda!',
    'checkout_success' => 'Invoice berhasil dibuat. Silahkan cek email anda',
    'fees_notification' => 'Setiap transaksi akan dikenakan pajak (PPN) sebesar 10%',
    'empty_stock' => 'Stok produk telah habis',
    'add_rating' => 'Berhasil menambahkan ulasan',
    'update_rating' => 'Berhasil mengubah ulasan',
    'failed_rating' => 'Gagal menambahkan ulasan karena anda belum pernah membelinya',
    'delete_rating' => 'Berhasil menghapus ulasan',
    'modify_rating' => 'Berhasil modifikasi rating',
    'send_license_success' => 'Berhasil mengirim lisensi',
    'user_add_rating' => 'Berhasil menambahkan ulasan',
    'user_update_rating' => 'Berhasil mengubah ulasan',
    'login_success' => 'Berhasil login',
    'account_unverified' => 'Akun belum terverifikasi',
    'invalid_callback_signature' => 'Invalid Callback Signature! request blocked.',
    'invalid_callback_event' => 'Unrecognized Callback Event! request blocked.',
    'callback_success' => 'Callback berhasil',
    'view_success' => 'berhasil menambah view',
    'not_pre_test_yet' => 'selesaikan pre-test terlebih dahulu',
    'withdraw_success' => 'Pencairan dana berhasil diproses. Dana akan segera dikirim ke rekening Anda.',
    'withdraw_failed' => 'Pencairan dana gagal. Silakan coba beberapa saat lagi.',

    'publish_failed' => 'Terdapat kejanggalan pada kursus',

    'voucher_limit' => 'Voucher telah habis',
    'voucher_invalid' => 'Kode voucher tidak valid',
    'voucher_valid' => 'Berhasil menggunakan voucher',

    'file_not_found' => 'File tidak ditemukan',
    'class_empty' => 'Tambahkan kelas terlebih dahulu',
    'review_expired' => "Review kadaluarsa",
    'upload_failed' => 'Gagal mengunggah file',
    'upload_success' => 'Berhasil mengunggah file',

    'submit_success' => 'Berhasil mengumpulkan tugas',
    'submit_failed' => 'Gagal mengumpulkan tugas',

    'finish_exam_success' => 'Berhasil mengirim jawaban',
    'finish_exam_failed' => 'Gagal mengirim jawaban',

    'reset_exam_success' => 'Berhasil reset ujian',
    'reset_exam_failed' => 'Gagal reset ujian',

    'assessment_success' => 'Berhasil memberi penilaian',
    'assessment_failed' => 'Gagal memberi penilaian',
    'model_not_found' => 'Data yang diminta tidak ditemukan atau sudah dihapus',

    'rules.exam' => '
        <ul class="row gap-2 px-3">
            <li class="list-group-item">
                <i class="ti ti-alert-circle-filled fs-4 me-2 text-primary"></i>
                Peserta wajib memastikan koneksi internet dalam kondisi stabil sebelum dan selama ujian berlangsung.
            </li>
            <li class="list-group-item">
                <i class="ti ti-alert-circle-filled fs-4 me-2 text-primary"></i>
                Ujian harus dikerjakan menggunakan perangkat pribadi dan tidak diperbolehkan berganti perangkat selama ujian berlangsung.
            </li>
            <li class="list-group-item">
                <i class="ti ti-alert-circle-filled fs-4 me-2 text-primary"></i>
                Peserta tidak diperkenankan bekerja sama, berdiskusi, ataupun menggunakan bantuan pihak lain dalam bentuk apa pun selama ujian.
            </li>
            <li class="list-group-item">
                <i class="ti ti-alert-circle-filled fs-4 me-2 text-primary"></i>
                Ujian akan berjalan dalam mode layar penuh. Apabila peserta keluar dari halaman ujian, sistem dapat langsung mengakhiri dan mengirim jawaban secara otomatis.
            </li>
            <li class="list-group-item">
                <i class="ti ti-alert-circle-filled fs-4 me-2 text-primary"></i>
                Jika waktu pengerjaan berakhir sebelum ujian dikirim, sistem akan otomatis mengirim jawaban yang telah tersimpan.
            </li>
            <li class="list-group-item">
                <i class="ti ti-alert-circle-filled fs-4 me-2 text-primary"></i>
                Pengiriman ujian secara manual hanya dapat dilakukan ketika waktu pengerjaan telah memasuki 5 menit terakhir.
            </li>
            <li class="list-group-item">
                <i class="ti ti-alert-circle-filled fs-4 me-2 text-primary"></i>
                Segala bentuk kecurangan akan dikenakan sanksi sesuai dengan peraturan yang berlaku di sekolah.
            </li>
            <li class="list-group-item">
                <i class="ti ti-alert-circle-filled fs-4 me-2 text-primary"></i>
                Apabila waktu ujian telah berakhir, sistem akan mengatur ulang waktu menjadi 5 menit sebagai kesempatan terakhir untuk menyelesaikan ujian.
            </li>
            <li class="list-group-item">
                <i class="ti ti-alert-circle-filled fs-4 me-2 text-primary"></i>
                Jika sisa waktu kurang dari 5 menit, sistem akan mengikuti waktu yang tersedia.
            </li>
            <li class="list-group-item">
                <i class="ti ti-alert-circle-filled fs-4 me-2 text-primary"></i>
                Jika koneksi terputus, siswa tidak diperbolehkan memuat ulang halaman. Tetap berada di halaman ujian dan segera perbaiki koneksi untuk melanjutkan ujian.
            </li>
        </ul>
    '
];
