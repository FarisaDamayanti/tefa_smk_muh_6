<?php

namespace App\Services;

class LandingService
{
    public function __construct() {}

    public function faqs(): array
    {
        $faqs = [
            [
                'id' => 1,
                'question' => 'Apa itu MiSchool?',
                'answer' => 'Mischool adalah website manajemen sekolah yang dirancang untuk memudahkan pengelolaan sekolah. Website ini dilengkapi dengan fitur-fitur seperti absensi, jurnal mengajar, jurnal staff, pelanggaran dan perbaikan, tanggapan siswa, serta buku tamu.'
            ],
            [
                'id' => 2,
                'question' => 'Apa saja fitur-fitur yang didapatkan?',
                'answer' => 'Kami menyediakan Fitur Absensi, Pelanggaran, Perbaikan, Jurnal Mengajar & Jurnal Staf, Tanggapan Siwa, dan Buku Tamu.'
            ],
            [
                'id' => 3,
                'question' => 'Bagaimana cara sekolah mendapatkan akses ke sistem ini?',
                'answer' => 'Setiap sekolah akan mendapatkan subdomain khusus. Nantinya Admin sekolah akan menerima informasi login dari kami untuk memanajemen data lebih lanjut.'
            ],
            [
                'id' => 4,
                'question' => 'Apakah Mischool menyediakan panduan penggunaan?',
                'answer' => 'Ya, setiap pengguna akan mendapatkan manual book yang berisi panduan lengkap tentang cara menggunakan fitur-fitur pada platform sesuai dengan perannya. Mulai dari admin sekolah, guru, staf, hingga siswa.'
            ]
        ];

        return $faqs;
    }
}
