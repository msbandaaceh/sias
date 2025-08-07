<?php

/**
 * @property CI_Config $config
 * @property CI_Input $input
 * @property CI_Model $model
 * @property CI_Model $api
 * @property CI_Encryption $encryption
 * @property Apihelper $apihelper
 * @property TanggalHelper $tanggalhelper
 * @property CI_Session $session
 * @property CI_Upload $upload
 * @property CI_Form_validation $form_validation
 */

class MY_Controller extends CI_Controller
{
    private $jwt_key;
    protected $peran;

    public function __construct()
    {
        parent::__construct();
        $this->jwt_key = $this->config->item('jwt_key'); // inisialisasi di sini
        $cookie_domain = $this->config->item('cookie_domain');
        $sso_server = $this->config->item('sso_server');
        $this->session->set_userdata('sso_db', $this->config->item('sso_db'));

        $this->load->model('ModelSias', 'model');

        if (!$this->session->userdata('logged_in')) {
            $token = $this->input->cookie('sso_token');
            if ($token) {
                $this->cek_token($token);
            } else {
                $this->session->sess_destroy();
                $redirect_url = current_url();
                setcookie('redirect_to', urlencode($redirect_url), time() + 300, "/", $cookie_domain);
                redirect($sso_server . 'login');
            }
        }

        # Cek Data Pengguna
        $params = [
            'tabel' => 'v_users',
            'kolom_seleksi' => 'userid',
            'seleksi' => $this->session->userdata('userid')
        ];

        $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $user_data = $result['response']['data'][0];
            $this->session->set_userdata('pegawai_id', $user_data['pegawai_id']);
        }

        # Cek Data Aplikasi Ini
        $this->model->cek_aplikasi();

        #Cek peran pegawai
        $this->peran = $this->model->cek_peran();

        #$cekArsipSM = count($this->sm->all_sm_data());
        #$this->session->set_userdata('jum_sm', $cekArsipSM);

        #$cekSM = count($this->sm->cek_sm_unread($this->session->userdata('id_jabatan')));
        #$this->session->set_userdata('jum_sm_user', $cekSM);

        #$cekDispoSM = count($this->sm->cek_dispo_sm_unread($this->session->userdata('id_jabatan')));
        #$this->session->set_userdata('jum_dis_user', $cekDispoSM);
    }

    protected function cek_token($token)
    {
        $cookie_domain = $this->config->item('sso_server');
        $sso_api = $cookie_domain . "api/cek_token?sso_token={$token}";
        $response = file_get_contents($sso_api);
        $data = json_decode($response, true);

        if ($data['status'] == 'success') {
            $this->session->set_userdata([
                'logged_in' => TRUE,
                'userid' => $data['user']['userid'],
                'status_plh' => $data['user']['status_plh'],
                'status_plt' => $data['user']['status_plt']
            ]);
            redirect(current_url());
        } else {
            redirect($cookie_domain . 'login');
        }
    }

    private function get_config_value($seleksi)
    {
        $params = [
            'tabel' => 'sys_config',
            'kolom_seleksi' => 'id',
            'seleksi' => $seleksi
        ];

        $result = $this->apihelper->get('apiclient/get_data_seleksi', $params);

        if ($result['status_code'] === 200 && $result['response']['status'] === 'success') {
            $user_data = $result['response']['data'][0];
            return $user_data['value'];
        }
    }
}