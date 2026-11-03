<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// FILE SEMENTARA UNTUK DIAGNOSA LOGIN - HAPUS SETELAH SELESAI
class Tes extends CI_Controller {

    public function index() {
        $this->load->database();
        $this->load->library('ion_auth');

        $o = array();
        $o[] = 'ENVIRONMENT       = ' . ENVIRONMENT;
        $o[] = 'Database aplikasi = ' . $this->db->database . ' @ ' . $this->db->hostname;

        $u = $this->db->get_where('users', array('username' => 'admin'))->row();
        if (!$u) {
            $o[] = 'User admin TIDAK DITEMUKAN';
        } else {
            $o[] = 'id / active       = ' . $u->id . ' / ' . $u->active;
            $o[] = 'panjang hash      = ' . strlen($u->password);
            $o[] = 'password_verify   = ' . (password_verify('admin123', $u->password) ? 'COCOK' : 'TIDAK COCOK');
            $grup = array();
            foreach ($this->ion_auth->get_users_groups($u->id)->result() as $g) {
                $grup[] = $g->name;
            }
            $o[] = 'grup user admin   = ' . (count($grup) ? implode(', ', $grup) : '(KOSONG)');
        }

        $ok = $this->ion_auth->login('admin', 'admin123');
        $o[] = 'ion_auth->login   = ' . ($ok ? 'BERHASIL' : 'GAGAL');
        if (!$ok) {
            $o[] = 'pesan error       = ' . strip_tags($this->ion_auth->errors());
        }
        $o[] = 'logged_in         = ' . ($this->ion_auth->logged_in() ? 'ya' : 'TIDAK');
        $o[] = 'is_admin          = ' . ($this->ion_auth->is_admin() ? 'ya' : 'TIDAK');
        $o[] = 'folder session    = ' . sys_get_temp_dir() . ' | bisa ditulis = ' . (is_writable(sys_get_temp_dir()) ? 'ya' : 'TIDAK');

        $this->output
             ->set_content_type('text/plain')
             ->set_output(implode("\n", $o));
    }

    // Halaman uji: tiga form yang mengirim data dengan cara berbeda.
    public function form() {
        $this->load->helper(array('url', 'form'));
        $tn = $this->security->get_csrf_token_name();
        $th = $this->security->get_csrf_hash();
        $csrf = '<input type="hidden" name="' . $tn . '" value="' . $th . '">';
        $isi = '<p>identity: <input name="identity" value="admin"></p>'
             . '<p>password: <input name="password" value="admin123"></p>'
             . '<input type="hidden" name="role_akses" value="admin">';

        echo '<!DOCTYPE html><html><body style="font-family:sans-serif;padding:30px">';

        echo '<h3>A. Cek data POST sampai ke server (alamat dari site_url)</h3>';
        echo form_open('tes/postcek') . $isi . '<button type="submit">Kirim A</button>' . form_close();
        echo '<p style="color:#666">Action: ' . site_url('tes/postcek') . '</p><hr>';

        echo '<h3>B. Cek data POST sampai ke server (alamat /tes/postcek, satu garis miring)</h3>';
        echo '<form method="post" action="/tes/postcek">' . $csrf . $isi . '<button type="submit">Kirim B</button></form><hr>';

        echo '<h3>C. Kirim ke login asli (alamat /auth/cek_login, satu garis miring)</h3>';
        echo '<form method="post" action="/auth/cek_login">' . $csrf . $isi . '<button type="submit">Kirim C</button></form>';

        echo '</body></html>';
    }

    // Menampilkan apa yang benar-benar diterima server dari kiriman form.
    public function postcek() {
        $o = array();
        $o[] = 'METHOD          = ' . $_SERVER['REQUEST_METHOD'];
        $o[] = 'REQUEST_URI     = ' . $_SERVER['REQUEST_URI'];
        $o[] = 'CONTENT_TYPE    = ' . (isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '-');
        $o[] = 'CONTENT_LENGTH  = ' . (isset($_SERVER['CONTENT_LENGTH']) ? $_SERVER['CONTENT_LENGTH'] : '-');
        $o[] = 'jumlah isi POST = ' . count($_POST);
        foreach ($_POST as $k => $v) {
            $o[] = '   ' . $k . ' = ' . ($k === 'password' ? str_repeat('*', strlen($v)) : $v);
        }
        $this->output->set_content_type('text/plain')->set_output(implode("\n", $o));
    }
}