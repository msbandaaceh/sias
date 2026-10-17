<div class="content-wrapper">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pengaturan Aplikasi</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="javascript:;" data-page="dashboard">
                                <?= $this->session->userdata('nama_client_app') ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">Pengaturan Aplikasi</li>
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
                            <ul class="nav nav-tabs" id="custom-tabs-template" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab-template-surat" onclick="TampilTemplateSurat()"
                                        data-toggle="pill" href="#template-surat" role="tab"
                                        aria-controls="template-surat" aria-selected="true">
                                        <i class="fas fa-book"></i> Template Surat
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-jenis-surat" onclick="TampilJenisSurat()"
                                        data-toggle="pill" href="#jenis-surat" role="tab" aria-controls="jenis-surat"
                                        aria-selected="false">
                                        <i class="fas fa-envelope"></i> Jenis Surat
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab-klasifikasi-surat" onclick="TampilKlasifikasiSurat()"
                                        data-toggle="pill" href="#klasifikasi-surat" role="tab"
                                        aria-controls="klasifikasi-surat" aria-selected="false">
                                        <i class="fas fa-envelope"></i> Klasifikasi Surat
                                    </a>
                                </li>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-template-tabContent">

                                <!-- Tab Template Surat -->
                                <div class="tab-pane fade show active" id="template-surat" role="tabpanel"
                                    aria-labelledby="tab-template-surat">
                                    <h3><i class="fas fa-info-circle text-primary"></i> Daftar Template Surat</h3>
                                    <p class="lead">
                                        Halaman ini menampilkan daftar template surat yang dapat digunakan untuk membuat
                                        surat masuk atau keluar. Template ini memudahkan pengguna dalam menyusun surat
                                        dengan format yang konsisten dan profesional.
                                    </p>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card card-primary card-outline">
                                                <div class="card-header">
                                                    <div class="card-tools">
                                                        <button type="button" onclick="ModalTemplate()"
                                                            class="btn btn-outline-primary">
                                                            <i class="fas fa-plus"></i> Tambah Template
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <table id="tblTemplate" class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>NAMA TEMPLATE SURAT</th>
                                                                <th>JENIS SURAT</th>
                                                                <th>AKSI</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Data akan di-load via AJAX (server-side processing) -->
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>NO</th>
                                                                <th>NAMA TEMPLATE SURAT</th>
                                                                <th>JENIS SURAT</th>
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
                                <div class="tab-pane fade" id="jenis-surat" role="tabpanel"
                                    aria-labelledby="tab-jenis-surat">
                                    <h3><i class="fas fa-info-circle text-primary"></i> Jenis Surat</h3>
                                    <p class="lead">
                                        Halaman ini menampilkan jenis surat yang digunakan dalam sistem.
                                        Anda dapat menambah, mengedit, atau menghapus jenis surat sesuai
                                        kebutuhan.
                                    </p>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card card-primary card-outline">
                                                <div class="card-header">
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-outline-primary"
                                                            onclick="ModalJenis()">
                                                            <i class="fas fa-plus"></i> Tambah Jenis Surat
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <table id="tblJenis" class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Jenis Surat</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Jenis Surat</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="klasifikasi-surat" role="tabpanel"
                                    aria-labelledby="tab-klasifikasi-surat">
                                    <h3><i class="fas fa-info-circle text-primary"></i> Klasifikasi Surat</h3>
                                    <p class="lead">
                                        Halaman ini digunakan untuk mengelola klasifikasi surat yang digunakan dalam
                                        sistem sesuai SK KMA Nomor 131/KMA/SK/VII/2023 tentang Tata Naskah Dinas. Anda
                                        dapat menambahkan, mengedit, atau menghapus klasifikasi sesuai kebutuhan
                                        organisasi Anda.
                                    </p>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card card-primary card-outline">
                                                <div class="card-header">
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-outline-primary"
                                                            onclick="ModalKlasifikasi()">
                                                            <i class="fas fa-plus"></i> Tambah Klasifikasi Surat
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <table id="tblKlasifikasi"
                                                        class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Kode Klasifikasi</th>
                                                                <th>Jenis Klasifikasi</th>
                                                                <th width="10%">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Kode Klasifikasi</th>
                                                                <th>Jenis Klasifikasi</th>
                                                                <th width="10%">Aksi</th>
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

    <!-- Modal Tambah/Edit -->
    <div class="modal fade" id="templateModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTemplateTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="template_id" name="id">
                    <div class="form-group">
                        <label>Nama Template <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_template" name="nama_template"
                            placeholder="Contoh: Surat Tugas, Surat Kuasa">
                    </div>
                    <div class="form-group">
                        <label>Jenis Surat <span class="text-danger">*</span></label>
                        <div id="div_jenis"></div>
                    </div>
                    <div class="form-group">
                        <label>Isi Template <span class="text-danger">*</span></label>
                        <ul>
                            <li><strong>Salin link berikut untuk menggunakan kop surat</strong> :
                                <?= $this->session->userdata('kop_satker') ?>
                            </li>
                            <li><strong>Salin link berikut untuk menggunakan logo satuan kerja</strong> :
                                <?= $this->session->userdata('logo_satker') ?>
                            </li>
                            <li><strong><code>#0001#</code></strong> : Nomor Surat</li>
                            <li><strong><code>#0002#</code></strong> : Lokasi Surat</li>
                            <li><strong><code>#0003#</code></strong> : Tanggal Surat</li>
                            <li><strong><code>#0004#</code></strong> : Jabatan Penandatangan
                            </li>
                            <li><strong><code>#0005#</code></strong> : Nama Satker</li>
                            <li><strong><code>#0006#</code></strong> : Tanda Tangan Pejabat Penandatangan</li>
                            <li><strong><code>#0007#</code></strong> : Nama Pejabat Penandatangan</li>
                            <li><strong><code>#0008#</code></strong> : NIP Pejabat Penandatangan</li>
                        </ul>
                        <small class="text-muted">Gunakan <code>#variabel#</code> untuk placeholder dinamis.</small>
                        <textarea class="form-control" id="isi_template" name="isi_template" rows="12"
                            placeholder="Kepada Yth."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="SimpanTemplate()">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="jenisModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalJudulTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="jenis_id" name="id">
                    <div class="form-group">
                        <label>Nama Jenis Surat <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_jenis" name="jenis_surat" autocomplete="off"
                            placeholder="Contoh: Surat Tugas, Surat Keputusan">
                    </div>
                    <div class="form-group">
                        <label>Kode Penetapan Jenis Surat</label>
                        <input type="text" class="form-control" id="kode_penetapan" name="kode_penetapan"
                            autocomplete="off" placeholder="Contoh: ST, SK">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="SimpanJenis()">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="klasifikasiModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="klasifikasiJudul"></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="klasifikasi_id" name="id">
                    <div class="form-group">
                        <label>Kode Klasifikasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode_klasifikasi" name="kode_klasifikasi"
                            autocomplete="off" placeholder="Contoh: HK.1.1">
                    </div>
                    <div class="form-group">
                        <label>Jenis Klasifikasi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="jenis_klasifikasi" name="jenis_klasifikasi"
                            autocomplete="off" placeholder="Contoh: Hukum, Humas, Kepegawaian">
                    </div>
                    <div class="form-group">
                        <label>Keterangan Klasifikasi <span class="text-danger">*</span></label>
                        <textarea rows="3" class="form-control" id="keterangan" name="keterangan" autocomplete="off"
                            placeholder="Keterangan yang menjelaskan klasifikasi surat">
                        </textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="SimpanKlasifikasi()">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Preview -->
    <div class="modal fade" id="previewTemplateModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-eye"></i> Preview Template</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <pre id="previewContent"
                        style="white-space: pre-wrap; font-family: inherit; font-size: 14px;"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/tinymce/tinymce.min.js'); ?>"></script>
<script src="<?= site_url('assets/js/page/pengaturan_aplikasi.js') . '?' . time(); ?>"></script>

<script>
    $(document).ready(function () {
        TampilTemplateSurat();
    });
</script>