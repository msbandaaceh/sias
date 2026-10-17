<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TemplateSurat extends MY_Controller
{
    public function index()
    {
        $data['peran'] = $this->session->userdata('peran');
        $data['page'] = 'template_surat';
        $this->load->view('layout', $data);
    }

    public function get_template()
    {
        $id = (int) $this->input->post('id');

        // Find by id from all templates
        $result = null;
        foreach ($this->model->get_all_template_surat() as $row) {
            if ((int) $row->id === $id) {
                $result = [
                    'id' => (int) $row->id,
                    'nama_template' => $row->nama_template,
                    'jenis_surat' => (int) $row->jenis_surat,
                    'isi_template' => $row->isi_template,
                ];
                break;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($result ? ['success' => true, 'data' => $result] : ['success' => false]);
    }

    public function get_templates()
    {
        // DataTables server-side parameters
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search = $this->input->post('search')['value'] ?? '';
        $order_column = intval($this->input->post('order')[0]['column'] ?? 0);
        $order_dir = $this->input->post('order')[0]['dir'] ?? 'DESC';

        $result = $this->model->get_template_surat_datatables($start, $length, $search, $order_column, $order_dir);

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
                $row->nama_template ?? '',
                $this->model->get_ref_jenis_surat_by_id($row->jenis_surat)->jenis_surat ?? '',
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function get_template_by_id()
    {
        $id = (int) $this->input->post('id');
        $jenis_surat = ['' => '-- Pilih Jenis Surat --'];
        $query_jenis = $this->model->get_seleksi_array('ref_jenis_surat', ['hapus' => '0']);
        foreach ($query_jenis->result() as $r) {
            $jenis_surat[$r->id] = $r->jenis_surat;
        }

        if ($id > 0) {
            $row = $this->model->get_template_surat_by_id($id);
            if ($row) {
                $template = [
                    'id' => (int) $row->id,
                    'judul' => 'Edit Template Surat',
                    'nama_template' => $row->nama_template,
                    'jenis_surat' => form_dropdown('jenis_surat', $jenis_surat, $row->jenis_surat, 'class="form-control select2" id="jenis_surat" style="width: 100%;"'),
                    'isi_template' => $row->isi_template,
                ];
            }
        } else {
            $template = [
                'id' => 0,
                'judul' => 'Tambah Template Surat',
                'nama_template' => '',
                'jenis_surat' => form_dropdown('jenis_surat', $jenis_surat, '', 'class="form-control select2" id="jenis_surat" style="width: 100%;"'),
                'isi_template' => '',
            ];
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $template ?? [
                'id' => 0,
                'judul' => 'Tambah Template Surat',
                'nama_template' => '',
                'jenis_surat' => form_dropdown('jenis_surat', $jenis_surat, '', 'class="form-control select2" id="jenis_surat" style="width: 100%;"'),
                'isi_template' => '',
            ]
        ]);
    }

    public function simpan_template()
    {
        $this->form_validation->set_rules('nama_template', 'Nama Template', 'trim|required|max_length[100]');
        $this->form_validation->set_rules('jenis_surat', 'Jenis Surat', 'trim|required');
        $this->form_validation->set_rules('isi_template', 'Isi Template', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => validation_errors()]);
            return;
        }

        $id = $this->input->post('id');
        $by = $this->session->userdata('fullname');
        $now = date('Y-m-d H:i:s');
        $jenis = (int) $this->input->post('jenis_surat');

        $ukuran_kertas = $this->input->post('ukuran_kertas');

        if ($id) {
            $data = [
                'nama_template' => $this->input->post('nama_template'),
                'jenis_surat' => $jenis,
                'isi_template' => $this->input->post('isi_template'),
                'modified_by' => $by,
                'modified_on' => $now,
            ];
            $result = $this->model->pembaharuan_data('template_surat', $data, 'id', $id);
        } else {
            $data = [
                'nama_template' => $this->input->post('nama_template'),
                'jenis_surat' => $jenis,
                'isi_template' => $this->input->post('isi_template'),
                'hapus' => '0',
                'created_by' => $by,
                'created_on' => $now
            ];
            $result = $this->model->simpan_data('template_surat', $data);
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => (bool) $result]);
    }

    public function hapus_template()
    {
        $id = $this->input->post('id');
        $data = [
            'hapus' => '1',
            'modified_by' => $this->session->userdata('fullname'),
            'modified_on' => date('Y-m-d H:i:s'),
        ];
        $result = $this->model->pembaharuan_data('template_surat', $data, 'id', $id);
        header('Content-Type: application/json');
        echo json_encode(['success' => (bool) $result]);
    }
}
