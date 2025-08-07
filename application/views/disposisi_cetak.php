<html>

<head>
    <style>
        table,
        td,
        th {
            border: 1px solid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        div {
            border-bottom-style: none;
        }
    </style>
</head>

<body>
    <table style="border:none;">
        <tr>
            <td style="width:100%;text-align:center;border:none" colspan=4><img style="height:100%; width: 100%;"
                    src="<?= $this->session->userdata('kop_satker') ?>"></td>
        </tr>
    </table>
    <br />
    <table width="100%">

        <tr>
            <td style="text-align:center;" colspan=4>
                <h3>LEMBAR DISPOSISI</h3>
            </td>
        </tr>
        <tr>
            <td colspan=4 style="text-align:center;"><strong>PERHATIAN : Dilarang memisahkan sehelai
                    Naskah Dinas pun yang tergabung dalam berkas
                    ini</strong></td>
        </tr>
        <tr>
            <td colspan=2 style="border-bottom:1px solid #fff;">Nomor Naskah Dinas : <br /><?= $no_sm ?></td>
            <td style="border-bottom:1px solid #fff;">Status : </td>
            <td style="border-bottom:1px solid #fff;">Diterima Tanggal : <br /><?= $tgl_terima ?></td>
        </tr>
        <tr>
            <td colspan=2 style="border-bottom:1px solid #fff;">Tanggal Naskah Dinas : <br /><?= $tgl_surat ?></td>
            <td style="border-bottom:1px solid #fff;">Sifat : </td>
            <td style="border-bottom:1px solid #fff;">Nomor Agenda : <br /><?= $no_agenda ?></td>
        </tr>
        <tr>
            <td colspan=2>Lampiran : </td>
            <td>Jenis : </td>
            <td></td>
        </tr>
        <tr>
            <td colspan=4 style="border-bottom:1px solid #fff;">Dari : <br /><?= $pengirim ?></td>
        </tr>
        <tr>
            <td colspan=4>Hal : <br /><?= $perihal ?></td>
        </tr>
        <tr style="text-align:center;">
            <td colspan=2>
                <table style="border:none;">
                    <tr>
                        <td style="border-bottom:1px solid #444;"></td>
                        <td style="border:none;">SANGAT SEGERA</td>
                    </tr>
                </table>
            </td>
            <td colspan=2>
                <table style="border:none;">
                    <tr>
                        <td style="border-bottom:1px solid #444;"></td>
                        <td style="border:none;">SEGERA</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan=2>DISPOSISI :</td>
            <td colspan=2>PETUNJUK :</td>
        </tr>
        <tr>
            <td colspan="2">
                <ol>
                    <?php
                    $no = 1;
                    foreach ($progres as $item) {
                        switch ($item->status) {
                            case '1': $status = 'Diteruskan'; break;
                            case '2': $status = 'Disposisi'; break;
                        }
                        ?>

                        <li><?= $item->jabatan ?> -- <i><?= $status ?> ke</i> <?= $item->jab_tujuan ?> <br />
                            Catatan :
                            <?php if ($item->ket) {
                                echo $item->ket;
                            } else {
                                echo '-';
                            } ?>
                            <br />
                            Tanggal : <i><?= $item->created_on; ?></i>
                        </li>
                        <hr/>
                        <?php
                    }
                    $no++;
                    ?>
                </ol>
            </td>
            <td colspan="2">
                <table>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Setuju sesuai dengan ketentuan yang berlaku</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Tolak sesuai dengan ketentuan yang berlaku</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Selesaikan sesuai dengan ketentuan yang berlaku</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Jawab sesuai dengan ketentuan yang berlaku</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Perbaiki</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Teliti dan pendapat</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Sesuai catatan</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Untuk perhatian</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Untuk diketahui</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Edarkan</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Bicarakan dengan saya</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Bicarakan bersama dan laporkan hasilnya</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Dijadwalkan</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Simpan</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Disiapkan</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Ingatkan</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Harap dihadiri/diwakilkan</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td style="border:1px solid #fff;">Asli kepada .....</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan=2 style="border-bottom:1px solid #fff;">Tanggal Kirim untuk Proses : <br /><?= $tgl_dispo ?></td>
            <td colspan=2 style="border-bottom:1px solid #fff;">Diajukan Kembali Oleh : </td>
        </tr>
        <tr>
            <td colspan=2>Diterima Oleh : <br /><?= $nama ?></td>
            <td colspan=2>Diterima Tanggal : </td>
        </tr>
        <tr>
            <td colspan=2 style="border-bottom:1px solid #fff;">Tanggal Kembali untuk Proses :</td>
            <td colspan=2 style="border-bottom:1px solid #fff;">Tanggal Selesai dari Pejabat yang memberi disposisi : </td>
        </tr>
        <tr>
            <td colspan=2>Diterima Oleh :</td>
        </tr>
    </table>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>

</body>

</html>