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
$route['simpan_pelaksanaan_surat_masuk'] = 'HalamanSuratMasuk/simpan_pelaksanaan_surat_masuk';

$route['get_validasi'] = 'HalamanSuratMasuk/get_validasi';
$route['get_surat_masuk'] = 'HalamanSuratMasuk/get_surat_masuk';
$route['get_disposisi'] = 'HalamanSuratMasuk/get_disposisi';
$route['get_progres_surat_masuk'] = 'HalamanSuratMasuk/get_progres_surat_masuk';
$route['get_riwayat_disposisi'] = 'HalamanSuratMasuk/get_riwayat_disposisi';

$route['edit_status_surat_masuk'] = 'HalamanSuratMasuk/edit_status_surat_masuk';

$route['blok_peran'] = 'HalamanUtama/blok_peran';
$route['aktif_peran'] = 'HalamanUtama/aktif_peran';

$route['cetak_disposisi'] = 'HalamanSuratMasuk/cetak_lembar_disposisi';

$route['hapus_surat_masuk'] = 'HalamanSuratMasuk/hapus_surat_masuk';

$route['keluar'] = 'HalamanUtama/keluar';

// DataTables server-side processing untuk arsip surat masuk
$route['arsip_sm_datatables'] = 'HalamanUtama/get_arsip_sm_datatables';

$route['daftar_klasifikasi'] = 'HalamanPengaturan/get_klasifikasi_datatables';
$route['get_klasifikasi_surat_by_id'] = 'HalamanPengaturan/get_klasifikasi_surat_by_id';
$route['simpan_klasifikasi_surat'] = 'HalamanPengaturan/simpan_klasifikasi_surat';
$route['hapus_klasifikasi_surat'] = 'HalamanPengaturan/hapus_klasifikasi_surat';

$route['daftar_template'] = 'TemplateSurat/get_templates';
$route['get_template'] = 'TemplateSurat/get_template';
$route['get_template_by_id'] = 'TemplateSurat/get_template_by_id';
$route['simpan_template'] = 'TemplateSurat/simpan_template';

$route['get_jenis_surat_datatables'] = 'HalamanPengaturan/get_jenis_surat_datatables';
$route['get_jenis_surat_by_id'] = 'HalamanPengaturan/get_jenis_surat_by_id';

$route['get_data_jenis_surat'] = 'HalamanPengaturan/get_data_jenis_surat';
$route['simpan_jenis_surat'] = 'HalamanPengaturan/simpan_jenis_surat';
$route['hapus_jenis_surat'] = 'HalamanPengaturan/hapus_jenis_surat';

// Surat Keluar routes
$route['simpan_sk'] = 'HalamanSuratKeluar/simpan_sk';
$route['get_jenis_surat_sk'] = 'HalamanSuratKeluar/get_jenis_surat_sk';
$route['get_klasifikasi_surat_sk'] = 'HalamanSuratKeluar/get_klasifikasi_surat_sk';
$route['get_templates_sk'] = 'HalamanSuratKeluar/get_templates';
$route['get_pegawai_sk'] = 'HalamanSuratKeluar/get_pegawai_sk';
$route['get_pensign_template'] = 'HalamanSuratKeluar/get_pensign_template';
$route['get_data_surat_keluar'] = 'HalamanSuratKeluar/get_data_surat_keluar';
$route['save_surat_keluar'] = 'HalamanSuratKeluar/save_surat_keluar';
$route['arsip_sk_datatables'] = 'HalamanSuratKeluar/get_arsip_sk_datatables';
$route['preview_surat_keluar'] = 'HalamanSuratKeluar/preview_surat_keluar';
$route['hapus_sk'] ='HalamanSuratKeluar/hapus_sk';
$route['simpan_parameter_surat'] = 'HalamanSuratKeluar/simpan_parameter_surat';
$route['simpan_penandatangan'] = 'HalamanSuratKeluar/simpan_penandatangan';

$route['get_nomor_surat_by_id'] = 'HalamanPengaturan/get_nomor_surat_by_id';
$route['simpan_nomor_surat'] = 'HalamanPengaturan/simpan_nomor_surat';
$route['hapus_nomor_surat'] = 'HalamanPengaturan/hapus_nomor_surat';

$route['lihat_nomor_surat_by_id'] = 'HalamanPengaturan/lihat_nomor_surat_by_id';
$route['daftar_nomor_surat_ketua'] = 'HalamanPengaturan/get_nomor_surat_ketua_datatables';
$route['daftar_nomor_surat_panitera'] = 'HalamanPengaturan/get_nomor_surat_panitera_datatables';
$route['daftar_nomor_surat_sekretaris'] = 'HalamanPengaturan/get_nomor_surat_sekretaris_datatables';

// Tracking routes (public access)
$route['tracking'] = 'Tracking/index';
$route['tracking/cek'] = 'Tracking/cek';