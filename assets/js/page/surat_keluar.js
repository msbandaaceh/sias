function loadDataForEdit(id) {
    document.getElementById('toolbar_surat').style.display = 'none';
    loadJenisSurat(id);
    loadKlasifikasi(id);
    loadPegawai(id)
    setDefaultTahun();

    loadPreview(id);
}

function loadPreview(id) {
    var d;
    $('#editor-overlay').show();

    $.ajax({
        url: 'get_data_surat_keluar',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                d = res.data;
                console.log('Data for edit:', d);
                $('#record_id').val(d.id);
                if (d.body_surat) {
                    $('#ukuran_kertas').val(d.jenis_kertas);
                    $('#bulan').val(d.bulan);
                    $('#tahun').val(d.tahun);
                    $('#tgl_surat').val(d.tgl_surat);
                    $('#lokasi_surat').val(d.lokasi_surat);
                }

                if (d.template_id > 0) {
                    document.getElementById('toolbar_surat').style.display = 'block';
                }

            } else {
                toastr.error(res.message || 'Gagal memuat data surat keluar');
                $('#editor-overlay').hide();
            }
        },
        error: function () {
            toastr.error('Terjadi kesalahan saat memuat data');
            $('#editor-overlay').hide();
        },
        complete: function () {
            updatePreview(d);

            // Hide overlay
            $('#editor-overlay').hide();
        }
    });
}

function loadPegawai(sk_id) {
    $.ajax({
        url: 'get_pegawai_sk',
        type: 'POST',
        data: { sk_id: sk_id },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#select_pegawai_').html('');
                $('#select_pegawai_').append(res.selected_pegawai);
                $('#select_pegawai').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: 'Pilih Penandatangan Surat'
                });
            }
        },
        error: function () {
            console.error('Gagal memuat data pegawai');
        }
    });
}

function setDefaultTahun() {
    var currentYear = new Date().getFullYear();
    var options = '<option value="">-- Pilih Tahun --</option>';
    for (var i = currentYear - 2; i <= currentYear + 2; i++) {
        options += '<option value="' + i + '">' + i + '</option>';
    }
    $('#tahun').html(options);
}

function loadKlasifikasi(sk_id) {
    $.ajax({
        url: 'get_klasifikasi_surat_sk',
        type: 'POST',
        data: { sk_id: sk_id },
        dataType: 'json',
        success: function (res) {
            console.log(res);
            $('#klasifikasi_surat_').html('');
            $('#klasifikasi_surat_').append(res.klasifikasi_surat);
            $('#klasifikasi_surat').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Pilih Klasifikasi Surat',
                allowClear: true
            });
        }
    });
}

function PilihJenisSurat(sk_id) {
    var jenisId = $('#jenis_surat').val();

    if (!jenisId) return;

    $.ajax({
        url: 'get_templates_sk',
        type: 'GET',
        data: {
            sk_id: sk_id,
            jenis_surat: jenisId
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#jenis_template_').html('');
                $('#jenis_template_').append(res.selected_template);
                $('#select_template').select2({ theme: 'bootstrap4', width: '100%', placeholder: 'Pilih Template' });
                //if (jenisId) {
                //    $('#toolbar_surat').show();
                //}
            }
        }
    });
}

function PilihTemplateSurat() {
    var templateId = $('#select_template').val();

    // If template is already loaded and user selects a new one, ask for confirmation
    var newTemplateId = templateId;
    Swal.fire({
        title: 'Ganti Template?',
        text: 'Apakah Anda yakin ingin memuat template baru? Perubahan pada isi template saat ini akan hilang.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Muat Template Baru',
        cancelButtonText: 'Batal'
    }).then(function (result) {
        if (result.isConfirmed) {
            loadNewTemplate(newTemplateId);
        }
    });
}

function loadNewTemplate(tid) {
    $.ajax({
        url: 'get_pensign_template',
        type: 'POST',
        data: { template_id: tid },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                currentTemplate = res.data;
                //$('#toolbar_surat').show();
                // In edit mode, load the saved content; otherwise load the raw template
                updatePreview(res.data);
            }
        }
    });
}

function loadJenisSurat(sk_id) {
    $.ajax({
        url: 'get_jenis_surat_sk',
        type: 'GET',
        data: { sk_id: sk_id },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#jenis_surat_').html('');
                $('#jenis_surat_').append(res.jenis_surat);
                $('#jenis_surat').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: 'Pilih Jenis Surat'
                });
            }
        }, complete: function () {
            PilihJenisSurat(sk_id);
        }
    });
}

function SimpanSuratKeluar() {
    if (!currentTemplate) {
        toastr.error('Pilih template terlebih dahulu!');
        return;
    }

    var templateId = $('#select_template').val();
    /*
    var jenisSurat = $('#select_template').find('option:selected').data('jenis');
    var ukuranKertas = $('#ukuran_kertas_display').val();
    var kodePengirim = $('#kode_pengirim').val();
    var klasifikasiSurat = $('#klasifikasi_surat').val();
    var bulan = $('#bulan').val();
    var tahun = $('#tahun').val();
    var pegawaiLoop = $('#pegawai_loop').val();
    var lokasiSurat = $('#lokasi_surat').val();
    var tglSurat = $('#tgl_surat').val();
    */
    // Generate final body (fill visible placeholders, strip hidden wrappers)
    //updatePreview(tinymce.activeEditor.getContent());
    var isiTemplate = tinymce.activeEditor.getContent();
    // Strip all <code> wrappers before saving — keep raw {{...}} and HTML only
    //isiTemplate = isiTemplate.replace(/<code[^>]*>/g, '').replace(/<\/code>/g, '');

    // Basic validation
    if (!templateId) { toastr.error('Pilih template!'); return; }
    //if (!ukuranKertas) { toastr.error('Ukuran kertas belum terisi!'); return; }

    // Validation untuk Surat Tugas
    /*
    if (jenisSurat == '1') {
        if (!kodePengirim) { toastr.error('Isi Penandatangan Surat!'); return; }
        if (!klasifikasiSurat) { toastr.error('Pilih Klasifikasi Surat!'); return; }
        if (!bulan) { toastr.error('Pilih Bulan!'); return; }
        if (!tahun) { toastr.error('Pilih Tahun!'); return; }
        if (!pegawaiLoop || pegawaiLoop.length === 0) { toastr.error('Pilih minimal 1 Pegawai!'); return; }
        if (!lokasiSurat) { toastr.error('Isi Lokasi Surat!'); return; }
        if (!tglSurat) { toastr.error('Pilih Tanggal Surat!'); return; }
    }

    // Build pegawai_loop JSON
    var pegawaiData = [];
    if (pegawaiLoop && pegawaiLoop.length > 0) {
        pegawaiLoop.forEach(function (userid, index) {
            var pegawai = allPegawai.find(function (p) { return p.userid === userid; });
            if (pegawai) {
                pegawaiData.push({
                    no: index + 1,
                    userid: userid,
                    nama: pegawai.fullname,
                    nip: pegawai.nip || '',
                    jabatan: pegawai.jabatan || ''
                });
            }
        });
    }
    */
    var data = {
        record_id: $('#record_id').val() || 0,
        template_id: templateId,
        //jenis_surat: jenisSurat,
        //ukuran_kertas: ukuranKertas,
        //kode_pengirim: kodePengirim,
        //klasifikasi_surat: klasifikasiSurat,
        //bulan: bulan,
        //tahun: tahun,
        //lokasi_surat: lokasiSurat,
        //tgl_surat: tglSurat,
        //pegawai_loop: JSON.stringify(pegawaiData),
        isi_surat: isiTemplate
    };

    $.ajax({
        url: 'save_surat_keluar',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                toastr.success(res.message || 'Surat keluar berhasil disimpan!');
                setTimeout(function () {
                    loadPage('arsip_sk');
                }, 1500);
            } else {
                toastr.error(res.message || 'Gagal menyimpan surat keluar.');
            }
        },
        error: function () {
            toastr.error('Terjadi kesalahan saat menyimpan.');
        }
    });
}

function getTemplate(id) {
    $.ajax({
        url: 'get_template',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
            if (res.success)
                return res.data.isi_template;
        }
    });
}

function updatePreview(d) {
    // ==========================================
    // PENGATURAN KERTAS
    // ==========================================
    var paperType = d.jenis_kertas; // 'A4' atau 'F4'
    // isi_surat / isi_template sudah berisi konten final — pasrahkan
    // langsung ke editor.
    var processed = d.body_surat ? d.body_surat : '';
    var paperConfig = {
        A4: {
            width: '210mm',
            height: '297mm',
            cssSize: 'A4'
        },
        F4: {
            width: '215mm',
            height: '330mm',
            cssSize: '215mm 330mm'
        }
    };

    var paper = paperConfig[paperType] || paperConfig.A4;

    if (d.lokasi_surat) {
        processed = processed.replace(/#0002#/g, d.lokasi_surat);
    }

    if (d.tgl_surat) {
        processed = processed.replace(/#0003#/g, d.tgl_surat_text);
    }

    if (d.penandatangan_id) {
        processed = processed.replace(/#0004#/g, d.penandatangan_jabatan);
        processed = processed.replace(/#0005#/g, d.nama_satker);
        processed = processed.replace(
            /#0006#/g,
            '<img src="' + d.penandatangan_tte + '" alt="Tanda Tangan Elektronik" style="max-width: 90px; height: auto;">'
        );
        processed = processed.replace(/#0007#/g, d.penandatangan_nama);
    }

    // ==========================================
    // INIT TINYMCE
    // ==========================================
    // Idempotent: kalau editor sudah ada (reload data edit, ganti template,
    // dsb.), cukup setContent tanpa init ulang. Tinymce.init adalah ASYNC,
    // jadi setContent hanya bisa di-set lewat setup (editor sudah live).
    if (tinymce.get('isi_template_editor')) {
        tinymce.get('isi_template_editor').setContent(processed);
        return;
    }

    tinymce.init({

        selector: '#isi_template_editor',

        license_key: 'gpl',

        height: 800,

        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount code',

        // Saat editor sudah live, set konten (idempotent aman)
        setup: function (editor) {
            editor.on('init', function () {
                editor.setContent(processed);
            });
        },

        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline | code | align lineheight | numlist bullist | removeformat',

        font_family_formats:
            'Arial=arial,helvetica,sans-serif;' +
            'Bookman Old Style=bookman old style;' +
            'Times New Roman=times new roman;' +
            'Courier New=courier new,courier;' +
            'Georgia=georgia,palatino;' +
            'Helvetica=helvetica,arial,sans-serif',

        content_style: `

            /* ==========================================
               WORD-LIKE PAGE VIEW
               ========================================== */

            html {
                background: #e5e5e5;
                min-height: 100%;
            }

            body {
                box-sizing: border-box;

                width: ${paper.width};
                min-height: ${paper.height};

                margin: 30px auto !important;

                background: #ffffff;

                color: #000000;

                font-family: "Bookman Old Style", serif;
                font-size: 12pt;
                line-height: 1.5;

                box-shadow:
                    0 0 8px rgba(0, 0, 0, 0.20);

                overflow: visible;

                /*
                 * Memberikan efek seperti lembar kertas.
                 * Konten tetap dapat bertambah ke bawah.
                 */
            }


            /* ==========================================
               PARAGRAPH
               ========================================== */

            p {
                margin-top: 0;
                margin-bottom: 8pt;
            }


            /* ==========================================
               KOP SURAT
               ========================================== */

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


            /* ==========================================
               TABLE
               ========================================== */

            table {
                border-collapse: collapse;
                width: 100%;
            }

            table td,
            table th {
                vertical-align: top;
            }


            /* ==========================================
               PLACEHOLDER
               ========================================== */

            code.pv-placeholder {
                display: none;
            }


            /* ==========================================
               PAGE BREAK MANUAL
               ========================================== */

            .page-break {
                page-break-before: always;
                break-before: page;
            }

        `
    });

    // ==========================================
    // REPLACE PLACEHOLDER
    // ==========================================

    //if (d.isi_surat) {
    /*
    var kodePengirim =
        $('#kode_pengirim')
            .find('option:selected')
            .text()
            .trim();

    var kodeSurat =
        $('#klasifikasi_surat').val();

    var bulan =
        $('#bulan').val();

    var tahun =
        $('#tahun').val();

    var lokasiSurat =
        $('#lokasi_surat').val();

    var tglSurat =
        $('#tgl_surat').val();


    if (kodePengirim) {
        processed = processed.replace(
            /\{\{kode_pengirim\}\}/g,
            kodePengirim
        );
    }

    if (kodeSurat) {
        processed = processed.replace(
            /\{\{kode_surat\}\}/g,
            kodeSurat
        );
    }

    if (bulan) {
        processed = processed.replace(
            /\{\{bulan\}\}/g,
            bulan
        );
    }

    if (tahun) {
        processed = processed.replace(
            /\{\{tahun\}\}/g,
            tahun
        );
    }

    if (lokasiSurat) {
        processed = processed.replace(
            /\{\{lokasi_surat\}\}/g,
            lokasiSurat
        );
    }

    if (tglSurat) {
        processed = processed.replace(
            /\{\{tanggal_surat\}\}/g,
            tglSurat
        );
    }


    // ==========================================
    // PEGAWAI LOOP
    // ==========================================

    var pegawaiSelected =
        $('#pegawai_loop').val();

    if (
        pegawaiSelected &&
        pegawaiSelected.length > 0
    ) {

        var loopHtml =
            '<table class="table table-bordered table-sm mt-2">' +
            '<thead>' +
            '<tr>' +
            '<th>No</th>' +
            '<th>Nama</th>' +
            '<th>NIP</th>' +
            '<th>Jabatan</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>';


        pegawaiSelected.forEach(function (userid, idx) {

            var p =
                allPegawai.find(function (emp) {
                    return emp.userid === userid;
                });


            if (p) {

                loopHtml +=
                    '<tr>' +
                    '<td>' + (idx + 1) + '</td>' +
                    '<td>' + (p.fullname || '') + '</td>' +
                    '<td>' + (p.nip || '') + '</td>' +
                    '<td>' + (p.jabatan || '') + '</td>' +
                    '</tr>';

            }

        });


        loopHtml +=
            '</tbody>' +
            '</table>';


        processed =
            processed.replace(
                /\{\{pegawai_loop\}\}/g,
                loopHtml
            );

    } else {

        processed =
            processed.replace(
                /\{\{pegawai_loop\}\}/g,
                ''
            );

    }
    */

    // Content sudah di-supply via setup hook di dalam tinymce.init di atas
    // (editor live saat itu). Tidak perlu setContent lagi di sini.
    //console.log('Processed content:', processed);

}

function ModalSuratKeluar(id = null) {
    $('#modalSuratKeluar').modal('show');

    if (id) {
    } else {
        $('#judul').html('Tambah Surat Keluar');
        $('#id').val('');
        $('#perihal').val('');
    }

    document.getElementById('overlay').remove();
}

function SimpanParameterSurat(kolom, selector) {
    var param = $(selector).val();
    var id = $('#record_id').val();

    $.ajax({
        url: 'simpan_parameter_surat',
        type: 'POST',
        data: {
            id: id,
            kolom: kolom,
            param: param
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                loadPreview(id);
            } else {
                toastr.error(res.message);
            }
        },
        error: function () {
            toastr.error('Terjadi kesalahan saat menyimpan data.');
        }
    });
}

function SimpanPenandatangan(pegawai) {
    var id = $('#record_id').val();

    $.ajax({
        url: 'simpan_penandatangan',
        type: 'POST',
        data: {
            id: id,
            pegawai: $(pegawai).val()
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                loadPreview(id);
            } else {
                toastr.error(res.message);
            }
        },
        error: function () {
            toastr.error('Terjadi kesalahan saat menyimpan data.');
        }
    });
}