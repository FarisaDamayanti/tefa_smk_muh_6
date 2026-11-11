@extends('teacher.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/teacher/teacherpresence.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/dropdown/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/material.css') }}">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Tombol Kembali -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-warning">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </button>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <!-- Header -->
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-eye me-2"></i>Preview Materi
                            </h5>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="contentDropdown" data-bs-toggle="dropdown">
                                    <i class="fas fa-file-alt me-1"></i>Pilih Materi
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="contentDropdown">
                                    <li><a class="dropdown-item" href="#" data-content="math">Matematika - Teorema Phytagoras</a></li>
                                    <li><a class="dropdown-item" href="#" data-content="physics">Fisika - Hukum Newton</a></li>
                                    <li><a class="dropdown-item" href="#" data-content="biology">Biologi - Sistem Pencernaan</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Konten Preview -->
                    <div class="card-body p-4">
                        <div id="previewContent" class="border rounded p-4 bg-light" style="min-height: 400px;">
                            <!-- Konten akan dimuat di sini -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 

@section('script')
    <script>
        const materials = {
            'math': `
                <h1>Teorema Phytagoras <span class="badge bg-primary ms-2">Matematika</span></h1>
                <p>Teorema Phytagoras adalah salah satu teorema fundamental dalam matematika yang menghubungkan panjang sisi-sisi pada segitiga siku-siku. Teorema ini dinamai dari matematikawan Yunani kuno, <strong>Pythagoras</strong>.</p>
                
                <div class="alert alert-primary">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Fakta Menarik:</strong> Teorema ini telah dikenal oleh peradaban Babilonia sekitar 1000 tahun sebelum Phytagoras lahir!
                </div>
                
                <h2>Rumus Dasar</h2>
                <p>Pada segitiga siku-siku dengan sisi miring (hipotenusa) <em>c</em> dan sisi siku-siku <em>a</em> dan <em>b</em>, berlaku:</p>
                <div class="text-center mb-4">
                    <div class="display-6 text-primary">c² = a² + b²</div>
                    <small class="text-muted">Dimana c adalah sisi miring</small>
                </div>
                
                <h2>Contoh Soal</h2>
                <p>Diketahui segitiga siku-siku dengan panjang sisi:</p>
                <table class="table table-bordered w-50">
                    <thead>
                        <tr class="table-light">
                            <th>Sisi</th>
                            <th>Panjang (cm)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>a (alas)</td>
                            <td>6</td>
                        </tr>
                        <tr>
                            <td>b (tinggi)</td>
                            <td>8</td>
                        </tr>
                        <tr>
                            <td>c (miring)</td>
                            <td>?</td>
                        </tr>
                    </tbody>
                </table>
                
                <h4>Penyelesaian:</h4>
                <div class="bg-white p-3 rounded border">
                    <ol>
                        <li>Gunakan rumus: <strong>c² = a² + b²</strong></li>
                        <li>Substitusi nilai: <strong>c² = 6² + 8²</strong></li>
                        <li>Hitung: <strong>c² = 36 + 64 = 100</strong></li>
                        <li><strong>c = √100 = 10 cm</strong></li>
                    </ol>
                </div>
                
                <div class="mt-4">
                    <h4>Aplikasi dalam Kehidupan Sehari-hari:</h4>
                    <ul>
                        <li>Menghitung panjang tangga yang bersandar di tembok</li>
                        <li>Menentukan jarak terpendek antara dua titik</li>
                        <li>Perhitungan dalam konstruksi bangunan</li>
                    </ul>
                </div>
            `,
            
            'physics': `
                <h1>Hukum Newton <span class="badge bg-danger ms-2">Fisika</span></h1>
                <p>Hukum Newton adalah tiga hukum fisika yang menjadi dasar mekanika klasik. Hukum ini menggambarkan hubungan antara gaya yang bekerja pada suatu benda dan gerak yang disebabkannya.</p>
                
                <h2>Hukum I Newton (Hukum Inersia)</h2>
                <div class="bg-white p-3 rounded border mb-3">
                    <p class="lead mb-0">"Setiap benda akan tetap berada dalam keadaan diam atau bergerak lurus beraturan, kecuali jika ada gaya yang bekerja padanya."</p>
                </div>
                
                <h2>Hukum II Newton</h2>
                <div class="bg-white p-3 rounded border mb-3">
                    <p class="lead mb-0">"Percepatan yang dihasilkan oleh resultan gaya yang bekerja pada suatu benda berbanding lurus dengan resultan gaya dan berbanding terbalik dengan massa benda."</p>
                    <div class="text-center mt-2">
                        <strong class="text-primary">F = m × a</strong>
                    </div>
                </div>
                
                <h2>Hukum III Newton (Aksi-Reaksi)</h2>
                <div class="bg-white p-3 rounded border mb-3">
                    <p class="lead mb-0">"Untuk setiap aksi, ada reaksi yang sama besar dan berlawanan arah."</p>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Contoh Hukum I</h5>
                                <p>Penumpang terdorong ke depan saat bus berhenti mendadak.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Contoh Hukum III</h5>
                                <p>Roket mendorong gas ke bawah, gas mendorong roket ke atas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            
            'biology': `
                <h1>Sistem Pencernaan <span class="badge bg-success ms-2">Biologi</span></h1>
                <p>Sistem pencernaan adalah sistem organ dalam hewan multisel yang menerima makanan, mencernanya menjadi energi dan nutrien, serta mengeluarkan sisa proses tersebut melalui dubur.</p>
                
                <h2>Organ-organ Pencernaan</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-success">
                            <tr>
                                <th>Organ</th>
                                <th>Fungsi</th>
                                <th>Enzim yang Dihasilkan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Mulut</strong></td>
                                <td>Mengunyah dan menghancurkan makanan</td>
                                <td>Amilase</td>
                            </tr>
                            <tr>
                                <td><strong>Lambung</strong></td>
                                <td>Mencerna protein</td>
                                <td>Pepsin, HCl</td>
                            </tr>
                            <tr>
                                <td><strong>Usus Halus</strong></td>
                                <td>Penyerapan nutrisi</td>
                                <td>Laktase, Maltase</td>
                            </tr>
                            <tr>
                                <td><strong>Usus Besar</strong></td>
                                <td>Penyerapan air</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <h2>Proses Pencernaan</h2>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="display-6 text-primary">1</div>
                                <h5>Ingesti</h5>
                                <p>Memasukkan makanan ke dalam mulut</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="display-6 text-primary">2</div>
                                <h5>Digesti</h5>
                                <p>Pemecahan makanan secara kimiawi</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="display-6 text-primary">3</div>
                                <h5>Absorpsi</h5>
                                <p>Penyerapan nutrisi ke dalam darah</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-success mt-4">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Tips Kesehatan:</strong> Konsumsi serat yang cukup dan minum air putih minimal 8 gelas per hari untuk menjaga kesehatan sistem pencernaan.
                </div>
            `
        };
        
        function loadContent(contentKey) {
            const previewContent = document.getElementById('previewContent');
            
            if (materials[contentKey]) {
                previewContent.innerHTML = materials[contentKey];
                
                // Tambahkan efek loading
                previewContent.style.opacity = '0.7';
                setTimeout(() => {
                    previewContent.style.opacity = '1';
                }, 300);
            }
        }
        
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const contentKey = this.getAttribute('data-content');
                loadContent(contentKey);
                
                // Update teks dropdown
                document.getElementById('contentDropdown').innerHTML = 
                    `<i class="fas fa-file-alt me-1"></i>${this.textContent}`;
            });
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            loadContent('math');
        });
        
        function saveSelectedContent(contentKey) {
            localStorage.setItem('selectedMaterial', contentKey);
        }
        
        function loadSavedContent() {
            const saved = localStorage.getItem('selectedMaterial');
            if (saved && materials[saved]) {
                loadContent(saved);
                
                // Update dropdown
                const dropdownItems = document.querySelectorAll('.dropdown-item');
                dropdownItems.forEach(item => {
                    if (item.getAttribute('data-content') === saved) {
                        document.getElementById('contentDropdown').innerHTML = 
                            `<i class="fas fa-file-alt me-1"></i>${item.textContent}`;
                    }
                });
            }
        }
    
    </script>
@endsection