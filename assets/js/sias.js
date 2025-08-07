$(function () {
    var chkAgenda = document.getElementById('chkAgenda');

    if (chkAgenda) { // Cek apakah elemen dengan id 'chkAgenda' ada
        chkAgenda.addEventListener('change', function () {
            var noAgendaInput = document.getElementById('no_agenda');
            if (this.checked) {
                noAgendaInput.removeAttribute('readonly');
            } else {
                noAgendaInput.setAttribute('readonly', true);
            }
        });
    }

    $('#detilModal').on('shown.bs.modal', function () {
        // Mengatur tab aktif ke data_surat_tab
        $('#data_surat_tab').tab('show');
    });

    $(document).on("click", "#hapus", function () {
        var id = $(this).data('id');
        $('#hapusSM').attr('href', 'hapus_sm/' + id);
    })

    $(document).on('submit', '#formLaporanSM', function (e) {
        e.preventDefault(); // Cegah reload

        let tgl_awal = $('#tgl_awal').val();
        let tgl_akhir = $('#tgl_akhir').val();

        console.log('Tgl Awal ' + tgl_awal + ', Tgl Akhir ' + tgl_akhir);
        $.ajax({
            url: 'halamansuratmasuk/filter_laporan_surat_masuk', // ganti sesuai route controller Anda
            method: 'POST',
            dataType: 'json',
            data: {
                tgl_awal: tgl_awal,
                tgl_akhir: tgl_akhir
            },
            beforeSend: function () {
                $('#tblLaporanSM tbody').html('<tr><td colspan="7" class="text-center">Memuat data...</td></tr>');
            },
            success: function (response) {
                if (response.status == 'success') {
                    let rows = '';
                    let no = 1;
                    if (response.data.length > 0) {
                        response.data.forEach(item => {
                            rows += `<tr>
                                <td>${no++}</td>
                                <td>${item.no_agenda}</td>
                                <td>${item.no_sm}</td>
                                <td>${item.pengirim}</td>
                                <td>${item.perihal}</td>
                                <td>${item.tgl_surat}</td>
                                <td>${item.tgl_terima}</td>
                            </tr>`;
                        });
                    } else {
                        rows = `<tr><td colspan="7" class="text-center">Tidak ada data ditemukan.</td></tr>`;
                    }
                    $('#tblLaporanSM tbody').html(rows);
                } else {
                    alert(response.message || 'Gagal memuat data');
                }
            },
            error: function () {
                alert('Terjadi kesalahan saat memproses data.');
            }
        });
    });

    $(document).on('submit', '#formSM', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        $.ajax({
            url: 'simpan_sm',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#tambah-modal').modal('hide');
                    toastr.success(res.message);
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    loadPage('arsip_sm');
                } else {
                    toastr.error(res.message);
                }
            },
            error: function () {
                toastr.error('Terjadi kesalahan saat menyimpan data.');
            }
        });
    });

    $(document).on('submit', '#formPeran', function (e) {
        e.preventDefault();
        let form = this;
        let formData = new FormData(form);

        $.ajax({
            url: 'simpan_peran',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    toastr.success(res.message);
                    ModalRole('-1');
                } else {
                    toastr.error(res.message);
                }
            },
            error: function () {
                toastr.error('Terjadi kesalahan saat menyimpan data.');
            }
        });
    });
});

function loadPage(page) {
    cekToken();
    $('#app').html('<div class="text-center p-4">Memuat...</div>');
    $.get("halamanutama/page/" + page, function (data) {
        $('#app').html(data);
    }).fail(function () {
        $('#app').html('<div class="text-danger">Halaman tidak ditemukan.</div>');
    });
}

function cekToken() {
    $.ajax({
        url: 'cek_token',
        type: 'POST',
        dataType: 'json',
        success: function (res) {
            if (!res.valid) {
                alert(res.message);
                window.location.href = res.url;
            }
        }
    });
}

function getNotifSuratMasuk() {
    var total = $('#total');
    var valid = $('#validasi');
    var surat_masuk = $('#surat_masuk');
    var disposisi = $('#disposisi');
    var icon_sm = $('#surat_masuk_icon');
    var icon_disposisi = $('#disposisi_icon');
    valid.empty();
    surat_masuk.empty();
    disposisi.empty();
    var notif = 0;

    // Gunakan $.when untuk menunggu semua AJAX selesai
    $.when(
        $.ajax({
            url: 'get_validasi',
            method: 'GET',
            dataType: 'json'
        }),
        $.ajax({
            url: 'get_surat_masuk',
            method: 'GET',
            dataType: 'json'
        }),
        $.ajax({
            url: 'get_disposisi',
            method: 'GET',
            dataType: 'json'
        })
    ).done(function (validasi, suratmasuk, dispo) {
        // Handle hasil dari 'get_validasi'
        try {
            if (Array.isArray(validasi[0]) && validasi[0].length > 0) {
                notif += validasi[0].length;
                valid.append(validasi[0].length);
            }
        } catch (e) {
            console.error("Error parsing validasi data:", e);
        }

        // Handle hasil dari 'get_surat_masuk'
        try {
            if (Array.isArray(suratmasuk[0]) && suratmasuk[0].length > 0) {
                notif += suratmasuk[0].length;
                surat_masuk.append(suratmasuk[0].length);
                icon_sm.append(suratmasuk[0].length);
            }
        } catch (e) {
            console.error("Error parsing surat masuk data:", e);
        }

        // Handle hasil dari 'get_disposisi'
        try {
            if (Array.isArray(dispo[0]) && dispo[0].length > 0) {
                notif += dispo[0].length;
                disposisi.append(dispo[0].length);
                icon_disposisi.append(dispo[0].length);
            }
        } catch (e) {
            console.error("Error parsing disposisi data:", e);
        }

        // Perbarui elemen setelah semua request selesai
        total.empty();

        if (notif > 0) {
            total.append(notif);
        }

    }).fail(function (xhr, status, error) {
        console.error("AJAX request failed:", status, error);
    });
}

var result = config.result;
var pesan = config.pesan;
if (result != '-1') {
    if (result == '1') {
        sukses(pesan);
    } else if (result == '2') {
        peringatan(pesan);
    } else {
        gagal(pesan);
    }
}

function sukses(pesan) {
    swal({
        title: "<h4>Sukses<h4>",
        type: "success",
        text: "<h5>" + pesan + "</h5>",
        html: true
    });
}

function peringatan(pesan) {
    swal({
        title: "<h4>Oops...<h4>",
        type: "warning",
        text: "<h5>" + pesan + "</h5>",
        html: true
    });
}

function gagal(pesan) {
    swal({
        title: "<h4>Oops...<h4>",
        type: "error",
        text: "<h5>" + pesan + "</h5>",
        html: true
    });
}

function CariPegawai(id) {
    $.post('<?= base_url() ?>cari_pegawai', {
        id: id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#dispo").html('');
            $("#dispo").append(json.fullname);
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function EditUser(id) {
    $.post('edit_klas_user', {
        id: id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#judul").html("");
            $("#level_").html('');
            $("#sm_").html('');
            $("#sk_").html('');
            $("#arsipsm_").html('');
            $("#arsipsk_").html('');
            $("#arsipfile_").html('');
            $("#repsm_").html('');
            $("#repsk_").html('');
            $("#repprog_").html('');
            $("#repdispo_").html('');
            $("#reparsip_").html('');
            $("#klas_").html('');
            $("#siasuser_").html('');

            $("#judul").append(json.judul);
            $("#level_").append(json.level);
            $("#sm_").append(json.sm);
            $("#sk_").append(json.sk);
            $("#sm, #sk").select2({
                dropdownParent: $('#tambah-modal')
            });
            $("#arsipsm_").append(json.arsipsm);
            $("#arsipsk_").append(json.arsipsk);
            $("#arsipfile_").append(json.arsipfile);
            $("#repsm_").append(json.repsm);
            $("#repsk_").append(json.repsk);
            $("#repprog_").append(json.repprog);
            $("#repdispo_").append(json.repdispo);
            $("#reparsip_").append(json.reparsip);
            $("#klas_").append(json.klas);
            $("#siasuser_").append(json.siasuser);
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function ModalKlasifikasi(id) {
    $.post('edit_klas', {
        id: id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#judul").html("");
            $("#kode").val('');
            $("#jenis").val('');
            $("#ket").val('');
            $("#id").val('');
            $("#judul").append(json.judul);
            $("#kode").val(json.kode);
            $("#jenis").val(json.jenis);
            $("#ket").val(json.ket);
            $("#id").val(json.id);
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function ModalKlasifikasiArsip(id) {
    $.post('edit_klas_arsip', {
        id: id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#judul").html("");
            $("#jenis").val('');
            $("#judul").append(json.judul);
            $("#jenis").val(json.jenis);
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function ModalInputSurat(id) {
    $('#tambah-modal').modal('show');
    $.post('show_sm', {
        id: id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        document.getElementById('overlay').remove();
        if (json.st == 1) {
            $("#id").val('');
            $("#judul").html("");
            $("#no_agenda").val('');
            $("#pengirim").val('');
            $("#no_surat").val('');
            $("#perihal").val('');
            $("#tgl_surat").val('');
            $("#tgl_terima").val('');
            $("#ket").val('');
            $("#file").val('');
            $("#judul").append(json.judul);
            $("#no_agenda").val(json.no_agenda);
            $("#pengirim").val(json.pengirim);
            $("#no_surat").val(json.no_surat);
            $("#perihal").val(json.perihal);
            $("#id").val(json.id);
            $("#tgl_surat").val(json.tgl_surat);
            $("#tgl_terima").val(json.tgl_terima);
            $("#ket").val(json.ket);
            $("#file").val(json.file);

            $('#tglsurat, #tglterima').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            //custom file input
            bsCustomFileInput.init();
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function BukaDetilSurat(status, id) {
    $.post('show_detil_sm', {
        status: status, id: id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#judul_detil").html("");
            $("#register_id_detil").val("");
            $("#dokumen_detil").html("");
            $("#nomor_agenda_detil").html("");
            $("#nomor_surat_detil").html("");
            $("#pengirim_detil").html("");
            $("#perihal_detil").html("");
            $("#ket_detil").html("");
            $("#tanggal_surat_detil").html("");
            $("#tanggal_terima_detil").html("");

            $("#judul_detil").append(json.judul);
            $("#register_id_detil").val(json.id);
            $("#nomor_agenda_detil").append(json.no_agenda);
            $("#nomor_surat_detil").append(json.no_sm);
            $("#pengirim_detil").append(json.pengirim);
            $("#perihal_detil").append(json.perihal);
            if (json.ket == "") {
                $("#ket_detil").append("-");
            } else {
                $("#ket_detil").append(json.ket);
            }
            $("#dokumen_detil").append(json.dokumen);
            $("#tanggal_surat_detil").append(json.tgl_surat);
            $("#tanggal_terima_detil").append(json.tgl_terima);

            if (json.tab_dispo == '0') {
                $("#riwayat_disposisi_tab").hide();
                $("#riwayat_disposisi").hide();
            }
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function TampilProgresSurat() {
    $.ajax({
        url: 'get_progres_surat_masuk',
        method: 'POST',
        data: {
            suratId: $('#register_id_detil').val()
        },
        success: function (data) {
            $('#tambah_pelaksanaan').hide();
            var progres = JSON.parse(data);
            var div_surat = $('#progres_surat');
            div_surat.empty();
            //console.log(progres);
            // Mengecek apakah queues adalah array atau objek
            if (progres.length > 0) {
                div_surat.append('<div class="text-center"><h5>PROGRES SURAT</h5></div>');
                progres.forEach(function (prog) {
                    var status;
                    switch (prog.status) {
                        case '1': status = 'Diteruskan'; break;
                        case '2': status = 'Disposisi'; break;
                        case '3': status = 'Dilaksanakan'; break;
                        case '4': status = 'Selesai'; break;
                        default: status = 'Status tidak dikenal';
                    }

                    var ket = 'Keterangan : ' + prog.ket;

                    if (prog.jab_tujuan) {
                        div_surat.append('<div>Status Surat : <strong>' + status + '</strong> ke ' + prog.jab_tujuan + '<br> Oleh : <strong>' + prog.created_by + '</strong> -- <i>' + prog.created_on + '</i><br>' + ket + '</div><hr/>');
                    } else {
                        div_surat.append('<div>Status Surat : <strong>' + status + '</strong><br>' + prog.jabatan + ' -- <i>' + prog.created_on + '</i><br>' + ket + '</div><hr/>');
                    }

                    status_progres = prog.status;
                });

                var div_aksi = $('#btn_aksi');
                div_aksi.empty();
                if (status_progres < '4') {
                    div_aksi.append('<button onclick="TampilPelaksanaan()" id="TombolTambahPelaksanaan" class="btn btn-sm btn-success">Tambah</button>');
                }
            } else {
                div_surat.append('<div class="text-center">Progres Surat Belum Ada</div>');
            }
        }
    });
}

function TampilRiwayatDisposisi() {
    $.ajax({
        url: 'get_riwayat_disposisi',
        method: 'POST',
        data: {
            suratId: $('#register_id_detil').val()
        },
        success: function (data) {
            var riwayat = JSON.parse(data);
            var div_surat = $('#riwayat_disposisi');
            div_surat.empty();
            //console.log(progres);
            // Mengecek apakah queues adalah array atau objek
            if (riwayat.length > 0) {
                div_surat.append('<div class="text-center"><h5>DISPOSISI SURAT</h5></div>');
                riwayat.forEach(function (rwyt) {
                    var ket = 'Catatan Disposisi : -';

                    if (rwyt.catatan != null) {
                        ket = 'Catatan Disposisi : <strong>' + rwyt.catatan + '</strong>';
                    }

                    div_surat.append('<div>Tujuan Disposisi : <strong>' + rwyt.nama + '</strong><br>' + ket + '<br><button onclick="CetakDisposisi(' + rwyt.id + ')" class="btn btn-sm btn-success">Lembar Disposisi</button></div><hr/>');
                });
            } else {
                div_surat.append('<div class="text-center">Belum Ada Riwayat Disposisi</div>');
            }
        }
    });
}

function CetakDisposisi(id) {
    var register_id = id;
    console.log(register_id);
    // Buat formulir dengan jQuery
    var form = $('<form action="cetak_disposisi" target="_blank" method="post">' +
        '<input type="hidden" name="register_id" value="' + register_id + '">' +
        '</form>');

    // Menambahkan formulir ke dalam dokumen
    $('body').append(form);

    // Mengirimkan formulir
    form.submit();

    // Menghapus formulir setelah pengiriman untuk kebersihan
    form.remove();
}

function TampilPelaksanaan() {
    var register_id = $('#register_id_detil').val();
    $.post('edit_status_surat_masuk', {
        register_id: register_id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $("#disposisi_").html('');
            $("#pelaksanaan_").html('');
            $("#pelaksanaan_id").val('');
            $("#disposisi_").append(json.jenis_jabatan);
            $("#pelaksanaan_").append(json.jenis_progres);
            $("#pelaksanaan_id").val(json.pelaksanaan_id);
            $("#jenis_jabatan").select2({
                theme: 'bootstrap4'
            });
            $('#tambah_pelaksanaan').show();
            $('.disposisi_surat').hide();
        } else if (json.st == 0) {
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function JenisPelaksanaan() {
    var jenis_progres = $('#jenis_progres').val();
    if (jenis_progres == 2) {
        //console.log(jenis_progres);
        $('.disposisi_surat').show();
    } else {
        $('.disposisi_surat').hide();
    }
}

function SimpanPelaksanaan() {
    var register_id = $('#register_id_detil').val();
    var pelaksanaan_id = $('#pelaksanaan_id').val();
    var jenis_jabatan = $('#jenis_jabatan').val();
    var keterangan = $('#keterangan_pelaksanaan').val();
    var jenis_progres = $('#jenis_progres').val();

    $('#tombol_simpan_disposisi').attr("disabled", true);
    //console.log(register_id+", "+pelaksanaan_id+", "+jenis_jabatan+", "+keterangan);
    $.post('simpan_validasi_surat_masuk', {
        pelaksanaan_id: pelaksanaan_id,
        jenis_jabatan: jenis_jabatan,
        keterangan: keterangan,
        register_id: register_id,
        jenis_progres: jenis_progres
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.success) {
            $('#detilModal').modal('hide');
            $('#tambah_pelaksanaan').hide();
            toastr.success(json.message);
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            $('#tombol_simpan_disposisi').removeAttr("disabled");
            //TampilProgresSurat();
            getNotifSuratMasuk();
            loadPage('validasi_sm');
        } else {
            toastr.error(json.message);
            getNotifSuratMasuk();
            $('#tombol_simpan_disposisi').removeAttr("disabled");
        }
    });
}

function SimpanPelaksanaanSM() {
    var register_id = $('#register_id_detil').val();
    var pelaksanaan_id = $('#pelaksanaan_id').val();
    var jenis_progres = $('#jenis_progres').val();
    var jenis_jabatan = $('#jenis_jabatan').val();
    var keterangan = $('#keterangan_pelaksanaan').val();

    $('#tombol_simpan_disposisi').attr("disabled", true);
    //console.log(register_id+", "+pelaksanaan_id+", "+jenis_jabatan+", "+keterangan);
    $.post('simpan_pelaksanaan_surat_masuk', {
        pelaksanaan_id: pelaksanaan_id,
        jenis_progres: jenis_progres,
        jenis_jabatan: jenis_jabatan,
        keterangan: keterangan,
        register_id: register_id
    }, function (response) {
        var json = jQuery.parseJSON(response);
        if (json.success) {
            $('#detilModal').modal('hide');
            $('#tambah_pelaksanaan').hide();
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            toastr.success(json.message);
            $('#tombol_simpan_disposisi').removeAttr("disabled");
            getNotifSuratMasuk();
        } else {
            toastr.error(json.message);
            getNotifSuratMasuk();
            $('#tombol_simpan_disposisi').removeAttr("disabled");
        }
    });
}

function TutupPelaksanaan() {
    $("#keterangan").val("");
    $('#tambah_pelaksanaan').hide();
}

function loadPegawai() {
    $.post('show_all_pegawai', function (response) {
        var json = jQuery.parseJSON(response);
        if (json.st == 1) {
            $('#dataModal').modal('show');
            $("#pegawai_").html('');

            $("#pegawai_").append(json.pegawai);
        } else if (json.st == 0) {
            pesan('PERINGATAN', json.msg, '');
            $('#table_pegawai').DataTable().ajax.reload();
        }
    });
}

function ModalRole(id) {
    $('#role-pegawai').modal('show');
    if (id != '-1') {
        $('#tabel-role').html('');
    }

    $.post('show_role',
        { id: id },
        function (response) {
            try {
                const json = JSON.parse(response); // pastikan response valid JSON
                $('#pegawai_').html('');

                let html = `<select class="form-control select2" id="pegawai" name="pegawai" style="width:100%">`;
                json.pegawai.forEach(row => {
                    html += `<option value="${row.userid}" data-nama="${row.fullname}" data-jabatan="${row.jabatan}">${row.fullname}</option>`;
                });
                html += `</select>`;
                $('#pegawai_').append(html);

                $('#peran_').html('');
                let role = `<select class="form-control select2" id="peran" name="peran" style="width:100%">`;
                role += `<option value="eselon_iii">Administrator Satker</option>`;
                role += `<option value="penelaah">Penelaah Persuratan</option>`;
                role += `<option value="petugas">Petugas Persuratan</option>`;
                role += `</select>`;
                $('#peran_').append(role);

                $('#overlay').hide();

                $('#pegawai').select2({
                    theme: 'bootstrap4',
                    dropdownParent: $('#role-pegawai'),
                    width: '100%',
                    placeholder: "Pilih pegawai",
                    templateResult: formatPegawaiOption,
                    templateSelection: formatPegawaiSelection
                });

                $('#peran').select2({
                    theme: 'bootstrap4',
                    dropdownParent: $('#role-pegawai'),
                    width: '100%',
                    placeholder: "Pilih Peran"
                });

                if (id != '-1') {
                    $('#id').val('');

                    $('#id').val(json.id);
                    $('#pegawai').val(json.editPegawai).trigger('change');
                    $('#peran').val(json.editPeran).trigger('change');

                    $('#pegawai').on('select2:opening select2:selecting', function (e) {
                        e.preventDefault(); // mencegah dropdown terbuka
                    });
                } else {
                    $('#tabel-role').html('');

                    let data = `
                    <table id="tabelPeran" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead><tbody>`;
                    json.data_peran.forEach(row => {
                        if (`${row.peran}` == 'admin_satker') {
                            var peran = 'Administrator Satker';
                        } else if (`${row.peran}` == 'penelaah') {
                            var peran = 'Penelaah Surat';
                        } else {
                            var peran = 'Petugas Surat';
                        }
                        data += `
                        <tr>
                            <td>${row.nama}</td>
                            <td>`;


                        if (`${row.hapus}` == '0') {
                            data += `<span class='badge bg-success'>${peran}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-xs bg-orange" id="editPeran" onclick="ModalRole('${row.id}')" title="Edit Peran">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button type="button" class="btn btn-xs bg-red" id="hapusPeran" onclick="blokPeran('${row.id}')" title="Blok Pegawai">
                                    <i class="fas fa-ban"></i>
                                </button>`;
                        } else {
                            data += `<span class='badge bg-secondary'>${peran}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-xs bg-success" id="hapusPeran" onclick="aktifPeran('${row.id}')" title="Aktifkan Pegawai">
                                    <i class="fas fa-check"></i>
                                </button>`;
                        }
                        data += `
                            </td>
                        </tr>`;
                    });
                    data += `
                        </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <span class='badge bg-success'>Aktif</span>
                        <span class='badge bg-secondary'>Non-aktif</span>
                    </div>`;
                    $('#tabel-role').append(data);
                    $("#tabelPeran").DataTable({
                        "responsive": true, "lengthChange": false, "autoWidth": false
                    }).buttons().container().appendTo('#tabelPeran_wrapper .col-md-6:eq(0)');
                }
            } catch (e) {
                console.error("Gagal parsing JSON:", e);
                $('#pegawai_').html('<div class="alert alert-danger">Gagal memuat data pegawai.</div>');
            }
        }
    );
}

function aktifPeran(id) {
    Swal.fire({
        title: "Yakin ingin mengaktifkan kembali peran pegawai?",
        text: "Data peran ini akan diaktifkan perannya.",
        icon: "warning", // ⬅️ gunakan 'icon' bukan 'type'
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, aktifkan!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            // Eksekusi penghapusan setelah konfirmasi
            $.post('aktif_peran', { id: id }, function (response) {
                Swal.fire("Berhasil!", "Peran telah diaktifkan.", "success");
                ModalRole('-1');
            }).fail(function () {
                Swal.fire("Gagal", "Terjadi kesalahan saat mengaktifkan data.", "error");
            });
        }
    });
}

function blokPeran(id) {
    Swal.fire({
        title: "Yakin ingin menonaktifkan peran pegawai?",
        text: "Data peran ini akan dinonaktifkan perannya.",
        icon: "warning", // ⬅️ gunakan 'icon' bukan 'type'
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Ya, nonaktifkan!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            // Eksekusi penghapusan setelah konfirmasi
            $.post('blok_peran', { id: id }, function (response) {
                Swal.fire("Berhasil!", "Peran telah dinonaktifkan.", "success");
                ModalRole('-1');
            }).fail(function () {
                Swal.fire("Gagal", "Terjadi kesalahan saat menghapus data.", "error");
            });
        }
    });
}

function formatPegawaiOption(option) {
    if (!option.id) return option.text;

    const nama = $(option.element).data('nama');
    const jabatan = $(option.element).data('jabatan');

    return $(`
        <div style="line-height:1.2">
            <div style="font-weight:bold;">${nama}</div>
            <div style="font-size:12px; color:#555;">${jabatan}</div>
        </div>
    `);
}

// Menampilkan teks terpilih di kotak select
function formatPegawaiSelection(option) {
    if (!option.id) return option.text;

    const nama = $(option.element).data('nama');
    const jabatan = $(option.element).data('jabatan');

    return `${nama} > ${jabatan}`;
}