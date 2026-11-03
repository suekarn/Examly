<?php
/**
 * =====================================================================
 * LICENSE SERVER — check_license.php (v2: 1 KODE LISENSI = 1 MAC ADDRESS)
 * =====================================================================
 * Taruh di: C:\xampp\htdocs\key\check_license.php  (sefolder dengan admin.php)
 *
 * Dipanggil aplikasi klien (license_helper.php):
 *     check_license.php?key=GCBT-XXXX-XXXX-XXXX-XXXX&mac=AA-BB-CC-DD-EE-FF
 *
 * ATURAN
 *  1. Pemakaian pertama (kode terdaftar, status active, belum expired, MAC terbaca)
 *     -> kode otomatis TERIKAT ke MAC itu.
 *  2. Setelah terikat, hanya MAC tersebut yang diterima.
 *  3. MAC lain (atau MAC tidak terbaca) memakai kode yang sama -> DIKUNCI:
 *     menu AI Soal disembunyikan di komputer itu, dan percobaannya tercatat di
 *     admin.php ("x percobaan perangkat lain diblokir"). Komputer asli tidak terganggu.
 *  4. Pindah komputer resmi: klik ikon "Lepas ikatan perangkat" di admin.php,
 *     lalu komputer baru yang mengecek berikutnya menjadi perangkat resmi.
 *  5. Domain / IP / nama komputer BUKAN penentu (hanya dicatat kalau dikirim).
 *
 * Berbagi berkas data dengan admin.php: licenses.json.
 * =====================================================================
 */

define('LICENSES_FILE', __DIR__ . '/licenses.json');
define('BACKUP_DIR',    __DIR__ . '/backup');
define('BACKUP_KEEP',   10);   // harus sama dengan admin.php
define('HISTORY_KEEP',  100);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');

/* ---------------------------------------------------------------
 * Baca-ubah-tulis berkas JSON secara atomik (dikunci) supaya dua
 * permintaan bersamaan tidak saling menimpa data.
 * --------------------------------------------------------------- */
/** Salin kondisi SEBELUM perubahan penting (mis. pengikatan perangkat) ke backup/ (dipakai bersama admin.php). */
function cl_backup($raw) {
    $raw = (string)$raw;
    if (trim($raw) === '') return;
    $d = json_decode($raw, true);
    if (!is_array($d) || empty($d['installations'])) return;           // data kosong tidak dicadangkan
    if (!is_dir(BACKUP_DIR)) @mkdir(BACKUP_DIR, 0755, true);
    if (!is_dir(BACKUP_DIR)) return;
    if (!is_file(BACKUP_DIR . '/.htaccess')) @file_put_contents(BACKUP_DIR . '/.htaccess', "<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n    Order allow,deny\n    Deny from all\n</IfModule>\n");
    if (!is_file(BACKUP_DIR . '/index.html')) @file_put_contents(BACKUP_DIR . '/index.html', '');
    $key = function ($path) {
        return preg_match('/licenses-(\d{8})-(\d{6})(?:-(\d+))?\.json$/', $path, $m) ? $m[1] . $m[2] . sprintf('%03d', (int)($m[3] ?? 0)) : '0';
    };
    $files = glob(BACKUP_DIR . '/licenses-*.json') ?: [];
    usort($files, function ($a, $b) use ($key) { return strcmp($key($b), $key($a)); });
    if ($files && @file_get_contents($files[0]) === $raw) return;
    $base = BACKUP_DIR . '/licenses-' . date('Ymd-His');
    $name = $base . '.json'; $i = 1;
    while (is_file($name)) { $i++; $name = $base . '-' . $i . '.json'; }
    if (@file_put_contents($name, $raw) === false) return;
    $files = glob(BACKUP_DIR . '/licenses-*.json') ?: [];
    usort($files, function ($a, $b) use ($key) { return strcmp($key($b), $key($a)); });
    foreach (array_slice($files, BACKUP_KEEP) as $old) @unlink($old);
}

/** Tambah catatan riwayat ke sebuah instalasi. */
function cl_log(&$inst, $event, $detail, $now) {
    $h = $inst['history'] ?? [];
    $h[] = ['t' => $now, 'e' => $event, 'd' => $detail];
    $inst['history'] = array_slice($h, -HISTORY_KEEP);
}

function cl_json_update($file, callable $fn, $default) {
    $fh = @fopen($file, 'c+');
    if (!$fh) return $default;
    flock($fh, LOCK_EX);
    $raw  = stream_get_contents($fh);
    $data = json_decode($raw === false ? '' : $raw, true);
    if (!is_array($data)) {
        // Berkas ada isinya tetapi tidak bisa dibaca (rusak / sedang ditulis proses lain): JANGAN ditimpa
        // dengan data kosong. Kembalikan null supaya pemanggil menjawab "tidak tersedia" tanpa menyentuh berkas.
        if (trim((string)$raw) !== '') { flock($fh, LOCK_UN); fclose($fh); return null; }
        $data = $default;   // berkas baru / kosong
    }

    $result = $fn($data);              // $fn mengubah $data lewat referensi & mengembalikan hasil
    if (!empty($result['backup'])) cl_backup($raw);   // cadangkan kondisi sebelum perubahan (dari string, bukan baca ulang berkas terkunci)
    $out    = $result['data'];
    $new    = json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($new !== $raw) {
        ftruncate($fh, 0);
        rewind($fh);
        fwrite($fh, $new);
        fflush($fh);
    }
    flock($fh, LOCK_UN);
    fclose($fh);
    return $result['value'];
}

function cl_default_shell() {
    return ['installations' => [], 'default' => [
        'status' => 'disabled', 'message' => '', 'contact' => '081234567890',
        'expired_date' => '-', 'menu_ai_soal' => false,
    ]];
}

function cl_norm_mac($mac) {
    $mac = strtoupper(trim((string)$mac));
    $mac = str_replace(':', '-', $mac);
    if (!preg_match('/^([0-9A-F]{2}-){5}[0-9A-F]{2}$/', $mac)) return '';
    if ($mac === '00-00-00-00-00-00' || $mac === 'FF-FF-FF-FF-FF-FF') return '';
    return $mac;
}

function cl_clean($v, $max = 80) {
    $v = preg_replace('/[^A-Za-z0-9 _.:\-]/', '', (string)$v);
    return substr(trim($v), 0, $max);
}

function cl_reply($arr) {
    echo json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/* ---------------------------------------------------------------
 * Masukan dari klien
 * --------------------------------------------------------------- */
$key     = trim((string)($_GET['key'] ?? ''));
$mac     = cl_norm_mac($_GET['mac'] ?? '');
$host    = cl_clean($_GET['host'] ?? '');
$ipRep   = cl_clean($_GET['ip'] ?? '', 45);
$domain  = cl_clean($_GET['domain'] ?? '', 120);
$visitor = cl_clean($_SERVER['REMOTE_ADDR'] ?? '', 45);
$now     = date('Y-m-d H:i:s');
$today   = date('Y-m-d');

$response = cl_json_update(LICENSES_FILE, function ($data) use ($key, $mac, $host, $ipRep, $domain, $visitor, $now, $today) {
    $data['installations'] = $data['installations'] ?? [];
    $data['default']       = $data['default'] ?? cl_default_shell()['default'];

    $backup = false;
    $out = function ($arr) use (&$data, &$backup) { return ['data' => $data, 'value' => $arr, 'backup' => $backup]; };

    /* ---- kode tidak dikenal / kosong ---- */
    if ($key === '' || !isset($data['installations'][$key])) {
        $d = $data['default'];
        return $out([
            'status'       => 'disabled',
            'message'      => ($d['message'] ?? '') ?: 'Kode lisensi tidak terdaftar. Hubungi developer.',
            'contact'      => $d['contact'] ?? '081234567890',
            'expired_date' => $d['expired_date'] ?? '-',
            'menu_ai_soal' => false,
            '_unreg'       => true,
        ]);
    }

    $inst = &$data['installations'][$key];

    /* ---- status efektif (expired otomatis menurut tanggal) ---- */
    $status = $inst['status'] ?? 'disabled';
    $exp    = $inst['expired_date'] ?? '-';
    if ($status === 'active' && $exp !== '-' && $exp !== '' && strtotime($exp) !== false && $exp < $today) {
        $status = 'expired';
    }
    $base = [
        'status'       => $status,
        'message'      => $inst['message'] ?? '',
        'contact'      => $inst['contact'] ?? '081234567890',
        'expired_date' => $exp ?: '-',
        'menu_ai_soal' => !empty($inst['menu_ai_soal']),
    ];

    $bound = cl_norm_mac($inst['bound_mac'] ?? '');

    /* ---- sudah terikat, tetapi MAC pemanggil berbeda / tidak terbaca -> KUNCI ---- */
    if ($bound !== '' && $mac !== $bound) {
        $inst['blocked_attempts']  = (int)($inst['blocked_attempts'] ?? 0) + 1;
        $inst['last_blocked_mac']  = $mac;
        $inst['last_blocked_host'] = $host;
        $inst['last_blocked_ip']   = $ipRep !== '' ? $ipRep : $visitor;
        $inst['last_blocked_at']   = $now;
        if ($mac !== '') { // MAC kosong biasanya hanya gagal baca sesaat; jangan beri tanda "MAC berubah!"
            $inst['mac_mismatch_detected_at'] = $now;
            $inst['previous_mac']             = $bound;
        }
        // Riwayat: catat percobaan diblokir paling sering tiap 10 menit per MAC (aplikasi mengecek tiap menit).
        if (($inst['blocked_log_mac'] ?? null) !== $mac || (time() - (int)($inst['blocked_log_ts'] ?? 0)) > 600) {
            cl_log($inst, 'blocked', $mac !== '' ? "Percobaan dari komputer lain diblokir (MAC $mac)." : 'Pengecekan tanpa MAC ditolak (MAC tidak terbaca).', $now);
            $inst['blocked_log_mac'] = $mac;
            $inst['blocked_log_ts']  = time();
        }
        return $out([
            'status'       => 'disabled',
            'message'      => $mac === ''
                ? 'MAC address komputer ini tidak terbaca, sehingga lisensi tidak bisa diverifikasi. Muat ulang halaman; jika berulang, hubungi developer.'
                : 'Kode lisensi ini sudah terikat ke komputer lain, sehingga modul AI Soal dikunci di komputer ini. '
                . 'Hubungi developer jika Anda memindahkan aplikasi ke komputer baru.',
            'contact'      => $base['contact'],
            'expired_date' => $base['expired_date'],
            'menu_ai_soal' => false,
        ]);
    }

    /* ---- belum terikat: ikat ke MAC pertama yang valid (hanya kalau lisensi aktif) ---- */
    if ($bound === '') {
        if ($status === 'active' && $mac !== '') {
            $inst['bound_mac']  = $mac;
            $inst['bound_macs'] = [$mac];
            $inst['bound_host'] = $host;
            $inst['bound_at']   = $now;
            foreach (['mac_mismatch_detected_at', 'previous_mac'] as $f) unset($inst[$f]);
            cl_log($inst, 'bind', "Terikat ke perangkat baru (MAC $mac).", $now);
            $backup = true;   // pengikatan = perubahan penting: cadangkan kondisi sebelumnya
        } elseif ($status === 'active' && $mac === '') {
            // Belum ada MAC yang bisa diikat -> jangan beri akses gratis tanpa identitas.
            return $out([
                'status'       => 'disabled',
                'message'      => 'MAC address komputer tidak terbaca, lisensi belum bisa diaktifkan. Hubungi developer.',
                'contact'      => $base['contact'],
                'expired_date' => $base['expired_date'],
                'menu_ai_soal' => false,
            ]);
        }
    }

    /* ---- perangkat resmi: catat aktivitas ---- */
    $inst['last_seen_at']          = $now;
    $inst['last_seen_mac']         = $mac;
    $inst['last_seen_domain']      = $domain;
    $inst['last_seen_hostname']    = $host;
    $inst['last_seen_ip']          = $ipRep;
    $inst['last_seen_visitor_ip']  = $visitor;

    return $out($base);
}, cl_default_shell());

if ($response === null) {   // data lisensi tidak terbaca: klien memakai cache + masa tenggangnya, berkas tidak disentuh
    http_response_code(503);
    cl_reply(['error' => 'Data lisensi sementara tidak tersedia.']);
}

unset($response['_unreg']); // kode tak dikenal: cukup ditolak, tidak dicatat

cl_reply($response);?? ''); });
            $list = array_slice($list, -UNREG_MAX, null, true);
        }
        return ['data' => $list, 'value' => null];
    }, []);
    unset($response['_unreg']);
}

cl_reply($response);