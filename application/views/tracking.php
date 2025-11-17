<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracking Surat Masuk - SIAS</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= site_url('assets/icon/sias.ico'); ?>" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= site_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="<?= site_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="<?= site_url('assets/dist/css/adminlte.min.css') ?>">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        .tracking-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .tracking-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .tracking-body {
            padding: 30px;
        }
        .status-badge {
            font-size: 14px;
            padding: 8px 16px;
            border-radius: 20px;
        }
        .badge-lg {
            font-size: 18px;
            padding: 12px 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="tracking-container">
                    <!-- Header -->
                    <div class="tracking-header">
                        <h2><i class="fas fa-search-location"></i> Tracking Surat Masuk</h2>
                        <p class="mb-0">Lacak status surat masuk Anda dengan mudah</p>
                    </div>

                    <!-- Body -->
                    <div class="tracking-body">
                        <!-- Form Input Tracking Code -->
                        <div class="card card-outline card-primary mb-4">
                            <div class="card-body">
                                <form id="formTracking" method="GET">
                                    <div class="input-group input-group-lg">
                                        <input type="text" 
                                               class="form-control" 
                                               name="code" 
                                               id="trackingCode" 
                                               placeholder="Masukkan Tracking Code (Contoh: SIAS-20250115-A3B2C1)"
                                               value="<?= isset($tracking_code) ? htmlspecialchars($tracking_code) : '' ?>"
                                               required>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search"></i> Lacak
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted mt-2">
                                        <i class="fas fa-info-circle"></i> 
                                        Format: SIAS-YYYYMMDD-XXXXXX (contoh: SIAS-20250115-A3B2C1)
                                    </small>
                                </form>
                            </div>
                        </div>

                        <!-- Error Message -->
                        <?php if (isset($error)) { ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
                        </div>
                        <?php } ?>

                        <!-- Result -->
                        <?php if (isset($result) && $result) { ?>
                        <div class="card card-outline card-success">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-file-alt"></i> Informasi Surat
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong><i class="fas fa-barcode"></i> Tracking Code:</strong><br>
                                        <span class="badge badge-primary badge-lg" style="font-size: 16px; padding: 8px 12px;">
                                            <?= $result->tracking_code ?>
                                        </span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <strong>Status:</strong><br>
                                        <?php
                                        $status_class = 'secondary';
                                        $status_text = 'Unknown';
                                        
                                        if ($result->valid == 0) {
                                            $status_class = 'warning';
                                            $status_text = 'Menunggu Validasi Oleh Penelaah';
                                        } elseif ($result->valid == 1) {
                                            $status_class = 'warning';
                                            $status_text = 'Menunggu Validasi Oleh Eselon III';
                                        } elseif ($result->valid == 2) {
                                            if ($result->status == 0) {
                                                $status_class = 'info';
                                                $status_text = 'Sudah Divalidasi';
                                            } elseif ($result->status == 1) {
                                                $status_class = 'primary';
                                                $status_text = 'Sedang Diproses';
                                            } elseif ($result->status == 2) {
                                                $status_class = 'warning';
                                                $status_text = 'Disposisi';
                                            } elseif ($result->status == 3) {
                                                $status_class = 'info';
                                                $status_text = 'Dilaksanakan';
                                            } elseif ($result->status == 4) {
                                                $status_class = 'success';
                                                $status_text = 'Selesai';
                                            }
                                        }
                                        ?>
                                        <span class="badge badge-<?= $status_class ?> status-badge">
                                            <?= $status_text ?>
                                        </span>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <dl>
                                            <dt><i class="fas fa-envelope text-primary"></i> Nomor Surat:</dt>
                                            <dd><?= htmlspecialchars($result->no_sm) ?></dd>
                                            
                                            <dt><i class="fas fa-user text-info"></i> Pengirim:</dt>
                                            <dd><?= htmlspecialchars($result->pengirim) ?></dd>
                                            
                                            <dt><i class="fas fa-file-alt text-success"></i> Perihal:</dt>
                                            <dd><?= htmlspecialchars($result->perihal) ?></dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <dl>
                                            <dt><i class="fas fa-calendar text-warning"></i> Tanggal Surat:</dt>
                                            <dd><?= $this->tanggalhelper->convertDayDate($result->tgl_surat) ?></dd>
                                            
                                            <dt><i class="fas fa-calendar-check text-danger"></i> Tanggal Terima:</dt>
                                            <dd><?= $this->tanggalhelper->convertDayDate($result->tgl_terima) ?></dd>
                                            
                                            <dt><i class="fas fa-hashtag text-secondary"></i> Nomor Agenda:</dt>
                                            <dd><?= htmlspecialchars($result->no_agenda) ?></dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Proses Terakhir -->
                        <?php if (!empty($progres_terakhir)) { ?>
                        <div class="card card-outline card-info mt-4">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-history"></i> Proses Terakhir
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <?php
                                        $status_text = 'Unknown';
                                        $status_icon = 'question-circle';
                                        $status_color = 'secondary';
                                        $tujuan_text = '';
                                        
                                        // Mapping jabatan
                                        $jabatan_map = [
                                            '1' => 'Ketua',
                                            '4' => 'Panitera',
                                            '5' => 'Sekretaris',
                                            '6' => 'Panitera Muda Gugatan',
                                            '7' => 'Panitera Muda Permohonan',
                                            '8' => 'Panitera Muda Jinayat',
                                            '9' => 'Panitera Muda Hukum',
                                            '10' => 'Kepala Sub Bagian Umum dan Keuangan',
                                            '11' => 'Kepala Sub Bagian Kepegawaian',
                                            '12' => 'Kepala Sub Bagian PTIP'
                                        ];
                                        
                                        switch($progres_terakhir->status) {
                                            case '1': 
                                                $status_text = 'Diteruskan'; 
                                                $status_icon = 'arrow-right';
                                                $status_color = 'primary';
                                                // Ambil tujuan untuk Diteruskan
                                                $tujuan_id = isset($progres_terakhir->tujuan) ? $progres_terakhir->tujuan : null;
                                                if ($tujuan_id && isset($jabatan_map[$tujuan_id])) {
                                                    $tujuan_text = $jabatan_map[$tujuan_id];
                                                }
                                                break;
                                            case '2': 
                                                $status_text = 'Disposisi'; 
                                                $status_icon = 'share-alt';
                                                $status_color = 'warning';
                                                // Ambil tujuan disposisi dari field 'tujuan'
                                                $tujuan_id = isset($progres_terakhir->tujuan) ? $progres_terakhir->tujuan : null;
                                                if ($tujuan_id && isset($jabatan_map[$tujuan_id])) {
                                                    $tujuan_text = $jabatan_map[$tujuan_id];
                                                }
                                                break;
                                            case '3': 
                                                $status_text = 'Dilaksanakan'; 
                                                $status_icon = 'cog';
                                                $status_color = 'info';
                                                break;
                                            case '4': 
                                                $status_text = 'Selesai'; 
                                                $status_icon = 'check-circle';
                                                $status_color = 'success';
                                                break;
                                        }
                                        
                                        // Gunakan status_text dari query jika ada
                                        if (isset($progres_terakhir->status_text) && !empty($progres_terakhir->status_text)) {
                                            $status_text = $progres_terakhir->status_text;
                                        }
                                        
                                        // Ambil created_by (oleh siapa) - hanya jabatan saja
                                        $oleh_siapa_raw = isset($progres_terakhir->created_by) ? $progres_terakhir->created_by : 'Tidak diketahui';
                                        
                                        // Ekstrak hanya jabatan (bagian sebelum tanda kurung jika ada)
                                        $oleh_siapa = $oleh_siapa_raw;
                                        if (strpos($oleh_siapa_raw, '(') !== false) {
                                            // Jika ada format "Jabatan (Nama)", ambil hanya bagian sebelum kurung
                                            $oleh_siapa = trim(explode('(', $oleh_siapa_raw)[0]);
                                        }
                                        ?>
                                        
                                        <!-- Progress Apa -->
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge badge-<?= $status_color ?> badge-lg mr-2" style="font-size: 16px; padding: 10px 16px;">
                                                    <i class="fas fa-<?= $status_icon ?>"></i> 
                                                    <?= $status_text ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Oleh Siapa -->
                                        <div class="mb-2">
                                            <i class="fas fa-user text-info"></i> 
                                            <strong>Oleh:</strong> 
                                            <span class="text-dark"><?= htmlspecialchars($oleh_siapa) ?></span>
                                        </div>
                                        
                                        <!-- Kepada Siapa (jika ada) -->
                                        <?php if (!empty($tujuan_text)) { ?>
                                        <div class="mb-2">
                                            <i class="fas fa-user-tie text-primary"></i> 
                                            <strong>Kepada:</strong> 
                                            <span class="text-primary"><?= htmlspecialchars($tujuan_text) ?></span>
                                        </div>
                                        <?php } ?>
                                        
                                        <!-- Tanggal/Waktu -->
                                        <div class="mb-0">
                                            <i class="fas fa-clock text-muted"></i> 
                                            <small class="text-muted">
                                                <?= date('d F Y, H:i', strtotime($progres_terakhir->created_on)) ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle"></i> 
                            Progres surat belum tersedia. Surat masih dalam tahap validasi.
                        </div>
                        <?php } ?>

                        <!-- Share Link -->
                        <div class="card card-outline card-secondary mt-4">
                            <div class="card-body text-center">
                                <h5><i class="fas fa-share-alt"></i> Bagikan Link Tracking</h5>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           id="shareLink" 
                                           value="<?= base_url('tracking?code=' . $result->tracking_code) ?>" 
                                           readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary" onclick="copyLink()">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <!-- Info Box -->
                        <div class="alert alert-info mt-4">
                            <h5><i class="fas fa-question-circle"></i> Cara Mendapatkan Tracking Code?</h5>
                            <p class="mb-0">
                                Tracking code akan otomatis dikirimkan melalui notifikasi (SMS/WhatsApp) 
                                ke nomor HP yang Anda berikan saat mengirim surat. 
                                Jika Anda belum menerima, silakan hubungi bagian PTSP MS Banda Aceh.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= site_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= site_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    
    <script>
        function copyLink() {
            const linkInput = document.getElementById('shareLink');
            linkInput.select();
            linkInput.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand('copy');
            
            // Show feedback
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
            btn.classList.add('btn-success');
            btn.classList.remove('btn-secondary');
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-secondary');
            }, 2000);
        }

        // Auto submit jika ada code di URL
        $(document).ready(function() {
            const code = $('#trackingCode').val();
            if (code && code.length > 0) {
                // Code sudah ada, form akan submit otomatis via GET
            }
        });
    </script>
</body>
</html>

