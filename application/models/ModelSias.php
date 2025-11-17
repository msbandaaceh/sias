<?php

/**
 * @property CI_Session $session
 * @property CI_DB_query_builder $db
 * @property Apihelper $apihelper
 * @property TanggalHelper $tanggalhelper
 * @property CI_Encryption $encryption
 */

class ModelSias extends CI_Model
{
    private $db_sso;

    public function __construct()
    {
        parent::__construct();

        // Inisialisasi variabel private dengan nilai dari session
        $this->db_sso = $this->session->userdata('sso_db');
        $this->url_ptsp = $this->config->item('ptsp_server'); # Domain PTSP Server
    }

    private function add_audittrail($action, $title, $table, $descrip)
    {

        $params = [
            'tabel' => 'sys_audittrail',
            'data' => [
                'datetime' => date("Y-m-d H:i:s"),
                'ipaddress' => $this->input->ip_address(),
                'action' => $action,
                'title' => $title,
                'tablename' => $table,
                'description' => $descrip,
                'username' => $this->session->userdata('username')
            ]
        ];

        $this->apihelper->post('apiclient/simpan_data', $params);
    }

    private function kirim_notif($data)
    {
        $params = [
            'tabel' => 'sys_notif',
            'data' => $data
        ];

        $this->apihelper->post('apiclient/simpan_data', $params);
    }

    private function kirim_notif_ptsp($data)
    {
        $params = [
            'tabel' => 'sys_notif',
            'data' => $data,
            'apine' => 'M4hk4m4hBn4@2025'
        ];

        $this->apihelper->post_url($this->url_ptsp . 'api/simpan_data', $params);
    }

    public function cek_aplikasi($id)
    {
        $params = [
            'tabel' => 'ref_client_app',
            'kolom_seleksi' => 'id',
            'seleksi' => $id
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
        $this->db->order_by('status', 'ASC');
        $this->db->order_by('created_on', 'DESC');
        $this->db->order_by('dibaca', 'ASC');
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

    public function disposisi_surat_masuk($jab_id)
    {
        $this->db->order_by('dibaca');
        $this->db->order_by('id', 'DESC');
        $this->db->where('disposisi', $jab_id);
        return $this->db->select('*')->from('v_disposisi')->get()->result();
    }

    public function surat_masuk($jab_id)
    {
        $this->db->order_by('status', 'ASC');
        $this->db->where('status', '0');
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

    /**
     * Get data surat masuk untuk server-side processing DataTables
     * OPTIMASI: Menggunakan pagination, search, dan sorting di server-side
     * 
     * @param int $start Start offset untuk pagination
     * @param int $length Jumlah record per halaman
     * @param string $search Search keyword
     * @param int $order_column Index kolom untuk sorting (0-based, kolom pertama adalah NO yang tidak di-order)
     * @param string $order_dir Direction sorting (ASC/DESC)
     * @return array Array dengan data dan total records
     */
    public function get_arsip_sm_datatables($start = 0, $length = 10, $search = '', $order_column = 0, $order_dir = 'DESC')
    {
        // Get total records (tanpa filter)
        $total_records = $this->db->count_all('register_surat_masuk');
        
        // Build query untuk filtered count (query terpisah)
        $this->db->from('register_surat_masuk');
        
        // Search filter untuk count
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('no_agenda', $search);
            $this->db->or_like('no_sm', $search);
            $this->db->or_like('pengirim', $search);
            $this->db->or_like('perihal', $search);
            $this->db->or_like('tgl_surat', $search);
            $this->db->or_like('tgl_terima', $search);
            $this->db->group_end();
        }
        
        // Get total filtered - gunakan count_all_results dengan reset
        $total_filtered = $this->db->count_all_results('', false);
        
        // Reset query builder untuk query data
        $this->db->reset_query();
        
        // Build query untuk get data
        $this->db->select('*');
        $this->db->from('register_surat_masuk');
        
        // Apply search filter untuk data
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('no_agenda', $search);
            $this->db->or_like('no_sm', $search);
            $this->db->or_like('pengirim', $search);
            $this->db->or_like('perihal', $search);
            $this->db->or_like('tgl_surat', $search);
            $this->db->or_like('tgl_terima', $search);
            $this->db->group_end();
        }
        
        // Order by - mapping kolom DataTables ke kolom database
        // Kolom DataTables: [NO(0), no_agenda(1), no_sm(2), pengirim(3), perihal(4), tgl_surat(5), tgl_terima(6), aksi(7)]
        // Kolom database: [id, no_agenda, no_sm, pengirim, perihal, tgl_surat, tgl_terima]
        $columns = ['id', 'no_agenda', 'no_sm', 'pengirim', 'perihal', 'tgl_surat', 'tgl_terima'];
        // Adjust order_column: jika order_column = 0 (NO), gunakan id; jika > 0, kurangi 1
        if ($order_column == 0) {
            $order_by_column = 'id'; // Default order by id jika kolom NO
        } else {
            $db_column_index = $order_column - 1;
            $order_by_column = isset($columns[$db_column_index]) ? $columns[$db_column_index] : 'id';
        }
        $this->db->order_by($order_by_column, $order_dir);
        
        // Limit untuk pagination
        if ($length > 0) {
            $this->db->limit($length, $start);
        }
        
        // Get data
        $query = $this->db->get();
        $data = $query->result();
        
        return [
            'data' => $data,
            'total_records' => $total_records,
            'total_filtered' => $total_filtered
        ];
    }

    public function all_sm_data_filter($tgl_awal, $tgl_akhir)
    {
        $this->db->order_by('created_on', 'DESC');
        // OPTIMASI: Gunakan range query tanpa fungsi DATE() untuk memungkinkan penggunaan index
        $this->db->where('created_on >=', $tgl_awal . ' 00:00:00');
        $this->db->where('created_on <=', $tgl_akhir . ' 23:59:59');
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
                'file' => '',
                'no_hp' => '',
                'tracking_code' => ''
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
            'file' => $row->file,
            'no_hp' => isset($row->no_hp) ? $row->no_hp : '',
            'tracking_code' => isset($row->tracking_code) ? $row->tracking_code : ''
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

        if ($surat->valid == 2 && in_array($status, ['validasi', 'disposisi'])) {
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
            $title = "Simpan Data <br />Update tabel <b>" . $tabel . "</b>[]";
            $descrip = null;
            $this->add_audittrail("INSERT", $title, $tabel, $descrip);
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

    /**
     * Generate tracking code unik
     * Format: SIAS-YYYYMMDD-XXXXXX
     */
    public function generate_tracking_code()
    {
        $date = date('Ymd');
        $max_attempts = 10;
        $attempt = 0;

        do {
            // Generate 6 karakter random alfanumerik
            $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
            $tracking_code = 'SIAS-' . $date . '-' . $random;

            // Cek apakah sudah ada di database
            $this->db->where('tracking_code', $tracking_code);
            $query = $this->db->get('register_surat_masuk');

            $attempt++;
            if ($attempt >= $max_attempts) {
                // Fallback: tambah timestamp jika masih duplikat
                $tracking_code = 'SIAS-' . $date . '-' . strtoupper(substr(md5(time() . rand()), 0, 6));
                break;
            }
        } while ($query->num_rows() > 0);

        return $tracking_code;
    }

    /**
     * Get tracking information by tracking code
     */
    public function get_tracking_info($tracking_code)
    {
        $this->db->select('r.*, 
            (SELECT COUNT(*) FROM status_surat_masuk WHERE id_sm = r.id) as jumlah_progres');
        $this->db->from('register_surat_masuk r');
        $this->db->where('r.tracking_code', strtoupper($tracking_code));
        $query = $this->db->get();
        return $query;
    }

    /**
     * Get progres surat untuk tracking
     */
    public function get_progres_tracking($surat_id)
    {
        // Berdasarkan grep, tabel progres_surat menggunakan id_sm
        // Gunakan select dengan false untuk menghindari escaping pada CASE statement
        // Field 'tujuan' sudah berisi ID jabatan tujuan (untuk disposisi, ini adalah tujuan disposisi)
        $this->db->select('p.*', false);
        $this->db->select('CASE 
            WHEN p.status = 1 THEN "Diteruskan"
            WHEN p.status = 2 THEN "Disposisi"
            WHEN p.status = 3 THEN "Dilaksanakan"
            WHEN p.status = 4 THEN "Selesai"
            ELSE "Unknown"
        END as status_text', false);
        $this->db->from('status_surat_masuk p');
        $this->db->where('p.id_sm', $surat_id);
        $this->db->order_by('p.created_on', 'ASC');
        return $this->db->get();
    }
    
    /**
     * Get mapping nama jabatan berdasarkan ID
     */
    public function get_nama_jabatan($jab_id)
    {
        $jabatan_map = [
            '1' => 'Ketua',
            '4' => 'Panitera',
            '5' => 'Sekretaris',
            '6' => 'Panitera Muda Gugatan',
            '7' => 'Panitera Muda Permohonan',
            '8' => 'Panitera Muda Jinayat',
            '9' => 'Panitera Muda Hukum',
            '10' => 'Kepala Sub Bagian Umum dan Keuangan',
            '11' => 'Kepala Sub Bagian Kepegawaian',
            '12' => 'Kepala Sub Bagian PTIP'
        ];
        
        return isset($jabatan_map[$jab_id]) ? $jabatan_map[$jab_id] : 'Jabatan Tidak Diketahui';
    }

    public function simpan_sm($data)
    {
        // Generate tracking code untuk surat baru
        if (!$data['id']) {
            $data['tracking_code'] = $this->generate_tracking_code();
        }

        if (!$data['id'])
            $query = $this->db->insert('register_surat_masuk', $data);
        else {
            $this->db->where('id', $data['id']);
            $query = $this->db->update('register_surat_masuk', $data);
        }

        if ($query === true) {
            if (!$data['id']) {
                // Simpan ID surat yang baru dibuat untuk notifikasi
                #$surat_id = $this->db->insert_id();
                $tracking_code = $data['tracking_code'];
                $no_hp = isset($data['no_hp']) ? $data['no_hp'] : null;

                // Kirim notifikasi ke penelaah
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

                $dataNotif = [
                    'jenis_pesan' => 'sias',
                    'id_pemohon' => $this->session->userdata("pegawai_id"),
                    'pesan' => $pesan,
                    'id_tujuan' => $pegawai_id,
                    'created_by' => $this->session->userdata('fullname'),
                    'created_on' => date('Y-m-d H:i:s')
                ];

                $this->kirim_notif($dataNotif);

                // Kirim notifikasi ke pengirim jika no_hp diisi
                if (!empty($no_hp) && !empty($tracking_code)) {
                    $base_url = base_url();
                    $tracking_url = $base_url . 'tracking?code=' . $tracking_code;
                    
                    $pesan_pengirim = 'Assalamualaikum Wr. Wb. Surat masuk Anda telah diterima dengan nomor surat *' . $data['no_sm'] . '* perihal *' . $data['perihal'] . '*. Tracking Code: *' . $tracking_code . '*. Lacak status surat Anda di: ' . $tracking_url . ' Terima kasih.';

                    $dataNotif = [
                        'jenis_pesan' => 'sias',
                        'pesan' => $pesan_pengirim,
                        'nohp' => $no_hp,
                        'dibuat' => date('Y-m-d H:i:s')
                    ];
    
                    $this->kirim_notif_ptsp($dataNotif);                    
                }
            }
            return $query;
        } else {
            return $query;
        }
    }

    public function simpan_pelaksanaan_validasi($data)
    {
        $querydetail = $this->get_seleksi('register_surat_masuk', 'id', $data['register_id']);
        $pengirim = $querydetail->row()->pengirim;
        $perihal = $querydetail->row()->perihal;
        $nama_app = $this->session->userdata('nama_client_app');
        $nama_pengadilan = $this->session->userdata('nama_satker');

        $ket_progres = $data['keterangan'];
        $register_id = $data['register_id'];
        $progres = $data['progres'];

        # cek user
        if ($this->session->userdata('jab_id') == '10') {
            # jika user penelaah
            $status_valid = '1';
            $status_progres = '1';
            $tujuan_sm = '';
            if (!$ket_progres) {
                $ket_progres = '-';
            }

            $tujuanProgres = $progres;
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
                $queryPlh = $this->get_seleksi($this->db_sso . '.v_plh', 'plh_id_jabatan', $progres);
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
                $queryPlh = $this->get_seleksi($this->db_sso . '.v_plh', 'plh_id_jabatan', $progres);
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
                'id_sm' => $register_id,
                'userid' => $this->session->userdata('userid'),
                'status' => $status_progres,
                'tujuan' => $tujuanProgres,
                'ket' => $ket_progres,
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
                'jenis_pesan' => 'surat',
                'id_pemohon' => $this->session->userdata("pegawai_id"),
                'pesan' => $pesan,
                'id_tujuan' => $tujuanNotif,
                'created_by' => $penginput,
                'created_on' => date('Y-m-d H:i:s')
            ];

            $this->kirim_notif($dataNotif);
            $querySimpanProgres = $this->simpan_data('status_surat_masuk', $dataProgres);
            $queryUpdateSM = $this->pembaharuan_data('register_surat_masuk', $data_sm, 'id', $register_id);

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
                $tujuanProgres = $progres;
                if (!$ket_progres) {
                    $ket_progres = '-';
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

                if (!$ket_progres) {
                    return json_encode(array('success' => false, 'message' => 'Keterangan Disposisi tidak boleh kosong'));
                }

                # Progres surat Disposisi
                foreach ($data['jabatan'] as $jabatan_id) {
                    $queryPlh = $this->get_seleksi($this->db_sso . '.v_plh', 'plh_id_jabatan', $jabatan_id);

                    if ($queryPlh->row()->pegawai_id != null) {
                        $tujuanNotif = $queryPlh->row()->pegawai_id;
                        $jab = $queryPlh->row()->nama;
                        $pesan = 'Assalamualaikum Wr. Wb., Yth. *' . $jab . ' (' . $queryPlh->row()->nama_pegawai . ')* ' . $nama_pengadilan . '. Ada disposisi surat masuk baru dari *' . $pengirim . '* perihal *' . $perihal . '* dengan Disposisi : *' . $ket_progres . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                    } else {
                        $queryUser = $this->get_seleksi2($this->db_sso . '.v_users', 'jab_id', $jabatan_id, 'status_pegawai', '1');
                        $tujuanNotif = $queryUser->row()->pegawai_id;
                        $jab = $queryUser->row()->jabatan;
                        $pesan = 'Assalamualaikum Wr. Wb., Yth. *' . $jab . ' (' . $queryUser->row()->fullname . ')* ' . $nama_pengadilan . '. Ada disposisi surat masuk baru dari *' . $pengirim . '* perihal *' . $perihal . '* dengan Disposisi : *' . $ket_progres . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                    }

                    $tujuanProgres = $jabatan_id;

                    $dataNotif = [
                        'jenis_pesan' => 'surat',
                        'id_pemohon' => $this->session->userdata('pegawai_id'),
                        'pesan' => $pesan,
                        'id_tujuan' => $tujuanNotif,
                        'created_by' => $penginput,
                        'created_on' => date('Y-m-d H:i:s')
                    ];

                    $dataDispo = array(
                        'id_sm' => $register_id,
                        'jab_id' => $this->session->userdata('jab_id'),
                        'disposisi' => $jabatan_id,
                        'ket_disposisi' => $ket_progres,
                        'created_by' => $penginput,
                        'created_on' => date("Y-m-d H:i:s")
                    );

                    $data = array(
                        'id_sm' => $register_id,
                        'userid' => $this->session->userdata('userid'),
                        'status' => $status_progres,
                        'tujuan' => $tujuanProgres,
                        'ket' => $ket_progres,
                        'created_by' => $penginput,
                        'created_on' => date("Y-m-d H:i:s")
                    );

                    $this->kirim_notif($dataNotif);
                    $queryDispo = $this->simpan_data('register_disposisi', $dataDispo);
                    $queryStatus = $this->simpan_data('status_surat_masuk', $data);
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
                if ($progres == '3') {
                    //Progres Dilaksanakan
                    if (!$ket_progres) {
                        $ket_progres = "Dilaksanakan";
                    }

                    $status_progres = '3';
                    $status_sm = '1';
                } elseif ($progres == '4') {
                    //Progres Selesai
                    if (!$ket_progres) {
                        $ket_progres = "Selesai";
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
                    'id_sm' => $register_id,
                    'userid' => $this->session->userdata('userid'),
                    'status' => $status_progres,
                    'tujuan' => $this->session->userdata('jab_id'),
                    'ket' => $ket_progres,
                    'created_by' => $penginput,
                    'created_on' => date("Y-m-d H:i:s")
                );

                $queryStatus = $this->simpan_data('status_surat_masuk', $data);
            }

            //query untuk update data Surat Masuk
            $queryUpdateSM = $this->pembaharuan_data('register_surat_masuk', $data_sm, 'id', $register_id);

            //cek apakah proses disposisi
            if ($progres == '1') {
                # Bukan Disposisi
                $data_progres = array(
                    'id_sm' => $register_id,
                    'userid' => $this->session->userdata('userid'),
                    'status' => $status_progres,
                    'tujuan' => $tujuanProgres,
                    'ket' => $ket_progres,
                    'created_by' => $penginput,
                    'created_on' => date("Y-m-d H:i:s")
                );
                //die(var_dump($data_progres));
                $queryStatus = $this->simpan_data('status_surat_masuk', $data_progres);

                if ($queryUpdateSM == '1' && $queryStatus == '1') {
                    $dataNotif = [
                        'jenis_pesan' => 'surat',
                        'id_pemohon' => $this->session->userdata("pegawai_id"),
                        'pesan' => $pesan,
                        'id_tujuan' => $tujuanNotif,
                        'created_by' => $penginput,
                        'created_on' => date('Y-m-d H:i:s')
                    ];

                    $this->kirim_notif($dataNotif);
                    return json_encode(array('success' => true, 'message' => 'Simpan Data Pelaksanaan Berhasil, Notifikasi Akan Segera Dikirim'));
                } else {
                    return json_encode(array('success' => false, 'message' => 'Simpan Data Pelaksanaan Gagal'));
                }
            } elseif ($progres == '2') {
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

    public function simpan_pelaksanaan_surat_masuk($data)
    {
        $querydetail = $this->get_seleksi('register_surat_masuk', 'id', $data['register_id']);
        $tujuan = $querydetail->row()->tujuan_surat;
        $perihal = $querydetail->row()->perihal;
        $nama_app = $this->session->userdata('nama_client_app');
        $nama_pengadilan = $this->session->userdata('nama_satker');
        $queryDispo = "";

        if ($data['pelaksanaan_id'] == '99') {
            if ($data['progres'] == '2') {
                #Progres Disposisi
                if (!$data['jabatan']) {
                    return json_encode(array('success' => false, 'message' => 'Tujuan Disposisi Surat Tidak Boleh Kosong'));
                }

                if (!$data['ket']) {
                    return json_encode(array('success' => false, 'message' => 'Keterangan Disposisi Surat Tidak Boleh Kosong'));
                }

                $data_sm = array('status' => '1', 'modified_on' => date("Y-m-d H:i:s"), 'modified_by' => $this->session->userdata('fullname'));
                $this->pembaharuan_data('register_surat_masuk', $data_sm, 'id', $data['register_id']);
                $penginput = $this->session->userdata('jabatan') . ' (' . $this->session->userdata('fullname') . ')';

                foreach ($data['jabatan'] as $jabatan_id) {
                    $ke = $jabatan_id;
                    $queryPlh = $this->get_seleksi($this->db_sso . '.v_plh', 'plh_id_jabatan', $jabatan_id);

                    if ($queryPlh->row()->pegawai_id != null) {
                        $jab = $queryPlh->row()->nama;
                        $nama_pegawai = $queryPlh->row()->nama_pegawai;
                        $tujuanNotif = $queryPlh->row()->pegawai_id;
                        $pesan = 'Assalamualaikum Wr. Wb., Yth. *' . $jab . ' (' . $nama_pegawai . ')*. Ada disposisi surat masuk baru dari *' . $data['pengirim'] . '* perihal *' . $perihal . '* dengan Disposisi : *' . $data['ket'] . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' sebagai ' . $jab . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                    } else {
                        $queryUser = $this->get_seleksi2($this->db_sso . '.v_users', 'jab_id', $jabatan_id, 'status_pegawai', '1');
                        $jab = $queryUser->row()->jabatan;
                        $tujuanNotif = $queryUser->row()->pegawai_id;
                        $pesan = 'Assalamualaikum Wr. Wb., Yth. *' . $jab . ' (' . $queryUser->row()->fullname . ')*. Ada disposisi surat masuk baru dari *' . $data['pengirim'] . '* perihal *' . $perihal . '* dengan Disposisi : *' . $data['ket'] . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                    }

                    $dataNotif = array(
                        'jenis_pesan' => 'surat',
                        'id_pemohon' => $this->session->userdata('pegawai_id'),
                        'pesan' => $pesan,
                        'id_tujuan' => $tujuanNotif,
                        'created_by' => $penginput,
                        'created_on' => date('Y-m-d H:i:s')
                    );

                    $dataDispo = array(
                        'id_sm' => $data['register_id'],
                        'jab_id' => $this->session->userdata('jab_id'),
                        'disposisi' => $jabatan_id,
                        'ket_disposisi' => $data['ket'],
                        'created_by' => $penginput,
                        'created_on' => date("Y-m-d H:i:s")
                    );

                    $dataStatus = array(
                        'id_sm' => $data['register_id'],
                        'userid' => $data['pengguna_id'],
                        'status' => '2',
                        'tujuan' => $ke,
                        'ket' => $data['ket'],
                        'created_by' => $penginput,
                        'created_on' => date("Y-m-d H:i:s")
                    );

                    $this->kirim_notif($dataNotif);
                    $queryDispo = $this->simpan_data('register_disposisi', $dataDispo);
                    $queryStatus = $this->simpan_data('status_surat_masuk', $dataStatus);
                }
            } else {
                $penginput = $this->session->userdata('jabatan') . ' (' . $this->session->userdata('fullname') . ')';

                $queryPlh = $this->get_seleksi($this->db_sso . '.v_plh', 'plh_id_jabatan', $tujuan);
                if ($queryPlh->row()->pegawai_id != null) {
                    $notifKe = $queryPlh->row()->pegawai_id;
                    $notif_to = $queryPlh->row()->nama_pegawai;
                    if ($queryPlh->row()->jabatan == 'Wakil Ketua') {
                        $jab = $queryPlh->row()->jabatan;
                    } else {
                        $jab = $queryPlh->row()->nama;
                    }
                } else {
                    $queryUser = $this->get_seleksi2($this->db_sso . '.v_users', 'jab_id', $tujuan, 'status_pegawai', '1');
                    $notifKe = $queryUser->row()->pegawai_id;
                    $jab = $queryUser->row()->jabatan;
                    $notif_to = $queryUser->row()->fullname;
                }

                if ($data['progres'] == '3') {
                    if (!$data['ket']) {
                        $ket = "Dilaksanakan";
                    }
                    $data_sm = array('status' => '1', 'modified_on' => date("Y-m-d H:i:s"), 'modified_by' => $this->session->userdata('fullname'));
                    $this->pembaharuan_data('register_surat_masuk', $data_sm, 'id', $data['register_id']);
                    $pesan = 'Assalamualaikum Wr. Wb., Yth. *' . $jab . ' (' . $notif_to . ')*. Surat Masuk perihal *' . $perihal . '* sedang dilaksanakan oleh *' . $data['pengirim'] . '*. Demikian diinformasikan, Terima Kasih atas perhatian.';
                    $data = array(
                        'id_sm' => $data['register_id'],
                        'userid' => $data['pengguna_id'],
                        'status' => '3',
                        'tujuan' => $tujuan,
                        'ket' => $ket,
                        'created_by' => $penginput,
                        'created_on' => date("Y-m-d H:i:s")
                    );
                } elseif ($data['progres'] == '4') {
                    if (!$data['ket']) {
                        $ket = "Selesai";
                    }
                    $data_sm = array('status' => '2', 'modified_on' => date("Y-m-d H:i:s"), 'modified_by' => $this->session->userdata('fullname'));
                    $this->pembaharuan_data('register_surat_masuk', $data_sm, 'id', $data['register_id']);
                    $pesan = 'Assalamualaikum Wr. Wb., Yth. *' . $jab . ' (' . $notif_to . ')*. Surat masuk perihal *' . $perihal . '* telah selesai dilaksanakan oleh *' . $data['pengirim'] . '*. Demikian diinformasikan, Terima Kasih atas perhatian.';
                    $data = array(
                        'id_sm' => $data['register_id'],
                        'userid' => $data['pengguna_id'],
                        'status' => '4',
                        'tujuan' => $tujuan,
                        'ket' => $data['ket'],
                        'created_by' => $penginput,
                        'created_on' => date("Y-m-d H:i:s")
                    );
                }

                $dataNotif = array(
                    'jenis_pesan' => 'surat',
                    'id_pemohon' => $this->session->userdata('pegawai_id'),
                    'pesan' => $pesan,
                    'id_tujuan' => $notifKe,
                    'created_by' => $penginput,
                    'created_on' => date('Y-m-d H:i:s')
                );

                $this->kirim_notif($dataNotif);
                $queryStatus = $this->simpan_data('status_surat_masuk', $data);
            }

            if ($queryDispo) {
                if ($queryStatus == 1 && $queryDispo == 1) {
                    return json_encode(array('success' => true, 'message' => 'Simpan Data Disposisi Berhasil, Notifikasi Akan Segera Dikirim'));
                } else {
                    return json_encode(array('success' => false, 'message' => 'Simpan Data Disposisi Gagal'));
                }
            } else {
                return json_encode(array('success' => true, 'message' => 'Simpan Data Pelaksanaan Berhasil, Notifikasi Akan Segera Dikirim'));
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

        $cekValid = $this->get_seleksi('register_surat_masuk', 'id', $register_id);
        //cek apakah surat sudah di validasi oleh penelaah
        if ($cekValid->row()->valid == '2') {
            //jika surat valid
            $array_progres = array('0' => 'Pilih Progres Surat', '2' => 'Disposisi', '3' => 'Dilaksanakan', '4' => 'Selesai');

            if ($this->session->userdata('jab_id') == '1') {
                //user Ketua
                $array_jabatan = array('4' => 'Panitera', '5' => 'Sekretaris');
                $jenis_jabatan = form_multiselect('jenis_jabatan[]', $array_jabatan, '', 'class="form-control" data-placeholder="Pilih Disposisi" required id="jenis_jabatan"');
            } elseif ($this->session->userdata('jab_id') == '4') {
                //user Panitera
                $array_jabatan = array('6' => 'Panitera Muda Gugatan', '7' => 'Panitera Muda Permohonan', '8' => 'Panitera Muda Jinayat', '9' => 'Panitera Muda Hukum');
                $jenis_jabatan = form_multiselect('jenis_jabatan[]', $array_jabatan, '', 'class="form-control" data-placeholder="Pilih Disposisi" required id="jenis_jabatan"');
            } elseif ($this->session->userdata('jab_id') == '5') {
                //user Sekretaris
                $array_jabatan = array('10' => 'Kepala Sub Bagian Umum dan Keuangan', '11' => 'Kepala Sub Bagian Kepegawaian', '12' => 'Kepala Sub Bagian PTIP');
                $jenis_jabatan = form_multiselect('jenis_jabatan[]', $array_jabatan, '', 'class="form-control" data-placeholder="Pilih Disposisi" required id="jenis_jabatan"');
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

    public function get_progres_sm($id, $tgl)
    {
        $this->db->where("created_on <=", $tgl);
        $this->db->where('id_sm', $id);
        return $this->db->select('*')->from('v_progres_surat')->get()->result();
    }

    public function hapus_data($tabel, $id)
    {
        try {
            $this->db->where('id', $id);
            $this->db->delete('register_surat_masuk');
            $title = "Hapus Surat Masuk [Surat Masuk=<b>" . $id . "</b>]<br />Hapus tabel <b>sias_arsip_sm</b>]";
            $descrip = '';
            $this->add_audittrail("INSERT", $title, $tabel, $descrip);
            return 1;
        } catch (Exception $e) {
            return $e;
        }
    }
}