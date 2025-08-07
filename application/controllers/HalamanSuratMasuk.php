<?php

class HalamanSuratMasuk extends MY_Controller
{
    public function show_sm()
    # Fungsi ini dipanggil ketika Tombol Tambah/Edit Surat ditekan
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));
        $data = $this->model->get_data_surat_masuk($id);
        echo json_encode($data);
    }

    public function show_detil_sm()
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('id')));
        $status = $this->input->post('status');

        $data = $this->model->get_detail_surat_masuk($status, $id);
        echo json_encode($data);
    }

    public function simpan_sm()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('no_agenda', 'Nomor Agenda Surat', 'trim|required');
        $this->form_validation->set_rules('no_surat', 'Nomor Surat Masuk', 'trim|required');
        $this->form_validation->set_rules('pengirim', 'Pengirim Surat', 'trim|required');
        $this->form_validation->set_rules('perihal', 'Perihal Surat', 'trim|required');
        $this->form_validation->set_rules('tgl_surat', 'Tanggal Surat', 'trim|required');
        $this->form_validation->set_rules('tgl_terima', 'Tanggal Terima Surat', 'trim|required');

        $this->form_validation->set_message(['required' => '%s Tidak Boleh Kosong']);

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['success' => false, 'message' => validation_errors()]);
            return;
        }

        $data = [
            'id' => $this->input->post('id'),
            'no_agenda' => $this->input->post('no_agenda'),
            'no_sm' => $this->input->post('no_surat'),
            'pengirim' => $this->input->post('pengirim'),
            'perihal' => $this->input->post('perihal'),
            'tgl_surat' => $this->input->post('tgl_surat'),
            'tgl_terima' => $this->input->post('tgl_terima'),
            'ket' => $this->input->post('ket'),
            'created_by' => $this->session->userdata('fullname'),
            'created_on' => date('Y-m-d H:i:s'),
            'file' => null
        ];

        if (!empty($_FILES['dokumen']['name'])) {
            $max_size = 5000 * 1024;
            if ($_FILES['dokumen']['size'] > $max_size) {
                echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar. Maksimal 5MB']);
                return;
            } elseif ($_FILES['dokumen']['type'] != 'application/pdf') {
                echo json_encode(['success' => false, 'message' => 'File harus dalam format PDF']);
                return;
            } else {
                $doc = time() . '-' . $_FILES["dokumen"]['name'];
                $config = array(
                    'upload_path' => './assets/dokumen/',
                    'allowed_types' => "pdf",
                    'file_ext_tolower' => TRUE,
                    'file_name' => $doc,
                    'overwrite' => TRUE,
                    'remove_spaces' => TRUE,
                    'max_size' => "5000"
                );

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('dokumen')) {
                    echo json_encode(['success' => false, 'message' => $this->upload->display_errors()]);
                    return;
                } else {
                    $upload_data = $this->upload->data();
                    $data['file'] = $upload_data['file_name'];
                }
            }
        } else {
            if (!$this->input->post('id')) {
                echo json_encode(['success' => false, 'message' => 'Dokumen surat tidak boleh kosong']);
                return;
            }
        }

        $result = $this->model->simpan_sm($data);

        if ($result === true) {
            echo json_encode(['success' => true, 'message' => 'Surat Masuk berhasil disimpan']);
        } elseif ($result == 'penelaah_kosong') {
            $file_path = FCPATH . 'assets/dokumen/' . $upload_data['file_name'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan Surat Masuk dikarenakan Tidak Ada Penelaah Surat. Hubungi Bagian Kepegawaian']);
        } else {
            $file_path = FCPATH . 'assets/dokumen/' . $upload_data['file_name'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan Surat Masuk']);
        }
    }

    public function get_validasi()
    {
        $validasi_sm = $this->model->validasi_surat_masuk($this->session->userdata('jab_id'));
        echo json_encode($validasi_sm);
    }

    public function get_surat_masuk()
    {
        $surat_masuk = $this->model->surat_masuk($this->session->userdata('jab_id'));
        echo json_encode($surat_masuk);
    }

    public function get_progres_surat_masuk()
    # Fungsi ini dipanggil ketika Tab Progres di Modal Detail Surat ditekan
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('suratId')));
        $progresSurat = $this->model->get_seleksi('v_progres_surat', 'id_sm', $id)->result_array();
        echo json_encode($progresSurat);
    }

    public function get_disposisi()
    {
        $disposisi = $this->model->disposisi($this->session->userdata('jab_id'));
        echo json_encode($disposisi);
    }

    public function get_riwayat_disposisi()
    //Fungsi ini dipanggil ketika Tab Progres di Modal Detail Surat ditekan
    {
        $id = $this->encryption->decrypt(base64_decode($this->input->post('suratId')));
        $riwayatDispo = $this->model->get_seleksi('v_disposisi', 'id_sm', $id)->result_array();
        echo json_encode($riwayatDispo);
    }

    public function edit_status_surat_masuk()
    {
        $this->form_validation->set_rules('register_id', 'ID Register', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('st' => 0, 'msg' => 'Anda Dilarang Melakukan Akses Langsung Ke Aplikasi'));
            return;
        }

        $register_id = $this->encryption->decrypt(base64_decode($this->input->post('register_id')));
        $data = $this->model->edit_status_surat_masuk($register_id);
        echo json_encode($data);
    }

    public function simpan_validasi_surat_masuk()
    # Fungsi ini dipanggil untuk menyimpan validasi Surat
    {
        $data = [
            'pelaksanaan_id' => $this->encryption->decrypt(base64_decode($this->input->post('pelaksanaan_id'))),
            'register_id' => $this->encryption->decrypt(base64_decode($this->input->post('register_id'))),
            'progres' => $this->input->post('jenis_progres'),
            'jabatan' => $this->input->post('jenis_jabatan'),
            'keterangan' => $this->input->post('keterangan'),
            'pengguna_id' => $this->session->userdata('userid')
        ];

        $result = $this->model->simpan_pelaksanaan_validasi($data);
        echo $result;
    }

    public function simpan_pelaksanaan_surat_masuk()
    //Fungsi ini dipanggil untuk menyimpan Pelaksanaan Surat
    {
        $this->form_validation->set_rules('register_id', 'ID Register', 'trim|required');
        $this->form_validation->set_rules('pelaksanaan_id', 'ID Surat Masuk', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('success' => false, 'message' => validation_errors()));
            return;
        }

        $data = [
            'pelaksanaan_id' => $this->encryption->decrypt(base64_decode($this->input->post('pelaksanaan_id'))),
            'register_id' => $this->encryption->decrypt(base64_decode($this->input->post('register_id'))),
            'jabatan' => $this->input->post('jenis_jabatan'),
            'progres' => $this->input->post('jenis_progres'),
            'ket' => $this->input->post('keterangan'),
            'pengirim' => $this->session->userdata('jabatan'),
            'pengguna_id' => $this->session->userdata('userid')
        ];

        $result = $this->model->simpan_pelaksanaan_surat_masuk($data);
        echo $result;
    }

    public function cetak_lembar_disposisi()
    {
        $id = $this->input->post('register_id');
        // Ambil data izin berdasarkan ID
        $dataDisposisi = $this->model->get_seleksi('v_disposisi', 'id', $id);

        $data['no_sm'] = $dataDisposisi->row()->no_sm;
        $data['tgl_terima'] = $dataDisposisi->row()->tgl_terima;
        $data['no_agenda'] = $dataDisposisi->row()->no_agenda;
        $data['tgl_surat'] = $dataDisposisi->row()->tgl_surat;
        $data['pengirim'] = $dataDisposisi->row()->pengirim;
        $data['perihal'] = $dataDisposisi->row()->perihal;
        $data['nama'] = $dataDisposisi->row()->nama;
        $idsm = $dataDisposisi->row()->id_sm;
        $tgl_dispo = $dataDisposisi->row()->tgl_dispo;

        $data['progres'] = $this->model->get_progres_sm($idsm, $tgl_dispo);
        $data['tgl_dispo'] = $dataDisposisi->row()->tgl_dispo;

        $this->load->view('disposisi_cetak', $data);
    }

    public function filter_laporan_surat_masuk()
    {
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');

        // Validasi sederhana
        if (!$tgl_awal || !$tgl_akhir) {
            echo json_encode(['status' => 'error', 'message' => 'Tanggal tidak boleh kosong.']);
            return;
        }

        $data = $this->model->all_sm_data_filter($tgl_awal, $tgl_akhir);

        // Konversi tanggal jika perlu
        $data = array_map(function ($item) {
            return [
                'no_agenda' => $item->no_agenda,
                'no_sm' => $item->no_sm,
                'pengirim' => $item->pengirim,
                'perihal' => $item->perihal,
                'tgl_surat' => $this->tanggalhelper->convertDayDate($item->tgl_surat),
                'tgl_terima' => $this->tanggalhelper->convertDayDate($item->tgl_terima)
            ];
        }, $data);

        echo json_encode(['status' => 'success', 'data' => $data]);
    }
}