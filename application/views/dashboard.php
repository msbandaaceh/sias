<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-home"></i> Beranda</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="javascript:;" data-page="dashboard">SIAS</a></li>
                        <li class="breadcrumb-item active">Beranda</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <!-- Welcome Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="mb-1">
                                        <i class="fas fa-hand-sparkles text-warning"></i> 
                                        Selamat Datang, <?= $this->session->userdata('fullname') ?>
                                    </h5>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-calendar-alt"></i> 
                                        <?= date('l, d F Y') ?> - 
                                        <span id="current-time"></span>
                                    </p>
                                    <p class="mt-2 mb-0">
                                        <small class="text-muted">
                                            <i class="fas fa-user-tag"></i> 
                                            <?= $this->session->userdata('jabatan') ?> | 
                                            <i class="fas fa-shield-alt"></i> 
                                            Peran: <strong><?= ucfirst($peran) ?></strong>
                                        </small>
                                    </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="info-box bg-gradient-primary">
                                        <span class="info-box-icon"><i class="fas fa-file-alt"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Arsip</span>
                                            <span class="info-box-number"><?= $jumlah_sm ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($peran == 'admin') { ?>
                <!-- DASHBOARD ADMIN -->
                <?php include 'dashboard_admin.php'; ?>
            <?php } elseif ($peran == 'penelaah') { ?>
                <!-- DASHBOARD PENELAAH -->
                <?php include 'dashboard_penelaah.php'; ?>
            <?php } elseif ($peran == 'pejabat') { ?>
                <!-- DASHBOARD PEJABAT -->
                <?php include 'dashboard_pejabat.php'; ?>
            <?php } else { ?>
                <!-- DASHBOARD OPERATOR / USER LAINNYA -->
                <?php include 'dashboard_operator.php'; ?>
            <?php } ?>

        </div>
    </section>
</div>