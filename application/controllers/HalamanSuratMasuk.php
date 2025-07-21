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

    public function simpan_pelaksanaan_sm()
    //Fungsi ini dipanggil untuk menyimpan Pelaksanaan Surat
    {
        $this->form_validation->set_rules('register_id', 'ID Register', 'trim|required');
        $this->form_validation->set_rules('pelaksanaan_id', 'ID Surat Masuk', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('st' => 0, 'msg' => 'Tidak Berhasil:<br/>' . validation_errors()));
            return;
        }

        $pelaksanaan_id = $this->encrypt->decode(base64_decode($this->input->post('pelaksanaan_id')));
        $register_id = $this->encrypt->decode(base64_decode($this->input->post('register_id')));
        $jabatan = $this->input->post('jenis_jabatan');
        $progres = $this->input->post('jenis_progres');
        $ket = $this->input->post('keterangan');
        //die(var_dump($jabatan));
        $pengirim = $this->session->userdata('jabatan');
        $pengguna_id = $this->session->userdata('userid');

        $querydetail = $this->model->get_seleksi('sias_arsip_sm', 'id', $register_id);
        $tujuan = $querydetail->row()->tujuan_surat;
        $perihal = $querydetail->row()->perihal;
        $nama_app = $this->session->userdata('nama_app');
        $nama_pengadilan = $this->session->userdata('nama_pengadilan');
        $queryDispo = "";

        if ($pelaksanaan_id == '99') {
            if ($progres == '2') {
                #Progres Disposisi
                $data_sm = array('status' => '1', 'modified_on' => date("Y-m-d H:i:s"), 'modified_by' => $this->session->userdata('fullname'));
                $this->model->pembaharuan_data('sias_arsip_sm', $data_sm, 'id', $register_id);

                if ($this->session->userdata('status_plh') == '1') {
                    $penginput = $this->session->userdata('fullname') . ' (' . $this->session->userdata('nama_pegawai_plh') . ')';
                } else {
                    $penginput = $this->session->userdata('jabatan') . ' (' . $this->session->userdata('fullname') . ')';
                }

                for ($i = 0; $i < count($jabatan); $i++) {
                    //die(var_dump($jabatan[$i]));
                    $ke = $jabatan[$i];
                    $queryPlh = $this->model->get_seleksi('v_plh', 'plh_id_jabatan', $jabatan[$i]);

                    if ($queryPlh->row()->pegawai_id != null) {
                        $jab = $queryPlh->row()->nama;
                        $nama_pegawai = $queryPlh->row()->nama_pegawai;
                        $tujuanNotif = $queryPlh->row()->pegawai_id;
                        $pesan = 'Assalamualaikum Wr. Wb., Yth. *' . $jab . ' (' . $nama_pegawai . ')*. Ada disposisi surat masuk baru dari *' . $pengirim . '* perihal *' . $perihal . '* dengan Disposisi : *' . $ket . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' sebagai ' . $jab . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                    } else {
                        $queryUser = $this->model->get_seleksi_pegawai('v_users', 'jab_id', $jabatan[$i]);
                        $jab = $queryUser->row()->jabatan;
                        $tujuanNotif = $queryUser->row()->pegawai_id;
                        $pesan = 'Assalamualaikum Wr. Wb., Yth. *' . $jab . ' (' . $queryUser->row()->fullname . ')*. Ada disposisi surat masuk baru dari *' . $pengirim . '* perihal *' . $perihal . '* dengan Disposisi : *' . $ket . '*. Silakan akses aplikasi *' . $nama_app . '* - ' . $nama_pengadilan . ' untuk menindaklanjuti. Demikian diinformasikan, Terima Kasih atas perhatian.';
                    }

                    $dataNotif = array(
                        'jenis_pesan' => 'surat',
                        'id_pemohon' => $this->session->userdata('id_pegawai'),
                        'pesan' => $pesan,
                        'id_tujuan' => $tujuanNotif,
                        'created_by' => $penginput,
                        'created_on' => date('Y-m-d H:i:s')
                    );

                    $dataDispo = array(
                        'id_sm' => $register_id,
                        'jab_id' => $this->session->userdata('id_jabatan'),
                        'disposisi' => $jabatan[$i],
                        'ket_disposisi' => $ket,
                        'created_by' => $penginput,
                        'created_on' => date("Y-m-d H:i:s")
                    );

                    $data = array(
                        'id_sm' => $register_id,
                        'userid' => $pengguna_id,
                        'status' => '2',
                        'tujuan' => $ke,
                        'ket' => $ket,
                        'created_by' => $penginput,
                        'created_on' => date("Y-m-d H:i:s")
                    );
                    //die(var_dump($dataNotif += $data));
                    $this->notif->tambahNotif($dataNotif, 'sys_notif');
                    $queryDispo = $this->model->simpan_data('sias_disposisi', $dataDispo);
                    $queryStatus = $this->model->simpan_data('sias_status_sm', $data);
                }

            } else {
                if ($this->session->userdata('status_plh') == '1') {
                    $penginput = $this->session->userdata('fullname') . ' (' . $this->session->userdata('nama_pegawai_plh') . ')';
                } else {
                    $penginput = $this->session->userdata('jabatan') . ' (' . $this->session->userdata('fullname') . ')';
                }

                $queryPlh = $this->model->get_seleksi('v_plh', 'plh_id_jabatan', $tujuan);
                if ($queryPlh->row()->pegawai_id != null) {
                    $notifKe = $queryPlh->row()->pegawai_id;
                    $jab = $queryPlh->row()->nama;
                    $notif_to = $queryPlh->row()->nama_pegawai;
                } else {
                    $queryUser = $this->model->get_seleksi_pegawai('v_users', 'jab_id', $tujuan);
                    $notifKe = $queryUser->row()->pegawai_id;
                    $jab = $queryUser->row()->jabatan;
                    $notif_to = $queryUser->row()->fullname;
                }

                if ($progres == '3') {
                    if (!$ket) {
                        $ket = "Dilaksanakan";
                    }
                    $pesan = 'Assalamualaikum Wr. Wb., Yth. *' . $jab . ' (' . $notif_to . ')*. Surat Masuk perihal *' . $perihal . '* sedang dilaksanakan oleh *' . $pengirim . '*. Demikian diinformasikan, Terima Kasih atas perhatian.';
                    $data = array(
                        'id_sm' => $register_id,
                        'userid' => $pengguna_id,
                        'status' => '3',
                        'tujuan' => $tujuan,
                        'ket' => $ket,
                        'created_by' => $penginput,
                        'created_on' => date("Y-m-d H:i:s")
                    );

                    $data_sm = array('status' => '1', 'modified_on' => date("Y-m-d H:i:s"), 'modified_by' => $this->session->userdata('fullname'));
                    $this->model->pembaharuan_data('sias_arsip_sm', $data_sm, 'id', $register_id);
                } elseif ($progres == '4') {
                    $pesan = 'Assalamualaikum Wr. Wb., Yth. *' . $jab . ' (' . $notif_to . ')*. Surat masuk perihal *' . $perihal . '* telah selesai dilaksanakan oleh *' . $pengirim . '*. Demikian diinformasikan, Terima Kasih atas perhatian.';
                    $data = array(
                        'id_sm' => $register_id,
                        'userid' => $pengguna_id,
                        'status' => '4',
                        'tujuan' => $tujuan,
                        'ket' => $ket,
                        'created_by' => $penginput,
                        'created_on' => date("Y-m-d H:i:s")
                    );

                    $data_sm = array('status' => '2', 'modified_on' => date("Y-m-d H:i:s"), 'modified_by' => $this->session->userdata('fullname'));
                    $this->model->pembaharuan_data('sias_arsip_sm', $data_sm, 'id', $register_id);
                }

                $dataNotif = array(
                    'jenis_pesan' => 'surat',
                    'id_pemohon' => $this->session->userdata('id_pegawai'),
                    'pesan' => $pesan,
                    'id_tujuan' => $notifKe,
                    'created_by' => $penginput,
                    'created_on' => date('Y-m-d H:i:s')
                );

                $this->notif->tambahNotif($dataNotif, 'sys_notif');
                #die(var_dump($data));
                $queryStatus = $this->model->simpan_data('sias_status_sm', $data);
            }

            if ($queryDispo) {
                if ($queryStatus == 1 && $queryDispo == 1) {
                    $this->session->set_flashdata('info', '1');
                    $this->session->set_flashdata('pesan_sukses', 'Simpan Data Disposisi Berhasil, Notifikasi Akan Segera Dikirim');
                    echo json_encode(array('st' => 1, 'msg' => 'Simpan Data Disposisi Berhasil, Notifikasi Akan Segera Dikirim'));
                } else {
                    $this->session->set_flashdata('info', '2');
                    $this->session->set_flashdata('pesan_gagal', 'Simpan Data Disposisi Gagal');
                    echo json_encode(array('st' => 0, 'msg' => 'Simpan Data Disposisi Gagal'));
                }
            } else {
                $this->session->set_flashdata('info', '1');
                $this->session->set_flashdata('pesan_gagal', 'Simpan Data Pelaksanaan Berhasil, Notifikasi Akan Segera Dikirim');
                echo json_encode(array('st' => 1, 'msg' => 'Simpan Data Pelaksanaan Berhasil, Notifikasi Akan Segera Dikirim'));
            }
        }
    }
}