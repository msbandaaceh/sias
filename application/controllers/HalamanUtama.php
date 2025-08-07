<?php

class HalamanUtama extends MY_Controller
{
    public function index()
    {
        #die(var_dump($this->session->all_userdata()));
        $data['peran'] = $this->peran;
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
            'laporan_arsip'
        ];

        if (in_array($halaman, $allowed)) {
            $data['peran'] = $this->peran;
            $data['page'] = $halaman;
            if ($halaman == 'arsip_sm') {
                $data['arsip_sm'] = $this->model->all_sm_data();
            } elseif ($halaman == 'arsip_sk') {
                $halaman = '500';
            } elseif ($halaman == 'arsip_digital') {
                $halaman = '500';
            } elseif ($halaman == 'dashboard') {
                $data['jumlah_sm'] = count($this->model->all_sm_data());
            } elseif ($halaman == 'validasi_sm') {
                $data['validasi'] = $this->model->validasi_surat_masuk($this->peran);
            } elseif ($halaman == 'surat_masuk') {
                $data['surat_masuk'] = $this->model->register_surat_masuk($this->peran);
            } elseif ($halaman == 'surat_keluar') {
                $halaman = '500';
            } elseif ($halaman == 'disposisi') {
                $data['disposisi'] = $this->model->disposisi_surat_masuk($this->peran);
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
            echo json_encode(['valid' => false, 'message' => 'Token Expired, Silakan login ulang', 'url' => $cookie_domain . 'login']);
        }
    }

    public function keluar()
    {
        $this->session->sess_destroy();
        redirect($this->config->item('sso_server') . '/keluar');
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
}