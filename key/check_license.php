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
 * Berbagi berkas data dengan admin.php: licenses.json dan unregistered.json.
 * =====================================================================
 */

define('LICENSES_FILE', __DIR__ . '/licenses.json');
define('UNREG_FILE',    __DIR__ . '/unregistered.json');
define('UNREG_MAX',     200);   // batas catatan perangkat tak terdaftar

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');

/* ---------------------------------------------------------------
 * Baca-ubah-tulis berkas JSON secara atomik (dikunci) supaya dua
 * permintaan bersamaan tidak saling menimpa data.
 * --------------------------------------------------------------- */
function cl_json_update($file, callable $fn, $default) {
    $fh = @fopen($file, 'c+');
    if (!$fh) return $default;
    flock($fh, LOCK_EX);
    $raw  = stream_get_contents($fh);
    $data = json_decode($raw === false ? '' : $raw, true);
    if (!is_array($data)) $data = $default;

    $result = $fn($data);              // $fn mengubah $data lewat referensi & mengembalikan hasil
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

    $out = function ($arr) use (&$data) { return ['data' => $data, 'value' => $arr]; };

    /* ---- kode tidak dikenal / kosong ---- */
    if ($key === '' || !isset($data['installations'][$key])) {
        $d = $data['default'];
        return $out([
            'status'       => 'disabled',
            'message'      => $d['message'] ?: 'Kode lisensi tidak terdaftar. Hubungi developer.',
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

/* ---------------------------------------------------------------
 * Catat perangkat tak terdaftar (kode kosong / salah) untuk admin.php
 * --------------------------------------------------------------- */
if (!empty($response['_unreg'])) {
    cl_json_update(UNREG_FILE, function ($list) use ($key, $mac, $host, $ipRep, $domain, $visitor, $now) {
        $uid = md5($key . '|' . $mac . '|' . $visitor);
        if (!isset($list[$uid])) {
            $list[$uid] = [
                'key' => $key, 'host' => $host, 'mac' => $mac, 'ip' => $ipRep,
                'visitor_ip' => $visitor, 'domain' => $domain,
                'first_seen' => $now, 'last_seen' => $now, 'hits' => 0,
            ];
        }
        $list[$uid]['last_seen'] = $now;
        $list[$uid]['hits']      = (int)($list[$uid]['hits'] ?? 0) + 1;
        $list[$uid]['mac']       = $mac;

        if (count($list) > UNREG_MAX) { // buang yang paling lama
            uasort($list, function ($a, $b) { return strcmp($a['last_seen'] ?? '', $b['last_seen'] ?? ''); });
            $list = array_slice($list, -UNREG_MAX, null, true);
        }
        return ['data' => $list, 'value' => null];
    }, []);
    unset($response['_unreg']);
}

cl_reply($response);