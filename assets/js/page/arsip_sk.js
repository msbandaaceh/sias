$(function () {
    $(document).on('submit', '#formSK', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        $.ajax({
            url: 'simpan_sk',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    toastr.success(res.message);

                    $('#modalSuratKeluar').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    window._arsipSKTable.ajax.reload();
                } else {
                    toastr.error(res.message);
                }
            },
            error: function () {
                toastr.error('Terjadi kesalahan saat menyimpan data.');
            }
        });
    });

    $(document).on('click', '.hapus-sk-btn', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Surat Keluar akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d73a49',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'YA, HAPUS'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'hapus_sk',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            toastr.success('Surat Keluar berhasil dihapus.');
                            window._arsipSKTable.ajax.reload();
                        } else {
                            toastr.error('Gagal menghapus Surat Keluar.');
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

function TampilArsipSuratKeluar() {
    if (!$('#tblArsipSK').hasClass('dataTable')) {
        var table = $("#tblArsipSK").DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            "ajax": {
                "url": "arsip_sk_datatables",
                "type": "POST"
            },
            "columns": [
                { "data": 0, "orderable": false, "searchable": false },
                {
                    "data": 2,
                    "orderable": true,
                    "render": function (data, type, row) {
                        return (data && data.trim() !== '')
                            ? data
                            : '<span class="badge badge-warning">Belum Valid</span>';
                    }
                },
                {
                    "data": null,
                    "orderable": false,
                    "searchable": true,
                    "render": function (_data, _type, row) {
                        var perihal = row[3] || '';
                        var oleh = row[5] || '';
                        var badgeClass = 'badge-info';
                        var html = perihal;
                        if (window.userRole === 'admin') html += '<hr><span class="badge ' + badgeClass + ' p-1">Dibuat oleh: ' + oleh + '</span>';
                        return html;
                    }
                },
                { "data": 4, "orderable": true },
                {
                    "data": 1,
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row) {
                        var btns = '';
                        btns += '<button class="btn btn-sm btn-info mr-1" onclick="PreviewSuratKeluar(\'' + data + '\')" title="Preview"><i class="fas fa-eye"></i></button> ';
                        btns += '<button class="btn btn-sm btn-warning mr-1" onclick="EditSuratKeluar(\'' + data + '\')" title="Edit"><i class="fas fa-edit"></i></button> ';
                        if (window.userRole === 'admin' || window.userRole === 'penelaah') {
                            btns += '<button class="btn btn-sm btn-danger hapus-sk-btn" data-id="' + data + '" title="Hapus"><i class="fas fa-trash"></i></button>';
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
                "emptyTable": '<div class="text-center py-5"><i class="fas fa-folder-open fa-3x text-muted mb-3"></i><p class="lead text-muted">Belum ada arsip surat keluar</p></div>'
            }
        });
        window._arsipSKTable = table;
    } else {
        window._arsipSKTable.ajax.reload();
    }
}

function PreviewSuratKeluar(id) {
    $.ajax({
        url: 'preview_surat_keluar',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                var d = res.data;
                var isi = d.isi_surat;
                console.log(d.jenis_kertas);
                $('#preview_ukuran_kertas').val('');
                $('#preview_konten').html(isi);

                // Set ukuran kertas
                var sizeClass = d.jenis_kertas === 'F4' ? 'page-f4' : 'page-a4';
                $('#preview_body').attr('class', 'page-content ' + sizeClass);
                $('#preview_ukuran_kertas').val(d.jenis_kertas);
                $('#previewModal').modal('show');
            } else {
                toastr.error(res.message || 'Gagal memuat preview');
            }
        },
        error: function () {
            toastr.error('Terjadi kesalahan saat memuat preview');
        }
    });
}

function EditSuratKeluar(encryptedId) {
    loadPage('surat_keluar', encryptedId);
}

function CetakSuratKeluar() {
    var ukuran = $('#preview_ukuran_kertas').val().trim();
    console.log(ukuran);
    var isF4 = ukuran === 'F4';
    console.log('Ukuran kertas:', ukuran);
    var pageWidth = isF4 ? '215mm' : '210mm';
    var pageHeight = isF4 ? '330mm' : '297mm';

    var konten = $('#preview_konten').html();

    var win = window.open('', '_blank', 'width=900,height=700');
    win.document.write('<html><head><title>Cetak Surat Keluar</title>');
    win.document.write('<style>');
    win.document.write('@page { size: ' + pageWidth + ' ' + pageHeight + '; margin: 2cm 2.5cm 2cm 3cm; }');
    win.document.write('body { font-family: "Bookman Old Style", serif; font-size: 12pt; line-height: 1.6; margin: 0; }');
    win.document.write('img { max-width: 100%; }');
    win.document.write('table { border-collapse: collapse; width: 100%; }');
    win.document.write('.kop-surat-preview img { width: 100%; height: 10%; display: block; margin-bottom: 20px; }');
    win.document.write('</style>');
    win.document.write('</head><body>' + konten + '</body></html>');
    win.document.close();
    win.focus();

    setTimeout(function () {
        win.print();
    }, 500);
}

function formatDateID(dateStr) {
    if (!dateStr) return '';
    var parts = dateStr.split('-');
    if (parts.length !== 3) return dateStr;
    var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    return parseInt(parts[2]) + ' ' + bulan[parseInt(parts[1]) - 1] + ' ' + parts[0];
}

function formatDateTime(dt) {
    if (!dt) return '';
    var parts = dt.split(' ');
    if (parts.length < 2) return dt;
    return formatDateID(parts[0]) + ' ' + parts[1];
}