<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller untuk Tracking Surat Masuk (Public Access)
 * Tidak memerlukan session/authentication
 */
class Tracking extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelSias', 'model');
        $this->load->helper('url');
    }

    /**
     * Halaman utama tracking
     */
    public function index()
    {
        $tracking_code = $this->input->get('code');
        $data['tracking_code'] = $tracking_code;
        $data['result'] = null;
        $data['progres_terakhir'] = null;

        if (!empty($tracking_code)) {
            // Validasi format tracking code
            if ($this->validate_tracking_code($tracking_code)) {
                $query = $this->model->get_tracking_info(strtoupper($tracking_code));
                
                if ($query->num_rows() > 0) {
                    $data['result'] = $query->row();
                    
                    // Ambil progres terakhir surat
                    $progres_query = $this->model->get_progres_tracking($data['result']->id);
                    $progres_all = $progres_query->result();
                    // Ambil hanya progres terakhir
                    $data['progres_terakhir'] = !empty($progres_all) ? end($progres_all) : null;
                } else {
                    $data['error'] = 'Tracking code tidak ditemukan. Pastikan kode yang Anda masukkan benar.';
                }
            } else {
                $data['error'] = 'Format tracking code tidak valid. Format yang benar: SIAS-YYYYMMDD-XXXXXX';
            }
        }

        $this->load->view('tracking', $data);
    }

    /**
     * API untuk cek tracking (AJAX)
     */
    public function cek()
    {
        header('Content-Type: application/json');
        
        if (!$this->input->is_ajax_request()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $tracking_code = $this->input->post('tracking_code') ?: $this->input->get('code');
        
        if (empty($tracking_code)) {
            echo json_encode(['success' => false, 'message' => 'Tracking code tidak boleh kosong']);
            return;
        }

        // Validasi format
        if (!$this->validate_tracking_code($tracking_code)) {
            echo json_encode(['success' => false, 'message' => 'Format tracking code tidak valid']);
            return;
        }

        // Rate limiting sederhana (bisa ditingkatkan dengan session/cache)
        // Untuk production, gunakan library rate limiting yang lebih robust

        $query = $this->model->get_tracking_info(strtoupper($tracking_code));
        
        if ($query->num_rows() > 0) {
            $surat = $query->row();
            
            // Ambil progres terakhir
            $progres_query = $this->model->get_progres_tracking($surat->id);
            $progres_all = $progres_query->result();
            $progres_terakhir = !empty($progres_all) ? end($progres_all) : null;
            
            // Format status
            $status_text = $this->get_status_text($surat);
            
            echo json_encode([
                'success' => true,
                'data' => [
                    'tracking_code' => $surat->tracking_code,
                    'no_surat' => $surat->no_sm,
                    'pengirim' => $surat->pengirim,
                    'perihal' => $surat->perihal,
                    'tgl_surat' => $surat->tgl_surat,
                    'tgl_terima' => $surat->tgl_terima,
                    'status' => $status_text,
                    'valid' => $surat->valid,
                    'progres_terakhir' => $progres_terakhir
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Tracking code tidak ditemukan']);
        }
    }

    /**
     * Validasi format tracking code
     * Format: SIAS-YYYYMMDD-XXXXXX
     */
    private function validate_tracking_code($code)
    {
        // Format: SIAS-YYYYMMDD-XXXXXX (18 karakter)
        $pattern = '/^SIAS-\d{8}-[A-Z0-9]{6}$/i';
        return preg_match($pattern, $code) === 1;
    }

    /**
     * Get status text berdasarkan data surat
     */
    private function get_status_text($surat)
    {
        if ($surat->valid == 0) {
            return 'Menunggu Validasi';
        } elseif ($surat->valid == 1) {
            if ($surat->status == 0) {
                return 'Sudah Divalidasi - Menunggu Tindak Lanjut';
            } elseif ($surat->status == 1) {
                return 'Sedang Diproses';
            } elseif ($surat->status == 2) {
                return 'Disposisi';
            } elseif ($surat->status == 3) {
                return 'Dilaksanakan';
            } elseif ($surat->status == 4) {
                return 'Selesai';
            }
        } elseif ($surat->valid == 2) {
            return 'Ditolak';
        }
        
        return 'Unknown';
    }
}

