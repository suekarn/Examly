<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Izinkeluar extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        if (!$this->db->table_exists('izin_keluar')) {
            $this->db->query("CREATE TABLE izin_keluar (
                username VARCHAR(100) NOT NULL PRIMARY KEY,
                izin TINYINT(1) NOT NULL DEFAULT 0,
                pesan VARCHAR(255) NOT NULL DEFAULT ''
            )");
        } elseif (!$this->db->field_exists('pesan', 'izin_keluar')) {
            // Tabel lama: tambahkan kolom pesan untuk pemberitahuan ke siswa
            $this->db->query("ALTER TABLE izin_keluar ADD COLUMN pesan VARCHAR(255) NOT NULL DEFAULT ''");
        }
    }

    private function output_json($data) {
        $this->output
            ->set_header('Cache-Control: no-store')
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    // Menentukan siswa yang dimaksud: dari sesi login, atau (bila sudah logout,
    // misalnya setelah ujian selesai dan kembali ke halaman login) dari parameter u
    // yang dikirim aplikasi (nama pengguna terakhir yang login di aplikasi itu).
    private function _tentukan_username(&$login) {
        $login = 0;
        if ($this->ion_auth->logged_in()) {
            $user = $this->ion_auth->user()->row();
            if ($user && !empty($user->username)) {
                $login = 1;
                return $user->username;
            }
        }
        $u = trim((string) $this->input->get_post('u', TRUE));
        return (strlen($u) > 0 && strlen($u) <= 100) ? $u : '';
    }

    // Apakah ada jadwal ujian yang aktif sekarang?
    // Mengikuti logika dashboard siswa: status jadwal = 1 dan hari ini di antara tgl_mulai dan tgl_selesai.
    // Bila kolom jam_mulai/jam_selesai ada dan terisi, jam sekarang juga harus di dalam rentang itu.
    // Bila struktur tabel tidak dikenali, kembalikan 1 (jangan memblokir siswa karena salah baca).
    private function _ada_jadwal_aktif() {
        try {
            if (!$this->db->table_exists('cbt_jadwal')) { return 1; }
            foreach (['tgl_mulai', 'tgl_selesai', 'status'] as $kol) {
                if (!$this->db->field_exists($kol, 'cbt_jadwal')) { return 1; }
            }
            $ada_jam = $this->db->field_exists('jam_mulai', 'cbt_jadwal') && $this->db->field_exists('jam_selesai', 'cbt_jadwal');
            $rows = $this->db->get('cbt_jadwal')->result_array();

            $hari_ini = strtotime(date('Y-m-d'));
            $sekarang = time();
            foreach ($rows as $r) {
                if ((string) $r['status'] !== '1') { continue; }
                $mulai   = strtotime(date('Y-m-d', strtotime((string) $r['tgl_mulai'])));
                $selesai = strtotime(date('Y-m-d', strtotime((string) $r['tgl_selesai'])));
                if (!$mulai || !$selesai) { continue; }
                if ($hari_ini < $mulai || $hari_ini > $selesai) { continue; }

                if ($ada_jam) {
                    $jm = trim((string) $r['jam_mulai']);
                    $js = trim((string) $r['jam_selesai']);
                    if ($jm !== '' && $js !== '') {
                        $t1 = strtotime(date('Y-m-d') . ' ' . $jm);
                        $t2 = strtotime(date('Y-m-d') . ' ' . $js);
                        if ($t1 && $t2 && $t2 > $t1 && ($sekarang < $t1 || $sekarang > $t2)) { continue; }
                    }
                }
                return 1;
            }
            return 0;
        } catch (Exception $e) {
            return 1;
        }
    }

    // Dipanggil aplikasi Examly sebelum siswa masuk: {"ada":0/1,"pesan":"..."}
    public function jadwal() {
        $ada = $this->_ada_jadwal_aktif();
        $this->output_json(['ada' => $ada, 'pesan' => $ada ? '' : 'Saat ini tidak ada jadwal ujian.']);
    }

    // Dipanggil aplikasi Examly tiap beberapa detik
    // Hasil: {"izin":0/1,"username":"...","login":0/1,"pesan":"..."}
    // "pesan" = pemberitahuan dari admin (mis. data ujian dihapus), dikirim satu kali.
    public function status() {
        $izin = 0;
        $pesan = '';
        $username = $this->_tentukan_username($login);
        if ($username !== '') {
            $row = $this->db->get_where('izin_keluar', ['username' => $username])->row();
            $izin = $row ? (int) $row->izin : 0;
            if ($row && isset($row->pesan) && $row->pesan !== '') {
                // Pemberitahuan dikirim satu kali, lalu dikosongkan
                $pesan = (string) $row->pesan;
                $this->db->where('username', $username)->update('izin_keluar', ['pesan' => '']);
            }
        }
        $this->output_json(['izin' => $izin, 'username' => $username, 'login' => $login, 'pesan' => $pesan, 'jadwal' => $this->_ada_jadwal_aktif()]);
    }

    // Dipanggil aplikasi setelah siswa menekan tombol Keluar (hanya mengembalikan izin ke 0)
    public function reset() {
        $username = $this->_tentukan_username($login);
        if ($username !== '') {
            $this->db->replace('izin_keluar', ['username' => $username, 'izin' => 0, 'pesan' => '']);
        }
        $this->output_json(['ok' => true]);
    }

    // Dipanggil dashboard untuk mengetahui status izin banyak siswa sekaligus
    // GET: ids = id_siswa dipisah koma. Hasil: {"ok":true,"data":{"12":1,"13":0}}
    public function daftar() {
        if (!$this->ion_auth->logged_in() || !($this->ion_auth->is_admin() || $this->ion_auth->in_group('guru'))) {
            $this->output_json(['ok' => false, 'msg' => 'Tidak diizinkan']);
            return;
        }
        $ids = array_filter(array_map('trim', explode(',', (string) $this->input->get('ids', TRUE))), 'strlen');
        $ids = array_slice(array_unique($ids), 0, 300);
        $hasil = [];
        foreach ($ids as $id) {
            $izin = 0;
            $username = $this->_username_dari_siswa($id);
            if ($username !== '') {
                $row = $this->db->get_where('izin_keluar', ['username' => $username])->row();
                $izin = $row ? (int) $row->izin : 0;
            }
            $hasil[$id] = $izin;
        }
        $this->output_json(['ok' => true, 'data' => $hasil]);
    }

    // Mencari username login (tabel users) dari id siswa di master_siswa
    private function _username_dari_siswa($id_siswa) {
        $tabel = '';
        foreach (['master_siswa', 'm_siswa', 'siswa'] as $t) {
            if ($this->db->table_exists($t)) { $tabel = $t; break; }
        }
        if ($tabel === '') { return ''; }

        $kolom_id = $this->db->field_exists('id_siswa', $tabel) ? 'id_siswa' : 'id';
        $row = $this->db->get_where($tabel, [$kolom_id => $id_siswa])->row_array();
        if (!$row) { return ''; }

        $tabel_users = 'users';
        $cfg = $this->config->item('tables', 'ion_auth');
        if (is_array($cfg) && !empty($cfg['users'])) { $tabel_users = $cfg['users']; }

        // Sama seperti Auth.php: username login cocok dengan salah satu kolom identitas siswa
        foreach (['username', 'nis', 'nisn', 'no_peserta'] as $kol) {
            if (!empty($row[$kol])) {
                $u = $this->db->get_where($tabel_users, ['username' => $row[$kol]])->row();
                if ($u) { return $u->username; }
            }
        }
        return '';
    }

    // Dipanggil tombol di dashboard admin/guru
    // POST: id_siswa (atau username), izin = 1 (izinkan) / 0 (cabut)
    public function set() {
        if (!$this->ion_auth->logged_in() || !($this->ion_auth->is_admin() || $this->ion_auth->in_group('guru'))) {
            $this->output_json(['ok' => false, 'msg' => 'Tidak diizinkan']);
            return;
        }
        $izin = $this->input->post('izin') == '1' ? 1 : 0;
        $username = trim((string) $this->input->post('username', TRUE));
        if ($username === '') {
            $id_siswa = trim((string) $this->input->post('id_siswa', TRUE));
            if ($id_siswa !== '') { $username = $this->_username_dari_siswa($id_siswa); }
        }
        if ($username === '') {
            $this->output_json(['ok' => false, 'msg' => 'Akun login siswa tidak ditemukan']);
            return;
        }
        $this->db->replace('izin_keluar', ['username' => $username, 'izin' => $izin]);
        $this->output_json(['ok' => true, 'izin' => $izin, 'csrf' => $this->security->get_csrf_hash()]);
    }
}