<?php
/**
 * =====================================================================
 * LICENSE SERVER — admin.php (v3, tampilan baru)
 * =====================================================================
 * Logika backend TIDAK berubah dari versi sebelumnya (field POST sama
 * persis), hanya tampilannya yang diperbarui.
 * =====================================================================
 */

session_start();

$ADMIN_PASSWORD_HASH = '$2b$12$CnAvVa9sIveDwO2ha5ulJuCiTTHqIwD.fdVFOPo4sk5xaSrwt2F9.'; // password: 000000

define('LICENSES_FILE', __DIR__ . '/licenses.json');

function adm_default_shell() {
    return ['installations' => [], 'default' => [
        'status' => 'disabled', 'message' => '', 'contact' => '081234567890',
        'expired_date' => '-', 'menu_ai_soal' => false,
    ]];
}
function adm_load() {
    if (!is_file(LICENSES_FILE)) return adm_default_shell();
    $data = json_decode(file_get_contents(LICENSES_FILE), true);
    if (!is_array($data)) return adm_default_shell();
    $data['installations'] = $data['installations'] ?? [];
    $data['default']       = $data['default']       ?? adm_default_shell()['default'];
    return $data;
}
function adm_save($data) {
    file_put_contents(LICENSES_FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
}
function adm_generate_key() {
    $parts = [];
    for ($i = 0; $i < 4; $i++) $parts[] = strtoupper(bin2hex(random_bytes(2)));
    return 'GCBT-' . implode('-', $parts);
}

// ----------------------------- LOGIN -----------------------------
if (isset($_POST['login_password'])) {
    if (password_verify($_POST['login_password'], $ADMIN_PASSWORD_HASH)) {
        $_SESSION['ls_admin'] = true;
    } else {
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

// ----------------------------- ACTIONS -----------------------------
$data = adm_load();
$notice = '';
$newGeneratedKey = '';
$editTarget = ''; // kalau abis save/generate, modal otomatis kebuka lagi

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'save_installation') {
        $key = trim($_POST['license_key'] ?? '');
        if ($key !== '') {
            $existing = $data['installations'][$key] ?? [];
            $data['installations'][$key] = array_merge($existing, [
                'label'            => $_POST['label'] ?: ($existing['label'] ?? ''),
                'domain'           => $_POST['domain'] ?: ($existing['domain'] ?? ''),
                'status'           => $_POST['status'] ?? 'disabled',
                'message'          => $_POST['message'] ?? '',
                'contact'          => $_POST['contact'] ?? '081234567890',
                'expired_date'     => $_POST['expired_date'] ?: '-',
                'menu_ai_soal'     => isset($_POST['menu_ai_soal']),
                'last_seen_domain' => $existing['last_seen_domain'] ?? null,
                'last_seen_at'     => $existing['last_seen_at'] ?? null,
            ]);
            adm_save($data);
            $notice = "Instalasi '$key' berhasil disimpan.";
        }
    } elseif ($_POST['action'] === 'delete_installation') {
        $key = $_POST['license_key'] ?? '';
        unset($data['installations'][$key]);
        adm_save($data);
        $notice = "Kode lisensi '$key' dihapus.";
    } elseif ($_POST['action'] === 'toggle_disable') {
        $key = $_POST['license_key'] ?? '';
        if (isset($data['installations'][$key])) {
            $current = $data['installations'][$key]['status'] ?? 'disabled';
            $data['installations'][$key]['status'] = ($current === 'disabled') ? 'active' : 'disabled';
            adm_save($data);
            $notice = ($current === 'disabled')
                ? "Instalasi '$key' diaktifkan kembali."
                : "Instalasi '$key' dinonaktifkan.";
        }
    } elseif ($_POST['action'] === 'reset_binding') {
        $key = $_POST['license_key'] ?? '';
        if (isset($data['installations'][$key])) {
            foreach (['bound_mac','bound_macs','mac_changed_at','bound_host','bound_at','blocked_attempts','last_blocked_mac','last_blocked_host','last_blocked_ip','last_blocked_at','mac_mismatch_detected_at','previous_mac'] as $f) {
                unset($data['installations'][$key][$f]);
            }
            adm_save($data);
            $notice = "Ikatan perangkat untuk '$key' dilepas. Perangkat berikutnya yang mengecek akan terikat.";
        }
    } elseif ($_POST['action'] === 'generate_key') {
        $newGeneratedKey = adm_generate_key();
    }
    $data = adm_load();
}

// Statistik ringkas
$total = count($data['installations']);
$countByStatus = ['active' => 0, 'expired' => 0, 'disabled' => 0];
$menuOnCount = 0;
foreach ($data['installations'] as $info) {
    $s = $info['status'] ?? 'disabled';
    if (isset($countByStatus[$s])) $countByStatus[$s]++;
    if (!empty($info['menu_ai_soal'])) $menuOnCount++;
}

// Jumlah komputer yang sudah terikat ke sebuah kode lisensi
$totalUsers = 0;
foreach ($data['installations'] as $info) { if (!empty($info['bound_mac'])) $totalUsers++; }
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
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Instalasi Baru</button>
    </div>
</div>

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
                <th>Terakhir Aktif</th>
                <th style="text-align:right">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($data['installations'] as $key => $info): ?>
            <tr data-search="<?= htmlspecialchars(strtolower($key.' '.($info['label']??'').' '.($info['last_seen_mac']??''))) ?>">
                <td>
                    <div class="keycell">
                        <code class="mono"><?= htmlspecialchars($key) ?></code>
                        <button class="copy-btn" onclick="copyKey(this, '<?= htmlspecialchars($key, ENT_QUOTES) ?>')" title="Salin kode">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                </td>
                <td><?= htmlspecialchars($info['label'] ?: '-') ?></td>
                <td>
                    <span class="badge <?= htmlspecialchars($info['status']) ?>">
                        <i class="fa-solid fa-circle"></i> <?= htmlspecialchars($info['status']) ?>
                    </span>
                </td>
                <td>
                    <span class="badge <?= !empty($info['menu_ai_soal']) ? 'menu-on' : 'menu-off' ?>">
                        <i class="fa-solid <?= !empty($info['menu_ai_soal']) ? 'fa-eye' : 'fa-eye-slash' ?>"></i>
                        <?= !empty($info['menu_ai_soal']) ? 'Tampil' : 'Sembunyi' ?>
                    </span>
                </td>
                <td class="mono" style="color:var(--muted)"><?= htmlspecialchars($info['expired_date'] ?? '-') ?></td>
                <td>
                    <div style="font-size:11.5px;line-height:1.8">
                        <div class="mono" style="color:var(--text)"><i class="fa-solid fa-ethernet" style="width:14px;color:var(--accent2)"></i> <?= htmlspecialchars($info['last_seen_mac'] ?: '-') ?></div>
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
                                <?= htmlspecialchars($info['last_blocked_mac'] ?: 'tanpa MAC') ?><br>
                                <?= htmlspecialchars($info['last_blocked_at'] ?? '') ?>
                            </div>
                        <?php endif; ?>
                    </div>
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
                        <button class="icon-btn" title="Edit" onclick='editInstall(<?= json_encode($key) ?>, <?= json_encode($info) ?>)'>
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
    document.getElementById('modalOverlay').classList.add('show');
}
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