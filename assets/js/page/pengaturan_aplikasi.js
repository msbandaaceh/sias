$(function () {
    $(document).on('click', '.hapus-template-btn', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Template akan ditandai sebagai dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d73a49',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'YA, HAPUS'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'template_surat/hapus_template',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            toastr.success('Template berhasil dihapus.');
                            table.ajax.reload();
                        } else {
                            toastr.error('Gagal menghapus template.');
                        }
                    },
                    error: function () {
                        toastr.error('Terjadi kesalahan.');
                    }
                });
            }
        });
    });

    $(document).on('click', '.hapus-klasifikasi-btn', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Klasifikasi Surat akan ditandai sebagai dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d73a49',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'YA, HAPUS'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'hapus_klasifikasi_surat',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            toastr.success('Klasifikasi berhasil dihapus.');
                            window._klasifikasiTable.ajax.reload();
                        } else {
                            toastr.error('Gagal menghapus template.');
                        }
                    },
                    error: function () {
                        toastr.error('Terjadi kesalahan.');
                    }
                });
            }
        });
    });

    $(document).on('click', '.hapus-jenis-btn', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Jenis surat akan ditandai sebagai dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d73a49',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'YA, HAPUS'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'hapus_jenis_surat',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            toastr.success('Data berhasil dihapus.');
                            window._jenisTable.ajax.reload();
                        } else {
                            toastr.error('Gagal menghapus data.');
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

function TampilKlasifikasiSurat() {
    if (!$('#tblKlasifikasi').hasClass('dataTable')) {
        var table = $("#tblKlasifikasi").DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            "ajax": {
                "url": "daftar_klasifikasi",
                "type": "POST"
            },
            "columns": [
                { "data": 0, "orderable": false, "searchable": false },
                { "data": 2, "orderable": true },
                {
                    "data": null,
                    "orderable": false,
                    "render": function (_data, _type, row) {
                        var jenis = row[3] || '';
                        var ket = row[4] || '';
                        var badgeClass = 'badge-info';
                        var html = '<span class="badge ' + badgeClass + ' p-2">' + jenis + '</span>';
                        if (ket) html += '<br><small class="text-muted">' + ket + '</small>';
                        return html;
                    }
                },
                {
                    "data": 1,
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row) {
                        var btns = '';
                        btns += '<button class="btn btn-sm btn-warning mr-1" onclick="ModalKlasifikasi(' + data + ')" title="Edit"><i class="fas fa-edit"></i></button>';
                        btns += '<button class="btn btn-sm btn-danger hapus-klasifikasi-btn" data-id="' + data + '" title="Hapus"><i class="fas fa-trash"></i></button>';
                        return btns;
                    }
                }
            ],
            "order": [[0, "asc"]],
            "language": {
                "processing": "Memproses data...",
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "emptyTable": '<div class="text-center py-5"><i class="fas fa-file-alt fa-3x text-muted mb-3"></i><p class="lead text-muted">Belum ada klasifikasi yang tersedia</p><p class="text-muted">Ayo buat klasifikasi pertamamu!</p><button type="button" class="btn btn-primary mt-2" onclick="ModalKlasifikasi()"><i class="fas fa-plus"></i> Tambah Klasifikasi</button></div>',
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
                var searchInput = $('#tblKlasifikasi_filter input');
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
                        '<tr><td colspan="4" class="text-center p-5">' +
                        '<i class="fas fa-file-alt fa-3x text-muted mb-3 d-block"></i>' +
                        '<p class="lead text-muted">Belum ada klasifikasi yang tersedia</p>' +
                        '<p class="text-muted">Ayo buat klasifikasi pertamamu!</p>' +
                        '<button type="button" class="btn btn-primary mt-2" onclick="ModalKlasifikasi()">' +
                        '<i class="fas fa-plus"></i> Tambah Klasifikasi' +
                        '</button></td></tr>';
                    $('tbody', api.table().node()).html(emptyHtml);
                }
            }
        });
        window._klasifikasiTable = table;
    } else {
        window._klasifikasiTable.ajax.reload();
    }
}

function TampilTemplateSurat() {
    if (!$('#tblTemplate').hasClass('dataTable')) {
        var table = $("#tblTemplate").DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            "ajax": {
                "url": "daftar_template",
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
                        btns += '<button class="btn btn-sm btn-info mr-1" onclick="PreviewTemplate(' + data + ')" title="Preview"><i class="fas fa-eye"></i></button>';
                        btns += '<button class="btn btn-sm btn-warning mr-1" onclick="ModalTemplate(' + data + ')" title="Edit"><i class="fas fa-edit"></i></button>';
                        btns += '<button class="btn btn-sm btn-danger hapus-template-btn" data-id="' + data + '" title="Hapus"><i class="fas fa-trash"></i></button>';
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
                "emptyTable": '<div class="text-center py-5"><i class="fas fa-file-alt fa-3x text-muted mb-3"></i><p class="lead text-muted">Belum ada template yang tersedia</p><p class="text-muted">Ayo buat template pertamamu!</p><button type="button" class="btn btn-primary mt-2" onclick="ModalTambahTemplate()"><i class="fas fa-plus"></i> Tambah Template</button></div>',
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
                var searchInput = $('#tblTemplate_filter input');
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
                        '<tr><td colspan="4" class="text-center p-5">' +
                        '<i class="fas fa-file-alt fa-3x text-muted mb-3 d-block"></i>' +
                        '<p class="lead text-muted">Belum ada template yang tersedia</p>' +
                        '<p class="text-muted">Ayo buat template pertamamu!</p>' +
                        '<button type="button" class="btn btn-primary mt-2" onclick="ModalTemplate()">' +
                        '<i class="fas fa-plus"></i> Tambah Template' +
                        '</button></td></tr>';
                    $('tbody', api.table().node()).html(emptyHtml);
                }
            }
        });
        window._templateTable = table;
    } else {
        window._templateTable.ajax.reload();
    }
}

function TampilJenisSurat() {
    if (!$('#tblJenis').hasClass('dataTable')) {
        var table = $('#tblJenis').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            "ajax": {
                "url": "get_jenis_surat_datatables",
                "type": "POST"
            },
            "columns": [
                { "data": 0, "orderable": false, "searchable": false },
                { "data": 2, "orderable": true },
                {
                    "data": 1,
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row) {
                        return '<button class="btn btn-sm btn-warning mr-1" onclick="ModalJenis(' + data + ')" title="Edit"><i class="fas fa-edit"></i></button>' +
                            '<button class="btn btn-sm btn-danger hapus-jenis-btn" data-id="' + data + '" title="Hapus"><i class="fas fa-trash"></i></button>';
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
                "emptyTable": '<div class="text-center py-5"><i class="fas fa-tags fa-3x text-muted mb-3"></i><p class="lead text-muted">Belum ada jenis surat</p><p class="text-muted">Klik tombol di atas untuk menambah jenis surat baru.</p><button type="button" class="btn btn-outline-primary mt-2" onclick="ModalJenis()"><i class="fas fa-plus"></i> Tambah Jenis Surat</button></div>'
            },
            "drawCallback": function (settings) {
                var api = this.api();
                if (api.rows({ search: 'applied' }).data().length === 0) {
                    var emptyHtml =
                        '<tr><td colspan="3" class="text-center p-5">' +
                        '<i class="fas fa-tags fa-3x text-muted mb-3 d-block"></i>' +
                        '<p class="lead text-muted">Belum ada jenis surat</p>' +
                        '<p class="text-muted">Klik tombol di bawah ini untuk menambah jenis surat baru.</p>' +
                        '<button type="button" class="btn btn-outline-primary mt-2" onclick="ModalJenis()">' +
                        '<i class="fas fa-plus"></i> Tambah Jenis Surat</button></td></tr>';
                    $('tbody', api.table().node()).html(emptyHtml);
                }
            }
        });
        window._jenisTable = table;
    } else {
        window._jenisTable.ajax.reload();
    }
}

function ModalTambahTemplate() {
    $('#template_id').val('');
    $('#nama_template').val('');
    $('#jenis_surat').val('');
    $('#ukuran_kertas').val('');
    setEditorIsiTemplateContent('');
    $('#modalTemplateTitle').text('Tambah Template');
    loadJenisSuratOptions();
    $('#templateModal').modal('show');
}

function ModalJenis(id = null) {
    $.ajax({
        url: 'get_jenis_surat_by_id',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
            $('#jenis_id').val('');
            $('#nama_jenis').val('');
            $('#kode_penetapan').val('');
            $('#modalJudulTitle').html('');

            $('#jenis_id').val(res.data.id);
            $('#nama_jenis').val(res.data.jenis_surat);
            $('#modalJudulTitle').html(res.data.judul);
            $('#kode_penetapan').val(res.data.kode_penetapan);
            $('#jenisModal').modal('show');
        }
    });
}

function SimpanJenis() {
    var id = $('#jenis_id').val();
    var nama = $.trim($('#nama_jenis').val());
    var kode = $.trim($('#kode_penetapan').val());

    if (!nama) {
        toastr.error('Nama jenis surat wajib diisi!');
        return;
    }

    $.ajax({
        url: 'simpan_jenis_surat',
        type: 'POST',
        data: { id: id, jenis_surat: nama, kode: kode },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                toastr.success('Data berhasil disimpan!');
                $('#jenisModal').modal('hide');
                window._jenisTable.ajax.reload();
            } else {
                toastr.error(res.message || 'Gagal menyimpan data.');
            }
        },
        error: function () {
            toastr.error('Terjadi kesalahan saat menyimpan.');
        }
    });
}

function ModalTemplate(id = null) {
    $.ajax({
        url: 'get_template_by_id',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
            $('#template_id').val('');
            $('#nama_template').val('');
            $('#div_jenis').html('');
            $('#modalTemplateTitle').html('');

            $('#template_id').val(res.data.id);
            $('#nama_template').val(res.data.nama_template);
            $('#modalTemplateTitle').append(res.data.judul);
            $('#div_jenis').append(res.data.jenis_surat);

            $('#jenis_surat').select2({
                theme: 'bootstrap4',
                width: '100%',
                dropdownParent: $('#templateModal'),
                allowClear: true
            });

            $(document).on('focusin', function (e) {
                if ($(e.target).closest('.tox-dialog, .tox-tinymce-aux').length) {
                    e.stopImmediatePropagation();
                }
            });

            if (tinymce.get('isi_template')) {
                tinymce.get('isi_template').remove();
            }

            tinymce.init({
                selector: '#isi_template',
                license_key: 'gpl',
                height: 800,

                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline | code | align lineheight | numlist bullist | removeformat',
                font_family_formats: 'Arial=sans-serif;Bookman Old Style=bookman old style;Times New Roman=times new roman;Courier New=courier new;Georgia=georgia,palatino;Helvetica=helvetica',

                content_style: `
                    body { 
                        font-family: Bookman Old Style, sans-serif; 
                        font-size: 12pt; 
                    }

                    .kop-surat { 
                        width: 100%; 
                        margin-bottom: 20px; 
                        text-align: center; 
                    } 
                        
                    .kop-surat img { 
                        width: 100%; 
                        height: auto; 
                        display: block; 
                    } 
                `,

                setup: function (editor) {
                    editor.on('init', function () {
                        // This code runs only when the editor is fully ready
                        editor.setContent(res.data.isi_template || '');
                    });
                }
            });

            $('#templateModal').modal('show');
        }
    });
}

function PreviewTemplate(id) {
    $.ajax({
        url: 'get_template',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
            if (!res.success || !res.data) return;
            $('#previewContent').html(res.data.isi_template);
            $('#previewTemplateModal').modal('show');
        }
    });
}

function SimpanTemplate() {
    var id = $('#template_id').val();
    var nama = $.trim($('#nama_template').val());
    var jenis = $('#jenis_surat').val();
    var isi = getEditorIsiTemplateContent();

    if (!nama || !jenis) {
        toastr.error('Semua field wajib diisi!');
        return;
    }

    $.ajax({
        url: 'simpan_template',
        type: 'POST',
        data: { id: id, nama_template: nama, jenis_surat: jenis, ukuran_kertas: $('#ukuran_kertas').val(), isi_template: isi },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                toastr.success('Template berhasil disimpan!');
                $('#templateModal').modal('hide');
                window._templateTable.ajax.reload();
            } else {
                toastr.error(res.message || 'Gagal menyimpan template.');
            }
        },
        error: function () {
            toastr.error('Terjadi kesalahan saat menyimpan.');
        }
    });
}

function ModalKlasifikasi(id = null) {
    $.ajax({
        url: 'get_klasifikasi_surat_by_id',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
            $('#klasifikasi_id').val('');
            $('#kode_klasifikasi').val('');
            $('#jenis_klasifikasi').val('');
            $('#keterangan').val('');
            $('#klasifikasiJudul').html('');

            $('#klasifikasi_id').val(res.data.id);
            $('#kode_klasifikasi').val(res.data.kode);
            $('#klasifikasiJudul').html(res.data.judul);
            $('#jenis_klasifikasi').val(res.data.jenis);
            $('#keterangan').val(res.data.keterangan);
            $('#klasifikasiModal').modal('show');
        }
    });
}

function SimpanKlasifikasi() {
    var id = $('#klasifikasi_id').val();
    var kode = $.trim($('#kode_klasifikasi').val());
    var jenis = $.trim($('#jenis_klasifikasi').val());
    var ket = $.trim($('#keterangan').val());

    if (!kode || !jenis || !ket) {
        toastr.error('Masih ada isian yg kosong !');
        return;
    }

    $.ajax({
        url: 'simpan_klasifikasi_surat',
        type: 'POST',
        data: { id: id, kode: kode, jenis: jenis, ket: ket },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                toastr.success('Data berhasil disimpan!');
                $('#klasifikasiModal').modal('hide');
                window._klasifikasiTable.ajax.reload();
            } else {
                toastr.error(res.message || 'Gagal menyimpan data.');
            }
        },
        error: function () {
            toastr.error('Terjadi kesalahan saat menyimpan.');
        }
    });
}

function getEditorIsiTemplateContent() {
    var ed = tinymce.get('isi_template');
    return ed ? ed.getContent() : ($('#isi_template').val() || '');
}

function setEditorIsiTemplateContent(html) {
    var ed = tinymce.get('isi_template');
    if (ed) {
        ed.setContent(html || '');
    } else {
        $('#isi_template').val(html || '');
    }
}

function loadJenisSuratOptions(preselect) {
    $.ajax({
        url: 'get_data_jenis_surat',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            jenisSuratCache = data;
            var sel = $('#jenis_surat');
            sel.empty().append('<option value="">-- Pilih Jenis Surat --</option>');
            $.each(data, function (i, j) {
                sel.append('<option value="' + j.id + '">' + escapeHtml(j.jenis_surat) + '</option>');
            });
            if (preselect !== undefined) {
                sel.val(preselect);
            }
        }
    });
}

function escapeHtml(str) {
    return $('<span>').text(str).html();
}