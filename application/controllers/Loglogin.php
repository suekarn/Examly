<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Halaman Log Login Siswa (IP + MAC + perangkat)
 * Akses: http://<server>/loglogin  (khusus Administrator)
 *
 * Hemat sumber daya:
 * - Auto-refresh hanya mengirim 1 query ringan (/loglogin/cek) tiap 5 detik,
 *   berhenti saat tab tidak dilihat, dan baru memuat ulang tabel jika ada perubahan.
 */
class Loglogin extends CI_Controller
{
    // Log yang lebih tua dari sekian jam dihapus otomatis (0 = tidak pernah dihapus otomatis)
    private $simpan_jam = 24;

    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in()) {
            redirect('auth');
        }

        if (!$this->ion_auth->is_admin()) {
            show_error('Hanya Administrator yang berhak mengakses halaman ini', 403, 'Akses Terlarang');
        }

        // Permintaan cek ringan tidak perlu memuat model & cek tabel
        if ($this->router->fetch_method() !== 'cek') {
            $this->load->model('Dashboard_model', 'dashboard');
            $this->_pastikan_tabel();
        }
    }

    // Buat tabel otomatis jika belum ada + migrasi kolom untuk versi lama
    private function _pastikan_tabel()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS log_perangkat (
              id INT AUTO_INCREMENT PRIMARY KEY,
              identity VARCHAR(100),
              status VARCHAR(10),
              keterangan VARCHAR(255),
              ip_address VARCHAR(45),
              perangkat VARCHAR(100),
              mac_address VARCHAR(20),
              platform VARCHAR(50),
              browser VARCHAR(80),
              user_agent VARCHAR(255),
              waktu DATETIME DEFAULT CURRENT_TIMESTAMP,
              INDEX (identity),
              INDEX (ip_address),
              INDEX (waktu)
            )
        ");

        if (!$this->db->field_exists('perangkat', 'log_perangkat')) {
            $this->db->query("ALTER TABLE log_perangkat ADD COLUMN perangkat VARCHAR(100) AFTER ip_address");
        }
        if (!$this->db->field_exists('mac_address', 'log_perangkat')) {
            $this->db->query("ALTER TABLE log_perangkat ADD COLUMN mac_address VARCHAR(20) AFTER perangkat");
        }
    }

    // Hapus log yang lebih tua dari $simpan_jam jam (query berindeks, sangat ringan)
    private function _bersihkan_log_lama()
    {
        if ($this->simpan_jam <= 0) {
            return;
        }
        $this->db->query('DELETE FROM log_perangkat WHERE waktu < (NOW() - INTERVAL ' . (int) $this->simpan_jam . ' HOUR)');
    }

    // "Tanda tangan" isi tabel: berubah jika ada baris baru atau baris dihapus
    private function _sig()
    {
        $db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        $q = $this->db->query("SELECT COUNT(*) AS n, COALESCE(MAX(id), 0) AS m FROM log_perangkat");

        $this->db->db_debug = $db_debug;

        if (!$q) {
            return '0:0';
        }
        $r = $q->row();
        return $r->n . ':' . $r->m;
    }

    // Dipanggil JavaScript tiap beberapa detik. Sangat ringan: hanya 1 query.
    public function cek()
    {
        // Lepas kunci sesi agar tidak menghambat halaman lain
        if (function_exists('session_status') && session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        $this->output
            ->set_content_type('application/json')
            ->set_header('Cache-Control: no-store')
            ->set_output(json_encode(['sig' => $this->_sig()]));
    }

    public function index()
    {
        $this->_bersihkan_log_lama();

        $user = $this->ion_auth->user()->row();
        $tp   = $this->dashboard->getTahunActive();
        $smt  = $this->dashboard->getSemesterActive();

        $data = [
            'user'       => $user,
            'judul'      => 'Log Login Siswa',
            'subjudul'   => 'Riwayat Percobaan Login (IP & Perangkat)',
            'setting'    => $this->dashboard->getSetting(),
            'tp'         => $this->dashboard->getTahun(),
            'tp_active'  => $tp,
            'smt'        => $this->dashboard->getSemester(),
            'smt_active' => $smt,
            'profile'    => $this->dashboard->getProfileAdmin($user->id),
        ];

        $data['sig'] = $this->_sig();

        // Ringkasan (1 query)
        $sum = $this->db->query("
            SELECT COUNT(*) AS total,
                   COALESCE(SUM(status = 'berhasil'), 0) AS berhasil,
                   COALESCE(SUM(status = 'gagal'), 0) AS gagal
            FROM log_perangkat
        ")->row();
        $data['total']    = (int) $sum->total;
        $data['berhasil'] = (int) $sum->berhasil;
        $data['gagal']    = (int) $sum->gagal;

        // Siswa yang gagal terus dan belum pernah berhasil login
        $data['bermasalah'] = $this->db->query("
            SELECT identity, ip_address, perangkat, browser,
                   MAX(mac_address) AS mac_address,
                   COUNT(*) AS jumlah_gagal,
                   MAX(waktu) AS terakhir,
                   MAX(keterangan) AS pesan
            FROM log_perangkat
            WHERE status = 'gagal'
              AND identity NOT IN (SELECT identity FROM log_perangkat WHERE status = 'berhasil')
            GROUP BY identity, ip_address, perangkat, browser
            ORDER BY terakhir DESC
            LIMIT 100
        ")->result();

        // 200 percobaan login terakhir
        $data['logs'] = $this->db->order_by('id', 'DESC')->limit(200)->get('log_perangkat')->result();

        $this->load->view('_templates/dashboard/_header', $data);
        $this->load->view('loglogin/data');
        $this->load->view('_templates/dashboard/_footer');
    }

    // Hapus semua log (dipanggil via POST dari tombol di halaman)
    public function hapus()
    {
        if ($this->input->method() !== 'post') {
            redirect('loglogin');
        }

        $this->db->truncate('log_perangkat');
        redirect('loglogin');
    }
}