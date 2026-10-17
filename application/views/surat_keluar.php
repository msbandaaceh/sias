<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 id="sk_heading">Buat Surat Keluar</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="javascript:;" data-page="dashboard">Surat</a></li>
                        <li class="breadcrumb-item"><a href="javascript:;" data-page="arsip_sk">Arsip Surat Keluar</a>
                        </li>
                        <li class="breadcrumb-item active" id="sk_breadcrumb">Buat Surat Keluar</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Toolbar Panel -->
                <div class="col-md-3">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-tools"></i> Pengisi Template</h3>
                        </div>
                        <div class="card-body">
                            <!-- Pilih Jenis Surat (Langkah 1) -->
                            <div class="form-group">
                                <label>Jenis Surat <span class="text-danger">*</span></label>
                                <div id="jenis_surat_">
                                    <select class="form-control">
                                        <option value="">Pilih Jenis Surat</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Pilih Template (Langkah 2 - terfilter berdasarkan jenis) -->
                            <div class="form-group">
                                <label>Pilih Template <span class="text-danger">*</span></label>
                                <div id="jenis_template_">
                                    <select class="form-control">
                                        <option value="">Pilih Jenis Surat Dahulu</option>
                                    </select>
                                </div>
                            </div>

                            <div id="toolbar_surat" style="display: none;">
                                <hr>
                                <div class="form-group">
                                    <label>Ukuran Kertas</label>
                                    <select class="form-control" id="ukuran_kertas"
                                        onchange="SimpanParameterSurat('jenis_kertas', '#ukuran_kertas')"
                                        name="ukuran_kertas">
                                        <option value="">Pilih Ukuran Kertas</option>
                                        <option value="A4">A4 (210 × 297 mm)</option>
                                        <option value="F4">F4 (215 × 330 mm)</option>
                                    </select>
                                </div>

                                <!-- 1. Penandatangan Surat -->
                                <div class="form-group">
                                    <label>1. Penandatangan Surat</label>
                                    <div id="select_pegawai_">
                                        <select class="form-control">
                                            <option value="">Pilih Penandatangan</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- 2. Jenis Klasifikasi Surat -->
                                <div class="form-group">
                                    <label>2. Jenis Klasifikasi Surat</label>
                                    <div id="klasifikasi_surat_">
                                        <select class="form-control">
                                            <option value="">Pilih Klasifikasi</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- 3. Pilihan Bulan -->
                                <div class="form-group">
                                    <label>3. Bulan</label>
                                    <select class="form-control" id="bulan" name="bulan" onchange="SimpanParameterSurat('bulan', '#bulan')">
                                        <option value="">Pilih Bulan</option>
                                        <option value="I">Januari (I)</option>
                                        <option value="II">Februari (II)</option>
                                        <option value="III">Maret (III)</option>
                                        <option value="IV">April (IV)</option>
                                        <option value="V">Mei (V)</option>
                                        <option value="VI">Juni (VI)</option>
                                        <option value="VII">Juli (VII)</option>
                                        <option value="VIII">Agustus (VIII)</option>
                                        <option value="IX">September (IX)</option>
                                        <option value="X">Oktober (X)</option>
                                        <option value="XI">November (XI)</option>
                                        <option value="XII">Desember (XII)</option>
                                    </select>
                                </div>

                                <!-- 4. Pilihan Tahun -->
                                <div class="form-group">
                                    <label>4. Tahun</label>
                                    <select class="form-control" id="tahun" name="tahun" onchange="SimpanParameterSurat('tahun', '#tahun')">
                                        <option value="">Pilih Tahun</option>
                                    </select>
                                </div>

                                <!-- 6. Input Lokasi -->
                                <div class="form-group">
                                    <label>6. Lokasi Surat</label>
                                    <input type="text" class="form-control" id="lokasi_surat"
                                        placeholder="Contoh: Banda Aceh">
                                    <small class="text-muted">Mengisi placeholder #0002#</small>
                                </div>

                                <!-- 7. Tanggal Surat -->
                                <div class="form-group">
                                    <label>7. Tanggal Surat</label>
                                    <input type="date" class="form-control" id="tgl_surat" onchange="SimpanParameterSurat('tgl_surat', '#tgl_surat')">
                                    <small class="text-muted">Mengisi placeholder #0003#</small>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <input type="hidden" id="record_id" name="record_id" value="0">
                            <button type="button" class="btn btn-primary btn-block" id="sk_save_btn"
                                onclick="SimpanSuratKeluar()">
                                <i class="fas fa-save"></i> Simpan Surat Keluar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Editor Panel -->
                <div class="col-md-9">
                    <div class="card card-primary card-outline" style="position:relative;">
                        <div class="overlay" id="editor-overlay" style="display:none;">
                            <i class="fas fa-2x fa-sync fa-spin text-primary"></i>
                        </div>
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-edit"></i> Editor Template</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <textarea id="isi_template_editor" name="isi_template_editor"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="<?php echo base_url('assets/tinymce/tinymce.min.js'); ?>"></script>
<script src="<?= site_url('assets/js/page/surat_keluar.js') . '?' . time(); ?>"></script>

<script>
    var currentTemplate = null;
    var allPegawai = [];
    var editingSKId = 0;
    var savedIsiTemplate = '';

    $(document).ready(function () {

        // Detect edit mode via query param
        var params = new URLSearchParams(window.location.search);
        var skId = params.get('id');
        if (skId) {
            editingSKId = skId;
            $('#sk_heading').text('Edit Surat Keluar');
            $('#sk_save_btn').text('Simpan Perubahan');
            loadDataForEdit(skId);
        }
    });

    // Live preview update when toolbar fields change.
    // Untuk field teks (#lokasi_surat) debounce 400ms agar preview hanya
    // ter-render saat user selesai mengetik — bukan tiap ketukan.
    // keepLokasi=true: lokasi dipertahankan & tetap bisa diedit.
    var lokasiDebounceTimer = null;
    $(document).on('change keyup', '#lokasi_surat', function (e) {
        // Hanya teks bebas yang butuh debounce; dropdown/datepicker langsung.
        if (e.target.id === 'lokasi_surat') {
            clearTimeout(lokasiDebounceTimer);
            lokasiDebounceTimer = setTimeout(function () {
                SimpanParameterSurat('lokasi_surat', '#lokasi_surat');
            }, 400);
        } else {
            updatePreview(tinymce.activeEditor.getContent(), true);
        }
    });
</script>