<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Panduan Penggunaan Aplikasi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="javascript:;" data-page="dashboard">Surat</a></li>
                        <li class="breadcrumb-item active">Panduan</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Tabs -->
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-panduan" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-panduan-umum" data-toggle="pill" 
                                       href="#panduan-umum" role="tab" aria-controls="panduan-umum" 
                                       aria-selected="true">
                                        <i class="fas fa-book"></i> Panduan Umum
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-panduan-surat" data-toggle="pill" 
                                       href="#panduan-surat" role="tab" aria-controls="panduan-surat" 
                                       aria-selected="false">
                                        <i class="fas fa-envelope"></i> Pengelolaan Surat
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-panduan-arsip" data-toggle="pill" 
                                       href="#panduan-arsip" role="tab" aria-controls="panduan-arsip" 
                                       aria-selected="false">
                                        <i class="fas fa-archive"></i> Arsip
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-panduan-tabContent">
                                
                                <!-- Tab Panduan Umum -->
                                <div class="tab-pane fade show active" id="panduan-umum" role="tabpanel" 
                                     aria-labelledby="tab-panduan-umum">
                                    <h3><i class="fas fa-info-circle text-primary"></i> Tentang SIAS</h3>
                                    <p class="lead">
                                        SIAS (Sistem Informasi Arsip Surat) adalah aplikasi berbasis web untuk 
                                        mengelola surat masuk, surat keluar, disposisi, dan arsip digital.
                                    </p>
                                    
                                    <div class="callout callout-info">
                                        <h5><i class="icon fas fa-info"></i> Informasi:</h5>
                                        Aplikasi ini menggunakan teknologi CodeIgniter 3 dan AdminLTE 3 
                                        dengan pendekatan Single Page Application (SPA).
                                    </div>

                                    <h4 class="mt-4"><i class="fas fa-users"></i> Peran Pengguna</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-box bg-info">
                                                <span class="info-box-icon"><i class="fas fa-user-shield"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Admin</span>
                                                    <span class="info-box-number">Pengelola Sistem</span>
                                                    <div class="progress">
                                                        <div class="progress-bar" style="width: 100%"></div>
                                                    </div>
                                                    <span class="progress-description">
                                                        Akses penuh ke semua fitur aplikasi
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-success">
                                                <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Penelaah</span>
                                                    <span class="info-box-number">Validasi Surat</span>
                                                    <div class="progress">
                                                        <div class="progress-bar" style="width: 80%"></div>
                                                    </div>
                                                    <span class="progress-description">
                                                        Dapat memvalidasi dan mengelola surat
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-warning">
                                                <span class="info-box-icon"><i class="fas fa-user-tie"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Pejabat</span>
                                                    <span class="info-box-number">Penerima Disposisi</span>
                                                    <div class="progress">
                                                        <div class="progress-bar" style="width: 60%"></div>
                                                    </div>
                                                    <span class="progress-description">
                                                        Mengelola surat dan disposisi
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box bg-secondary">
                                                <span class="info-box-icon"><i class="fas fa-user"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Operator</span>
                                                    <span class="info-box-number">Pengguna Umum</span>
                                                    <div class="progress">
                                                        <div class="progress-bar" style="width: 40%"></div>
                                                    </div>
                                                    <span class="progress-description">
                                                        Akses arsip dan laporan terbatas
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h4 class="mt-4"><i class="fas fa-laptop"></i> Persyaratan Sistem</h4>
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <i class="fas fa-check text-success"></i> 
                                            Browser modern (Chrome, Firefox, Edge, Safari)
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-check text-success"></i> 
                                            JavaScript diaktifkan
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-check text-success"></i> 
                                            Koneksi internet stabil
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-check text-success"></i> 
                                            Resolusi layar minimal 1024x768
                                        </li>
                                    </ul>

                                    <h4 class="mt-4"><i class="fas fa-palette"></i> Fitur Tampilan</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h5><i class="fas fa-sun text-warning"></i> Mode Terang</h5>
                                                    <p>Tampilan default dengan background putih/terang yang nyaman untuk mata di kondisi normal.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-dark text-white">
                                                <div class="card-body">
                                                    <h5><i class="fas fa-moon text-info"></i> Mode Gelap</h5>
                                                    <p>Tampilan dengan background gelap yang mengurangi kelelahan mata, cocok untuk penggunaan di malam hari atau ruangan minim cahaya.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="callout callout-info mt-3">
                                        <h5><i class="icon fas fa-lightbulb"></i> Tips Dark Mode:</h5>
                                        <p>Klik tombol floating di kanan bawah layar (icon bulan/matahari) untuk beralih antara mode terang dan gelap. Preferensi Anda akan tersimpan secara otomatis!</p>
                                    </div>

                                    <h4 class="mt-4"><i class="fas fa-map-signs"></i> Navigasi Menu</h4>
                                    <p>Semua menu dilengkapi dengan icon yang intuitif untuk memudahkan navigasi:</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-unstyled">
                                                <li><i class="fas fa-envelope-open-text text-primary"></i> <strong>Surat</strong> - Pengelolaan surat masuk, keluar, dan disposisi</li>
                                                <li><i class="fas fa-archive text-info"></i> <strong>Arsip</strong> - Akses arsip surat dan dokumen</li>
                                                <li><i class="fas fa-chart-bar text-success"></i> <strong>Laporan</strong> - Berbagai laporan dan statistik</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-unstyled">
                                                <li><i class="fas fa-cog text-warning"></i> <strong>Pengaturan</strong> - Konfigurasi sistem dan pengguna</li>
                                                <li><i class="fas fa-book text-secondary"></i> <strong>Bantuan</strong> - Panduan dan dokumentasi</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab Panduan Surat -->
                                <div class="tab-pane fade" id="panduan-surat" role="tabpanel" 
                                     aria-labelledby="tab-panduan-surat">
                                    <h3><i class="fas fa-envelope-open-text text-primary"></i> Pengelolaan Surat</h3>
                                    
                                    <div class="accordion" id="accordionSurat">
                                        <!-- Validasi Surat Masuk -->
                                        <div class="card">
                                            <div class="card-header" id="headingValidasi">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left" type="button" 
                                                            data-toggle="collapse" data-target="#collapseValidasi" 
                                                            aria-expanded="true" aria-controls="collapseValidasi">
                                                        <i class="fas fa-check-circle text-success"></i> 
                                                        <strong>1. Validasi Surat Masuk</strong>
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapseValidasi" class="collapse show" 
                                                 aria-labelledby="headingValidasi" data-parent="#accordionSurat">
                                                <div class="card-body">
                                                    <h5>Cara Memvalidasi Surat Masuk:</h5>
                                                    <ol>
                                                        <li>Buka menu <strong>Surat > Validasi Surat Masuk</strong></li>
                                                        <li>Akan muncul daftar surat yang perlu divalidasi</li>
                                                        <li>Klik pada surat untuk melihat detail</li>
                                                        <li>Periksa kelengkapan data surat (nomor, pengirim, perihal, dll)</li>
                                                        <li>Tekan tombol <span class="badge badge-success">Validasi</span> 
                                                            untuk menyetujui atau <span class="badge badge-danger">Tolak</span> 
                                                            jika ada kesalahan</li>
                                                        <li>Surat yang telah divalidasi akan masuk ke Register Surat Masuk</li>
                                                    </ol>
                                                    <div class="callout callout-warning">
                                                        <h5>Penting!</h5>
                                                        <p>Pastikan semua data sudah benar sebelum memvalidasi. 
                                                           Surat yang sudah divalidasi akan diteruskan ke pejabat terkait.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Surat Masuk -->
                                        <div class="card">
                                            <div class="card-header" id="headingSuratMasuk">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left collapsed" 
                                                            type="button" data-toggle="collapse" 
                                                            data-target="#collapseSuratMasuk" 
                                                            aria-expanded="false" aria-controls="collapseSuratMasuk">
                                                        <i class="fas fa-inbox text-info"></i> 
                                                        <strong>2. Mengelola Surat Masuk</strong>
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapseSuratMasuk" class="collapse" 
                                                 aria-labelledby="headingSuratMasuk" data-parent="#accordionSurat">
                                                <div class="card-body">
                                                    <h5>Cara Menambah Surat Masuk:</h5>
                                                    <ol>
                                                        <li>Buka halaman <strong>Arsip > Arsip Surat Masuk</strong></li>
                                                        <li>Klik tombol <span class="badge badge-primary">
                                                            <i class="fas fa-plus"></i> Tambah Surat</span></li>
                                                        <li>Isi form dengan data:
                                                            <ul>
                                                                <li>Nomor Agenda (otomatis atau manual)</li>
                                                                <li>Nomor Surat</li>
                                                                <li>Pengirim</li>
                                                                <li>Perihal</li>
                                                                <li>Tanggal Surat</li>
                                                                <li>Tanggal Terima</li>
                                                                <li>Upload file surat (PDF)</li>
                                                            </ul>
                                                        </li>
                                                        <li>Klik <strong>Simpan</strong></li>
                                                    </ol>

                                                    <h5 class="mt-3">Cara Melihat Detail Surat:</h5>
                                                    <ol>
                                                        <li>Dari daftar surat, klik pada baris surat yang ingin dilihat</li>
                                                        <li>Modal detail akan muncul dengan 3 tab:
                                                            <ul>
                                                                <li><strong>Data Surat:</strong> Informasi lengkap surat</li>
                                                                <li><strong>Progres Surat:</strong> Status dan tindakan</li>
                                                                <li><strong>Riwayat Disposisi:</strong> Daftar disposisi</li>
                                                            </ul>
                                                        </li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Disposisi -->
                                        <div class="card">
                                            <div class="card-header" id="headingDisposisi">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left collapsed" 
                                                            type="button" data-toggle="collapse" 
                                                            data-target="#collapseDisposisi" 
                                                            aria-expanded="false" aria-controls="collapseDisposisi">
                                                        <i class="fas fa-paper-plane text-warning"></i> 
                                                        <strong>3. Membuat Disposisi</strong>
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapseDisposisi" class="collapse" 
                                                 aria-labelledby="headingDisposisi" data-parent="#accordionSurat">
                                                <div class="card-body">
                                                    <h5>Cara Membuat Disposisi:</h5>
                                                    <ol>
                                                        <li>Buka surat yang akan didisposisi</li>
                                                        <li>Pada tab <strong>Progres Surat</strong>, 
                                                            klik tombol <span class="badge badge-success">Tambah</span></li>
                                                        <li>Pilih jenis pelaksanaan: <strong>Disposisi</strong></li>
                                                        <li>Pilih pejabat/jabatan tujuan disposisi</li>
                                                        <li>Isi catatan/instruksi disposisi</li>
                                                        <li>Klik <strong>Simpan</strong></li>
                                                    </ol>

                                                    <h5 class="mt-3">Mencetak Lembar Disposisi:</h5>
                                                    <ol>
                                                        <li>Buka detail surat</li>
                                                        <li>Pada tab <strong>Riwayat Disposisi</strong></li>
                                                        <li>Klik tombol <span class="badge badge-success">Lembar Disposisi</span></li>
                                                        <li>File PDF akan terbuka di tab baru dan siap dicetak</li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab Panduan Arsip -->
                                <div class="tab-pane fade" id="panduan-arsip" role="tabpanel" 
                                     aria-labelledby="tab-panduan-arsip">
                                    <h3><i class="fas fa-archive text-primary"></i> Pengelolaan Arsip</h3>
                                    
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="fas fa-folder-open"></i> Arsip Surat Masuk
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <p>Menu ini menampilkan semua arsip surat masuk yang telah tersimpan dalam sistem.</p>
                                            <h5>Fitur yang tersedia:</h5>
                                            <ul>
                                                <li><i class="fas fa-search text-info"></i> Pencarian surat berdasarkan nomor, pengirim, atau perihal</li>
                                                <li><i class="fas fa-filter text-info"></i> Filter berdasarkan tanggal</li>
                                                <li><i class="fas fa-eye text-info"></i> Melihat detail dan dokumen surat</li>
                                                <li><i class="fas fa-edit text-warning"></i> Edit data surat (untuk admin/penelaah)</li>
                                                <li><i class="fas fa-trash text-danger"></i> Hapus surat (untuk admin)</li>
                                                <li><i class="fas fa-download text-success"></i> Unduh dokumen surat</li>
                                            </ul>

                                            <div class="callout callout-info">
                                                <h5><i class="icon fas fa-info"></i> Tips:</h5>
                                                Gunakan fitur DataTables untuk sorting, searching, dan export data ke Excel/PDF.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card card-success">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="fas fa-folder"></i> Arsip Surat Keluar
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <p>Menu untuk mengelola arsip surat keluar dari organisasi.</p>
                                            <p class="text-muted"><em>Fitur ini sedang dalam pengembangan.</em></p>
                                        </div>
                                    </div>

                                    <div class="card card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="fas fa-file-archive"></i> Arsip Berkas Digital
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <p>Menu untuk menyimpan dan mengelola berkas digital lainnya.</p>
                                            <p class="text-muted"><em>Fitur ini sedang dalam pengembangan.</em></p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips & Tricks Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-lightbulb"></i> Tips & Trik Penggunaan
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5><i class="fas fa-bolt text-warning"></i> Produktivitas</h5>
                                    <ul>
                                        <li>Gunakan fitur pencarian di tabel untuk menemukan data dengan cepat</li>
                                        <li>Export data ke Excel/PDF untuk laporan external</li>
                                        <li>Manfaatkan filter tanggal untuk analisis periode tertentu</li>
                                        <li>Aktifkan notifikasi untuk tidak melewatkan surat penting</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5><i class="fas fa-eye text-info"></i> Kenyamanan Visual</h5>
                                    <ul>
                                        <li>Gunakan <strong>Dark Mode</strong> untuk penggunaan di malam hari</li>
                                        <li>Icon berwarna membantu identifikasi cepat jenis dokumen</li>
                                        <li>Badge merah menunjukkan jumlah item yang perlu ditindaklanjuti</li>
                                        <li>Hover pada menu untuk melihat tooltip informasi</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card card-warning collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-question-circle"></i> FAQ - Pertanyaan yang Sering Diajukan
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <dl>
                                <dt>Q: Bagaimana cara mengubah nomor agenda otomatis ke manual?</dt>
                                <dd>A: Pada form input surat, centang checkbox "Ubah Nomor Agenda" untuk mengaktifkan input manual.</dd>
                                
                                <dt class="mt-3">Q: Format file apa yang bisa diupload?</dt>
                                <dd>A: Saat ini sistem mendukung file PDF untuk dokumen surat.</dd>
                                
                                <dt class="mt-3">Q: Bagaimana cara mengaktifkan Dark Mode?</dt>
                                <dd>A: Klik tombol floating berbentuk lingkungan di kanan bawah layar. Icon bulan untuk mode gelap, icon matahari untuk kembali ke mode terang. Preferensi akan tersimpan otomatis.</dd>
                                
                                <dt class="mt-3">Q: Bagaimana jika lupa password?</dt>
                                <dd>A: Hubungi administrator sistem atau gunakan fitur reset password di halaman login SSO.</dd>
                                
                                <dt class="mt-3">Q: Apakah bisa menghapus surat yang sudah divalidasi?</dt>
                                <dd>A: Hanya admin yang memiliki hak akses untuk menghapus surat yang sudah divalidasi.</dd>
                                
                                <dt class="mt-3">Q: Bagaimana cara melihat riwayat perubahan surat?</dt>
                                <dd>A: Buka detail surat, lalu pilih tab "Progres Surat" untuk melihat semua riwayat perubahan.</dd>
                                
                                <dt class="mt-3">Q: Apa arti icon dan warna di menu?</dt>
                                <dd>A: Setiap menu memiliki icon unik untuk memudahkan identifikasi. Warna berbeda pada submenu membantu membedakan fungsi (biru untuk surat masuk, hijau untuk dokumen selesai, kuning untuk perlu tindakan, dll).</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kontak Support -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-headset"></i> Bantuan & Dukungan
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>Jika Anda mengalami kendala atau memerlukan bantuan lebih lanjut, silakan hubungi:</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-primary">
                                            <i class="fas fa-user-cog"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Administrator Sistem</span>
                                            <span class="info-box-number">IT Support</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon bg-success">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Email Support</span>
                                            <span class="info-box-number text-sm">support@example.com</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

