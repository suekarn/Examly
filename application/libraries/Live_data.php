<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Live_data
 * Mengambil data monitoring siswa (logika sama persis dengan view live_tabel.php),
 * dipakai oleh halaman publik orang tua (controller Pantau).
 */
class Live_data
{
    public function ambil()
    {
        $CI =& get_instance();
        if (!isset($CI->db)) { $CI->load->database(); }

        $data_siswa = [];
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
                        $nama_mapel_siswa = ''; 

                        if (!empty($tabel_durasi) && $id_s !== '0') {
                            $cek_durasi = $CI->db->query("SELECT * FROM $tabel_durasi WHERE id_siswa = ?", [$id_s])->row_array();

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
                                                $get_m = $CI->db->query("SELECT $kolom_nama_mapel FROM $tabel_mapel WHERE $kolom_id_master_mapel = ?", [$id_mapel_referensi])->row_array();
                                                if (!empty($get_m) && !empty(trim($get_m[$kolom_nama_mapel]))) {
                                                    $nama_mapel_siswa = trim($get_m[$kolom_nama_mapel]);
                                                }
                                            }
                                            if (empty($nama_mapel_siswa) && isset($bank_row['bank_kode']) && !empty(trim($bank_row['bank_kode']))) {
                                                $nama_mapel_siswa = trim($bank_row['bank_kode']);
                                            }
                                        }
                                    }

                                    if (empty($nama_mapel_siswa)) {
                                        $nama_mapel_siswa = "Mapel ID: " . $id_b;
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
                        }

                        if ($status !== '0') {
                            $data_siswa[] = [
                                'id_siswa'       => $id_s,
                                'nama'           => $nama_peserta,
                                'kelas'          => $kelas_siswa,
                                'jenis_kelamin'  => $jk,
                                'nama_mapel'     => $nama_mapel_siswa,
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

                            if (!in_array($nama_mapel_siswa, $list_mapel_aktif)) { $list_mapel_aktif[] = $nama_mapel_siswa; }
                            if (!in_array($kelas_siswa, $list_kelas_aktif)) { $list_kelas_aktif[] = $kelas_siswa; }
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

        return $data_siswa;
    }
}
