$(function () {
    $(document).off('submit', '#formNomor').on('submit', '#formNomor', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);
        let jabatan = $('#jabatan').val();
        let $button = $('#btnSimpanNomor');

        $button.prop('disabled', true);
        $('#overlay').show();

        $.ajax({
            url: 'simpan_nomor_surat',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    Swal.fire({
                        title: 'Simpan Data Berhasil',
                        text: res.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            $('#NoSuratModal').modal('hide');
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                            if (jabatan === 'Ketua') {
                                window._NomorKetuaTable.ajax.reload();
                            } else if (jabatan === 'Panitera') {
                                window._NomorPaniteraTable.ajax.reload();
                            } else {
                                window._NomorSekretarisTable.ajax.reload();
                            }
                        }
                    });

                } else {
                    toastr.error(res.message);
                    $button.prop('disabled', false);
                }
            },
            error: function () {
                toastr.error('Terjadi kesalahan saat menyimpan data.');
                $button.prop('disabled', false);
            },
            complete: function () {
                $('#overlay').hide();
            }
        });
    });

    $(document).on('click', '.hapus-no-btn', function () {
        var id = $(this).data('id');
        var jabatan = $(this).data('jabatan');
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Nomor akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d73a49',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'YA, HAPUS'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'hapus_nomor_surat',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            toastr.success('Nomor Surat berhasil dihapus.');
                            if (jabatan === 'Ketua') {
                                window._NomorKetuaTable.ajax.reload();
                            } else if (jabatan === 'Panitera') {
                                window._NomorPaniteraTable.ajax.reload();
                            } else {
                                window._NomorSekretarisTable.ajax.reload();
                            }
                        } else {
                            toastr.error('Gagal menghapus Nomor Surat.');
                        }
                    },
                    error: function () {
                        toastr.error('Terjadi kesalahan.');
                    }
                });
            }
        });
    });
});

function TampilNomorSuratKetua() {
    if (!$('#tblNoKetua').hasClass('dataTable')) {
        var table = $("#tblNoKetua").DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            "ajax": {
                "url": "daftar_nomor_surat_ketua",
                "type": "POST"
            },
            "columns": [
                { "data": 0, "orderable": false, "searchable": false },
                { "data": 2, "orderable": true },
                { "data": 3, "orderable": true },
                {
                    "data": 1,
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row) {
                        var btns = '';
                        btns += '<button class="btn btn-sm btn-info mr-1" onclick="ModalLihatNomor(' + data + ', this)" title="Lihat"><i class="fas fa-eye"></i></button>';
                        if (window.userRole === 'admin' || window.userRole === 'penelaah') {
                            btns += '<button class="btn btn-sm btn-warning mr-1" onclick="ModalNomor(' + data + ', this)" data-jabatan="Ketua" title="Edit"><i class="fas fa-edit"></i></button>';
                            btns += '<button class="btn btn-sm btn-danger hapus-no-btn" data-id="' + data + '" data-jabatan="Ketua" title="Hapus"><i class="fas fa-trash"></i></button>';
                        }
                        return btns;
                    }
                }
            ],
            "order": [[0, "desc"]],
            "language": {
                "processing": "Memproses data...",
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "emptyTable": '<div class="text-center py-5"><i class="fas fa-file-alt fa-3x text-muted mb-3"></i><p class="lead text-muted">Belum ada nomor surat yang dibuat</p><p class="text-muted">Ayo input nomor surat pertamamu!</p><button type="button" class="btn btn-primary mt-2" onclick="ModalNomor(null, this)"><i class="fas fa-plus"></i> Tambah Nomor Surat</button></div>',
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            },
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            initComplete: function () {
                var api = this.api();
                var searchInput = $('#tblNomorKetua_filter input');
                var debounceTimer;
                searchInput.off('keyup.DT input.DT');

                searchInput.on('input', function () {
                    var value = this.value;
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function () {
                        if (api.search() !== value) {
                            api.search(value).draw();
                        }
                    }, 500);
                });
            },
            "drawCallback": function (settings) {
                var api = this.api();
                if (api.rows({ search: 'applied' }).data().length === 0) {
                    var emptyHtml =
                        '<tr><td colspan="6" class="text-center p-5">' +
                        '<i class="fas fa-file-alt fa-3x text-muted mb-3 d-block"></i>' +
                        '<p class="lead text-muted">Belum ada nomor surat yang dibuat</p>' +
                        '<p class="text-muted">Ayo input nomor surat pertamamu!</p>' +
                        '<button type="button" class="btn btn-primary mt-2" onclick="ModalNomor(null, this)" data-jabatan="Ketua">' +
                        '<i class="fas fa-plus"></i> Tambah Nomor Surat' +
                        '</button></td></tr>';
                    $('tbody', api.table().node()).html(emptyHtml);
                }
            }
        });
        window._NomorKetuaTable = table;
    } else {
        window._NomorKetuaTable.ajax.reload();
    }
}

function TampilNomorSuratPanitera() {
    if (!$('#tblNoPanitera').hasClass('dataTable')) {
        var table = $("#tblNoPanitera").DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            "ajax": {
                "url": "daftar_nomor_surat_panitera",
                "type": "POST"
            },
            "columns": [
                { "data": 0, "orderable": false, "searchable": false },
                { "data": 2, "orderable": true },
                { "data": 3, "orderable": true },
                {
                    "data": 1,
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row) {
                        var btns = '';
                        btns += '<button class="btn btn-sm btn-info mr-1" onclick="ModalLihatNomor(' + data + ', this)" title="Lihat"><i class="fas fa-eye"></i></button>';
                        if (window.userRole === 'admin' || window.userRole === 'penelaah') {
                            btns += '<button class="btn btn-sm btn-warning mr-1" onclick="ModalNomor(' + data + ', this)" data-jabatan="Panitera" title="Edit"><i class="fas fa-edit"></i></button>';
                            btns += '<button class="btn btn-sm btn-danger hapus-no-btn" data-id="' + data + '" data-jabatan="Panitera" title="Hapus"><i class="fas fa-trash"></i></button>';
                        }
                        return btns;
                    }
                }
            ],
            "order": [[0, "desc"]],
            "language": {
                "processing": "Memproses data...",
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "emptyTable": '<div class="text-center py-5"><i class="fas fa-file-alt fa-3x text-muted mb-3"></i><p class="lead text-muted">Belum ada nomor surat yang dibuat</p><p class="text-muted">Ayo input nomor surat pertamamu!</p><button type="button" class="btn btn-primary mt-2" onclick="ModalNomor(null, this)" data-jabatan="Panitera"><i class="fas fa-plus"></i> Tambah Nomor Surat</button></div>',
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            },
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            initComplete: function () {
                var api = this.api();
                var searchInput = $('#tblNomorPanitera_filter input');
                var debounceTimer;
                searchInput.off('keyup.DT input.DT');

                searchInput.on('input', function () {
                    var value = this.value;
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function () {
                        if (api.search() !== value) {
                            api.search(value).draw();
                        }
                    }, 500);
                });
            },
            "drawCallback": function (settings) {
                var api = this.api();
                if (api.rows({ search: 'applied' }).data().length === 0) {
                    var emptyHtml =
                        '<tr><td colspan="6" class="text-center p-5">' +
                        '<i class="fas fa-file-alt fa-3x text-muted mb-3 d-block"></i>' +
                        '<p class="lead text-muted">Belum ada nomor surat yang dibuat</p>' +
                        '<p class="text-muted">Ayo input nomor surat pertamamu!</p>' +
                        '<button type="button" class="btn btn-primary mt-2" onclick="ModalNomor(null, this)" data-jabatan="Panitera">' +
                        '<i class="fas fa-plus"></i> Tambah Nomor Surat' +
                        '</button></td></tr>';
                    $('tbody', api.table().node()).html(emptyHtml);
                }
            }
        });
        window._NomorPaniteraTable = table;
    } else {
        window._NomorPaniteraTable.ajax.reload();
    }
}

function TampilNomorSuratSekretaris() {
    if (!$('#tblNoSekretaris').hasClass('dataTable')) {
        var table = $("#tblNoSekretaris").DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            "ajax": {
                "url": "daftar_nomor_surat_sekretaris",
                "type": "POST"
            },
            "columns": [
                { "data": 0, "orderable": false, "searchable": false },
                { "data": 2, "orderable": true },
                { "data": 3, "orderable": true },
                {
                    "data": 1,
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row) {
                        var btns = '';
                        btns += '<button class="btn btn-sm btn-info mr-1" onclick="ModalLihatNomor(' + data + ', this)" title="Lihat"><i class="fas fa-eye"></i></button>';
                        if (window.userRole === 'admin' || window.userRole === 'penelaah') {
                            btns += '<button class="btn btn-sm btn-warning mr-1" onclick="ModalNomor(' + data + ', this)" data-jabatan="Sekretaris" title="Edit"><i class="fas fa-edit"></i></button>';
                            btns += '<button class="btn btn-sm btn-danger hapus-no-btn" data-id="' + data + '" data-jabatan="Sekretaris" title="Hapus"><i class="fas fa-trash"></i></button>';
                        }
                        return btns;
                    }
                }
            ],
            "order": [[0, "desc"]],
            "language": {
                "processing": "Memproses data...",
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "emptyTable": '<div class="text-center py-5"><i class="fas fa-file-alt fa-3x text-muted mb-3"></i><p class="lead text-muted">Belum ada nomor surat yang dibuat</p><p class="text-muted">Ayo input nomor surat pertamamu!</p><button type="button" class="btn btn-primary mt-2" onclick="ModalNomor(null, this)" data-jabatan="Sekretaris"><i class="fas fa-plus"></i> Tambah Nomor Surat</button></div>',
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            },
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            initComplete: function () {
                var api = this.api();
                var searchInput = $('#tblNomorSekretaris_filter input');
                var debounceTimer;
                searchInput.off('keyup.DT input.DT');

                searchInput.on('input', function () {
                    var value = this.value;
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function () {
                        if (api.search() !== value) {
                            api.search(value).draw();
                        }
                    }, 500);
                });
            },
            "drawCallback": function (settings) {
                var api = this.api();
                if (api.rows({ search: 'applied' }).data().length === 0) {
                    var emptyHtml =
                        '<tr><td colspan="6" class="text-center p-5">' +
                        '<i class="fas fa-file-alt fa-3x text-muted mb-3 d-block"></i>' +
                        '<p class="lead text-muted">Belum ada nomor surat yang dibuat</p>' +
                        '<p class="text-muted">Ayo input nomor surat pertamamu!</p>' +
                        '<button type="button" class="btn btn-primary mt-2" onclick="ModalNomor(null, this)" data-jabatan="Sekretaris">' +
                        '<i class="fas fa-plus"></i> Tambah Nomor Surat' +
                        '</button></td></tr>';
                    $('tbody', api.table().node()).html(emptyHtml);
                }
            }
        });
        window._NomorSekretarisTable = table;
    } else {
        window._NomorSekretarisTable.ajax.reload();
    }
}

function ModalNomor(id = null, event) {
    var jabatan = $(event).data('jabatan');
    $('#div_no_agenda').hide();
    $('#overlay').show();
    $.ajax({
        url: 'get_nomor_surat_by_id',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
            $('#overlay').hide();

            if (window.userRole === 'admin' || window.userRole === 'penelaah') {
                $('#no_agenda').val('');
                $('#no_surat').val('');

                $('#no_agenda').val(res.data.no_agenda);
                $('#no_surat').val(res.data.no_surat);
            }

            if (id !== null) {
                $('#div_no_agenda').show();
            }

            $('#nomorTitle').html('');
            $('#no_id').val('');
            $('#tujuan_surat').val('');
            $('#perihal').val('');
            $('#jabatan').val('');
            $('#div_jenis_surat').html('');
            $('#div_klasifikasi').html('');

            $('#nomorTitle').append(res.data.judul + ' ' + jabatan);
            $('#no_id').val(res.data.id);
            $('#tujuan_surat').val(res.data.tujuan_surat);
            $('#perihal').val(res.data.perihal);
            $('#jabatan').val(jabatan);
            $('#div_jenis_surat').append(res.data.jenis_surat);
            $('#div_klasifikasi').html(res.data.klasifikasi_surat);

            $('#jenis_surat').select2({
                theme: 'bootstrap4',
                width: '100%',
                dropdownParent: $('#NoSuratModal'),
                allowClear: true
            });

            $('#klasifikasi_surat').select2({
                theme: 'bootstrap4',
                width: '100%',
                dropdownParent: $('#NoSuratModal'),
                allowClear: true
            });

            $('#NoSuratModal').modal('show');
        }
    });
}

// Lihat detail (read-only) nomor surat
function ModalLihatNomor(id) {
    $.ajax({
        url: 'lihat_nomor_surat_by_id',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
            if (!res.success) {
                toastr.error('Data tidak ditemukan.');
                return;
            }
            var d = res.data;
            $('#nomorViewTitle').text('Detail Nomor Surat' + (d.jabatan ? ' — ' + d.jabatan : ''));
            $('#v_no_agenda').text(d.no_agenda || '-');
            $('#v_no_surat').text(d.no_surat || '-');
            $('#v_tujuan_surat').text(d.tujuan_surat || '-');
            $('#v_perihal').text(d.perihal || '-');
            $('#v_jenis_surat').text(d.jenis_surat_label || '-');
            $('#v_klasifikasi').text(d.klasifikasi_label || '-');

            $('#NoSuratViewModal').modal('show');
        },
        error: function () {
            toastr.error('Gagal memuat detail nomor surat.');
        }
    });
}