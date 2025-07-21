<?php

class Api extends MY_Controller {

    public function cek_jumlah_surat_masuk() {
        $jumlah_sm = $this->model->all_sm_data();
        echo $jumlah_sm;
    }
}