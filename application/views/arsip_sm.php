<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Arsip Surat Masuk</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="" data-page="dashboard">Surat</a></li>
                        <li class="breadcrumb-item active">Arsip Surat Masuk</li>
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
                        <?php
                        if (in_array($peran, ['super', 'petugas'])) {
                            ?>
                            <div class="card-header">
                                <div class="row justify-content-end">

                                    <button id="tambah"
                                        onclick="ModalInputSurat('<?= base64_encode($this->encryption->encrypt(-1)); ?>')"
                                        class="btn btn-outline-primary">
                                        Tambah Data
                                    </button>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="card-body">
                            <?php
                            if ($arsip_sm) {
                                $no = 1;
                                ?>
                                <table id="tblSM" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NOMOR AGENDA</th>
                                            <th>NOMOR SURAT</th>
                                            <th>PENGIRIM</th>
                                            <th>PERIHAL</th>
                                            <th>TANGGAL SURAT</th>
                                            <th>TANGGAL TERIMA</th>
                                            <?php
                                            if (in_array($peran, ['super', '10'])) {
                                                ?>
                                                <th>AKSI</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($arsip_sm as $item) { ?>
                                            <tr>
                                                <td>
                                                    <?= $no; ?>
                                                </td>
                                                <td>
                                                    <?= $item->no_agenda;
                                                    ?>
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
                                                <td><button class="dropdown-item" data-target="#detilModal"
                                                        onclick="BukaDetilSurat('arsip', '<?= base64_encode($this->encryption->encrypt($item->id)) ?>')"
                                                        data-toggle="modal"
                                                        style="background: transparent; border: none !important;"><i
                                                            class="bx bx-edit-alt me-1"></i>
                                                        <p class="text-info"><b><?= $item->no_sm; ?></b></p>
                                                    </button>
                                                </td>
                                                <td>
                                                    <?= $item->pengirim; ?>
                                                </td>
                                                <td>
                                                    <?= $item->perihal; ?>
                                                </td>
                                                <td>
                                                    <?= $item->tgl_surat; ?>
                                                </td>
                                                <td>
                                                    <?= $item->tgl_terima; ?>
                                                </td>
                                                <?php
                                                if (in_array($peran, ['super', '10'])) {
                                                    ?>
                                                    <td>
                                                        <?php $idEncrypt = str_replace('/', '___', $this->encryption->encrypt($item->id));
                                                        ?>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                                data-toggle="dropdown">
                                                                <i class="fas fa-cogs"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <button class="dropdown-item" data-target="#tambah-modal"
                                                                    onclick="ModalInputSurat('<?= base64_encode($this->encryption->encrypt($item->id)) ?>')"
                                                                    data-toggle="modal"><i class="bx bx-edit-alt me-1"></i>
                                                                    EDIT</button>
                                                                <a class="dropdown-item" id="hapus" href="#" data-toggle="modal"
                                                                    data-target="#hapusModal" data-id="<?= $idEncrypt; ?>"><i
                                                                        class="bx bx-trash me-1"></i>
                                                                    HAPUS</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <?php
                                                } ?>
                                            </tr>
                                            <?php $no++;
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>NO</th>
                                            <th>NOMOR AGENDA</th>
                                            <th>NOMOR SURAT</th>
                                            <th>PENGIRIM</th>
                                            <th>PERIHAL</th>
                                            <th>TANGGAL SURAT</th>
                                            <th>TANGGAL TERIMA</th>
                                            <?php
                                            if (in_array($peran, ['super', '10'])) {
                                                ?>
                                                <th>AKSI</th>
                                            <?php } ?>
                                        </tr>
                                    </tfoot>
                                </table>
                                <?php

                            } else {
                                ?>
                                <div class="callout callout-danger">
                                    <h5>
                                        <i class="fas fa-info"></i>
                                        Perhatian !!
                                    </h5>
                                    Tidak ada arsip surat masuk. Terimakasih.
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="tambah-modal" data-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="card card-default">
                <div class="overlay" id="overlay">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </div>
                <form method="POST" id="formSM" class="modal-content" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="judul">Large Modal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input hidden type="hidden" name="id" id="id" class="form-control" />
                            <div class="row g-2">
                                <div class="col-lg-6 col-sm-12 mb-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="chkAgenda" class="form-label ml-auto">NO AGENDA</label>
                                        </div>
                                        <div class="col-6 text-right">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="chkAgenda"
                                                    value="option1" autocomplete="off">
                                                <label for="chkAgenda" class="custom-control-label">Edit Nomor
                                                    Agenda</label>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="text" name="no_agenda" id="no_agenda" class="form-control" required
                                        readonly />
                                </div>
                                <div class="col-lg-6 col-sm-12 mb-3">
                                    <label for="no_surat" class="form-label">NOMOR SURAT</label><code> *</code>
                                    <input type="text" id="no_surat" class="form-control" placeholder="Nomor Surat"
                                        name="no_surat" autocomplete="off" />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-lg-12 col-sm-12 mb-3">
                                    <label for="pengirim" class="form-label">PENGIRIM</label><code> *</code>
                                    <input type="text" name="pengirim" id="pengirim" class="form-control"
                                        placeholder="Pengirim Surat" />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-lg-6 col-sm-12 mb-3">
                                    <label for="perihal" class="form-label">PERIHAL</label><code> *</code>
                                    <textarea id="perihal" class="form-control" rows="2" name="perihal"
                                        placeholder="Perihal/Subjek Surat" autocomplete="off"></textarea>
                                </div>
                                <div class="col-lg-6 col-sm-12 mb-3">
                                    <label for="ket" class="form-label">KETERANGAN</label>
                                    <textarea id="ket" class="form-control" rows="2" name="ket"
                                        placeholder="Keterangan Surat"></textarea>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-lg-6 col-sm-12 mb-3">
                                    <label for="tanggal_surat" class="form-label">TANGGAL SURAT</label><code> *</code>
                                    <div class="input-group date" id="tglsurat" data-target-input="nearest">
                                        <input type="text" name="tgl_surat" id="tanggal_surat"
                                            class="form-control datetimepicker-input" data-target="#tglsurat"
                                            placeholder="Tanggal Surat" data-toggle="datetimepicker"
                                            autocomplete="off" />
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-12 mb-3">
                                    <label for="tanggal_terima" class="form-label">TANGGAL TERIMA</label><code> *</code>
                                    <div class="input-group date" id="tglterima" data-target-input="nearest">
                                        <input type="text" name="tgl_terima" id="tanggal_terima"
                                            class="form-control datetimepicker-input" data-target="#tglterima"
                                            data-toggle="datetimepicker" placeholder="Tanggal Surat"
                                            autocomplete="off" />
                                        <div class="input-group-append">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-12 mb-3">
                                    <div class="form-group">
                                        <label for="dokumen" class="form-label">FILE SURAT</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="dokumen"
                                                    accept="application/pdf" name="dokumen">
                                                <label class="custom-file-label" for="exampleInputFile">Pilih
                                                    Dokumen Surat</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="form-label"><code><i>* Wajib Diisi</i></code></label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="hapusModal" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" id="formPegawai" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="backDropModalTitle">HAPUS DATA SURAT MASUK</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="dropdown-divider"></div>
                <div class="modal-body">
                    <blockquote class="blockquote mt-3">
                        <p>Apakah anda yakin akan menghapus data ini?</p>
                    </blockquote>
                </div>

                <div class="modal-footer">
                    <a id="hapusSM" class="btn btn-danger" role="button"><span class="badge bg-danger">Hapus</span></a>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="detilModal" data-backdrop="static">
        <div class="modal-dialog modal-xl">
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
                            <ul class="nav nav-tabs" id="tab_surat" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="data_surat_tab" data-toggle="pill" href="#view_surat"
                                        role="tab" aria-controls="view_surat" aria-selected="false">Lihat Surat</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="progres_surat_tab" onclick="TampilProgresSurat()"
                                        data-toggle="pill" href="#progres_surat" role="tab"
                                        aria-controls="progres_surat" aria-selected="false">Progres Surat</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="riwayat_disposisi_tab" onclick="TampilRiwayatDisposisi()"
                                        data-toggle="pill" href="#riwayat_disposisi" role="tab"
                                        aria-controls="riwayat_disposisi" aria-selected="false">Riwayat Disposisi</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="tab_surat_content">
                                <div class="tab-pane active" id="view_surat" role="tabpanel"
                                    aria-labelledby="view_surat_tab">
                                    <span id="dokumen_detil"></span>
                                </div>
                                <div class="tab-pane fade" id="progres_surat" role="tabpanel"
                                    aria-labelledby="progres_surat_tab">
                                    <div id="progres_surat"></div>
                                    <div class="row">
                                        <div class="col-md-12" style="margin-bottom:10px;" id="btn_aksi_arsip">
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

<script>
    $(function () {
        $("#tblSM").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tblSM_wrapper .col-md-6:eq(0)');
    });
</script>