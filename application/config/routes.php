<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'HalamanUtama';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['cek_token'] = 'HalamanUtama/cek_token_sso';

$route['show_sm'] = 'HalamanSuratMasuk/show_sm';
$route['show_role'] = 'HalamanUtama/show_role';
$route['show_detil_sm'] = 'HalamanSuratMasuk/show_detil_sm';

$route['simpan_sm'] = 'HalamanSuratMasuk/simpan_sm';
$route['simpan_peran'] = 'HalamanUtama/simpan_peran';
$route['simpan_validasi_surat_masuk'] = 'HalamanSuratMasuk/simpan_validasi_surat_masuk';

$route['get_validasi'] = 'HalamanSuratMasuk/get_validasi';
$route['get_surat_masuk'] = 'HalamanSuratMasuk/get_surat_masuk';
$route['get_disposisi'] = 'HalamanSuratMasuk/get_disposisi';
$route['get_progres_surat_masuk'] = 'HalamanSuratMasuk/get_progres_surat_masuk';

$route['edit_status_surat_masuk'] = 'HalamanSuratMasuk/edit_status_surat_masuk';

$route['blok_peran'] = 'HalamanUtama/blok_peran';
$route['aktif_peran'] = 'HalamanUtama/aktif_peran';

$route['keluar'] = 'HalamanUtama/keluar';
