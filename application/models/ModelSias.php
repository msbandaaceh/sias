<?php

class ModelSias extends CI_Model
{
    private $db_sso;

    public function __construct()
    {
        parent::__construct();

        // Inisialisasi variabel private dengan nilai dari session
        $this->db_sso = $this->config->item('sso_db');
    }

    private function add_audittrail($action, $title, $table, $descrip)
    {

        $params = [
            'tabel' => 'sys_audittrail',
            'data' => [
                'action' => $action,
                'title' => $title,
                'table' => $table,
                'description' => $descrip,
                'username' => $this->session->userdata('username')
            ]
        ];

        $this->apihelper->post('api_audittrail', $params);
    }

    public function cek_aplikasi()
    {
        $params = [
            'tabel' => 'ref_client_app',
            'kolom_seleksi' => 'id',
            'seleksi' => '3'
        ];

        $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $user_data = $result['response']['data'][0];
            $this->session->set_userdata(
                [
                    'nama_client_app' => $user_data['nama_app'],
                    'deskripsi_client_app' => $user_data['deskripsi']
                ]
            );
        }
    }

    public function cek_peran()
    {
        $peran = '';
        $query = $this->model->get_seleksi2('peran', 'userid', $this->session->userdata('userid'), 'hapus', '0');
        if ($query->num_rows() > 0) {
            if ($query->row()->role == 'petugas')
                $peran = 'petugas';
        } else if ($this->session->userdata('super'))
            $peran = 'super';
        else
            $peran = $this->session->userdata('jab_id');

        return $peran;
    }

    public function cek_no_agenda()
    {
        try {
            $this->db->select_max('no_agenda');
            $this->db->where('Year(tgl_terima) = Year(Now())');
            $query = $this->db->get('register_surat_masuk');
            return $query->row();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function register_surat_masuk($jab_id)
    {
        $this->db->order_by('dibaca');
        $this->db->order_by('id', 'DESC');
        $this->db->where('tujuan_surat', $jab_id);
        return $this->db->select('*')->from('register_surat_masuk')->get()->result();
    }

    public function validasi_surat_masuk($jab_id)
    {
        $this->db->order_by('id', 'DESC');

        if ($jab_id == '10') {
            $this->db->where('valid', '0');
        } elseif ($jab_id == '4') {
            $this->db->where('valid', '1');
            $this->db->where('bidang', '1');
        } elseif ($jab_id == '5') {
            $this->db->where('valid', '1');
            $this->db->where('bidang', '2');
        } else {
            return 0;
        }

        return $this->db->select('*')->from('register_surat_masuk')->get()->result();
    }

    public function surat_masuk($jab_id)
    {
        $this->db->order_by('id', 'DESC');
        $this->db->where('dibaca', null);
        $this->db->where('valid', '2');
        $this->db->where('tujuan_surat', $jab_id);
        return $this->db->select('*')->from('register_surat_masuk')->get()->result();
    }

    public function disposisi($jab_id)
    {
        $this->db->order_by('id', 'DESC');
        $this->db->where('dibaca', '0');
        $this->db->where('disposisi', $jab_id);
        return $this->db->select('*')->from('register_disposisi')->get()->result();
    }

    public function all_sm_data()
    {
        $this->db->order_by('id', 'DESC');
        return $this->db->select('*')->from('register_surat_masuk')->get()->result();
    }

    public function all_peran_data()
    {
        $this->db->order_by('id', 'DESC');
        return $this->db->select('*')->from('peran')->get()->result();
    }

    public function get_data_surat_masuk($id)
    {
        $cekNoAgenda = $this->cek_no_agenda();
        $noAgenda = $cekNoAgenda ? $cekNoAgenda->no_agenda + 1 : 1;

        if ($id == '-1') {
            return [
                'st' => 1,
                'judul' => 'TAMBAH DATA SURAT MASUK',
                'id' => '',
                'no_agenda' => $noAgenda,
                'no_surat' => '',
                'tgl_surat' => '',
                'tgl_terima' => '',
                'pengirim' => '',
                'tujuan' => '',
                'perihal' => '',
                'ket' => '',
                'file' => ''
            ];
        }

        $query = $this->get_seleksi('register_surat_masuk', 'id', $id);
        if ($query->num_rows() === 0) {
            return ['st' => 0, 'error' => 'Data tidak ditemukan'];
        }

        $row = $query->row();
        return [
            'st' => 1,
            'judul' => 'EDIT DATA SURAT MASUK',
            'id' => $id,
            'no_agenda' => $row->no_agenda,
            'no_surat' => $row->no_sm,
            'tgl_surat' => $row->tgl_surat,
            'tgl_terima' => $row->tgl_terima,
            'pengirim' => $row->pengirim,
            'tujuan' => $row->tujuan_surat,
            'perihal' => $row->perihal,
            'ket' => $row->ket,
            'file' => $row->file
        ];
    }

    public function get_seleksi_disposisi($id, $jab_id)
    {
        try {
            $this->db->where('dibaca', '0');
            $this->db->where('disposisi', $jab_id);
            $this->db->where('id_sm', $id);
            return $this->db->get('v_disposisi');
        } catch (Exception $e) {
            return 0;
        }
    }

    public function get_detail_surat_masuk($status, $id)
    {
        $surat = $this->get_seleksi('register_surat_masuk', 'id', $id)->row();

        $tgl_surat = $this->tanggalhelper->convertDayDate($surat->tgl_surat);
        $tgl_terima = $this->tanggalhelper->convertDayDate($surat->tgl_terima);

        $tab_dispo = $this->get_seleksi('v_disposisi', 'id_sm', $id)->num_rows() > 0 ? 1 : 0;

        if ($surat->valid == 2 && $status == 'validasi') {
            $cekDispo = $this->get_seleksi_disposisi($id, $this->session->userdata('jab_id'));

            $data_update = [
                'dibaca' => '1',
                'modified_on' => date("Y-m-d H:i:s"),
                'modified_by' => $this->session->userdata('fullname')
            ];

            if ($cekDispo->num_rows() > 0) {
                $this->pembaharuan_data('register_disposisi', $data_update, 'id', $cekDispo->row()->id);
            } else {
                $this->pembaharuan_data('register_surat_masuk', $data_update, 'id', $id);
            }
        }

        $dokumen = !empty($surat->file)
            ? '<iframe src="' . base_url('assets/pdfjs/web/viewer.html?file=' . base_url('assets/dokumen/' . $surat->file)) . '" width="100%" height="640"></iframe>'
            : '<object id="pdf" height="1024px" width="100%" type="application/pdf"><span align="center">Dokumen Elektronik Tidak Tersedia</span></object>';

        return [
            'st' => 1,
            'judul' => 'TAMPIL DETAIL DATA SURAT MASUK',
            'id' => base64_encode($this->encryption->encrypt($id)),
            'no_agenda' => $surat->no_agenda,
            'no_sm' => $surat->no_sm,
            'pengirim' => $surat->pengirim,
            'perihal' => $surat->perihal,
            'ket' => $surat->ket,
            'tgl_surat' => $tgl_surat,
            'tgl_terima' => $tgl_terima,
            'dokumen' => $dokumen,
            'tab_dispo' => $tab_dispo
        ];
    }

    public function get_seleksi($tabel, $kolom_seleksi, $seleksi)
    {
        try {
            $this->db->where($kolom_seleksi, $seleksi);
            return $this->db->get($tabel);
        } catch (Exception $e) {
            return 0;
        }
    }

    public function get_seleksi2($tabel, $kolom_seleksi, $seleksi, $kolom_seleksi2, $seleksi2)
    {
        try {
            $this->db->where($kolom_seleksi2, $seleksi2);
            $this->db->where($kolom_seleksi, $seleksi);
            return $this->db->get($tabel);
        } catch (Exception $e) {
            return 0;
        }
    }

    public function simpan_data($tabel, $data)
    {
        try {
            $this->db->insert($tabel, $data);
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function pembaharuan_data($tabel, $data, $kolom_seleksi, $seleksi)
    {
        try {
            $this->db->where($kolom_seleksi, $seleksi);
            $this->db->update($tabel, $data);
            $title = "Pembaharuan Data <br />Update tabel <b>" . $tabel . "</b>[Pada kolom<b>" . $kolom_seleksi . "</b>]";
            $descrip = null;
            $this->add_audittrail("UPDATE", $title, $tabel, $descrip);
            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function simpan_sm($data)
    {
        if (!$data['id'])
            $query = $this->db->insert('register_surat_masuk', $data);
        else {
            $this->db->where('id', $data['id']);
            $query = $this->db->update('register_surat_masuk', $data);
        }

        if ($query === true) {
            if (!$data['id']) {

                $penelaah = array();
                $penelaah = $this->cari_penelaah();
                if ($penelaah['id'])
                    $pegawai_id = $penelaah['id'];
                else
                    return 'penelaah_kosong';

                if ($penelaah['status']) {
                    $pesan = 'Assalamualaikum Wr. Wb., Yth. *Plh/Plt Penelaah Surat MS Banda Aceh. Ada surat masuk baru perlu penelaahan. Silakan akses aplikasi *LITERASI* MS Banda Aceh untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                } else {
                    $pesan = 'Assalamualaikum Wr. Wb., Yth. *Penelaah Surat MS Banda Aceh. Ada surat masuk baru perlu penelaahan. Silakan akses aplikasi *LITERASI* MS Banda Aceh untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                }

                $params = [
                    'tabel' => 'sys_notif',
                    'data' => array(
                        'jenis_pesan' => 'sias',
                        'id_pemohon' => $this->session->userdata("pegawai_id"),
                        'pesan' => $pesan,
                        'id_tujuan' => $pegawai_id,
                        'created_by' => $this->session->userdata('fullname'),
                        'created_on' => date('Y-m-d H:i:s')
                    )
                ];

                $this->apihelper->post('apiclient/simpan_data', $params);
            }

            return $query;
        } else {
            return $query;
        }
    }

    public function simpan_pelaksanaan_validasi($data)
    {
        $querydetail = $this->model->get_seleksi('register_surat_masuk', 'id', $data['register_id']);
        $pengirim = $querydetail->row()->pengirim;
        $perihal = $querydetail->row()->perihal;
        $nama_app = $this->session->userdata('nama_client_app');
        $nama_pengadilan = $this->session->userdata('nama_satker');

        # cek user
        if ($this->session->userdata('jab_id') == '10') {
            # jika user penelaah
            $status_valid = '1';
            $status_progres = '1';
            $tujuan_sm = '';
            if (!$data['keterangan']) {
                $data['keterangan'] = '-';
            }

            $tujuanProgres = $data['progres'];
            if ($this->session->userdata('status_plh') == '1' || $this->session->userdata('status_plt') == '1') {
                $penginput = 'Penelaah (' . $this->session->userdata('nama_pegawai_plh') . ')';
            } else {
                $penginput = 'Penelaah (' . $this->session->userdata('fullname') . ')';
            }

            # cek tujuan penerusan progres
            if ($tujuanProgres == "4") {
                # Diteruskan untuk Panitera
                $bidang = '1';

                # Cek apakah ada plh jabatan
                $queryPlh = $this->get_seleksi($this->db_sso . '.v_plh', 'plh_id_jabatan', $data['progres']);
                if ($queryPlh->row()->pegawai_id != null) {
                    $tujuanNotif = $queryPlh->row()->pegawai_id;
                    $pesan = 'Assalamualaikum Wr. Wb., Yth. *Plh Panitera MS Banda Aceh (' . $queryPlh->row()->nama_pegawai . ')*. Ada validasi surat masuk perlu diproses baru dari *' . $pengirim . '* perihal *' . $perihal . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                } else {
                    $getUser = $this->get_seleksi2($this->db_sso . '.v_users', 'jab_id', $tujuanProgres, 'status_pegawai', '1');
                    $tujuanNotif = $getUser->row()->pegawai_id;
                    $pesan = 'Assalamualaikum Wr. Wb., Yth. *Panitera MS Banda Aceh (' . $getUser->row()->fullname . ')*. Ada validasi surat masuk perlu diproses baru dari *' . $pengirim . '* perihal *' . $perihal . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                }
            } elseif ($tujuanProgres == "5") {
                # diteruskan untuk Sekretaris
                $bidang = '2';

                # Cek apakah ada plh jabatan
                $queryPlh = $this->get_seleksi($this->db_sso . '.v_plh', 'plh_id_jabatan', $data['progres']);
                if ($queryPlh->row()->pegawai_id != null) {
                    $tujuanNotif = $queryPlh->row()->pegawai_id;
                    $pesan = 'Assalamualaikum Wr. Wb., Yth. *Plh Sekretaris MS Banda Aceh (' . $queryPlh->row()->nama_pegawai . ')*. Ada validasi surat masuk perlu diproses baru dari *' . $pengirim . '* perihal *' . $perihal . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                } else {
                    $getUser = $this->get_seleksi2($this->db_sso . '.v_users', 'jab_id', $tujuanProgres, 'status_pegawai', '1');
                    $tujuanNotif = $getUser->row()->pegawai_id;
                    $pesan = 'Assalamualaikum Wr. Wb., Yth. *Sekretaris MS Banda Aceh (' . $getUser->row()->fullname . ')*. Ada validasi surat masuk perlu diproses baru dari *' . $pengirim . '* perihal *' . $perihal . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                }
            }

            $dataProgres = array(
                'id_sm' => $data['register_id'],
                'userid' => $data['pengguna_id'],
                'status' => $status_progres,
                'tujuan' => $tujuanProgres,
                'ket' => $data['keterangan'],
                'created_by' => $penginput,
                'created_on' => date("Y-m-d H:i:s")
            );

            $data_sm = array(
                'tujuan_surat' => $tujuan_sm,
                'bidang' => $bidang,
                'valid' => $status_valid,
                'modified_on' => date("Y-m-d H:i:s"),
                'modified_by' => $penginput
            );

            $dataNotif = [
                'tabel' => 'sys_notif',
                'data' => [
                    'jenis_pesan' => 'surat',
                    'id_pemohon' => $this->session->userdata("id_pegawai"),
                    'pesan' => $pesan,
                    'id_tujuan' => $tujuanNotif,
                    'created_by' => $penginput,
                    'created_on' => date('Y-m-d H:i:s')
                ]
            ];

            $querySimpanProgres = $this->simpan_data('status_surat_masuk', $dataProgres);
            $queryUpdateSM = $this->model->pembaharuan_data('register_surat_masuk', $data_sm, 'id', $data['register_id']);

            if ($querySimpanProgres == '1' && $queryUpdateSM == '1') {
                $this->apihelper->post('apiclient/simpan_data', $dataNotif);
                return json_encode(array('success' => true, 'message' => 'Simpan Data Pelaksanaan Berhasil, Notifikasi Akan Segera Dikirim'));
            } else {
                return json_encode(array('success' => false, 'message' => 'Simpan Data Pelaksanaan Gagal'));
            }

        } elseif (in_array($this->session->userdata('jab_id'), ['4', '5'])) {
            # masuk user Panitera dan Sekretaris
            if ($data['progres'] == '1') {
                # Progres surat Diteruskan ke Ketua
                $tujuan_sm = '1';
                $status_progres = '1';
                $tujuanProgres = $data['progres'];
                if (!$data['keterangan']) {
                    $data['keterangan'] = '-';
                }

                $queryPlh = $this->get_seleksi($this->db_sso . '.v_plh', 'plh_id_jabatan', '1');
                if ($queryPlh->row()->pegawai_id != null) {
                    $tujuanNotif = $queryPlh->row()->pegawai_id;
                    if ($queryPlh->row()->jabatan == 'Wakil Ketua') {
                        $pesan = 'Assalamualaikum Wr. Wb., Yth. *Wakil Ketua MS Banda Aceh (' . $queryPlh->row()->nama_pegawai . ')*. Ada surat masuk baru dari *' . $pengirim . '* perihal *' . $perihal . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                    } else {
                        $pesan = 'Assalamualaikum Wr. Wb., Yth. *Plh/Plt Ketua MS Banda Aceh (' . $queryPlh->row()->nama_pegawai . ')*. Ada surat masuk baru dari *' . $pengirim . '* perihal *' . $perihal . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                    }
                } else {
                    $queryUser = $this->get_seleksi2($this->db_sso . '.v_users', 'jab_id', '1', 'status_pegawai', '1');
                    $tujuanNotif = $queryUser->row()->pegawai_id;
                    $pesan = 'Assalamualaikum Wr. Wb., Yth. *Ketua MS Banda Aceh (' . $queryUser->row()->fullname . ')*. Ada surat masuk baru dari *' . $pengirim . '* perihal *' . $perihal . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                }

                if ($this->session->userdata('status_plh') == '1' || $this->session->userdata('status_plt') == '1') {
                    $penginput = $this->session->userdata('fullname') . ' (' . $this->session->userdata('nama_pegawai_plh') . ')';
                } else {
                    $penginput = $this->session->userdata('jabatan') . ' (' . $this->session->userdata('fullname') . ')';
                }

                $data_sm = array(
                    'tujuan_surat' => $tujuan_sm,
                    'valid' => '2',
                    'modified_on' => date("Y-m-d H:i:s"),
                    'modified_by' => $penginput
                );

            } elseif ($data['progres'] == '2') {
                # Progres Surat diDisposisi
                $status_progres = '2';
                if ($this->session->userdata('status_plh') == '1' || $this->session->userdata('status_plt') == '1') {
                    $penginput = $this->session->userdata('fullname') . ' (' . $this->session->userdata('nama_pegawai_plh') . ')';
                } else {
                    $penginput = $this->session->userdata('fullname');
                }

                if (!$data['keterangan']) {
                    return json_encode(array('success' => false, 'message' => 'Keterangan Disposisi tidak boleh kosong'));
                }

                # Progres surat Disposisi
                for ($i = 0; $i < count($data['jabatan']); $i++) {
                    # die(var_dump($jabatan[$i]));
                    # $queryUser = $this->model->get_seleksi('v_users', 'jab_id', $jabatan[$i]);
                    $queryPlh = $this->get_seleksi($this->db_sso . '.v_plh', 'plh_id_jabatan', $data['jabatan'][$i]);

                    if ($queryPlh->row()->pegawai_id != null) {
                        $tujuanNotif = $queryPlh->row()->pegawai_id;
                        $jab = $queryPlh->row()->nama;
                        $pesan = 'Assalamualaikum Wr. Wb., Yth. *' . $jab . ' (' . $queryPlh->row()->nama_pegawai . ')* ' . $nama_pengadilan . '. Ada disposisi surat masuk baru dari *' . $pengirim . '* perihal *' . $perihal . '* dengan Disposisi : *' . $data['keterangan'] . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                    } else {
                        $queryUser = $this->get_seleksi2($this->db_sso . '.v_users', 'jab_id', $data['jabatan'][$i], 'status_pegawai', '1');
                        $tujuanNotif = $queryUser->row()->pegawai_id;
                        $jab = $queryUser->row()->jabatan;
                        $pesan = 'Assalamualaikum Wr. Wb., Yth. *' . $jab . ' (' . $queryUser->row()->fullname . ')* ' . $nama_pengadilan . '. Ada disposisi surat masuk baru dari *' . $pengirim . '* perihal *' . $perihal . '* dengan Disposisi : *' . $data['keterangan'] . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                    }

                    $tujuanProgres = $data['jabatan'][$i];

                    $dataNotif = [
                        'tabel' => 'sys_notif',
                        'data' => [
                            'jenis_pesan' => 'surat',
                            'id_pemohon' => $this->session->userdata('id_pegawai'),
                            'pesan' => $pesan,
                            'id_tujuan' => $tujuanNotif,
                            'created_by' => $penginput,
                            'created_on' => date('Y-m-d H:i:s')
                        ]
                    ];

                    $dataDispo = array(
                        'id_sm' => $data['register_id'],
                        'jab_id' => $this->session->userdata('jab_id'),
                        'disposisi' => $data['jabatan'][$i],
                        'ket_disposisi' => $data['keterangan'],
                        'created_by' => $penginput,
                        'created_on' => date("Y-m-d H:i:s")
                    );

                    $data = array(
                        'id_sm' => $data['register_id'],
                        'userid' => $this->session->userdata('userid'),
                        'status' => $status_progres,
                        'tujuan' => $tujuanProgres,
                        'ket' => $data['keterangan'],
                        'created_by' => $penginput,
                        'created_on' => date("Y-m-d H:i:s")
                    );

                    $this->notif->tambahNotif($dataNotif, 'sys_notif');
                    $queryDispo = $this->model->simpan_data('register_disposisi', $dataDispo);
                    $queryStatus = $this->model->simpan_data('status_surat_masuk', $data);
                }

                $data_sm = array(
                    'status' => '1',
                    'dibaca' => '1',
                    'valid' => '2',
                    'tujuan_surat' => $this->session->userdata('jab_id'),
                    'modified_on' => date("Y-m-d H:i:s"),
                    'modified_by' => $penginput
                );
            } else {
                # Progres surat Dilaksanakan atau Selesai
                if ($this->session->userdata('status_plh') == '1' || $this->session->userdata('status_plt') == '1') {
                    $penginput = $this->session->userdata('fullname') . ' (' . $this->session->userdata('nama_pegawai_plh') . ')';
                } else {
                    $penginput = $this->session->userdata('fullname');
                }

                //cek pilihan Progres
                if ($data['progres'] == '3') {
                    //Progres Dilaksanakan
                    if (!$data['keterangan']) {
                        $keterangan = "Dilaksanakan";
                    }

                    $status_progres = '3';
                    $status_sm = '1';
                } elseif ($data['progres'] == '4') {
                    //Progres Selesai
                    if (!$data['keterangan']) {
                        $keterangan = "Selesai";
                    }

                    $status_progres = '4';
                    $status_sm = '2';
                }

                $data_sm = array(
                    'tujuan_surat' => $this->session->userdata('jab_id'),
                    'dibaca' => '1',
                    'valid' => '2',
                    'status' => $status_sm,
                    'modified_on' => date("Y-m-d H:i:s"),
                    'modified_by' => $penginput
                );

                $data = array(
                    'id_sm' => $data['register_id'],
                    'userid' => $data['pengguna_id'],
                    'status' => $status_progres,
                    'tujuan' => $this->session->userdata('jab_id'),
                    'ket' => $keterangan,
                    'created_by' => $penginput,
                    'created_on' => date("Y-m-d H:i:s")
                );

                $queryStatus = $this->model->simpan_data('status_surat_masuk', $data);
            }

            //query untuk update data Surat Masuk
            $queryUpdateSM = $this->model->pembaharuan_data('register_surat_masuk', $data_sm, 'id', $data['register_id']);

            //cek apakah proses disposisi
            if ($data['progres'] == '1') {
                # Bukan Disposisi
                $data_progres = array(
                    'id_sm' => $data['register_id'],
                    'userid' => $data['pengguna_id'],
                    'status' => $status_progres,
                    'tujuan' => $tujuanProgres,
                    'ket' => $data['keterangan'],
                    'created_by' => $penginput,
                    'created_on' => date("Y-m-d H:i:s")
                );
                //die(var_dump($data_progres));
                $queryStatus = $this->model->simpan_data('status_surat_masuk', $data_progres);

                if ($queryUpdateSM == '1' && $queryStatus == '1') {
                    $dataNotif = array(
                        'tabel' => 'sys_notif',
                        'data' => [
                            'jenis_pesan' => 'surat',
                            'id_pemohon' => $this->session->userdata("id_pegawai"),
                            'pesan' => $pesan,
                            'id_tujuan' => $tujuanNotif,
                            'created_by' => $penginput,
                            'created_on' => date('Y-m-d H:i:s')
                        ]
                    );

                    $this->apihelper->post('apiclient/simpan_data', $dataNotif);
                    return json_encode(array('success' => true, 'message' => 'Simpan Data Pelaksanaan Berhasil, Notifikasi Akan Segera Dikirim'));
                } else {
                    return json_encode(array('success' => false, 'message' => 'Simpan Data Pelaksanaan Gagal'));
                }
            } elseif ($data['progres'] == '2') {
                # Disposisi
                if ($queryStatus == '1' && $queryDispo == '1') {
                    return json_encode(array('success' => true, 'message' => 'Simpan Data Pelaksanaan Berhasil, Notifikasi Akan Segera Dikirim'));
                } else {
                    return json_encode(array('success' => false, 'message' => 'Simpan Data Pelaksanaan Gagal'));
                }
            } else {
                # Dilaksanakan atau Selesai
                if ($queryStatus == '1') {
                    return json_encode(array('success' => true, 'message' => 'Simpan Data Pelaksanaan Berhasil, Notifikasi Akan Segera Dikirim'));
                } else {
                    return json_encode(array('success' => false, 'message' => 'Simpan Data Pelaksanaan Gagal'));
                }
            }
        }
    }

    public function cari_penelaah()
    {
        $data = [
            'tabel' => 'v_users',
            'kolom_seleksi' => 'jab_id',
            'seleksi' => '10',
            'kolom_seleksi2' => 'status_pegawai',
            'seleksi2' => '1'
        ];

        $result = $this->apihelper->get('api_get_seleksi2', $data);
        $status = '';
        if ($result['response']['status'] == 'success') {

            $plh = $this->cek_plh('10');
            if ($plh) {
                $status = true;
                $id = $plh;
            } else {
                $user_data = $result['response']['data'][0];
                $id = $user_data['pegawai_id'];
            }
        } else {
            $plh = $this->cek_plh('10');
            if ($plh) {
                $status = true;
                $id = $plh;
            } else {
                $id = '';
            }
        }

        $penelaah = array(
            'status' => $status,
            'id' => $id
        );

        return $penelaah;
    }

    public function cek_plh($jab_id)
    {
        $data_plh = [
            'tabel' => 'v_plh',
            'kolom_seleksi' => 'plh_id_jabatan',
            'seleksi' => $jab_id
        ];

        $result = $this->apihelper->get('api_get_seleksi', $data_plh);
        if ($result['response']['status'] == 'success') {
            $plh_data = $result['response']['data'][0];
            $plh = $plh_data['pegawai_id'];
        } else {
            $plh = '';
        }

        return $plh;
    }

    public function get_data_peran()
    {
        $this->db->select('l.id AS id, u.userid AS userid, u.fullname AS nama, l.role AS peran, l.hapus AS hapus');
        $this->db->from('peran l');
        $this->db->join($this->db_sso . '.v_users u', 'l.userid = u.userid', 'left');
        $this->db->order_by('l.id', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }

    public function edit_status_surat_masuk($register_id)
    {
        $pelaksanaan_id = '99';

        $cekValid = $this->model->get_seleksi('register_surat_masuk', 'id', $register_id);
        //cek apakah surat sudah di validasi oleh penelaah
        if ($cekValid->row()->valid == '2') {
            //jika surat valid
            $array_progres = array('0' => 'Pilih Progres Surat', '2' => 'Disposisi', '3' => 'Dilaksanakan', '4' => 'Selesai');

            if ($this->session->userdata('jab_id') == '1') {
                //user Ketua
                $array_jabatan = array('4' => 'Panitera', '5' => 'Sekretaris');
                $jenis_jabatan = form_multiselect('jenis_jabatan[]', $array_jabatan, '', 'class="form-select" data-placeholder="Pilih Disposisi" required id="jenis_jabatan"');
            } elseif ($this->session->userdata('jab_id') == '4') {
                //user Panitera
                $array_jabatan = array('6' => 'Panitera Muda Gugatan', '7' => 'Panitera Muda Permohonan', '8' => 'Panitera Muda Jinayat', '9' => 'Panitera Muda Hukum');
                $jenis_jabatan = form_multiselect('jenis_jabatan[]', $array_jabatan, '', 'class="form-select" data-placeholder="Pilih Disposisi" required id="jenis_jabatan"');
            } elseif ($this->session->userdata('jab_id') == '5') {
                //user Sekretaris
                $array_jabatan = array('10' => 'Kepala Sub Bagian Umum dan Keuangan', '11' => 'Kepala Sub Bagian Kepegawaian', '12' => 'Kepala Sub Bagian PTIP');
                $jenis_jabatan = form_multiselect('jenis_jabatan[]', $array_jabatan, '', 'class="form-select" data-placeholder="Pilih Disposisi" required id="jenis_jabatan"');
            } else {
                $jenis_jabatan = "";
                $array_progres = array('0' => 'Pilih Progres Surat', '3' => 'Dilaksanakan', '4' => 'Selesai');
            }

            $jenis_progres = form_dropdown('jenis_progres', $array_progres, '', 'class="form-control" onchange="JenisPelaksanaan()" required id="jenis_progres"');
        } else {
            //jika surat belum valid
            //cek apakah penelaah atau validator
            if ($this->session->userdata('jab_id') == '10') {
                //user Penelaah (Kasub UK)
                $array_progres = array('4' => 'Kepaniteraan', '5' => 'Kesekretariatan');
                $jenis_progres = form_dropdown('jenis_progres', $array_progres, '', 'class="form-control" onchange="JenisPelaksanaan()" required id="jenis_progres"');
                $jenis_jabatan = '';

            } elseif (in_array($this->session->userdata('jab_id'), ['4', '5'])) {
                //user Validator
                if ($this->session->userdata('jab_id') == '4') {
                    $array_jabatan = array('6' => 'Panitera Muda Gugatan', '7' => 'Panitera Muda Permohonan', '8' => 'Panitera Muda Jinayat', '9' => 'Panitera Muda Hukum');
                    $jenis_jabatan = form_multiselect('jenis_jabatan[]', $array_jabatan, '', 'class="form-control select2" data-placeholder="Pilih Disposisi" required id="jenis_jabatan"');
                } else {
                    $array_jabatan = array('10' => 'Kepala Sub Bagian Umum dan Keuangan', '11' => 'Kepala Sub Bagian Kepegawaian', '12' => 'Kepala Sub Bagian PTIP');
                    $jenis_jabatan = form_multiselect('jenis_jabatan[]', $array_jabatan, '', 'class="form-control select2" data-placeholder="Pilih Disposisi" required id="jenis_jabatan"');
                }

                $array_progres = array('0' => 'Pilih Progres Surat', '1' => 'Diteruskan', '2' => 'Disposisi', '3' => 'Dilaksanakan', '4' => 'Selesai');
                $jenis_progres = form_dropdown('jenis_progres', $array_progres, '', 'class="form-control" onchange="JenisPelaksanaan()" required id="jenis_progres"');
            }
        }

        return [
            'st' => 1,
            'pelaksanaan_id' => base64_encode($this->encryption->encrypt($pelaksanaan_id)),
            'jenis_jabatan' => $jenis_jabatan,
            'jenis_progres' => $jenis_progres
        ];
    }
}