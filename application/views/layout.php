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
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="#" data-page="dashboard" class="navbar-brand">
                    <img src="<?= site_url('assets/icon/sias.ico'); ?>" alt="Logo"
                        class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">Surat</span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse"
                    data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="#" data-page="dashboard" class="nav-link">Beranda</a>
                        </li>
                        <?php if (in_array($peran, ['super', '1', '4', '5', '6', '7', '8', '9', '10', '11', '12'])) { ?>
                            <li class="nav-item dropdown">
                                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false" class="nav-link dropdown-toggle">Surat
                                    <span class="right badge badge-danger" id="total"></span>
                                </a>
                                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                    <?php if (in_array($peran, ['super', '4', '5', '10'])) {
                                        ?>
                                        <li>
                                            <a href="#" data-page="validasi_sm" class="dropdown-item">Validasi Surat Masuk
                                                <span class="right badge badge-danger" id="validasi"></span>
                                            </a>
                                        </li>
                                        <?php
                                    } ?>
                                    <li>
                                        <a href="#" data-page="surat_masuk" class="dropdown-item">Surat Masuk
                                            <span class="right badge badge-danger" id="surat_masuk"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-page="surat_keluar" class="dropdown-item">Surat Keluar
                                            <span class="right badge badge-danger" id="surat_keluar"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" data-page="disposisi" class="dropdown-item">Disposisi
                                            <span class="right badge badge-danger" id="disposisi"></span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php } ?>

                        <li class="nav-item dropdown">
                            <a href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                class="nav-link dropdown-toggle">Arsip</a>
                            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                <li><a href="#" data-page="arsip_sm" class="dropdown-item">Arsip Surat Masuk</a></li>
                                <li><a href="#" data-page="arsip_sk" class="dropdown-item">Arsip Surat Keluar</a>
                                </li>
                                <li><a href="#" data-page="arsip_digital" class="dropdown-item">Arsip Berkas
                                        Digital</a></li>
                            </ul>
                        </li>

                        <?php if (in_array($peran, ['super', '4', '5', '10'])) {
                            ?>
                            <li class="nav-item dropdown">
                                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false" class="nav-link dropdown-toggle">Laporan</a>
                                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                    <li><a href="#" data-page="laporan_sm" class="dropdown-item">Surat Masuk</a></li>
                                    <li><a href="#" data-page="laporan_sk" class="dropdown-item">Surat Keluar</a></li>
                                    <li><a href="#" data-page="laporan_disposisi" class="dropdown-item">Disposisi</a></li>
                                    <li><a href="#" data-page="laporan_progres" class="dropdown-item">Progres Surat</a></li>
                                    <li><a href="#" data-page="laporan_arsip" class="dropdown-item">Arsip Digital</a></li>
                                </ul>
                            </li>
                        <?php } ?>

                        <li class="nav-item dropdown">
                            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" class="nav-link dropdown-toggle">Pengaturan</a>
                            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                                <li><button onclick="ModalRole('-1')" class="dropdown-item">Data Pengguna</a></li>
                                <li><a href="<?= base_url() ?>klas" class="dropdown-item">Klasifikasi Surat</a></li>
                                <li><a href="<?= base_url() ?>klas_arsip" class="dropdown-item">Klasifikasi Arsip</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <!-- Notifikasi Surat Masuk -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-page="surat_masuk" title="Notifikasi Surat Masuk">
                            <i class="fas fa-envelope-open"></i>
                            <span class="badge badge-danger navbar-badge" id="surat_masuk_icon"></span>
                        </a>
                    </li>

                    <!-- Notifikasi Surat Keluar -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-page="surat_keluar" title="Notifikasi Surat Keluar">
                            <i class="fas fa-envelope"></i>
                            <span class="badge badge-danger navbar-badge" id="surat_keluar_icon"></span>
                        </a>
                    </li>

                    <!-- Notifikasi Disposisi -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-page="disposisi" title="Notifikasi Disposisi">
                            <i class="fas fa-paper-plane"></i>
                            <span class="badge badge-danger navbar-badge" id="disposisi_icon"></span>
                        </a>
                    </li>

                    <!-- Messages Dropdown Menu -->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link" data-toggle="dropdown">
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
                                <p>
                                    <?= $this->session->userdata('fullname') ?>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <a href="<?= site_url('') ?>"
                                            class="btn btn-outline-success btn-block">Dashboard</a>
                                    </div>
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
            $('[data-page]').on('click', function (e) {
                e.preventDefault();
                let page = $(this).data('page');
                loadPage(page);
            });

            let jabatan = '<?= $peran ?>';
            const peran = ['1', '4', '5', '6', '7', '8', '9', '10', '11', '12'];

            if (peran.includes(jabatan)) {
                getNotifSuratMasuk();
                setInterval(getNotifSuratMasuk, 50000);
            }
        });
    </script>

    <script type="text/javascript">
        var config = {
            result: '<?= $result ?>',
            pesan: '<?= $pesan ?>'
        };
    </script>

    <script src="<?= site_url('assets/js/sias.js'); ?>"></script>
</body>

</html>