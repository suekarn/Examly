<?php
// File ini dipanggil oleh: $this->load->view('errors/html/error_license', $data_error);
// Variabel yang dikirim dari Converter.php: $title, $message, $contact, $domain, $expired_date
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($title ?? 'Lisensi Tidak Aktif') ?></title>
<style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{
        min-height:100vh;
        display:flex;align-items:center;justify-content:center;
        font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif;
        background:
            radial-gradient(circle at 15% 20%, rgba(255,79,154,.12), transparent 30%),
            radial-gradient(circle at 85% 80%, rgba(0,229,255,.08), transparent 30%),
            linear-gradient(180deg,#0a0509 0%,#120a12 50%,#0a0509 100%);
        color:#f5eef2;
        padding:20px;
    }
    .box{
        width:100%;max-width:480px;
        background:linear-gradient(180deg,rgba(28,14,22,.9),rgba(18,9,15,.95));
        border:1px solid rgba(255,79,154,.35);
        border-top:1px solid rgba(255,79,154,.7);
        border-radius:18px;
        box-shadow:0 0 0 1px rgba(255,79,154,.06),0 20px 60px rgba(0,0,0,.5),0 0 40px rgba(255,79,154,.08);
        padding:36px 32px;
        text-align:center;
    }
    .icon{
        width:64px;height:64px;margin:0 auto 18px;
        display:flex;align-items:center;justify-content:center;
        border-radius:50%;
        background:rgba(255,79,154,.12);
        border:1px solid rgba(255,79,154,.4);
        font-size:30px;
    }
    h1{font-size:19px;font-weight:800;margin-bottom:10px;color:#ffe3ef}
    p.message{font-size:14px;line-height:1.6;color:#d9c4ce;margin-bottom:22px}
    .details{
        text-align:left;
        background:rgba(0,0,0,.25);
        border:1px solid rgba(255,255,255,.08);
        border-radius:10px;
        padding:14px 16px;
        margin-bottom:22px;
        font-size:13px;
    }
    .details div{display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid rgba(255,255,255,.06)}
    .details div:last-child{border-bottom:none}
    .details span.label{color:#a98c97}
    .details span.value{color:#f5eef2;font-weight:600;text-align:right}
    .contact-btn{
        display:inline-block;width:100%;
        background:linear-gradient(135deg,#ff4f9a,#ff7cb5);
        color:#1a0010;font-weight:800;font-size:14px;
        padding:12px 20px;border-radius:10px;
        text-decoration:none;
        box-shadow:0 0 18px rgba(255,79,154,.25);
    }
    .contact-btn:hover{filter:brightness(1.08)}
    .footer-note{margin-top:18px;font-size:11.5px;color:#6b5560}
</style>
</head>
<body>
    <div class="box">
        <div class="icon">🔒</div>
        <h1><?= htmlspecialchars($title ?? 'Lisensi Tidak Aktif') ?></h1>
        <p class="message">
            <?= nl2br(htmlspecialchars($message ?? 'Masa berlaku lisensi modul ini telah habis atau dinonaktifkan. Silakan hubungi developer untuk mengaktifkan kembali.')) ?>
        </p>

        <div class="details">
            <div><span class="label">Domain</span><span class="value"><?= htmlspecialchars($domain ?? '-') ?></span></div>
            <div><span class="label">Status Berakhir</span><span class="value"><?= htmlspecialchars($expired_date ?? '-') ?></span></div>
        </div>

        <?php
        $contactVal = $contact ?? '-';
        $isPhone = preg_match('/^[0-9+\-\s]+$/', $contactVal);
        $href = $isPhone
            ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $contactVal)
            : (filter_var($contactVal, FILTER_VALIDATE_EMAIL) ? 'mailto:' . $contactVal : '#');
        ?>
        <a class="contact-btn" href="<?= htmlspecialchars($href) ?>" target="_blank" rel="noopener">
            Hubungi Developer (<?= htmlspecialchars($contactVal) ?>)
        </a>

        <p class="footer-note">Halaman ini tampil otomatis karena verifikasi lisensi aplikasi tidak berhasil.</p>
    </div>
</body>
</html>
