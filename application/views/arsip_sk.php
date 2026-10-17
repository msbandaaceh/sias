<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Arsip Surat Keluar</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="javascript:;" data-page="dashboard">Surat</a></li>
                        <li class="breadcrumb-item active">Arsip Surat Keluar</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-folder"></i> Daftar Arsip Surat Keluar</h3>
                    <div class="card-tools">
                        <button id="tambah" onclick="ModalSuratKeluar()" class="btn btn-outline-primary">
                            Tambah Surat Keluar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tblArsipSK" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Surat Keluar</th>
                                <th>Perihal</th>
                                <th>Dibuat Pada</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalSuratKeluar" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="card card-default">
                <div class="overlay" id="overlay">
                    <i class="fas fa-2x fa-sync fa-spin"></i>
                </div>
                <form method="POST" id="formSK" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="judul"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input hidden type="hidden" name="id" id="id" class="form-control" />
                            <div class="row g-2">
                                <div class="col">
                                    <label for="chkAgenda" class="form-label ml-auto">PERIHAL <code>*</code></label>
                                    <input type="text" name="perihal" id="perihal" class="form-control"
                                        placeholder="Perihal Surat" autocomplete="off" required />
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

    <!-- Modal Preview Surat Keluar -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-eye"></i> Preview Surat Keluar</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body" style="padding:0;">
                    <!-- Konten Surat -->
                    <input type="hidden" id="preview_ukuran_kertas">
                    <div class="p-3" style="background:#eee;">
                        <div id="preview_body" class="page-content page-a4"
                            style="background:#fff;margin:0 auto;padding:40px;max-width:210mm;min-height:297mm;box-shadow:0 2px 8px rgba(0,0,0,.15);font-family:'Times New Roman',serif;font-size:12pt;line-height:1.6;">
                            <div id="preview_konten"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="CetakSuratKeluar()">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="preview_sk_id">
</div>

<script src="<?= site_url('assets/js/page/arsip_sk.js') . '?' . time(); ?>"></script>

<script>
    $(document).ready(function () {
        TampilArsipSuratKeluar();
    });
</script>