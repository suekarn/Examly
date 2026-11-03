<?php
/**
 * =====================================================================
 * LICENSE SERVER — admin.php (v3, tampilan baru)
 * =====================================================================
 * Logika backend TIDAK berubah dari versi sebelumnya (field POST sama
 * persis), hanya tampilannya yang diperbarui.
 * =====================================================================
 */

// ----------------------------- AKSES -----------------------------
// Halaman admin hanya bisa dibuka dari KOMPUTER SERVER ini (127.0.0.1, atau lewat IP LAN-nya sendiri
// seperti 192.168.3.118 yang dibuka di komputer server). Perangkat lain di LAN ditolak.
// Kalau suatu saat perlu membuka dari komputer lain, tambahkan IP-nya di sini, mis. ['192.168.3.50'].
// check_license.php TIDAK dibatasi, jadi pengecekan lisensi dari aplikasi tetap jalan.
define('ADMIN_ALLOW_IPS', []);

function adm_norm_ip($ip) { $ip = (string)$ip; return strpos($ip, '::ffff:') === 0 ? substr($ip, 7) : $ip; }
function adm_client_allowed() {
    $ip  = adm_norm_ip($_SERVER['REMOTE_ADDR'] ?? '');
    $srv = adm_norm_ip($_SERVER['SERVER_ADDR'] ?? '');
    if ($ip === '127.0.0.1' || $ip === '::1') return true;
    if ($srv !== '' && $ip === $srv) return true;
    return in_array($ip, ADMIN_ALLOW_IPS, true);
}
if (!adm_client_allowed()) {
    http_response_code(403);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>403</title></head>'
       . '<body style="font-family:sans-serif;background:#07130f;color:#eafff8;display:flex;align-items:center;justify-content:center;min-height:100vh">'
       . '<div style="text-align:center"><h1 style="font-size:20px">Akses ditolak</h1>'
       . '<p style="color:#7fb3a3;margin-top:8px;font-size:14px">Halaman admin lisensi hanya bisa dibuka dari komputer server.</p></div></body></html>';
    exit;
}

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();

// Hash kata sandi bawaan. Setelah Anda mengganti kata sandi lewat tombol "Kata sandi" di panel,
// hash baru disimpan di admin_auth.php dan hash bawaan ini tidak dipakai lagi.
$ADMIN_PASSWORD_HASH = '$2b$12$CnAvVa9sIveDwO2ha5ulJuCiTTHqIwD.fdVFOPo4sk5xaSrwt2F9.';

define('LICENSES_FILE', __DIR__ . '/licenses.json');
define('AUTH_FILE',     __DIR__ . '/admin_auth.php');
define('BACKUP_DIR',    __DIR__ . '/backup');
define('BACKUP_KEEP',   10);   // jumlah cadangan terakhir yang disimpan
define('HISTORY_KEEP',  100);  // jumlah catatan riwayat per lisensi

// ----------------------------- DATA LISENSI -----------------------------
function adm_default_shell() {
    return ['installations' => [], 'default' => [
        'status' => 'disabled', 'message' => '', 'contact' => '081234567890',
        'expired_date' => '-', 'menu_ai_soal' => false,
    ]];
}

/** Baca mentah licenses.json dengan kunci baca. null = berkas belum ada, false = gagal dibaca. */
function adm_read_raw() {
    for ($i = 0; $i < 6; $i++) {
        $fh = @fopen(LICENSES_FILE, 'rb');
        if ($fh) {
            if (flock($fh, LOCK_SH)) {
                $raw = stream_get_contents($fh);
                flock($fh, LOCK_UN); fclose($fh);
                return $raw === false ? '' : $raw;
            }
            fclose($fh);
        } elseif (!is_file(LICENSES_FILE)) {
            return null;
        }
        usleep(100000);
    }
    return false;
}

function adm_load() {
    $raw = adm_read_raw();
    if ($raw === null) return adm_default_shell();                 // belum ada berkas: instalasi baru
    $data = ($raw === false) ? null : json_decode($raw, true);
    if (!is_array($data)) {
        // Berkas ada tetapi tidak terbaca / rusak: JANGAN dianggap kosong, supaya tidak menimpa data asli.
        if ($raw === false || trim((string)$raw) !== '') $GLOBALS['ADM_LOAD_ERROR'] = true;
        return adm_default_shell();
    }
    $data['installations'] = $data['installations'] ?? [];
    $data['default']       = $data['default']       ?? adm_default_shell()['default'];
    return $data;
}

/** Tulis licenses.json (terkunci). Return true bila berhasil. */
function adm_write_file($data) {
    $fh = @fopen(LICENSES_FILE, 'c+');
    if (!$fh) return false;
    if (!flock($fh, LOCK_EX)) { fclose($fh); return false; }
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    ftruncate($fh, 0); rewind($fh);
    $ok = fwrite($fh, $json) !== false;
    fflush($fh); flock($fh, LOCK_UN); fclose($fh);
    return $ok;
}

// ----------------------------- CADANGAN OTOMATIS -----------------------------
function adm_backup_prepare() {
    if (!is_dir(BACKUP_DIR)) @mkdir(BACKUP_DIR, 0755, true);
    if (!is_dir(BACKUP_DIR)) return false;
    $ht = BACKUP_DIR . '/.htaccess';
    if (!is_file($ht)) @file_put_contents($ht, "<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n    Order allow,deny\n    Deny from all\n</IfModule>\n");
    if (!is_file(BACKUP_DIR . '/index.html')) @file_put_contents(BACKUP_DIR . '/index.html', '');
    return true;
}
function adm_backup_key($path) {
    if (preg_match('/licenses-(\d{8})-(\d{6})(?:-(\d+))?\.json$/', $path, $m)) return $m[1] . $m[2] . sprintf('%03d', (int)($m[3] ?? 0));
    return '0';
}
/** Daftar berkas cadangan, terbaru dulu. */
function adm_backup_files() {
    $f = glob(BACKUP_DIR . '/licenses-*.json') ?: [];
    usort($f, function ($a, $b) { return strcmp(adm_backup_key($b), adm_backup_key($a)); });
    return $f;
}
/** Salin kondisi SEBELUM perubahan ke backup/. Data kosong tidak dicadangkan (supaya cadangan bagus tidak tergeser). */
function adm_backup($raw) {
    $raw = (string)$raw;
    if (trim($raw) === '') return false;
    $d = json_decode($raw, true);
    if (!is_array($d) || empty($d['installations'])) return false;
    if (!adm_backup_prepare()) return false;
    $files = adm_backup_files();
    if ($files && @file_get_contents($files[0]) === $raw) return false;   // sama dengan cadangan terbaru
    $base = BACKUP_DIR . '/licenses-' . date('Ymd-His');
    $name = $base . '.json'; $i = 1;
    while (is_file($name)) { $i++; $name = $base . '-' . $i . '.json'; }
    if (@file_put_contents($name, $raw) === false) return false;
    foreach (array_slice(adm_backup_files(), BACKUP_KEEP) as $old) @unlink($old);
    return true;
}
function adm_backup_label($path) {
    if (preg_match('/licenses-(\d{4})(\d{2})(\d{2})-(\d{2})(\d{2})(\d{2})/', $path, $m)) return "$m[1]-$m[2]-$m[3] $m[4]:$m[5]:$m[6]";
    return basename($path);
}

function adm_save($data) {
    if (!empty($GLOBALS['ADM_LOAD_ERROR'])) return false;   // data asli tak terbaca: jangan menimpa
    adm_backup(adm_read_raw());
    return adm_write_file($data);
}

/** Tulis pengganti penuh dari JSON (impor / pulihkan). Return true atau string pesan galat. */
function adm_install_json($json) {
    $d = json_decode((string)$json, true);
    if (!is_array($d) || !isset($d['installations']) || !is_array($d['installations'])) return 'Berkas bukan data lisensi yang valid.';
    $d['default'] = $d['default'] ?? adm_default_shell()['default'];
    adm_backup(adm_read_raw());
    return adm_write_file($d) ? true : 'Gagal menulis licenses.json (cek izin folder).';
}

// ----------------------------- RIWAYAT -----------------------------
function adm_log(&$data, $key, $event, $detail) {
    if (!isset($data['installations'][$key])) return;
    $h = $data['installations'][$key]['history'] ?? [];
    $h[] = ['t' => date('Y-m-d H:i:s'), 'e' => $event, 'd' => $detail];
    $data['installations'][$key]['history'] = array_slice($h, -HISTORY_KEEP);
}
function adm_js($v) { return json_encode($v, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG | JSON_UNESCAPED_UNICODE); }

// ----------------------------- MASA BERLAKU -----------------------------
/** Sisa hari sampai tanggal expired (0 = hari ini hari terakhir, negatif = sudah lewat). null = tanpa batas. */
function adm_days_left($exp) {
    $exp = trim((string)$exp);
    if ($exp === '' || $exp === '-') return null;
    $t = strtotime($exp . ' 00:00:00');
    if ($t === false) return null;
    return (int)floor(($t - strtotime(date('Y-m-d') . ' 00:00:00')) / 86400);
}
function adm_effective_status($info) {
    $s = $info['status'] ?? 'disabled';
    if ($s === 'active') { $dl = adm_days_left($info['expired_date'] ?? '-'); if ($dl !== null && $dl < 0) return 'expired'; }
    return $s;
}
/** Tambah N bulan ke tanggal (Y-m-d) tanpa meloncat bulan (31 Jan + 1 bln = 28/29 Feb). */
function adm_add_months($date, $n) {
    $d = new DateTime($date);
    $day = (int)$d->format('j');
    $d->modify('first day of this month')->modify('+' . (int)$n . ' month');
    $d->setDate((int)$d->format('Y'), (int)$d->format('n'), min($day, (int)$d->format('t')));
    return $d->format('Y-m-d');
}

function adm_generate_key() {
    $parts = [];
    for ($i = 0; $i < 4; $i++) $parts[] = strtoupper(bin2hex(random_bytes(2)));
    return 'GCBT-' . implode('-', $parts);
}

// ----------------------------- KATA SANDI -----------------------------
function adm_pass_hash() {
    global $ADMIN_PASSWORD_HASH;
    if (is_file(AUTH_FILE)) {
        if (function_exists('opcache_invalidate')) @opcache_invalidate(AUTH_FILE, true);
        $h = @include AUTH_FILE;
        if (is_string($h) && $h !== '') return $h;
    }
    return $ADMIN_PASSWORD_HASH;
}
function adm_pass_is_default() { return !is_file(AUTH_FILE); }
function adm_pass_save($plain) {
    $hash = password_hash($plain, PASSWORD_DEFAULT);
    $ok = @file_put_contents(AUTH_FILE, "<?php\n// Dibuat otomatis oleh admin.php. Jangan dibagikan.\nreturn " . var_export($hash, true) . ";\n", LOCK_EX) !== false;
    if (function_exists('opcache_invalidate')) @opcache_invalidate(AUTH_FILE, true);
    return $ok;
}

// ----------------------------- PERLINDUNGAN FILE DATA -----------------------------
// Cegah licenses.json, cadangan, dll. diunduh langsung lewat browser (http://.../key/licenses.json).
if (!is_file(__DIR__ . '/.htaccess')) {
    @file_put_contents(__DIR__ . '/.htaccess',
        "# Dibuat otomatis oleh admin.php: lindungi data lisensi dari unduhan langsung\n"
      . "Options -Indexes\n"
      . "<FilesMatch \"\\.(json|log|bak|txt)$\">\n"
      . "    <IfModule mod_authz_core.c>\n        Require all denied\n    </IfModule>\n"
      . "    <IfModule !mod_authz_core.c>\n        Order allow,deny\n        Deny from all\n    </IfModule>\n"
      . "</FilesMatch>\n");
}

// ----------------------------- LOGIN -----------------------------
$loginError = '';
if (isset($_POST['login_password'])) {
    $nowL = time();
    if (($_SESSION['login_lock_until'] ?? 0) > $nowL) {
        $loginError = 'Terlalu banyak percobaan. Coba lagi dalam ' . ($_SESSION['login_lock_until'] - $nowL) . ' detik.';
    } elseif (password_verify($_POST['login_password'], adm_pass_hash())) {
        session_regenerate_id(true);
        $_SESSION['ls_admin'] = true;
        $_SESSION['login_fails'] = 0;
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    } else {
        sleep(1);
        $_SESSION['login_fails'] = ($_SESSION['login_fails'] ?? 0) + 1;
        if ($_SESSION['login_fails'] >= 5) { $_SESSION['login_lock_until'] = $nowL + 60; $_SESSION['login_fails'] = 0; }
        $loginError = 'Password salah.';
    }
}
if (isset($_GET['logout'])) { session_destroy(); header('Location: admin.php'); exit; }

if (empty($_SESSION['ls_admin'])) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — License Server</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{
            min-height:100vh;display:flex;align-items:center;justify-content:center;
            font-family:'Plus Jakarta Sans',sans-serif;
            background:
                radial-gradient(circle at 20% 20%, rgba(0,255,178,.10), transparent 35%),
                radial-gradient(circle at 80% 75%, rgba(0,200,255,.08), transparent 35%),
                linear-gradient(160deg,#040907 0%,#07130f 55%,#040907 100%);
            color:#eafff8;padding:20px;
        }
        .box{
            width:100%;max-width:360px;
            background:linear-gradient(180deg,rgba(10,30,24,.85),rgba(6,18,15,.95));
            border:1px solid rgba(0,255,178,.25);
            border-radius:20px;padding:36px 30px;
            box-shadow:0 25px 70px rgba(0,0,0,.55),0 0 50px rgba(0,255,178,.06);
            text-align:center;
        }
        .logo{
            width:56px;height:56px;margin:0 auto 18px;border-radius:16px;
            background:linear-gradient(135deg,#00e5a8,#00b4d8);
            display:flex;align-items:center;justify-content:center;font-size:24px;color:#031b14;
            box-shadow:0 0 30px rgba(0,229,168,.4);
        }
        h1{font-size:17px;font-weight:800;margin-bottom:4px}
        p.sub{font-size:12.5px;color:#7fb3a3;margin-bottom:24px}
        .field{position:relative;margin-bottom:16px;text-align:left}
        input{
            width:100%;padding:13px 14px 13px 42px;border-radius:12px;
            border:1px solid rgba(0,255,178,.22);background:#041310;color:#fff;font-size:14px;
            font-family:inherit;outline:none;transition:border-color .2s;
        }
        input:focus{border-color:#00e5a8}
        .field i{position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#4b8a78;font-size:13px}
        button{
            width:100%;padding:13px;border:none;border-radius:12px;cursor:pointer;
            background:linear-gradient(135deg,#00e5a8,#00b4d8);color:#031b14;
            font-weight:800;font-size:14px;font-family:inherit;
            box-shadow:0 10px 25px rgba(0,229,168,.25);transition:filter .15s;
        }
        button:hover{filter:brightness(1.08)}
        .err{color:#ff6b9d;font-size:12.5px;margin-top:14px}
    </style></head><body>
    <div class="box">
        <div class="logo"><i class="fa-solid fa-shield-halved"></i></div>
        <h1>License Server</h1>
        <p class="sub">Masuk untuk kelola lisensi instalasi</p>
        <form method="post">
            <div class="field">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="login_password" placeholder="Password admin" required autofocus>
            </div>
            <button type="submit">Masuk <i class="fa-solid fa-arrow-right-long" style="margin-left:6px"></i></button>
        </form>
        <?php if (!empty($loginError)): ?><p class="err"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($loginError) ?></p><?php endif; ?>
    </div>
    </body></html>
    <?php
    exit;
}

// ----------------------------- HELPER: terakhir aktif -----------------------------
const ONLINE_WINDOW = 300; // detik: dianggap "aktif" jika terakhir lapor <= 5 menit lalu
function adm_ts($v) {
    if ($v === null || $v === '' || $v === '-') return null;
    if (is_numeric($v)) { $n = (float)$v; return (int)($n > 99999999999 ? $n / 1000 : $n); }
    $t = strtotime((string)$v);
    return $t === false ? null : $t;
}
function adm_ago($ts, $now) {
    if (!$ts) return 'Belum pernah';
    $d = $now - $ts;
    if ($d < 60) return 'Baru saja';
    if ($d < 3600) return floor($d / 60) . ' menit lalu';
    if ($d < 86400) return floor($d / 3600) . ' jam lalu';
    if ($d < 86400 * 30) return floor($d / 86400) . ' hari lalu';
    return date('Y-m-d', $ts);
}

// ----------------------------- EKSPOR (unduh licenses.json) -----------------------------
if (isset($_GET['export'])) {
    $rawExp = adm_read_raw();
    if (!is_string($rawExp) || trim($rawExp) === '') { http_response_code(404); echo 'Belum ada data lisensi untuk diekspor.'; exit; }
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="licenses-' . date('Ymd-His') . '.json"');
    header('Cache-Control: no-store');
    echo $rawExp;
    exit;
}

// ----------------------------- ACTIONS -----------------------------
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
$data = adm_load();
$notice = '';
$newGeneratedKey = '';
$editTarget = ''; // kalau abis save/generate, modal otomatis kebuka lagi

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!hash_equals($_SESSION['csrf'], (string)($_POST['csrf'] ?? ''))) {
        $notice = 'Sesi tidak valid. Muat ulang halaman lalu coba lagi.';
    } elseif ($_POST['action'] === 'save_installation') {
        $key = trim($_POST['license_key'] ?? '');
        if ($key !== '') {
            $isNew    = !isset($data['installations'][$key]);
            $existing = $data['installations'][$key] ?? [];
            $pd       = trim($_POST['purchase_date'] ?? '');
            $data['installations'][$key] = array_merge($existing, [
                'label'            => ($_POST['label'] ?? '') ?: ($existing['label'] ?? ''),
                'domain'           => ($_POST['domain'] ?? '') ?: ($existing['domain'] ?? ''),
                'status'           => $_POST['status'] ?? 'disabled',
                'message'          => $_POST['message'] ?? '',
                'contact'          => $_POST['contact'] ?? '081234567890',
                'expired_date'     => ($_POST['expired_date'] ?? '') ?: '-',
                'menu_ai_soal'     => isset($_POST['menu_ai_soal']),
                'school'           => substr(trim($_POST['school'] ?? ''), 0, 120),
                'school_contact'   => substr(trim($_POST['school_contact'] ?? ''), 0, 80),
                'purchase_date'    => ($pd !== '' && strtotime($pd) !== false) ? date('Y-m-d', strtotime($pd)) : '',
                'last_seen_domain' => $existing['last_seen_domain'] ?? null,
                'last_seen_at'     => $existing['last_seen_at'] ?? null,
            ]);
            adm_log($data, $key, $isNew ? 'created' : 'edited', $isNew ? 'Lisensi dibuat.' : 'Data lisensi diubah.');
            $notice = adm_save($data) ? "Instalasi '$key' berhasil disimpan." : 'Gagal menyimpan: data lisensi tidak terbaca. Pulihkan dari Cadangan.';
        }
    } elseif ($_POST['action'] === 'delete_installation') {
        $key = $_POST['license_key'] ?? '';
        unset($data['installations'][$key]);
        $notice = adm_save($data) ? "Kode lisensi '$key' dihapus." : 'Gagal menyimpan: data lisensi tidak terbaca. Pulihkan dari Cadangan.';
    } elseif ($_POST['action'] === 'toggle_disable') {
        $key = $_POST['license_key'] ?? '';
        if (isset($data['installations'][$key])) {
            $current = $data['installations'][$key]['status'] ?? 'disabled';
            $data['installations'][$key]['status'] = ($current === 'disabled') ? 'active' : 'disabled';
            adm_log($data, $key, $current === 'disabled' ? 'enabled' : 'disabled', $current === 'disabled' ? 'Diaktifkan kembali.' : 'Dinonaktifkan.');
            $ok = adm_save($data);
            $notice = !$ok ? 'Gagal menyimpan: data lisensi tidak terbaca.'
                : (($current === 'disabled') ? "Instalasi '$key' diaktifkan kembali." : "Instalasi '$key' dinonaktifkan.");
        }
    } elseif ($_POST['action'] === 'reset_binding') {
        $key = $_POST['license_key'] ?? '';
        if (isset($data['installations'][$key])) {
            $oldMac = $data['installations'][$key]['bound_mac'] ?? '';
            foreach (['bound_mac','bound_macs','mac_changed_at','bound_host','bound_at','blocked_attempts','last_blocked_mac','last_blocked_host','last_blocked_ip','last_blocked_at','mac_mismatch_detected_at','previous_mac','blocked_log_mac','blocked_log_ts'] as $f) {
                unset($data['installations'][$key][$f]);
            }
            adm_log($data, $key, 'unbind', 'Ikatan perangkat dilepas' . ($oldMac ? " (sebelumnya $oldMac)." : '.'));
            $notice = adm_save($data) ? "Ikatan perangkat untuk '$key' dilepas. Perangkat berikutnya yang mengecek akan terikat." : 'Gagal menyimpan: data lisensi tidak terbaca.';
        }
    } elseif ($_POST['action'] === 'extend_license') {
        $key = $_POST['license_key'] ?? '';
        $months = (int)($_POST['months'] ?? 0);
        if (isset($data['installations'][$key]) && in_array($months, [1, 12], true)) {
            $inst = &$data['installations'][$key];
            $cur  = $inst['expired_date'] ?? '-';
            if (adm_days_left($cur) === null) {
                $notice = "Lisensi '$key' tanpa batas waktu (tanggal expired kosong), tidak perlu diperpanjang.";
            } else {
                $today = date('Y-m-d');
                $base  = (date('Y-m-d', strtotime($cur)) > $today) ? date('Y-m-d', strtotime($cur)) : $today; // sudah lewat: hitung dari hari ini
                $inst['expired_date'] = adm_add_months($base, $months);
                if (($inst['status'] ?? '') === 'expired') $inst['status'] = 'active';
                $new = $inst['expired_date'];
                unset($inst);
                adm_log($data, $key, 'extended', 'Diperpanjang ' . ($months === 12 ? '1 tahun' : '1 bulan') . " (sampai $new).");
                $notice = adm_save($data) ? "Masa berlaku '$key' diperpanjang sampai $new." : 'Gagal menyimpan: data lisensi tidak terbaca.';
            }
        }
    } elseif ($_POST['action'] === 'change_password') {
        $old = (string)($_POST['old_pass'] ?? ''); $n1 = (string)($_POST['new_pass'] ?? ''); $n2 = (string)($_POST['new_pass2'] ?? '');
        if (!password_verify($old, adm_pass_hash()))      $notice = 'Kata sandi lama salah.';
        elseif (strlen($n1) < 8)                          $notice = 'Kata sandi baru minimal 8 karakter.';
        elseif ($n1 !== $n2)                              $notice = 'Ulangi kata sandi tidak sama.';
        elseif ($n1 === '000000')                         $notice = 'Kata sandi baru tidak boleh sama dengan kata sandi bawaan.';
        else $notice = adm_pass_save($n1) ? 'Kata sandi berhasil diganti.' : 'Gagal menyimpan kata sandi (cek izin tulis folder key).';
    } elseif ($_POST['action'] === 'import_licenses') {
        $f = $_FILES['import_file'] ?? null;
        if (!$f || ($f['error'] ?? 1) !== UPLOAD_ERR_OK) $notice = 'Pilih berkas licenses.json terlebih dulu.';
        elseif (($f['size'] ?? 0) > 2 * 1024 * 1024)       $notice = 'Berkas terlalu besar (maksimal 2 MB).';
        else {
            $r = adm_install_json(file_get_contents($f['tmp_name']));
            $notice = ($r === true) ? 'Data lisensi berhasil diimpor. Kondisi sebelumnya tersimpan di cadangan.' : $r;
        }
    } elseif ($_POST['action'] === 'restore_backup') {
        $fn = basename((string)($_POST['backup_file'] ?? ''));
        if (!preg_match('/^licenses-\d{8}-\d{6}(-\d+)?\.json$/', $fn) || !is_file(BACKUP_DIR . '/' . $fn)) $notice = 'Cadangan tidak ditemukan.';
        else {
            $r = adm_install_json(file_get_contents(BACKUP_DIR . '/' . $fn));
            $notice = ($r === true) ? 'Cadangan ' . adm_backup_label($fn) . ' dipulihkan. Kondisi sebelumnya tersimpan di cadangan.' : $r;
        }
    } elseif ($_POST['action'] === 'generate_key') {
        $newGeneratedKey = adm_generate_key();
    }
    $data = adm_load();
}

// Statistik ringkas (status efektif: lisensi "active" yang tanggalnya lewat dihitung Expired)
$total = count($data['installations']);
$countByStatus = ['active' => 0, 'expired' => 0, 'disabled' => 0];
$menuOnCount = 0;
foreach ($data['installations'] as $info) {
    $s = adm_effective_status($info);
    if (isset($countByStatus[$s])) $countByStatus[$s]++;
    if (!empty($info['menu_ai_soal'])) $menuOnCount++;
}

// Jumlah komputer yang sudah terikat ke sebuah kode lisensi
$totalUsers = 0;
foreach ($data['installations'] as $info) { if (!empty($info['bound_mac'])) $totalUsers++; }

$backupList = [];
foreach (adm_backup_files() as $bf) {
    $bd = json_decode((string)@file_get_contents($bf), true);
    $backupList[] = ['file' => basename($bf), 'label' => adm_backup_label($bf), 'count' => is_array($bd) ? count($bd['installations'] ?? []) : 0, 'size' => (int)@filesize($bf)];
}
$passIsDefault = adm_pass_is_default();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>License Server — Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script>
    // Terapkan tema SEBELUM CSS/HTML dirender, supaya tidak ada "kedipan"
    // tema lama sekilas saat halaman dibuka.
    (function(){
        var saved = localStorage.getItem('ls_admin_theme');
        if (saved === 'light') document.documentElement.setAttribute('data-theme', 'light');
    })();
</script>
<style>
    *{box-sizing:border-box;margin:0;padding:0}
    html{transition:background-color .25s ease}
    :root{
        --accent:#00e5a8; --accent2:#00b4d8; --pink:#ff4f9a;
        --bg:#050a08; --panel:#0a1a15; --panel2:#0d211b; --border:rgba(0,229,168,.16);
        --text:#eafff8; --muted:#6f9c8c;
        --input-bg:#041310; --input-text:#ffffff;
        --th-bg:rgba(0,229,168,.05); --row-hover:rgba(0,229,168,.04);
        --shadow-soft:rgba(0,0,0,.55);
    }
    :root[data-theme="light"]{
        --bg:#f4faf7; --panel:#ffffff; --panel2:#eef8f4; --border:rgba(0,150,110,.18);
        --text:#0b241c; --muted:#5c8276;
        --input-bg:#ffffff; --input-text:#0b241c;
        --th-bg:rgba(0,150,110,.06); --row-hover:rgba(0,150,110,.05);
        --shadow-soft:rgba(80,130,110,.18);
    }
    body, *{transition:background-color .25s ease, border-color .25s ease, color .25s ease}
    body{
        font-family:'Plus Jakarta Sans',sans-serif;background:
            radial-gradient(circle at 10% 0%, rgba(0,229,168,.07), transparent 30%),
            radial-gradient(circle at 90% 90%, rgba(0,180,216,.05), transparent 30%),
            var(--bg);
        color:var(--text);min-height:100vh;padding:28px 28px 60px;
    }

    /* ---------- Theme toggle switch ---------- */
    .theme-toggle{
        display:flex;align-items:center;gap:0;background:var(--panel2);border:1px solid var(--border);
        border-radius:30px;padding:4px;position:relative;cursor:pointer;width:64px;height:34px;
    }
    .theme-toggle .knob{
        width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent2));
        display:flex;align-items:center;justify-content:center;color:#031b14;font-size:12px;
        transition:transform .25s cubic-bezier(.4,0,.2,1);transform:translateX(0);
        box-shadow:0 4px 10px rgba(0,229,168,.3);
    }
    :root[data-theme="light"] .theme-toggle .knob{transform:translateX(30px)}
    .theme-toggle .icons{position:absolute;inset:0;display:flex;align-items:center;justify-content:space-between;padding:0 9px;font-size:11px;color:var(--muted);pointer-events:none}
    .mono{font-family:'JetBrains Mono',monospace}

    /* ---------- Topbar ---------- */
    .topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:14px}
    .brand{display:flex;align-items:center;gap:12px}
    .brand .logo{
        width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;
        background:linear-gradient(135deg,var(--accent),var(--accent2));color:#031b14;font-size:19px;
        box-shadow:0 0 25px rgba(0,229,168,.35);
    }
    .brand h1{font-size:18px;font-weight:800}
    .brand p{font-size:12px;color:var(--muted)}
    .btn{
        display:inline-flex;align-items:center;gap:7px;padding:10px 16px;border-radius:10px;border:none;
        font-family:inherit;font-weight:700;font-size:13px;cursor:pointer;text-decoration:none;transition:filter .15s,transform .1s;
    }
    .btn:active{transform:scale(.97)}
    .btn-primary{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#031b14;box-shadow:0 8px 20px rgba(0,229,168,.25)}
    .btn-primary:hover{filter:brightness(1.08)}
    .btn-ghost{background:transparent;border:1px solid var(--border);color:var(--accent)}
    .btn-ghost:hover{background:rgba(0,229,168,.08)}
    .btn-danger{background:rgba(255,79,154,.15);color:#ff8ab8;border:1px solid rgba(255,79,154,.3)}
    .btn-danger:hover{background:rgba(255,79,154,.25)}
    .btn-logout{color:var(--pink);font-size:12.5px;display:flex;align-items:center;gap:6px}

    /* ---------- Stat cards ---------- */
    .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:14px;margin-bottom:26px}
    .stat-card{
        background:linear-gradient(160deg,var(--panel2),var(--panel));border:1px solid var(--border);
        border-radius:16px;padding:18px 20px;position:relative;overflow:hidden;
    }
    .stat-card .icon{font-size:17px;opacity:.85;margin-bottom:10px}
    .stat-card .num{font-size:26px;font-weight:800;line-height:1}
    .stat-card .label{font-size:11.5px;color:var(--muted);margin-top:4px;text-transform:uppercase;letter-spacing:.5px}
    .stat-card.c-total .icon{color:var(--accent)}
    .stat-card.c-active .icon{color:#21f6b0}
    .stat-card.c-expired .icon{color:#ffd84d}
    .stat-card.c-disabled .icon{color:#ff4f9a}

    /* ---------- Panel / table ---------- */
    .panel{background:var(--panel);border:1px solid var(--border);border-radius:18px;overflow:hidden;margin-bottom:24px}
    .panel-head{display:flex;align-items:center;justify-content:space-between;padding:18px 20px;border-bottom:1px solid var(--border);flex-wrap:wrap;gap:12px}
    .panel-head h2{font-size:14.5px;font-weight:700}
    .search-box{position:relative}
    .search-box i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:12px}
    .search-box input{
        padding:9px 14px 9px 34px;border-radius:9px;border:1px solid var(--border);background:var(--input-bg);
        color:var(--input-text);font-size:12.5px;font-family:inherit;width:220px;outline:none;
    }
    .search-box input:focus{border-color:var(--accent)}

    table{width:100%;border-collapse:collapse;font-size:12.8px}
    th{text-align:left;padding:11px 16px;background:var(--th-bg);color:var(--muted);font-weight:700;font-size:11px;text-transform:uppercase;letter-spacing:.4px;white-space:nowrap}
    td{padding:12px 16px;border-top:1px solid var(--border);vertical-align:middle}
    tr:hover td{background:var(--row-hover)}

    .keycell{display:flex;align-items:center;gap:8px}
    .keycell code{background:var(--input-bg);padding:4px 9px;border-radius:7px;color:var(--accent);font-size:11.5px}
    .copy-btn{background:none;border:none;color:var(--muted);cursor:pointer;font-size:12px;padding:4px;border-radius:5px}
    .copy-btn:hover{color:var(--accent);background:rgba(0,229,168,.08)}

    .badge{display:inline-flex;align-items:center;gap:5px;padding:4px 11px;border-radius:20px;font-size:11px;font-weight:700;white-space:nowrap}
    .badge.active{background:rgba(33,246,176,.13);color:#21f6b0}
    .badge.expired{background:rgba(255,216,77,.13);color:#ffd84d}
    .badge.disabled{background:rgba(255,79,154,.13);color:#ff8ab8}
    .badge.menu-on{background:rgba(0,180,216,.13);color:#5ad6f0}
    .badge.menu-off{background:rgba(255,255,255,.06);color:#7a8f88}
    .badge i{font-size:9px}

    .row-actions{display:flex;gap:6px}
    .icon-btn{width:30px;height:30px;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--muted);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;transition:.15s}
    .icon-btn:hover{color:var(--accent);border-color:var(--accent);background:rgba(0,229,168,.08)}
    .icon-btn.del:hover{color:#ff8ab8;border-color:#ff4f9a;background:rgba(255,79,154,.08)}

    .empty-state{padding:50px 20px;text-align:center;color:var(--muted)}
    .empty-state i{font-size:30px;margin-bottom:12px;opacity:.5;display:block}

    /* ---------- Modal ---------- */
    .modal-overlay{
        position:fixed;inset:0;background:rgba(2,8,6,.72);backdrop-filter:blur(3px);
        display:none;align-items:center;justify-content:center;z-index:1000;padding:20px;
    }
    .modal-overlay.show{display:flex}
    .modal{
        width:100%;max-width:480px;max-height:90vh;overflow-y:auto;
        background:linear-gradient(170deg,var(--panel2),var(--panel));
        border:1px solid var(--border);border-radius:18px;padding:26px 26px 22px;
        box-shadow:0 30px 80px var(--shadow-soft);
    }
    .modal-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
    .modal-head h3{font-size:15.5px;font-weight:800}
    .modal-head button{background:none;border:none;color:var(--muted);font-size:16px;cursor:pointer}
    .modal-head button:hover{color:#fff}

    label{display:block;font-size:11.5px;color:var(--muted);margin-bottom:6px;margin-top:14px;font-weight:600;text-transform:uppercase;letter-spacing:.3px}
    label:first-of-type{margin-top:0}
    input[type=text], select, textarea{
        width:100%;padding:10px 12px;border-radius:9px;border:1px solid var(--border);
        background:var(--input-bg);color:var(--input-text);font-size:13px;font-family:inherit;outline:none;transition:border-color .15s;
    }
    input[type=text]:focus, select:focus, textarea:focus{border-color:var(--accent)}
    textarea{resize:vertical}
    .genrow{display:flex;gap:8px}
    .genrow input{flex:1}
    .genrow button{white-space:nowrap}

    .check-field{display:flex;align-items:center;gap:10px;margin-top:16px;padding:12px 14px;background:rgba(0,180,216,.06);border:1px solid rgba(0,180,216,.18);border-radius:10px}
    .check-field input{width:auto}
    .check-field span{font-size:12.5px;color:var(--text);font-weight:600}

    .modal-footer{display:flex;justify-content:flex-end;gap:10px;margin-top:22px}

    /* ---------- Toast ---------- */
    .toast{
        position:fixed;top:20px;right:20px;z-index:2000;
        background:linear-gradient(135deg,rgba(0,229,168,.95),rgba(0,180,216,.95));
        color:#031b14;padding:13px 20px;border-radius:12px;font-weight:700;font-size:13px;
        box-shadow:0 15px 40px rgba(0,0,0,.3);display:flex;align-items:center;gap:10px;
        animation:toastIn .35s ease, toastOut .35s ease 3.2s forwards;
    }
    @keyframes toastIn{from{transform:translateY(-16px);opacity:0}to{transform:translateY(0);opacity:1}}
    @keyframes toastOut{to{transform:translateY(-16px);opacity:0}}

    @media (max-width:640px){
        .search-box input{width:140px}
        table{font-size:11.5px}
        th,td{padding:9px 10px}
    }
    /* ===== Keterbacaan: ukuran huruf lebih besar (semua mode) ===== */
    body{font-size:15px;line-height:1.5;font-weight:500}
    table{font-size:14.5px}
    th{font-size:12.5px;letter-spacing:.3px}
    td{padding:15px 16px}
    .stat-card .label{font-size:12.5px}
    .stat-card .num{font-size:30px}
    .brand p{font-size:13px}
    .panel-head h2{font-size:16px}
    .keycell code{font-size:13.5px}
    .badge{font-size:12.5px;padding:5px 12px}
    .search-box input{font-size:14px}
    .icon-btn{width:34px;height:34px;font-size:13.5px}
    td .mono, .mono{font-size:13.5px}
    label{font-size:12.5px}
    input[type=text], select, textarea{font-size:14.5px}

    /* ===== Mode siang: nyaman untuk mata, kontras tinggi, tanpa silau ===== */
    :root[data-theme="light"]{
        --bg:#e8eeea; --panel:#f8faf9; --panel2:#f0f4f2; --border:rgba(20,60,45,.22);
        --text:#101c17; --muted:#33493f;
        --input-bg:#ffffff; --input-text:#101c17;
        --th-bg:#dfe8e3; --row-hover:rgba(20,60,45,.06);
        --shadow-soft:rgba(30,60,50,.18);
    }
    :root[data-theme="light"] body{background:var(--bg)}
    :root[data-theme="light"] .stat-card{background:var(--panel)}
    :root[data-theme="light"] .keycell code{color:#004d38;background:#e0f1ea;border:1px solid rgba(0,100,70,.35);font-weight:700}
    :root[data-theme="light"] .copy-btn{color:#33493f}
    :root[data-theme="light"] .copy-btn:hover{color:#004d38;background:rgba(0,120,85,.12)}
    :root[data-theme="light"] .stat-card .icon{opacity:1}
    :root[data-theme="light"] .stat-card.c-total .icon{color:#007757}
    :root[data-theme="light"] .stat-card.c-active .icon{color:#008a62}
    :root[data-theme="light"] .stat-card.c-expired .icon{color:#a86f00}
    :root[data-theme="light"] .stat-card.c-disabled .icon{color:#c01a60}
    :root[data-theme="light"] .badge.active{background:#d3efe3;color:#004d38}
    :root[data-theme="light"] .badge.expired{background:#f6e6b8;color:#6b4600}
    :root[data-theme="light"] .badge.disabled{background:#f6d3e2;color:#8f0f47}
    :root[data-theme="light"] .badge.menu-on{background:#d0eaf4;color:#004f6b}
    :root[data-theme="light"] .badge.menu-off{background:#dde3e0;color:#3b4a44}
    :root[data-theme="light"] .btn-ghost{color:#004d38;border-color:rgba(0,100,70,.4)}
    :root[data-theme="light"] .btn-danger{color:#8f0f47;background:#f6d3e2;border-color:rgba(160,20,80,.4)}
    :root[data-theme="light"] .btn-logout{color:#a8124f}
    :root[data-theme="light"] .icon-btn{color:#2a3d34;border-color:rgba(20,60,45,.35)}
    :root[data-theme="light"] .icon-btn:hover{color:#004d38;border-color:#007757;background:rgba(0,120,85,.1)}
    :root[data-theme="light"] .icon-btn.del:hover{color:#8f0f47;border-color:#c01a60;background:rgba(192,26,96,.1)}
    :root[data-theme="light"] .modal-head button:hover{color:#101c17}
    :root[data-theme="light"] th{color:#1f332a}
    :root[data-theme="light"] td i.fa-solid{color:#00607f !important}
    :root[data-theme="light"] h2 i.fa-list,
    :root[data-theme="light"] .modal-head h3 i{color:#007757 !important}
    :root[data-theme="light"] .search-box input,
    :root[data-theme="light"] input[type=text],
    :root[data-theme="light"] select,
    :root[data-theme="light"] textarea{border-color:rgba(20,60,45,.4)}
    :root[data-theme="light"] ::placeholder{color:#5b6d65;opacity:1}
    /* ===== Pencarian: awalnya hanya ikon kaca, klik untuk membuka kolom ===== */
    .sbox{display:flex;align-items:center;gap:8px}
    .sbox .sbtn{
        width:44px;height:44px;border-radius:50%;border:1.5px solid var(--border);background:var(--input-bg);
        color:var(--muted);cursor:pointer;display:flex;align-items:center;justify-content:center;
        font-size:16px;flex:none;transition:.2s;
    }
    .sbox .sbtn:hover,.sbox.open .sbtn{color:var(--accent);border-color:var(--accent)}
    .panel .sbox input[type=text]{
        width:0;opacity:0;padding:0;border-width:0;height:44px;border-radius:12px;
        font-size:14.5px;font-weight:500;background:var(--input-bg);color:var(--input-text);
        transition:width .25s ease,opacity .2s ease,padding .25s ease;pointer-events:none;
    }
    .panel .sbox.open input[type=text]{
        width:320px;max-width:60vw;opacity:1;padding:0 16px;border:1.5px solid var(--accent);pointer-events:auto;
        box-shadow:0 0 0 4px rgba(0,180,130,.18);
    }
    :root[data-theme="light"] .sbox .sbtn{background:#fff;border-color:rgba(20,60,45,.4);color:#2a3d34}
    :root[data-theme="light"] .sbox .sbtn:hover,
    :root[data-theme="light"] .sbox.open .sbtn{color:#004d38;border-color:#007757}
    :root[data-theme="light"] .panel .sbox.open input[type=text]{background:#fff;border-color:#007757;box-shadow:0 0 0 4px rgba(0,119,87,.18)}
    /* ===== Header: rata tengah + logo animasi ===== */
    .topbar{flex-direction:column;align-items:stretch;gap:6px;margin-bottom:30px}
    .topbar .actions{order:1;align-self:flex-end;flex-wrap:wrap;justify-content:flex-end}
    .topbar .brand{order:2;flex-direction:column;justify-content:center;text-align:center;gap:14px;margin:4px auto 0}
    .brand .brand-text{text-align:center}
    .brand h1{font-size:28px;letter-spacing:-.3px;
        background:linear-gradient(90deg,var(--text),var(--accent),var(--accent2),var(--text));
        background-size:250% 100%;-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;
        animation:titleShift 8s ease-in-out infinite}
    .brand p{font-size:14px;margin-top:3px}

    .logo-anim{position:relative;width:96px;height:96px;display:flex;align-items:center;justify-content:center}
    .logo-anim .logo{
        position:relative;z-index:2;width:64px;height:64px;border-radius:19px;font-size:28px;overflow:hidden;
        animation:logoFloat 3.6s ease-in-out infinite;
        box-shadow:0 10px 30px rgba(0,229,168,.45),inset 0 1px 0 rgba(255,255,255,.4);
    }
    .logo-anim .logo i{position:relative;z-index:1;animation:shieldBeat 3.6s ease-in-out infinite}
    .logo-anim .logo::after{ /* kilau cahaya */
        content:"";position:absolute;top:-30%;left:-60%;width:40%;height:160%;
        background:linear-gradient(90deg,transparent,rgba(255,255,255,.65),transparent);
        transform:rotate(20deg);animation:logoShine 4s ease-in-out infinite;
    }
    .logo-anim .ring{position:absolute;border-radius:50%;pointer-events:none}
    .logo-anim .ring-spin{
        inset:0;padding:2px;
        background:conic-gradient(from 0deg,transparent 0 55%,var(--accent) 80%,var(--accent2) 100%);
        -webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);
        -webkit-mask-composite:xor;mask-composite:exclude;
        animation:ringSpin 3.2s linear infinite;
    }
    .logo-anim .ring-pulse{inset:14px;border:2px solid var(--accent);opacity:0;animation:ringPulse 3s ease-out infinite}
    .logo-anim .ring-pulse.delay{animation-delay:1.5s}
    .logo-anim .spark{position:absolute;width:6px;height:6px;border-radius:50%;background:var(--accent);box-shadow:0 0 10px var(--accent);opacity:0}
    .logo-anim .s1{top:8px;right:10px;animation:spark 3s ease-in-out infinite}
    .logo-anim .s2{bottom:12px;left:6px;background:var(--accent2);box-shadow:0 0 10px var(--accent2);animation:spark 3s ease-in-out 1s infinite}
    .logo-anim .s3{top:22px;left:2px;width:4px;height:4px;animation:spark 3s ease-in-out 2s infinite}

    @keyframes logoFloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-5px)}}
    @keyframes shieldBeat{0%,100%{transform:scale(1)}50%{transform:scale(1.08)}}
    @keyframes logoShine{0%,55%{left:-60%}100%{left:140%}}
    @keyframes ringSpin{to{transform:rotate(360deg)}}
    @keyframes ringPulse{0%{transform:scale(.75);opacity:.55}100%{transform:scale(1.5);opacity:0}}
    @keyframes spark{0%,100%{opacity:0;transform:scale(.4)}50%{opacity:1;transform:scale(1.2)}}
    @keyframes titleShift{0%,100%{background-position:0% 50%}50%{background-position:100% 50%}}

    :root[data-theme="light"] .logo-anim .logo{box-shadow:0 8px 22px rgba(0,120,90,.35),inset 0 1px 0 rgba(255,255,255,.5)}
    :root[data-theme="light"] .brand h1{
        background:linear-gradient(90deg,#101c17,#007757,#00607f,#101c17);background-size:250% 100%;
        -webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent}
    :root[data-theme="light"] .logo-anim .ring-pulse{border-color:#007757}
    :root[data-theme="light"] .logo-anim .ring-spin{background:conic-gradient(from 0deg,transparent 0 55%,#007757 80%,#00607f 100%)}

    @media(prefers-reduced-motion:reduce){
        .logo-anim *,.logo-anim .logo,.brand h1{animation:none !important}
        .logo-anim .ring-pulse,.logo-anim .spark{display:none}
    }
    @media(max-width:640px){
        .topbar .actions{align-self:center;justify-content:center}
        .brand h1{font-size:23px}
    }
    /* ===== Header dinaikkan: logo di paling atas, tombol aksi sejajar di kanan ===== */
    body{padding-top:12px}
    .topbar{position:relative;margin-bottom:22px;gap:0}
    .topbar .brand{order:1;margin:0 auto;gap:8px}
    .topbar .actions{order:2;position:absolute;top:6px;right:0;align-self:auto}
    .logo-anim{width:84px;height:84px}
    .logo-anim .logo{width:56px;height:56px;font-size:24px;border-radius:17px}
    .logo-anim .ring-pulse{inset:10px}
    .brand h1{font-size:26px;line-height:1.15}
    .brand p{font-size:13.5px;margin-top:1px}
    @media(max-width:900px){
        .topbar .actions{position:static;order:2;align-self:center;justify-content:center;margin-top:10px}
    }
    /* ===== Logo animasi di kiri & kanan judul ===== */
    .topbar .brand{flex-direction:row;align-items:center;justify-content:center;gap:22px}
    .logo-anim{width:76px;height:76px;flex:none}
    .logo-anim .logo{width:50px;height:50px;font-size:21px;border-radius:15px}
    .logo-anim .ring-pulse{inset:9px}
    .logo-right .ring-spin{animation-direction:reverse}
    .logo-right .logo{animation-delay:-1.8s}
    .logo-right .logo i{animation-delay:-1.8s}
    .logo-right .logo::after{animation-delay:.6s}
    .logo-right .ring-pulse{animation-delay:.75s}
    .logo-right .ring-pulse.delay{animation-delay:2.25s}
    @media(max-width:560px){
        .topbar .brand{gap:10px}
        .logo-anim{width:52px;height:52px}
        .logo-anim .logo{width:36px;height:36px;font-size:15px;border-radius:11px}
        .logo-anim .ring-pulse{inset:6px}
        .logo-anim .spark{display:none}
    }
    /* ===== Kartu statistik: dikecilkan, ukuran huruf tetap ===== */
    .stats{gap:12px;margin-bottom:20px}
    .stat-card{
        display:grid;grid-template-columns:auto 1fr;grid-template-rows:auto auto;
        column-gap:14px;align-items:center;padding:10px 18px;border-radius:14px;
    }
    .stat-card .icon{grid-row:1 / span 2;margin:0;font-size:20px;width:30px;text-align:center}
    .stat-card .num{line-height:1.05}
    .stat-card .label{margin-top:2px}
    /* ===== Kolom "Terakhir Aktif" + titik status ===== */
    .seen{display:flex;align-items:center;gap:10px;white-space:nowrap}
    .seen-text{font-weight:700;font-size:14px}
    .seen-sub{font-size:12px;color:var(--muted);margin-top:1px}
    .seen-dot{width:11px;height:11px;border-radius:50%;flex:none;position:relative;background:#8a9a93}
    .seen-dot.never{background:transparent;border:2px solid #8a9a93}
    .seen-dot.on{background:#21f6b0;box-shadow:0 0 8px #21f6b0}
    .seen-dot.on::after{content:"";position:absolute;inset:-4px;border-radius:50%;border:2px solid #21f6b0;opacity:0;animation:seenPing 1.8s ease-out infinite}
    .seen.is-on .seen-text{color:#21f6b0}
    @keyframes seenPing{0%{transform:scale(.6);opacity:.7}100%{transform:scale(1.6);opacity:0}}
    :root[data-theme="light"] .seen-dot.on{background:#00a070;box-shadow:0 0 6px rgba(0,160,112,.6)}
    :root[data-theme="light"] .seen-dot.on::after{border-color:#00a070}
    :root[data-theme="light"] .seen.is-on .seen-text{color:#006b4e}
    :root[data-theme="light"] .seen-dot.off{background:#6b7d75}
    :root[data-theme="light"] .seen-dot.never{border-color:#6b7d75}
    @media(prefers-reduced-motion:reduce){.seen-dot.on::after{animation:none;display:none}}
    /* ===== Kartu statistik tambahan ===== */
    .stat-card.c-users .icon{color:#5ad6f0}
    :root[data-theme="light"] .stat-card.c-users .icon{color:#00607f}
    .panel-head .sub{font-size:12.5px;color:var(--muted);font-weight:500;margin-top:2px}
    .btn-sm{padding:7px 12px;font-size:12.5px}
    /* ===== Detail percobaan diblokir ===== */
    .blocked-detail{font-size:12px;color:var(--muted);line-height:1.5;margin-top:3px}
    :root[data-theme="light"] .blocked-detail{color:#8f0f47}

    /* ===== Tambahan v4: masa berlaku, catatan, banner, riwayat, cadangan ===== */
    .exp-note{font-size:11.5px;font-weight:700;margin-top:3px}
    .exp-ok{color:var(--muted)} .exp-warn{color:#ffd84d} .exp-bad{color:#ff6b8b}
    .ext-row{display:flex;gap:5px;margin-top:6px}
    .chip-btn{border:1px solid var(--border);background:transparent;color:var(--accent);border-radius:999px;padding:2px 9px;font-size:11px;font-weight:700;cursor:pointer;font-family:inherit}
    .chip-btn:hover{background:rgba(0,229,168,.1);border-color:var(--accent)}
    .note-cell{font-size:12px;line-height:1.55;max-width:210px}
    .note-school{font-weight:800;color:var(--text)}
    .note-line{color:var(--muted)} .note-line i{width:14px;color:var(--accent2);font-size:10.5px}
    .sec-title{margin:20px 0 4px;font-size:12px;font-weight:800;color:var(--accent);text-transform:uppercase;letter-spacing:.4px}
    .sec-title i{margin-right:6px}
    .hint{font-size:12.5px;color:var(--muted);line-height:1.6}
    .hint code,.warn-banner code{background:var(--input-bg);padding:1px 6px;border-radius:5px;color:var(--accent)}
    .warn-banner{display:flex;gap:12px;align-items:flex-start;padding:13px 16px;margin-bottom:14px;border-radius:12px;font-size:13px;line-height:1.55;background:rgba(255,216,77,.10);border:1px solid rgba(255,216,77,.35);color:#ffe28a}
    .warn-banner.bad{background:rgba(255,79,154,.10);border-color:rgba(255,79,154,.4);color:#ffb3d0}
    .warn-banner i{margin-top:3px}
    .hist-key{font-size:12px;color:var(--accent);margin-bottom:12px}
    .hist-list{display:flex;flex-direction:column;gap:8px;max-height:52vh;overflow-y:auto}
    .hist-row{display:flex;gap:11px;align-items:flex-start;padding:10px 12px;border:1px solid var(--border);border-radius:10px;background:var(--input-bg)}
    .hist-row>i{margin-top:3px;width:16px;text-align:center;color:var(--accent2)}
    .hist-row.ev-blocked>i,.hist-row.ev-disabled>i{color:#ff6b8b}
    .hist-row.ev-bind>i,.hist-row.ev-enabled>i,.hist-row.ev-extended>i{color:var(--accent)}
    .hist-d{font-size:13px;font-weight:600;color:var(--text)} .hist-t{font-size:11px;color:var(--muted);margin-top:2px}
    .bk-list{display:flex;flex-direction:column;gap:8px;max-height:260px;overflow-y:auto;margin-top:8px}
    .bk-row{display:flex;justify-content:space-between;align-items:center;gap:10px;padding:9px 12px;border:1px solid var(--border);border-radius:10px;background:var(--input-bg)}
    .bk-name{font-size:12.5px;color:var(--text)} .bk-sub{font-size:11.5px;color:var(--muted)}
    .file-in{padding:7px 10px !important}
    input[type=password].pw-in{width:100%;padding:10px 12px;border-radius:9px;border:1px solid var(--border);background:var(--input-bg);color:var(--input-text);font-size:13px;font-family:inherit;outline:none}
    input[type=password].pw-in:focus{border-color:var(--accent)}
    input[type=file]{width:100%;border:1px solid var(--border);border-radius:9px;background:var(--input-bg);color:var(--input-text);font-size:12.5px;font-family:inherit}
    :root[data-theme="light"] .exp-warn{color:#6b4600} :root[data-theme="light"] .exp-bad{color:#8f0f47}
    :root[data-theme="light"] .warn-banner{background:#fbefc9;border-color:#e3c36a;color:#5b3d00}
    :root[data-theme="light"] .warn-banner.bad{background:#f9dce8;border-color:#e08aab;color:#7a0b3d}
</style>
</head>
<body>

<div class="topbar">
    <div class="brand">
        <div class="logo-anim" aria-hidden="true">
            <span class="ring ring-spin"></span>
            <span class="ring ring-pulse"></span>
            <span class="ring ring-pulse delay"></span>
            <div class="logo"><i class="fa-solid fa-shield-halved"></i></div>
            <span class="spark s1"></span><span class="spark s2"></span><span class="spark s3"></span>
        </div>
        <div class="brand-text">
            <h1>License Server</h1>
            <p>Kelola lisensi tiap instalasi klien</p>
        </div>
        <div class="logo-anim logo-right" aria-hidden="true">
            <span class="ring ring-spin"></span>
            <span class="ring ring-pulse"></span>
            <span class="ring ring-pulse delay"></span>
            <div class="logo"><i class="fa-solid fa-shield-halved"></i></div>
            <span class="spark s1"></span><span class="spark s2"></span><span class="spark s3"></span>
        </div>
    </div>
    <div class="actions" style="display:flex;align-items:center;gap:16px">
        <div class="theme-toggle" id="themeToggle" onclick="toggleTheme()" title="Ganti tema siang/malam">
            <div class="icons"><i class="fa-solid fa-sun"></i><i class="fa-solid fa-moon"></i></div>
            <div class="knob" id="themeKnob"><i class="fa-solid fa-moon" id="themeKnobIcon"></i></div>
        </div>
        <a class="btn-logout" href="?logout=1"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        <button class="btn btn-ghost" onclick="ovShow('backupOverlay')" title="Cadangan, ekspor, dan impor data lisensi"><i class="fa-solid fa-database"></i> Cadangan</button>
        <button class="btn btn-ghost" onclick="ovShow('passOverlay')" title="Ganti kata sandi admin"><i class="fa-solid fa-key"></i> Kata sandi</button>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Instalasi Baru</button>
    </div>
</div>

<?php if (!empty($GLOBALS['ADM_LOAD_ERROR'])): ?>
<div class="warn-banner bad"><i class="fa-solid fa-triangle-exclamation"></i>
    <div><b>licenses.json tidak terbaca atau rusak.</b> Perubahan dinonaktifkan supaya data asli tidak tertimpa. Buka <b>Cadangan</b> lalu pulihkan salah satu cadangan, atau perbaiki berkas itu secara manual.</div></div>
<?php endif; ?>
<?php if ($passIsDefault): ?>
<div class="warn-banner"><i class="fa-solid fa-key"></i>
    <div><b>Kata sandi admin belum pernah diganti lewat panel ini</b> (masih memakai hash bawaan di <code>admin.php</code>). Ganti lewat tombol <b>Kata sandi</b> di kanan atas supaya tersimpan di <code>admin_auth.php</code>.</div></div>
<?php endif; ?>
<div class="warn-banner bad" id="exposeBanner" style="display:none"><i class="fa-solid fa-lock-open"></i>
    <div><b>Data lisensi bisa diunduh langsung lewat browser</b> (<code>/key/licenses.json</code>). Pastikan berkas <code>.htaccess</code> di folder <code>key</code> aktif (Apache perlu <code>AllowOverride All</code>), lalu muat ulang halaman ini.</div></div>

<div class="stats">
    <div class="stat-card c-total">
        <div class="icon"><i class="fa-solid fa-layer-group"></i></div>
        <div class="num"><?= $total ?></div>
        <div class="label">Total Instalasi</div>
    </div>
    <div class="stat-card c-active">
        <div class="icon"><i class="fa-solid fa-circle-check"></i></div>
        <div class="num"><?= $countByStatus['active'] ?></div>
        <div class="label">Aktif</div>
    </div>
    <div class="stat-card c-expired">
        <div class="icon"><i class="fa-solid fa-hourglass-end"></i></div>
        <div class="num"><?= $countByStatus['expired'] ?></div>
        <div class="label">Expired</div>
    </div>
    <div class="stat-card c-disabled">
        <div class="icon"><i class="fa-solid fa-ban"></i></div>
        <div class="num"><?= $countByStatus['disabled'] ?></div>
        <div class="label">Dinonaktifkan</div>
    </div>
    <div class="stat-card c-users">
        <div class="icon"><i class="fa-solid fa-users"></i></div>
        <div class="num"><?= $totalUsers ?></div>
        <div class="label">Perangkat Terikat</div>
    </div>
</div>

<div class="panel">
    <div class="panel-head">
        <h2><i class="fa-solid fa-list" style="color:var(--accent);margin-right:6px"></i>Daftar Instalasi</h2>
        <div class="sbox" id="sbox">
            <input type="text" id="searchInput" placeholder="Cari label / kode / MAC..." onkeyup="filterTable()" onblur="if(!this.value)toggleSearch(false)" onkeydown="if(event.key==='Escape'){this.value='';filterTable();toggleSearch(false)}">
            <button type="button" class="sbtn" id="sbtn" title="Cari" aria-label="Cari" onmousedown="event.preventDefault()" onclick="toggleSearch()"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
    </div>

    <?php if (empty($data['installations'])): ?>
        <div class="empty-state">
            <i class="fa-solid fa-inbox"></i>
            Belum ada instalasi terdaftar. Klik "Instalasi Baru" untuk mulai.
        </div>
    <?php else: ?>
    <div style="overflow-x:auto">
    <table id="installTable">
        <thead>
            <tr>
                <th>Kode Lisensi</th>
                <th>Label</th>
                <th>Status</th>
                <th>Menu AI Soal</th>
                <th>Expired</th>
                <th>MAC Address</th>
                <th>Catatan</th>
                <th>Terakhir Aktif</th>
                <th style="text-align:right">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($data['installations'] as $key => $info):
            $eff = adm_effective_status($info);
            $dl  = adm_days_left($info['expired_date'] ?? '-');
            $hist = $info['history'] ?? [];
            $infoEdit = array_diff_key($info, ['history' => 1]);
        ?>
            <tr data-search="<?= htmlspecialchars(strtolower($key.' '.($info['label']??'').' '.($info['last_seen_mac']??'').' '.($info['school']??'').' '.($info['school_contact']??''))) ?>">
                <td>
                    <div class="keycell">
                        <code class="mono"><?= htmlspecialchars($key) ?></code>
                        <button class="copy-btn" onclick="copyKey(this, '<?= htmlspecialchars($key, ENT_QUOTES) ?>')" title="Salin kode">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                </td>
                <td><?= htmlspecialchars(($info['label'] ?? '') ?: '-') ?></td>
                <td>
                    <span class="badge <?= htmlspecialchars($eff) ?>">
                        <i class="fa-solid fa-circle"></i> <?= htmlspecialchars($eff) ?>
                    </span>
                </td>
                <td>
                    <span class="badge <?= !empty($info['menu_ai_soal']) ? 'menu-on' : 'menu-off' ?>">
                        <i class="fa-solid <?= !empty($info['menu_ai_soal']) ? 'fa-eye' : 'fa-eye-slash' ?>"></i>
                        <?= !empty($info['menu_ai_soal']) ? 'Tampil' : 'Sembunyi' ?>
                    </span>
                </td>
                <td>
                    <div class="mono" style="color:var(--muted)"><?= htmlspecialchars($info['expired_date'] ?? '-') ?></div>
                    <?php if ($dl === null): ?>
                        <div class="exp-note exp-ok">tanpa batas</div>
                    <?php elseif ($dl < 0): ?>
                        <div class="exp-note exp-bad">lewat <?= abs($dl) ?> hari</div>
                    <?php elseif ($dl === 0): ?>
                        <div class="exp-note exp-bad">berakhir hari ini</div>
                    <?php else: ?>
                        <div class="exp-note <?= $dl <= 7 ? 'exp-bad' : ($dl <= 30 ? 'exp-warn' : 'exp-ok') ?>">sisa <?= $dl ?> hari</div>
                    <?php endif; ?>
                    <?php if ($dl !== null): ?>
                    <div class="ext-row">
                        <?php foreach ([1 => '+1 bln', 12 => '+1 thn'] as $mo => $lbl): ?>
                        <form method="post" onsubmit="return confirm('Perpanjang <?= $mo === 12 ? '1 tahun' : '1 bulan' ?>?<?= $dl < 0 ? ' Lisensi sudah lewat, dihitung dari hari ini.' : '' ?>');" style="display:inline">
                            <input type="hidden" name="action" value="extend_license">
                            <input type="hidden" name="license_key" value="<?= htmlspecialchars($key) ?>">
                            <input type="hidden" name="months" value="<?= $mo ?>">
                            <button type="submit" class="chip-btn" title="Perpanjang <?= $mo === 12 ? '1 tahun' : '1 bulan' ?>"><?= $lbl ?></button>
                        </form>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="font-size:11.5px;line-height:1.8">
                        <div class="mono" style="color:var(--text)"><i class="fa-solid fa-ethernet" style="width:14px;color:var(--accent2)"></i> <?= htmlspecialchars(($info['last_seen_mac'] ?? '') ?: '-') ?></div>
                        <?php if (!empty($info['mac_mismatch_detected_at'])): ?>
                            <div class="badge expired" style="margin-top:4px" title="MAC sebelumnya: <?= htmlspecialchars($info['previous_mac'] ?? '-') ?>">
                                <i class="fa-solid fa-triangle-exclamation"></i> MAC berubah!
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($info['bound_mac'])): ?>
                            <div class="badge active" style="margin-top:4px" title="Terkunci ke komputer <?= htmlspecialchars($info['bound_host'] ?? '-') ?> sejak <?= htmlspecialchars($info['bound_at'] ?? '-') ?>. MAC dikenal: <?= htmlspecialchars(implode(', ', $info['bound_macs'] ?? [$info['bound_mac']])) ?>">
                                <i class="fa-solid fa-link"></i> Terikat ke perangkat ini
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($info['blocked_attempts'])): ?>
                            <div class="badge disabled blocked-badge" style="margin-top:4px">
                                <i class="fa-solid fa-triangle-exclamation"></i> <?= (int)$info['blocked_attempts'] ?>x percobaan perangkat lain diblokir
                            </div>
                            <div class="mono blocked-detail">
                                <?= htmlspecialchars(($info['last_blocked_mac'] ?? '') ?: 'tanpa MAC') ?><br>
                                <?= htmlspecialchars($info['last_blocked_at'] ?? '') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </td>
                <td>
                    <?php
                        $nSch = trim($info['school'] ?? ''); $nCon = trim($info['school_contact'] ?? ''); $nBuy = trim($info['purchase_date'] ?? '');
                    ?>
                    <?php if ($nSch === '' && $nCon === '' && $nBuy === ''): ?>
                        <span style="color:var(--muted)">-</span>
                    <?php else: ?>
                    <div class="note-cell">
                        <?php if ($nSch !== ''): ?><div class="note-school"><?= htmlspecialchars($nSch) ?></div><?php endif; ?>
                        <?php if ($nCon !== ''): ?><div class="note-line"><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($nCon) ?></div><?php endif; ?>
                        <?php if ($nBuy !== ''): ?><div class="note-line"><i class="fa-solid fa-receipt"></i> Dibeli <?= htmlspecialchars(date('d M Y', strtotime($nBuy))) ?></div><?php endif; ?>
                    </div>
                    <?php endif; ?>
                </td>
                <?php
                    $seenTs   = adm_ts($info['last_seen_at'] ?? null);
                    $nowTs    = time();
                    $isOnline = $seenTs && ($nowTs - $seenTs) <= ONLINE_WINDOW;
                ?>
                <td>
                    <div class="seen<?= $isOnline ? ' is-on' : '' ?>" data-ts="<?= (int)$seenTs ?>" title="<?= $seenTs ? htmlspecialchars(date('d M Y H:i:s', $seenTs)) : 'Belum pernah melapor' ?>">
                        <span class="seen-dot <?= $isOnline ? 'on' : ($seenTs ? 'off' : 'never') ?>"></span>
                        <div>
                            <div class="seen-text"><?= htmlspecialchars(adm_ago($seenTs, $nowTs)) ?></div>
                            <div class="seen-sub"><?= $isOnline ? 'Sedang aktif' : ($seenTs ? htmlspecialchars(date('d M Y, H:i', $seenTs)) : '-') ?></div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="row-actions" style="justify-content:flex-end">
                        <?php if (!empty($info['bound_mac']) || !empty($info['blocked_attempts'])): ?>
                        <form method="post" onsubmit="return confirm('Lepas ikatan perangkat? Perangkat berikutnya yang mengecek akan menjadi perangkat resmi baru, dan perangkat lama akan ditolak.');" style="display:inline">
                            <input type="hidden" name="action" value="reset_binding">
                            <input type="hidden" name="license_key" value="<?= htmlspecialchars($key) ?>">
                            <button type="submit" class="icon-btn" title="Lepas ikatan perangkat (pindah komputer)"><i class="fa-solid fa-link-slash"></i></button>
                        </form>
                        <?php endif; ?>
                        <form method="post" onsubmit="return confirm('<?= $info['status']==='disabled' ? 'Aktifkan kembali instalasi ini?' : 'Nonaktifkan instalasi ini? Modul AI Soal klien akan langsung terkunci.' ?>');" style="display:inline">
                            <input type="hidden" name="action" value="toggle_disable">
                            <input type="hidden" name="license_key" value="<?= htmlspecialchars($key) ?>">
                            <button type="submit" class="icon-btn <?= $info['status']==='disabled' ? '' : 'del' ?>" title="<?= $info['status']==='disabled' ? 'Aktifkan' : 'Nonaktifkan' ?>">
                                <i class="fa-solid <?= $info['status']==='disabled' ? 'fa-power-off' : 'fa-ban' ?>"></i>
                            </button>
                        </form>
                        <button class="icon-btn" title="Riwayat aktivitas" onclick='showHistory(<?= adm_js($key) ?>, <?= adm_js($hist) ?>)'>
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </button>
                        <button class="icon-btn" title="Edit" onclick='editInstall(<?= adm_js($key) ?>, <?= adm_js($infoEdit) ?>)'>
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <form method="post" onsubmit="return confirm('Hapus instalasi ini? Tindakan tidak bisa dibatalkan.');" style="display:inline">
                            <input type="hidden" name="action" value="delete_installation">
                            <input type="hidden" name="license_key" value="<?= htmlspecialchars($key) ?>">
                            <button type="submit" class="icon-btn del" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

<!-- ===================== MODAL ===================== -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal">
        <div class="modal-head">
            <h3 id="modalTitle"><i class="fa-solid fa-plus" style="color:var(--accent);margin-right:7px"></i>Instalasi Baru</h3>
            <button onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form method="post" id="installForm">
            <input type="hidden" name="action" value="save_installation">

            <label>Kode Lisensi</label>
            <div class="genrow">
                <input type="text" name="license_key" id="f_key" placeholder="GCBT-XXXX-XXXX-XXXX-XXXX" required>
                <button type="button" class="btn btn-ghost" onclick="generateKey()"><i class="fa-solid fa-dice"></i> Generate</button>
            </div>

            <label>Label (nama sekolah / klien)</label>
            <input type="text" name="label" id="f_label" placeholder="contoh: SMK Contoh Pontianak">

            <label>Domain (catatan saja, tidak memengaruhi blokir)</label>
            <input type="text" name="domain" id="f_domain" placeholder="localhost">

            <label>Status</label>
            <select name="status" id="f_status">
                <option value="active">Active</option>
                <option value="expired">Expired</option>
                <option value="disabled">Disabled</option>
            </select>

            <label>Expired Date</label>
            <input type="text" name="expired_date" id="f_expired" placeholder="2027-12-31">

            <label>Pesan kustom (opsional)</label>
            <textarea name="message" id="f_message" rows="2" placeholder="Masa berlaku lisensi telah habis..."></textarea>

            <label>Kontak</label>
            <input type="text" name="contact" id="f_contact" value="081234567890">

            <div class="sec-title"><i class="fa-solid fa-address-card"></i> Catatan lisensi</div>
            <label>Nama sekolah</label>
            <input type="text" name="school" id="f_school" placeholder="contoh: SDN 01 Pontianak">
            <label>Kontak sekolah (nama / nomor)</label>
            <input type="text" name="school_contact" id="f_school_contact" placeholder="contoh: Bu Rina 0812xxxxxxx">
            <label>Tanggal beli</label>
            <input type="text" name="purchase_date" id="f_purchase" placeholder="2026-11-02">

            <div class="check-field">
                <input type="checkbox" name="menu_ai_soal" id="f_menu" checked>
                <span><i class="fa-solid fa-wand-magic-sparkles"></i> Tampilkan menu "AI Soal" di sidebar klien</span>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>


<!-- ===================== MODAL: RIWAYAT ===================== -->
<div class="modal-overlay" id="histOverlay">
    <div class="modal">
        <div class="modal-head">
            <h3><i class="fa-solid fa-clock-rotate-left" style="color:var(--accent);margin-right:7px"></i>Riwayat aktivitas</h3>
            <button onclick="ovHide('histOverlay')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="hist-key mono" id="histKey"></div>
        <div id="histList" class="hist-list"></div>
    </div>
</div>

<!-- ===================== MODAL: CADANGAN ===================== -->
<div class="modal-overlay" id="backupOverlay">
    <div class="modal" style="max-width:560px">
        <div class="modal-head">
            <h3><i class="fa-solid fa-database" style="color:var(--accent);margin-right:7px"></i>Cadangan data lisensi</h3>
            <button onclick="ovHide('backupOverlay')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <p class="hint">Setiap kali ada perubahan dari panel ini (dan saat sebuah kode pertama kali terikat ke komputer), kondisi sebelumnya otomatis disalin ke folder <code>backup/</code>. Disimpan <?= BACKUP_KEEP ?> cadangan terakhir.</p>

        <div class="sec-title"><i class="fa-solid fa-file-export"></i> Ekspor</div>
        <a class="btn btn-ghost" href="?export=1" style="text-decoration:none;display:inline-flex"><i class="fa-solid fa-download"></i> Unduh licenses.json</a>

        <div class="sec-title"><i class="fa-solid fa-file-import"></i> Impor</div>
        <form method="post" enctype="multipart/form-data" onsubmit="return confirm('Impor akan MENGGANTI seluruh data lisensi saat ini. Kondisi sekarang tetap disimpan sebagai cadangan. Lanjutkan?');">
            <input type="hidden" name="action" value="import_licenses">
            <div class="genrow">
                <input type="file" name="import_file" accept=".json,application/json" required class="file-in">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Impor</button>
            </div>
        </form>

        <div class="sec-title"><i class="fa-solid fa-clock-rotate-left"></i> Cadangan otomatis (<?= count($backupList) ?>)</div>
        <?php if (!$backupList): ?>
            <p class="hint">Belum ada cadangan. Cadangan pertama dibuat saat ada perubahan berikutnya.</p>
        <?php else: ?>
        <div class="bk-list">
            <?php foreach ($backupList as $bk): ?>
            <div class="bk-row">
                <div>
                    <div class="mono bk-name"><?= htmlspecialchars($bk['label']) ?></div>
                    <div class="bk-sub"><?= (int)$bk['count'] ?> instalasi · <?= number_format($bk['size'] / 1024, 1) ?> KB</div>
                </div>
                <form method="post" onsubmit="return confirm('Pulihkan cadangan <?= htmlspecialchars($bk['label']) ?>? Data lisensi saat ini akan diganti (dan tetap disimpan sebagai cadangan).');">
                    <input type="hidden" name="action" value="restore_backup">
                    <input type="hidden" name="backup_file" value="<?= htmlspecialchars($bk['file']) ?>">
                    <button type="submit" class="btn btn-ghost" style="padding:7px 12px"><i class="fa-solid fa-rotate-left"></i> Pulihkan</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- ===================== MODAL: KATA SANDI ===================== -->
<div class="modal-overlay" id="passOverlay">
    <div class="modal" style="max-width:420px">
        <div class="modal-head">
            <h3><i class="fa-solid fa-key" style="color:var(--accent);margin-right:7px"></i>Ganti kata sandi</h3>
            <button onclick="ovHide('passOverlay')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="post" autocomplete="off">
            <input type="hidden" name="action" value="change_password">
            <label>Kata sandi lama</label>
            <input type="password" name="old_pass" required class="pw-in">
            <label>Kata sandi baru (minimal 8 karakter)</label>
            <input type="password" name="new_pass" required minlength="8" class="pw-in">
            <label>Ulangi kata sandi baru</label>
            <input type="password" name="new_pass2" required minlength="8" class="pw-in">
            <p class="hint" style="margin-top:12px">Halaman admin hanya bisa dibuka dari komputer server ini. Kata sandi disimpan sebagai hash di <code>admin_auth.php</code>.</p>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="ovHide('passOverlay')">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php if ($notice): ?>
<div class="toast" id="toast"><i class="fa-solid fa-circle-check"></i> <?= htmlspecialchars($notice) ?></div>
<?php endif; ?>

<script>
function toggleTheme(){
    const isLight = document.documentElement.getAttribute('data-theme') === 'light';
    if (isLight) {
        document.documentElement.removeAttribute('data-theme');
        localStorage.setItem('ls_admin_theme', 'dark');
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
        localStorage.setItem('ls_admin_theme', 'light');
    }
    updateThemeIcon();
}
function updateThemeIcon(){
    const isLight = document.documentElement.getAttribute('data-theme') === 'light';
    document.getElementById('themeKnobIcon').className = isLight ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
}
updateThemeIcon();

function openModal(){
    document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-plus" style="color:var(--accent);margin-right:7px"></i>Instalasi Baru';
    document.getElementById('installForm').reset();
    document.getElementById('f_menu').checked = true;
    document.getElementById('modalOverlay').classList.add('show');
}
function closeModal(){
    document.getElementById('modalOverlay').classList.remove('show');
}
function editInstall(key, info){
    document.getElementById('modalTitle').innerHTML = '<i class="fa-solid fa-pen" style="color:var(--accent);margin-right:7px"></i>Edit Instalasi';
    document.getElementById('f_key').value = key;
    document.getElementById('f_label').value = info.label || '';
    document.getElementById('f_domain').value = info.domain || '';
    document.getElementById('f_status').value = info.status || 'disabled';
    document.getElementById('f_expired').value = info.expired_date || '';
    document.getElementById('f_message').value = info.message || '';
    document.getElementById('f_contact').value = info.contact || '081234567890';
    document.getElementById('f_menu').checked = !!info.menu_ai_soal;
    document.getElementById('f_school').value = info.school || '';
    document.getElementById('f_school_contact').value = info.school_contact || '';
    document.getElementById('f_purchase').value = info.purchase_date || '';
    document.getElementById('modalOverlay').classList.add('show');
}
function ovShow(id){ document.getElementById(id).classList.add('show'); }
function ovHide(id){ document.getElementById(id).classList.remove('show'); }
function showHistory(key, list){
    document.getElementById('histKey').textContent = key;
    const box = document.getElementById('histList');
    box.textContent = '';
    const icons = {created:'fa-plus', edited:'fa-pen', enabled:'fa-power-off', disabled:'fa-ban', extended:'fa-calendar-plus', bind:'fa-link', unbind:'fa-link-slash', blocked:'fa-triangle-exclamation'};
    if (!list || !list.length){
        const p = document.createElement('p'); p.className = 'hint'; p.textContent = 'Belum ada catatan aktivitas.'; box.appendChild(p);
    } else {
        list.slice().reverse().forEach(ev => {
            const row = document.createElement('div'); row.className = 'hist-row ev-' + (ev.e || '');
            const ic = document.createElement('i'); ic.className = 'fa-solid ' + (icons[ev.e] || 'fa-circle');
            const wrap = document.createElement('div');
            const d = document.createElement('div'); d.className = 'hist-d'; d.textContent = ev.d || '';
            const t = document.createElement('div'); t.className = 'hist-t mono'; t.textContent = ev.t || '';
            wrap.appendChild(d); wrap.appendChild(t); row.appendChild(ic); row.appendChild(wrap); box.appendChild(row);
        });
    }
    ovShow('histOverlay');
}
// token CSRF otomatis pada semua form POST
(function(){
    const tok = <?= json_encode($_SESSION['csrf']) ?>;
    document.querySelectorAll('form[method="post"]').forEach(f => {
        const i = document.createElement('input'); i.type = 'hidden'; i.name = 'csrf'; i.value = tok; f.appendChild(i);
    });
})();
// peringatan bila data lisensi bisa diunduh langsung lewat browser
fetch('licenses.json?_=' + Date.now(), {cache:'no-store'}).then(r => {
    if (r.ok) document.getElementById('exposeBanner').style.display = 'flex';
}).catch(() => {});
function generateKey(){
    const parts = [];
    for (let i = 0; i < 4; i++){
        const bytes = new Uint8Array(2);
        crypto.getRandomValues(bytes);
        parts.push([...bytes].map(b => b.toString(16).padStart(2,'0')).join('').toUpperCase());
    }
    document.getElementById('f_key').value = 'GCBT-' + parts.join('-');
}
function copyKey(btn, key){
    navigator.clipboard.writeText(key).then(() => {
        const icon = btn.querySelector('i');
        icon.className = 'fa-solid fa-check';
        setTimeout(() => { icon.className = 'fa-regular fa-copy'; }, 1200);
    });
}
function toggleSearch(force){
    const box = document.getElementById('sbox');
    const open = (typeof force === 'boolean') ? force : !box.classList.contains('open');
    box.classList.toggle('open', open);
    const inp = document.getElementById('searchInput');
    if (open) { setTimeout(() => inp.focus(), 50); }
    else if (!inp.value) { inp.blur(); }
}
function filterTable(){
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#installTable tbody tr').forEach(row => {
        row.style.display = row.dataset.search.includes(q) ? '' : 'none';
    });
}
// ---- Terakhir aktif: perbarui teks "x menit lalu" & titik hijau secara live ----
(function(){
    const ONLINE = <?= ONLINE_WINDOW ?>;
    const offset = <?= time() ?> * 1000 - Date.now(); // selisih jam server vs browser
    function ago(ts, now){
        if(!ts) return 'Belum pernah';
        const d = Math.floor(now/1000) - ts;
        if(d < 60) return 'Baru saja';
        if(d < 3600) return Math.floor(d/60) + ' menit lalu';
        if(d < 86400) return Math.floor(d/3600) + ' jam lalu';
        if(d < 86400*30) return Math.floor(d/86400) + ' hari lalu';
        return null; // biarkan tanggal dari server
    }
    function tick(){
        const now = Date.now() + offset;
        document.querySelectorAll('.seen').forEach(el => {
            const ts = parseInt(el.dataset.ts, 10);
            if(!ts) return;
            const on = (Math.floor(now/1000) - ts) <= ONLINE;
            const dot = el.querySelector('.seen-dot');
            dot.classList.toggle('on', on);
            dot.classList.toggle('off', !on);
            el.classList.toggle('is-on', on);
            const t = ago(ts, now);
            if(t !== null) el.querySelector('.seen-text').textContent = t;
            const sub = el.querySelector('.seen-sub');
            if(on) sub.textContent = 'Sedang aktif';
            else if(sub.textContent === 'Sedang aktif') sub.textContent = new Date(ts*1000).toLocaleString('id-ID',{day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'});
        });
    }
    tick(); setInterval(tick, 20000);
})();
document.getElementById('modalOverlay').addEventListener('click', function(e){
    if (e.target === this) closeModal();
});
['histOverlay','backupOverlay','passOverlay'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e){ if (e.target === this) ovHide(id); });
});
<?php if ($newGeneratedKey): ?>
openModal();
document.getElementById('f_key').value = <?= json_encode($newGeneratedKey) ?>;
<?php endif; ?>
<?php if ($notice): ?>
setTimeout(() => { const t = document.getElementById('toast'); if (t) t.remove(); }, 3600);
<?php endif; ?>
</script>

</body>
</html>