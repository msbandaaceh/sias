<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HalamanPengaturan extends MY_Controller
{
    public function get_klasifikasi_datatables()
    {
        // DataTables server-side parameters
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search')['value'] ?? '';
        $order_column = intval($this->input->post('order')[0]['column'] ?? 0);
        $order_dir = $this->input->post('order')[0]['dir'] ?? 'DESC';

        $result = $this->model->get_klasifikasi_surat_datatables($start, $length, $search, $order_column, $order_dir);

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
                $row->id ?? '',
                $row->kode ?? '',
                $row->jenis ?? '',
                $row->keterangan ?? ''
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function get_klasifikasi_surat_by_id()
    {
        $id = (int) $this->input->post('id');
        $kode = "";
        $jenis = "";
        $keterangan = "";
        $judul = "";
        if (isset($id) && $id > 0) {
            $row = $this->model->get_seleksi_array('ref_klasifikasi_surat', ['id' => $id])->row();
            if ($row) {
                $kode = $row->kode;
                $jenis = $row->jenis;
                $keterangan = $row->keterangan;
            }
            $judul = "Edit Klasifikasi Surat";
        } else {
            $judul = "Tambah Klasifikasi Surat";
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => [
                'id' => (int) $id,
                'kode' => $kode,
                'jenis' => $jenis,
                'keterangan' => $keterangan,
                'judul' => $judul,
            ]
        ]);
    }

    public function simpan_klasifikasi_surat()
    {
        $this->form_validation->set_rules('kode', 'Kode Klasifikasi', 'trim|required');
        $this->form_validation->set_rules('jenis', 'Jenis Klasifikasi', 'trim|required');
        $this->form_validation->set_rules('ket', 'Keterangan Klasifikasi', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => validation_errors()]);
            return;
        }

        $id = $this->input->post('id');
        $by = $this->session->userdata('fullname');
        $now = date('Y-m-d H:i:s');

        if ($id) {
            $data = [
                'kode' => $this->input->post('kode'),
                'jenis' => $this->input->post('jenis'),
                'keterangan' => $this->input->post('ket'),
                'modified_by' => $by,
                'modified_on' => $now,
            ];
            $result = $this->model->pembaharuan_data('ref_klasifikasi_surat', $data, 'id', $id);
        } else {
            $data = [
                'kode' => $this->input->post('kode'),
                'jenis' => $this->input->post('jenis'),
                'keterangan' => $this->input->post('ket'),
                'created_by' => $by,
                'created_on' => $now,
            ];
            $result = $this->model->simpan_data('ref_klasifikasi_surat', $data);
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => (bool) $result]);
    }

    public function hapus_klasifikasi_surat()
    {
        $id = $this->input->post('id');
        $data = [
            'hapus' => '1',
            'modified_by' => $this->session->userdata('fullname'),
            'modified_on' => date('Y-m-d H:i:s'),
        ];
        $result = $this->model->pembaharuan_data('ref_klasifikasi_surat', $data, 'id', $id);
        header('Content-Type: application/json');
        echo json_encode(['success' => (bool) $result]);
    }

    public function get_jenis_surat_datatables()
    {
        // DataTables server-side parameters
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search')['value'] ?? '';
        $order_column = intval($this->input->post('order')[0]['column'] ?? 0);
        $order_dir = $this->input->post('order')[0]['dir'] ?? 'DESC';

        $result = $this->model->get_jenis_surat_datatables($start, $length, $search, $order_column, $order_dir);

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
                $row->id ?? '',
                $row->jenis_surat ?? ''
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function get_data_jenis_surat()
    {
        $rows = $this->model->get_all_ref_jenis_surat();
        $result = [];
        foreach ($rows as $r) {
            $result[] = [
                'id' => $r->id,
                'jenis_surat' => $r->jenis_surat,
                'created_by' => $r->created_by,
                'created_on' => $r->created_on,
                'modified_by' => $r->modified_by,
                'modified_on' => $r->modified_on,
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function get_jenis_surat_by_id()
    {
        $id = (int) $this->input->post('id');
        $jenis_surat = "";
        $kode = "";
        $judul = "";
        if (isset($id) && $id > 0) {
            $row = $this->model->get_ref_jenis_surat_by_id($id);
            if ($row) {
                $jenis_surat = $row->jenis_surat;
                $kode = $row->kode_penetapan;
            }
            $judul = "Edit Jenis Surat";
        } else {
            $judul = "Tambah Jenis Surat";
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => [
                'id' => (int) $id,
                'jenis_surat' => $jenis_surat,
                'kode_penetapan' => $kode,
                'judul' => $judul,
            ]
        ]);
    }

    public function simpan_jenis_surat()
    {
        $this->form_validation->set_rules('jenis_surat', 'Jenis Surat', 'trim|required|max_length[100]');

        if ($this->form_validation->run() === FALSE) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => validation_errors()]);
            return;
        }

        $id = $this->input->post('id');
        $by = $this->session->userdata('fullname');
        $now = date('Y-m-d H:i:s');

        if ($id) {
            $data = [
                'jenis_surat' => $this->input->post('jenis_surat'),
                'kode_penetapan' => $this->input->post('kode'),
                'modified_by' => $by,
                'modified_on' => $now,
            ];
            $result = $this->model->pembaharuan_data('ref_jenis_surat', $data, 'id', $id);
        } else {
            $data = [
                'jenis_surat' => $this->input->post('jenis_surat'),
                'kode_penetapan' => $this->input->post('kode'),
                'created_by' => $by,
                'created_on' => $now,
            ];
            $result = $this->model->simpan_data('ref_jenis_surat', $data);
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => (bool) $result]);
    }

    public function hapus_jenis_surat()
    {
        $id = $this->input->post('id');
        $data = [
            'hapus' => '1',
            'modified_by' => $this->session->userdata('fullname'),
            'modified_on' => date('Y-m-d H:i:s'),
        ];
        $result = $this->model->pembaharuan_data('ref_jenis_surat', $data, 'id', $id);
        header('Content-Type: application/json');
        echo json_encode(['success' => (bool) $result]);
    }

    public function get_nomor_surat_by_id()
    {
        $id = (int) $this->input->post('id');
        $daftar_jenis_surat = $this->get_list('ref_jenis_surat', 'jenis_surat', 'Pilih Jenis Surat');
        $daftar_klasifikasi = $this->get_list('ref_klasifikasi_surat', ['kode', 'jenis'], 'Pilih Klasifikasi');
        if (isset($id) && $id > 0) {
            $query = $this->model->get_seleksi_array('register_nomor_surat', ['id' => $id])->row();
            $judul = "Edit Nomor Surat";
            $tujuan_surat = $query->tujuan_surat;
            $perihal = $query->perihal;

            $jenis_surat = form_dropdown('jenis_surat', $daftar_jenis_surat, $query->kode_penetapan, 'id="jenis_surat" class="form-control select2"');
            $klasifikasi_surat = form_dropdown('klasifikasi_surat', $daftar_klasifikasi, $query->kode_klasifikasi, 'id="klasifikasi_surat" class="form-control select2"');
        } else {
            $judul = "Tambah Nomor Surat";
            $jenis_surat = form_dropdown('jenis_surat', $daftar_jenis_surat, '', 'id="jenis_surat" class="form-control select2"');
            $klasifikasi_surat = form_dropdown('klasifikasi_surat', $daftar_klasifikasi, '', 'id="klasifikasi_surat" class="form-control select2"');
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => [
                'id' => (int) $id,
                'no_agenda' => $query->no_agenda ?? '',
                'no_surat' => $query->no_surat ?? '',
                'tujuan_surat' => $tujuan_surat ?? '',
                'perihal' => $perihal ?? '',
                'jenis_surat' => $jenis_surat,
                'klasifikasi_surat' => $klasifikasi_surat,
                'judul' => $judul,
            ]
        ]);
    }

    /**
     * Lihat detail nomor surat (read-only) — mengembalikan label teks polos,
     * dipakai ModalLihatNomor di UI.
     */
    public function lihat_nomor_surat_by_id()
    {
        $id = (int) $this->input->post('id');
        $row = $this->model->get_seleksi_array('v_register_nomor_surat', ['id' => $id])->row();
        $no_surat = $this->buat_nomor_by_id($id);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => (bool) $row,
            'data' => [
                'no_agenda' => $row->no_agenda ?? '',
                'no_surat' => $no_surat,
                'tujuan_surat' => $row->tujuan_surat ?? '',
                'perihal' => $row->perihal ?? '',
                'jabatan' => $row->jabatan ?? '',
                'jenis_surat_label' => $row->jenis_surat,
                'klasifikasi_label' => $row->jenis,
            ]
        ]);
    }

    public function simpan_nomor_surat()
    {
        $this->form_validation->set_rules('tujuan_surat', 'Tujuan Surat', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('perihal', 'Perihal', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('jenis_surat', 'Jenis Surat', 'trim|required');
        $this->form_validation->set_rules('klasifikasi_surat', 'Klasifikasi Surat', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => validation_errors()]);
            return;
        }

        $id = $this->input->post('id');
        $by = $this->session->userdata('fullname');
        $now = date('Y-m-d H:i:s');
        $jabatan = $this->input->post('jabatan');
        $tahun = date('Y');
        $kode_penetapan = $this->input->post('jenis_surat');
        $kode_klasifikasi = $this->input->post('klasifikasi_surat');
        if ($jabatan === 'Ketua') {
            $queryPlh = $this->model->get_seleksi($this->session->userdata('sso_db') . '.v_plh', 'plh_id_jabatan', '1');
            if ($queryPlh->row()->pegawai_id != null) {
                if ($queryPlh->row()->jabatan == 'Wakil Ketua') {
                    $kode_jabatan = 'WKMS.' . $this->session->userdata('kode_satker');
                } else {
                    $kode_jabatan = 'KMS.' . $this->session->userdata('kode_satker');
                }
            } else {
                $kode_jabatan = 'KMS.' . $this->session->userdata('kode_satker');
            }
        } elseif ($jabatan === 'Panitera') {
            $kode_jabatan = 'PAN.MS.' . $this->session->userdata('kode_satker');
        } elseif ($jabatan === 'Sekretaris') {
            $kode_jabatan = 'SEK.MS.' . $this->session->userdata('kode_satker');
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Jabatan tidak valid.']);
            return;
        }

        if ($id > 0) {
            $data = [
                'tujuan_surat' => $this->input->post('tujuan_surat'),
                'perihal' => $this->input->post('perihal'),
                'kode_penetapan' => $kode_penetapan,
                'kode_klasifikasi' => $kode_klasifikasi,
                'kode_jabatan' => $kode_jabatan,
                'tahun' => $tahun,
                'modified_by' => $by,
                'modified_on' => $now,
            ];

            if ($this->session->userdata('peran') === 'admin') {
                $data['no_surat'] = $this->input->post('no_surat');
            }

            $result = $this->model->pembaharuan_data('register_nomor_surat', $data, 'id', $id);
        } else {

            $data = [
                'tujuan_surat' => $this->input->post('tujuan_surat'),
                'perihal' => $this->input->post('perihal'),
                'kode_penetapan' => $kode_penetapan,
                'kode_klasifikasi' => $kode_klasifikasi,
                'kode_jabatan' => $kode_jabatan,
                'tahun' => $tahun,
                'jabatan' => $jabatan,
                'created_by' => $by,
                'created_on' => $now
            ];

            $result = $this->model->simpan_no_surat_baru($data);
        }

        if ($result) {
            header('Content-Type: application/json');
            if ($id > 0) {
                echo json_encode(['success' => true, 'message' => 'Berhasil Menyimpan data nomor surat.']);
            } else {
                $no_surat = $this->buat_nomor_by_id($result['pesan']);
                echo json_encode(['success' => true, 'message' => 'Berhasil Menyimpan data nomor surat. No Surat Anda ' . $no_surat]);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data nomor surat.']);
        }
    }

    public function hapus_nomor_surat()
    {
        $id = $this->input->post('id');
        $data = [
            'hapus' => '1',
            'modified_by' => $this->session->userdata('fullname'),
            'modified_on' => date('Y-m-d H:i:s'),
        ];
        $result = $this->model->pembaharuan_data('register_nomor_surat', $data, 'id', $id);
        header('Content-Type: application/json');
        echo json_encode(['success' => (bool) $result]);
    }

    public function get_nomor_surat_ketua_datatables()
    {
        // DataTables server-side parameters
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search')['value'] ?? '';
        $order_column = intval($this->input->post('order')[0]['column'] ?? 0);
        $order_dir = $this->input->post('order')[0]['dir'] ?? 'DESC';

        $result = $this->model->get_nomor_surat_ketua_datatables($start, $length, $search, $order_column, $order_dir);

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
                $row->id ?? '',
                $row->tujuan_surat ?? '',
                $row->perihal ?? '',
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function get_nomor_surat_panitera_datatables()
    {
        // DataTables server-side parameters
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search')['value'] ?? '';
        $order_column = intval($this->input->post('order')[0]['column'] ?? 0);
        $order_dir = $this->input->post('order')[0]['dir'] ?? 'DESC';

        $result = $this->model->get_nomor_surat_panitera_datatables($start, $length, $search, $order_column, $order_dir);

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
                $row->id ?? '',
                $row->tujuan_surat ?? '',
                $row->perihal ?? '',
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function get_nomor_surat_sekretaris_datatables()
    {
        // DataTables server-side parameters
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search')['value'] ?? '';
        $order_column = intval($this->input->post('order')[0]['column'] ?? 0);
        $order_dir = $this->input->post('order')[0]['dir'] ?? 'DESC';

        $result = $this->model->get_nomor_surat_sekretaris_datatables($start, $length, $search, $order_column, $order_dir);

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
                $row->id ?? '',
                $row->tujuan_surat ?? '',
                $row->perihal ?? '',
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    private function get_list($tabel, $kolom, $labelKosong = 'Pilih ...')
    {
        $query = $this->model->get_seleksi_array($tabel, ['hapus' => '0'], ['id' => 'ASC'])->result();
        $list[''] = $labelKosong;
        foreach ($query as $q) {
            if (is_array($kolom)) {
                $labelParts = [];
                foreach ($kolom as $c) {
                    if (!empty($q->$c))
                        $labelParts[] = $q->$c;
                }
                $list[$q->id] = implode(' - ', $labelParts);
            } else {
                $list[$q->id] = $q->$kolom;
            }
        }

        return $list;
    }

    private function buat_nomor_by_id($id)
    {
        $row = $this->model->get_seleksi_array('v_register_nomor_surat', ['id' => $id])->row();

        $kode_penetapan = '';
        if ($row->kode_jenis) {
            $kode_penetapan = $row->kode_jenis . '.';
        }
        $bulan = $this->tanggalhelper->monthRoman(date('m', strtotime($row->created_on)));
        $no_surat = $row->no_surat . '/' . $row->kode_jabatan . '/' . $kode_penetapan . $row->kode . '/' . $bulan . '/' . $row->tahun;

        return $no_surat;
    }
}