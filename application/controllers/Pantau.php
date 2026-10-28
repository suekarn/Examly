<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pantau: halaman pantauan ujian untuk orang tua (tanpa login, hanya lihat).
 *
 *   Alamat pendek (lewat VirtualHost, lihat PANDUAN.md):
 *     Halaman : https://pantau.sekolahanda.sch.id/pantau/TOKEN
 *     Data    : https://pantau.sekolahanda.sch.id/pantau/data/TOKEN
 *   Alamat biasa (lokal) tetap bisa: http://localhost/ujian/index.php/pantau/index/TOKEN
 *
 * PENTING: controller ini TIDAK boleh diblokir oleh pengecekan login/hook milik aplikasi CBT.
 */
class Pantau extends CI_Controller
{
    // Ganti dengan teks acak panjang. Ganti lagi kapan saja untuk mencabut akses.
    private $token = 'GANTI_DENGAN_TOKEN_ACAK_YANG_PANJANG';

    // Nama host publik (domain Cloudflare Tunnel). Dipakai untuk membuat alamat data & gambar yang benar.
    private $host_publik  = 'pantau.sekolahanda.sch.id';

    private $aktif        = TRUE;   // FALSE = tutup halaman (misal di luar jam ujian)
    private $singkat_nama = TRUE;   // "Ahmad Fauzi Ramadhan" -> "Ahmad F. R."
    private $ttl_cache    = 4;      // detik; data dipakai bersama oleh semua pengunjung

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    private function token_valid($token)
    {
        return $this->aktif && $token !== '' && hash_equals($this->token, (string) $token);
    }

    public function index($token = '')
    {
        if (!$this->token_valid($token)) { show_404(); }
        $this->output->set_header('X-Robots-Tag: noindex, nofollow');
        $this->load->view('pantau_publik', ['data_url' => $this->url_data($token)]);
    }

    public function data($token = '')
    {
        if (!$this->token_valid($token)) {
            $this->kirim(['error' => 'Link tidak berlaku'], 403);
            return;
        }

        $file = APPPATH . 'cache/pantau_live.json';
        $json = null;

        if (is_file($file) && (time() - filemtime($file)) < $this->ttl_cache) {
            $json = @file_get_contents($file);
        }

        if (!$json) {
            $this->load->library('live_data');
            $rows = $this->live_data->ambil();
            $out  = [];

            foreach ($rows as $r) {
                $jk = strtoupper($r['jenis_kelamin']);
                $out[] = [
                    'id'       => substr(md5($r['id_siswa'] . $this->token), 0, 10), // bukan ID asli
                    'nama'     => $this->singkat_nama ? $this->singkat($r['nama']) : $r['nama'],
                    'kelas'    => $r['kelas'],
                    'mapel'    => $r['nama_mapel'],
                    'avatar'   => ($jk === 'P' || strpos(strtolower($r['nama']), 'awa') !== false) ? 'siswa-p.png' : 'siswa-l.png',
                    'progress' => (float) $r['progress'],
                    'benar'    => (int) $r['benar'],
                    'salah'    => (int) $r['salah'],
                    'skor'     => (float) $r['skor'],
                    'status'   => $r['status'] === 'selesai' ? 'selesai' : 'aktif',
                    'selesai'  => (int) $r['waktu_selesai'],
                ];
            }

            // Urut berdasarkan kelas lalu nama (bukan peringkat skor)
            usort($out, function ($a, $b) {
                $c = strcmp($a['kelas'], $b['kelas']);
                return $c !== 0 ? $c : strcmp($a['nama'], $b['nama']);
            });

            $json = json_encode(['rows' => $out], JSON_UNESCAPED_UNICODE);
            @file_put_contents($file, $json, LOCK_EX);
        }

        // 'now' dikirim dari server agar hitung mundur tidak bergantung jam HP orang tua
        $data = json_decode($json, true);
        foreach ($data['rows'] as &$row) { $row['avatar'] = $this->url_avatar($row['avatar']); }
        unset($row);
        $data['now'] = time();
        $this->kirim($data, 200);
    }

    private function di_host_publik()
    {
        return isset($_SERVER['HTTP_HOST']) && strcasecmp($_SERVER['HTTP_HOST'], $this->host_publik) === 0;
    }

    private function url_data($token)
    {
        return $this->di_host_publik() ? '/pantau/data/' . rawurlencode($token) : site_url('pantau/data/' . rawurlencode($token));
    }

    private function url_avatar($file)
    {
        return $this->di_host_publik() ? '/assets/img/' . $file : base_url('assets/img/' . $file);
    }

    private function kirim($data, $status)
    {
        $this->output
            ->set_status_header($status)
            ->set_header('Cache-Control: no-store, max-age=0')
            ->set_header('X-Robots-Tag: noindex')
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    private function singkat($nama)
    {
        $kata = preg_split('/\s+/', trim($nama));
        if (count($kata) <= 1) { return $nama; }
        $hasil = array_shift($kata);
        foreach ($kata as $k) { $hasil .= ' ' . mb_strtoupper(mb_substr($k, 0, 1, 'UTF-8'), 'UTF-8') . '.'; }
        return $hasil;
    }
}