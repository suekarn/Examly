<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usersiswa extends CI_Controller { 

    public function __construct() { 
        parent::__construct();
        if (!$this->ion_auth->logged_in()) { 
            redirect('auth');
        } 
        $this->load->library(['datatables', 'form_validation']);
        $this->load->model('Users_model', 'users');
        $this->load->model('Master_model', 'master');
        $this->load->model('Dashboard_model', 'dashboard');
        $this->form_validation->set_error_delimiters('', '');
    } 

    public function is_has_access() { 
        $user_id = $this->ion_auth->user()->row()->id;
        $group = $this->ion_auth->get_users_groups($user_id)->row()->name;
        if ($group !== 'admin' && $group !== 'guru') {
            show_error("Hanya Administrator yang diberi hak untuk mengakses halaman ini", 403, "Akses Terlarang");
        } 
    } 

    public function output_json($data, $encode = true) { 
        if ($encode) { 
            $data = json_encode($data);
        } 
        $this->output->set_content_type('application/json')->set_output($data);
    } 

    public function data() { 
        $this->is_has_access();
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $this->output_json($this->users->getUserSiswa($tp->id_tp, $smt->id_smt), false);
    } 

    public function index() { 
        $this->is_has_access();
        $user = $this->ion_auth->user()->row();
        
        $data = [
            "user" => $user, 
            "judul" => "User Management", 
            "subjudul" => "Data User Siswa", 
            "profile" => $this->dashboard->getProfileAdmin($user->id), 
            "setting" => $this->dashboard->getSetting()
        ];
        
        $data["tp"] = $this->dashboard->getTahun();
        $data["tp_active"] = $this->dashboard->getTahunActive();
        $data["smt"] = $this->dashboard->getSemester();
        $data["smt_active"] = $this->dashboard->getSemesterActive();
        $data["kelas_list"] = $this->users->getKelas($data["tp_active"]->id_tp, $data["smt_active"]->id_smt);
        
        $this->load->view("_templates/dashboard/_header", $data);
        $this->load->view("users/siswa/data");
        $this->load->view("_templates/dashboard/_footer");
    } 

    public function list() { 
        $page = $this->input->post('page', true);
        $limit = $this->input->post('limit', true);
        $search = $this->input->post('search', true);
        $offset = ($page - 1) * $limit;
        
        $tp = $this->dashboard->getTahunActive();
        $smt = $this->dashboard->getSemesterActive();
        $count_siswa = $this->users->getUserSiswaTotalPage($search);
        $lists = $this->users->getUserSiswaPage($tp->id_tp, $smt->id_smt, $offset, $limit, $search);
        
        $data = [
            "lists" => $lists, 
            "total" => $count_siswa, 
            "pages" => ceil($count_siswa / $limit), 
            "search" => $search, 
            "perpage" => $limit
        ];
        $this->output_json($data);
    } 

    public function change_password() {
        if ($this->input->is_ajax_request()) {
            $id_siswa = $this->input->post('id_siswa');
            $username = $this->input->post('username');
            $password_baru = $this->input->post('password');

            if (empty($id_siswa) || empty($username) || empty($password_baru)) {
                $this->output_json(['status' => false, 'msg' => 'Parameter tidak lengkap.']);
                return;
            }

            $result = $this->users->updatePasswordSiswa($id_siswa, $username, $password_baru);
            if ($result) {
                $this->output_json(['status' => true, 'msg' => 'Password siswa berhasil diubah!']);
            } else {
                $this->output_json(['status' => false, 'msg' => 'Gagal mengubah database password siswa.']);
            }
        } else {
            exit('No direct script access allowed');
        }
    }

    // Edit data siswa (NIS, nama, username, kelas, password opsional)
    public function update_siswa() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        $this->is_has_access();

        $id_siswa = $this->input->post('id_siswa', true);
        $nis      = trim((string)$this->input->post('nis', true));
        $nama     = trim((string)$this->input->post('nama', true));
        $username = trim((string)$this->input->post('username', true));
        $id_kelas = $this->input->post('id_kelas', true);
        $password = (string)$this->input->post('password');

        if (empty($id_siswa) || $nis === '' || $nama === '' || $username === '') {
            $this->output_json(['status' => false, 'msg' => 'NIS, Nama, dan Username wajib diisi.']);
            return;
        }

        $siswa = $this->db->get_where('master_siswa', ['id_siswa' => $id_siswa])->row();
        if (!$siswa) {
            $this->output_json(['status' => false, 'msg' => 'Data siswa tidak ditemukan.']);
            return;
        }

        // Username lama (yang dipakai di tabel users & login_attempts)
        $old_username = $this->_get_username($siswa);

        // Cek NIS / username tidak dipakai siswa lain
        $dup = $this->db->where('id_siswa !=', $id_siswa)->where('nis', $nis)->get('master_siswa')->row();
        if ($dup) {
            $this->output_json(['status' => false, 'msg' => 'NIS ' . $nis . ' sudah dipakai siswa lain (' . $dup->nama . ').']);
            return;
        }
        $dup = $this->db->where('id_siswa !=', $id_siswa)->where('username', $username)->get('master_siswa')->row();
        if ($dup) {
            $this->output_json(['status' => false, 'msg' => 'Username ' . $username . ' sudah dipakai siswa lain (' . $dup->nama . ').']);
            return;
        }

        // Akun login siswa ini (jika sudah diaktifkan)
        $user = $old_username ? $this->db->get_where('users', ['username' => $old_username])->row() : null;

        // Username baru tidak boleh bentrok dengan akun lain di tabel users
        if ($username !== $old_username) {
            $q = $this->db->where('username', $username);
            if ($user) $q->where('id !=', $user->id);
            if ($q->get('users')->row()) {
                $this->output_json(['status' => false, 'msg' => 'Username ' . $username . ' sudah dipakai akun lain.']);
                return;
            }
        }

        $old_debug = $this->db->db_debug;
        $this->db->db_debug = false;
        $this->db->trans_begin();

        // 1. master_siswa
        $data = ['nis' => $nis, 'nama' => $nama, 'username' => $username];
        if ($password !== '') {
            $data['password'] = $password;
        }
        $this->db->where('id_siswa', $id_siswa)->update('master_siswa', $data);

        // 2. akun login (users)
        if ($user) {
            $u = ['username' => $username, 'first_name' => $nama];
            if ($password !== '') {
                $u['password'] = password_hash($password, PASSWORD_BCRYPT);
            }
            // ikut ubah email bawaan (username@siswa.com) bila username berubah
            if ($username !== $old_username && $user->email === $old_username . '@siswa.com') {
                $u['email'] = $username . '@siswa.com';
            }
            $this->db->where('id', $user->id)->update('users', $u);
        }

        // 3. riwayat percobaan login ikut username baru
        if ($old_username && $username !== $old_username) {
            $this->db->where('login', $old_username)->update('login_attempts', ['login' => $username]);
        }

        // 4. kelas (tahun pelajaran & semester aktif)
        if (!empty($id_kelas)) {
            $tp  = $this->dashboard->getTahunActive();
            $smt = $this->dashboard->getSemesterActive();
            $where = ['id_siswa' => $id_siswa, 'id_tp' => $tp->id_tp, 'id_smt' => $smt->id_smt];
            $ada = $this->db->get_where('kelas_siswa', $where)->row();
            if ($ada) {
                $this->db->where($where)->update('kelas_siswa', ['id_kelas' => $id_kelas]);
            } else {
                $this->db->insert('kelas_siswa', $where + ['id_kelas' => $id_kelas]);
            }
        }

        if ($this->db->trans_status() === false) {
            $err = $this->db->error();
            $this->db->trans_rollback();
            $this->db->db_debug = $old_debug;
            $this->output_json([
                'status' => false,
                'msg'    => 'Gagal menyimpan perubahan. ' . (isset($err['message']) ? $err['message'] : '')
            ]);
            return;
        }

        $this->db->trans_commit();
        $this->db->db_debug = $old_debug;
        $this->output_json(['status' => true, 'msg' => 'Data siswa ' . $nama . ' berhasil diperbarui.']);
    }

    // Upload / ganti foto siswa (dipakai di halaman Cetak > Kartu Peserta)
    public function upload_foto() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        $this->is_has_access();

        $id_siswa = $this->input->post('id_siswa', true);
        $siswa = $id_siswa ? $this->db->get_where('master_siswa', ['id_siswa' => $id_siswa])->row() : null;
        if (!$siswa) {
            $this->output_json(['status' => false, 'msg' => 'Data siswa tidak ditemukan.']);
            return;
        }
        if (empty($_FILES['foto']['name'])) {
            $this->output_json(['status' => false, 'msg' => 'Pilih file foto terlebih dahulu.']);
            return;
        }

        $dir = FCPATH . 'uploads/foto_siswa/';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $this->load->library('upload');
        $this->upload->initialize([
            'upload_path'   => $dir,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size'      => 2048, // KB
            'encrypt_name'  => true,
        ]);
        if (!$this->upload->do_upload('foto')) {
            $this->output_json(['status' => false, 'msg' => strip_tags($this->upload->display_errors('', ''))]);
            return;
        }
        $up = $this->upload->data();

        // Perkecil supaya ringan (maksimal 600 px)
        if ($up['image_width'] > 600 || $up['image_height'] > 600) {
            $this->load->library('image_lib');
            $this->image_lib->initialize([
                'image_library'  => 'gd2',
                'source_image'   => $up['full_path'],
                'maintain_ratio' => true,
                'width'          => 600,
                'height'         => 600,
            ]);
            $this->image_lib->resize();
            $this->image_lib->clear();
        }

        // Hapus foto lama bila ada di folder upload dan tidak dipakai siswa lain
        // (avatar bawaan aplikasi tidak disentuh)
        if (!empty($siswa->foto) && preg_match('#^uploads/(foto_siswa|profiles)/#', $siswa->foto)) {
            $dipakai_lain = $this->db->where('foto', $siswa->foto)->where('id_siswa !=', $id_siswa)->count_all_results('master_siswa');
            $old = FCPATH . $siswa->foto;
            if ($dipakai_lain == 0 && is_file($old)) {
                @unlink($old);
            }
        }

        $path = 'uploads/foto_siswa/' . $up['file_name'];
        $this->db->where('id_siswa', $id_siswa)->update('master_siswa', ['foto' => $path]);

        $this->output_json([
            'status' => true,
            'msg'    => 'Foto ' . $siswa->nama . ' berhasil diperbarui.',
            'foto'   => $path
        ]);
    }

    // Pesan error terakhir dari proses aktivasi (untuk ditampilkan ke user)
    private $_last_error = '';

    // Mengaktifkan 1 siswa (dipanggil per tombol)
    public function activate($id) {
        $this->is_has_access();
        $this->_last_error = '';

        $siswa = $this->_get_siswa($id);
        if (!$siswa) {
            $this->output_json(['status' => false, 'msg' => 'Data siswa tidak ditemukan.']);
            return;
        }

        $status = $this->_proses_aktifkan($siswa);
        $this->output_json([
            "status" => (bool)$status,
            "msg"    => $status
                ? "Siswa berhasil diaktifkan."
                : "Gagal mengaktifkan siswa. " . $this->_last_error
        ]);
    }

    // URL yang dipanggil oleh tombol "Aktifkan Semua"
    public function aktifkansemua() {
        $this->is_has_access();

        // Proses ratusan siswa (hashing password) bisa lama -> hilangkan batas waktu
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');
        ignore_user_abort(true);

        $all_siswa = $this->db->get('master_siswa')->result();
        if (empty($all_siswa)) {
            $this->output_json(['status' => false, 'msg' => 'Tidak ada data siswa.']);
            return;
        }

        // Ambil sekali saja daftar user yang sudah ada (hindari query per siswa)
        $existing = [];
        foreach ($this->db->select('id, username, active')->get('users')->result() as $u) {
            $existing[strtolower((string)$u->username)] = $u;
        }

        $berhasil = 0;
        $dilewati = 0;
        $gagal    = 0;
        $errors   = [];

        foreach ($all_siswa as $siswa) {
            $this->_last_error = '';
            $username = $this->_get_username($siswa);

            // Sudah ada & sudah aktif -> lewati
            if ($username && isset($existing[strtolower($username)]) && (int)$existing[strtolower($username)]->active === 1) {
                $dilewati++;
                continue;
            }

            if ($this->_proses_aktifkan($siswa)) {
                $berhasil++;
            } else {
                $gagal++;
                if (count($errors) < 3) {
                    $errors[] = ($username ?: ('ID ' . ($siswa->id_siswa ?? '?'))) . ': ' . $this->_last_error;
                }
            }
        }

        $msg = "Berhasil mengaktifkan {$berhasil} siswa";
        if ($dilewati > 0) $msg .= ", {$dilewati} siswa sudah aktif";
        if ($gagal > 0)    $msg .= ", {$gagal} siswa gagal (" . implode(' | ', $errors) . ")";
        $msg .= ".";

        $this->output_json([
            "status" => ($berhasil > 0 || ($gagal === 0 && $dilewati > 0)),
            "msg"    => $msg
        ]);
    }

    // Ambil data siswa dari master_siswa (sumber utama), fallback ke model
    private function _get_siswa($id) {
        $siswa = $this->db->get_where('master_siswa', ['id_siswa' => $id])->row();
        if (!$siswa) {
            $siswa = $this->users->getDataSiswa($id);
            if (is_array($siswa)) $siswa = (object)$siswa;
        }
        return $siswa ?: null;
    }

    // Username harus SAMA dengan yang dipakai daftar & tombol nonaktif (kolom username dulu, baru nis)
    private function _get_username($siswa) {
        foreach (['username', 'nis'] as $f) {
            if (isset($siswa->$f) && trim((string)$siswa->$f) !== '') {
                return trim((string)$siswa->$f);
            }
        }
        return null;
    }

    // Helper internal untuk mengaktifkan/mendaftarkan ke tabel users
    private function _proses_aktifkan($siswa) {
        if (!$siswa) {
            $this->_last_error = 'Data siswa kosong.';
            return false;
        }

        $username = $this->_get_username($siswa);
        if (!$username) {
            $this->_last_error = 'Username/NIS kosong.';
            return false;
        }

        $nama = (isset($siswa->nama) && trim((string)$siswa->nama) !== '') ? $siswa->nama : 'Siswa';

        // Password kosong akan membuat ion_auth gagal (hash_password mengembalikan FALSE)
        $password = (isset($siswa->password) && trim((string)$siswa->password) !== '') ? (string)$siswa->password : $username;
        if (strlen($password) < 1) $password = '123456';

        $user_exists = $this->db->get_where('users', ['username' => $username])->row();

        if ($user_exists) {
            $this->db->where('id', $user_exists->id)->update('users', ['active' => 1]);

            // Pastikan user ada di group siswa (3)
            $in_group = $this->db->get_where('users_groups', ['user_id' => $user_exists->id, 'group_id' => 3])->row();
            if (!$in_group) {
                $this->db->insert('users_groups', ['user_id' => $user_exists->id, 'group_id' => 3]);
            }
            return true;
        }

        $email = $username . '@siswa.com';
        $additional_data = [
            'first_name' => $nama,
            'last_name'  => ''
        ];
        $group = ['3'];

        $register = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
        if (!$register) {
            $this->_last_error = trim(strip_tags($this->ion_auth->errors())) ?: 'Registrasi user ditolak.';
            return false;
        }

        // Pastikan akun benar-benar aktif
        $this->db->where('id', $register)->update('users', ['active' => 1]);
        return true;
    }

    // Menonaktifkan 1 siswa
    public function deactivate($username, $nama) { 
        $user = $this->users->getUsers($username);
        $deleted = false;
        if ($user && isset($user->id)) {
            $deleted = $this->ion_auth->delete_user($user->id);
        }
        $data = [
            "status" => (bool)$deleted, 
            "msg" => $deleted ? "Siswa dinonaktifkan." : "Gagal dinonaktifkan."
        ];
        $this->output_json($data, true);
    }

    // URL yang dipanggil oleh tombol "Nonaktifkan Semua"
    public function nonaktifkansemua() {
        $this->is_has_access();
        @set_time_limit(0);
        ignore_user_abort(true);

        // Ambil semua akun siswa (group 3) di tabel users
        $users_siswa = $this->db->select('u.id')
            ->from('users u')
            ->join('users_groups ug', 'ug.user_id = u.id')
            ->where('ug.group_id', 3)
            ->get()->result();

        $count = 0;
        foreach ($users_siswa as $u) {
            if ($this->ion_auth->delete_user($u->id)) {
                $count++;
            }
        }

        $this->output_json([
            "status" => true,
            "msg"    => "Berhasil menonaktifkan {$count} akun siswa."
        ]);
    }

    // Hapus siswa (data master + akun login + relasi kelas). Hanya admin.
    public function hapus() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        $this->is_has_access();

        $user_id = $this->ion_auth->user()->row()->id;
        $group = $this->ion_auth->get_users_groups($user_id)->row()->name;
        if ($group !== 'admin') {
            $this->output_json(['status' => false, 'msg' => 'Hanya Administrator yang boleh menghapus siswa.']);
            return;
        }

        $id_siswa = $this->input->post('id_siswa', true);
        if (empty($id_siswa)) {
            $this->output_json(['status' => false, 'msg' => 'Parameter tidak lengkap.']);
            return;
        }

        $siswa = $this->db->get_where('master_siswa', ['id_siswa' => $id_siswa])->row();
        if (!$siswa) {
            $this->output_json(['status' => false, 'msg' => 'Data siswa tidak ditemukan.']);
            return;
        }

        $username = $this->_get_username($siswa);
        $nama = isset($siswa->nama) ? $siswa->nama : 'Siswa';

        // Matikan tampilan error DB supaya kegagalan (mis. relasi data) bisa dikirim sebagai pesan JSON
        $old_debug = $this->db->db_debug;
        $this->db->db_debug = false;

        $this->db->trans_begin();

        // 1. Akun login (ion_auth ikut membersihkan users_groups)
        if ($username) {
            $user = $this->db->get_where('users', ['username' => $username])->row();
            if ($user) {
                $this->ion_auth->delete_user($user->id);
            }
            $this->db->delete('login_attempts', ['login' => $username]);
        }

        // 2. Relasi kelas siswa (jika tabelnya ada)
        if ($this->db->table_exists('kelas_siswa') && $this->db->field_exists('id_siswa', 'kelas_siswa')) {
            $this->db->delete('kelas_siswa', ['id_siswa' => $id_siswa]);
        }

        // 3. Data master siswa
        $this->db->delete('master_siswa', ['id_siswa' => $id_siswa]);

        if ($this->db->trans_status() === false) {
            $err = $this->db->error();
            $this->db->trans_rollback();
            $this->db->db_debug = $old_debug;
            $this->output_json([
                'status' => false,
                'msg'    => 'Gagal menghapus ' . $nama . '. Data siswa masih terhubung dengan data lain. ' . (isset($err['message']) ? $err['message'] : '')
            ]);
            return;
        }

        $this->db->trans_commit();
        $this->db->db_debug = $old_debug;

        $this->output_json(['status' => true, 'msg' => 'Siswa ' . $nama . ' berhasil dihapus.']);
    }

    public function reset_login($username, $nama) { 
        $this->db->where('login', $username);
        if ($this->db->delete("login_attempts")) {
            $data = ["status" => true, "msg" => "User " . $nama . " berhasil direset"];
        } else { 
            $data = ["status" => false, "msg" => "User " . $nama . " gagal direset"];
        } 
        $this->output_json($data, true);
    } 
}