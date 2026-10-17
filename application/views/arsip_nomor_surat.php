<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Arsip Nomor Surat</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="javascript:;" data-page="dashboard">
                                <?= $this->session->userdata('nama_client_app') ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Arsip Nomor Surat</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Tabs -->
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="tabNoSurat" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-nomor-surat-ketua"
                                        onclick="TampilNomorSuratKetua()" data-toggle="pill" href="#nomor-surat-ketua"
                                        role="tab" aria-controls="nomor-surat-ketua" aria-selected="true">
                                        <i class="fas fa-book"></i> Nomor Surat Ketua
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-nomor-surat-panitera"
                                        onclick="TampilNomorSuratPanitera()" data-toggle="pill"
                                        href="#nomor-surat-panitera" role="tab" aria-controls="nomor-surat-panitera"
                                        aria-selected="false">
                                        <i class="fas fa-envelope"></i> Nomor Surat Panitera
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-nomor-surat-sekretaris"
                                        onclick="TampilNomorSuratSekretaris()" data-toggle="pill"
                                        href="#nomor-surat-sekretaris" role="tab" aria-controls="nomor-surat-sekretaris"
                                        aria-selected="false">
                                        <i class="fas fa-envelope"></i> Nomor Surat Sekretaris
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="tabNoSurat-tabContent">

                                <!-- Tab Template Surat -->
                                <div class="tab-pane fade show active" id="nomor-surat-ketua" role="tabpanel"
                                    aria-labelledby="tab-nomor-surat-ketua">
                                    <h3><i class="fas fa-info-circle text-primary"></i> Daftar Nomor Surat Ketua</h3>
                                    <p class="lead">
                                        Halaman ini menampilkan daftar nomor surat yang dapat sudah digunakan untuk
                                        membuat surat keluar Ketua.
                                    </p>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card card-primary card-outline">
                                                <div class="card-header">
                                                    <div class="card-tools">
                                                        <button type="button" onclick="ModalNomor(null, this)"
                                                            data-jabatan="Ketua" class="btn btn-outline-primary">
                                                            <i class="fas fa-plus"></i> Tambah Nomor Surat
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <table id="tblNoKetua" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>TUJUAN SURAT</th>
                                                                <th>PERIHAL</th>
                                                                <th>AKSI</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Data akan di-load via AJAX (server-side processing) -->
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>TUJUAN SURAT</th>
                                                                <th>PERIHAL</th>
                                                                <th>AKSI</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tab Jenis Klasifikasi Surat -->
                                <div class="tab-pane fade" id="nomor-surat-panitera" role="tabpanel"
                                    aria-labelledby="tab-nomor-surat-panitera">
                                    <h3><i class="fas fa-info-circle text-primary"></i> Daftar Nomor Surat Panitera</h3>
                                    <p class="lead">
                                        Halaman ini menampilkan daftar nomor surat yang dapat sudah digunakan untuk
                                        membuat surat keluar Panitera.
                                    </p>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card card-primary card-outline">
                                                <div class="card-header">
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-outline-primary"
                                                            onclick="ModalNomor(null, this)" data-jabatan="Panitera">
                                                            <i class="fas fa-plus"></i> Tambah Nomor Surat
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <table id="tblNoPanitera" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>TUJUAN SURAT</th>
                                                                <th>PERIHAL</th>
                                                                <th>AKSI</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Data akan di-load via AJAX (server-side processing) -->
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>TUJUAN SURAT</th>
                                                                <th>PERIHAL</th>
                                                                <th>AKSI</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nomor-surat-sekretaris" role="tabpanel"
                                    aria-labelledby="tab-nomor-surat-sekretaris">
                                    <h3><i class="fas fa-info-circle text-primary"></i> Daftar Nomor Surat Sekretaris
                                    </h3>
                                    <p class="lead">
                                        Halaman ini menampilkan daftar nomor surat yang dapat sudah digunakan untuk
                                        membuat surat keluar Sekretaris.
                                    </p>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card card-primary card-outline">
                                                <div class="card-header">
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-outline-primary"
                                                            onclick="ModalNomor(null, this)" data-jabatan="Sekretaris">
                                                            <i class="fas fa-plus"></i> Tambah Nomor Surat Sekretaris
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <table id="tblNoSekretaris"
                                                        class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>TUJUAN SURAT</th>
                                                                <th>PERIHAL</th>
                                                                <th>AKSI</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Data akan di-load via AJAX (server-side processing) -->
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>TUJUAN SURAT</th>
                                                                <th>PERIHAL</th>
                                                                <th>AKSI</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
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

    <!-- Modal Tambah -->
    <div class="modal fade" id="NoSuratModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="overlay" id="overlay">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </div>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nomorTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="formNomor">
                    <div class="modal-body">
                        <input type="hidden" id="no_id" name="id">
                        <input type="hidden" id="jabatan" name="jabatan">
                        <div id="div_no_agenda">
                            <div class="form-group">
                                <label>Nomor Agenda <span class="text-danger"></span></label>
                                <input type="text" class="form-control" id="no_agenda" readonly>
                            </div>
                            <div class="form-group">
                                <label>Nomor Surat <span class="text-danger"></span></label>
                                <input type="text" class="form-control" id="no_surat" name="no_surat">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tujuan Surat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="tujuan_surat" name="tujuan_surat"
                                autocomplete="off" placeholder="Contoh: MS Aceh, Ditjen Badilag, Mahkamah Agung, dll.">
                        </div>
                        <div class="form-group">
                            <label>Perihal <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="perihal" name="perihal" autocomplete="off"
                                placeholder="Contoh: Pengantar Berkas Banding"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Jenis Surat <span class="text-danger">*</span></label>
                            <div id="div_jenis_surat"></div>
                        </div>
                        <div class="form-group">
                            <label>Klasifikasi Surat <span class="text-danger">*</span></label>
                            <div id="div_klasifikasi"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Lihat -->
    <div class="modal fade" id="NoSuratViewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nomorViewTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nomor Agenda:</strong> <span id="v_no_agenda"></span></p>
                            <p><strong>Nomor Surat:</strong> <span id="v_no_surat"></span></p>
                            <p><strong>Tujuan Surat:</strong> <span id="v_tujuan_surat"></span></p>
                            <p><strong>Perihal:</strong> <span id="v_perihal"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Jenis Surat:</strong> <span id="v_jenis_surat"></span></p>
                            <p><strong>Klasifikasi:</strong> <span id="v_klasifikasi"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= site_url('assets/js/page/arsip_nomor_surat.js') . '?' . time(); ?>"></script>

<script>
    $(document).ready(function () {
        TampilNomorSuratKetua();
    });
</script>