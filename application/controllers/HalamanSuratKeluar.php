<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HalamanSuratKeluar extends MY_Controller
{
    public function get_data_surat_keluar()
    {
        $id = $this->input->post('id');

        if (!$id || $id == '-1') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
            return;
        }

        $row = $this->model->get_surat_keluar_by_id($id);
        if (!$row) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Surat keluar tidak ditemukan']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => [
                'id' => $row->id,
                'no_sk' => $row->no_sk ?? '',
                'tgl_surat' => $row->tgl_surat ?? '',
                'tgl_surat_text' => $this->tanggalhelper->konversiTanggal($row->tgl_surat) ?? '',
                'jenis_surat' => $row->jenis_surat ?? '',
                'klasifikasi_surat' => $row->kode_surat ?? '',
                'jenis_kertas' => $row->jenis_kertas ?? '',
                'template_id' => $row->template_id ?? '',
                'body_surat' => $row->isi_surat ?? '',
                'nama_satker' => $this->session->userdata('nama_satker'),
                'penandatangan_id' => $row->penandatangan_id ?? '',
                'penandatangan_nama' => $row->penandatangan_nama ?? '',
                'penandatangan_jabatan' => $row->penandatangan_jabatan ?? '',
                'penandatangan_tte' => $row->penandatangan_tte ?? '',
                'bulan' => $row->bulan ?? '',
                'tahun' => $row->tahun ?? '',
                'lokasi_surat' => $row->lokasi_surat ?? '',
            ]
        ]);
    }

    public function get_pensign_template()
    {
        $respon = $this->model->get_pensign_template();
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $respon['hasil'],
            'pesan' => $respon['pesan'],
            'data' => [
                'body_surat' => $respon['data']->isi_template ?? '',
            ]
        ]);
    }

    public function save_surat_keluar()
    {
        $this->form_validation->set_rules('template_id', 'Template', 'required|integer');
        //$this->form_validation->set_rules('jenis_surat', 'Jenis Surat', 'required|integer');
        //$this->form_validation->set_rules('klasifikasi_surat', 'Klasifikasi Surat', 'required|integer');
        //$this->form_validation->set_rules('ukuran_kertas', 'Ukuran Kertas', 'required|in_array[A4/F4]');
        //$this->form_validation->set_rules('kode_pengirim', 'Penandatangan', 'required');
        //$this->form_validation->set_rules('bulan', 'Bulan', 'required');
        //$this->form_validation->set_rules('tahun', 'Tahun', 'required');
        //$this->form_validation->set_rules('lokasi_surat', 'Lokasi', 'required');
        $this->form_validation->set_rules('isi_surat', 'Isi Surat', 'required');

        if ($this->form_validation->run() === FALSE) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => validation_errors()]);
            return;
        }

        $by = $this->session->userdata('fullname');
        $now = date('Y-m-d H:i:s');

        $record_id = (int) $this->input->post('record_id') ?: 0;

        $data = [
            //'tgl_surat' => $this->input->post('tgl_surat'),
            //'jenis_surat' => (int) $this->input->post('jenis_surat'),
            //'klasifikasi_surat' => (int) $this->input->post('klasifikasi_surat'),
            //'ukuran_kertas' => $this->input->post('ukuran_kertas'),
            'template_id' => (int) $this->input->post('template_id'),
            'isi_surat' => $this->input->post('isi_surat'),
            //'kode_pengirim' => $this->input->post('kode_pengirim'),
            //'kode_surat' => $this->input->post('klasifikasi_surat'),
            //'bulan' => $this->input->post('bulan'),
            //'tahun' => $this->input->post('tahun'),
            //'lokasi_surat' => $this->input->post('lokasi_surat'),
            //'pegawai_loop' => $this->input->post('pegawai_loop'),
        ];

        if ($record_id > 0) {
            // UPDATE existing record
            $data['modified_by'] = $by;
            $data['modified_on'] = $now;
            $result = $this->model->pembaharuan_data('register_surat_keluar', $data, 'id', $record_id);
            $msg = $result ? 'Surat keluar berhasil diperbarui' : 'Gagal memperbarui surat keluar';
        } else {
            // INSERT new record
            $no_sk = $this->input->post('no_sk');
            /*
            if (empty($no_sk)) {
                $no_sk = $this->generate_no_sk();
            }
            $data['no_sk'] = $no_sk;
            */
            $data['created_by'] = $by;
            $data['created_on'] = $now;
            $result = $this->model->simpan_data('register_surat_keluar', $data);
            $msg = $result ? 'Surat keluar berhasil disimpan' : 'Gagal menyimpan surat keluar';
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => (bool) $result,
            'message' => $msg
        ]);
    }

    private function generate_no_sk()
    {
        $year = date('Y');
        $month = date('m');
        $last_sk = $this->db->select('no_sk')
            ->where('YEAR(tgl_surat)', $year)
            ->where('MONTH(tgl_surat)', $month)
            ->order_by('id', 'DESC')
            ->get('register_surat_keluar')
            ->row();

        if ($last_sk) {
            $last_num = (int) substr($last_sk->no_sk, -4);
            $new_num = str_pad($last_num + 1, 4, '0', STR_PAD_LEFT);
            return sprintf('SK-%s/%s/%s', $new_num, $month, $year);
        }

        return sprintf('SK-0001/%s/%s', $month, $year);
    }

    public function get_jenis_surat_sk()
    {
        $sk_id = $this->input->get('sk_id');

        $jenis_ = $this->model->get_seleksi_array('ref_jenis_surat', ['hapus' => '0'], ['id' => 'ASC'])->result_array();
        $jenis[''] = 'Pilih Jenis Surat';
        foreach ($jenis_ as $j) {
            $jenis[$j['id']] = $j['jenis_surat'];
        }
        $template_id = '';

        if ($sk_id) {
            $query = $this->model->get_seleksi_array('register_surat_keluar', ['id' => $sk_id]);
            $jenis_surat = form_dropdown('jenis_surat', $jenis, $query->row()->jenis_surat, 'class="form-control select2" id="jenis_surat" onchange="PilihJenisSurat()"');
            $template_id = $query->row()->template_id;
        } else {
            $jenis_surat = form_dropdown('jenis_surat', $jenis, '', 'class="form-control select2" id="jenis_surat" onchange="PilihJenisSurat()"');
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'jenis_surat' => $jenis_surat, 'template_id' => $template_id]);
    }

    public function get_klasifikasi_surat_sk()
    {
        $sk_id = $this->input->post('sk_id');
        $klas_surat = $this->model->get_seleksi_array('ref_klasifikasi_surat', ['hapus' => '0'], ['id' => 'ASC'])->result_array();
        $klasifikasi = ['' => 'Pilih Klasifikasi Surat'];
        foreach ($klas_surat as $k) {
            $klasifikasi[$k['id']] = $k['kode'] . ' - ' . $k['jenis'];
        }

        if ($sk_id) {
            $query = $this->model->get_seleksi_array('register_surat_keluar', ['id' => $sk_id]);
            $klasifikasi_surat = form_dropdown('klasifikasi_surat', $klasifikasi, $query->row()->klasifikasi_surat, 'class="form-control select2" id="klasifikasi_surat" onchange="SimpanParameterSurat(`klasifikasi_surat`, `#klasifikasi_surat`)"');
        } else {
            $klasifikasi_surat = form_dropdown('klasifikasi_surat', $klasifikasi, '', 'class="form-control select2" id="klasifikasi_surat" onchange="SimpanParameterSurat("klasifikasi_surat", "#klasifikasi_surat")"');
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'klasifikasi_surat' => $klasifikasi_surat]);
    }

    public function get_templates()
    {
        $jenis_surat = $this->input->get('jenis_surat');
        $sk_id = $this->input->get('sk_id'); // Optional parameter to pre-select a template
        $query = $this->model->get_seleksi_array('template_surat', ['jenis_surat' => $jenis_surat, 'hapus' => '0'], ['id' => 'ASC'])->result();
        $template[''] = 'Pilih Template';
        foreach ($query as $t) {
            $template[$t->id] = $t->nama_template;
        }

        if ($sk_id) {
            $query = $this->model->get_seleksi_array('register_surat_keluar', ['id' => $sk_id]);
            $template_id = $query->row()->template_id;
            $selected_template = form_dropdown('select_template', $template, $template_id, 'class="form-control select2" id="select_template" onchange="PilihTemplateSurat()"');
        } else {
            $selected_template = form_dropdown('select_template', $template, '', 'class="form-control select2" id="select_template" onchange="PilihTemplateSurat()"');
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'selected_template' => $selected_template]);
    }

    public function get_pegawai_sk()
    {
        $data = [
            "tabel" => "v_users",
            "kolom_seleksi" => "status_pegawai",
            "seleksi" => "1"
        ];

        $users = $this->apihelper->get('apiclient/get_data_seleksi', $data);

        $pegawai = array();
        if ($users['status_code'] === 200) {
            foreach ($users['response']['data'] as $item) {
                $pegawai[$item['pegawai_id']] = $item['fullname'];
            }
        }

        $sk_id = $this->input->post('sk_id');
        if ($sk_id) {
            $query = $this->model->get_seleksi_array('register_surat_keluar', ['id' => $sk_id]);
            $penandatangan_id = $query->row()->penandatangan_id;
            $selected_pegawai = form_dropdown('select_pegawai', $pegawai, $penandatangan_id, 'class="form-control select2" id="select_pegawai" onchange="SimpanPenandatangan(this)"');
        } else {
            $selected_pegawai = form_dropdown('select_pegawai', $pegawai, '', 'class="form-control select2" id="select_pegawai" onchange="SimpanPenandatangan(this)"');
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'selected_pegawai' => $selected_pegawai]);
    }

    public function preview_surat_keluar()
    {
        $id = $this->input->post('id');

        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'ID tidak valid']);
            return;
        }

        $row = $this->model->get_surat_keluar_by_id($id);

        $kop_satker = $this->session->userdata('kop_satker') ?? '';

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => [
                'id' => (int) $row->id,
                'no_sk' => $row->no_sk ?? '',
                'tgl_surat' => $row->tgl_surat ?? '',
                'jenis_surat' => $row->jenis_surat ?? '',
                'jenis_kertas' => $row->jenis_kertas ?? '',
                'isi_surat' => $row->isi_surat ?? '',
                'kode_pengirim' => $row->kode_pengirim ?? '',
                'bulan' => $row->bulan ?? '',
                'tahun' => $row->tahun ?? '',
                'lokasi_surat' => $row->lokasi_surat ?? '',
                'kop_satker' => $kop_satker,
                'created_on' => $row->created_on ?? '',
            ]
        ]);
    }

    public function get_arsip_sk_datatables()
    {
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search')['value'] ?? '';
        $order_column = intval($this->input->post('order')[0]['column'] ?? 0);
        $order_dir = $this->input->post('order')[0]['dir'] ?? 'DESC';

        $result = $this->model->get_arsip_sk_datatables($start, $length, $search, $order_column, $order_dir);

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
                $row->no_sk ?? '',
                $row->perihal ?? '',
                $row->created_on ?? '',
                $row->created_by ?? ''
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function simpan_sk()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('perihal', 'Perihal Surat', 'trim|required');
        $this->form_validation->set_message(['required' => '%s Tidak Boleh Kosong']);

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['success' => false, 'message' => validation_errors()]);
            return;
        }

        $data = [
            'perihal' => $this->input->post('perihal'),
            'pegawai_id' => $this->session->userdata('pegawai_id'),
            'created_by' => $this->session->userdata('fullname'),
        ];

        $result = $this->model->simpan_data('register_surat_keluar', $data);

        if ($result === 1) {
            echo json_encode(['success' => true, 'message' => 'Draft Surat Keluar berhasil disimpan']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan Draft Surat Keluar']);
        }
    }

    public function hapus_sk()
    {
        $id = $this->input->post('id');
        $data = [
            'hapus' => '1',
            'modified_by' => $this->session->userdata('fullname'),
            'modified_on' => date('Y-m-d H:i:s'),
        ];
        $result = $this->model->pembaharuan_data('register_surat_keluar', $data, 'id', $id);
        header('Content-Type: application/json');
        echo json_encode(['success' => (bool) $result]);
    }

    public function simpan_parameter_surat()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $id = $this->input->post('id');
        $kolom = $this->input->post('kolom');

        $data = [
            $kolom => $this->input->post('param'),
            'modified_by' => $this->session->userdata('fullname'),
            'modified_on' => date('Y-m-d H:i:s')
        ];

        $result = $this->model->pembaharuan_data('register_surat_keluar', $data, 'id', $id);

        if ($result === 1) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function simpan_penandatangan()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $id = $this->input->post('id');
        $pegawai = $this->input->post('pegawai');

        $data = [
            "tabel" => "v_pegawai",
            "kolom_seleksi" => "id",
            "seleksi" => $pegawai
        ];

        $users = $this->apihelper->get('apiclient/get_data_seleksi', $data);

        $pegawai = array();
        if ($users['status_code'] === 200) {
            foreach ($users['response']['data'] as $item) {
                if ($item['jab_id'] == '1') {
                    $kode = 'KMS';
                } else if ($item['jab_id'] == '2') {
                    $kode = 'WKMS';
                } else if ($item['jab_id'] == '4') {
                    $kode = 'PAN.MS';
                } else {
                    $kode = 'SEK.MS';
                }

                if (!$item['qrcode_pegawai']) {
                    echo json_encode(['success' => false, 'message' => 'Pegawai Belum Memiliki QRCODE TTE']);
                    return;
                }

                $data = [
                    'penandatangan_id' => $item['id'],
                    'penandatangan_nama' => $item['nama'],
                    'penandatangan_jabatan' => $item['nama_jabatan'],
                    'penandatangan_kode' => $kode,
                    'penandatangan_tte' => $this->config->item('sso_server') . $item['qrcode_pegawai'],
                    'modified_by' => $this->session->userdata('fullname'),
                    'modified_on' => date('Y-m-d H:i:s')
                ];
            }
        }

        $result = $this->model->pembaharuan_data('register_surat_keluar', $data, 'id', $id);

        if ($result === 1) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
}
