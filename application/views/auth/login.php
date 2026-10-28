<?php
// Cegah HP/browser menyimpan halaman login lama (cache), agar tidak perlu clear data
if (!headers_sent()) {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#326698">
    <title>Login</title>

    <!-- Tanpa CDN: semua gaya, ikon, dan script ada di file ini,
         jadi halaman tampil instan walau HP tidak punya internet. -->
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        html { -webkit-text-size-adjust: 100%; text-size-adjust: 100%; }

        body {
            margin: 0;
            background-color: #f8fafc;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #334155;
            -webkit-tap-highlight-color: transparent;
        }

        .ic {
            width: 1em; height: 1em;
            fill: none; stroke: currentColor;
            stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
            vertical-align: -0.125em;
            flex-shrink: 0;
        }

        /* HEADER (warna solid, tanpa gambar eksternal) */
        .header-top {
            background: #326698;
            background-image: linear-gradient(135deg, #3b74ab 0%, #2a5885 100%);
            height: 150px;
            display: flex;
            align-items: flex-start;
            padding: 15px 20px 0 50px;
            color: #fff;
        }
        .header-logo img { height: 110px; width: auto; display: block; }
        .header-text { margin-left: 15px; margin-top: 5px; }
        .header-text b { font-size: 1.3rem; }

        .login-wrapper {
            margin-top: -10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 15px 30px;
            position: relative;
        }

        .welcome-card {
            background: #fff;
            width: 100%;
            max-width: 350px;
            border-radius: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,.06);
            padding: 22px;
            text-align: center;
            margin-bottom: 15px;
        }
        .welcome-card h3 { margin: 0; font-weight: 500; font-size: 1.75rem; line-height: 1.2; color: #212529; }
        .welcome-card p { margin: 0; font-size: .82rem; color: #636e72; }
        .welcome-card p:empty { display: none; }

        .login-card {
            background: #fff;
            width: 100%;
            max-width: 350px;
            border-radius: 35px;
            box-shadow: 0 20px 50px rgba(0,0,0,.1);
            overflow: hidden;
        }
        .login-card-body { padding: 30px 25px; }

        .input-group-custom {
            position: relative;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 50px;
            padding: 6px 20px;
            border: 1px solid #e2e8f0;
            transition: border-color .2s, box-shadow .2s;
            color: #636e72;
        }
        .input-group-custom:focus-within {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,.15);
        }
        .input-group-custom > .ic { margin-right: 12px; font-size: 1.1rem; }

        .form-control {
            display: block;
            width: 100%;
            flex: 1;
            min-width: 0;
            border: none !important;
            outline: none;
            box-shadow: none !important;
            padding: 6px 0;
            background: transparent;
            /* di atas 16px agar iPhone tidak zoom otomatis saat input difokuskan */
            font-size: 1.07rem;
            font-family: inherit;
            color: #334155;
            -webkit-appearance: none;
            appearance: none;
            border-radius: 0;
        }

        .form-control::placeholder { color: #6c757d; opacity: 1; }

        .select-custom select.form-control {
            cursor: pointer;
            text-align: center;
            text-align-last: center;
            padding-right: 24px;
        }
        .select-custom .chev { position: absolute; right: 20px; margin: 0; pointer-events: none; }

        #toggle-password { cursor: pointer; margin: 0 0 0 5px; color: #aaa; padding: 4px; box-sizing: content-box; }

        .btn-login {
            display: block;
            width: 100%;
            height: 46px;
            margin-top: 10px;
            border: none;
            border-radius: 50px;
            background: #007bff;
            color: #fff;
            font-family: inherit;
            font-weight: 700;
            font-size: 1.05rem;
            box-shadow: 0 8px 20px rgba(0,123,255,.15);
            cursor: pointer;
            -webkit-appearance: none;
            appearance: none;
            transition: opacity .2s;
        }
        .btn-login.admin-mode { background: #dc3545; box-shadow: 0 8px 20px rgba(220,53,69,.15); }
        .btn-login:disabled { opacity: .7; cursor: default; }

        .role-link-wrapper { display: flex; justify-content: flex-end; margin-top: 15px; }
        .role-link-wrapper a {
            color: #636e72;
            font-size: .85rem;
            font-weight: 700;
            text-decoration: none;
            padding: 6px 0;
        }
        .role-link-wrapper a:hover { color: #007bff; }
        .role-link-wrapper .ic { margin-right: 4px; }

        .btn-download {
            display: block;
            margin-top: 8px;
            text-align: left;
            font-size: 1.1rem;
            font-weight: 400;
            color: #007bff;
            text-decoration: none;
            padding: 8px 0;
        }

        /* Alert */
        .alert {
            padding: 10px 15px;
            border-radius: 50px;
            font-size: .95rem;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 500;
            border: 1px solid transparent;
        }
        .alert-info    { background: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
        .alert-success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
        .alert-danger  { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .alert-warning { background: #fff3cd; border-color: #ffeeba; color: #856404; }
        #infoMessage:empty { display: none; }
        #infoMessage p { margin: 0 0 6px; }

        .hidden { display: none !important; }

        @media (max-width: 480px) {
            .header-top { padding-left: 20px; height: 140px; }
            .header-logo img { height: 88px; }
            .header-text b { font-size: 1.15rem; }
        }

        /* =====================================================
           DESKTOP / PC SAJA (layar >= 1024px + mouse).
           HP Android/iPhone (termasuk landscape) tidak terpengaruh.
           ===================================================== */
        .desk-title, .desk-toggle { display: none; }

        @media (min-width: 1024px) and (hover: hover) and (pointer: fine) {
            body {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(90deg, #e2e2e2, #c9d6ff);
            }
            .header-top, .welcome-card, .role-link-wrapper { display: none; }

            .login-wrapper {
                position: relative;
                display: block;
                width: 850px;
                height: 550px;
                margin: 0;
                padding: 0;
                background: #fff;
                border-radius: 30px;
                box-shadow: 0 0 30px rgba(0,0,0,.2);
                overflow: hidden;
            }

            /* Form: setengah kanan (Siswa) -> setengah kiri (Admin) */
            .login-card {
                position: absolute;
                top: 0; right: 0;
                width: 50%;
                max-width: none;
                height: 100%;
                border-radius: 0;
                box-shadow: none;
                display: flex;
                align-items: center;
                z-index: 1;
                transition: right .6s ease-in-out 1.2s;
            }
            .login-wrapper.is-admin .login-card { right: 50%; }
            .login-card-body { width: 100%; padding: 30px 40px; }

            .desk-title {
                display: block;
                margin: 0 0 22px;
                text-align: center;
                font-size: 34px;
                font-weight: 700;
                color: #333;
            }

            .input-group-custom {
                background: #eee;
                border: none;
                border-radius: 8px;
                padding: 5px 16px;
                margin-bottom: 20px;
            }
            .input-group-custom:focus-within { box-shadow: 0 0 0 2px #7494ec; }
            .btn-login {
                background: #7494ec;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0,0,0,.1);
            }
            .btn-login.admin-mode { background: #dc3545; }

            /* Panel biru yang bergeser */
            .desk-toggle { display: block; position: absolute; inset: 0; pointer-events: none; }
            .desk-toggle::before {
                content: '';
                position: absolute;
                left: -250%;
                width: 300%;
                height: 100%;
                background: #7494ec;
                border-radius: 150px;
                z-index: 2;
                transition: left 1.8s ease-in-out;
            }
            .login-wrapper.is-admin .desk-toggle::before { left: 50%; }

            .desk-panel {
                position: absolute;
                width: 50%;
                height: 100%;
                padding: 0 30px;
                color: #fff;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
                z-index: 3;
                pointer-events: auto;
                transition: .6s ease-in-out;
            }
            .desk-panel h2 { margin: 0 0 6px; font-size: 32px; font-weight: 700; }
            .desk-panel p  { margin: 0 0 20px; font-size: .9rem; }
            .desk-logo { height: 80px; width: auto; margin-bottom: 14px; }

            .desk-left  { left: 0;     transition-delay: 1.2s; }
            .desk-right { right: -50%; transition-delay: .6s; }
            .login-wrapper.is-admin .desk-left  { left: -50%; transition-delay: .6s; }
            .login-wrapper.is-admin .desk-right { right: 0;   transition-delay: 1.2s; }

            .desk-btn {
                width: 160px;
                height: 46px;
                background: transparent;
                border: 2px solid #fff;
                border-radius: 8px;
                color: #fff;
                font-family: inherit;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
            }
            .desk-btn:hover { background: rgba(255,255,255,.15); }

            /* matikan animasi sementara saat load awal */
            .login-wrapper.no-anim .login-card,
            .login-wrapper.no-anim .desk-panel,
            .login-wrapper.no-anim .desk-toggle::before { transition: none !important; }
        }
    </style>
</head>
<body>

<header class="header-top">
    <div class="header-logo">
        <img src="<?= base_url('assets/img/wall212.png') ?>" alt="Logo" onerror="this.style.display='none'">
    </div>
    <div class="header-text">
        <b>Application</b>
    </div>
</header>

<div class="login-wrapper">

    <div class="welcome-card">
        <h3 id="login-title">Selamat Datang</h3>
        <p id="login-desc"></p>
    </div>

    <div class="login-card">
        <div class="login-card-body">
            <h1 class="desk-title" id="desk-title">Login Siswa</h1>
            <div id="infoMessage"><?= isset($message) ? $message : ''; ?></div>

            <?php
            $options_html = '';
            $is_jadwal_aktif = false;

            try {
                include(APPPATH . 'config/database.php');
                $db_config = $db[$active_group];

                $pdo = new PDO(
                    "mysql:host={$db_config['hostname']};dbname={$db_config['database']};charset=utf8",
                    $db_config['username'],
                    $db_config['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_TIMEOUT => 3, // jangan menggantung kalau DB lambat
                    ]
                );

                $stmt_jadwal = $pdo->prepare("SELECT id_jadwal, id_bank FROM cbt_jadwal WHERE status = '1'");
                $stmt_jadwal->execute();
                $jadwal_list = $stmt_jadwal->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($jadwal_list)) {
                    $is_jadwal_aktif = true;
                    foreach ($jadwal_list as $row) {
                        $id_jadwal = $row['id_jadwal'];
                        $id_bank = $row['id_bank'];
                        $text_tampil = "Ujian Aktif";

                        if (!empty($id_bank)) {
                            $stmt_bank = $pdo->prepare("SELECT * FROM cbt_bank_soal WHERE id_bank = ?");
                            $stmt_bank->execute([$id_bank]);
                            $bank_data = $stmt_bank->fetch(PDO::FETCH_ASSOC);

                            if ($bank_data) {
                                // Cadangan awal: kode soal
                                if (!empty($bank_data['bank_kode'])) {
                                    $text_tampil = $bank_data['bank_kode'];
                                }

                                // Ambil nama mapel lengkap dari tabel mapel lewat bank_mapel_id
                                if (!empty($bank_data['bank_mapel_id'])) {
                                    $tabel_mapel = ['master_mapel', 'mapel', 'cbt_mapel'];
                                    $kolom_id    = ['id_mapel', 'id'];
                                    $kolom_nama  = ['nama_mapel', 'mapel_nama', 'nama', 'mapel'];
                                    $ketemu = false;

                                    foreach ($tabel_mapel as $tb) {
                                        if ($ketemu) break;
                                        foreach ($kolom_id as $kid) {
                                            try {
                                                $stmt_m = $pdo->prepare("SELECT * FROM `$tb` WHERE `$kid` = ? LIMIT 1");
                                                $stmt_m->execute([$bank_data['bank_mapel_id']]);
                                                $m = $stmt_m->fetch(PDO::FETCH_ASSOC);
                                            } catch (Exception $e2) {
                                                continue; // tabel/kolom tidak ada, coba kombinasi lain
                                            }
                                            if ($m) {
                                                foreach ($kolom_nama as $kn) {
                                                    if (!empty($m[$kn])) {
                                                        $text_tampil = $m[$kn];
                                                        $ketemu = true;
                                                        break;
                                                    }
                                                }
                                                if ($ketemu) break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $options_html .= '<option value="' . htmlspecialchars($id_jadwal) . '">' . htmlspecialchars($text_tampil) . '</option>';
                    }
                }
            } catch (Exception $e) {
                $options_html = '<option value="" disabled>Gagal memuat daftar ujian</option>';
            }
            ?>

            <?= form_open("auth/cek_login", array('id' => 'login')); ?>

                <!-- DEFAULT ROLE: SISWA -->
                <input type="hidden" name="role_akses" id="role_akses" value="siswa">

                <div class="input-group-custom">
                    <svg class="ic" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <?= form_input($identity, '', 'required class="form-control" placeholder="Username" autocomplete="off" autocapitalize="none" autocorrect="off" spellcheck="false"'); ?>
                </div>

                <div class="input-group-custom">
                    <svg class="ic" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <?= form_password($password, '', 'required class="form-control" id="password" placeholder="Password"'); ?>
                    <svg id="toggle-password" class="ic" viewBox="0 0 24 24" role="button" aria-label="Tampilkan password">
                        <g id="eye-on"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></g>
                        <g id="eye-off" class="hidden"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></g>
                    </svg>
                </div>

                <!-- DROPDOWN MAPEL: tampil di mode Siswa (jika ada ujian aktif) -->
                <div class="input-group-custom select-custom<?= $is_jadwal_aktif ? '' : ' hidden' ?>" id="mapel-wrapper">
                    <svg class="ic" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    <select name="id_ujian" id="id_ujian" class="form-control"<?= $is_jadwal_aktif ? ' required' : '' ?>>
                        <?php if ($is_jadwal_aktif): ?>
                            <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                            <?= $options_html; ?>
                        <?php else: ?>
                            <option value="" disabled selected>Belum Ada Ujian Aktif</option>
                        <?php endif; ?>
                    </select>
                    <svg class="ic chev" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </div>

                <button type="submit" id="submit" class="btn-login">Login Siswa</button>

                <div class="role-link-wrapper">
                    <a href="#" id="btn-switch-role">
                        <span id="lnk-siswa" class="hidden"><svg class="ic" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>Login Siswa</span>
                        <span id="lnk-admin"><svg class="ic" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Login Admin</span>
                    </a>
                </div>

                 
            <?= form_close(); ?>
        </div>
    </div>

    <!-- Panel biru (hanya tampil di PC) -->
    <div class="desk-toggle" aria-hidden="false">
        <div class="desk-panel desk-left">
            <img class="desk-logo" src="<?= base_url('assets/img/wall212.png') ?>" alt="" onerror="this.style.display='none'">
            <h2>Selamat Datang!</h2>
            <p>Ingin masuk sebagai Admin?</p>
            <button type="button" class="desk-btn" id="desk-to-admin">Login Admin</button>
        </div>
        <div class="desk-panel desk-right">
            <img class="desk-logo" src="<?= base_url('assets/img/wall212.png') ?>" alt="" onerror="this.style.display='none'">
            <h2>Halo, Admin!</h2>
            <p>Bukan admin? Kembali ke siswa.</p>
            <button type="button" class="desk-btn" id="desk-to-siswa">Login Siswa</button>
        </div>
    </div>
</div>

<script>
(function () {
    var currentRole = 'siswa';
    var isJadwalAktifServer = <?= $is_jadwal_aktif ? 'true' : 'false'; ?>;
    var baseUrl = '<?= base_url() ?>';

    function $(id) { return document.getElementById(id); }
    function show(el) { el.classList.remove('hidden'); }
    function hide(el) { el.classList.add('hidden'); }
    function esc(s) {
        return String(s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    var form = $('login'),
        info = $('infoMessage'),
        btn = $('submit'),
        mapelWrap = $('mapel-wrapper'),
        mapelSel = $('id_ujian'),
        roleInput = $('role_akses'),
        pass = $('password');

    function setBtnText() { btn.textContent = currentRole === 'siswa' ? 'Login Siswa' : 'Login'; }

    // Ganti mode Admin <-> Siswa
    $('btn-switch-role').addEventListener('click', function (e) {
        e.preventDefault();
        info.innerHTML = '';

        if (currentRole === 'siswa') {
            currentRole = 'admin';
            roleInput.value = 'admin';
            hide(mapelWrap);
            mapelSel.removeAttribute('required');
            mapelSel.selectedIndex = 0;
            btn.classList.add('admin-mode');
            show($('lnk-siswa')); hide($('lnk-admin'));
        } else {
            currentRole = 'siswa';
            roleInput.value = 'siswa';
            if (isJadwalAktifServer) {
                show(mapelWrap);
                mapelSel.setAttribute('required', 'required');
            } else {
                hide(mapelWrap);
                mapelSel.removeAttribute('required');
            }
            btn.classList.remove('admin-mode');
            hide($('lnk-siswa')); show($('lnk-admin'));
        }
        setBtnText();

        // Tampilan PC: geser panel & ubah judul
        document.querySelector('.login-wrapper').classList.toggle('is-admin', currentRole === 'admin');
        $('desk-title').textContent = currentRole === 'admin' ? 'Login Admin' : 'Login Siswa';
    });

    // Tombol di panel biru (PC) memicu pergantian role yang sama
    $('desk-to-admin').addEventListener('click', function () { if (currentRole !== 'admin') $('btn-switch-role').click(); });
    $('desk-to-siswa').addEventListener('click', function () { if (currentRole !== 'siswa') $('btn-switch-role').click(); });

    // Tampil/sembunyi password
    $('toggle-password').addEventListener('click', function () {
        var isPwd = pass.type === 'password';
        pass.type = isPwd ? 'text' : 'password';
        if (isPwd) { hide($('eye-on')); show($('eye-off')); }
        else { show($('eye-on')); hide($('eye-off')); }
    });

    function serialize(f) {
        var parts = [];
        for (var i = 0; i < f.elements.length; i++) {
            var el = f.elements[i];
            if (!el.name || el.disabled) continue;
            if ((el.type === 'checkbox' || el.type === 'radio') && !el.checked) continue;
            if (el.type === 'submit' || el.type === 'button') continue;
            parts.push(encodeURIComponent(el.name) + '=' + encodeURIComponent(el.value));
        }
        return parts.join('&');
    }

    function done(html) {
        btn.disabled = false;
        setBtnText();
        info.innerHTML = html;
    }

    // Login via AJAX (tanpa jQuery)
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        info.innerHTML = '<div class="alert alert-info">Memeriksa data...</div>';
        btn.disabled = true;
        btn.textContent = 'Tunggu...';

        var xhr = new XMLHttpRequest();
        xhr.open('POST', form.getAttribute('action'), true);
        xhr.timeout = 20000;
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function () {
            var res;
            try { res = JSON.parse(xhr.responseText); }
            catch (err) {
                done('<div class="alert alert-warning">Terjadi kesalahan pada respon server.</div>');
                return;
            }
            if (res.status) {
                info.innerHTML = '<div class="alert alert-success">Login Berhasil</div>';
                window.location.href = baseUrl + res.url;
            } else {
                done('<div class="alert alert-danger">' + esc(res.failed ? res.failed : 'Username atau Password Salah!') + '</div>');
            }
        };
        xhr.onerror = function () {
            done('<div class="alert alert-danger">Gagal terhubung ke server. Cek koneksi Anda.</div>');
        };
        xhr.ontimeout = function () {
            done('<div class="alert alert-danger">Server tidak merespons. Coba lagi.</div>');
        };
        xhr.send(serialize(form));
    });

    // Buka langsung mode Admin jika URL berisi ?role=admin (mis. setelah logout admin)
    var wrap = document.querySelector('.login-wrapper');
    var isDesktop = window.matchMedia('(min-width: 1024px) and (hover: hover) and (pointer: fine)').matches;
    var wantSiswa = /[?&]role=siswa(&|$)/.test(window.location.search);

    // PC/laptop: default Login Admin. HP (Android/iPhone) SELALU mulai dari Login Siswa, ?role=admin diabaikan.
    // Gunakan ?role=siswa untuk memaksa siswa di PC.
    // Animasi dimatikan sementara agar tidak ada geseran saat halaman baru dibuka.
    if (isDesktop && (/[?&]role=admin(&|$)/.test(window.location.search) || !wantSiswa)) {
        wrap.classList.add('no-anim');
        $('btn-switch-role').click();
        requestAnimationFrame(function () {
            requestAnimationFrame(function () { wrap.classList.remove('no-anim'); });
        });
    }
})();
</script>

</body>
</html>