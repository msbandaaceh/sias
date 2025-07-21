<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Peran Pegawai</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="" data-page="dashboard">Surat</a></li>
                        <li class="breadcrumb-item active">Daftar Peran Pegawai</li>
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
                        <div class="card-header">
                            <div class="row justify-content-end">
                                <button id="tambah"
                                    onclick="ModalPeran('<?= base64_encode($this->encryption->encrypt(-1)); ?>')"
                                    class="btn btn-outline-primary">
                                    Tambah Data
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <?php
                            if ($peran_pegawai != null) {
                                $no = 1;
                                ?>
                                <table id="tblPeran" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>PERAN</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($peran_pegawai as $item) { ?>
                                            <tr>
                                                <td>
                                                    <?= $no; ?>
                                                </td>
                                                <td>
                                                    <?= $item->nama; ?>
                                                </td>
                                                <?php
                                                if ($item->status == '0') {
                                                    ?>
                                                    <td><span class='badge bg-green'><?= $item->peran ?></span></td>
                                                    <td>
                                                        <button type="button" class="btn btn-xs bg-orange" id="editPeran"
                                                            onclick="ModalRole('<?= base64_encode($this->ecnryption->encrypt($item->id)) ?>')"
                                                            title="Edit Peran">
                                                            <i class="fas fa-pen"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-xs bg-red" id="hapusPeran"
                                                            onclick="blokPeran('<?= base64_encode($this->ecnryption->encrypt($item->id)) ?>')"
                                                            title="Blok Pegawai">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    </td>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <td><span class='badge bg-grey'><?= $item->peran ?></span></td>
                                                    <td>
                                                        <button type="button" class="btn btn-xs bg-success" id="hapusPeran"
                                                            onclick="aktifPeran('<?= base64_encode($this->ecnryption->encrypt($item->id)) ?>')"
                                                            title="Aktifkan Pegawai">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php $no++;
                                        } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAMA</th>
                                            <th>PERAN</th>
                                            <th>AKSI</th>
                                        </tr>
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
                                    Belum ada pegawai yang didaftarkan.
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

    <div class="modal fade" id="modal-peran" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="card card-default">
                <div class="overlay" id="overlay">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </div>
                <form method="POST" id="formSM" class="modal-content">
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
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="pegawai" class="form-label">PILIH PEGAWAI</label><code> *</code>
                                        <div id="pegawai_"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="pegawai" class="form-label">PILIH PERAN PEGAWAI</label><code> *</code>
                                        <div id="peran_"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row justify-content-end">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.content -->
</div>
<!-- Content wrapper -->