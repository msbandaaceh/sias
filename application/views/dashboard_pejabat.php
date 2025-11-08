<!-- Statistics Cards untuk Pejabat -->
<div class="row">
    <!-- Surat Masuk -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-info elevation-1">
                <i class="fas fa-inbox"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Surat Masuk</span>
                <span class="info-box-number"><?= isset($jumlah_surat_masuk) ? $jumlah_surat_masuk : 0 ?></span>
                <div class="progress">
                    <div class="progress-bar bg-info" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    <a href="javascript:;" data-page="surat_masuk" class="text-sm">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </span>
            </div>
        </div>
    </div>

    <!-- Disposisi Pending -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1">
                <i class="fas fa-share-alt"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Disposisi Pending</span>
                <span class="info-box-number"><?= isset($jumlah_disposisi) ? $jumlah_disposisi : 0 ?></span>
                <div class="progress">
                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    <a href="javascript:;" data-page="disposisi" class="text-sm">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </span>
            </div>
        </div>
    </div>

    <!-- Total Arsip -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1">
                <i class="fas fa-archive"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Total Arsip</span>
                <span class="info-box-number"><?= $jumlah_sm ?></span>
                <div class="progress">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    <a href="javascript:;" data-page="arsip_sm" class="text-sm">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions untuk Pejabat -->
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt"></i> Akses Cepat - Pejabat
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card bg-gradient-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-inbox fa-3x mb-2"></i>
                                <h5>Surat Masuk</h5>
                                <p class="mb-2">
                                    <span class="badge badge-light">
                                        <?= isset($jumlah_surat_masuk) ? $jumlah_surat_masuk : 0 ?> Item
                                    </span>
                                </p>
                                <a href="javascript:;" data-page="surat_masuk" class="btn btn-sm btn-light">
                                    <i class="fas fa-arrow-right"></i> Buka
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card bg-gradient-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-share-alt fa-3x mb-2"></i>
                                <h5>Disposisi</h5>
                                <p class="mb-2">
                                    <span class="badge badge-light">
                                        <?= isset($jumlah_disposisi) ? $jumlah_disposisi : 0 ?> Pending
                                    </span>
                                </p>
                                <a href="javascript:;" data-page="disposisi" class="btn btn-sm btn-light">
                                    <i class="fas fa-arrow-right"></i> Buka
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card bg-gradient-success">
                            <div class="card-body text-center">
                                <i class="fas fa-archive fa-3x mb-2"></i>
                                <h5>Arsip</h5>
                                <p class="mb-2 text-sm">Lihat Arsip</p>
                                <a href="javascript:;" data-page="arsip_sm" class="btn btn-sm btn-light">
                                    <i class="fas fa-arrow-right"></i> Buka
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Surat Terbaru -->
    <div class="col-12 col-lg-8">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock"></i> Surat Terbaru
                </h3>
                <div class="card-tools">
                    <a href="javascript:;" data-page="arsip_sm" class="btn btn-tool btn-sm">
                        <i class="fas fa-external-link-alt"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (isset($surat_terbaru) && !empty($surat_terbaru)) { ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Nomor Surat</th>
                                <th>Pengirim</th>
                                <th>Perihal</th>
                                <th>Tanggal</th>
                                <th style="width: 40px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($surat_terbaru as $surat) { ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= isset($surat->no_sm) ? $surat->no_sm : '-' ?></strong></td>
                                <td><?= isset($surat->pengirim) ? $surat->pengirim : '-' ?></td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" title="<?= isset($surat->perihal) ? $surat->perihal : '' ?>">
                                        <?= isset($surat->perihal) ? $surat->perihal : '-' ?>
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        <?= (isset($surat->tgl_terima) && $surat->tgl_terima) ? date('d/m/Y', strtotime($surat->tgl_terima)) : '-' ?>
                                    </small>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" 
                                            onclick="BukaDetilSurat('1', '<?= isset($surat->id) ? $surat->id : '' ?>')" 
                                            title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php } else { ?>
                <div class="text-center p-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada surat terbaru</p>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Sidebar Widgets -->
    <div class="col-12 col-lg-4">
        <!-- Quick Links -->
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-link"></i> Tautan Cepat
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="javascript:;" data-page="surat_masuk" class="nav-link">
                            <i class="fas fa-inbox text-primary"></i> Surat Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:;" data-page="disposisi" class="nav-link">
                            <i class="fas fa-share-alt text-warning"></i> Disposisi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:;" data-page="arsip_sm" class="nav-link">
                            <i class="fas fa-folder-open text-info"></i> Arsip Surat Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:;" data-page="panduan" class="nav-link">
                            <i class="fas fa-book text-success"></i> Panduan Penggunaan
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="card bg-gradient-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-lightbulb"></i> Tips Pejabat
                </h3>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    <i class="fas fa-quote-left"></i> 
                    Sebagai pejabat, segera tindaklanjuti surat masuk dan buat disposisi yang jelas 
                    untuk memastikan kelancaran proses surat.
                    <i class="fas fa-quote-right"></i>
                </p>
            </div>
        </div>
    </div>
</div>

