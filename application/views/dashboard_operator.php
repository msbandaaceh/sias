<!-- Statistics Cards untuk Operator -->
<div class="row">
    <!-- Surat Masuk -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-info elevation-1">
                <i class="fas fa-inbox"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Arsip Surat Masuk</span>
                <span class="info-box-number"><?= $jumlah_sm ?></span>
                <div class="progress">
                    <div class="progress-bar bg-info" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    <a href="javascript:;" data-page="arsip_sm" class="text-sm">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </span>
            </div>
        </div>
    </div>

    <!-- Surat Keluar -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1">
                <i class="fas fa-paper-plane"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Arsip Surat Keluar</span>
                <span class="info-box-number"><?= isset($jumlah_sk) ? $jumlah_sk : 0 ?></span>
                <div class="progress">
                    <div class="progress-bar bg-success" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    <a href="javascript:;" data-page="arsip_sk" class="text-sm">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </span>
            </div>
        </div>
    </div>

    <!-- Arsip Digital -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1">
                <i class="fas fa-hdd"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Arsip Digital</span>
                <span class="info-box-number"><?= isset($jumlah_arsip_digital) ? $jumlah_arsip_digital : 0 ?></span>
                <div class="progress">
                    <div class="progress-bar bg-warning" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                    <a href="javascript:;" data-page="arsip_digital" class="text-sm">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions untuk Operator -->
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt"></i> Akses Cepat - Operator
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card bg-gradient-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-folder-open fa-3x mb-2"></i>
                                <h5>Arsip Surat Masuk</h5>
                                <p class="mb-2">
                                    <span class="badge badge-light">
                                        <?= $jumlah_sm ?> Dokumen
                                    </span>
                                </p>
                                <a href="javascript:;" data-page="arsip_sm" class="btn btn-sm btn-light">
                                    <i class="fas fa-arrow-right"></i> Buka
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card bg-gradient-success">
                            <div class="card-body text-center">
                                <i class="fas fa-folder fa-3x mb-2"></i>
                                <h5>Arsip Surat Keluar</h5>
                                <p class="mb-2">
                                    <span class="badge badge-light">
                                        <?= isset($jumlah_sk) ? $jumlah_sk : 0 ?> Dokumen
                                    </span>
                                </p>
                                <a href="javascript:;" data-page="arsip_sk" class="btn btn-sm btn-light">
                                    <i class="fas fa-arrow-right"></i> Buka
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="card bg-gradient-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-hdd fa-3x mb-2"></i>
                                <h5>Arsip Digital</h5>
                                <p class="mb-2 text-sm">Berkas Digital</p>
                                <a href="javascript:;" data-page="arsip_digital" class="btn btn-sm btn-light">
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
    <!-- Informasi -->
    <div class="col-12 col-lg-8">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i> Informasi untuk Operator
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Akses Terbatas</h5>
                    <p>Sebagai operator, Anda memiliki akses untuk melihat dan mengelola arsip surat. 
                    Untuk mengelola surat aktif (validasi, disposisi), silakan hubungi administrator.</p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="callout callout-success">
                            <h5><i class="fas fa-check-circle"></i> Yang Bisa Anda Lakukan:</h5>
                            <ul>
                                <li>Melihat arsip surat masuk</li>
                                <li>Melihat arsip surat keluar</li>
                                <li>Mengakses arsip digital</li>
                                <li>Mencari dan filter arsip</li>
                                <li>Mengunduh dokumen</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="callout callout-warning">
                            <h5><i class="fas fa-exclamation-triangle"></i> Yang Tidak Bisa Anda Lakukan:</h5>
                            <ul>
                                <li>Validasi surat masuk</li>
                                <li>Membuat disposisi</li>
                                <li>Mengelola pengguna</li>
                                <li>Melihat laporan</li>
                                <li>Mengubah pengaturan sistem</li>
                            </ul>
                        </div>
                    </div>
                </div>
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
                        <a href="javascript:;" data-page="arsip_sm" class="nav-link">
                            <i class="fas fa-folder-open text-primary"></i> Arsip Surat Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:;" data-page="arsip_sk" class="nav-link">
                            <i class="fas fa-folder text-info"></i> Arsip Surat Keluar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:;" data-page="arsip_digital" class="nav-link">
                            <i class="fas fa-hdd text-success"></i> Arsip Digital
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:;" data-page="panduan" class="nav-link">
                            <i class="fas fa-book text-warning"></i> Panduan Penggunaan
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="card bg-gradient-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-lightbulb"></i> Tips Operator
                </h3>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    <i class="fas fa-quote-left"></i> 
                    Gunakan fitur pencarian dan filter untuk menemukan arsip dengan cepat. 
                    Pastikan dokumen tersimpan dengan rapi untuk kemudahan akses di kemudian hari.
                    <i class="fas fa-quote-right"></i>
                </p>
            </div>
        </div>
    </div>
</div>

