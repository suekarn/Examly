<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cbtlive extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cek keamanan sesi guru/admin
        if (!$this->ion_auth->logged_in()) {
            exit(json_encode(['status' => 'expired']));
        }
        // Load helper URL jika belum di-load di autoload
        $this->load->helper('url');
    }

    /**
     * FUNGSI BARU: Endpoint AJAX untuk menyinkronkan data ke live_tabel.php
     * Panggil fungsi ini lewat AJAX di view utama Anda
     */
    public function ambil_tabel_ajax() {
        // Query mengambil data siswa yang sedang ujian secara Live
        // Menghubungkan nilai, durasi, nama siswa, dan kelasnya
        $this->db->select('
            n.id_siswa, 
            CONCAT(s.nama, " (", k.nama_kelas, ")") as nama, 
            n.pg_benar as benar, 
            (n.total_soal - n.pg_benar) as salah, 
            d.status as status, 
            n.pg_nilai as skor, 
            d.lama_ujian as waktu
        ');
        $this->db->from('cbt_nilai n');
        $this->db->join('cbt_durasi_siswa d', 'n.id_siswa = d.id_siswa AND n.id_jadwal = d.id_jadwal', 'left');
        $this->db->join('m_siswa s', 'n.id_siswa = s.id_siswa', 'left'); // <-- Sesuaikan jika nama tabel siswa berbeda
        $this->db->join('m_kelas k', 's.id_kelas = k.id_kelas', 'left'); // <-- Sesuaikan jika nama tabel kelas berbeda
        $this->db->order_by('n.pg_nilai', 'DESC');
        $this->db->limit(100); // Batasi 100 siswa teratas demi performa
        
        $data['materi'] = $this->db->get()->result();

        // Lempar data ke view live_tabel.php milik Anda
        $this->load->view('live_tabel', $data);
    }

    /**
     * Fungsi Logika Hitung Nilai Otomatis bawaan aplikasi Anda (Sudah Diperbaiki)
     */
    public function hitung_rekap_dashboard() {
        // 1. Ambil Kunci Jawaban Resmi Terbaru dari Bank Soal
        $soal_kunci = $this->db->select('id_soal, jawaban')->get('cbt_soal')->result_array();
        $kunci_map = array_column($soal_kunci, 'jawaban', 'id_soal');

        // 2. DETEKSI JADWAL AKTIF: Ambil ID Jadwal unik dari data nilai terbaru
        $this->db->select('id_jadwal');
        $this->db->group_by('id_jadwal');
        $this->db->order_by('id_jadwal', 'DESC');
        $this->db->limit(3); 
        $jadwal_aktif = $this->db->get('cbt_nilai')->result_array();

        if (!empty($jadwal_aktif)) {
            $list_jadwal = array_column($jadwal_aktif, 'id_jadwal');

            // 3. Ambil data siswa yang mengikuti paket ujian aktif tersebut
            $this->db->select('id_siswa, id_jadwal');
            $this->db->where_in('id_jadwal', $list_jadwal); 
            $siswa_live = $this->db->get('cbt_nilai')->result();

            if (!empty($siswa_live)) {
                foreach ($siswa_live as $siswa) {
                    $id_s = $siswa->id_siswa;
                    $id_j = $siswa->id_jadwal;

                    // Ambil lembar jawaban milik siswa ini
                    $jawaban_siswa = $this->db->where(['id_jadwal' => $id_j, 'id_siswa' => $id_s])->get('cbt_soal_siswa')->result();

                    $pg_benar = 0;
                    $total_soal_pg = 0;

                    foreach ($jawaban_siswa as $jwb) {
                        if (isset($kunci_map[$jwb->id_soal])) {
                            $total_soal_pg++;
                            if (strtoupper(trim($jwb->jawaban)) === strtoupper(trim($kunci_map[$jwb->id_soal]))) {
                                $pg_benar++;
                            }
                        }
                    }

                    if ($total_soal_pg > 0) {
                        $pg_nilai = ($pg_benar / $total_soal_pg) * 100;
                        $pg_nilai_format = number_format($pg_nilai, 2, '.', '');

                        // UPDATE TABEL 1: cbt_durasi_siswa
                        $this->db->where(['id_jadwal' => $id_j, 'id_siswa' => $id_s])->update('cbt_durasi_siswa', [
                            'nilai' => $pg_nilai_format
                        ]);

                        // UPDATE TABEL 2: cbt_nilai
                        $this->db->where(['id_jadwal' => $id_j, 'id_siswa' => $id_s])->update('cbt_nilai', [
                            'pg_benar' => $pg_benar,
                            'pg_nilai' => $pg_nilai_format
                        ]);
                    }
                }
            }
        }
        echo json_encode(['status' => 'success', 'message' => 'Synced']);
        exit;
    }
} // Akhir dari Class Controller