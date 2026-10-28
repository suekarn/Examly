<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    // true = log login user dihapus otomatis saat user itu logout
    private $hapus_log_saat_logout = true;

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library(['ion_auth', 'form_validation']);
        $this->load->helper(['url', 'language']);
        $this->load->model("Settings_model", "settings");
    }

    // =========================================================
    // TEMPLATE DATA
    // =========================================================
    private function _get_template_data($title)
    {
        $data['setting'] = $this->settings->getSetting();
        $data['title']   = $title;

        return $data;
    }

    // =========================================================
    // DEFAULT LOGIN (LANGSUNG KE KUSUS/UTAMA)
    // =========================================================
    public function index()
    {
        // Menampilkan view login gabungan yang sudah diset default Admin
        $data = $this->_get_template_data("Login - System");
        $this->load->view('auth/login', $data);
    }

    // =========================================================
    // LOGIN SISWA
    // =========================================================
    public function siswa()
    {
        // Jika sudah login
        if ($this->ion_auth->logged_in()) {

            // Jika group siswa
            if ($this->ion_auth->in_group('siswa')) {
                redirect('siswa/dashboard');
            } else {

                // Jika bukan siswa logout
                $this->ion_auth->logout();
                $this->session->sess_destroy();

                redirect('login');
            }
        }

        $data = $this->_get_template_data("Login Siswa - GarudaCBT");

        $jadwal_format_baru = array();

        $tabel_jadwal = 'cbt_jadwal';

        // Deteksi tabel bank soal
        $tabel_bank = '';

        if ($this->db->table_exists('cbt_bank_soal')) {
            $tabel_bank = 'cbt_bank_soal';
        } elseif ($this->db->table_exists('cbt_banksoal')) {
            $tabel_bank = 'cbt_banksoal';
        }

        // Jika tabel jadwal ada
        if ($this->db->table_exists($tabel_jadwal)) {

            $hari_ini = date('Y-m-d');

            $this->db->select($tabel_jadwal . '.*');

            if (!empty($tabel_bank)) {

                $this->db->select($tabel_bank . '.*');

                $this->db->from($tabel_jadwal);

                $this->db->join(
                    $tabel_bank,
                    $tabel_jadwal . '.id_bank = ' . $tabel_bank . '.id_bank',
                    'left'
                );
            } else {

                $this->db->from($tabel_jadwal);
            }

            // Jadwal hari ini
            $this->db->where($tabel_jadwal . '.tgl_mulai', $hari_ini);

            $semua_jadwal = $this->db->get()->result();

            foreach ($semua_jadwal as $j) {

                $id_ujian = $j->id_jadwal;

                $nama_tampilan = '';

                // bank_kode
                if (isset($j->bank_kode) && !empty(trim($j->bank_kode))) {

                    $nama_tampilan = $j->bank_kode;
                }

                // kode_banksoal
                elseif (isset($j->kode_banksoal) && !empty(trim($j->kode_banksoal))) {

                    $nama_tampilan = $j->kode_banksoal;
                }

                // nama_mapel
                elseif (isset($j->nama_mapel) && !empty(trim($j->nama_mapel))) {

                    $nama_tampilan = $j->nama_mapel;
                }

                // Default nama
                if (empty($nama_tampilan)) {

                    $nama_tampilan = "Ujian Sesi " . $id_ujian;
                } else {

                    $nama_tampilan = $nama_tampilan . " (Sesi " . $id_ujian . ")";
                }

                $jadwal_format_baru[] = (object) array(
                    'id_ujian'   => $id_ujian,
                    'nama_ujian' => '',
                    'nama_mapel' => trim($nama_tampilan)
                );
            }
        }

        $data['jadwal'] = $jadwal_format_baru;

        $this->load->view('auth/login', $data);
    }

    // =========================================================
    // LOGIN ADMIN & GURU (REDIRECT KE ROUTE BARU)
    // =========================================================
    public function admin()
    {
        if ($this->ion_auth->logged_in()) {

            if (!$this->ion_auth->in_group('siswa')) {

                redirect('dashboard');
            } else {

                $this->ion_auth->logout();

                redirect('login');
            }
        }

        // Jika tidak login, alihkan langsung ke login utama
        redirect('login');
    }

    // =========================================================
    // CATAT PERCOBAAN LOGIN (IP + PERANGKAT) -> tabel log_perangkat
    // Tidak pernah menggagalkan login: error database dimatikan sementara
    // =========================================================
    private function _catat_login($identity, $status, $keterangan = '')
    {
        try {
            if (!$this->db->table_exists('log_perangkat')) {
                return;
            }

            $this->load->library('user_agent');

            $ua = (string) $this->input->user_agent();

            $db_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;

            $this->db->insert('log_perangkat', array(
                'identity'   => substr((string) $identity, 0, 100),
                'status'     => $status,
                'keterangan' => substr(strip_tags((string) $keterangan), 0, 255),
                'ip_address' => $this->input->ip_address(),
                'perangkat'  => $this->_nama_perangkat($ua),
                'platform'   => substr((string) $this->agent->platform(), 0, 50),
                'browser'    => substr(trim($this->agent->browser() . ' ' . $this->agent->version()), 0, 80),
                'user_agent' => substr($ua, 0, 255),
            ));

            $this->db->db_debug = $db_debug;
        } catch (Exception $e) {
            // abaikan, login tetap berjalan
        }
    }

    // Menebak nama perangkat dari user agent
    // (browser modern sering menyembunyikan model HP, jadi hasilnya tidak selalu lengkap)
    private function _nama_perangkat($ua)
    {
        if (preg_match('/Android [\d.]+;\s*([^;)]+)/i', $ua, $m)) {
            $model = trim(preg_replace('/\s+Build\/.*$/i', '', trim($m[1])));
            if ($model !== '' && strtoupper($model) !== 'K') {
                return substr('Android - ' . $model, 0, 100);
            }
            return 'Android';
        }
        if (stripos($ua, 'iPhone') !== false) return 'iPhone';
        if (stripos($ua, 'iPad') !== false) return 'iPad';
        if (stripos($ua, 'Windows') !== false) return 'Windows PC';
        if (stripos($ua, 'Macintosh') !== false) return 'Mac';
        if (stripos($ua, 'CrOS') !== false) return 'Chromebook';
        if (stripos($ua, 'Linux') !== false) return 'Linux';

        return 'Tidak diketahui';
    }

    // Hapus log milik user yang sedang logout, agar daftar hanya berisi yang masih relevan.
    // Matikan dengan mengubah $hapus_log_saat_logout menjadi false.
    private function _hapus_log_saat_logout()
    {
        if (!$this->hapus_log_saat_logout) {
            return;
        }

        try {
            $user = $this->ion_auth->logged_in() ? $this->ion_auth->user()->row() : null;

            if ($user && !empty($user->username) && $this->db->table_exists('log_perangkat')) {
                $db_debug = $this->db->db_debug;
                $this->db->db_debug = FALSE;

                $this->db->where('identity', $user->username)->delete('log_perangkat');

                $this->db->db_debug = $db_debug;
            }
        } catch (Exception $e) {
            // abaikan, logout tetap berjalan
        }
    }

    // =========================================================
    // PROSES LOGIN SISWA
    // =========================================================
    public function proses_siswa()
    {
        $identity  = $this->input->post('identity', TRUE);
        $password  = $this->input->post('password', TRUE);
        $remember  = (bool)$this->input->post('remember');
        $id_jadwal = $this->input->post('id_jadwal', TRUE);

        if ($this->ion_auth->login($identity, $password, $remember)) {

            if ($this->ion_auth->in_group('siswa')) {

                $this->load->model("Log_model", "logging");

                $this->logging->saveLog(1, "Login Siswa");

                $this->_catat_login($identity, 'berhasil');

                $this->session->set_userdata('id_jadwal_aktif', $id_jadwal);

                $output = array(
                    'status' => TRUE,
                    'url'    => 'siswa/dashboard'
                );
            } else {

                $this->ion_auth->logout();

                $this->_catat_login($identity, 'gagal', 'Bukan akun siswa');

                $output = array(
                    'status' => FALSE,
                    'failed' => 'Akun Anda bukan akun Siswa!'
                );
            }
        } else {

            $pesan = $this->ion_auth->errors()
                ? $this->ion_auth->errors()
                : 'Username atau Password salah!';

            $this->_catat_login($identity, 'gagal', $pesan);

            $output = array(
                'status' => FALSE,
                'failed' => $pesan
            );
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    // =========================================================
    // PROSES LOGIN ADMIN
    // =========================================================
    public function proses_admin()
    {
        $identity = $this->input->post('identity', TRUE);
        $password = $this->input->post('password', TRUE);
        $remember = (bool)$this->input->post('remember');

        if ($this->ion_auth->login($identity, $password, $remember)) {

            if ($this->ion_auth->in_group('siswa')) {

                $this->ion_auth->logout();

                $output = array(
                    'status' => FALSE,
                    'failed' => 'Akses Ditolak!'
                );
            } else {

                $this->load->model("Log_model", "logging");

                $this->logging->saveLog(1, "Login Admin/Guru");

                $output = array(
                    'status' => TRUE,
                    'url'    => 'dashboard'
                );
            }
        } else {

            $output = array(
                'status' => FALSE,
                'failed' => $this->ion_auth->errors()
                    ? $this->ion_auth->errors()
                    : 'Username atau Password salah!'
            );
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($output));
    }

    // =========================================================
    // LOGOUT SISWA
    // =========================================================
    public function logout_siswa()
    {
        $this->_hapus_log_saat_logout();
        $this->ion_auth->logout();

        $this->session->sess_destroy();

        redirect('login');
    }

    // =========================================================
    // LOGOUT ADMIN (DIARAHKAN KE LOGIN UTAMA)
    // =========================================================
    public function logout_admin()
    {
        $this->_hapus_log_saat_logout();
        $this->ion_auth->logout();

        $this->session->sess_destroy();

        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Pengalihan bersih ke halaman login utama
        header("Location: " . base_url('login'));
        exit;
    }
}