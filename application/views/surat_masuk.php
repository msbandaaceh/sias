<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Surat Masuk</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" data-page="dashboard">Surat</a></li>
                        <li class="breadcrumb-item active">Surat Masuk</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            if ($surat_masuk) {
                                $no = 1;
                                ?>
                                <table class="table">
                                    <tbody>
                                        <?php foreach ($surat_masuk as $item) { ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td class="text-center" style="width: 10%">
                                                    <span class="badge badge-success">
                                                        <div class="card-tools">
                                                            <?php
                                                            //Surat Dibaca
                                                            if ($item->dibaca) {
                                                                ?><i class="fas fa-check"
                                                                    title="Surat Sudah Dibaca"></i>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </span>
                                                    <span class="badge badge-success">
                                                        <div class="card-tools">
                                                            <?php
                                                            //Surat Ditindaklanjuti
                                                            if ($item->status) {
                                                                if ($item->status == 1) {
                                                                    ?><i class="fas fa-hourglass-half"
                                                                        title="Surat Sedang Ditindaklanjuti"></i>
                                                                    <?php
                                                                } elseif ($item->status == 2) {
                                                                    ?><i class="fas fa-thumbs-up"
                                                                        title="Surat Selesai Diproses"></i>
                                                                    <?php
                                                                }
                                                            } else {
                                                                ?>
                                                                <i class="fas fa-info" title="Surat Belum Diproses"></i>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </span>
                                                </td>
                                                <td><a class="btn text-left text-primary" data-target="#detilModal"
                                                        onclick="BukaDetilSurat('validasi', '<?= base64_encode($this->encryption->encrypt($item->id)) ?>')"
                                                        data-toggle="modal"><i class="bx bx-edit-alt me-1"></i>
                                                        <?php if ($item->dibaca) {
                                                            ?>
                                                            <?= $item->perihal; ?>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <b><?= $item->perihal; ?></b>
                                                        <?php } ?>
                                                    </a>

                                                </td>
                                                <td>
                                                    <?= $this->tanggalhelper->convertDayDate($item->tgl_surat); ?>
                                                </td>
                                            </tr>
                                            <?php $no++;
                                        } ?>
                                    </tbody>
                                </table>
                                <?php
                            } else {
                                ?>
                                <div class="row justify-content-center">
                                    <div class="col-12">
                                        <div class="callout callout-danger">
                                            <h5>
                                                <i class="fas fa-info"></i>
                                                Perhatian !!
                                            </h5>
                                            Belum ada surat masuk untuk anda. Terimakasih.
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <div class="modal fade" id="detilModal" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judul_detil"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <dl class="row">
                                <input type="hidden" name="register_id_detil" id="register_id_detil">
                                <dt class="col-sm-3 col-xs-3">Tanggal Register</dt>
                                <dd class="col-sm-1">:</dd>
                                <dd class="col-sm-8"><span id=tanggal_terima_detil></span></dd>
                                <dt class="col-sm-3 col-xs-3">Nomor Agenda</dt>
                                <dd class="col-sm-1">:</dd>
                                <dd class="col-sm-8"><span id=nomor_agenda_detil></span></dd>
                                <dt class="col-sm-3 col-xs-3">Tanggal Surat</dt>
                                <dd class="col-sm-1">:</dd>
                                <dd class="col-sm-8"><span id=tanggal_surat_detil></span></dd>
                                <dt class="col-sm-3 col-xs-3">Nomor Surat</dt>
                                <dd class="col-sm-1">:</dd>
                                <dd class="col-sm-8"><span id=nomor_surat_detil></span></dd>
                                <dt class="col-sm-3 col-xs-3">Pengirim</dt>
                                <dd class="col-sm-1">:</dd>
                                <dd class="col-sm-8"><span id=pengirim_detil></span></dd>
                                <dt class="col-sm-3 col-xs-3">Perihal</dt>
                                <dd class="col-sm-1">:</dd>
                                <dd class="col-sm-8"><span id=perihal_detil></span></dd>
                                <dt class="col-sm-3 col-xs-3">Keterangan</dt>
                                <dd class="col-sm-1">:</dd>
                                <dd class="col-sm-8"><span id=ket_detil></span></dd>
                            </dl>
                        </div>
                    </div>
                    <div class="card card-success card-tabs">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="surat_tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="data_surat_tab" data-toggle="pill"
                                        href="#custom-tabs-one-profile" role="tab"
                                        aria-controls="custom-tabs-one-profile" aria-selected="false">Lihat Surat</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-messages-tab" onclick="TampilProgresSurat()"
                                        data-toggle="pill" href="#custom-tabs-one-messages" role="tab"
                                        aria-controls="custom-tabs-one-messages" aria-selected="false">Progres Surat</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="riwayat_disposisi_tab" onclick="TampilRiwayatDisposisi()"
                                        data-toggle="pill" href="#riwayat_disposisi" role="tab"
                                        aria-controls="riwayat_disposisi" aria-selected="false">Riwayat Disposisi</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div class="tab-pane active" id="custom-tabs-one-profile" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-profile-tab">
                                    <span id="dokumen_detil"></span>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel"
                                    aria-labelledby="custom-tabs-one-messages-tab">
                                    <div id="progres_surat"></div>
                                    <div class="row">
                                        <div class="col-md-12" style="margin-bottom:10px;" id="btn_aksi">
                                            <button onclick="TampilPelaksanaan()" id="TombolTambahPelaksanaan"
                                                class="btn btn-sm btn-success">Tambah</button>
                                        </div>

                                        <br /><br />
                                        <div class="table-responsive" style="width: 100%;">
                                            <table class="table table-hover" id="tambah_pelaksanaan"
                                                style="display: none;">
                                                <tbody>
                                                    <tr>
                                                        <input type="hidden" id="pelaksanaan_id">
                                                        <td style="width: 30%;" class="control-label"><label>Progres
                                                                Surat <font color='red'>*</font></label></td>
                                                        <td style="width: 1%;"><b>:</b></td>
                                                        <td style="width: 70%;"><span id="pelaksanaan_"></span></td>
                                                    </tr>
                                                    <tr class="disposisi_surat">
                                                        <td style="width: 30%;" class="control-label"><label>Disposisi
                                                                Surat <font color='red'>*</font></label></td>
                                                        <td style="width: 1%;"><b>:</b></td>
                                                        <td style="width: 70%;"><span id="disposisi_"></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 30%;" class="control-label">
                                                            <label>Keterangan</label>
                                                        </td>
                                                        <td style="width: 1%;"><b>:</b></td>
                                                        <td style="width: 70%;"><textarea id="keterangan_pelaksanaan"
                                                                name="keterangan_pelaksanaan" class="form-control"
                                                                rows="2" placeholder="Keterangan"></textarea></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">
                                                            <div align="right">
                                                                <button onclick="TutupPelaksanaan()"
                                                                    class="btn btn-sm btn-white">Kembali</button>
                                                                <button onclick="SimpanPelaksanaanSM()"
                                                                    class="btn btn-sm btn-success"
                                                                    id="tombol_simpan_disposisi">Simpan</button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="riwayat_disposisi" role="tabpanel"
                                    aria-labelledby="riwayat_disposisi_tab">
                                    <div id="riwayat_disposisi"></div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.content -->
</div>
<!-- Content wrapper -->