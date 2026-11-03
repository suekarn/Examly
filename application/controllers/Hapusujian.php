<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Hapus data ujian (durasi, log, jawaban, nilai) per siswa + jadwal.
 * Dipanggil tombol hapus di dashboard. Hanya untuk admin.
 * POST: pairs[] = "id_siswa_id_jadwal"  (sama dengan data-id baris di tabel dashboard)
 *
 * Hapus per kelas dan mapel: siswa otomatis diberi izin keluar (tabel izin_keluar),
 * supaya tombol Keluar muncul di aplikasi ujian mereka. Hapus per siswa (beri_izin=0):
 * tombol Keluar tidak ditampilkan, siswa hanya mendapat pemberitahuan. Baris siswa sudah hilang dari
 * dashboard, jadi tombol kuning "Izinkan Keluar" tidak ada lagi untuk menekannya manual.
 * Sesi login siswa juga dihapus, sehingga siswa kembali ke halaman login (bukan ke
 * halaman beranda siswa).
 */
class Hapusujian extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
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

        foreach (['username', 'nis', 'nisn', 'no_peserta'] as $kol) {
            if (!empty($row[$kol])) {
                $u = $this->db->get_where($tabel_users, ['username' => $row[$kol]])->row();
                if ($u) { return $u->username; }
            }
        }
        return '';
    }

    // Memutus sesi login siswa (paksa logout) dengan menghapus data sesinya di server.
    // Mendukung penyimpanan sesi 'database' dan 'files' milik CodeIgniter.
    private function _logout_siswa($username) {
        if ($username === '') { return; }

        $driver = $this->config->item('sess_driver');
        $path   = $this->config->item('sess_save_path');
        $len    = strlen($username);
        // Cara PHP menyimpan data sesi: nama|s:panjang:"isi";
        $polaList = [
            'identity|s:' . $len . ':"' . $username . '";',
            'username|s:' . $len . ':"' . $username . '";',
        ];

        try {
            if ($driver === 'database' && $path && $this->db->table_exists($path)) {
                foreach ($polaList as $pola) {
                    $this->db->like('data', $pola)->delete($path);
                }
                return;
            }

            if ($driver === 'files') {
                if (!$path) { $path = ini_get('session.save_path'); }
                if (!$path || !is_dir($path)) { return; }
                $awalan = $this->config->item('sess_cookie_name') ?: 'ci_session';
                $daftar = glob(rtrim($path, '/\\') . DIRECTORY_SEPARATOR . $awalan . '*');
                if (!$daftar) { return; }
                foreach ($daftar as $f) {
                    $isi = @file_get_contents($f);
                    if ($isi === false) { continue; }
                    foreach ($polaList as $pola) {
                        if (strpos($isi, $pola) !== false) { @unlink($f); break; }
                    }
                }
            }
        } catch (Exception $e) {
            // abaikan, penghapusan data ujian tetap berjalan
        }
    }

    private function output_json($data) {
        $this->output
            ->set_header('Cache-Control: no-store')
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function hapus() {
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            $this->output_json(['ok' => false, 'msg' => 'Hanya admin yang boleh menghapus data ujian']);
            return;
        }

        // Jangan biarkan galat database menampilkan halaman HTML (merusak balasan JSON)
        $this->db->db_debug = FALSE;

        $pairs = $this->input->post('pairs');
        if (!is_array($pairs) || count($pairs) === 0) {
            $this->output_json(['ok' => false, 'msg' => 'Tidak ada data yang dipilih']);
            return;
        }
        $pairs = array_slice($pairs, 0, 500);

        // beri_izin = 0 (hapus per siswa dari kolom AKSI): tombol Keluar TIDAK ditampilkan di aplikasi.
        // Selain itu (hapus per kelas dan mapel): siswa diberi izin keluar otomatis.
        $beri_izin = ((string) $this->input->post('beri_izin')) !== '0';

        // reset_waktu = 1 (tombol per siswa): jawaban, log, dan nilai dihapus (pengerjaan direset), lalu
        // ujian siswa DIAKHIRI (paksa selesai, sama seperti aksi "Paksa Selesai" di halaman Status Siswa).
        // Baris durasi siswa dipertahankan sehingga siswa tetap tampil di dashboard admin dan admin
        // bisa menekan tombol izin keluar. Untuk mengulang ujian dari awal, pakai aksi "Ulang" di Status Siswa.
        $reset_waktu = ((string) $this->input->post('reset_waktu')) === '1';

        // Tabel yang berisi data ujian satu siswa pada satu jadwal
        $tabel = ['cbt_durasi_siswa', 'log_ujian', 'cbt_nilai', 'cbt_soal_siswa'];

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

        $jumlah = 0;
        $this->db->trans_start();
        foreach ($pairs as $key) {
            if (!preg_match('/^(\d+)_(\d+)$/', (string) $key, $m)) { continue; }
            $id_siswa  = (int) $m[1];
            $id_jadwal = (int) $m[2];
            if ($id_siswa <= 0 || $id_jadwal <= 0) { continue; }

            foreach ($tabel as $t) {
                if (!$this->db->table_exists($t)) { continue; }
                if (!$this->db->field_exists('id_siswa', $t) || !$this->db->field_exists('id_jadwal', $t)) { continue; }

                if ($reset_waktu && $t === 'cbt_durasi_siswa') {
                    // Paksa selesai (meniru applyAction di Siswa.php): status 2, waktu selesai sekarang, reset 3.
                    $set = [];
                    $nilai_reset = [
                        'status'         => 2,
                        'selesai'        => date('Y-m-d H:i:s'),
                        'reset'          => 3,
                        'jeda'           => null,
                        'waktu_tambahan' => 0,
                    ];
                    foreach ($nilai_reset as $kol => $nilai) {
                        if ($this->db->field_exists($kol, $t)) { $set[$kol] = $nilai; }
                    }
                    if (!empty($set)) {
                        $this->db->where('id_siswa', $id_siswa)->where('id_jadwal', $id_jadwal)->update($t, $set);
                    }
                    continue;
                }

                $this->db->where('id_siswa', $id_siswa)->where('id_jadwal', $id_jadwal)->delete($t);
            }
            $username = $this->_username_dari_siswa($id_siswa);
            if ($username !== '') {
                $this->db->replace('izin_keluar', [
                    'username' => $username,
                    'izin'     => $beri_izin ? 1 : 0,
                    'pesan'    => $reset_waktu
                        ? 'Ujian Anda telah diakhiri oleh pengawas dan hasilnya direset. Silakan tunggu arahan pengawas.'
                        : ($beri_izin
                            ? 'Data ujian Anda telah dihapus oleh pengawas. Silakan tekan tombol Keluar.'
                            : 'Data ujian Anda telah dihapus oleh pengawas. Silakan login kembali.'),
                ]);
                $this->_logout_siswa($username);   // kembalikan siswa ke halaman login
            }
            $jumlah++;
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->output_json(['ok' => false, 'msg' => 'Gagal menghapus data (database menolak perubahan)']);
            return;
        }
        $this->output_json(['ok' => true, 'jumlah' => $jumlah, 'csrf' => $this->security->get_csrf_hash()]);
    }
}