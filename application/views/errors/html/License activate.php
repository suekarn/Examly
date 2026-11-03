<?php
// Simpan di: application/views/errors/html/license_activate.php
// Dipanggil dari Aiimport.php. Variabel: $title, $message, $contact, $mac, $csrf_name, $csrf_hash, $action, $error, $code_value
defined('BASEPATH') OR exit('No direct script access allowed');

$contactVal = $contact ?? '-';
$isPhone = preg_match('/^[0-9+\-\s]+$/', $contactVal);
$href = $isPhone
    ? 'https://wa.me/' . preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $contactVal))
    : (filter_var($contactVal, FILTER_VALIDATE_EMAIL) ? 'mailto:' . $contactVal : '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($title ?? 'Aktifkan Lisensi') ?></title>
<style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{
        min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;
        font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif;color:#eafff8;
        background:
            radial-gradient(circle at 18% 20%, rgba(0,229,168,.12), transparent 32%),
            radial-gradient(circle at 85% 80%, rgba(0,180,216,.10), transparent 32%),
            linear-gradient(180deg,#050a08 0%,#08140f 55%,#050a08 100%);
    }
    .box{
        width:100%;max-width:470px;text-align:center;padding:34px 30px 28px;border-radius:20px;
        background:linear-gradient(180deg,rgba(13,33,27,.92),rgba(8,20,16,.96));
        border:1px solid rgba(0,229,168,.28);border-top:1px solid rgba(0,229,168,.6);
        box-shadow:0 24px 70px rgba(0,0,0,.55),0 0 40px rgba(0,229,168,.07);
    }
    .icon{width:62px;height:62px;margin:0 auto 16px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:28px;
        background:rgba(0,229,168,.12);border:1px solid rgba(0,229,168,.4)}
    h1{font-size:19px;font-weight:800;margin-bottom:8px}
    p.message{font-size:13.5px;line-height:1.6;color:#a8cfc2;margin-bottom:18px}
    .err{display:flex;gap:8px;align-items:flex-start;text-align:left;margin-bottom:16px;padding:11px 13px;border-radius:10px;
        background:rgba(255,79,154,.10);border:1px solid rgba(255,79,154,.38);color:#ffb3d0;font-size:13px;line-height:1.5}
    .mac{text-align:left;margin-bottom:16px;padding:12px 14px;border-radius:12px;background:rgba(0,0,0,.28);border:1px solid rgba(255,255,255,.08)}
    .mac .lbl{font-size:11px;font-weight:700;letter-spacing:.4px;text-transform:uppercase;color:#6f9c8c;margin-bottom:5px}
    .mac .row{display:flex;align-items:center;justify-content:space-between;gap:10px}
    .mac code{font-family:Consolas,Menlo,monospace;font-size:15px;font-weight:700;color:#00e5a8;word-break:break-all}
    .mac button{flex:none;border:1px solid rgba(0,229,168,.35);background:transparent;color:#00e5a8;border-radius:8px;padding:5px 10px;font-size:12px;font-weight:700;cursor:pointer}
    .mac button:hover{background:rgba(0,229,168,.1)}
    label{display:block;text-align:left;font-size:11.5px;font-weight:700;letter-spacing:.3px;text-transform:uppercase;color:#6f9c8c;margin-bottom:6px}
    input[type=text]{
        width:100%;padding:13px 14px;border-radius:12px;font-size:15px;font-family:Consolas,Menlo,monospace;letter-spacing:.5px;text-transform:uppercase;
        background:#041310;color:#fff;border:1px solid rgba(0,229,168,.28);outline:none;margin-bottom:14px
    }
    input[type=text]:focus{border-color:#00e5a8}
    .go{width:100%;padding:13px;border:none;border-radius:12px;cursor:pointer;font-size:14.5px;font-weight:800;color:#031b14;
        background:linear-gradient(135deg,#00e5a8,#00b4d8);box-shadow:0 10px 26px rgba(0,229,168,.25)}
    .go:hover{filter:brightness(1.07)}
    .help{margin-top:16px;font-size:12.5px;color:#7fb3a3;line-height:1.6}
    .help a{color:#00e5a8;font-weight:700;text-decoration:none}
    .note{margin-top:14px;font-size:11.5px;color:#5c8276}
</style>
</head>
<body>
    <div class="box">
        <div class="icon">🔑</div>
        <h1><?= htmlspecialchars($title ?? 'Aktifkan Lisensi') ?></h1>
        <p class="message"><?= nl2br(htmlspecialchars($message ?? 'Masukkan kode lisensi untuk mengaktifkan modul AI Soal di komputer ini.')) ?></p>

        <?php if (!empty($error)): ?>
        <div class="err"><span>⚠️</span><div><?= nl2br(htmlspecialchars($error)) ?></div></div>
        <?php endif; ?>

        <div class="mac">
            <div class="lbl">MAC address komputer ini (terdeteksi otomatis)</div>
            <div class="row">
                <code id="macVal"><?= htmlspecialchars(($mac ?? '') !== '' ? $mac : 'tidak terbaca') ?></code>
                <?php if (!empty($mac)): ?><button type="button" onclick="copyMac(this)">Salin</button><?php endif; ?>
            </div>
        </div>

        <form method="post" action="<?= htmlspecialchars($action ?? '') ?>" autocomplete="off">
            <?php if (!empty($csrf_name)): ?>
            <input type="hidden" name="<?= htmlspecialchars($csrf_name) ?>" value="<?= htmlspecialchars($csrf_hash ?? '') ?>">
            <?php endif; ?>
            <label for="code">Kode lisensi</label>
            <input type="text" id="code" name="lg_activate_code" placeholder="GCBT-XXXX-XXXX-XXXX-XXXX" maxlength="45" value="<?= htmlspecialchars($code_value ?? '') ?>" required autofocus spellcheck="false">
            <button type="submit" class="go">Aktifkan di komputer ini</button>
        </form>

        <p class="help">
            Kode lisensi didapat dari developer. Satu kode hanya untuk <b>satu komputer</b>: setelah diaktifkan, kode terikat ke MAC address di atas.
            <?php if ($href !== ''): ?><br>Belum punya kode? <a href="<?= htmlspecialchars($href) ?>" target="_blank" rel="noopener">Hubungi developer (<?= htmlspecialchars($contactVal) ?>)</a><?php endif; ?>
        </p>
        <p class="note">Sebutkan MAC address di atas saat meminta kode atau pemindahan lisensi.</p>
    </div>
<script>
function copyMac(btn){
    var t = document.getElementById('macVal').textContent;
    (navigator.clipboard ? navigator.clipboard.writeText(t) : Promise.reject()).then(function(){ btn.textContent = 'Tersalin'; setTimeout(function(){ btn.textContent = 'Salin'; }, 1500); }).catch(function(){});
}
</script>
</body>
</html>