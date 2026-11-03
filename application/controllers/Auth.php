<?php
/* ________________________________________
    |                 GarudaCBT              |
    |    https://github.com/garudacbt/cbt    |
    |________________________________________|
*/
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller {

    public $data = array();

    // true = log login user dihapus otomatis saat user itu logout
    private $hapus_log_saat_logout = true;

    // Log yang lebih tua dari sekian jam dihapus otomatis (0 = tidak pernah dihapus otomatis)
    private $simpan_jam = 24;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->helper(['url', 'language']);
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');
    }

    public function output_json($data) {
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function index() {
        $this->load->model('Settings_model', 'settings');
        if (count($this->db->list_tables()) == 0) {
            redirect('install');
        }
        $setting = $this->settings->getSetting();
        if ($setting == null) {
            redirect('install');
        }
        if ($this->ion_auth->logged_in()) {
            $user_id = $this->ion_auth->user()->row()->id;
            $group = $this->ion_auth->get_users_groups($user_id)->row()->name;
            redirect('dashboard');
        }
        $this->data['setting'] = $setting;
        $this->data['identity'] = [
            'name' => 'identity',
            'id' => 'identity',
            'type' => 'text',
            'placeholder' => 'Username',
            'autofocus' => 'autofocus',
            'class' => 'form-control',
            'autocomplete' => 'off'
        ];
        $this->data['password'] = [
            'name' => 'password',
            'id' => 'password',
            'type' => 'password',
            'placeholder' => 'Password',
            'class' => 'form-control'
        ];
        $this->data['message'] = validation_errors() ? validation_errors() : $this->session->flashdata('message');

        $this->load->view('_templates/auth/_header', $this->data);
        $this->load->view('auth/login');
        $this->load->view('_templates/auth/_footer');
    }

    // ==========================================
    // FUNGSI CEK LOGIN (SUDAH DI-RESET & BERSIH)
    // ==========================================
    public function cek_login() {
        $this->form_validation->set_rules('identity', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() === TRUE) {
            $remember = (bool)$this->input->post('remember');
            $identity = $this->input->post('identity');

            // Di komputer server sendiri (localhost) jangan pernah terkunci oleh percobaan login gagal.
            if (in_array($this->input->ip_address(), ['127.0.0.1', '::1'], true)) {
                $this->ion_auth->clear_login_attempts($identity);
            }

            if ($this->ion_auth->login($identity, $this->input->post('password'), $remember)) {
                $this->cek_akses();
            } else {
                // Tampilkan alasan sebenarnya dari Ion Auth (salah password / akun nonaktif / terkunci sementara)
                $alasan = trim(strip_tags($this->ion_auth->errors()));
                $this->_catat_login($identity, 'gagal', $alasan !== '' ? $alasan : 'Incorrect Login');
                $data = [
                    'status' => false,
                    'failed' => $alasan !== '' ? $alasan : 'Incorrect Login',
                    'akses' => 'no attempts'
                ];
                $this->output_json($data);
            }
        } else {
            $invalid = [
                'identity' => form_error('identity'),
                'password' => form_error('password')
            ];
            $data = [
                'status' => false,
                'invalid' => $invalid,
                'akses' => 'no valid'
            ];
            $this->output_json($data);
        }
    }

    public function cek_akses() {
        if ($this->ion_auth->logged_in()) {
            $role_akses = $this->input->post('role_akses');
            $is_admin   = $this->ion_auth->is_admin();
            $is_guru    = $this->ion_auth->in_group("guru");
            $identity   = $this->input->post('identity', TRUE);

            if ($role_akses === 'siswa' && ($is_admin || $is_guru)) {
                $this->ion_auth->logout();
                $this->_catat_login($identity, 'gagal', 'Akun Manajemen/Guru masuk lewat jalur Siswa');
                $this->output_json([
                    "status" => false, 
                    "failed" => "Akun Manajemen/Guru tidak diizinkan masuk melalui jalur Siswa!"
                ]);
                return;
            }

            if ($role_akses === 'admin' && !$is_admin && !$is_guru) {
                $this->ion_auth->logout();
                $this->_catat_login($identity, 'gagal', 'Akun Siswa masuk lewat jalur Admin');
                $this->output_json([
                    "status" => false, 
                    "failed" => "Akun Siswa tidak memiliki hak akses ke Panel Admin!"
                ]);
                return;
            }

            // === CEK STATUS LOGIN KELAS (khusus siswa) ===
            // Jika login kelas siswa dinonaktifkan dari dashboard, siswa ditolak masuk.
            if (!$is_admin && !$is_guru && !$this->login_kelas_diizinkan()) {
                $this->ion_auth->logout();
                $this->_catat_login($identity, 'gagal', 'Login kelas belum dibuka');
                $this->output_json([
                    "status" => false,
                    "failed" => "Login kelas Anda belum dibuka. Tunggu arahan Guru."
                ]);
                return;
            }

            // Siswa login baru: cabut izin keluar lama (tombol Keluar di aplikasi ujian)
            if (!$is_admin && !$is_guru) {
                $this->_reset_izin_keluar();
            }

            $status = true;
            $this->load->model('Log_model', 'logging');
            $this->logging->saveLog(1, "Login");
            $this->_catat_login($identity, 'berhasil', 'Login ' . ($is_admin ? 'admin' : ($is_guru ? 'guru' : 'siswa')));
            $url = "dashboard";
        } else {
            $status = false;
            $url = "auth";
        }

        $data = [
            'status' => $status,
            'url' => $url,
            'role' => $this->ion_auth->is_admin() ? 'admin' : ($this->ion_auth->in_group('guru') ? 'guru' : 'siswa')
        ];
        $this->output_json($data);
    }

    // ==========================================
    // CATAT PERCOBAAN LOGIN (IP + PERANGKAT) -> tabel log_perangkat
    // Tidak pernah menggagalkan login: error database dimatikan sementara
    // ==========================================
    private function _catat_login($identity, $status, $keterangan = '') {
        try {
            if (!$this->db->table_exists('log_perangkat')) {
                return;
            }

            $this->load->library('user_agent');

            $ua = (string) $this->input->user_agent();

            $db_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;

            $ip  = $this->input->ip_address();
            $row = [
                'identity'   => substr((string) $identity, 0, 100),
                'status'     => $status,
                'keterangan' => substr(strip_tags((string) $keterangan), 0, 255),
                'ip_address' => $ip,
                'perangkat'  => $this->_nama_perangkat($ua),
                'platform'   => substr((string) $this->agent->platform(), 0, 50),
                'browser'    => substr(trim($this->agent->browser() . ' ' . $this->agent->version()), 0, 80),
                'user_agent' => substr($ua, 0, 255),
            ];

            // MAC address dibaca dari tabel ARP server (hanya jika kolomnya sudah ada)
            if ($this->db->field_exists('mac_address', 'log_perangkat')) {
                $row['mac_address'] = $this->_cari_mac($ip);
            }

            $this->db->insert('log_perangkat', $row);

            $this->_bersihkan_log_lama();

            $this->db->db_debug = $db_debug;
        } catch (Exception $e) {
            // abaikan, login tetap berjalan
        }
    }

    // Membaca MAC address perangkat di LAN dari tabel ARP server.
    // Hanya berhasil untuk perangkat di jaringan yang sama dan jika exec() diizinkan PHP.
    private function _cari_mac($ip) {
        if (!filter_var($ip, FILTER_VALIDATE_IP) || in_array($ip, ['127.0.0.1', '::1'], true)) {
            return '';
        }
        if (!function_exists('exec')) {
            return '';
        }

        $out = [];
        $cmd = (stripos(PHP_OS, 'WIN') === 0)
            ? 'arp -a ' . escapeshellarg($ip)
            : 'arp -n ' . escapeshellarg($ip);
        @exec($cmd, $out);

        foreach ($out as $line) {
            if (preg_match('/(^|\s)' . preg_quote($ip, '/') . '\s/', $line)
                && preg_match('/([0-9a-f]{2}(?:[-:][0-9a-f]{2}){5})/i', $line, $m)) {
                return strtoupper(str_replace('-', ':', $m[1]));
            }
        }
        return '';
    }

    // Menebak nama perangkat dari user agent
    // (browser modern sering menyembunyikan model HP, jadi hasilnya tidak selalu lengkap)
    private function _nama_perangkat($ua) {
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

    // Hapus log yang lebih tua dari $simpan_jam jam.
    // Dijalankan paling sering sekali per 10 menit (dibatasi lewat file penanda di folder temp),
    // jadi hampir tanpa beban walau banyak siswa login.
    private function _bersihkan_log_lama()
    {
        if ($this->simpan_jam <= 0) {
            return;
        }

        $file = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR . 'cbt_log_purge.txt';
        if (is_file($file) && (time() - filemtime($file)) < 600) {
            return;
        }
        @touch($file);

        $this->db->query('DELETE FROM log_perangkat WHERE waktu < (NOW() - INTERVAL ' . (int) $this->simpan_jam . ' HOUR)');
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

    // Mengembalikan izin keluar siswa yang baru login ke 0
    private function _reset_izin_keluar() {
        try {
            if (!$this->db->table_exists('izin_keluar')) {
                return;
            }
            $user = $this->ion_auth->user()->row();
            if ($user && !empty($user->username)) {
                $db_debug = $this->db->db_debug;
                $this->db->db_debug = FALSE;
                $this->db->where('username', $user->username)->update('izin_keluar', ['izin' => 0]);
                $this->db->db_debug = $db_debug;
            }
        } catch (Exception $e) {
            // abaikan, login tetap berjalan
        }
    }

    public function logout() {
        // Tentukan peran SEBELUM logout (setelah logout datanya hilang)
        $is_staff = $this->ion_auth->logged_in()
            && ($this->ion_auth->is_admin() || $this->ion_auth->in_group('guru'));

        $this->_hapus_log_saat_logout();
        $this->ion_auth->logout();

        if ($is_staff) {
            // Admin/Guru -> halaman login admin
            redirect('login?role=admin', 'refresh');
        } else {
            // Siswa -> halaman login siswa (mode default)
            redirect('login', 'refresh');
        }
    }

    // ==========================================
    // AKTIF / NONAKTIF LOGIN SISWA PER KELAS
    // ==========================================

    // Mengembalikan true jika siswa boleh login (kelasnya berstatus aktif).
    // Bila data siswa/kolom tidak ditemukan, siswa dibiarkan masuk (agar tidak terkunci salah).
    private function login_kelas_diizinkan() {
        if (!$this->db->field_exists('login_aktif', 'master_kelas')) { return true; }

        $user = $this->ion_auth->user()->row();
        if (!$user) { return true; }

        $tabel = '';
        foreach (['master_siswa', 'm_siswa', 'siswa'] as $t) {
            if ($this->db->table_exists($t)) { $tabel = $t; break; }
        }
        if ($tabel === '') { return true; }

        // cocokkan username login dengan kolom identitas siswa
        $siswa = null;
        foreach (['username', 'nis', 'nisn', 'no_peserta'] as $kol) {
            if ($this->db->field_exists($kol, $tabel)) {
                $siswa = $this->db->get_where($tabel, [$kol => $user->username])->row_array();
                if ($siswa) { break; }
            }
        }
        if (!$siswa) { return true; }

        $id_siswa = $siswa['id_siswa'] ?? $siswa['id'] ?? 0;
        $row = $this->db->query(
            "SELECT mk.login_aktif
               FROM kelas_siswa ks
               JOIN master_kelas mk ON mk.id_kelas = ks.id_kelas
              WHERE ks.id_siswa = ?
              ORDER BY ks.id_kelas_siswa DESC
              LIMIT 1", [$id_siswa]
        )->row();

        return !($row && (int)$row->login_aktif === 0);
    }

    // Dipanggil tombol di dashboard admin/guru.
    // POST: kelas = id_kelas atau 'semua', status = 1 (aktif) / 0 (nonaktif)
    public function set_login_kelas() {
        if (!$this->ion_auth->logged_in() || !($this->ion_auth->is_admin() || $this->ion_auth->in_group('guru'))) {
            $this->output_json(['ok' => false, 'msg' => 'Tidak diizinkan']);
            return;
        }
        if (!$this->db->field_exists('login_aktif', 'master_kelas')) {
            $this->output_json(['ok' => false, 'msg' => 'Kolom login_aktif belum dibuat di tabel master_kelas']);
            return;
        }

        $kelas  = $this->input->post('kelas');
        $status = $this->input->post('status') == '1' ? 1 : 0;

        if ($kelas === 'semua') {
            $this->db->update('master_kelas', ['login_aktif' => $status]);
        } else {
            $this->db->where('id_kelas', (int)$kelas)->update('master_kelas', ['login_aktif' => $status]);
        }
        $this->output_json(['ok' => true, 'status' => $status, 'csrf' => $this->security->get_csrf_hash()]);
    }
}