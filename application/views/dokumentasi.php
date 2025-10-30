<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dokumentasi Teknis</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="javascript:;" data-page="dashboard">Surat</a></li>
                        <li class="breadcrumb-item active">Dokumentasi</li>
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
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info-circle"></i> Informasi</h5>
                        Halaman ini berisi dokumentasi teknis untuk pengembang dan administrator sistem SIAS.
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-layer-group"></i> Arsitektur Sistem
                            </h3>
                        </div>
                        <div class="card-body">
                            <h5>Teknologi yang Digunakan:</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-server"></i> Backend</h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-unstyled">
                                                <li><i class="fas fa-check text-success"></i> <strong>PHP 7.4+</strong></li>
                                                <li><i class="fas fa-check text-success"></i> <strong>CodeIgniter 3</strong> - Framework MVC</li>
                                                <li><i class="fas fa-check text-success"></i> <strong>MySQL/MariaDB</strong> - Database</li>
                                                <li><i class="fas fa-check text-success"></i> <strong>Apache/Nginx</strong> - Web Server</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-desktop"></i> Frontend</h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-unstyled">
                                                <li><i class="fas fa-check text-success"></i> <strong>AdminLTE 3</strong> - Template Admin</li>
                                                <li><i class="fas fa-check text-success"></i> <strong>jQuery 3</strong> - JavaScript Library</li>
                                                <li><i class="fas fa-check text-success"></i> <strong>Bootstrap 4</strong> - CSS Framework</li>
                                                <li><i class="fas fa-check text-success"></i> <strong>DataTables</strong> - Table Plugin</li>
                                                <li><i class="fas fa-check text-success"></i> <strong>Select2</strong> - Enhanced Select</li>
                                                <li><i class="fas fa-check text-success"></i> <strong>SweetAlert2</strong> - Alert Dialog</li>
                                                <li><i class="fas fa-check text-success"></i> <strong>PDF.js</strong> - PDF Viewer</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4">Struktur Direktori:</h5>
                            <pre class="bg-dark text-light p-3 rounded"><code>sias/
├── application/
│   ├── controllers/      # Controller (HalamanUtama, HalamanSuratMasuk, Api)
│   ├── models/           # Model (ModelSias)
│   ├── views/            # View files (layout, dashboard, surat_masuk, dll)
│   ├── libraries/        # Custom libraries (ApiHelper, TanggalHelper)
│   ├── core/             # MY_Controller (Authentication & Authorization)
│   ├── config/           # Konfigurasi aplikasi
│   └── helpers/          # Helper functions
├── assets/
│   ├── dist/             # AdminLTE assets (CSS, JS, Images)
│   ├── plugins/          # jQuery plugins & dependencies
│   ├── dokumen/          # Upload dokumen surat (PDF)
│   ├── js/               # Custom JavaScript (sias.js)
│   └── pdfjs/            # PDF.js library
├── system/               # CodeIgniter core files
└── index.php             # Entry point</code></pre>
                        </div>
                    </div>

                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-route"></i> Routing & Single Page Application
                            </h3>
                        </div>
                        <div class="card-body">
                            <h5>Single Page Application (SPA) Pattern:</h5>
                            <p>Aplikasi ini menggunakan pendekatan SPA dengan jQuery AJAX untuk navigasi tanpa reload halaman penuh.</p>
                            
                            <div class="alert alert-warning">
                                <i class="icon fas fa-lightbulb"></i>
                                <strong>Konsep SPA:</strong> Halaman utama (layout.php) dimuat sekali, kemudian konten 
                                halaman lainnya dimuat secara dinamis via AJAX ke dalam div <code>#app</code>.
                            </div>

                            <h6>Fungsi Utama JavaScript (sias.js):</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 30%">Fungsi</th>
                                            <th>Deskripsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><code>loadPage(page)</code></td>
                                            <td>Memuat halaman view via AJAX ke div #app</td>
                                        </tr>
                                        <tr>
                                            <td><code>cekToken()</code></td>
                                            <td>Validasi SSO token secara berkala</td>
                                        </tr>
                                        <tr>
                                            <td><code>getNotifSuratMasuk()</code></td>
                                            <td>Mengambil notifikasi surat masuk real-time</td>
                                        </tr>
                                        <tr>
                                            <td><code>ModalInputSurat(id)</code></td>
                                            <td>Menampilkan form input/edit surat dalam modal</td>
                                        </tr>
                                        <tr>
                                            <td><code>BukaDetilSurat(status, id)</code></td>
                                            <td>Membuka detail lengkap surat dengan tabs</td>
                                        </tr>
                                        <tr>
                                            <td><code>TampilProgresSurat()</code></td>
                                            <td>Menampilkan timeline progres surat</td>
                                        </tr>
                                        <tr>
                                            <td><code>TampilRiwayatDisposisi()</code></td>
                                            <td>Menampilkan riwayat disposisi surat</td>
                                        </tr>
                                        <tr>
                                            <td><code>SimpanPelaksanaan()</code></td>
                                            <td>Menyimpan data validasi/pelaksanaan</td>
                                        </tr>
                                        <tr>
                                            <td><code>ModalRole(id)</code></td>
                                            <td>Modal untuk mengelola peran pengguna</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h6 class="mt-4">Route Controller (config/routes.php):</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Route</th>
                                            <th>Method</th>
                                            <th>Controller/Function</th>
                                            <th>Deskripsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><code>/</code></td>
                                            <td>GET</td>
                                            <td>HalamanUtama/index</td>
                                            <td>Load layout utama</td>
                                        </tr>
                                        <tr>
                                            <td><code>halamanutama/page/{halaman}</code></td>
                                            <td>GET</td>
                                            <td>HalamanUtama/page</td>
                                            <td>Load view halaman via AJAX</td>
                                        </tr>
                                        <tr>
                                            <td><code>cek_token</code></td>
                                            <td>POST</td>
                                            <td>HalamanUtama/cek_token_sso</td>
                                            <td>Validasi SSO token</td>
                                        </tr>
                                        <tr>
                                            <td><code>simpan_sm</code></td>
                                            <td>POST</td>
                                            <td>HalamanSuratMasuk/simpan_sm</td>
                                            <td>Simpan/update surat masuk</td>
                                        </tr>
                                        <tr>
                                            <td><code>show_sm</code></td>
                                            <td>POST</td>
                                            <td>HalamanSuratMasuk/show_sm</td>
                                            <td>Tampilkan form edit surat</td>
                                        </tr>
                                        <tr>
                                            <td><code>show_detil_sm</code></td>
                                            <td>POST</td>
                                            <td>HalamanSuratMasuk/show_detil_sm</td>
                                            <td>Tampilkan detail surat</td>
                                        </tr>
                                        <tr>
                                            <td><code>simpan_validasi_surat_masuk</code></td>
                                            <td>POST</td>
                                            <td>HalamanSuratMasuk/simpan_validasi_surat_masuk</td>
                                            <td>Validasi/disposisi surat</td>
                                        </tr>
                                        <tr>
                                            <td><code>simpan_pelaksanaan_surat_masuk</code></td>
                                            <td>POST</td>
                                            <td>HalamanSuratMasuk/simpan_pelaksanaan_surat_masuk</td>
                                            <td>Simpan pelaksanaan surat</td>
                                        </tr>
                                        <tr>
                                            <td><code>get_validasi</code></td>
                                            <td>GET</td>
                                            <td>HalamanSuratMasuk/get_validasi</td>
                                            <td>Ambil data validasi</td>
                                        </tr>
                                        <tr>
                                            <td><code>cetak_disposisi</code></td>
                                            <td>POST</td>
                                            <td>HalamanSuratMasuk/cetak_lembar_disposisi</td>
                                            <td>Cetak lembar disposisi PDF</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-database"></i> Database Schema
                            </h3>
                        </div>
                        <div class="card-body">
                            <h5>Tabel Utama:</h5>
                            
                            <div class="accordion" id="accordionDatabase">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block" data-toggle="collapse" href="#collapsePeran">
                                                <i class="fas fa-table"></i> Tabel: <code>peran</code>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapsePeran" class="collapse" data-parent="#accordionDatabase">
                                        <div class="card-body">
                                            <p>Menyimpan data peran/role pengguna dalam sistem.</p>
                                            <strong>Kolom utama:</strong>
                                            <ul>
                                                <li><code>id</code> - Primary key</li>
                                                <li><code>userid</code> - ID pengguna dari SSO</li>
                                                <li><code>role</code> - Jenis peran (operator, admin, dll)</li>
                                                <li><code>hapus</code> - Status aktif/non-aktif (0/1)</li>
                                                <li><code>created_by, created_on</code> - Audit trail</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block collapsed" data-toggle="collapse" href="#collapseSuratMasuk">
                                                <i class="fas fa-table"></i> Tabel: <code>surat_masuk</code>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseSuratMasuk" class="collapse" data-parent="#accordionDatabase">
                                        <div class="card-body">
                                            <p>Menyimpan data arsip surat masuk.</p>
                                            <strong>Kolom utama:</strong>
                                            <ul>
                                                <li><code>id</code> - Primary key</li>
                                                <li><code>no_agenda</code> - Nomor agenda surat</li>
                                                <li><code>no_sm</code> - Nomor surat masuk</li>
                                                <li><code>pengirim</code> - Pengirim surat</li>
                                                <li><code>perihal</code> - Perihal surat</li>
                                                <li><code>tgl_surat, tgl_terima</code> - Tanggal surat & terima</li>
                                                <li><code>file</code> - Path file dokumen PDF</li>
                                                <li><code>ket</code> - Keterangan tambahan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block collapsed" data-toggle="collapse" href="#collapseRegister">
                                                <i class="fas fa-table"></i> Tabel: <code>register_surat_masuk</code>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseRegister" class="collapse" data-parent="#accordionDatabase">
                                        <div class="card-body">
                                            <p>Menyimpan data register/validasi surat masuk yang perlu ditindaklanjuti.</p>
                                            <strong>Kolom utama:</strong>
                                            <ul>
                                                <li><code>id</code> - Primary key</li>
                                                <li><code>surat_id</code> - Foreign key ke surat_masuk</li>
                                                <li><code>jab_id</code> - Jabatan penerima</li>
                                                <li><code>status</code> - Status surat (1:Diteruskan, 2:Disposisi, 3:Dilaksanakan, 4:Selesai)</li>
                                                <li><code>ket</code> - Keterangan/catatan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block collapsed" data-toggle="collapse" href="#collapseDisposisi">
                                                <i class="fas fa-table"></i> Tabel: <code>disposisi</code>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseDisposisi" class="collapse" data-parent="#accordionDatabase">
                                        <div class="card-body">
                                            <p>Menyimpan data disposisi surat.</p>
                                            <strong>Kolom utama:</strong>
                                            <ul>
                                                <li><code>id</code> - Primary key</li>
                                                <li><code>register_id</code> - Foreign key ke register_surat_masuk</li>
                                                <li><code>jab_tujuan</code> - Jabatan tujuan disposisi</li>
                                                <li><code>catatan</code> - Catatan/instruksi disposisi</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title w-100">
                                            <a class="d-block collapsed" data-toggle="collapse" href="#collapseProgres">
                                                <i class="fas fa-table"></i> Tabel: <code>progres_surat</code>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseProgres" class="collapse" data-parent="#accordionDatabase">
                                        <div class="card-body">
                                            <p>Menyimpan riwayat progres/timeline surat.</p>
                                            <strong>Kolom utama:</strong>
                                            <ul>
                                                <li><code>id</code> - Primary key</li>
                                                <li><code>register_id</code> - Foreign key ke register_surat_masuk</li>
                                                <li><code>status</code> - Status progres</li>
                                                <li><code>jab_tujuan</code> - Jabatan tujuan (jika ada)</li>
                                                <li><code>ket</code> - Keterangan</li>
                                                <li><code>created_by, created_on</code> - Siapa & kapan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="callout callout-warning mt-3">
                                <h5><i class="icon fas fa-exclamation-triangle"></i> Catatan Penting:</h5>
                                <p>Untuk melihat schema lengkap dengan relasi antar tabel, gunakan tools seperti 
                                   phpMyAdmin, MySQL Workbench, atau lihat file migration database.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-shield-alt"></i> Authentication & Authorization
                            </h3>
                        </div>
                        <div class="card-body">
                            <h5>MY_Controller - Base Controller:</h5>
                            <p>Semua controller extend dari <code>MY_Controller</code> yang menangani autentikasi dan autorisasi.</p>

                            <h6>Proses Authentication:</h6>
                            <ol>
                                <li>Cek session <code>logged_in</code></li>
                                <li>Jika tidak ada, cek cookie <code>sso_token</code></li>
                                <li>Validasi token ke SSO server</li>
                                <li>Jika valid, set session dan ambil data user dari API</li>
                                <li>Jika tidak valid, redirect ke halaman login SSO</li>
                            </ol>

                            <h6 class="mt-3">Penentuan Peran (Role):</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Kondisi</th>
                                            <th>Peran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>role = 'super' atau 'admin_satker' atau jab_id = '4'</td>
                                            <td><span class="badge badge-danger">admin</span></td>
                                        </tr>
                                        <tr>
                                            <td>role = 'validator_uk_satker'</td>
                                            <td><span class="badge badge-primary">penelaah</span></td>
                                        </tr>
                                        <tr>
                                            <td>jab_id in ['1', '6', '7', '8', '9', '11', '12']</td>
                                            <td><span class="badge badge-warning">pejabat</span></td>
                                        </tr>
                                        <tr>
                                            <td>Ada di tabel peran dengan role = 'operator'</td>
                                            <td><span class="badge badge-info">operator</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h6 class="mt-3">Pembatasan Akses di View:</h6>
                            <pre class="bg-light p-3 rounded"><code>&lt;?php if (in_array($peran, ['admin', 'penelaah'])) { ?&gt;
    &lt;!-- Konten hanya untuk admin dan penelaah --&gt;
&lt;?php } ?&gt;</code></pre>
                        </div>
                    </div>

                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-plug"></i> API & Integration
                            </h3>
                        </div>
                        <div class="card-body">
                            <h5>Single Sign-On (SSO) Integration:</h5>
                            <p>Aplikasi terintegrasi dengan sistem SSO untuk autentikasi terpusat.</p>

                            <h6>Konfigurasi SSO (config/config.php):</h6>
                            <ul>
                                <li><code>$config['sso_server']</code> - URL SSO server</li>
                                <li><code>$config['cookie_domain']</code> - Domain untuk cookie SSO</li>
                                <li><code>$config['jwt_key']</code> - Key untuk JWT token</li>
                            </ul>

                            <h6 class="mt-3">API Helper Library:</h6>
                            <p>Library <code>ApiHelper.php</code> digunakan untuk komunikasi dengan API eksternal (SSO, Data Pegawai, dll).</p>

                            <pre class="bg-light p-3 rounded"><code>// Contoh penggunaan ApiHelper
$params = [
    'tabel' => 'v_users',
    'kolom_seleksi' => 'userid',
    'seleksi' => $userid
];

$result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

if ($result['status_code'] === 200) {
    $data = $result['response']['data'];
}</code></pre>

                            <h6 class="mt-3">Token Validation:</h6>
                            <p>Token SSO divalidasi secara berkala setiap 50 detik via JavaScript:</p>
                            <pre class="bg-light p-3 rounded"><code>// Di layout.php
setInterval(function() {
    $.ajax({
        url: 'cek_token',
        type: 'POST',
        success: function(res) {
            if (!res.valid) {
                alert(res.message);
                window.location.href = res.url;
            }
        }
    });
}, 50000); // 50 detik</code></pre>
                        </div>
                    </div>

                    <div class="card card-purple card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-palette"></i> UI/UX Features
                            </h3>
                        </div>
                        <div class="card-body">
                            <h5>Dark Mode Implementation:</h5>
                            <p>Aplikasi dilengkapi dengan fitur dark mode toggle yang tersimpan di localStorage browser.</p>

                            <h6>Komponen Dark Mode:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>HTML (layout.php):</strong>
                                    <pre class="bg-light p-2 rounded mt-2"><code>&lt;button class="theme-toggle-btn" id="themeToggle"&gt;
    &lt;i class="fas fa-moon" id="themeIcon"&gt;&lt;/i&gt;
    &lt;span class="tooltip-text"&gt;Mode Gelap&lt;/span&gt;
&lt;/button&gt;</code></pre>
                                </div>
                                <div class="col-md-6">
                                    <strong>CSS Styling:</strong>
                                    <ul>
                                        <li>Floating button dengan gradient purple</li>
                                        <li>Smooth transition untuk perubahan tema</li>
                                        <li>Dark mode class: <code>.dark-mode</code></li>
                                        <li>Warna background: #1a1a1a</li>
                                    </ul>
                                </div>
                            </div>

                            <h6 class="mt-3">JavaScript Logic:</h6>
                            <pre class="bg-light p-2 rounded"><code>// Simpan preferensi
localStorage.setItem('theme', 'dark');

// Load preferensi saat init
const currentTheme = localStorage.getItem('theme') || 'light';
if (currentTheme === 'dark') {
    body.addClass('dark-mode');
}</code></pre>

                            <h5 class="mt-4">Icon System:</h5>
                            <p>Semua menu dilengkapi dengan icon FontAwesome untuk meningkatkan UX:</p>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Menu</th>
                                            <th>Icon</th>
                                            <th>Class</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Surat</td>
                                            <td><i class="fas fa-envelope-open-text"></i></td>
                                            <td><code>fa-envelope-open-text</code></td>
                                        </tr>
                                        <tr>
                                            <td>Surat Masuk</td>
                                            <td><i class="fas fa-inbox text-primary"></i></td>
                                            <td><code>fa-inbox text-primary</code></td>
                                        </tr>
                                        <tr>
                                            <td>Surat Keluar</td>
                                            <td><i class="fas fa-paper-plane text-info"></i></td>
                                            <td><code>fa-paper-plane text-info</code></td>
                                        </tr>
                                        <tr>
                                            <td>Disposisi</td>
                                            <td><i class="fas fa-share-alt text-warning"></i></td>
                                            <td><code>fa-share-alt text-warning</code></td>
                                        </tr>
                                        <tr>
                                            <td>Arsip</td>
                                            <td><i class="fas fa-archive"></i></td>
                                            <td><code>fa-archive</code></td>
                                        </tr>
                                        <tr>
                                            <td>Laporan</td>
                                            <td><i class="fas fa-chart-bar"></i></td>
                                            <td><code>fa-chart-bar</code></td>
                                        </tr>
                                        <tr>
                                            <td>Pengaturan</td>
                                            <td><i class="fas fa-cog"></i></td>
                                            <td><code>fa-cog</code></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="callout callout-success">
                                <h5><i class="icon fas fa-paint-brush"></i> Design Consistency:</h5>
                                <ul class="mb-0">
                                    <li>Semua dropdown item memiliki icon dengan width tetap (20px)</li>
                                    <li>Margin konsisten (8px) antara icon dan text</li>
                                    <li>Hover effect scale(1.1) pada icon untuk interaksi visual</li>
                                    <li>Color coding: biru (primary), hijau (success), kuning (warning), merah (danger)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card card-danger card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-code-branch"></i> Development Guide
                            </h3>
                        </div>
                        <div class="card-body">
                            <h5>Best Practices:</h5>
                            <div class="alert alert-info">
                                <i class="icon fas fa-lightbulb"></i>
                                <strong>Tip:</strong> Ikuti pola MVC yang sudah ada dan manfaatkan library yang tersedia.
                            </div>

                            <h6>Menambah Halaman Baru:</h6>
                            <ol>
                                <li>Buat file view di <code>application/views/</code></li>
                                <li>Tambahkan nama view ke array <code>$allowed</code> di <code>HalamanUtama::page()</code></li>
                                <li>Tambahkan menu di <code>layout.php</code> dengan atribut <code>data-page="nama_halaman"</code></li>
                                <li>Tambahkan icon yang sesuai menggunakan FontAwesome</li>
                                <li>Jika perlu logic khusus, tambahkan kondisi di method <code>page()</code></li>
                            </ol>

                            <h6 class="mt-3">Menambah API Endpoint:</h6>
                            <ol>
                                <li>Buat method di controller (misal: <code>HalamanSuratMasuk</code>)</li>
                                <li>Tambahkan route di <code>config/routes.php</code></li>
                                <li>Buat fungsi JavaScript di <code>sias.js</code> untuk memanggil endpoint</li>
                                <li>Gunakan toastr untuk feedback ke user</li>
                            </ol>

                            <h6 class="mt-3">Menambah Menu dengan Icon:</h6>
                            <pre class="bg-light p-2 rounded"><code>&lt;li&gt;
    &lt;a href="javascript:;" data-page="nama_page" class="dropdown-item"&gt;
        &lt;i class="fas fa-icon-name text-color"&gt;&lt;/i&gt; Label Menu
    &lt;/a&gt;
&lt;/li&gt;</code></pre>

                            <h6 class="mt-3">Error Handling:</h6>
                            <ul>
                                <li>Gunakan <code>toastr</code> untuk notifikasi user-friendly</li>
                                <li>Gunakan <code>SweetAlert2</code> untuk konfirmasi aksi penting</li>
                                <li>Log error di <code>application/logs/</code> untuk debugging</li>
                            </ul>

                            <h6 class="mt-3">Security Checklist:</h6>
                            <ul>
                                <li><i class="fas fa-check text-success"></i> Validasi input di controller dengan <code>form_validation</code></li>
                                <li><i class="fas fa-check text-success"></i> Escape output di view dengan <code>htmlspecialchars()</code></li>
                                <li><i class="fas fa-check text-success"></i> Gunakan prepared statements untuk query database</li>
                                <li><i class="fas fa-check text-success"></i> Cek autorisasi sebelum akses data sensitif</li>
                                <li><i class="fas fa-check text-success"></i> Validasi file upload (type, size, extension)</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Contact Info -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-code"></i> Developer Support
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>Untuk pertanyaan teknis atau dukungan development, hubungi tim IT atau developer:</p>
                            <div class="callout callout-info">
                                <h5><i class="icon fas fa-info"></i> Repository & Version Control</h5>
                                <p>Pastikan selalu melakukan commit dengan pesan yang jelas dan dokumentasikan setiap perubahan besar.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

