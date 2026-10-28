<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// DEKLARASI DASAR & CLEAR CACHE DATABASE
$CI =& get_instance();
if (!isset($CI->db)) { $CI->load->database(); }
$CI->load->helper('url'); // Memastikan URL Helper aktif untuk fungsi site_url()

if (!function_exists('singkat_kode_mapel')) {
    // Cadangan: bila tabel master mapel tidak punya kolom kode, buat singkatan dari huruf awal tiap kata.
    // Contoh: "Pendidikan Jasmani, Olahraga dan Kesehatan" => PJOK, "Ilmu Pengetahuan Alam" => IPA.
    // Nama satu kata (mis. "Matematika") dibiarkan apa adanya.
    function singkat_kode_mapel($nama) {
        $nama = trim((string)$nama);
        if ($nama === '') { return ''; }
        $kata = preg_split('/[\s,\/\-&]+/', $nama, -1, PREG_SPLIT_NO_EMPTY);
        $abaikan = ['dan', 'dengan', 'di', 'dalam', 'untuk', 'atau', 'serta', 'yang', 'ke'];
        $inisial = '';
        $dipakai = 0;
        foreach ($kata as $k) {
            if (in_array(strtolower($k), $abaikan)) { continue; }
            $dipakai++;
            $inisial .= strtoupper(substr($k, 0, 1));
        }
        return ($dipakai >= 2) ? $inisial : $nama;
    }
}

$data_siswa = [];
// true = hanya tampilkan data ujian yang mulai HARI INI. Set false untuk menampilkan semua riwayat.
$hanya_hari_ini = true;
$list_mapel_aktif = []; 
$list_kelas_aktif = []; 

try {
    // =================================================================
    // DETEKSI OTOMATIS TABEL MASTER MATA PELAJARAN
    // =================================================================
    $tabel_mapel = '';
    $all_tables = $CI->db->list_tables();
    foreach ($all_tables as $tb) {
        if (in_array(strtolower($tb), ['cbt_mapel', 'master_mapel', 'mapel', 'm_mapel', 'mata_pelajaran', 'tbl_mapel'])) {
            $tabel_mapel = $tb;
            break;
        }
    }

    $kolom_nama_mapel = 'nama_mapel';
    if (!empty($tabel_mapel)) {
        if ($CI->db->field_exists('nama_mapel', $tabel_mapel)) { $kolom_nama_mapel = 'nama_mapel'; }
        elseif ($CI->db->field_exists('nama', $tabel_mapel)) { $kolom_nama_mapel = 'nama'; }
        elseif ($CI->db->field_exists('nama_mata_pelajaran', $tabel_mapel)) { $kolom_nama_mapel = 'nama_mata_pelajaran'; }
    }

    // Kolom kode mata pelajaran di tabel master (otomatis dicari; jika tidak ada dipakai singkatan nama)
    $kolom_kode_mapel = '';
    if (!empty($tabel_mapel)) {
        foreach (['kode_mapel', 'kode', 'mapel_kode', 'kode_mata_pelajaran', 'kd_mapel', 'kode_pelajaran', 'singkatan', 'alias'] as $kk) {
            if ($CI->db->field_exists($kk, $tabel_mapel)) { $kolom_kode_mapel = $kk; break; }
        }
    }
    $sel_kode_mapel = ($kolom_kode_mapel !== '') ? ", $kolom_kode_mapel AS kode_mapel_x" : '';

    $kolom_id_master_mapel = 'id_mapel';
    if (!empty($tabel_mapel)) {
        if ($CI->db->field_exists('id_mapel', $tabel_mapel)) { $kolom_id_master_mapel = 'id_mapel'; }
        elseif ($CI->db->field_exists('id', $tabel_mapel)) { $kolom_id_master_mapel = 'id'; }
        elseif ($CI->db->field_exists('mapel_id', $tabel_mapel)) { $kolom_id_master_mapel = 'mapel_id'; }
    }

    // =================================================================
    // PROSES PENGAMBILAN DATA UTAMA MONITORING SISWA
    // =================================================================
    $tabel_siswa = '';
    if ($CI->db->table_exists('master_siswa')) { $tabel_siswa = 'master_siswa'; }
    elseif ($CI->db->table_exists('m_siswa')) { $tabel_siswa = 'm_siswa'; }
    elseif ($CI->db->table_exists('siswa')) { $tabel_siswa = 'siswa'; }

    $tabel_durasi = '';
    if ($CI->db->table_exists('cbt_durasi_siswa')) { $tabel_durasi = 'cbt_durasi_siswa'; }
    elseif ($CI->db->table_exists('cbt_durasi')) { $tabel_durasi = 'cbt_durasi'; }

    // Bila siswa punya >1 baris durasi (beberapa mapel dalam sehari), pakai yang waktu mulainya paling baru.
    // Urut berdasarkan WAKTU MULAI (bukan id), karena id_durasi mengikuti nomor jadwal, bukan urutan waktu.
    $order_durasi = '';
    if (!empty($tabel_durasi)) {
        $kolom_urut = [];
        foreach (['mulai', 'waktu_mulai'] as $kc) {
            if ($CI->db->field_exists($kc, $tabel_durasi)) { $kolom_urut[] = "$kc DESC"; break; }
        }
        foreach (['id_durasi', 'id'] as $kc) {
            if ($CI->db->field_exists($kc, $tabel_durasi)) { $kolom_urut[] = "$kc DESC"; break; }
        }
        if (!empty($kolom_urut)) { $order_durasi = ' ORDER BY ' . implode(', ', $kolom_urut); }
    }

    $kolom_kunci = '';
    if ($CI->db->table_exists('cbt_soal')) {
        if ($CI->db->field_exists('kunci', 'cbt_soal')) { $kolom_kunci = 'kunci'; } 
        elseif ($CI->db->field_exists('kunci_jawaban', 'cbt_soal')) { $kolom_kunci = 'kunci_jawaban'; } 
        elseif ($CI->db->field_exists('jawaban', 'cbt_soal')) { $kolom_kunci = 'jawaban'; }
    }

    $kolom_id_bank_soal = '';
    if ($CI->db->table_exists('cbt_soal')) {
        if ($CI->db->field_exists('id_bank', 'cbt_soal')) { $kolom_id_bank_soal = 'id_bank'; } 
        elseif ($CI->db->field_exists('id_bk', 'cbt_soal')) { $kolom_id_bank_soal = 'id_bk'; }
    }

    if (!empty($tabel_siswa)) {
        $query_siswa = $CI->db->query("SELECT * FROM $tabel_siswa")->result_array();

        if (!empty($query_siswa)) {
            foreach ($query_siswa as $row) {
                $id_s = $row['id_siswa'] ?? $row['id'] ?? '0';
                $nama_peserta = $row['nama'] ?? $row['nama_siswa'] ?? 'Tanpa Nama';
                
                $jk = isset($row['jenis_kelamin']) ? strtoupper(trim($row['jenis_kelamin'])) : 'L';

                $kelas_siswa = 'Tanpa Kelas';
                if ($id_s !== '0') {
                    $cek_kolom_langsung = $row['kelas'] ?? $row['id_kelas'] ?? $row['nama_kelas'] ?? '';
                    if (!empty($cek_kolom_langsung) && !is_numeric($cek_kolom_langsung)) {
                        $kelas_siswa = $cek_kolom_langsung;
                    } else {
                        $id_kelas_ditemukan = '';
                        if (isset($row['id_kelas']) && !empty($row['id_kelas'])) {
                            $id_kelas_ditemukan = $row['id_kelas'];
                        } elseif ($CI->db->table_exists('kelas_siswa')) {
                            $kolom_id_S = $CI->db->field_exists('id_siswa', 'kelas_siswa') ? 'id_siswa' : 'siswa_id';
                            $res_jembatan = $CI->db->query("SELECT id_kelas FROM kelas_siswa WHERE $kolom_id_S = ?", [$id_s])->row_array();
                            if (!empty($res_jembatan)) { $id_kelas_ditemukan = $res_jembatan['id_kelas']; }
                        }
                        if (!empty($id_kelas_ditemukan) && $CI->db->table_exists('master_kelas')) {
                            $get_nama_kelas = $CI->db->query("SELECT nama_kelas FROM master_kelas WHERE id_kelas = ?", [$id_kelas_ditemukan])->row_array();
                            if (!empty($get_nama_kelas) && isset($get_nama_kelas['nama_kelas'])) { $kelas_siswa = $get_nama_kelas['nama_kelas']; }
                        }
                    }
                }

                // Ambil SEMUA baris durasi siswa (satu baris per jadwal/mapel), bukan hanya yang terbaru.
                $list_durasi = [];
                if (!empty($tabel_durasi) && $id_s !== '0') {
                    $list_durasi = $CI->db->query("SELECT * FROM $tabel_durasi WHERE id_siswa = ?" . $order_durasi, [$id_s])->result_array();
                }

                foreach ($list_durasi as $cek_durasi) {
                    $status = '0'; 
                    $id_j = '0';
                    $id_b = '1'; 
                    $benar_tampil = '0';
                    $salah_tampil = '0';
                    $nilai_tampil = '0'; 
                    $bobot_status = 0; 
                    $nilai_internal = 0.0;
                    $waktu_selesai_timestamp = 0; 
                    $progress_persen = 0; 
                    $nama_mapel_siswa = ''; $kode_mapel_siswa = ''; 

                    if ($hanya_hari_ini) {
                        $mulai_cek = $cek_durasi['waktu_mulai'] ?? $cek_durasi['mulai'] ?? '';
                        if (!empty($mulai_cek) && date('Y-m-d', strtotime($mulai_cek)) !== date('Y-m-d')) { continue; }
                    }

                    if (!empty($cek_durasi)) {
                        $status_clean = isset($cek_durasi['status']) ? trim(strtolower($cek_durasi['status'])) : '';
                        $id_j = $cek_durasi['id_jadwal'] ?? '0';
                        $id_b = $cek_durasi['id_bank'] ?? $cek_durasi['id_bk'] ?? '1'; 
                        $waktu_mulai_raw = $cek_durasi['waktu_mulai'] ?? $cek_durasi['mulai'] ?? '';

                        // 1. Hitung perkiraan waktu selesai terlebih dahulu
                        if ($id_j !== '0' && !empty($waktu_mulai_raw)) {
                            $durasi_menit = 0;
                            if ($CI->db->table_exists('cbt_jadwal')) {
                                if ($CI->db->field_exists('durasi_ujian', 'cbt_jadwal')) {
                                    $cek_jdwl_durasi = $CI->db->query("SELECT durasi_ujian FROM cbt_jadwal WHERE id_jadwal = ?", [$id_j])->row_array();
                                    $durasi_menit = intval($cek_jdwl_durasi['durasi_ujian'] ?? 0);
                                } elseif ($CI->db->field_exists('lama_ujian', 'cbt_jadwal')) {
                                    $cek_jdwl_durasi = $CI->db->query("SELECT lama_ujian FROM cbt_jadwal WHERE id_jadwal = ?", [$id_j])->row_array();
                                    $durasi_menit = intval($cek_jdwl_durasi['lama_ujian'] ?? 0);
                                }
                            }
                            if ($durasi_menit > 0) {
                                $waktu_selesai_timestamp = strtotime($waktu_mulai_raw) + ($durasi_menit * 60);
                            }
                        }

                        // 2. Tentukan Status berdasarkan Database Ujian ATAU Batas Waktu Server
                        $sekarang_timestamp = time();
                        if ($status_clean === '2' || $status_clean === 'selesai') {
                            $status = 'selesai';
                            $bobot_status = 1; 
                        } elseif ($waktu_selesai_timestamp > 0 && $sekarang_timestamp >= $waktu_selesai_timestamp) {
                            // JIKA WAKTU HABIS SECARA TIMESTAMP SERVER, PAKSA STATUS JADI SELESAI
                            $status = 'selesai';
                            $bobot_status = 1;
                        } else {
                            $status = '1'; 
                            $bobot_status = 2; 
                        }

                        if ($id_j !== '0') {
                            if ($id_b === '0' || $id_b === '1' || empty($id_b)) {
                                if ($CI->db->table_exists('cbt_jadwal')) {
                                    $cek_jdwl = $CI->db->query("SELECT id_bank FROM cbt_jadwal WHERE id_jadwal = ?", [$id_j])->row_array();
                                    if(empty($cek_jdwl)){
                                        $cek_jdwl = $CI->db->query("SELECT id_bk FROM cbt_jadwal WHERE id_jadwal = ?", [$id_j])->row_array();
                                        $id_b = $cek_jdwl['id_bk'] ?? '1';
                                    } else {
                                        $id_b = $cek_jdwl['id_bank'] ?? '1';
                                    }
                                }
                            }

                            if ($CI->db->table_exists('cbt_bank_soal')) {
                                $bank_row = $CI->db->query("SELECT bank_mapel_id, bank_kode FROM cbt_bank_soal WHERE id_bank = ?", [$id_b])->row_array();
                                if (!empty($bank_row)) {
                                    $id_mapel_referensi = $bank_row['bank_mapel_id'] ?? '';
                                    if (!empty($id_mapel_referensi) && !empty($tabel_mapel) && !empty($kolom_id_master_mapel)) {
                                        $get_m = $CI->db->query("SELECT $kolom_nama_mapel" . $sel_kode_mapel . " FROM $tabel_mapel WHERE $kolom_id_master_mapel = ?", [$id_mapel_referensi])->row_array();
                                        if (!empty($get_m) && !empty(trim($get_m[$kolom_nama_mapel]))) {
                                            $nama_mapel_siswa = trim($get_m[$kolom_nama_mapel]);
                                            $kode_mapel_siswa = (!empty(trim($get_m['kode_mapel_x'] ?? ''))) ? trim($get_m['kode_mapel_x']) : singkat_kode_mapel($nama_mapel_siswa);
                                        }
                                    }
                                    if (empty($nama_mapel_siswa) && isset($bank_row['bank_kode']) && !empty(trim($bank_row['bank_kode']))) {
                                        $nama_mapel_siswa = trim($bank_row['bank_kode']);
                                        $kode_mapel_siswa = $nama_mapel_siswa;
                                    }
                                }
                            }

                            if (empty($nama_mapel_siswa)) {
                                $nama_mapel_siswa = "Mapel ID: " . $id_b;
                                $kode_mapel_siswa = $nama_mapel_siswa;
                            }

                            if ($CI->db->table_exists('cbt_soal_siswa') && $CI->db->table_exists('cbt_soal')) {
                                $log_soal = $CI->db->query("SELECT id_soal, jawaban_siswa FROM cbt_soal_siswa WHERE id_siswa = ? AND id_jadwal = ?", [$id_s, $id_j])->result_array();
                                $total_soal_siswa = count($log_soal);
                                $hitung_benar = 0;
                                $soal_terjawab = 0;

                                if ($total_soal_siswa > 0) {
                                    foreach ($log_soal as $ls) {
                                        $jwb_siswa = isset($ls['jawaban_siswa']) ? trim(strtoupper($ls['jawaban_siswa'])) : '';
                                        $id_soal_asli = $ls['id_soal'] ?? '0';

                                        if ($jwb_siswa !== '' && $jwb_siswa !== '0' && $jwb_siswa !== null) {
                                            $soal_terjawab++; 
                                            if (!empty($kolom_kunci)) {
                                                if (!empty($kolom_id_bank_soal)) {
                                                    $soal_induk = $CI->db->query("SELECT $kolom_kunci, $kolom_id_bank_soal FROM cbt_soal WHERE id_soal = ?", [$id_soal_asli])->row_array();
                                                    if (($id_b === '1' || empty($id_b)) && isset($soal_induk[$kolom_id_bank_soal])) {
                                                        $id_b = $soal_induk[$kolom_id_bank_soal];
                                                    }
                                                } else {
                                                    $soal_induk = $CI->db->query("SELECT $kolom_kunci FROM cbt_soal WHERE id_soal = ?", [$id_soal_asli])->row_array();
                                                }

                                                $kunci_asli = isset($soal_induk[$kolom_kunci]) ? trim(strtoupper($soal_induk[$kolom_kunci])) : '';
                                                if ($kunci_asli !== '' && $jwb_siswa === $kunci_asli) { $hitung_benar++; }
                                            }
                                        }
                                    }
                                    
                                    $benar_tampil = $hitung_benar;
                                    $salah_tampil = $total_soal_siswa - $hitung_benar;
                                    
                                    $nilai_temp = ($hitung_benar / $total_soal_siswa) * 100;
                                    $nilai_tampil = floatval(number_format($nilai_temp, 1, '.', '')); 
                                    $nilai_internal = $nilai_temp;

                                    if ($status === 'selesai' || $soal_terjawab >= $total_soal_siswa) {
                                        $progress_persen = 100;
                                    } else {
                                        $progress_persen = ($total_soal_siswa > 0) ? round(($soal_terjawab / $total_soal_siswa) * 100) : 0;
                                    }
                                } else {
                                    $progress_persen = 0;
                                }
                            }
                        }
                    }

                    if ($status !== '0') {
                        $data_siswa[] = [
                            'row_key'        => $id_s . '_' . $id_j,
                            'id_siswa'       => $id_s,
                            'nama'           => $nama_peserta,
                            'kelas'          => $kelas_siswa,
                            'jenis_kelamin'  => $jk,
                            'nama_mapel'     => ($kode_mapel_siswa !== '' ? $kode_mapel_siswa : $nama_mapel_siswa),
                            'nama_mapel_lengkap' => $nama_mapel_siswa,
                            'waktu_selesai'  => $waktu_selesai_timestamp, 
                            'progress'       => $progress_persen,
                            'benar'          => $benar_tampil,
                            'salah'          => $salah_tampil,
                            'skor'           => $nilai_tampil, 
                            'bobot_status'   => $bobot_status,  
                            'nilai_internal' => $nilai_internal, 
                            'status'         => $status,
                            'id_jadwal'      => $id_j,
                            'id_bank'        => $id_b 
                        ];

                        $mapel_tampil = ($kode_mapel_siswa !== '' ? $kode_mapel_siswa : $nama_mapel_siswa);
                        if (!in_array($mapel_tampil, $list_mapel_aktif)) { $list_mapel_aktif[] = $mapel_tampil; }
                        if (!in_array($kelas_siswa, $list_kelas_aktif)) { $list_kelas_aktif[] = $kelas_siswa; }
                    }
                }
            }
        }
    }
    
    sort($list_mapel_aktif);
    sort($list_kelas_aktif);

    usort($data_siswa, function($a, $b) {
        if ($b['nilai_internal'] !== $a['nilai_internal']) { 
            return $b['nilai_internal'] <=> $a['nilai_internal']; 
        }
        return $a['nama'] <=> $b['nama'];
    });

} catch (Exception $e) { }
?>

<style>
    /* =================================================================================== */
    /* FORCE RESET SELECTOR BAWAAN (MENCEGAH STYLING TEMPLATE MENIMPA GAYA LIVE TABEL)    */
    /* =================================================================================== */
    html body.sidebar-mini.layout-fixed.layout-navbar-fixed.text-sm div.wrapper div.content-wrapper.bg-custom-theme div.pt-4.pb-5.mx-auto div.row.px-2.connectedSortable.ui-sortable div.col-12 div.card.main-table-card.overflow-hidden div.card-body.p-0 div.table-responsive.w-full div#wadah-tabel-live.w-full div#wrapper-tabel-bypass table#tabel-live-utama.table-premium-live tbody#body-tabel-live tr td {
        background: transparent !important; padding: 14px 10px !important; border: none !important; text-align: center !important; vertical-align: middle !important; box-shadow: none !important; position: relative !important;
    }
    html body.sidebar-mini.layout-fixed.layout-navbar-fixed.text-sm div.wrapper div.content-wrapper.bg-custom-theme div.pt-4.pb-5.mx-auto div.row.px-2.connectedSortable.ui-sortable div.col-12 div.card.main-table-card.overflow-hidden div.card-body.p-0 div.table-responsive.w-full div#wadah-tabel-live.w-full div#wrapper-tabel-bypass table#tabel-live-utama.table-premium-live tbody#body-tabel-live tr td div.flex-center-wrapper.txt-kelas {
        background: transparent !important; border: none !important; box-shadow: none !important; padding: 0 !important; margin: 0 auto !important; display: inline-flex !important; align-items: center !important; justify-content: center !important;
    }

    #wrapper-tabel-bypass table.table-premium-live tbody tr td {
        background: transparent !important; padding: 14px 10px !important; border: none !important; text-align: center !important; vertical-align: middle !important;
    }
    #wrapper-tabel-bypass, #wrapper-tabel-bypass * { box-sizing: border-box; }
    #wrapper-tabel-bypass {
        background: #ffffff !important; border-radius: 12px !important; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important; 
        padding: 20px !important; font-family: 'Inter', system-ui, -apple-system, sans-serif !important; width: 100% !important; overflow-x: auto !important; transition: all 0.3s ease;
    }
    
    .filter-live-container {
        display: flex; flex-wrap: wrap; gap: 15px; align-items: center; background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 20px;
    }
    .filter-live-box { display: flex; align-items: center; gap: 8px; }
    .filter-live-box label { font-weight: 700; color: #475569; font-size: 13px; margin: 0; text-transform: uppercase; }
    .btn-hapus-ujian {
        display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto;
        width: 42px; height: 38px; padding: 0; border: none; border-radius: 6px;
        background: #dc2626; color: #ffffff; font-size: 16px; cursor: pointer; transition: all 0.2s ease;
    }
    .btn-hapus-ujian:hover { background: #b91c1c; transform: translateY(-1px); }
    .btn-hapus-ujian:disabled { opacity: 0.6; cursor: wait; transform: none; }
    .select-live-dropdown {
        padding: 8px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; color: #334155; background-color: #fff; outline: none; min-width: 0; width: auto; max-width: 190px; cursor: pointer; font-weight: 500;
    }
    .select-live-dropdown:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    
    .btn-proyektor-live {
        background-color: #0f172a !important; color: #ffffff !important; font-weight: 700 !important; padding: 9px 18px !important; border-radius: 6px !important; border: none !important;
        font-size: 13px !important; display: inline-flex !important; align-items: center !important; gap: 8px !important; cursor: pointer !important; transition: all 0.2s ease !important; text-transform: uppercase !important; margin-left: auto;
    }
    .btn-proyektor-live:hover { background-color: #1e293b !important; transform: translateY(-1px); }

    .btn-export-nilai-live {
        background-color: #16a34a !important; color: #ffffff !important; font-weight: 700 !important; padding: 9px 18px !important; border-radius: 6px !important; border: none !important;
        font-size: 13px !important; display: inline-flex !important; align-items: center !important; gap: 8px !important; cursor: pointer !important; transition: all 0.2s ease !important; text-transform: uppercase !important;
    }
    .btn-export-nilai-live:hover { background-color: #15803d !important; transform: translateY(-1px); }

    #wrapper-tabel-bypass table.table-premium-live { width: 100% !important; border-collapse: collapse !important; }
    #wrapper-tabel-bypass table.table-premium-live th {
        text-align: center !important; vertical-align: middle !important; padding: 14px 10px !important; border: none !important; background-color: #e2e8f0 !important; color: #475569 !important; font-weight: 700 !important; font-size: 13px !important; text-transform: uppercase !important; letter-spacing: 0.5px !important;
    }
    #wrapper-tabel-bypass table.table-premium-live tbody tr { border-bottom: 1px solid #f1f5f9 !important; transition: all 0.2s ease; }
    #wrapper-tabel-bypass table.table-premium-live tbody tr:hover { background-color: #f8fafc !important; }
    
    .flex-center-wrapper { display: inline-flex !important; align-items: center !important; justify-content: center !important; text-align: center !important; width: 100% !important; }
    .container-nama-center { display: inline-flex !important; align-items: center !important; justify-content: flex-start !important; text-align: left !important; width: 100% !important; gap: 10px !important; }
    
    .status-dot-indicator { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; display: inline-block; }
    .dot-mengerjakan { background-color: #22c55e !important; box-shadow: 0 0 6px rgba(34, 197, 94, 0.7); }
    .dot-selesai { background-color: #2563eb !important; }

    .avatar-image-box {
        width: 36px !important; height: 36px !important; border-radius: 50% !important; flex-shrink: 0 !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; overflow: hidden !important; background-color: #f1f5f9 !important; border: 1px solid #e2e8f0 !important;
    }
    .avatar-image-box img { width: 100% !important; height: 100% !important; object-fit: cover !important; border-radius: 50% !important; }
    
    .txt-kelas { font-weight: 600; color: #475569; }
    .txt-mapel { font-weight: 700; color: #0284c7; font-size: 13px; }
    .txt-count { font-weight: 700; color: #334155; font-size: 14px; }
    
    .nilai-live-bold { font-size: 16px !important; font-weight: 800 !important; color: #16a34a !important; }
    .nilai-live-selesai { font-size: 16px !important; font-weight: 700 !important; color: #2563eb !important; }

    .progress-outer-layout {
        display: flex !important; flex-direction: column !important; width: 100% !important; max-width: 190px !important; margin: 0 auto !important; background: transparent !important; border: none !important; box-shadow: none !important;
    }
    .progress-labels-box {
        display: flex !important; 
        justify-content: space-between !important; 
        align-items: center !important; 
        width: 100% !important; 
        margin-bottom: 4px !important; 
        padding: 0 4px !important; 
        background: transparent !important;
    }
    .progress-lbl-text { 
        font-size: 14px !important; 
        font-weight: 800 !important; 
        color: #000000 !important; 
        font-family: Arial, sans-serif !important; 
        line-height: 1 !important; 
        background: transparent !important;
    }
    .progress-lbl-start {
        font-size: 13px !important;
        font-weight: 700 !important;
        color: #94a3b8 !important; 
        font-family: Arial, sans-serif !important;
        line-height: 1 !important;
    }
    .progress-bar-bg { 
        width: 100% !important; background-color: #e2e8f0 !important; height: 14px !important; border-radius: 30px !important; position: relative !important; box-shadow: inset 0 2px 4px rgba(0,0,0,0.15) !important; border: none !important; padding: 0 !important; overflow: visible !important;
    }
    .progress-bar-fill { 
        height: 100% !important; background: linear-gradient(90deg, #3b82f6 0%, #06b6d4 50%, #10b981 100%) !important; border-radius: 30px !important; position: relative !important; box-shadow: 0 1px 3px rgba(0,0,0,0.2) !important;
        animation: barMajuGrow 1.2s cubic-bezier(0.25, 1, 0.5, 1) forwards !important;
    }
    .progress-bar-fill::after {
        content: '' !important; position: absolute !important; top: 1px !important; left: 2px !important; right: 2px !important; height: 45% !important; background: linear-gradient(rgba(255,255,255,0.45), rgba(255,255,255,0.05)) !important; border-radius: 30px !important; z-index: 1 !important;
    }
    @keyframes barMajuGrow { from { width: 0% !important; } }

    .pulse-glow-node {
        position: absolute !important; top: 50% !important; right: -1px !important; transform: translateY(-50%) !important; width: 14px !important; height: 14px !important; background-color: #ffffff !important; border: 3.5px solid #06b6d4 !important; border-radius: 50% !important; box-shadow: 0 0 8px rgba(6, 182, 212, 0.9) !important; z-index: 5 !important;
    }
    .pulse-glow-node::before {
        content: '' !important; position: absolute !important; top: -11px !important; left: -11px !important; width: 28px !important; height: 28px !important; border: 2.5px solid rgba(6, 182, 212, 0.6) !important; border-radius: 50% !important; animation: waveMelarMelingkar 1.6s infinite ease-out !important;
    }
    @keyframes waveMelarMelingkar { 0% { transform: scale(0.4); opacity: 1; } 100% { transform: scale(1.6); opacity: 0; } }
    
    .badge-status-active { 
        background-color: #dcfce7 !important; color: #15803d !important; padding: 6px 14px !important; border-radius: 20px !important; font-size: 12px !important; font-weight: 700 !important; display: inline-flex !important; align-items: center !important; gap: 5px !important; 
    }
    .badge-status-active::before { content: ''; width: 6px; height: 6px; background-color: #22c55e; border-radius: 50%; }
    
    .badge-status-completed { 
        background-color: #dbeafe !important; color: #1d4ed8 !important; padding: 6px 14px !important; border-radius: 20px !important; font-size: 12px !important; font-weight: 700 !important; display: inline-flex !important; align-items: center !important; gap: 5px !important; text-transform: capitalize !important; 
    }
    .badge-status-completed::before { content: ''; width: 6px; height: 6px; background-color: #2563eb; border-radius: 50%; }
    
    .center-btn-group { display: inline-flex !important; gap: 4px !important; justify-content: center !important; align-items: center !important; }
    .countdown-timer { font-weight: bold; font-family: 'Courier New', Courier, monospace; font-size: 14px; color: #ef4444; }
    .countdown-finished { font-weight: 600; color: #2563eb; font-size: 13px; }

    /* Panel kontrol login siswa per kelas: satu baris penuh di bawah filter */
    .login-ctrl-bar {
        flex: 0 0 100%; display: flex; flex-wrap: wrap; align-items: center; gap: 12px;
        margin-top: 2px; padding-top: 14px; border-top: 1px dashed #cbd5e1;
    }
    .login-ctrl-title { display: inline-flex; align-items: center; gap: 8px; font-weight: 700; font-size: 13px; color: #475569; text-transform: uppercase; margin-right: 4px; }
    .login-ctrl-title i { color: #64748b; }
    .login-ctrl-bar .select-live-dropdown { height: 38px; min-width: 170px; }
    .pill-login {
        display: inline-flex; align-items: center; gap: 6px; height: 38px; padding: 0 14px; border-radius: 999px;
        font-size: 12px; font-weight: 800; letter-spacing: .4px; text-transform: uppercase; white-space: nowrap;
    }
    .pill-login::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: currentColor; }
    .pill-login.pill-on  { background: #dcfce7; color: #15803d; }
    .pill-login.pill-off { background: #fee2e2; color: #b91c1c; }
    .pill-login.pill-mix { background: #fef3c7; color: #b45309; }
    .btn-login-on, .btn-login-off {
        display: inline-flex !important; align-items: center !important; justify-content: center !important; gap: 0 !important;
        height: 38px !important; width: 42px !important;
        color: #ffffff !important; font-weight: 700 !important; padding: 0 !important; border-radius: 6px !important; border: none !important;
        font-size: 16px !important; cursor: pointer !important; text-transform: uppercase !important; transition: all 0.2s ease !important;
    }
    .btn-login-on  { background-color: #16a34a !important; }
    .btn-login-on:hover  { background-color: #15803d !important; transform: translateY(-1px); }
    .btn-login-off { background-color: #dc2626 !important; }
    .btn-login-off:hover { background-color: #b91c1c !important; transform: translateY(-1px); }
    .login-ctrl-hint { flex: 1 1 220px; font-size: 12px; color: #94a3b8; line-height: 1.4; }

    /* =================================================================================== */
    /* MODE PROYEKTOR: PAPAN SKOR LIVE (tampilan terpisah, dirender dari data tabel)       */
    /* =================================================================================== */
    #wrapper-tabel-bypass .pj-stage { display: none; }
    #wrapper-tabel-bypass.mode-proyektor-aktif {
        position: fixed !important; inset: 0 !important; z-index: 99999 !important;
        width: 100vw !important; height: 100vh !important; padding: 0 !important;
        border-radius: 0 !important; box-shadow: none !important; overflow: hidden !important;
        background: #0a1024 !important;
    }
    #wrapper-tabel-bypass.mode-proyektor-aktif > .filter-live-container,
    #wrapper-tabel-bypass.mode-proyektor-aktif > table { display: none !important; }
    #wrapper-tabel-bypass.mode-proyektor-aktif > .pj-stage { display: flex !important; }

    .pj-stage {
        --pj-bg: #0a1024; --pj-panel: #111a38; --pj-line: #22305f; --pj-text: #eef2ff; --pj-mute: #8d9bc8;
        --pj-live: #34d399; --pj-done: #60a5fa; --pj-gold: #fbbf24; --pj-silver: #cfd8ea; --pj-bronze: #e0905a;
        --pj-warn: #fbbf24; --pj-crit: #fb7185;
        flex-direction: column; gap: 1em; width: 100%; height: 100%; padding: 1.5em 2.2em 1em;
        overflow-x: auto; overflow-y: hidden; color: var(--pj-text);
        font-family: 'Bahnschrift', 'Segoe UI Variable Display', 'Segoe UI', system-ui, sans-serif;
        font-size: clamp(12px, calc(0.55vw + 0.85vh), 40px);
        background: radial-gradient(70em 32em at 88% -12%, rgba(59, 91, 219, 0.32), transparent 62%), var(--pj-bg);
    }
    .pj-stage > * { min-width: 64em; flex-shrink: 0; }

    /* Header */
    .pj-stage .pj-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 2em; }
    .pj-stage .pj-title { display: flex; align-items: center; gap: 1em; margin-bottom: .8em; }
    .pj-stage .pj-title h1 { margin: 0; padding: 0; font-size: 2.2em; font-weight: 700; line-height: 1; color: #fff; letter-spacing: .01em; }
    .pj-stage .pj-livebadge {
        display: inline-flex; align-items: center; gap: .5em; padding: .35em .85em; border-radius: 99em;
        background: rgba(52, 211, 153, .14); color: var(--pj-live); font-weight: 700; font-size: .95em;
    }
    .pj-stage .pj-livebadge i { width: .65em; height: .65em; border-radius: 50%; background: var(--pj-live); animation: pjPing 1.8s infinite; }
    @keyframes pjPing { 0% { box-shadow: 0 0 0 0 rgba(52, 211, 153, .75); } 80%, 100% { box-shadow: 0 0 0 .7em rgba(52, 211, 153, 0); } }

    .pj-stage .pj-filters { display: flex; gap: 1.6em; flex-wrap: wrap; }
    .pj-stage .pj-filters label { display: flex; align-items: center; gap: .6em; margin: 0; font-weight: 500; font-size: .95em; color: var(--pj-mute); }
    .pj-stage .pj-sel {
        min-width: 0; width: auto; max-width: 14em; padding: .4em .8em; font: inherit; font-size: 1em; color: var(--pj-text);
        background: #0f1830; border: 1px solid var(--pj-line); border-radius: .6em; outline: none; cursor: pointer; height: auto;
    }
    .pj-stage .pj-sel:focus { border-color: #5b7cfa; box-shadow: 0 0 0 .2em rgba(91, 124, 250, .25); }
    .pj-stage .pj-sel option { background: #0f1830; color: var(--pj-text); }

    .pj-stage .pj-head-right { display: flex; align-items: flex-start; gap: 1.4em; }
    .pj-stage .pj-clockbox { text-align: right; }
    .pj-stage .pj-clock { font-size: 2.8em; font-weight: 700; line-height: 1; font-variant-numeric: tabular-nums; color: #fff; }
    .pj-stage .pj-date { margin-top: .35em; color: var(--pj-mute); font-size: 1em; }
    .pj-stage .pj-exit {
        padding: .45em .95em; font: inherit; font-size: .9em; color: var(--pj-mute); background: transparent;
        border: 1px solid var(--pj-line); border-radius: .6em; cursor: pointer; transition: color .15s, border-color .15s;
    }
    .pj-stage .pj-exit:hover, .pj-stage .pj-exit:focus-visible { color: #fff; border-color: var(--pj-crit); outline: none; }

    /* Ringkasan */
    .pj-stage .pj-stats { display: grid; grid-template-columns: repeat(4, 1fr); background: var(--pj-panel); border: 1px solid var(--pj-line); border-radius: 1em; }
    .pj-stage .pj-stat { padding: .85em 1.5em; border-left: 1px solid var(--pj-line); min-width: 0; }
    .pj-stage .pj-stat:first-child { border-left: 0; }
    .pj-stage .pj-stat b { display: block; font-size: 2.6em; font-weight: 800; line-height: 1.05; font-variant-numeric: tabular-nums; }
    .pj-stage .pj-stat span { display: block; color: var(--pj-mute); font-size: 1em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .pj-stage .st-aktif b { color: var(--pj-live); }
    .pj-stage .st-selesai b { color: var(--pj-done); }
    .pj-stage .st-avg b { color: #fff; }
    .pj-stage .st-top b { color: var(--pj-gold); }

    /* Kolom & baris */
    .pj-stage .pj-cols, .pj-stage .pj-row {
        display: grid; align-items: center; column-gap: 1em;
        grid-template-columns: 3.4em minmax(13em, 2.6fr) 5.6em 7.4em minmax(10em, 1.6fr) 6.8em 3.6em 3.6em 4.8em;
    }
    /* Header memakai font-size 1em agar lebar kolom (satuan em) SAMA PERSIS dengan baris data.
       Ukuran huruf header dikecilkan pada anak elemennya saja. Padding = padding baris + border 1px + padding kanan list (.3em). */
    .pj-stage .pj-cols { box-sizing: border-box; padding: 0 calc(1.2em + 1px + .3em) 0 calc(1.5em + 1px); color: var(--pj-mute); font-size: 1em; font-weight: 600; margin-bottom: -.4em; }
    .pj-stage .pj-cols > div { font-size: .9em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .pj-stage .pj-cols > div:nth-child(1), .pj-stage .pj-cols > div:nth-child(3), .pj-stage .pj-cols > div:nth-child(4), .pj-stage .pj-cols > div:nth-child(n+6) { text-align: center; }

    .pj-stage .pj-list { flex: 1 1 0; min-height: 0; overflow-y: auto; display: flex; flex-direction: column; gap: .5em; scrollbar-width: none; -ms-overflow-style: none; padding: .3em .3em 2.6em 0;
        -webkit-mask-image: linear-gradient(to bottom, #000 calc(100% - 2.4em), transparent); mask-image: linear-gradient(to bottom, #000 calc(100% - 2.4em), transparent); }
    .pj-stage .pj-list::-webkit-scrollbar { display: none; width: 0; height: 0; }
    .pj-stage .pj-empty { flex: 1 1 0; display: none; align-items: center; justify-content: center; text-align: center; color: var(--pj-mute); font-size: 1.5em; line-height: 1.5; }

    .pj-stage .pj-row {
        position: relative; flex: 0 0 auto; padding: .75em 1.2em .75em 1.5em;
        background: var(--pj-panel); border: 1px solid var(--pj-line); border-radius: .9em; --stripe: var(--pj-done);
    }
    .pj-stage .pj-row::before { content: ''; position: absolute; left: 0; top: .6em; bottom: .6em; width: .3em; border-radius: 0 .3em .3em 0; background: var(--stripe); }
    .pj-stage .pj-row.is-active { --stripe: var(--pj-live); }
    .pj-stage .pj-row.is-done { --stripe: var(--pj-done); }
    .pj-stage .pj-row.rank-1 { border-color: rgba(251, 191, 36, .6); background: linear-gradient(90deg, rgba(251, 191, 36, .14), var(--pj-panel) 40%); }
    .pj-stage .pj-row.rank-2 { border-color: rgba(207, 216, 234, .45); background: linear-gradient(90deg, rgba(207, 216, 234, .10), var(--pj-panel) 38%); }
    .pj-stage .pj-row.rank-3 { border-color: rgba(224, 144, 90, .5); background: linear-gradient(90deg, rgba(224, 144, 90, .12), var(--pj-panel) 38%); }

    .pj-stage .pj-rank { text-align: center; }
    .pj-stage .pj-rank span {
        display: inline-grid; place-items: center; width: 2.2em; height: 2.2em; border-radius: 50%;
        font-size: 1.1em; font-weight: 800; font-variant-numeric: tabular-nums; color: var(--pj-mute);
    }
    .pj-stage .rank-1 .pj-rank span { background: var(--pj-gold); color: #3a2a00; }
    .pj-stage .rank-2 .pj-rank span { background: var(--pj-silver); color: #1d2740; }
    .pj-stage .rank-3 .pj-rank span { background: var(--pj-bronze); color: #2f1606; }

    .pj-stage .pj-name { display: flex; align-items: center; gap: .8em; min-width: 0; }
    .pj-stage .pj-name img { width: 2.7em; height: 2.7em; flex: 0 0 auto; border-radius: 50%; object-fit: cover; background: #1b2650; border: 2px solid #2b3b78; }
    .pj-stage .pj-nm { font-size: 1.35em; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .pj-stage .pj-kelas { text-align: center; font-size: 1.15em; font-weight: 600; }
    .pj-stage .pj-mapel { text-align: center; font-size: 1.15em; font-weight: 700; color: #7dd3fc; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .pj-stage .pj-prog { display: flex; align-items: center; gap: .8em; }
    .pj-stage .pj-bar { flex: 1; height: .95em; border-radius: 1em; background: #1c2954; overflow: hidden; }
    .pj-stage .pj-bar i { display: block; position: relative; height: 100%; width: 0; overflow: hidden; border-radius: 1em; background: linear-gradient(90deg, #3b82f6, #22d3ee 55%, #34d399); transition: width 1.1s cubic-bezier(.2, .8, .2, 1); }
    .pj-stage .is-active .pj-bar i::after { content: ''; position: absolute; inset: 0; background: linear-gradient(100deg, transparent 30%, rgba(255, 255, 255, .5) 50%, transparent 70%); transform: translateX(-100%); animation: pjShine 2.6s ease-in-out infinite; }
    @keyframes pjShine { 0% { transform: translateX(-100%); } 55%, 100% { transform: translateX(100%); } }
    .pj-stage .pj-prog b { width: 3.4em; text-align: right; font-size: 1.1em; font-weight: 700; font-variant-numeric: tabular-nums; }

    .pj-stage .pj-row > div { min-width: 0; }
    .pj-stage .pj-time { text-align: center; font-size: 1.5em; font-weight: 700; font-variant-numeric: tabular-nums; color: #e8fff6; white-space: nowrap; }
    .pj-stage .pj-time.warn { color: var(--pj-warn); }
    .pj-stage .pj-time.crit { color: var(--pj-crit); animation: pjBlink 1s steps(2, start) infinite; }
    .pj-stage .pj-time.end { font-size: 1.1em; font-weight: 600; color: var(--pj-done); }
    .pj-stage .pj-time.over { font-size: 1.1em; font-weight: 600; color: var(--pj-crit); }
    @keyframes pjBlink { 50% { opacity: .35; } }

    .pj-stage .pj-num { text-align: center; font-size: 1.45em; font-weight: 700; font-variant-numeric: tabular-nums; }
    .pj-stage .pj-num.ok { color: #6ee7b7; }
    .pj-stage .pj-num.bad { color: #fda4af; }
    .pj-stage .pj-skor { text-align: center; font-size: 2.1em; font-weight: 800; line-height: 1; font-variant-numeric: tabular-nums; }
    .pj-stage .pj-skor.hi { color: #34d399; }
    .pj-stage .pj-skor.mid { color: var(--pj-gold); }
    .pj-stage .pj-skor.lo { color: var(--pj-crit); }

    /* Animasi: masuk (sekali saat dibuka) dan sorot saat data berubah */
    .pj-stage .pj-row.pj-enter { animation: pjIn .6s ease-out backwards; }
    @keyframes pjIn { from { opacity: 0; transform: translateY(.9em); } }
    .pj-stage .pj-row.pj-flash { animation: pjFlash 1.8s ease-out; }
    @keyframes pjFlash {
        0% { box-shadow: 0 0 0 .2em rgba(125, 211, 252, .9), 0 0 2.4em rgba(125, 211, 252, .5); }
        100% { box-shadow: 0 0 0 0 rgba(125, 211, 252, 0), 0 0 0 rgba(125, 211, 252, 0); }
    }

    .pj-stage .pj-foot { display: flex; justify-content: space-between; color: var(--pj-mute); font-size: .85em; }

    @media (prefers-reduced-motion: reduce) {
        .pj-stage *, .pj-stage *::before, .pj-stage *::after { animation: none !important; transition: none !important; }
    }
</style>

<?php
// Daftar kelas + status login siswa (untuk tombol Aktif/Nonaktif).
// Hanya tampil jika kolom login_aktif sudah dibuat di master_kelas.
$daftar_login_kelas = [];
if ($CI->db->table_exists('master_kelas') && $CI->db->field_exists('login_aktif', 'master_kelas')) {
    $daftar_login_kelas = $CI->db->query("SELECT id_kelas, nama_kelas, login_aktif FROM master_kelas ORDER BY nama_kelas")->result_array();
}
?>
<div id="wrapper-tabel-bypass">
    <div class="filter-live-container">
        <div class="filter-live-box">
            <label for="filter-kelas">Kelas:</label>
            <select id="filter-kelas" class="select-live-dropdown">
                <option value="">-- Semua Kelas --</option>
                <?php foreach ($list_kelas_aktif as $kls) : ?>
                    <option value="<?= htmlspecialchars($kls) ?>"><?= htmlspecialchars($kls) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-live-box">
            <label for="filter-mapel">Mapel:</label>
            <select id="filter-mapel" class="select-live-dropdown">
                <option value="">-- Semua Mapel --</option>
                <?php foreach ($list_mapel_aktif as $mp) : ?>
                    <option value="<?= htmlspecialchars($mp) ?>"><?= htmlspecialchars($mp) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="button" id="btn-hapus-ujian" class="btn-hapus-ujian" title="Hapus data ujian untuk Kelas dan Mapel yang dipilih" aria-label="Hapus data ujian kelas dan mapel yang dipilih"><i class="fa fa-trash"></i></button>

        <button type="button" id="btn-trigger-proyektor" class="btn-proyektor-live">
            <i class="fa fa-television"></i> <span id="text-btn-proyektor">Mode Proyektor</span>
        </button>
        <button type="button" id="btn-export-nilai" class="btn-export-nilai-live">
            <i class="fa fa-file-excel-o"></i> <span>Export Nilai</span>
        </button>

        <?php if (!empty($daftar_login_kelas)) : ?>
        <div class="login-ctrl-bar">
            <div class="login-ctrl-title"><i class="fa fa-lock"></i> Login Siswa</div>
            <select id="login-kelas" class="select-live-dropdown">
                <option value="semua" data-nama="SEMUA KELAS">Semua Kelas</option>
                <?php foreach ($daftar_login_kelas as $k) : ?>
                    <option value="<?= (int)$k['id_kelas'] ?>" data-nama="<?= htmlspecialchars($k['nama_kelas']) ?>" data-aktif="<?= (int)$k['login_aktif'] ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
                <?php endforeach; ?>
            </select>
            <span id="login-status-pill" class="pill-login pill-on">Aktif</span>
            <button type="button" id="btn-login-on" class="btn-login-on" title="Aktifkan login siswa" aria-label="Aktifkan login siswa"><i class="fa fa-unlock"></i></button>
            <button type="button" id="btn-login-off" class="btn-login-off" title="Nonaktifkan login siswa" aria-label="Nonaktifkan login siswa"><i class="fa fa-lock"></i></button>
            <span class="login-ctrl-hint">Nonaktifkan sebelum ujian dimulai, aktifkan saat siswa boleh masuk.</span>
        </div>
        <?php endif; ?>
    </div>

    <table class="table-premium-live" id="tabel-live-utama">
        <thead>
            <tr>
                <th style="width: 60px;" class="kolom-nomor-header">NO</th>
                <th>NAMA</th>
                <th style="width: 110px;">KELAS</th>
                <th style="width: 110px;">MAPEL</th>
                <th style="width: 130px;">SISA WAKTU</th> 
                <th style="width: 210px;">PROGRESS</th>
                <th style="width: 90px;">BENAR</th>
                <th style="width: 90px;">SALAH</th>
                <th style="width: 90px;">SCORE</th>
                <th style="width: 130px;">STATUS</th>
                <th style="width: 185px;" class="kolom-aksi-header">AKSI</th>
            </tr>
        </thead>
        <tbody id="body-tabel-live">
            <?php 
            if (!empty($data_siswa)) :
                $no = 1;
                foreach ($data_siswa as $row) : 
                    if ($row['status'] === '1') {
                        $tr_class = 'tr-mengerjakan';
                        $status_badge = '<span class="badge-status-active">Active</span>';
                        $skor_style_class = 'nilai-live-bold';
                        $dot_status_html = '<span class="status-dot-indicator dot-mengerjakan" title="Sedang Mengerjakan"></span>';
                    } else {
                        $tr_class = 'tr-selesai';
                        $status_badge = '<span class="badge-status-completed">Completed</span>';
                        $skor_style_class = 'nilai-live-selesai';
                        $dot_status_html = '<span class="status-dot-indicator dot-selesai" title="Selesai Ujian"></span>';
                    }
                    
                    $nama_file_avatar = 'siswa-l.png'; 
                    if ($row['jenis_kelamin'] === 'P' || strpos(strtolower($row['nama']), 'awa') !== false) {
                        $nama_file_avatar = 'siswa-p.png'; 
                    }

                    $hash_cek = $row['status'] . '-' . $row['benar'] . '-' . $row['salah'] . '-' . $row['skor'] . '-' . $row['progress'] . '-' . urlencode($row['kelas']) . '-' . urlencode($row['nama_mapel']) . '-' . $nama_file_avatar;
                ?>
                    <tr class="<?= $tr_class ?>" data-id="<?= htmlspecialchars($row['row_key']) ?>" data-siswa="<?= htmlspecialchars($row['id_siswa']) ?>" data-hash="<?= $hash_cek ?>" data-selesai="<?= $row['waktu_selesai'] ?>" data-status="<?= $row['status'] ?>" data-kelas="<?= htmlspecialchars($row['kelas']) ?>" data-mapel="<?= htmlspecialchars($row['nama_mapel']) ?>" data-nama="<?= htmlspecialchars($row['nama']) ?>" data-avatar="<?= base_url('assets/img/' . $nama_file_avatar) ?>" data-progress="<?= $row['progress'] ?>" data-benar="<?= $row['benar'] ?>" data-salah="<?= $row['salah'] ?>" data-skor="<?= $row['skor'] ?>">
                        <td class="kolom-nomor-data">
                            <div class="flex-center-wrapper txt-count"><?= $no++ ?></div>
                        </td>
                        <td>
                            <div class="container-nama-center">
                                <?= $dot_status_html ?>
                                <div class="avatar-image-box">
                                    <img src="<?= base_url('assets/img/' . $nama_file_avatar) ?>" alt="Avatar">
                                </div>
                                <div>
                                    <span style="font-weight: 700; color: #1e293b; display: block; font-size: 14px; line-height: 1.2;">
                                        <?= htmlspecialchars($row['nama']) ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex-center-wrapper txt-kelas"><?= htmlspecialchars($row['kelas']) ?></div>
                        </td>
                        <td>
                            <div class="flex-center-wrapper txt-mapel" title="<?= htmlspecialchars($row['nama_mapel_lengkap']) ?>"><?= htmlspecialchars($row['nama_mapel']) ?></div>
                        </td>
                        <td>
                            <div class="flex-center-wrapper countdown-render">--:--:--</div>
                        </td>
                        <td>
                            <div class="progress-outer-layout">
                                <div class="progress-labels-box">
                                    <span class="progress-lbl-start">0%</span>
                                    <span class="progress-lbl-text"><?= $row['progress'] ?>%</span>
                                </div>
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="width: <?= $row['progress'] ?>% !important;">
                                        <?php if ($row['progress'] >= 100) : ?>
                                            <div class="pulse-glow-node"></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="flex-center-wrapper txt-count"><?= $row['benar'] ?></div>
                        </td>
                        <td>
                            <div class="flex-center-wrapper txt-count"><?= $row['salah'] ?></div>
                        </td>
                        <td>
                            <div class="flex-center-wrapper">
                                <span class="<?= $skor_style_class ?>"><?= $row['skor'] ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="flex-center-wrapper"><?= $status_badge ?></div>
                        </td>
                        <td class="kolom-aksi-data">
                            <div class="btn-group center-btn-group">
                                <?php 
                                $link_edit = site_url('cbtbanksoal/buatsoal/' . $row['id_bank']);
                                $link_koreksi = site_url('cbtnilai/detail?siswa=' . $row['id_siswa'] . '&jadwal=' . $row['id_jadwal']);
                                ?>
                                <a href="<?= $link_edit ?>" class="btn btn-default btn-xs" title="Edit Soal" style="border: 1px solid #cbd5e1; color: #64748b; padding: 4px 8px; background: #fff;"><i class="fa fa-pencil"></i></a>
                                <a href="<?= $link_koreksi ?>" class="btn btn-primary btn-xs" title="Detail Koreksi" style="padding: 4px 8px; background-color: #3b82f6; border-color: #2563eb;"><i class="fa fa-eye"></i></a>
                                <button type="button" class="btn btn-danger btn-xs btn-hapus-siswa" data-key="<?= htmlspecialchars($row['row_key']) ?>" data-nama="<?= htmlspecialchars($row['nama']) ?>" title="Reset waktu dan hapus data ujian siswa ini" style="padding: 4px 8px; background-color: #dc2626; border-color: #b91c1c; color: #fff;"><i class="fa fa-trash"></i></button>
                                <button type="button" class="btn btn-warning btn-xs btn-izin-keluar" data-siswa="<?= htmlspecialchars($row['id_siswa']) ?>" data-nama="<?= htmlspecialchars($row['nama']) ?>" title="Izinkan siswa keluar dari aplikasi ujian" style="padding: 4px 8px; background-color: #f59e0b; border-color: #d97706; color: #fff;"><i class="fa fa-sign-out"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11" style="padding: 30px !important; color: #94a3b8;">Belum ada data siswa yang aktif mengikuti ujian hari ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pj-stage" id="pj-stage">
        <header class="pj-head">
            <div class="pj-head-left">
                <div class="pj-title">
                    <h1>Pantauan Ujian Langsung</h1>
                    <span class="pj-livebadge"><i></i>Langsung</span>
                </div>
                <div class="pj-filters">
                    <label>Kelas <select id="pj-kelas" class="pj-sel"></select></label>
                    <label>Mapel <select id="pj-mapel" class="pj-sel"></select></label>
                </div>
            </div>
            <div class="pj-head-right">
                <div class="pj-clockbox">
                    <div class="pj-clock" id="pj-clock">00:00:00</div>
                    <div class="pj-date" id="pj-date"></div>
                </div>
                <button type="button" id="pj-exit" class="pj-exit">Keluar (Esc)</button>
            </div>
        </header>

        <section class="pj-stats">
            <div class="pj-stat st-aktif"><b id="pj-st-aktif">0</b><span>Sedang mengerjakan</span></div>
            <div class="pj-stat st-selesai"><b id="pj-st-selesai">0</b><span>Sudah selesai</span></div>
            <div class="pj-stat st-avg"><b id="pj-st-avg">0.0</b><span>Rata-rata skor</span></div>
            <div class="pj-stat st-top"><b id="pj-st-top">0</b><span id="pj-st-topname">Skor tertinggi</span></div>
        </section>

        <div class="pj-cols">
            <div>No</div><div>Nama</div><div>Kelas</div><div>Mapel</div><div>Progres</div>
            <div>Sisa waktu</div><div>Benar</div><div>Salah</div><div>Skor</div>
        </div>

        <div class="pj-list" id="pj-list"></div>
        <div class="pj-empty" id="pj-empty">Belum ada peserta yang mengerjakan ujian.<br>Data muncul otomatis saat siswa mulai.</div>

        <footer class="pj-foot">
            <span id="pj-foot-sync">Diperbarui otomatis tiap 4 detik</span>
            <span>Daftar bergulir otomatis jika peserta banyak</span>
        </footer>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    function eksekusiFilterGabungan() {
        var kelasTerpilih = $('#filter-kelas').val();
        var mapelTerpilih = $('#filter-mapel').val();

        $('#body-tabel-live tr').each(function() {
            var kelasBaris = $(this).data('kelas');
            var mapelBaris = $(this).data('mapel');
            
            var cocokKelas = (kelasTerpilih === "" || kelasBaris == kelasTerpilih);
            var cocokMapel = (mapelTerpilih === "" || mapelBaris == mapelTerpilih);

            if (cocokKelas && cocokMapel) { $(this).show(); } else { $(this).hide(); }
        });
    }

    $(document).on('change', '#filter-kelas, #filter-mapel', function() {
        eksekusiFilterGabungan();
    });

    function jalankanCountdownSiswa() {
        var sekarang = Math.floor(Date.now() / 1000); 

        $('#body-tabel-live tr').each(function() {
            var statusSiswa = $(this).data('status');
            var timestampSelesai = parseInt($(this).data('selesai'));
            var elementTampil = $(this).find('.countdown-render');

            if (statusSiswa === 'selesai') {
                elementTampil.html('<span class="countdown-finished">Selesai</span>');
                return;
            }

            if (!timestampSelesai || timestampSelesai === 0) {
                elementTampil.html('<span class="countdown-finished">--:--</span>');
                return;
            }

            var sisaDetik = timestampSelesai - sekarang;

            if (sisaDetik > 0) {
                var jam = Math.floor(sisaDetik / 3600);
                var menit = Math.floor((sisaDetik % 3600) / 60);
                var detik = sisaDetik % 60;

                var teksJam = jam > 0 ? (jam < 10 ? '0' + jam : jam) + ':' : '';
                var teksMenit = (menit < 10 ? '0' + menit : menit) + ':';
                var teksDetik = detik < 10 ? '0' + detik : detik;

                elementTampil.html('<span class="countdown-timer">' + teksJam + teksMenit + teksDetik + '</span>');
            } else {
                elementTampil.html('<span class="countdown-finished" style="color: #ef4444; font-weight:bold;">Waktu Habis</span>');
            }
        });
    }

    if (window.liveTimerInterval) { clearInterval(window.liveTimerInterval); }
    window.liveTimerInterval = setInterval(jalankanCountdownSiswa, 1000);
    jalankanCountdownSiswa(); 

    if (window.liveSmartInterval) { clearInterval(window.liveSmartInterval); }
    window.liveSmartInterval = setInterval(function() {
        var kelasSebelumReload = $('#filter-kelas').val();
        var mapelSebelumReload = $('#filter-mapel').val();

        $.ajax({
            url: window.location.href + (window.location.href.indexOf('?') >= 0 ? '&' : '?') + 'live_nocache=' + new Date().getTime(),
            type: 'GET',
            cache: false,
            success: function(responHtml) {
                if (window.PJ) { window.PJ.touch(); }
                var htmlBaru = $(responHtml);
                var tbodyBaru = htmlBaru.find('#body-tabel-live');
                var dKelasBaru = htmlBaru.find('#filter-kelas');
                var dMapelBaru = htmlBaru.find('#filter-mapel');

                if (tbodyBaru.length > 0) {
                    var trBaru = tbodyBaru.find('tr');
                    var adaPerubahan = false;

                    if ($('#body-tabel-live tr').length !== trBaru.length) { adaPerubahan = true; }

                    trBaru.each(function() {
                        var idSiswa = $(this).data('id');
                        var hashBaru = $(this).data('hash');
                        var barisLama = $('#body-tabel-live tr[data-id="' + idSiswa + '"]');

                        if (barisLama.length > 0) {
                            var hashLama = barisLama.data('hash');
                            if (hashLama !== hashBaru) { adaPerubahan = true; }
                        } else { adaPerubahan = true; }
                    });

                    if (!adaPerubahan) { return; }

                    $('#body-tabel-live').html(tbodyBaru.html());
                    if(dKelasBaru.length > 0) { $('#filter-kelas').html(dKelasBaru.html()); }
                    if(dMapelBaru.length > 0) { $('#filter-mapel').html(dMapelBaru.html()); }

                    $('#filter-kelas').val(kelasSebelumReload);
                    $('#filter-mapel').val(mapelSebelumReload);

                    jalankanCountdownSiswa(); 
                    eksekusiFilterGabungan();
                    if (window.PJ) { window.PJ.refresh(); }
                }
            }
        });
    }, 4000); 
});
</script>

<script type="text/javascript">
/* =========================================================================
   MODE PROYEKTOR: render papan skor dari baris #body-tabel-live
   (JavaScript murni, terpisah dari logika tabel di atas)
   ========================================================================= */
(function () {
    if (window.PJ && window.PJ.destroy) { window.PJ.destroy(); }

    var byId = function (id) { return document.getElementById(id); };
    var wrap = byId('wrapper-tabel-bypass'), list = byId('pj-list'), empty = byId('pj-empty');
    if (!wrap || !list) { return; }

    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var st = { active: false, first: true, prev: {}, pos: 0, dir: 1, pauseUntil: 0, lastTs: 0, raf: 0, lastSync: Date.now(), tgl: '' };
    var listeners = [];
    function on(t, ev, fn, opt) { t.addEventListener(ev, fn, opt); listeners.push([t, ev, fn, opt]); }
    function pad(n) { return n < 10 ? '0' + n : '' + n; }
    function jam(d) { return pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds()); }
    function setText(el, v) { if (el && el.textContent !== String(v)) { el.textContent = v; } }

    /* ---------- Data ---------- */
    function ambilBaris() {
        var fk = byId('filter-kelas') ? byId('filter-kelas').value : '';
        var fm = byId('filter-mapel') ? byId('filter-mapel').value : '';
        var out = [];
        var trs = document.querySelectorAll('#body-tabel-live tr[data-id]');
        for (var i = 0; i < trs.length; i++) {
            var t = trs[i], k = t.getAttribute('data-kelas'), m = t.getAttribute('data-mapel');
            if ((fk && k !== fk) || (fm && m !== fm)) { continue; }
            out.push({
                id: t.getAttribute('data-id'), nama: t.getAttribute('data-nama') || '', kelas: k, mapel: m,
                avatar: t.getAttribute('data-avatar') || '',
                progress: parseFloat(t.getAttribute('data-progress')) || 0,
                benar: t.getAttribute('data-benar'), salah: t.getAttribute('data-salah'),
                skor: parseFloat(t.getAttribute('data-skor')) || 0,
                status: t.getAttribute('data-status'),
                selesai: parseInt(t.getAttribute('data-selesai'), 10) || 0,
                hash: t.getAttribute('data-hash')
            });
        }
        return out;
    }

    function syncSelect(sel, orig, labelSemua) {
        if (!sel || !orig) { return; }
        var html = '';
        for (var i = 0; i < orig.options.length; i++) {
            var o = orig.options[i], txt = i === 0 ? labelSemua : o.text;
            html += '<option value="' + o.value.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;') + '">' +
                    txt.replace(/&/g, '&amp;').replace(/</g, '&lt;') + '</option>';
        }
        if (sel._src !== html) { sel.innerHTML = html; sel._src = html; }
        if (sel.value !== orig.value) { sel.value = orig.value || ''; }
    }

    /* ---------- Baris ---------- */
    function buatBaris() {
        var el = document.createElement('div');
        el.className = 'pj-row';
        el.innerHTML =
            '<div class="pj-rank"><span></span></div>' +
            '<div class="pj-name"><img alt=""><span class="pj-nm"></span></div>' +
            '<div class="pj-kelas"></div><div class="pj-mapel"></div>' +
            '<div class="pj-prog"><div class="pj-bar"><i></i></div><b></b></div>' +
            '<div class="pj-time"></div><div class="pj-num ok"></div><div class="pj-num bad"></div><div class="pj-skor"></div>';
        el._r = {
            rank: el.querySelector('.pj-rank span'), img: el.querySelector('img'), nm: el.querySelector('.pj-nm'),
            kelas: el.querySelector('.pj-kelas'), mapel: el.querySelector('.pj-mapel'),
            bar: el.querySelector('.pj-bar i'), pct: el.querySelector('.pj-prog b'), time: el.querySelector('.pj-time'),
            ok: el.querySelector('.pj-num.ok'), bad: el.querySelector('.pj-num.bad'), skor: el.querySelector('.pj-skor')
        };
        return el;
    }

    function isiBaris(el, r, no, baru) {
        var c = el._r;
        el.classList.remove('is-active', 'is-done', 'rank-1', 'rank-2', 'rank-3');
        el.classList.add(r.status === '1' ? 'is-active' : 'is-done');
        if (r.skor > 0 && no <= 3) { el.classList.add('rank-' + no); }
        setText(c.rank, no);
        setText(c.nm, r.nama); c.nm.title = r.nama;
        if (c.img.getAttribute('src') !== r.avatar) { c.img.setAttribute('src', r.avatar); }
        setText(c.kelas, r.kelas); setText(c.mapel, r.mapel);
        setText(c.pct, Math.round(r.progress) + '%');
        setText(c.ok, r.benar); setText(c.bad, r.salah); setText(c.skor, r.skor);
        c.skor.className = 'pj-skor ' + (r.skor >= 75 ? 'hi' : (r.skor >= 50 ? 'mid' : 'lo'));
        el.setAttribute('data-status', r.status);
        el.setAttribute('data-selesai', r.selesai);
        var w = Math.max(0, Math.min(100, r.progress)) + '%';
        if (baru && !reduce) {
            c.bar.style.width = '0%';
            requestAnimationFrame(function () { requestAnimationFrame(function () { c.bar.style.width = w; }); });
        } else { c.bar.style.width = w; }
    }

    /* ---------- Render (diff berdasarkan id siswa, tanpa membangun ulang semua) ---------- */
    function render() {
        if (!st.active) { return; }
        syncSelect(byId('pj-kelas'), byId('filter-kelas'), 'Semua kelas');
        syncSelect(byId('pj-mapel'), byId('filter-mapel'), 'Semua mapel');

        var rows = ambilBaris(), existing = {}, next = {}, seen = {}, i;
        for (i = 0; i < list.children.length; i++) { existing[list.children[i].getAttribute('data-id')] = list.children[i]; }

        var aktif = 0, selesai = 0, total = 0, maxS = -1, maxN = '';
        for (i = 0; i < rows.length; i++) {
            var r = rows[i], no = i + 1, el = existing[r.id], baru = !el, prev = st.prev[r.id];
            seen[r.id] = true;
            if (baru) {
                el = buatBaris(); el.setAttribute('data-id', r.id);
                if (!reduce) {
                    el.classList.add('pj-enter');
                    el.style.animationDelay = st.first ? (Math.min(i, 14) * 45) + 'ms' : '0ms';
                }
            }
            if (baru || !prev || prev.hash !== r.hash || prev.no !== no) { isiBaris(el, r, no, baru); }
            if (!baru && !st.first && !reduce && prev && prev.hash !== r.hash) {
                el.classList.remove('pj-flash'); void el.offsetWidth; el.classList.add('pj-flash');
            }
            var cur = list.children[i];
            if (cur !== el) { list.insertBefore(el, cur || null); }
            next[r.id] = { hash: r.hash, no: no };

            if (r.status === '1') { aktif++; } else { selesai++; }
            total += r.skor;
            if (r.skor > maxS) { maxS = r.skor; maxN = r.nama; }
        }
        for (var id in existing) { if (!seen[id] && existing[id].parentNode) { list.removeChild(existing[id]); } }
        st.prev = next; st.first = false;

        list.style.display = rows.length ? 'flex' : 'none';
        empty.style.display = rows.length ? 'none' : 'flex';

        setText(byId('pj-st-aktif'), aktif);
        setText(byId('pj-st-selesai'), selesai);
        setText(byId('pj-st-avg'), rows.length ? (Math.round(total / rows.length * 10) / 10).toFixed(1) : '0.0');
        setText(byId('pj-st-top'), rows.length ? (Math.round(maxS * 10) / 10) : '0');
        setText(byId('pj-st-topname'), rows.length ? 'Tertinggi: ' + maxN : 'Skor tertinggi');
        tick();
    }

    /* ---------- Jam & hitung mundur (tiap detik) ---------- */
    function tick() {
        if (!st.active) { return; }
        var d = new Date(), now = Math.floor(d.getTime() / 1000);
        setText(byId('pj-clock'), jam(d));
        var tgl = d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        if (tgl !== st.tgl) { st.tgl = tgl; setText(byId('pj-date'), tgl); }

        for (var i = 0; i < list.children.length; i++) {
            var el = list.children[i], t = el._r.time, txt, cls = 'pj-time';
            var status = el.getAttribute('data-status'), end = parseInt(el.getAttribute('data-selesai'), 10) || 0;
            if (status === 'selesai') { txt = 'Selesai'; cls += ' end'; }
            else if (!end) { txt = '--:--'; }
            else {
                var s = end - now;
                if (s > 0) {
                    var h = Math.floor(s / 3600), m = Math.floor((s % 3600) / 60), dt = s % 60;
                    txt = (h > 0 ? pad(h) + ':' : '') + pad(m) + ':' + pad(dt);
                    if (s <= 60) { cls += ' crit'; } else if (s <= 300) { cls += ' warn'; }
                } else { txt = 'Waktu habis'; cls += ' over'; }
            }
            setText(t, txt);
            if (t.className !== cls) { t.className = cls; }
        }
        setText(byId('pj-foot-sync'), 'Diperbarui otomatis tiap 4 detik. Terakhir: ' + jam(new Date(st.lastSync)));
    }

    /* ---------- Gulir otomatis kalau peserta lebih banyak dari layar ---------- */
    function loop(ts) {
        if (!st.active) { return; }
        var max = list.scrollHeight - list.clientHeight;
        if (max > 4) {
            if (Math.abs(list.scrollTop - st.pos) > 2) { st.pos = list.scrollTop; st.pauseUntil = ts + 6000; } // pengguna menggulir manual
            if (ts >= st.pauseUntil && st.lastTs) {
                var dt = Math.min((ts - st.lastTs) / 1000, .1);
                if (st.dir === 1) {
                    st.pos += 42 * dt;
                    if (st.pos >= max) { st.pos = max; st.dir = -1; st.pauseUntil = ts + 3000; }
                } else {
                    st.pos -= 320 * dt;
                    if (st.pos <= 0) { st.pos = 0; st.dir = 1; st.pauseUntil = ts + 3000; }
                }
                list.scrollTop = st.pos;
            }
        } else if (list.scrollTop !== 0) { list.scrollTop = 0; st.pos = 0; }
        st.lastTs = ts;
        st.raf = requestAnimationFrame(loop);
    }

    /* ---------- Masuk / keluar mode ---------- */
    function fsElement() { return document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement; }

    function masuk() {
        if (st.active) { return; }
        st.active = true; st.first = true; st.prev = {}; st.dir = 1; st.pos = 0; st.lastTs = 0;
        st.pauseUntil = performance.now() + 3000;
        list.textContent = '';
        wrap.classList.add('mode-proyektor-aktif');
        document.body.style.overflow = 'hidden';
        setText(byId('text-btn-proyektor'), 'Keluar Mode');
        var f = wrap.requestFullscreen || wrap.webkitRequestFullscreen || wrap.mozRequestFullScreen || wrap.msRequestFullscreen;
        if (f) { try { var p = f.call(wrap); if (p && p.catch) { p.catch(function () {}); } } catch (e) {} }
        render();
        list.scrollTop = 0;
        if (!reduce) { st.raf = requestAnimationFrame(loop); }
    }

    function keluar() {
        if (!st.active) { return; }
        st.active = false;
        cancelAnimationFrame(st.raf);
        wrap.classList.remove('mode-proyektor-aktif');
        document.body.style.overflow = '';
        setText(byId('text-btn-proyektor'), 'Mode Proyektor');
        if (fsElement()) {
            var x = document.exitFullscreen || document.webkitExitFullscreen || document.mozCancelFullScreen || document.msExitFullscreen;
            if (x) { try { var p = x.call(document); if (p && p.catch) { p.catch(function () {}); } } catch (e) {} }
        }
    }

    /* ---------- Event ---------- */
    on(document, 'click', function (e) {
        var b = e.target.closest ? e.target.closest('#btn-trigger-proyektor') : null;
        if (b) { st.active ? keluar() : masuk(); }
    });
    on(byId('pj-exit'), 'click', keluar);
    on(document, 'keydown', function (e) { if (e.key === 'Escape' && st.active) { keluar(); } });
    ['fullscreenchange', 'webkitfullscreenchange', 'mozfullscreenchange', 'MSFullscreenChange'].forEach(function (ev) {
        on(document, ev, function () { if (st.active && !fsElement()) { keluar(); } });
    });
    [['pj-kelas', 'filter-kelas'], ['pj-mapel', 'filter-mapel']].forEach(function (p) {
        var sel = byId(p[0]);
        on(sel, 'change', function () {
            var orig = byId(p[1]);
            if (orig) { orig.value = sel.value; orig.dispatchEvent(new Event('change', { bubbles: true })); }
            st.first = true; render();
        });
    });
    on(list, 'animationend', function (e) {
        if (e.target.classList && e.target.classList.contains('pj-row')) {
            e.target.classList.remove('pj-flash', 'pj-enter'); e.target.style.animationDelay = '';
        }
    });
    var timer = setInterval(tick, 1000);

    window.PJ = {
        refresh: function () { render(); },
        touch: function () { st.lastSync = Date.now(); },
        destroy: function () {
            clearInterval(timer); cancelAnimationFrame(st.raf);
            listeners.forEach(function (l) { l[0].removeEventListener(l[1], l[2], l[3]); });
            if (st.active) { wrap.classList.remove('mode-proyektor-aktif'); document.body.style.overflow = ''; }
        }
    };
})();
</script>

<script type="text/javascript">
(function () {
    var NAMA_SEKOLAH = 'SD Negeri 14 Sungai Kunyit';
    var BATAS_HIJAU = 80;   // skor >= 80 : hijau
    var BATAS_KUNING = 60;  // skor 60-79 : kuning, di bawahnya merah

    /* ---------- util ---------- */
    function esc(v) {
        return String(v === null || v === undefined ? '' : v)
            .replace(/[\u0000-\u0008\u000B\u000C\u000E-\u001F]/g, '')
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
    var COLS = 'ABCDEFGHI';
    var XMLH = '<' + '?xml version="1.0" encoding="UTF-8" standalone="yes"?>';

    /* ---------- ZIP (tanpa kompresi) + CRC32, supaya tidak perlu library ---------- */
    var CRC_T = (function () {
        var t = [], c, n, k;
        for (n = 0; n < 256; n++) { c = n; for (k = 0; k < 8; k++) { c = (c & 1) ? (0xEDB88320 ^ (c >>> 1)) : (c >>> 1); } t[n] = c >>> 0; }
        return t;
    })();
    function crc32(b) { var c = 0xFFFFFFFF; for (var i = 0; i < b.length; i++) { c = CRC_T[(c ^ b[i]) & 0xFF] ^ (c >>> 8); } return (c ^ 0xFFFFFFFF) >>> 0; }
    function zip(files) {
        var enc = new TextEncoder(), parts = [], central = [], offset = 0;
        var d = new Date(), dosT = (d.getHours() << 11) | (d.getMinutes() << 5) | (d.getSeconds() >> 1);
        var dosD = ((d.getFullYear() - 1980) << 9) | ((d.getMonth() + 1) << 5) | d.getDate();
        files.forEach(function (f) {
            var name = enc.encode(f.name), data = enc.encode(f.data), crc = crc32(data);
            var lh = new DataView(new ArrayBuffer(30));
            lh.setUint32(0, 0x04034b50, true); lh.setUint16(4, 20, true); lh.setUint16(6, 0x0800, true); lh.setUint16(8, 0, true);
            lh.setUint16(10, dosT, true); lh.setUint16(12, dosD, true); lh.setUint32(14, crc, true);
            lh.setUint32(18, data.length, true); lh.setUint32(22, data.length, true); lh.setUint16(26, name.length, true); lh.setUint16(28, 0, true);
            var ch = new DataView(new ArrayBuffer(46));
            ch.setUint32(0, 0x02014b50, true); ch.setUint16(4, 20, true); ch.setUint16(6, 20, true); ch.setUint16(8, 0x0800, true); ch.setUint16(10, 0, true);
            ch.setUint16(12, dosT, true); ch.setUint16(14, dosD, true); ch.setUint32(16, crc, true);
            ch.setUint32(20, data.length, true); ch.setUint32(24, data.length, true); ch.setUint16(28, name.length, true);
            ch.setUint32(42, offset, true);
            parts.push(new Uint8Array(lh.buffer), name, data);
            central.push(new Uint8Array(ch.buffer), name);
            offset += 30 + name.length + data.length;
        });
        var cdSize = 0; central.forEach(function (c) { cdSize += c.length; });
        var end = new DataView(new ArrayBuffer(22));
        end.setUint32(0, 0x06054b50, true); end.setUint16(8, files.length, true); end.setUint16(10, files.length, true);
        end.setUint32(12, cdSize, true); end.setUint32(16, offset, true);
        return new Blob(parts.concat(central, [new Uint8Array(end.buffer)]),
            { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    }

    /* ---------- styles.xml ---------- */
    function font(sz, bold, color) {
        return '<font>' + (bold ? '<b/>' : '') + '<sz val="' + sz + '"/><color rgb="FF' + color + '"/><name val="Calibri"/><family val="2"/></font>';
    }
    function fill(rgb) { return '<fill><patternFill patternType="solid"><fgColor rgb="FF' + rgb + '"/><bgColor indexed="64"/></patternFill></fill>'; }
    function xf(font, fill, border, h, numFmt) {
        return '<xf numFmtId="' + (numFmt || 0) + '" fontId="' + font + '" fillId="' + fill + '" borderId="' + border + '" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"' +
            (numFmt ? ' applyNumberFormat="1"' : '') + '><alignment horizontal="' + h + '" vertical="center"' + (h === 'center' ? ' wrapText="1"' : '') + '/></xf>';
    }
    // indeks style (S) yang dipakai di sheet
    var S = { DEF: 0, JUDUL: 1, SEKOLAH: 2, LABEL: 3, VALUE: 4, TH: 5, C: 6, L: 7, CZ: 8, LZ: 9, HIJAU: 10, KUNING: 11, MERAH: 12, SELESAI: 13, AKTIF: 14, FOOT: 15 };
    function stylesXml() {
        var fonts = [font(11, 0, '000000'), font(18, 1, 'FFFFFF'), font(12, 1, '1E3A8A'), font(11, 1, '1E3A8A'), font(11, 1, 'FFFFFF'),
                     font(11, 1, '166534'), font(11, 1, '854D0E'), font(11, 1, '991B1B'), font(11, 1, '9A3412'), font(9, 0, '64748B')];
        var fills = ['<fill><patternFill patternType="none"/></fill>', '<fill><patternFill patternType="gray125"/></fill>',
                     fill('1E3A8A'), fill('DBEAFE'), fill('EFF6FF'), fill('2563EB'), fill('F1F5F9'), fill('DCFCE7'), fill('FEF9C3'), fill('FEE2E2'), fill('BBF7D0'), fill('FED7AA')];
        var side = function (n) { return '<' + n + ' style="thin"><color rgb="FFCBD5E1"/></' + n + '>'; };
        var borders = '<border><left/><right/><top/><bottom/><diagonal/></border>' +
                      '<border>' + side('left') + side('right') + side('top') + side('bottom') + '<diagonal/></border>';
        var xfs = [
            xf(0, 0, 0, 'left'),            // 0 DEF
            xf(1, 2, 0, 'center'),          // 1 JUDUL
            xf(2, 3, 0, 'center'),          // 2 SEKOLAH
            xf(3, 4, 1, 'left'),            // 3 LABEL
            xf(0, 0, 1, 'left'),            // 4 VALUE
            xf(4, 5, 1, 'center'),          // 5 TH
            xf(0, 0, 1, 'center'),          // 6 C
            xf(0, 0, 1, 'left'),            // 7 L
            xf(0, 6, 1, 'center'),          // 8 CZ
            xf(0, 6, 1, 'left'),            // 9 LZ
            xf(5, 7, 1, 'center', 164),     // 10 HIJAU
            xf(6, 8, 1, 'center', 164),     // 11 KUNING
            xf(7, 9, 1, 'center', 164),     // 12 MERAH
            xf(5, 10, 1, 'center'),         // 13 SELESAI
            xf(8, 11, 1, 'center'),         // 14 AKTIF
            xf(9, 0, 0, 'right')            // 15 FOOT
        ];
        return XMLH + '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' +
            '<numFmts count="1"><numFmt numFmtId="164" formatCode="0.0"/></numFmts>' +
            '<fonts count="' + fonts.length + '">' + fonts.join('') + '</fonts>' +
            '<fills count="' + fills.length + '">' + fills.join('') + '</fills>' +
            '<borders count="2">' + borders + '</borders>' +
            '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>' +
            '<cellXfs count="' + xfs.length + '">' + xfs.join('') + '</cellXfs>' +
            '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles></styleSheet>';
    }

    /* ---------- sheet1.xml ---------- */
    function cellStr(ref, text, s) { return '<c r="' + ref + '" s="' + s + '" t="inlineStr"><is><t xml:space="preserve">' + esc(text) + '</t></is></c>'; }
    function cellNum(ref, n, s) { return '<c r="' + ref + '" s="' + s + '"><v>' + n + '</v></c>'; }
    function cellEmpty(ref, s) { return '<c r="' + ref + '" s="' + s + '"/>'; }

    function sheetXml(rows, meta) {
        var x = [], merges = [], r = 1;
        function barisGabung(text, s, ht) { // satu baris A..I digabung
            var c = cellStr('A' + r, text, s);
            for (var i = 1; i < 9; i++) { c += cellEmpty(COLS[i] + r, s); }
            x.push('<row r="' + r + '"' + (ht ? ' ht="' + ht + '" customHeight="1"' : '') + '>' + c + '</row>');
            merges.push('A' + r + ':I' + r); r++;
        }
        function barisInfo(label, value) { // label A:B, nilai C:I
            var c = cellStr('A' + r, label, S.LABEL) + cellEmpty('B' + r, S.LABEL) + cellStr('C' + r, value, S.VALUE);
            for (var i = 3; i < 9; i++) { c += cellEmpty(COLS[i] + r, S.VALUE); }
            x.push('<row r="' + r + '" ht="20" customHeight="1">' + c + '</row>');
            merges.push('A' + r + ':B' + r, 'C' + r + ':I' + r); r++;
        }
        function kosong() { x.push('<row r="' + r + '" ht="8" customHeight="1"/>'); r++; }
        function fmt1(n) { return (Math.round(n * 10) / 10).toString().replace('.', ','); }

        var total = rows.length, jmlSelesai = rows.filter(function (q) { return q.selesai; }).length;
        var skors = rows.map(function (q) { return q.skor; });
        var rata = skors.reduce(function (a, b) { return a + b; }, 0) / total;

        barisGabung('REKAP NILAI UJIAN', S.JUDUL, 36);
        barisGabung(NAMA_SEKOLAH, S.SEKOLAH, 24);
        kosong();
        barisInfo('Kelas', meta.kelas);
        barisInfo('Mapel', meta.mapel);
        barisInfo('Tanggal Cetak', meta.tanggal);
        barisInfo('Jumlah Siswa', total + ' siswa (' + jmlSelesai + ' selesai, ' + (total - jmlSelesai) + ' masih mengerjakan)');
        barisInfo('Rata-rata / Tertinggi / Terendah', fmt1(rata) + ' / ' + fmt1(Math.max.apply(null, skors)) + ' / ' + fmt1(Math.min.apply(null, skors)));
        kosong();

        var th = ['NO', 'NAMA', 'KELAS', 'MAPEL', 'BENAR', 'SALAH', 'SCORE', 'PROGRESS', 'STATUS'];
        x.push('<row r="' + r + '" ht="26" customHeight="1">' + th.map(function (t, i) { return cellStr(COLS[i] + r, t, S.TH); }).join('') + '</row>'); r++;

        rows.forEach(function (q, i) {
            var z = (i % 2 === 1), c = z ? S.CZ : S.C, l = z ? S.LZ : S.L;
            var warna = q.skor >= BATAS_HIJAU ? S.HIJAU : (q.skor >= BATAS_KUNING ? S.KUNING : S.MERAH);
            x.push('<row r="' + r + '" ht="20" customHeight="1">' +
                cellNum('A' + r, i + 1, c) + cellStr('B' + r, q.nama, l) + cellStr('C' + r, q.kelas, c) + cellStr('D' + r, q.mapel, l) +
                cellNum('E' + r, q.benar, c) + cellNum('F' + r, q.salah, c) + cellNum('G' + r, q.skor, warna) +
                cellStr('H' + r, q.progress, c) + cellStr('I' + r, q.selesai ? 'Selesai' : 'Mengerjakan', q.selesai ? S.SELESAI : S.AKTIF) +
                '</row>'); r++;
        });
        x.push('<row r="' + r + '">' + cellStr('A' + r, 'Dicetak dari ' + NAMA_SEKOLAH + ' - Sistem Ujian CBT', S.FOOT) + '</row>');
        merges.push('A' + r + ':I' + r);

        var widths = [6, 32, 11, 14, 10, 10, 11, 12, 16];
        return XMLH + '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' +
            '<sheetPr><pageSetUpPr fitToPage="1"/></sheetPr>' +
            '<sheetViews><sheetView showGridLines="0" workbookViewId="0"/></sheetViews>' +
            '<sheetFormatPr defaultRowHeight="15"/>' +
            '<cols>' + widths.map(function (w, i) { return '<col min="' + (i + 1) + '" max="' + (i + 1) + '" width="' + w + '" customWidth="1"/>'; }).join('') + '</cols>' +
            '<sheetData>' + x.join('') + '</sheetData>' +
            '<mergeCells count="' + merges.length + '">' + merges.map(function (m) { return '<mergeCell ref="' + m + '"/>'; }).join('') + '</mergeCells>' +
            '<pageMargins left="0.5" right="0.5" top="0.6" bottom="0.6" header="0.3" footer="0.3"/>' +
            '<pageSetup paperSize="9" orientation="landscape" fitToWidth="1" fitToHeight="0"/>' +
            '</worksheet>';
    }

    function buatXlsx(rows, meta) {
        var NS = 'http://schemas.openxmlformats.org/';
        return zip([
            { name: '[Content_Types].xml', data: XMLH + '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' +
                '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>' +
                '<Default Extension="xml" ContentType="application/xml"/>' +
                '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>' +
                '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>' +
                '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/></Types>' },
            { name: '_rels/.rels', data: XMLH + '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' +
                '<Relationship Id="rId1" Type="' + NS + 'officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>' },
            { name: 'xl/workbook.xml', data: XMLH + '<workbook xmlns="' + NS + 'spreadsheetml/2006/main" xmlns:r="' + NS + 'officeDocument/2006/relationships">' +
                '<sheets><sheet name="Rekap Nilai" sheetId="1" r:id="rId1"/></sheets></workbook>' },
            { name: 'xl/_rels/workbook.xml.rels', data: XMLH + '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' +
                '<Relationship Id="rId1" Type="' + NS + 'officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>' +
                '<Relationship Id="rId2" Type="' + NS + 'officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>' },
            { name: 'xl/styles.xml', data: stylesXml() },
            { name: 'xl/worksheets/sheet1.xml', data: sheetXml(rows, meta) }
        ]);
    }
    window.buatXlsxNilai = buatXlsx;

    /* ---------- aksi tombol ---------- */
    function eksporNilai() {
        var rows = [];
        document.querySelectorAll('#body-tabel-live tr[data-id]').forEach(function (tr) {
            if (tr.style.display === 'none') return; // ikuti filter Kelas & Mapel
            var d = tr.dataset;
            rows.push({
                nama: d.nama, kelas: d.kelas, mapel: d.mapel,
                benar: parseInt(d.benar, 10) || 0, salah: parseInt(d.salah, 10) || 0,
                skor: Math.round((parseFloat(String(d.skor).replace(',', '.')) || 0) * 10) / 10,
                progress: (d.progress || '0') + '%',
                selesai: d.status !== '1'
            });
        });
        if (!rows.length) { alert('Tidak ada data nilai untuk diexport.'); return; }

        var kelas = document.getElementById('filter-kelas').value || 'Semua Kelas';
        var mapel = document.getElementById('filter-mapel').value || 'Semua Mapel';
        var tgl = new Date().toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        var blob = buatXlsx(rows, { kelas: kelas, mapel: mapel, tanggal: tgl });

        var t = new Date(), p = function (n) { return n < 10 ? '0' + n : n; };
        var nama = ('Nilai_' + kelas + '_' + mapel).replace(/[^\w\-]+/g, '_') + '_' +
                   t.getFullYear() + p(t.getMonth() + 1) + p(t.getDate()) + '.xlsx';
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = nama;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        setTimeout(function () { URL.revokeObjectURL(a.href); }, 1000);
    }
    document.addEventListener('click', function (e) {
        if (e.target.closest && e.target.closest('#btn-export-nilai')) { eksporNilai(); }
    });
})();
</script>

<script type="text/javascript">
/* =========================================================================
   AKTIF / NONAKTIF LOGIN SISWA PER KELAS
   ========================================================================= */
(function () {
    // Perbarui lencana status sesuai pilihan kelas (untuk "Semua Kelas": aktif / nonaktif / campuran)
    function updatePill() {
        var sel = document.getElementById('login-kelas'), pill = document.getElementById('login-status-pill');
        if (!sel || !pill) { return; }
        var on = 0, off = 0, i, o;
        if (sel.value === 'semua') {
            for (i = 0; i < sel.options.length; i++) {
                o = sel.options[i];
                if (o.value === 'semua') { continue; }
                if (o.getAttribute('data-aktif') === '1') { on++; } else { off++; }
            }
        } else {
            if (sel.options[sel.selectedIndex].getAttribute('data-aktif') === '1') { on = 1; } else { off = 1; }
        }
        var kelas = (on && off) ? 'pill-mix' : (off ? 'pill-off' : 'pill-on');
        pill.className = 'pill-login ' + kelas;
        pill.textContent = (on && off) ? 'Campuran' : (off ? 'Nonaktif' : 'Aktif');
    }

    function kirimStatusLogin(status) {
        var sel = document.getElementById('login-kelas');
        if (!sel) { return; }
        var terpilih = sel.options[sel.selectedIndex];
        var nama = terpilih.getAttribute('data-nama');

        if (!confirm((status ? 'AKTIFKAN' : 'NONAKTIFKAN') + ' login siswa: ' + nama + ' ?')) { return; }

        var tokenEl = document.getElementById('csrf-live-token');
        var data = { kelas: sel.value, status: status };
        if (tokenEl) { data[tokenEl.name] = tokenEl.value; }

        $.ajax({ url: '<?= site_url("auth/set_login_kelas") ?>', type: 'POST', data: data, dataType: 'json' })
        .done(function (res) {
            if (res && res.csrf && tokenEl) { tokenEl.value = res.csrf; }
            if (!res || !res.ok) { alert((res && res.msg) || 'Gagal mengubah status login.'); return; }

            // perbarui label di dropdown tanpa muat ulang halaman
            for (var i = 0; i < sel.options.length; i++) {
                var o = sel.options[i];
                if (o.value === 'semua') { continue; }
                if (sel.value === 'semua' || o === terpilih) {
                    o.setAttribute('data-aktif', status);
                }
            }
            updatePill();
            alert('Login siswa ' + nama + ' sekarang ' + (status ? 'AKTIF' : 'NONAKTIF') + '.');
        })
        .fail(function () { alert('Gagal menghubungi server. Coba muat ulang halaman.'); });
    }

    document.addEventListener('change', function (e) {
        if (e.target && e.target.id === 'login-kelas') { updatePill(); }
    });
    updatePill();

    document.addEventListener('click', function (e) {
        if (!e.target.closest) { return; }
        if (e.target.closest('#btn-login-on'))  { kirimStatusLogin(1); }
        if (e.target.closest('#btn-login-off')) { kirimStatusLogin(0); }
    });
})();
</script>

<script type="text/javascript">
/* =========================================================================
   IZINKAN KELUAR (sakelar): tekan sekali = beri izin, tekan lagi = cabut izin.
   Status tombol diambil dari server sehingga selalu sesuai walau tabel dimuat ulang.
   ========================================================================= */
(function () {
    if (window.__izinKeluarTerpasang) { return; }
    window.__izinKeluarTerpasang = true;

    var URL_SET = '<?= site_url("izinkeluar/set") ?>';
    var URL_DAFTAR = '<?= site_url("izinkeluar/daftar") ?>';

    function tampil(b, izin) {
        var baru = izin ? '1' : '0';
        // Jangan menyentuh DOM bila tampilannya sudah sesuai (supaya klik tidak hilang)
        if (b.getAttribute('data-izin') === baru && b.getAttribute('data-sinkron') === '1') { return; }
        b.setAttribute('data-sinkron', '1');
        b.setAttribute('data-izin', baru);
        if (izin) {
            b.style.backgroundColor = '#16a34a';
            b.style.borderColor = '#15803d';
            b.innerHTML = '<i class="fa fa-check"></i>';
            b.title = 'Sudah diizinkan. Klik untuk membatalkan izin.';
        } else {
            b.style.backgroundColor = '#f59e0b';
            b.style.borderColor = '#d97706';
            b.innerHTML = '<i class="fa fa-sign-out"></i>';
            b.title = 'Izinkan siswa keluar dari aplikasi ujian';
        }
    }

    // Ambil status terbaru semua tombol yang sedang tampil
    function sinkron() {
        var tombol = document.querySelectorAll('.btn-izin-keluar');
        if (!tombol.length) { return; }
        var ids = [];
        for (var i = 0; i < tombol.length; i++) { ids.push(tombol[i].getAttribute('data-siswa')); }
        // fetch (bukan jQuery ajax) agar tidak memicu ajaxSuccess di dashboard yang menyusun ulang tabel
        fetch(URL_DAFTAR + '?ids=' + encodeURIComponent(ids.join(',')), { credentials: 'same-origin', cache: 'no-store' })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (!res || !res.ok) { return; }
            for (var j = 0; j < tombol.length; j++) {
                var b = tombol[j];
                if (b.__sibuk || !b.isConnected) { continue; }
                var id = b.getAttribute('data-siswa');
                tampil(b, res.data && res.data[id] == 1);
            }
        })
        .catch(function () {});
    }

    document.addEventListener('click', function (e) {
        var b = e.target.closest ? e.target.closest('.btn-izin-keluar') : null;
        if (!b || b.__sibuk) { return; }

        var nama = b.getAttribute('data-nama') || 'siswa ini';
        var izinBaru = b.getAttribute('data-izin') === '1' ? 0 : 1;
        var tanya = izinBaru === 1
            ? 'Izinkan ' + nama + ' keluar dari aplikasi ujian?'
            : 'Batalkan izin keluar untuk ' + nama + '?';
        if (!confirm(tanya)) { return; }

        var tokenEl = document.getElementById('csrf-live-token');
        var data = { id_siswa: b.getAttribute('data-siswa'), izin: izinBaru };
        if (tokenEl) { data[tokenEl.name] = tokenEl.value; }

        b.__sibuk = true;
        $.ajax({ url: URL_SET, type: 'POST', data: data, dataType: 'json' })
        .done(function (res) {
            if (res && res.csrf && tokenEl) { tokenEl.value = res.csrf; }
            if (!res || !res.ok) { alert((res && res.msg) || 'Gagal mengubah izin keluar.'); return; }
            tampil(b, res.izin == 1);
        })
        .fail(function () { alert('Gagal menghubungi server. Coba muat ulang halaman.'); })
        .always(function () { b.__sibuk = false; });
    });

    // Tabel dimuat ulang otomatis: sinkronkan lagi begitu tombol baru muncul
    var tunda = null;
    new MutationObserver(function (daftar) {
        for (var i = 0; i < daftar.length; i++) {
            var tambah = daftar[i].addedNodes;
            for (var j = 0; j < tambah.length; j++) {
                var n = tambah[j];
                if (n.nodeType === 1 && ((n.matches && n.matches('.btn-izin-keluar')) || (n.querySelector && n.querySelector('.btn-izin-keluar')))) {
                    clearTimeout(tunda);
                    tunda = setTimeout(sinkron, 150);
                    return;
                }
            }
        }
    }).observe(document.body, { childList: true, subtree: true });

    sinkron();
    setInterval(sinkron, 5000);
})();
</script>

<script type="text/javascript">
/* =========================================================================
   HAPUS DATA UJIAN per Kelas + Mapel yang dipilih di filter
   Menghapus durasi, log, jawaban, dan nilai siswa pada baris yang tampil.
   ========================================================================= */
(function () {
    if (window.__hapusUjianTerpasang) { return; }
    window.__hapusUjianTerpasang = true;

    document.addEventListener('click', function (e) {
        var btn = e.target.closest ? e.target.closest('#btn-hapus-ujian') : null;
        if (!btn || btn.disabled) { return; }

        var selK = document.getElementById('filter-kelas');
        var selM = document.getElementById('filter-mapel');
        var kelas = selK ? selK.value : '';
        var mapel = selM ? selM.value : '';
        if (kelas === '' || mapel === '') {
            alert('Pilih Kelas dan Mapel terlebih dahulu.');
            return;
        }

        var baris = [], pairs = [];
        var semua = document.querySelectorAll('#body-tabel-live tr');
        for (var i = 0; i < semua.length; i++) {
            var tr = semua[i];
            if (tr.getAttribute('data-kelas') === kelas && tr.getAttribute('data-mapel') === mapel) {
                var key = tr.getAttribute('data-id');
                if (key) { baris.push(tr); pairs.push(key); }
            }
        }
        if (!pairs.length) {
            alert('Tidak ada data siswa untuk ' + kelas + ' - ' + mapel + '.');
            return;
        }

        var pesan = 'HAPUS data ujian ' + pairs.length + ' siswa untuk ' + kelas + ' - ' + mapel + '?\n\n'
                  + 'Yang dihapus: durasi, log, jawaban, dan nilai. Data TIDAK bisa dikembalikan.';
        if (!confirm(pesan)) { return; }

        var tokenEl = document.getElementById('csrf-live-token');
        var data = { pairs: pairs };
        if (tokenEl) { data[tokenEl.name] = tokenEl.value; }

        btn.disabled = true;
        $.ajax({ url: '<?= site_url("hapusujian/hapus") ?>', type: 'POST', data: data, dataType: 'json' })
        .done(function (res) {
            if (res && res.csrf && tokenEl) { tokenEl.value = res.csrf; }
            if (!res || !res.ok) { alert((res && res.msg) || 'Gagal menghapus data ujian.'); return; }
            for (var j = 0; j < baris.length; j++) {
                if (baris[j].parentNode) { baris[j].parentNode.removeChild(baris[j]); }
            }
        })
        .fail(function (xhr) { alert(pesanGagal(xhr)); })
        .always(function () { btn.disabled = false; });
    });
})();
</script>

<script type="text/javascript">
/* =========================================================================
   RESET UJIAN PER SISWA (tombol tempat sampah di kolom AKSI)
   Menghapus jawaban, log, dan nilai siswa pada satu baris, lalu mengakhiri ujiannya (paksa selesai).
   Baris siswa tetap tampil di dashboard supaya admin bisa menekan tombol izin keluar.
   ========================================================================= */
(function () {
    if (window.__hapusSiswaTerpasang) { return; }
    window.__hapusSiswaTerpasang = true;

    document.addEventListener('click', function (e) {
        var b = e.target.closest ? e.target.closest('.btn-hapus-siswa') : null;
        if (!b || b.disabled) { return; }

        var tr = b.closest('tr');
        var nama = b.getAttribute('data-nama') || 'siswa ini';
        var key = b.getAttribute('data-key');
        if (!key) { return; }

        var sedang = tr && tr.getAttribute('data-status') === '1';
        var pesan = 'RESET dan AKHIRI ujian ' + nama + '?\n\n'
                  + 'Jawaban, log, dan nilai dihapus (tidak bisa dikembalikan), lalu ujian diakhiri (paksa selesai). '
                  + 'Siswa tetap tampil di dashboard sehingga Anda bisa menekan tombol izin keluar.';
        if (sedang) {
            pesan += '\n\nPERHATIAN: siswa ini sedang mengerjakan ujian. Ujiannya akan hilang di tengah jalan.';
        }
        if (!confirm(pesan)) { return; }

        var tokenEl = document.getElementById('csrf-live-token');
        var data = { pairs: [key], beri_izin: 0, reset_waktu: 1 };
        if (tokenEl) { data[tokenEl.name] = tokenEl.value; }

        b.disabled = true;
        $.ajax({ url: '<?= site_url("hapusujian/hapus") ?>', type: 'POST', data: data, dataType: 'json' })
        .done(function (res) {
            if (res && res.csrf && tokenEl) { tokenEl.value = res.csrf; }
            b.disabled = false;
            if (!res || !res.ok) { alert((res && res.msg) || 'Gagal mereset ujian.'); return; }
            // Baris tidak dihapus: tabel diperbarui otomatis dalam beberapa detik.
        })
        .fail(function (xhr) { alert(pesanGagal(xhr)); b.disabled = false; });
    });
})();
</script>

<script type="text/javascript">
/* Pesan galat yang menjelaskan penyebab (kode HTTP dan isi balasan server) */
window.pesanGagal = function (xhr) {
    var isi = String((xhr && xhr.responseText) || '').replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
    if (isi.length > 300) { isi = isi.substring(0, 300) + '...'; }
    var kode = xhr && xhr.status ? xhr.status : 0;
    if (kode === 0) { return 'Gagal menghubungi server (tidak ada balasan). Pastikan alamat halaman sama dengan alamat server, lalu muat ulang.'; }
    return 'Gagal (kode ' + kode + '): ' + (isi || 'balasan kosong') + '\n\nMuat ulang halaman (Ctrl+F5) lalu coba lagi.';
};
</script>