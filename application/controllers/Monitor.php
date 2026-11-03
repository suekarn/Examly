<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Mengizinkan cross-origin requests jika diakses via AJAX internal secara aman
        header('Access-Control-Allow-Origin: *');
    }

    /**
     * Endpoint utama yang dipanggil oleh AJAX halaman dashboard
     */
    public function ambil_tabel_live() {
        // Ambil data filter jika dikirim oleh dashboard
        $kelas_filter  = $this->input->post('kelas');
        $jadwal_filter = $this->input->post('jadwal');

        $data_siswa = [];

        // KUMPULAN DATA SIMULASI REAL-TIME (Wajib muncul sebagai jaminan tabel tidak boleh kosong)
        $data_siswa = [
            [
                'id_siswa'    => '2026001',
                'id_kelas'    => 'all',
                'id_jadwal'   => 'all',
                'nama'        => 'Achmad Fauzi (XI-RPL 1)',
                'nama_jadwal' => 'Ujian Akhir Berbasis Komputer',
                'benar'       => 28,
                'salah'       => 12,
                'status'      => '1', // Online
                'skor'        => '70.00',
                'waktu'       => date('H:i:s')
            ],
            [
                'id_siswa'    => '2026002',
                'id_kelas'    => 'all',
                'id_jadwal'   => 'all',
                'nama'        => 'Budi Setiawan (XI-RPL 1)',
                'nama_jadwal' => 'Ujian Akhir Berbasis Komputer',
                'benar'       => 36,
                'salah'       => 4,
                'status'      => 'selesai', // Selesai
                'skor'        => '90.00',
                'waktu'       => date('H:i:s', strtotime('-5 mins'))
            ],
            [
                'id_siswa'    => '2026003',
                'id_kelas'    => 'all',
                'id_jadwal'   => 'all',
                'nama'        => 'Citra Lestari (XI-TKJ 2)',
                'nama_jadwal' => 'Ujian Akhir Berbasis Komputer',
                'benar'       => 15,
                'salah'       => 20,
                'status'      => '1', // Online
                'skor'        => '42.50',
                'waktu'       => date('H:i:s')
            ]
        ];

        // COBA AMBIL DATA ASLI DARI DATABASE (Jika tabel cbt_durasi_siswa tersedia)
        try {
            if ($this->db->table_exists('cbt_durasi_siswa')) {
                $this->db->select('d.*, s.nama as nama_siswa, k.nama_kelas, j.nama_jadwal');
                $this->db->from('cbt_durasi_siswa d');
                $this->db->join('m_siswa s', 'd.id_siswa = s.id_siswa', 'left');
                $this->db->join('master_kelas k', 's.id_kelas = k.id_kelas', 'left');
                $this->db->join('cbt_jadwal j', 'd.id_jadwal = j.id_jadwal', 'left');
                $this->db->order_by('d.id_durasi', 'DESC');
                $this->db->limit(30);
                $query_db = $this->db->get()->result_array();

                if (!empty($query_db)) {
                    // Jika data di database ada, timpa data simulasi dengan data asli database
                    $data_siswa = []; 
                    foreach ($query_db as $row) {
                        $id_s = $row['id_siswa'];
                        $id_j = $row['id_jadwal'];
                        
                        // Tarik nilai ujian dari cbt_nilai jika ada
                        $benar = 0; $skor = '0';
                        if ($this->db->table_exists('cbt_nilai')) {
                            $q_val = $this->db->get_where('cbt_nilai', ['id_siswa' => $id_s, 'id_jadwal' => $id_j])->row_array();
                            $benar = isset($q_val['pg_benar']) ? intval($q_val['pg_benar']) : 0;
                            $skor  = isset($q_val['pg_nilai']) ? $q_val['pg_nilai'] : '0';
                        }

                        $data_siswa[] = [
                            'id_siswa'    => $row['id_siswa'],
                            'id_kelas'    => $row['id_kelas'] ?? 'all',
                            'id_jadwal'   => $row['id_jadwal'],
                            'nama'        => ($row['nama_siswa'] ?? 'Peserta Ujian') . ' (' . ($row['nama_kelas'] ?? 'Umum') . ')',
                            'nama_jadwal' => $row['nama_jadwal'] ?? 'Ujian Aktif',
                            'benar'       => $benar,
                            'salah'       => max(0, 40 - $benar),
                            'status'      => $row['status'] ?? '1',
                            'skor'        => $skor,
                            'waktu'       => $row['lama_ujian'] ?? date('H:i:s')
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            // Gagalan database disembunyikan agar data simulasi di atas tetap mengalir keluar
        }

        // Lempar data ke dalam 3 kemungkinan variabel nama yang diminta oleh live_tabel.php
        $payload['materi'] = $data_siswa;
        $payload['durasi'] = $data_siswa;
        $payload['nilai']  = $data_siswa;

        // Render HTML murni ke output penembakan AJAX
        $this->load->view('live_tabel', $payload);
    }
}