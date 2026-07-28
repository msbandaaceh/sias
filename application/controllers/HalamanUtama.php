<?php

class HalamanUtama extends MY_Controller
{
    public function index()
    {
        #die(var_dump($this->session->all_userdata()));
        $data['peran'] = $this->session->userdata('peran');
        $data['page'] = 'dashboard';

        $this->load->view('layout', $data);
    }

    public function page($halaman)
    {
        // Amanin nama file view agar tidak sembarang file bisa diload
        $allowed = [
            'dashboard',
            'validasi_sm',
            'surat_masuk',
            'surat_keluar',
            'disposisi',
            'arsip_sm',
            'arsip_sk',
            'arsip_digital',
            'laporan_sm',
            'laporan_sk',
            'laporan_disposisi',
            'laporan_progres',
            'laporan_arsip',
            'panduan',
            'dokumentasi'
        ];

        if (in_array($halaman, $allowed)) {
            $data['peran'] = $this->session->userdata('peran');
            $data['page'] = $halaman;
            
            // Cek akses halaman berdasarkan peran
            if ($halaman == 'panduan') {
                // Panduan hanya untuk admin, penelaah, operator, pejabat
                if (!in_array($data['peran'], ['admin', 'penelaah', 'operator', 'pejabat'])) {
                    show_404();
                    return;
                }
            } elseif ($halaman == 'dokumentasi') {
                // Dokumentasi hanya untuk admin
                if ($data['peran'] != 'admin') {
                    show_404();
                    return;
                }
            }
            
            if ($halaman == 'arsip_sm') {
                // Tidak perlu load semua data, akan di-load via AJAX (server-side processing)
                $data['arsip_sm'] = [];
            } elseif ($halaman == 'arsip_sk') {
                $halaman = '500';
            } elseif ($halaman == 'arsip_digital') {
                $halaman = '500';
            } elseif ($halaman == 'dashboard') {
                // Handle all_sm_data dengan aman
                $all_sm_data = $this->model->all_sm_data();
                $data['jumlah_sm'] = (is_array($all_sm_data) || is_object($all_sm_data)) ? count($all_sm_data) : 0;
                $data['jumlah_sk'] = 0; // Placeholder untuk surat keluar
                $data['jumlah_arsip_digital'] = 0; // Placeholder untuk arsip digital
                
                // Data untuk dashboard berdasarkan peran
                if (in_array($data['peran'], ['admin', 'penelaah', 'pejabat'])) {
                    $jab_id = $this->session->userdata('jab_id');
                    
                    // Validasi surat masuk - handle jika return integer
                    $validasi_data = $this->model->validasi_surat_masuk($jab_id);
                    $data['jumlah_validasi'] = (is_array($validasi_data) || is_object($validasi_data)) ? count($validasi_data) : 0;
                    
                    // Register surat masuk
                    $register_data = $this->model->register_surat_masuk($jab_id);
                    $data['jumlah_surat_masuk'] = (is_array($register_data) || is_object($register_data)) ? count($register_data) : 0;
                    
                    // Disposisi surat masuk
                    $disposisi_data = $this->model->disposisi_surat_masuk($jab_id);
                    $data['jumlah_disposisi'] = (is_array($disposisi_data) || is_object($disposisi_data)) ? count($disposisi_data) : 0;
                    
                    // Surat terbaru (5 terakhir)
                    $all_sm = $this->model->all_sm_data();
                    if (is_array($all_sm) && count($all_sm) > 0) {
                        $data['surat_terbaru'] = array_slice($all_sm, 0, 5);
                    } else {
                        $data['surat_terbaru'] = array();
                    }
                } else {
                    $data['jumlah_validasi'] = 0;
                    $data['jumlah_surat_masuk'] = 0;
                    $data['jumlah_disposisi'] = 0;
                    $data['surat_terbaru'] = array();
                }
            } elseif ($halaman == 'validasi_sm') {
                $data['validasi'] = $this->model->validasi_surat_masuk($this->session->userdata('jab_id'));
            } elseif ($halaman == 'surat_masuk') {
                $data['surat_masuk'] = $this->model->register_surat_masuk($this->session->userdata('jab_id'));
            } elseif ($halaman == 'surat_keluar') {
                $halaman = '500';
            } elseif ($halaman == 'disposisi') {
                $data['disposisi'] = $this->model->disposisi_surat_masuk($this->session->userdata('jab_id'));
            } elseif ($halaman == 'laporan_sm') {
                $data['laporan_sm'] = $this->model->all_sm_data();
            } elseif ($halaman == 'laporan_sk') {
                $halaman = '500';
            } elseif ($halaman == 'laporan_disposisi') {
                $halaman = '500';
            } elseif ($halaman == 'laporan_progres') {
                $halaman = '500';
            } elseif ($halaman == 'laporan_arsip') {
                $halaman = '500';
            }
            $this->load->view($halaman, $data);
        } else {
            show_404();
        }
    }

    public function cek_token_sso()
    {
        $token = $this->input->cookie('sso_token');
        $cookie_domain = $this->config->item('sso_server');
        $sso_api = $cookie_domain . "api/cek_token?sso_token={$token}";
        $response = file_get_contents($sso_api);
        $data = json_decode($response, true);

        if ($data['status'] == 'success') {
            echo json_encode(['valid' => true]);
        } else {
            echo json_encode(['valid' => false, 'message' => 'Session Expired, Silakan login ulang', 'url' => $cookie_domain . 'login']);
        }
    }

    public function keluar()
    {
        $sso_server = $this->config->item('sso_server');
        $this->session->sess_destroy();
        redirect($sso_server . '/keluar');
    }

    public function show_role()
    {
        $id = $this->input->post('id');
        $data = [
            "tabel" => "v_users",
            "kolom_seleksi" => "status_pegawai",
            "seleksi" => "1"
        ];

        $users = $this->apihelper->get('apiclient/get_data_seleksi', $data);

        $pegawai = array();
        if ($users['status_code'] === '200') {
            foreach ($users['response']['data'] as $item) {
                $pegawai[$item['userid']] = $item['fullname'];
            }
        }

        if ($id != '-1') {
            $query = $this->model->get_seleksi('peran', 'id', $id);

            echo json_encode(
                array(
                    'pegawai' => $users['response']['data'],
                    'role' => $pegawai,
                    'id' => $query->row()->id,
                    'editPegawai' => $query->row()->userid,
                    'editPeran' => $query->row()->role
                )
            );
        } else {
            $dataPeran = $this->model->get_data_peran();
            #die(var_dump($dataPeran));

            echo json_encode(
                array(
                    'pegawai' => $users['response']['data'],
                    'role' => $pegawai,
                    'data_peran' => $dataPeran
                )
            );
        }

        return;

        #die(var_dump($users["response"]["data"]));
        #echo $users["response"]["data"];

    }

    public function simpan_peran()
    {
        $id = $this->input->post('id');
        $pegawai = $this->input->post('pegawai');
        $peran = $this->input->post('peran');

        if ($id) {
            $data = array(
                'userid' => $pegawai,
                'role' => $peran,
                'modified_by' => $this->session->userdata('fullname'),
                'modified_on' => date('Y-m-d H:i:s')
            );

            $query = $this->model->pembaharuan_data('peran', $data, 'id', $id);
        } else {
            $query = $this->model->get_seleksi('peran', 'userid', $pegawai);
            if ($query->num_rows() > 0) {
                $this->session->set_flashdata('info', '2');
                $this->session->set_flashdata('pesan_gagal', 'Pegawai tersebut sudah memiliki peran');
                redirect('');
                return;
            }

            $data = array(
                'userid' => $pegawai,
                'role' => $peran,
                'created_by' => $this->session->userdata('fullname'),
                'created_on' => date('Y-m-d H:i:s')
            );

            $query = $this->model->simpan_data('peran', $data);
        }

        if ($query === 1) {
            echo json_encode(['success' => true, 'message' => 'Penunjukan Peran Pegawai Berhasil']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal Menunjuk Peran Pegawai']);
        }
    }

    public function aktif_peran()
    {
        $id = $this->input->post('id');

        $data = array(
            'hapus' => '0',
            'modified_by' => $this->session->userdata('username'),
            'modified_on' => date('Y-m-d H:i:s')
        );

        $query = $this->model->pembaharuan_data('peran', $data, 'id', $id);
        if ($query == '1') {
            echo json_encode(
                array(
                    'st' => '1'
                )
            );
        } else {
            echo json_encode(
                array(
                    'st' => '0'
                )
            );
        }
    }

    public function blok_peran()
    {
        $id = $this->input->post('id');

        $data = array(
            'hapus' => '1',
            'modified_by' => $this->session->userdata('username'),
            'modified_on' => date('Y-m-d H:i:s')
        );

        $query = $this->model->pembaharuan_data('peran', $data, 'id', $id);
        if ($query == '1') {
            echo json_encode(
                array(
                    'st' => '1'
                )
            );
        } else {
            echo json_encode(
                array(
                    'st' => '0'
                )
            );
        }
    }

    /**
     * Handle AJAX request untuk server-side processing DataTables arsip surat masuk
     * OPTIMASI: Menggunakan pagination di server-side untuk mengurangi beban query
     */
    public function get_arsip_sm_datatables()
    {
        // Get DataTables parameters
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search')['value'] ?? '';
        $order_column = intval($this->input->post('order')[0]['column'] ?? 0);
        $order_dir = $this->input->post('order')[0]['dir'] ?? 'DESC';

        // Get data from model
        $result = $this->model->get_arsip_sm_datatables($start, $length, $search, $order_column, $order_dir);

        // Format response untuk DataTables
        $response = [
            'draw' => $draw,
            'recordsTotal' => $result['total_records'],
            'recordsFiltered' => $result['total_filtered'],
            'data' => []
        ];

        // Format data untuk DataTables
        $no = $start + 1;
        foreach ($result['data'] as $row) {
            $response['data'][] = [
                $no++,
                $this->format_no_agenda($row),
                $this->format_no_surat($row),
                $row->pengirim ?? '',
                $row->perihal ?? '',
                $row->tgl_surat ?? '',
                $row->tgl_terima ?? '',
                $this->format_aksi($row)
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /**
     * Format nomor agenda dengan badge status
     */
    private function format_no_agenda($row)
    {
        $html = ($row->no_agenda ?? '');
        
        // Badge untuk status dibaca
        if (!empty($row->dibaca)) {
            $html .= '<br> <span class="badge badge-success"><i class="fas fa-check" title="Surat Sudah Dibaca"></i></span>';
        }
        
        // Badge untuk status surat
        if (!empty($row->status)) {
            if ($row->status == 1) {
                $html .= ' <span class="badge badge-warning"><i class="fas fa-hourglass-half" title="Surat Sedang Ditindaklanjuti"></i></span>';
            } elseif ($row->status == 2) {
                $html .= ' <span class="badge badge-success"><i class="fas fa-thumbs-up" title="Surat Selesai Diproses"></i></span>';
            }
        } else {
            $html .= ' <span class="badge badge-info"><i class="fas fa-info" title="Surat Belum Diproses"></i></span>';
        }
        
        return $html;
    }

    /**
     * Format nomor surat dengan link detail
     */
    private function format_no_surat($row)
    {
        $encrypted_id = base64_encode($this->encryption->encrypt($row->id));
        return '<button class="dropdown-item" data-target="#detilModal" onclick="BukaDetilSurat(\'arsip\', \'' . $encrypted_id . '\')" data-toggle="modal" style="background: transparent; border: none !important;"><i class="bx bx-edit-alt me-1"></i><p class="text-info"><b>' . ($row->no_sm ?? '') . '</b></p></button>';
    }

    /**
     * Format kolom aksi (hanya untuk admin dan penelaah)
     */
    private function format_aksi($row)
    {
        $peran = $this->session->userdata('peran');
        if (!in_array($peran, ['admin', 'penelaah'])) {
            return '';
        }

        $encrypted_id = base64_encode($this->encryption->encrypt($row->id));
        return '<div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-toggle="dropdown">
                <i class="fas fa-cogs"></i>
            </button>
            <div class="dropdown-menu">
                <button class="dropdown-item" data-target="#tambah-modal" onclick="ModalInputSurat(\'' . $encrypted_id . '\')" data-toggle="modal"><i class="bx bx-edit-alt me-1"></i>EDIT</button>
                <a class="dropdown-item hapus-btn" data-id="' . $encrypted_id . '"><i class="bx bx-trash me-1"></i>HAPUS</a>
            </div>
        </div>';
    }
}