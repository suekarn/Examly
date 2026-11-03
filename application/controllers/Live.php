<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Live extends CI_Controller {

    public function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
    }

    public function ambil_tabel_live() {
        $data_siswa = [];

        try {
            // AMBIL DATA DARI TABEL MASTER SISWA (Pasti Ada Datanya)
            if ($this->db->table_exists('m_siswa')) {
                $this->db->select('s.id_siswa, s.nama as nama_siswa, k.nama_kelas');
                $this->db->from('m_siswa s');
                $this->db->join('master_kelas k', 's.id_kelas = k.id_kelas', 'left');
                $this->db->order_by('s.nama', 'ASC');
                $this->db->limit(100); // Tampilkan 100 siswa pertama
                $query_siswa = $this->db->get()->result_array();

                if (!empty($query_siswa)) {
                    foreach ($query_siswa as $row) {
                        $id_s = $row['id_siswa'];
                        
                        // Default Status Awal (Offline / Belum Mulai)
                        $status = '0'; 
                        $nama_jadwal = 'Tidak Ada Ujian Active';
                        $waktu = '-';
                        $benar = 0;
                        $skor = '0';

                        // Cek apakah siswa ini punya catatan aktivitas di cbt_durasi_siswa
                        if ($this->db->table_exists('cbt_durasi_siswa')) {
                            $this->db->select('d.status, d.lama_ujian, j.nama_jadwal, d.id_jadwal');
                            $this->db->from('cbt_durasi_siswa d');
                            $this->db->join('cbt_jadwal j', 'd.id_jadwal = j.id_jadwal', 'left');
                            $this->db->where('d.id_siswa', $id_s);
                            $cek_durasi = $this->db->get()->row_array();

                            if (!empty($cek_durasi)) {
                                $status = $cek_durasi['status'] ?? '1';
                                $nama_jadwal = $cek_durasi['nama_jadwal'] ?? 'Ujian Aktif';
                                $waktu = $cek_durasi['lama_ujian'] ?? date('H:i:s');
                                $id_j = $cek_durasi['id_jadwal'];

                                // Cek nilai jika ada
                                if ($this->db->table_exists('cbt_nilai')) {
                                    $q_val = $this->db->get_where('cbt_nilai', ['id_siswa' => $id_s, 'id_jadwal' => $id_j])->row_array();
                                    $benar = isset($q_val['pg_benar']) ? intval($q_val['pg_benar']) : 0;
                                    $skor  = isset($q_val['pg_nilai']) ? $q_val['pg_nilai'] : '0';
                                }
                            }
                        }

                        // Susun data ke format objek live_tabel
                        $data_siswa[] = [
                            'id_siswa'    => $row['id_siswa'],
                            'id_kelas'    => 'all',
                            'id_jadwal'   => 'all',
                            'nama'        => $row['nama_siswa'] . ' (' . ($row['nama_kelas'] ?? 'Tanpa Kelas') . ')',
                            'nama_jadwal' => $nama_jadwal,
                            'benar'       => $benar,
                            'salah'       => max(0, 40 - $benar),
                            'status'      => $status, // '0'=Offline/Belum, '1'=Online, 'selesai'=Selesai
                            'skor'        => $skor,
                            'waktu'       => $waktu
                        ];
                    }
                }
            }
        } catch (Exception $e) { }

        // Lempar data siswa asli ke view
        $payload['materi'] = $data_siswa;
        $payload['durasi'] = $data_siswa;
        $payload['nilai']  = $data_siswa;

        $this->load->view('live_tabel', $payload);
    }
}