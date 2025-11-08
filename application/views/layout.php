<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->session->userdata('nama_client_app') ?> | <?= $this->session->userdata('deskripsi_client_app') ?>
    </title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= site_url('assets/icon/sias.ico'); ?>" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= site_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= site_url('assets/dist/css/adminlte.min.css') ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= site_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet"
        href="<?= site_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= site_url('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
    <!-- daterange picker -->
    <link rel="stylesheet" href="<?= site_url('assets/plugins/daterangepicker/daterangepicker.css') ?>">
    <!-- Toastr -->
    <link rel="stylesheet" href="<?= site_url('assets/plugins/toastr/toastr.min.css') ?>">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="<?= site_url('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
    <link rel="stylesheet" href="<?= site_url('assets/plugins/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= site_url('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">

    <!-- Custom CSS for Dark Mode Toggle -->
    <style>
        .theme-toggle-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            z-index: 1000;
            color: white;
            font-size: 24px;
        }

        .theme-toggle-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .theme-toggle-btn:active {
            transform: scale(0.95);
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }

        body.dark-mode .main-header {
            background-color: #2d2d2d !important;
            border-bottom: 1px solid #404040;
        }

        body.dark-mode .navbar-light {
            background-color: #2d2d2d !important;
        }

        body.dark-mode .nav-link {
            color: #e0e0e0 !important;
        }

        body.dark-mode .nav-link:hover {
            color: #fff !important;
        }

        body.dark-mode .dropdown-menu {
            background-color: #2d2d2d;
            border: 1px solid #404040;
        }

        body.dark-mode .dropdown-item {
            color: #e0e0e0;
        }

        body.dark-mode .dropdown-item:hover {
            background-color: #404040;
            color: #fff;
        }

        body.dark-mode .content-wrapper {
            background-color: #1a1a1a;
        }

        body.dark-mode .card {
            background-color: #2d2d2d;
            color: #e0e0e0;
            border: 1px solid #404040;
        }

        body.dark-mode .card-header {
            background-color: #242424;
            border-bottom: 1px solid #404040;
        }

        body.dark-mode .modal-content {
            background-color: #2d2d2d;
            color: #e0e0e0;
        }

        body.dark-mode .modal-header {
            background-color: #242424;
            border-bottom: 1px solid #404040;
        }

        body.dark-mode .modal-footer {
            border-top: 1px solid #404040;
        }

        body.dark-mode .form-control {
            background-color: #1a1a1a;
            color: #e0e0e0;
            border: 1px solid #404040;
        }

        body.dark-mode .form-control:focus {
            background-color: #242424;
            color: #e0e0e0;
            border-color: #667eea;
        }

        body.dark-mode .table {
            color: #e0e0e0;
        }

        body.dark-mode .table thead th {
            border-bottom: 2px solid #404040;
        }

        body.dark-mode .table td,
        body.dark-mode .table th {
            border-top: 1px solid #404040;
        }

        body.dark-mode .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        body.dark-mode .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.075);
        }

        body.dark-mode .breadcrumb {
            background-color: #2d2d2d;
        }

        body.dark-mode .breadcrumb-item.active {
            color: #9e9e9e;
        }

        body.dark-mode .info-box {
            background-color: #2d2d2d;
            color: #e0e0e0;
        }

        body.dark-mode .callout {
            border-left-color: #667eea;
        }

        body.dark-mode .callout.callout-info {
            background-color: #2d2d2d;
            border-left-color: #17a2b8;
        }

        body.dark-mode .callout.callout-warning {
            background-color: #2d2d2d;
            border-left-color: #ffc107;
        }

        body.dark-mode .callout.callout-danger {
            background-color: #2d2d2d;
            border-left-color: #dc3545;
        }

        body.dark-mode .alert {
            background-color: #2d2d2d;
            border: 1px solid #404040;
        }

        body.dark-mode .select2-container--bootstrap4 .select2-selection {
            background-color: #1a1a1a;
            border-color: #404040;
            color: #e0e0e0;
        }

        body.dark-mode .select2-container--bootstrap4.select2-container--focus .select2-selection {
            border-color: #667eea;
        }

        body.dark-mode .select2-dropdown {
            background-color: #2d2d2d;
            border-color: #404040;
        }

        body.dark-mode .select2-results__option {
            color: #e0e0e0;
        }

        body.dark-mode .select2-results__option--highlighted {
            background-color: #667eea;
        }

        body.dark-mode .user-header {
            background-color: #667eea !important;
        }

        /* Smooth transition for theme change */
        body,
        .main-header,
        .nav-link,
        .dropdown-menu,
        .content-wrapper,
        .card,
        .form-control,
        .modal-content {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        /* Tooltip for button */
        .theme-toggle-btn .tooltip-text {
            visibility: hidden;
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 5px 10px;
            border-radius: 6px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
            white-space: nowrap;
        }

        .theme-toggle-btn:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* Menu Icon Styling */
        .dropdown-item i {
            width: 20px;
            margin-right: 8px;
            text-align: center;
        }

        .nav-link i {
            margin-right: 5px;
        }

        /* Dropdown item hover effect */
        .dropdown-item:hover i {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }

        /* Badge alignment fix */
        .dropdown-item .badge {
            margin-left: 5px;
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="javascript:;" data-page="dashboard" class="navbar-brand">
                    <img src="<?= site_url('assets/icon/sias.ico'); ?>" alt="Logo"
                        class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light"></span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse"
                    data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">

                        <?php if (in_array($peran, ['admin', 'penelaah', 'pejabat'])) { ?>
                            <li class="nav-item dropdown">
                                <a id="dropdownSubMenu1" href="javascript:;" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false" class="nav-link dropdown-toggle">
                                    <i class="fas fa-envelope-open-text"></i> Surat
                                    <span class="right badge badge-danger" id="total"></span>
                                </a>
                                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                    <?php if (in_array($peran, ['admin', 'penelaah'])) {
                                        ?>
                                        <li>
                                            <a href="javascript:;" data-page="validasi_sm" class="dropdown-item">
                                                <i class="fas fa-check-circle text-success"></i> Validasi Surat Masuk
                                                <span class="right badge badge-danger" id="validasi"></span>
                                            </a>
                                        </li>
                                        <?php
                                    } ?>
                                    <li>
                                        <a href="javascript:;" data-page="surat_masuk" class="dropdown-item">
                                            <i class="fas fa-inbox text-primary"></i> Surat Masuk
                                            <span class="right badge badge-danger" id="surat_masuk"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" data-page="surat_keluar" class="dropdown-item">
                                            <i class="fas fa-paper-plane text-info"></i> Surat Keluar
                                            <span class="right badge badge-danger" id="surat_keluar"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" data-page="disposisi" class="dropdown-item">
                                            <i class="fas fa-share-alt text-warning"></i> Disposisi
                                            <span class="right badge badge-danger" id="disposisi"></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>

                        <li class="nav-item dropdown">
                            <a href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                class="nav-link dropdown-toggle">
                                <i class="fas fa-archive"></i> Arsip
                            </a>
                            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                <li>
                                    <a href="javascript:;" data-page="arsip_sm" class="dropdown-item">
                                        <i class="fas fa-folder-open text-primary"></i> Arsip Surat Masuk
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-page="arsip_sk" class="dropdown-item">
                                        <i class="fas fa-folder text-info"></i> Arsip Surat Keluar
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" data-page="arsip_digital" class="dropdown-item">
                                        <i class="fas fa-hdd text-success"></i> Arsip Berkas Digital
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <?php if (in_array($peran, ['admin', 'penelaah'])) {
                            ?>
                            <li class="nav-item dropdown">
                                <a id="dropdownSubMenu1" href="javascript:;" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false" class="nav-link dropdown-toggle">
                                    <i class="fas fa-chart-bar"></i> Laporan
                                </a>
                                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                    <li>
                                        <a href="javascript:;" data-page="laporan_sm" class="dropdown-item">
                                            <i class="fas fa-file-import text-primary"></i> Surat Masuk
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" data-page="laporan_sk" class="dropdown-item">
                                            <i class="fas fa-file-export text-info"></i> Surat Keluar
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" data-page="laporan_disposisi" class="dropdown-item">
                                            <i class="fas fa-tasks text-warning"></i> Disposisi
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" data-page="laporan_progres" class="dropdown-item">
                                            <i class="fas fa-stream text-success"></i> Progres Surat
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" data-page="laporan_arsip" class="dropdown-item">
                                            <i class="fas fa-database text-secondary"></i> Arsip Digital
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false" class="nav-link dropdown-toggle">
                                    <i class="fas fa-cog"></i> Pengaturan
                                </a>
                                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                    <li>
                                        <button onclick="ModalRole('-1')" class="dropdown-item">
                                            <i class="fas fa-users text-primary"></i> Data Pengguna
                                        </button>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>klas" class="dropdown-item">
                                            <i class="fas fa-tags text-warning"></i> Klasifikasi Surat
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>klas_arsip" class="dropdown-item">
                                            <i class="fas fa-layer-group text-info"></i> Klasifikasi Arsip
                                        </a>
                                    </li>
                                </ul>
                            </li>

                        <?php } ?>

                        <?php if (in_array($peran, ['admin', 'penelaah', 'operator', 'pejabat'])) { ?>
                            <li class="nav-item dropdown">
                                <a href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    class="nav-link dropdown-toggle"><i class="fas fa-book"></i> Bantuan</a>
                                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                    <li><a href="javascript:;" data-page="panduan" class="dropdown-item">
                                            <i class="fas fa-question-circle"></i> Panduan Penggunaan</a>
                                    </li>
                                    <?php if ($peran == 'admin') { ?>
                                        <li><a href="javascript:;" data-page="dokumentasi" class="dropdown-item">
                                                <i class="fas fa-code"></i> Dokumentasi Teknis</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>

                    </ul>
                </div>

                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <?php if (in_array($peran, ['admin', 'penelaah'])) {
                        ?>
                        <!-- Notifikasi Surat Masuk -->
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:;" data-page="surat_masuk" title="Notifikasi Surat Masuk">
                                <i class="fas fa-inbox"></i>
                                <span class="badge badge-danger navbar-badge" id="surat_masuk_icon"></span>
                            </a>
                        </li>

                        <!-- Notifikasi Surat Keluar -->
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:;" data-page="surat_keluar"
                                title="Notifikasi Surat Keluar">
                                <i class="fas fa-paper-plane"></i>
                                <span class="badge badge-danger navbar-badge" id="surat_keluar_icon"></span>
                            </a>
                        </li>

                        <!-- Notifikasi Disposisi -->
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:;" data-page="disposisi" title="Notifikasi Disposisi">
                                <i class="fas fa-share-alt"></i>
                                <span class="badge badge-danger navbar-badge" id="disposisi_icon"></span>
                            </a>
                        </li>
                    <?php } ?>

                    <!-- Messages Dropdown Menu -->
                    <li class="nav-item dropdown user-menu">
                        <a href="javascript:;" class="nav-link" data-toggle="dropdown">
                            <div class="image">
                                <img src="<?= $this->session->userdata('foto'); ?>"
                                    class="user-image img-circle elevation-2" alt="User Image">
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="<?= $this->session->userdata('foto'); ?>" class="img-circle elevation-2"
                                    alt="User Image">
                                <p class="profile-username text-center"><?= $this->session->userdata('fullname') ?></p>
                                <p class="text-muted text-center"><?= $this->session->userdata('jabatan') ?></p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <a href="<?= site_url('keluar') ?>"
                                            class="btn btn-outline-success btn-block">Keluar</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /.navbar -->

        <div id="app">Memuat...</div>

        <div class="modal fade" id="role-pegawai" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="card card-default">
                    <div class="modal-content">
                        <div class="overlay" id="overlay">
                            <i class="fas fa-2x fa-sync fa-spin"></i>
                        </div>
                        <div class="modal-header">
                            <h5 class="modal-title" id="judul">Daftar Petugas</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" id="formPeran">
                            <input type="hidden" id="id" name="id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <h5 class="form-label">Pilih Pegawai : </h5>
                                    <div id="pegawai_">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <h5 class="form-label">Pilih Peran : </h5>
                                    <div id="peran_"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="row justify-content-end">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                        <div class="modal-body" id="tabel-role"></div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- Floating Dark Mode Toggle Button -->
        <button class="theme-toggle-btn" id="themeToggle" title="Toggle Dark Mode">
            <i class="fas fa-moon" id="themeIcon"></i>
            <span class="tooltip-text">Mode Gelap</span>
        </button>
    </div>

    <script src="<?= site_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= site_url('assets/dist/js/adminlte.min.js') ?>"></script>

    <script src="<?= site_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/jszip/jszip.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/pdfmake/pdfmake.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/pdfmake/vfs_fonts.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/daterangepicker/daterangepicker.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/moment/moment.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/inputmask/jquery.inputmask.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/toastr/toastr.min.js') ?>"></script>
    <script
        src="<?= site_url('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/select2/js/select2.min.js') ?>"></script>

    <?php
    if ($this->session->flashdata('info')) {
        $result = $this->session->flashdata('info');
        if ($result == '1') {
            $pesan = $this->session->flashdata('pesan_sukses');
        } elseif ($result == '2') {
            $pesan = $this->session->flashdata('pesan_gagal');
        } else {
            $pesan = $this->session->flashdata('pesan_gagal');
        }
    } else {
        $result = "-1";
        $pesan = "";
    }
    ?>

    <script>
        $(document).ready(function () {
            // Load page
            loadPage('dashboard');

            // Navigasi SPA
            $(document).on('click', '[data-page]', function (e) {
                e.preventDefault();
                let page = $(this).data('page');
                loadPage(page);
            });

            let jabatan = '<?= $this->session->userdata('jab_id') ?>';
            const peran = ['1', '4', '5', '6', '7', '8', '9', '10', '11', '12'];

            if (peran.includes(jabatan)) {
                getNotifSuratMasuk();
                setInterval(getNotifSuratMasuk, 50000);
            }

            // Dark Mode Toggle
            const themeToggle = $('#themeToggle');
            const themeIcon = $('#themeIcon');
            const body = $('body');

            // Check for saved theme preference or default to light mode
            const currentTheme = localStorage.getItem('theme') || 'light';
            if (currentTheme === 'dark') {
                body.addClass('dark-mode');
                themeIcon.removeClass('fa-moon').addClass('fa-sun');
                themeToggle.find('.tooltip-text').text('Mode Terang');
            }

            // Toggle theme on button click
            themeToggle.on('click', function () {
                body.toggleClass('dark-mode');

                if (body.hasClass('dark-mode')) {
                    // Switch to dark mode
                    themeIcon.removeClass('fa-moon').addClass('fa-sun');
                    themeToggle.find('.tooltip-text').text('Mode Terang');
                    localStorage.setItem('theme', 'dark');

                    // Show notification
                    toastr.info('Mode gelap diaktifkan', '', {
                        timeOut: 2000,
                        closeButton: false,
                        progressBar: false
                    });
                } else {
                    // Switch to light mode
                    themeIcon.removeClass('fa-sun').addClass('fa-moon');
                    themeToggle.find('.tooltip-text').text('Mode Gelap');
                    localStorage.setItem('theme', 'light');

                    // Show notification
                    toastr.info('Mode terang diaktifkan', '', {
                        timeOut: 2000,
                        closeButton: false,
                        progressBar: false
                    });
                }
            });
        });
    </script>

    <script type="text/javascript">
        var config = {
            result: '<?= $result ?>',
            pesan: '<?= $pesan ?>',
            halaman: '<?= $page ?>'
        };
    </script>

    <script src="<?= site_url('assets/js/sias.js?v=1.0.5'); ?>"></script>
</body>

</html>