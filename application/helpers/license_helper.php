<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// LG_HELPER_VERSION: 8

/**
 * =====================================================================
 * LICENSE HELPER — dipakai di sisi aplikasi klien (Moodf / GarudaCBT)
 * =====================================================================
 * Taruh file ini di: application/helpers/license_helper.php
 * Lalu panggil: $this->load->helper('license');  (di Converter.php & di
 * sidebar.php kalau sidebar.php punya akses ke $this->load — kalau
 * tidak, cukup include file ini langsung dengan require_once).
 *
 * GANTI nilai LG_LICENSE_SERVER_URL di bawah ke URL hosting Anda sendiri.
 * JANGAN diarahkan ke localhost pada instalasi produksi — lihat
 * penjelasan di check_license.php.
 *
 * v5: tidak lemot saat internet mati.
 *  - Kegagalan menghubungi server dicatat, dan percobaan berikutnya baru
 *    dilakukan setelah LG_RETRY_SECONDS (bukan di setiap halaman).
 *  - Timeout dipersingkat dan tidak ada percobaan ganda (cURL lalu fopen).
 *  - MAC / IP / hostname di-cache 24 jam (shell_exec getmac itu lambat).
 * =====================================================================
 */

// ----- URL SERVER LISENSI -----
// Default di bawah untuk pengujian lokal (XAMPP). Untuk instalasi produksi,
// JANGAN ubah file ini: cukup tambahkan satu baris di
// application/config/license_key.php:
//     $server_url = 'https://lisensi-anda.com/check_license.php';
if (!defined('LG_LICENSE_SERVER_URL')) {
    define('LG_LICENSE_SERVER_URL', 'http://localhost/key/check_license.php');
}
// v7: instalasi dikenali hanya dari KODE LISENSI + MAC ADDRESS. Domain, IP, dan nama
// komputer tidak dikirim, jadi ganti IP / alamat akses tidak memengaruhi lisensi.
// Set true HANYA kalau server lisensi lama masih mewajibkan parameter domain/ip/host.
if (!defined('LG_SEND_EXTRA')) define('LG_SEND_EXTRA', false);
if (!defined('LG_SEND_IP'))    define('LG_SEND_IP', false); // v6: kirim IP komputer ke server lisensi? (tidak perlu)
if (!defined('LG_DEBUG'))      define('LG_DEBUG', false);   // set true hanya saat perlu melacak masalah
if (!defined('LG_DEBUG_FILE')) define('LG_DEBUG_FILE', APPPATH . 'cache/lg_license_debug.log');
if (!defined('LG_CACHE_FILE'))  define('LG_CACHE_FILE', APPPATH . 'cache/lg_license_cache.json');
if (!defined('LG_FAIL_FILE'))   define('LG_FAIL_FILE', APPPATH . 'cache/lg_license_fail.flag');
if (!defined('LG_IDENT_FILE'))  define('LG_IDENT_FILE', APPPATH . 'cache/lg_license_ident.json');
if (!defined('LG_MENU_TTL'))    define('LG_MENU_TTL', 60);    // detik; dipakai untuk tampil/sembunyi menu di sidebar
if (!defined('LG_GRACE_DAYS'))  define('LG_GRACE_DAYS', 3);   // hari offline maksimal sebelum Converter dikunci
if (!defined('LG_RETRY_SECONDS')) define('LG_RETRY_SECONDS', 60); // jeda sebelum mencoba server lisensi lagi setelah gagal
if (!defined('LG_HTTP_TIMEOUT'))  define('LG_HTTP_TIMEOUT', 2);    // detik, total waktu tunggu per permintaan
if (!defined('LG_CONNECT_TIMEOUT')) define('LG_CONNECT_TIMEOUT', 1); // detik, waktu tunggu koneksi


/**
 * Identitas domain instalasi yang STABIL. Pakai host dari base_url di
 * config.php (kalau diisi), bukan HTTP_HOST mentah, karena HTTP_HOST
 * berubah-ubah tergantung cara pengguna membuka aplikasi (localhost,
 * IP LAN, www / non-www). Itu penyebab lisensi tiba-tiba "terkunci".
 */
if (!function_exists('lg_site_host')) {
    function lg_site_host() {
        $host = '';
        if (function_exists('get_instance')) {
            $ci = get_instance();
            if ($ci && isset($ci->config)) {
                $base = (string)$ci->config->item('base_url');
                $host = (string)parse_url($base, PHP_URL_HOST);
            }
        }
        if ($host === '') $host = $_SERVER['HTTP_HOST'] ?? '';
        $host = strtolower(trim($host));
        $host = preg_replace('/:\d+$/', '', $host);
        $host = preg_replace('/^www\./', '', $host);
        // v6: IP LAN / localhost dianggap SATU domain tetap ('localhost'), supaya
        // ganti IP (DHCP, pindah jaringan, buka lewat .118 / .246) tidak mengubah
        // identitas instalasi dan tidak membuat menu AI Soal hilang.
        if ($host === '' || $host === 'localhost' || filter_var($host, FILTER_VALIDATE_IP)) return 'localhost';
        return $host;
    }
}


/** Catat alasan kegagalan ke application/cache/lg_license_debug.log (maks ~50 KB). */
if (!function_exists('lg_log')) {
    function lg_log($msg) {
        if (is_file(LG_DEBUG_FILE) && @filesize(LG_DEBUG_FILE) > 51200) @unlink(LG_DEBUG_FILE);
        @file_put_contents(LG_DEBUG_FILE, '[' . date('Y-m-d H:i:s') . '] ' . $msg . "\n", FILE_APPEND | LOCK_EX);
    }
}

/** Log detail hanya saat LG_DEBUG aktif. */
if (!function_exists('lg_dbg')) {
    function lg_dbg($msg) {
        if (defined('LG_DEBUG') && LG_DEBUG) lg_log('[DBG] ' . $msg);
    }
}

/** Ambil nilai string dari license_key.php (mis. $key / $server_url) tanpa include. */
if (!function_exists('lg_config_value')) {
    function lg_config_value($name) {
        $f = APPPATH . 'config/license_key.php';
        if (!is_file($f)) return '';
        $raw = @file_get_contents($f);
        if ($raw === false) return '';
        $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw); // buang BOM
        if (preg_match('/\$' . preg_quote($name, '/') . '\s*=\s*([\'"])([^\'"]+)\1\s*;/', $raw, $m)) {
            return trim($m[2]);
        }
        return '';
    }
}

if (!function_exists('lg_server_url')) {
    function lg_server_url() {
        $u = lg_config_value('server_url');
        return $u !== '' ? $u : LG_LICENSE_SERVER_URL;
    }
}

/**
 * Ambil MAC address fisik pertama dari mesin SERVER tempat aplikasi ini
 * berjalan (bukan mesin orang lain). Dipakai sebagai fingerprint
 * tambahan supaya kalau satu kode lisensi dipakai di komputer lain
 * (hardware beda), itu kelihatan di admin.php sebagai MAC yang berubah.
 * Butuh shell_exec aktif di server; kalau dimatikan hosting, akan
 * kembalikan string kosong (bukan error).
 */
if (!function_exists('lg_local_mac')) {
    function lg_local_mac() {
        static $mac = null;
        if ($mac !== null) return $mac;
        $mac = '';

        if (function_exists('shell_exec') && stripos(ini_get('disable_functions'), 'shell_exec') === false) {
            $isWindows = stripos(PHP_OS, 'WIN') === 0;
            $out = $isWindows
                ? @shell_exec('getmac /NH /FO CSV 2>NUL')
                : @shell_exec('ip link show 2>/dev/null || ifconfig -a 2>/dev/null');

            if ($out && preg_match_all('/([0-9A-Fa-f]{2}([:-][0-9A-Fa-f]{2}){5})/', $out, $m)) {
                // v6: kumpulkan semua MAC, buang yang nol / virtual / acak (locally administered,
                // VirtualBox, VMware, Hyper-V), lalu URUTKAN. Dengan begitu hasilnya tidak
                // bergantung pada urutan adapter yang bisa berubah (VPN, adapter virtual, dll).
                $virtual = ['0A-00-27', '00-50-56', '00-0C-29', '00-05-69', '00-15-5D', '00-1C-14'];
                $cands = [];
                foreach ($m[1] as $candidate) {
                    $norm = strtoupper(str_replace(':', '-', $candidate));
                    if ($norm === '00-00-00-00-00-00' || $norm === 'FF-FF-FF-FF-FF-FF') continue;
                    if (in_array(substr($norm, 0, 8), $virtual, true)) continue;
                    $firstByte = hexdec(substr($norm, 0, 2));
                    if ($firstByte & 0x02) continue; // locally administered = MAC acak / virtual
                    $cands[$norm] = true;
                }
                $cands = array_keys($cands);
                sort($cands, SORT_STRING);
                if ($cands) $mac = $cands[0];
            }
        }
        return $mac;
    }
}

/**
 * Ambil IP lokal/LAN mesin server (bukan IP pengunjung website).
 * Dipakai sebagai info tambahan di admin.php, bukan untuk blokir.
 */
if (!function_exists('lg_local_ip')) {
    function lg_local_ip() {
        static $ip = null;
        if ($ip !== null) return $ip;
        $ip = '';

        $host = @gethostname();
        if ($host) {
            $resolved = @gethostbyname($host);
            if ($resolved && $resolved !== $host && filter_var($resolved, FILTER_VALIDATE_IP)) {
                $ip = $resolved;
            }
        }
        return $ip;
    }
}

/** Nama perangkat (hostname) server tempat aplikasi ini berjalan. */
if (!function_exists('lg_local_hostname')) {
    function lg_local_hostname() {
        static $h = null;
        if ($h !== null) return $h;
        $h = @gethostname();
        $h = $h ? $h : '';
        return $h;
    }
}

/**
 * MAC + IP + hostname dalam satu paket, disimpan 24 jam di cache file.
 * getmac / gethostbyname lambat di Windows, jadi jangan dijalankan di
 * setiap pemeriksaan lisensi.
 */
if (!function_exists('lg_ident')) {
    function lg_ident() {
        static $id = null;
        if ($id !== null) return $id;

        if (is_file(LG_IDENT_FILE) && (time() - (int)@filemtime(LG_IDENT_FILE)) < 86400) {
            $d = json_decode((string)@file_get_contents(LG_IDENT_FILE), true);
            // v6: cache yang MAC-nya kosong dianggap tidak sah (dulu kosong ikut tersimpan 24 jam).
            if (is_array($d) && !empty($d['mac']) && array_key_exists('ip', $d) && array_key_exists('host', $d)) {
                return $id = $d;
            }
        }
        // v6: IP tidak dikirim (hanya info tambahan dan sering berganti). Set LG_SEND_IP = true
        // di atas file ini kalau masih ingin IP tampil di admin.php.
        $id = [
            'mac'  => lg_local_mac(),
            'ip'   => (defined('LG_SEND_IP') && LG_SEND_IP) ? lg_local_ip() : '',
            'host' => lg_local_hostname(),
        ];
        // v6: jangan simpan hasil gagal (MAC kosong) supaya dicoba lagi di permintaan berikutnya.
        if ($id['mac'] !== '') @file_put_contents(LG_IDENT_FILE, json_encode($id), LOCK_EX);
        return $id;
    }
}

/** True kalau percobaan terakhir ke server lisensi gagal dan jeda LG_RETRY_SECONDS belum lewat. */
if (!function_exists('lg_recent_fail')) {
    function lg_recent_fail() {
        if (!is_file(LG_FAIL_FILE)) return false;
        return (time() - (int)@filemtime(LG_FAIL_FILE)) < LG_RETRY_SECONDS;
    }
}

if (!function_exists('lg_mark_fail')) {
    function lg_mark_fail() { @file_put_contents(LG_FAIL_FILE, (string)time(), LOCK_EX); }
}

if (!function_exists('lg_clear_fail')) {
    function lg_clear_fail() { if (is_file(LG_FAIL_FILE)) @unlink(LG_FAIL_FILE); }
}

/**
 * GET satu kali saja dengan timeout pendek. Pakai cURL kalau ada (bisa
 * atur connect timeout), kalau tidak pakai file_get_contents. Tidak ada
 * percobaan ganda: dulu gagal fopen 3 detik lalu cURL 3 detik lagi.
 */
if (!function_exists('lg_http_get')) {
    function lg_http_get($url) {
        $raw = false;
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => LG_HTTP_TIMEOUT,
                CURLOPT_CONNECTTIMEOUT => LG_CONNECT_TIMEOUT,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_USERAGENT      => 'GarudaCBT-Converter/1.0',
                CURLOPT_NOSIGNAL       => true,   // timeout DNS < 1 detik ikut dihormati
            ]);
            $raw = curl_exec($ch);
            if ($raw === false) lg_log('cURL error: ' . curl_error($ch) . ' | url=' . strtok($url, '?'));
            curl_close($ch);
        } elseif (ini_get('allow_url_fopen')) {
            $ctx = stream_context_create(['http' => [
                'method' => 'GET', 'timeout' => LG_HTTP_TIMEOUT, 'ignore_errors' => true,
                'header' => "User-Agent: GarudaCBT-Converter/1.0\r\n",
            ]]);
            $raw = @file_get_contents($url, false, $ctx);
        }
        return $raw;
    }
}

/**
 * Baca kode lisensi dari application/config/license_key.php ($key = '...';)
 * atau license.key (teks biasa). Sengaja tidak lewat include/eval PHP supaya tidak ada titik gagal tambahan (opcache,
 * antivirus mengunci file saat sedang diedit, dll) yang pernah bikin
 * kode terbaca kosong secara acak.
 */
if (!function_exists('lg_license_key')) {
    function lg_license_key() {
        static $cached = null;
        if ($cached !== null && $cached !== '') return $cached;

        $cached = lg_config_value('key');            // license_key.php -> $key = '...';
        if ($cached !== '') return $cached;

        $txtFile = APPPATH . 'config/license.key';   // alternatif: teks biasa
        if (is_file($txtFile)) {
            $raw = @file_get_contents($txtFile);
            if ($raw !== false) $cached = trim(preg_replace('/^\xEF\xBB\xBF/', '', $raw));
        }
        return $cached;
    }
}

/**
 * Panggil server lisensi secara LIVE (sumber kebenaran saat ini) dan
 * simpan hasilnya ke cache file. Return null kalau server tidak
 * terjangkau, kode lisensi belum dikonfigurasi, atau percobaan terakhir
 * baru saja gagal (masih dalam jeda LG_RETRY_SECONDS).
 */
if (!function_exists('lg_fetch_license_live')) {
    function lg_fetch_license_live() {
        $key = lg_license_key();
        lg_dbg('fetch_live dipanggil dari ' . (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '-') . ' | key_len=' . strlen($key));
        if ($key === '') return null;

        // Server baru saja gagal dihubungi: jangan buat setiap halaman menunggu lagi.
        if (lg_recent_fail()) {
            lg_dbg('dilewati: percobaan terakhir gagal, menunggu jeda ' . LG_RETRY_SECONDS . ' detik');
            return null;
        }

        $id  = lg_ident();
        // v7: hanya kode lisensi + MAC address.
        $url = lg_server_url() . '?key=' . urlencode($key)
             . '&mac=' . urlencode($id['mac']);
        if (LG_SEND_EXTRA) { // mode kompatibilitas server lama
            $url .= '&domain=' . urlencode(lg_site_host())
                  . '&ip=' . urlencode((defined('LG_SEND_IP') && LG_SEND_IP) ? lg_local_ip() : '')
                  . '&host=' . urlencode($id['host']);
        }
        $raw    = lg_http_get($url);
        lg_dbg('request=' . preg_replace('/key=[^&]*/', 'key=***', $url) . ' | raw=' . ($raw === false ? 'FALSE' : substr($raw, 0, 160)));
        if ($raw === false || $raw === '') {
            lg_log('Server lisensi tidak terjangkau: ' . lg_server_url());
            lg_mark_fail();
            return null;
        }

        $parsed = json_decode($raw, true);
        if (!is_array($parsed) || !isset($parsed['status'])) {
            lg_log('Balasan server bukan JSON valid: ' . substr($raw, 0, 200));
            lg_mark_fail();
            return null;
        }

        lg_clear_fail();
        $cache = [
            'status'       => $parsed['status'],
            'message'      => $parsed['message']      ?? null,
            'contact'      => $parsed['contact']      ?? null,
            'expired_date' => $parsed['expired_date'] ?? null,
            'menu_ai_soal' => array_key_exists('menu_ai_soal', $parsed) ? (bool)$parsed['menu_ai_soal'] : true,
            'checked_at'   => time(),
        ];
        @file_put_contents(LG_CACHE_FILE, json_encode($cache), LOCK_EX);
        return $cache;
    }
}

if (!function_exists('lg_read_cache')) {
    function lg_read_cache() {
        if (!is_file(LG_CACHE_FILE)) return null;
        $raw  = @file_get_contents(LG_CACHE_FILE);
        $data = json_decode($raw, true);
        return is_array($data) ? $data : null;
    }
}

/**
 * Dipakai Converter.php: keputusan BLOKIR modul, dengan grace period
 * kalau server lisensi sedang tidak terjangkau. Return array:
 *   ['block' => bool, 'error' => [...] atau null]
 */
if (!function_exists('lg_evaluate_access')) {
    function lg_evaluate_access() {
        $domain = lg_site_host();
        $key    = lg_license_key();
        lg_dbg('evaluate_access | key_len=' . strlen($key));

        if ($key === '') {
            lg_log('Kode lisensi kosong. Dicari di: ' . APPPATH . 'config/license_key.php');
            return ['block' => true, 'error' => [
                'title' => 'Lisensi Belum Dikonfigurasi',
                'message' => 'Kode lisensi belum diatur di instalasi ini. Hubungi developer.',
                'contact' => '081234567890', 'domain' => $domain, 'expired_date' => '-',
            ]];
        }

        $live = lg_fetch_license_live();

        if ($live !== null) {
            if (in_array($live['status'], ['expired', 'disabled'], true)) {
                return ['block' => true, 'error' => [
                    'title' => $live['status'] === 'expired' ? 'Masa Trial / Lisensi Berakhir' : 'Lisensi Tidak Aktif',
                    'message' => $live['message'] ?: 'Masa berlaku lisensi modul Converter AI telah habis. Silakan perpanjang lisensi Anda.',
                    'contact' => $live['contact'] ?: '081234567890', 'domain' => $domain,
                    'expired_date' => $live['expired_date'] ?: '-',
                ]];
            }
            if (array_key_exists('menu_ai_soal', $live) && $live['menu_ai_soal'] === false) {
                return ['block' => true, 'error' => [
                    'title' => 'Modul Dinonaktifkan',
                    'message' => 'Modul AI Soal sedang dinonaktifkan untuk instalasi ini. Hubungi developer untuk mengaktifkan kembali.',
                    'contact' => $live['contact'] ?: '081234567890', 'domain' => $domain,
                    'expired_date' => $live['expired_date'] ?: '-',
                ]];
            }
            return ['block' => false, 'error' => null];
        }

        // Server lisensi tidak terjangkau -> pakai cache terakhir yang berhasil.
        $cache = lg_read_cache();
        if (is_array($cache) && isset($cache['checked_at'], $cache['status'])) {
            $ageDays = (time() - $cache['checked_at']) / 86400;

            if (in_array($cache['status'], ['expired', 'disabled'], true)) {
                return ['block' => true, 'error' => [
                    'title' => $cache['status'] === 'expired' ? 'Masa Trial / Lisensi Berakhir' : 'Lisensi Tidak Aktif',
                    'message' => $cache['message'] ?: 'Masa berlaku lisensi modul Converter AI telah habis. Silakan perpanjang lisensi Anda.',
                    'contact' => $cache['contact'] ?: '081234567890', 'domain' => $domain,
                    'expired_date' => $cache['expired_date'] ?: '-',
                ]];
            }
            if (array_key_exists('menu_ai_soal', $cache) && $cache['menu_ai_soal'] === false) {
                return ['block' => true, 'error' => [
                    'title' => 'Modul Dinonaktifkan',
                    'message' => 'Modul AI Soal sedang dinonaktifkan untuk instalasi ini. Hubungi developer untuk mengaktifkan kembali.',
                    'contact' => $cache['contact'] ?: '081234567890', 'domain' => $domain,
                    'expired_date' => $cache['expired_date'] ?: '-',
                ]];
            }
            if ($ageDays > LG_GRACE_DAYS) {
                return ['block' => true, 'error' => [
                    'title' => 'Verifikasi Lisensi Gagal',
                    'message' => 'Tidak dapat memverifikasi lisensi dalam ' . LG_GRACE_DAYS . ' hari terakhir. Hubungi developer.',
                    'contact' => '081234567890', 'domain' => $domain, 'expired_date' => '-',
                ]];
            }
            return ['block' => false, 'error' => null]; // masih dalam grace period
        }

        // Belum pernah ada cache sukses & server sedang tidak terjangkau -> kunci.
        return ['block' => true, 'error' => [
            'title' => 'Verifikasi Lisensi Gagal',
            'message' => 'Tidak dapat menghubungi server lisensi dan belum ada riwayat verifikasi yang tersimpan. Hubungi developer.',
            'contact' => '081234567890', 'domain' => $domain, 'expired_date' => '-',
        ]];
    }
}

/**
 * Dipakai sidebar.php: cukup tahu "tampilkan menu atau tidak". Boleh pakai
 * cache yang agak basi (LG_MENU_TTL) supaya ringan dipanggil di SETIAP
 * halaman, bukan cuma halaman Converter. Saat server lisensi tidak
 * terjangkau, fungsi ini langsung memakai cache terakhir tanpa menunggu.
 */
if (!function_exists('lg_show_ai_soal_menu')) {
    function lg_show_ai_soal_menu() {
        $cache = lg_read_cache();
        $fresh = $cache && isset($cache['checked_at']) && (time() - $cache['checked_at']) < LG_MENU_TTL;

        if (!$fresh) {
            $live = lg_fetch_license_live(); // null cepat bila baru gagal (lihat lg_recent_fail)
            if ($live !== null) $cache = $live;
        }
        // v8: belum pernah berhasil diverifikasi server lisensi -> SEMBUNYIKAN (dulu: tampil).
        if (!$cache) return false;
        if (in_array($cache['status'] ?? '', ['expired', 'disabled'], true)) return false;
        // v8: terlalu lama tidak bisa memverifikasi (lewat masa tenggang) -> sembunyikan.
        if (isset($cache['checked_at']) && (time() - $cache['checked_at']) / 86400 > LG_GRACE_DAYS) return false;
        return !empty($cache['menu_ai_soal']);
    }
}

/**
 * v8: GERBANG akses halaman AI Soal (dipanggil di constructor Aiimport.php).
 * Menyembunyikan menu saja tidak cukup, karena alamat /aiimport masih bisa diketik langsung.
 * Return: ['block' => bool, 'error' => [...] atau null] (bentuk sama dengan lg_evaluate_access).
 * Jawaban 'boleh' dari cache yang masih segar (LG_MENU_TTL) dipakai supaya tiap klik tidak
 * memanggil server; jawaban 'blokir' selalu dicek ulang ke server, jadi unbind di admin.php
 * berlaku dalam hitungan detik.
 */
if (!function_exists('lg_gate_ai_soal')) {
    function lg_gate_ai_soal() {
        $cache = lg_read_cache();
        if ($cache && isset($cache['checked_at'], $cache['status'])
            && (time() - $cache['checked_at']) < LG_MENU_TTL
            && $cache['status'] === 'active' && !empty($cache['menu_ai_soal'])) {
            return ['block' => false, 'error' => null];
        }
        return lg_evaluate_access();
    }
}