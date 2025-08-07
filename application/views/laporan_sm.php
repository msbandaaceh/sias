<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Laporan Surat Masuk</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#" data-page="dashboard">Surat</a></li>
                        <li class="breadcrumb-item active">Laporan Surat Masuk</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <form id="formLaporanSM">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="tgl_awal" class="form-label">Tanggal
                                Awal</label>
                            <input class="form-control" type="date" id="tgl_awal" name="tgl_awal"
                                value="<?php echo set_value('tgl_awal'); ?>" />
                            <code><?php echo form_error('tgl_awal'); ?></code>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="tgl_akhir" class="form-label">Tanggal
                                Akhir</label>
                            <input class="form-control" type="date" id="tgl_akhir" name="tgl_akhir"
                                value="<?php echo set_value('tgl_akhir'); ?>" />
                            <code><?php echo form_error('tgl_akhir'); ?></code>
                        </div>
                    </div>
                    <div class="col-sm-2 text-center">
                        <button class="btn btn-app bg-info" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            if ($laporan_sm) {
                                $no = 1;
                                ?>
                                <table id="tblLaporanSM" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NOMOR AGENDA</th>
                                            <th>NOMOR SURAT</th>
                                            <th>PENGIRIM</th>
                                            <th>PERIHAL</th>
                                            <th>TANGGAL SURAT</th>
                                            <th>TANGGAL TERIMA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($laporan_sm as $item) { ?>
                                            <tr>
                                                <td><?= $no; ?></td>
                                                <td><?= $item->no_agenda; ?></td>
                                                <td><?= $item->no_sm; ?></td>
                                                <td><?= $item->pengirim; ?></td>
                                                <td><?= $item->perihal; ?></td>
                                                <td><?= $this->tanggalhelper->convertDayDate($item->tgl_surat); ?></td>
                                                <td><?= $this->tanggalhelper->convertDayDate($item->tgl_terima); ?></td>
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
</div>

<script>
    $(function () {
        $("#tblLaporanSM").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tblLaporanSM_wrapper .col-md-6:eq(0)');
    });
</script>